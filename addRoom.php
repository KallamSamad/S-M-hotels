<?php
session_start();
require_once "function.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$db = new SQLite3("SNM.db");
$db->exec("PRAGMA foreign_keys = ON;");



if (isset($_POST["addroom"])) {

addRoom(
    $db,
    $_POST['hotelname'],
    $_POST['roomnum'],
    $_POST['floornum'],
    $_POST['price'],
    $_POST['desc'],
    $_POST['roomtypeid']   
);


    header("Location: viewRoom.php");
    exit();
}

$stmt = $db->prepare("SELECT hotel_ID, hotel_name FROM Hotel");
$resultHotels = $stmt->execute();


$stmt = $db->prepare("SELECT room_type_ID, type_name FROM RoomType");
$resultTypes = $stmt->execute();
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
    <title>Add Room</title>
</head>
<body>

<?php require "nav.php"; ?>

<div class="backcont">
    <a href="index.php" class="backbtn">Back</a>
</div>

<div class="middle">
    <form method="POST" class="addhotel">

        <label>Hotel</label>
        <select name="hotelname" required>
            <?php
            while ($row = $resultHotels->fetchArray(SQLITE3_ASSOC)) {
                echo "<option value='{$row['hotel_ID']}'>{$row['hotel_name']}</option>";
            }
            ?>
        </select>

        <label>Room Number</label>
        <input type="text" name="roomnum" required>

        <label>Floor Number</label>
        <input type="number" name="floornum" required>

        <label>Price</label>
        <input type="number" name="price" required>

        <label>Room Description</label>
        <textarea name="desc" required></textarea>

        <label>Room Type</label>
        <select name="roomtypeid" required>
            <?php
            while ($row = $resultTypes->fetchArray(SQLITE3_ASSOC)) {
                echo "<option value='{$row['room_type_ID']}'>{$row['type_name']}</option>";
            }
            ?>
        </select>

        <button type="submit" name="addroom">Confirm</button>
    </form>
</div>

</body>
</html>
