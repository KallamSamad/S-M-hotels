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
    echo "Error: No booking selected.";
    exit();
}

$booking_ID = $_POST['booking_ID'];

 
$stmt = $db->prepare("
    SELECT Booking.booking_ID, Hotel.hotel_name, Room.room_number
    FROM Booking
    INNER JOIN Room ON Booking.room_ID = Room.room_ID
    INNER JOIN Hotel ON Room.hotel_ID = Hotel.hotel_ID
    WHERE Booking.booking_ID = :booking_ID
    AND Booking.user_ID = :user_ID
");

$stmt->bindValue(":booking_ID", $booking_ID, SQLITE3_INTEGER);
$stmt->bindValue(":user_ID", $_SESSION['id'], SQLITE3_INTEGER);

$booking = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

 
if (isset($_POST['submitFeedback'])) {
    $rating   = $_POST['rating'];
    $comments = $_POST['comments'];

    addFeedback($db, $booking_ID, $rating, $comments);

    header("Location: viewFeedback.php");
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
    <title>S&M Hotels</title>
</head>
<?php require_once "nav.php";?>
<div class="backcont">
<a href="index.php" class="backbtn">Back</a>
</div>
<div class="middle">
    <?php
if (!$booking) {
    echo "Staff can't add feedback.";
    exit();
}?>

<body>
    <?php if ($_SESSION['role'] === 'staff') {
    die("Staff cannot add Feedback.");
}?>
<h2>Leave Feedback for Room <?= $booking['room_number'] ?> at <?= $booking['hotel_name'] ?></h2>

<form method="POST" class="addfeedback">

    <input type="hidden" name="booking_ID" value="<?= $booking_ID ?>">

    <label>Rating (1–5):</label>
    <select name="rating" required>
        <option value="1">1 ★</option>
        <option value="2">2 ★★</option>
        <option value="3">3 ★★★</option>
        <option value="4">4 ★★★★</option>
        <option value="5">5 ★★★★★</option>
    </select>

    <label>Comments:</label>
    <textarea name="comments" required></textarea>

    <button type="submit" name="submitFeedback">Submit</button>
</form>
</div>
</body>
</html>
