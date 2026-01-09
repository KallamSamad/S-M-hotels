<?php
session_start();
require_once "function.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$db = new SQLite3("SNM.db");
$db->exec("PRAGMA foreign_keys = ON;");

 if (!isset($_POST['room_ID'])) {
    header("Location: viewRoom.php");
    exit();
}

$roomID = $_POST['room_ID'];

 if (isset($_POST['updateroom'])) {

    updatefromroom(
        $db,
        $_POST['room_ID'],
        $_POST['room_number'],
        $_POST['floor_number'],
        $_POST['price'],
        $_POST['status'],
        $_POST['description']
    );

    header("Location: viewRoom.php?updated=1");
    exit();
}

$stmt = $db->prepare("SELECT * FROM Room WHERE room_ID = :id");
$stmt->bindValue(":id", $roomID, SQLITE3_INTEGER);
$room = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Update Room</title>
</head>
<body>

<?php require "nav.php"; ?>

<div class="backcont">
    <a href="viewRoom.php" class="backbtn">Back</a>
</div>

<div class="middle">

<form method="POST" class="addhotel">
    <input type="hidden" name="room_ID" value="<?= $roomID ?>">

    <label>Room Number</label>
    <input type="text" name="room_number" value="<?= $room['room_number'] ?>" required>

    <label>Floor Number</label>
    <input type="number" name="floor_number" value="<?= $room['floor_number'] ?>" required>

    <label>Price Per Night</label>
    <input type="number" step="0.01" name="price" value="<?= $room['price_per_night'] ?>" required>

    <label>Status</label>
    <select name="status" required>
        <option <?= $room['status'] === 'Available' ? 'selected' : '' ?>>Available</option>
        <option <?= $room['status'] === 'Occupied' ? 'selected' : '' ?>>Occupied</option>
        <option <?= $room['status'] === 'Booked' ? 'selected' : '' ?>>Booked</option>
        <option <?= $room['status'] === 'Maintenance' ? 'selected' : '' ?>>Maintenance</option>
        <option <?= $room['status'] === 'Cleaning' ? 'selected' : '' ?>>Cleaning</option>
    </select>

    <label>Description</label>
    <input type="text" name="description" value="<?= $room['description'] ?>">

    <button type="submit" name="updateroom">Confirm</button>
</form>

</div>

</body>
</html>
