<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    return;
}

$role = $_SESSION['role'] ?? 'guest';
?>

<div class="hamburger">
  <div class="profile-info">
    <span>
      Welcome <?= htmlspecialchars($_SESSION['username']) ?>
      (<?= htmlspecialchars($role) ?>)
    </span>

    <?php if (!empty($_SESSION['profilepic'])): ?>
      <img src="<?= htmlspecialchars($_SESSION['profilepic']) ?>"
           class="pfp"
           alt="Profile Picture"
           width="50" height="50"
           style="border-radius:50%;">
    <?php endif; ?>
  </div>
</div>

<div class="signin account-options">
  <ul>
    <li><a href="viewProfile.php">View Profile</a></li>
    <li><a href="updateProfile.php">Edit Profile</a></li>
    <li><a href="updatePassword.php">Change Password</a></li>
    <li><a href="logout.php">Logout</a></li>
  </ul>
</div>
