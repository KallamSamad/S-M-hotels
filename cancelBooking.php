<?php
session_start();
require_once 'function.php';
$username=$_SESSION['username'] ;

if (!isset($_SESSION['username'])) {
    $_SESSION['redirect'] = $_SERVER['REQUEST_URI'];  
    header("Location: login.php");
    exit();
}


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

<?php if (!isset($_SESSION['username'])): 
  ?>
  <div class="hamburger">Sign In ☰</div>
  <div class="signin">
    <?php if (isset($_GET['logout']) && $_GET['logout'] === 'success'): ?>
      <p style="color:lime;">You have been logged out successfully.</p>
    <?php endif; ?>

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
  <h3>Here are your Bookings, <b><?php echo $username?></b></h3>
    <?php
    $db = new SQLite3("SNM.db");
$db->exec("PRAGMA foreign_keys = ON;");

    $userID = $_SESSION['id'];


      $stmt=$db->prepare(queryBookingList($db,$records_per_page,$offset));
      $stmt->bindValue(":user_ID", $userID, SQLITE3_INTEGER);
      $result = $stmt->execute();
    
    echo" <table>";
    echo "
        <thead> <tr>
            </tr>
            <tr class='bottomroom'>
            <td>Hotel Name</td>
            <td>Room Number</td>
            <td>Price Per Night</td>
            <td>Room type</td>
            <td>Check In Date</td>
            <td>Check Out Date</td>
            <td>Nights</td>
            <td>Booking Date</td>
            <td>Booking Status</td>
            <td>Amount Paid</td>
            <td>Payment Status</td>
            <td>Payment Method</td>
            <td>Actions</td>
            </tr>
    </thead>";
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {

    echo "
    <tbody>
        <tr>
            <td>{$row['hotel_name']}</td>
            <td>{$row['room_number']}</td>
            <td>£{$row['price_per_night']}</td>
            <td>{$row['type_name']}</td>

            <td>{$row['check_in_date']}</td>
            <td>{$row['check_out_date']}</td>

            <td>{$row['nights']}</td>

            <td>{$row['booking_date']}</td>
            <td>{$row['status']}</td>

            <td>£{$row['amount_paid']}</td>
            <td>{$row['payment_status']}</td>
            <td>{$row['payment_method']}</td>

            <td>
                <form method='POST' action='cancelBooking.php'>
                    <input type='hidden' name='cancel_booking' value='{$row['booking_ID']}'>
                    <button type='submit' class='cancelbtn'>Cancel</button>
                </form>
            </td>
        </tr>
    </tbody>
    ";
}

echo"</table>";
        ?>
</div>
    <div class="footer">            
    © 2025 S&M Hotels. All rights reserved.
    </div>
</body>
 <script src="script.js" defer></script>
</html>