<?php
session_start();
require_once 'function.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$db = new SQLite3('SNM.db');
$db->exec('PRAGMA foreign_keys = ON;');

if (!isset($_POST['feedback_id'])) {
    header('Location: viewFeedback.php');
    exit();
}

$feedbackID = $_POST['feedback_id'];

if (isset($_POST['delete'])) {
    deleteFeedback($db, $feedbackID);
    header('Location: viewFeedback.php?deleted=1');
    exit();
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Delete feedback</title>
</head>
<body>

<?php require "nav.php"; ?>

<div class="backcont">
    <a href="index.php" class="backbtn">Back</a>
</div>

<div class="middle">

<p>Are you sure you want to delete this feedback?</p>

<form method="POST" action="confirmDeleteFeedback.php">
    <input type="hidden" name="feedback_id" value="<?= $feedbackID ?>">
    
    <button type="submit" name="delete" class="btn btn-danger">Delete</button>

    <a href="viewFeedback.php" class="btn btn-secondary ms-2">Cancel</a>
</form>

</div>
</body>
</html>

