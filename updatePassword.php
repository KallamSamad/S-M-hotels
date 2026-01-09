<?php
session_start();
require_once 'function.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}


$db = new SQLite3("SNM.db");
$db->exec("PRAGMA foreign_keys = ON;");
$id = $_SESSION['id'];

$error = "";
$success = "";

 if (isset($_POST["changepassword"])) {

    $oldpw  = $_POST["oldpw"];
    $newpw  = $_POST["newpw"];
    $newpw2 = $_POST["newpw2"];

     if ($_SESSION['role'] === 'staff') {
        $oldPasswordDB = oldStaffPassword($db, $id);
    } else {
        $oldPasswordDB = oldUserPassword($db, $id);
    }

     if ($oldpw !== $oldPasswordDB) {
        $error = "Old password is incorrect.";
    }
     elseif ($newpw !== $newpw2) {
        $error = "New passwords do not match.";
    }
     else {
        if ($_SESSION['role'] === 'staff') {
            updateStaffPassword($db, $id, $newpw);
        } else {
            updateUserPassword($db, $id, $newpw);
        }

        $success = "Password updated successfully!";
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
    <title>Change Password</title>
</head>
<body>

<?php require 'nav.php'; ?>

<div class="backcont">
<a href="index.php" class="backbtn">Back</a>
</div>

<div class="middle">
<form class="banner" method="POST">

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">

    <label>Old Password</label>
    <input type="password" name="oldpw" required>

    <label>New Password</label>
    <input type="password" name="newpw" required>

    <label>Confirm New Password</label>
    <input type="password" name="newpw2" required>

    <button type="submit" class="changebtn" name="changepassword" style="margin-top:10px;">
        Change Password
    </button>

</form>
</div>

</body>
</html>
