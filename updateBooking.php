<?php
session_start();
require_once "function.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$db = new SQLite3("SNM.db");
$db->exec("PRAGMA foreign_keys = ON;");


if (!isset($_POST['booking_ID'])) {
    header("Location: staffBookings.php");
    exit();
}

$bookingID = $_POST['booking_ID'];

if (isset($_POST['updatebooking'])) {

    updatefrombooking(
        $db,
        $_POST['booking_ID'],
        $_POST['check_in'],
        $_POST['check_out'],
        $_POST['amount'],
        $_POST['payment_method']
    );

   if ($_SESSION['role'] === 'staff') {
    header("Location: staffBookings.php?updated=1");
} else {
    header("Location: userBookings.php?updated=1");
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

exit();


}

$stmt = $db->prepare("
    SELECT Booking.*, Room.price_per_night
    FROM Booking
    INNER JOIN Room ON Booking.room_ID = Room.room_ID
    WHERE booking_ID = :id
");
$stmt->bindValue(":id", $bookingID, SQLITE3_INTEGER);
$booking = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

$price = $booking["price_per_night"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Update Booking</title>
</head>
<body>

<?php require "nav.php"; ?>

<div class="backcont">
    <label>Payment Method</label>
    <select name="payment_method">
        <option <?= $booking['payment_method'] == 'card' ? 'selected' : '' ?>>card</option>
        <option <?= $booking['payment_method'] == 'cash' ? 'selected' : '' ?>>cash</option>
        <option <?= $booking['payment_method'] == 'paypal' ? 'selected' : '' ?>>paypal</option>
    </select>

    <a href="staffBookings.php" class="backbtn">Back</a>
</div>

<div class="middle">

<h2>Update Booking</h2>

<form method="POST" class="addhotel">
    <input type="hidden" name="booking_ID" value="<?= $bookingID ?>">

    <label>Check-in Date</label>
    <input type="date" name="check_in" value="<?= $booking['check_in_date'] ?>" required>

    <label>Check-out Date</label>
    <input type="date" name="check_out" value="<?= $booking['check_out_date'] ?>" required>

    <label>Total Amount (£)</label>
    <input type="number" step="0.01" id="amount" name="amount" value="<?= $booking['amount_paid'] ?>" required>

    <button type="submit" name="updatebooking">Confirm</button>
</form>

</div>

<script>
const price = <?= $price ?>;
function calculateAmount() {
    let checkIn = new Date(document.querySelector("[name='check_in']").value);
    let checkOut = new Date(document.querySelector("[name='check_out']").value);

    if (checkOut > checkIn) {
        let nights = (checkOut - checkIn) / 86400000;
        document.getElementById("amount").value = (nights * price).toFixed(2);
    }
}

document.querySelector("[name='check_in']").onchange = calculateAmount;
document.querySelector("[name='check_out']").onchange = calculateAmount;
</script>

</body>
</html>
