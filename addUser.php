<?php
session_start();
require_once 'function.php';

$db = new SQLite3("SNM.db");
$db->exec("PRAGMA foreign_keys = ON;");

    if (isset($_POST['adduser'])) {
        addUser($db,$_POST['username'], $_POST['email'],$_POST['phone'],$_POST['password'],$_POST['fname'],$_POST['mname'],$_POST['lname']);
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
    <title>Change Password</title>
</head>
<body>

<?php require 'nav.php'; ?>

<div class="backcont">
<a href="index.php" class="backbtn">Back</a>
</div>

<div class="middle">
<form class="banner" method="POST" autocomplete="off">
    
<label>Username</label>
<input type="text" name="username" required autocomplete="off">

<label>Email</label>
<input type="email" name="email" required>

<label>First Name</label>
<input type="text" name="fname" required>

<label>Middle Name</label>
<input type="text" name="mname" required>
<label>Last Name</label>
<input type="text" name="lname" required>

<label>Phone</label>
<input type="tel" name="phone" required>

<label>Password</label>
<input type="password" name="password" required>

<button type="submit" class="changebtn" name="adduser" style="margin-top:10px;">
    Create Account
</button>

</form>
</div>

</body>
</html>
