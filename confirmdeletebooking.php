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

 if (isset($_POST["delete"])) {

    deletefrombooking($db, $bookingID);

    header("Location: staffBookings.php?deleted=1");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Delete Booking</title>
</head>
<body>

<?php require "nav.php"; ?>

<div class="backcont">
    <a href="viewBookings.php" class="backbtn">Back</a>
</div>

<div class="middle">

    <p>Are you sure you want to delete this booking?</p>

    <form method="POST" action="confirmdeletebooking.php">
        <input type="hidden" name="booking_ID" value="<?= $bookingID ?>">
        <button type="submit" name="delete" class="btn btn-danger">Delete</button>
        <a href="staffBookings.php" class="btn btn-secondary">Cancel</a>
    </form>

</div>

</body>
</html>
