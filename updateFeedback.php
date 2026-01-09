<?php
session_start();
require_once "function.php";
 

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}


$db = new SQLite3("SNM.db");
$db->exec("PRAGMA foreign_keys = ON;");

$feedbackID = $_POST['feedback_id'];

if (isset($_POST['updateFeedback'])) {
 updateFeedback($db,$feedbackID,$_POST['rating'],$_POST['comments']);
 echo"Sucessfully updated the feedback"; 
 header("Location: viewFeedback.php");


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
    <title>Document</title>
<body>

<?php require "nav.php"; ?>
<div class="backcont">
<a href="index.php" class="backbtn">Back</a>
</div>
<div class="middle">
    
 
 
 
    <form method="POST" class="addhotel">
        <input type="hidden" name="feedback_id" value="<?= $feedbackID ?>">

        <label>Comments</label>
        <textarea name="comments"></textarea>
        
        <label>Rating (1–5):</label>
        <select name="rating" required>
            <option value="1">1 ★</option>
            <option value="2">2 ★★</option>
            <option value="3">3 ★★★</option>
            <option value="4">4 ★★★★</option>
            <option value="5">5 ★★★★★</option>
        </select>
        <button type="submit" name="updateFeedback">Confirm</button>
    </form>

</div>


</body>
</html>
