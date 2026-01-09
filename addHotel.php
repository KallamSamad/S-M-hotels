<?php
session_start();
require_once "function.php";
 

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$db = new SQLite3("SNM.db");
$db->exec("PRAGMA foreign_keys = ON;");


if(isset($_POST["addhotel"])) {
 addHotel($db,$_POST['hotel_name'], $_POST['address'],$_POST['city'],$_POST['postcode'],$_POST['tel']);
 echo "Successfully added ".$_POST["hotel_name"];
 header(header: "Location: viewHotel.php");
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
<body>

<?php require "nav.php"; ?>
<div class="backcont">
<a href="index.php" class="backbtn">Back</a>
</div>
<div class="middle">
    
 
 
 
 
    <form method="POST" class="addhotel">
        <input type="hidden" name="hotel_ID" value="<?= $room_ID ?>">

        <label>Hotel Name</label>
        <input type="text" name="hotel_name">

        <label>Address</label>
        <input type="text" name="address" required>
        
        <label>City</label>
        <input type="text" name="city" required>


        <label>Postcode</label>
        <input type="text" name="postcode" required>

        <label>Tel No:</label>
        <input type="text" name="tel" required>

        <button type="submit" name="addhotel">Confirm</button>
    </form>

</div>


</body>
</html>
