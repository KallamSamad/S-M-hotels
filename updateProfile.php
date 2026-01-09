<?php
session_start();
require_once 'function.php';

if (!isset($_SESSION['username'])) {
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

$currentId        = $row['id'];
$currentPfp       = $row['profilepic'];
$currentUsername  = $row['username'];
$currentFirstName = $row['first_name'];
$currentMiddle    = $row['middle_name'];
$currentLastName  = $row['last_name'];
$currentEmail     = $row['email'];
$currentphone = $row['phone'];

$result->finalize();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Edit Profile</title>
</head>
<body>

<?php require 'nav.php'; ?>

<?php

?>
<?php
if (isset($_POST["changepfp"])) {

    $id       = $_POST["id"];
    $username = $_POST["username"];
    $fname    = $_POST["firstname"];
    $mname    = $_POST["middlename"];
    $lname    = $_POST["lastname"];
    $email    = $_POST["email"];

    if (!empty($_FILES["profilepic"]["name"])) {
        $targetDir = "uploads/";

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $filename   = uniqid("pfp_") . "_" . basename($_FILES["profilepic"]["name"]);
        $targetFile = $targetDir . $filename;

        if (move_uploaded_file($_FILES["profilepic"]["tmp_name"], $targetFile)) {
            $pfp = $targetFile;
        } else {
            $pfp = $currentPfp;   
        }
    } else {
        $pfp = $currentPfp;       
    }

if ($_SESSION['role'] === 'staff') {
        updateViewStaffProfile($db, $username, $pfp, $email, $fname, $mname, $lname, $id);
        $_SESSION['profilepic'] = $pfp;
        header("Location: viewProfile.php");
        exit();
} else {
        updateViewUserProfile($db, $username, $pfp, $email, $fname, $mname, $lname, $id);
        $_SESSION['profilepic'] = $pfp;
        header("Location: viewProfile.php");
        exit();
}

}
?>
<div class="backcont">
    <a href="index.php" class="backbtn">Back </a>
</div>
<div class="middle">
<form class="banner" method="POST" enctype="multipart/form-data">

    <div class="pfpandusername" style="align-items:center;">
        <div>
            <label>Current Profile Picture</label> <br>
            <img class="pfp" src="<?= htmlspecialchars($currentPfp) ?>"
                 alt="Profile Picture" height="120" width="120">
        </div>

        <div style="margin-left:20px;">
            <label>Choose New Picture</label>
            <input type="file" name="profilepic" accept="image/*" class="form-control">
        </div>
    </div>

    <input type="hidden" name="id" value="<?= htmlspecialchars($currentId) ?>">

    <label>Username</label>
    <input type="text" name="username" value="<?= htmlspecialchars($currentUsername) ?>" readonly>

    <label>First Name</label>
    <input type="text" name="firstname" value="<?= htmlspecialchars($currentFirstName) ?>" required>

    <label>Middle Name</label>
    <input type="text" name="middlename" value="<?= htmlspecialchars($currentMiddle) ?>">

    <label>Last Name</label>
    <input type="text" name="lastname" value="<?= htmlspecialchars($currentLastName) ?>" required>

    <label>Email</label>
    <input type="email" name="email" value="<?= htmlspecialchars($currentEmail) ?>" required>
    <label>Phone Number</label>
    <input type="text" value="<?= htmlspecialchars($currentphone) ?>" required readonly>

    <button type="submit" class="changebtn" name="changepfp" style="margin-top:10px;">
        Save Changes
    </button>

</form>
</div>

<div class="footer">
    © 2025 S&M Hotels. All rights reserved.
</div>

</body>
</html>
