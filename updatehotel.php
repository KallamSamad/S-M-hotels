<?php
session_start();
require_once "function.php";
 

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}


$db = new SQLite3("SNM.db");
$db->exec("PRAGMA foreign_keys = ON;");

$hotelID= $_POST['hotel_ID'];

if (isset($_POST['updatehotel'])) {
 updatefromhotel($db,$_POST['hotel_ID'],$_POST['hotel_name'],$_POST['address'], $_POST['city'],$_POST['postcode'],$_POST['phone']  );
 echo"Sucessfully updated the hotel"; 
 header("Location: viewHotel.php");


}

$stmt = $db->prepare("SELECT * FROM Hotel WHERE hotel_ID = :id");
$stmt->bindValue(":id", $hotelID, SQLITE3_INTEGER);
$hotel = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

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
<body>

<?php require "nav.php"; ?>
<div class="backcont">
<a href="index.php" class="backbtn">Back</a>
</div>
<div class="middle">
    
 
 
<form method="POST" class="addhotel">
    <input type="hidden" name="hotel_ID" value="<?= $hotelID ?>">

    <label>Hotel Name</label>
    <input type="text" name="hotel_name" value="<?= $hotel['hotel_name'] ?>" required>

    <label>Address</label>
    <input type="text" name="address" value="<?= $hotel['hotel_address'] ?>" required>

    <label>City</label>
    <input type="text" name="city" value="<?= $hotel['city'] ?>" required>

    <label>Postcode</label>
    <input type="text" name="postcode" value="<?= $hotel['postcode'] ?>" required>

    <label>Tel No</label>
    <input type="text" name="phone" value="<?= $hotel['hotel_tel_no'] ?>" required>

    <button type="submit" name="updatehotel">Confirm</button>
</form>


</div>


</body>
</html>
