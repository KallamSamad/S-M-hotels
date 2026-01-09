<?php
session_start();
require_once 'function.php';

$db = new SQLite3("SNM.db");
$db->exec("PRAGMA foreign_keys = ON;");



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

   $stmt = $db->prepare("SELECT username, password, profilepic, role, 'staff' AS user_type 
                      FROM Staff 
                      WHERE username=:username AND password=:password");

    $stmt->bindValue(":username", $username);
    $stmt->bindValue(":password", $password);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);

    if (!$row) {
    $stmt = $db->prepare("SELECT username, password, profilepic, 'guest' AS role, 'user' AS user_type 
                      FROM User 
                      WHERE username=:username AND password=:password");
        $stmt->bindValue(":username", $username);
        $stmt->bindValue(":password", $password);
        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);
    }

    if ($row) {
        $_SESSION['username'] = $row['username'];
        $_SESSION['profilepic'] = $row['profilepic'];
        $_SESSION['user_type'] = $row['user_type'];  
        $_SESSION['role'] = $row['role'];           


        
          $goBack = $_SESSION['redirect'] ?? 'index.php';
          unset($_SESSION['redirect']); 
          header("Location: $goBack");
          exit();

    } else {
        $error = "Login unsuccessful. Please check your credentials.";
    }
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
<h1 class="headerindex">Mission Statement</h1>
  <p>Here at S&M Hotels we make it our mission for you to have an exceptional stay. We have a wide range of rooms available all around the world, making it as easy as possible for you to find the right room for you.</p>
  <p>S&M Hotels started in 2025, inspired by the demand for premium and affordable hotels being accessible to anyone all over the world. Whether you are in the UK or the US, we strive to have a room for you.</p>  
    


  <h2>Special Offer</h2><p>Students and NHS workers can enjoy an exclusive <b>50% discount</b> on all room bookings.</p>

  <h2>Why Choose Us?</h2>
    <p>We combine comfort, convenience, and modern design to ensure your stay is memorable. 
       Our team is committed to excellent customer service, clean and well-maintained rooms, 
       and providing value you can trust.</p>

<div class="hero-container">
    <img src="images/hero.jpg" class="hero-image" alt="Hotel Hero Image">
</div>
  <h2>Contact Us</h2>
<p>Please Contact Us if you have any inquries</p>
<p><b>Email</b> : S&M-Hotels@mail.co.uk</p>
<p><b>Phone</b>: 0114 4386079</p>

</div>
  <div class="footer">
  © 2025 S&M Hotels. All rights reserved.
    </div>
</body>
 <script src="script.js" defer></script>
</html>