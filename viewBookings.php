<?php
session_start();
require_once 'function.php';

if (!isset($_SESSION['username'])) {
    $_SESSION['redirect'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit();
}

$db = new SQLite3(filename: "SNM.db");

$warning = "";

 if (isset($_POST['startCancel'])) {

    $booking_ID = $_POST['booking_ID'];
    $user_ID = $_SESSION['id'];

     if (!isset($_SESSION['pending_cancel'])) {

        $_SESSION['pending_cancel'] = $booking_ID;
        $warning = "Click CANCEL again to confirm.";
    }
     else if ($_SESSION['pending_cancel'] == $booking_ID) {

        cancelBooking($db, $booking_ID, $user_ID);
        unset($_SESSION['pending_cancel']);

        header("Location: viewBookings.php");
        exit();
    }
     else {
        $_SESSION['pending_cancel'] = $booking_ID;
        $warning = "Click CANCEL again to confirm.";
    }
}

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
    <title>My Bookings</title>
</head>
<body>

<div class="header">
    <div>
        <img class="logo" src="images/logo.webp" alt="S&M logo">
    </div>

    <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active"><img src="images/hotel1.webp" class="d-block w-100"></div>
            <div class="carousel-item"><img src="images/Hotelin.webp" class="d-block w-100"></div>
            <div class="carousel-item"><img src="images/hotelroom2.webp" class="d-block w-100"></div>
            <div class="carousel-item"><img src="images/hotelroom.webp" class="d-block w-100"></div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    <div class="topright">
        <?php require 'dashboard.php'; ?>
    </div>
</div>

<?php require_once 'nav.php'; ?>

<div class="middle">
    <h3>Here are your Bookings, <b><?= $_SESSION['username'] ?></b></h3>

    <?php if (!empty($warning)): ?>
        <p style="color:red; font-weight:bold;"><?= $warning ?></p>
    <?php endif; ?>

<?php
$userID = $_SESSION['id'];

$stmt = queryBookingList($db,$records_per_page,$offset);
$stmt->bindValue(":user_ID", $userID, SQLITE3_INTEGER);
$result = $stmt->execute();

echo "<table>";

echo "
<thead>
<tr class='bottomroom'>
    <td>Hotel Name</td>
    <td>Room Number</td>
    <td>Price Per Night</td>
    <td>Room Type</td>
    <td>Check-In</td>
    <td>Check-Out</td>
    <td>Nights</td>
    <td>Booking Date</td>
    <td>Status</td>
    <td>Amount Paid</td>
    <td>Payment Status</td>
    <td>Method</td>
    <td>Cancel</td>
    <td>Feedback</td>
</tr>
</thead>
";

while ($row = $result->fetchArray(SQLITE3_ASSOC)) {

    $statusClass = '';

    if ($row['status'] === 'Confirmed') {
        $statusClass = 'status-confirmed';
    } 
    else if ($row['status'] === 'Cancelled') {
        $statusClass = 'status-cancelled';
    } 
    else if ($row['status'] === 'Pending') {
        $statusClass = 'status-pending';
    }

    echo "
    <tbody>
        <tr class='$statusClass'>
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
                <form method='POST' action='viewBookings.php'>
                    <input type='hidden' name='booking_ID' value='{$row['booking_ID']}'>
                    <button type='submit' name='startCancel'>Cancel</button>
                </form>
            </td>
            <td>
                <form method='POST' action='addFeedback.php'>
                    <input type='hidden' name='booking_ID' value='{$row['booking_ID']}'>
                    <button type='submit'>Feedback</button>
                    
                </form>
            </td>

            </td>
        </tr>
    </tbody>
    ";
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

<div class="footer">© 2025 S&M Hotels. All rights reserved.</div>

</body>
<script src="script.js" defer></script>
</html>
