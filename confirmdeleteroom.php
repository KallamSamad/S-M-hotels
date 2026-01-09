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

 if (isset($_POST["delete"])) {

    deletefromroom($db, $roomID);

    header("Location: viewRoom.php?deleted=1");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Delete Room</title>
</head>
<body>

<?php require "nav.php"; ?>

<div class="middle">

    <h2>Are you sure you want to delete this room?</h2>

    <form method="POST" action="confirmdeleteroom.php">
        <input type="hidden" name="room_ID" value="<?= $roomID ?>">

        <button type="submit" name="delete" class="btn btn-danger">Delete</button>
        <a href="viewRoom.php" class="btn btn-secondary">Cancel</a>
    </form>

</div>

</body>
</html>
