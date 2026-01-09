<?php
session_start();
require_once 'function.php';

 if (!isset($_SESSION['username'])) {
    $_SESSION['redirect'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit();
}


$db = new SQLite3("SNM.db");
$db->exec("PRAGMA foreign_keys = ON;");
if ($_SESSION['role'] === 'staff') {
    $stmt = $db->prepare(queryViewStaffProfile());
} else {
    $stmt = $db->prepare(queryViewUserProfile());
}
 
$stmt->bindValue(":id", $_SESSION['id']);


$result = $stmt->execute();
$row = $result->fetchArray(SQLITE3_ASSOC);


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

<?php if (!isset($_SESSION['username'])): ?>

    <div class="hamburger">Sign In ☰</div>

    <div class="signin">
        <form method="POST" action="index.php">
            <label for="username" style="color:#ebddd7;">Username</label>
            <input type="text" name="username" required autocomplete="off">

            <label for="password" style="color:#ebddd7;">Password</label>
            <input type="password" name="password" minlength="8" required>

            <input type="submit" value="submit">
        </form>

        <p style="color:#ebddd7;">Not got an account? <a class="signup" href="#">Sign up</a></p>
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

<?php if ($row): ?>
<form class="banner">
     <div class="pfpandusername">   
    <label>Username</label>
    <input type="text" value="<?= htmlspecialchars($row['username']) ?>" readonly>

    <label>Profile Picture</label>
    <img class="pfp" src="<?= htmlspecialchars($row['profilepic']) ?>" 
         alt="Profile Picture" height="100px" width="100px"></div> 

    <label>First Name</label>
    <input type="text" value="<?= htmlspecialchars($row['first_name']) ?>" readonly>

    <label>Middle Name</label>
    <input type="text" value="<?= htmlspecialchars($row['middle_name']) ?>" readonly>

    <label>Last Name</label>
    <input type="text" value="<?= htmlspecialchars($row['last_name']) ?>" readonly>

    <label>Phone Number</label>
    <input type="text" value="<?= htmlspecialchars($row['phone']) ?>" readonly>

    <label>Email</label>
    <input type="email" value="<?= htmlspecialchars($row['email']) ?>" readonly>

    <label>Account Type</label>
    <input type="text" value="<?= htmlspecialchars($row['account_type']) ?>" readonly>

</form>

  

<?php else: ?>

    <p>Error: Could not load profile.</p>

<?php endif; ?>

</div>
  <div class="footer">
  © 2025 S&M Hotels. All rights reserved.
    </div>
</body>
 <script src="script.js" defer></script>
</html>