<?php
session_start();
require_once 'function.php';

if (!isset($_SESSION['username'])) {
    $_SESSION['redirect'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] !== 'staff') {
    header("Location: userBookings.php");
    exit();
}

    $db = new SQLite3("SNM.db");
$db->exec("PRAGMA foreign_keys = ON;");
$records_per_page=4;
$current_page=isset($_GET['page'])?intval($_GET['page']):1;
if ($current_page<1) $current_page= 1;
$offset=($current_page-1)*$records_per_page;
$total_query="SELECT COUNT(*) as total FROM Hotel";

$total_result=$db->query($total_query);
$total_row=$total_result->fetchArray((SQLITE3_ASSOC));

$total_records=$total_row["total"];
$total_pages=ceil($total_records/$records_per_page);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="header">
        <div>
            <img class="logo" src="images/logo.webp" alt="S&M logo">
        </div>
        <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
            <img src="images/hotel1.webp" class="d-block w-100" alt="Hotel outside">
            </div>
            <div class="carousel-item">
            <img src="images/Hotelin.webp" class="d-block w-100" alt="Hotel inside">
            </div>
            <div class="carousel-item">
            <img src="images/hotelroom2.webp" class="d-block w-100" alt="Hotel inside">
            </div>
            <div class="carousel-item">
            <img src="images/hotelroom.webp" class="d-block w-100" alt="Hotel Room">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
        </div>
        <div class="topright">

<?php if (!isset($_SESSION['username'])): ?>

  <div class="hamburger">Sign In ☰</div>
  <div class="signin">


    <form method="POST" action="index.php">
      <label for="username" style="color: #ebddd7;">Username</label>
      <input type="text" id="username" name="username" required autocomplete="off">

      <label for="password" style="color: #ebddd7;">Password</label>
      <input type="password" id="password" name="password" minlength="8" required>

      <input type="submit" name="submit" value="submit">
    </form>

    <p style="color: #ebddd7;">Not got an Account?<a class="signup" href="#"> Sign up</a></p>
  </div>

<?php else: ?>

  <?php require 'dashboard.php'; ?>

<?php endif; ?>

</div>

    </div>

    <?php
    require_once 'nav.php';
    ?>

<div class="middle">
    <h1>Bookings</h1>
    <?php
 



      $stmt=queryFetchBooking($db,$records_per_page,$offset);
      $result = $stmt->execute();
    
    echo" <table>";
    echo "
    <thead> <tr>
            </tr>
            <tr class='bottomroom'>
            <td>Room Number</td>
            <td>Name</td>
            <td>Booking Date</td>
            <td>Check In Date</td>
            <td>Check Out Date</td>
            <td>Status</td>
            <td>Payment</td>
            <td>Payment Date</td>
            <td>Payment Status</td>
            <td>Payment Method</td>
            <td>Update</td>
            <td>Delete</td>
            <td>Feeback</td>
            </tr>
    </thead>";
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $roomnum = $row["room_number"];
      $name= $row["name"];
      $bookdate= $row["booking_date"];
      $checkindate= $row["check_in_date"];
      $checkoutdate=$row['check_out_date'];
      $status=$row['status'];
      $amountpaid=$row['amount_paid'];
      $payday=$row['payment_date'];
      $paystay=$row['payment_status'];
      $paymeth=$row['payment_method'];

      echo "
            <tbody>
              <td>$roomnum</td>
              <td>$name</td>
              <td>$bookdate</td>
              <td>$checkindate</td>
              <td>$checkoutdate</td>
              <td>$status</td>
              <td>£$amountpaid</td>
              <td>$payday</td>
              <td>$paystay</td>
              <td>$paymeth</td>
              <td>
              <form method='POST' action='updatebooking.php'>
                  <input type='hidden' name='booking_ID' value='" . $row['booking_ID'] . "'>
                  <button type='submit' name='askupdate'>Update</button>
              </form>
          </td>

          <td>
              <form method='POST' action='confirmdeletebooking.php'>
                  <input type='hidden' name='booking_ID' value='" . $row['booking_ID'] . "'>
                  <button type='submit' name='askdelete'>Delete</button>
              </form>
          </td>

              <td><form method='POST' action='addFeedback.php'>
              <input type='hidden' name='booking_ID' value='{$row['booking_ID']}'><button type='submit' name='book'>Feedback</button></form></td>

            </tbody>";
           }
        echo "</table>";
          echo"<div class='pagination'>";
        if ($current_page > 1) {
          $prev_page = $current_page-1;
          echo "<a href='?page=$prev_page'>Previous</a>";
        }
          for($i=1;$i<=$total_pages;$i++){
            if($i==$current_page){
              echo "<strong>$i</strong>";
          }else{
                echo "<a href='?page=$i'>$i</a>";
            }

          }
        
          if ($current_page<$total_pages){
            $next_page=$current_page+1;
            echo "<a href='?page=$next_page'>Next</a>";
          }

          echo "</div>";
        $db->close();
        ?>
</div>
    <div class="footer">            
    © 2025 S&M Hotels. All rights reserved.
    </div>
</body>
 <script src="script.js" defer></script>
</html>