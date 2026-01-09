

<?php
session_start();
require_once 'function.php';
/* I declare that no AI has been used in this assignmnet and have used all tools given to me in lessons and other sources on the web such as w3schools*/

$db = new SQLite3("SNM.db");
$db->exec("PRAGMA foreign_keys = ON;");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

     $stmt = $db->prepare("
        SELECT 
            staff_ID AS id,
            username,
            password,
            profilepic,
            'staff' AS role
        FROM Staff
        WHERE username = :username AND password = :password
    ");
    $stmt->bindValue(":username", $username, SQLITE3_TEXT);
    $stmt->bindValue(":password", $password, SQLITE3_TEXT);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);

     if (!$row) {
        $stmt = $db->prepare("
            SELECT 
                user_ID AS id,
                username,
                password,
                profilepic,
                'user' AS role
            FROM User
            WHERE username = :username AND password = :password
        ");
        $stmt->bindValue(":username", $username, SQLITE3_TEXT);
        $stmt->bindValue(":password", $password, SQLITE3_TEXT);
        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);
    }

     if ($row) {

        $_SESSION['id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];
        $_SESSION['profilepic'] = $row['profilepic'];

         if (isset($_SESSION['redirect'])) {
            $redirect = $_SESSION['redirect'];
            unset($_SESSION['redirect']);
            header("Location: $redirect");
        } else {
            if ($row['role'] === 'staff') {
                header("Location: staffBookings.php");
            } else {
                header("Location: userBookings.php");
            }
        }
        exit();
    }
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

        <p style="color:#ebddd7;">Not got an account? <a class="signup" href="addUser.php">Sign up</a></p>
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
  <h1 class="headerindex">Welcome to S&M Hotels.</h1>
  <p>Book premium, top quality rooms from the best hotels around the world! What are you waiting for? <a class="signup" href="userBookings.php" >Book now!</a></p>
  <h2 class="mt-5">What Our Guests Say</h2>
<?php

      $stmt=queryFetchReview($db,$records_per_page,$offset);
      $result = $stmt->execute();
        $reviews = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $reviews[] = $row;
}

?>
<?php if (count($reviews) === 0): ?>

    <p>No reviews yet — be the first to leave feedback!</p>

<?php else: ?>
<div id="reviewCarousel" class="carousel slide" data-bs-ride="carousel">
  
  <div class="carousel-inner">

    <?php foreach ($reviews as $index => $review): ?>

      <?php
      $stars = str_repeat("⭐", (int)$review['rating']);
      ?>

      <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
        <div class="p-4 bg-dark text-white rounded" style="max-width: 500px;">
          <h5><?= $stars ?></h5>
          <p>"<?= htmlspecialchars($review['comments']) ?>"</p>
          <small>Submitted on: <?= $review['submitted_at'] ?></small>
          <small> -  <?= $review['first_name'] ?></small>
        </div>
      </div>

    <?php endforeach; ?>

  </div>

  <button class="carousel-control-prev" type="button" data-bs-target="#reviewCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>

  <button class="carousel-control-next" type="button" data-bs-target="#reviewCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>

</div>

<?php endif; ?>

</div>
  <div class="footer">
  © 2025 S&M Hotels. All rights reserved.
    </div>
</body>
 <script src="script.js" defer></script>
</html>