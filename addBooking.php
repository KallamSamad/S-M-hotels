<?php
session_start();
require_once "function.php";
$alreadybooked = "";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$db = new SQLite3("SNM.db");
$db->exec("PRAGMA foreign_keys = ON;");

if (!isset($_POST['room_ID'])) {
    echo "Error: No room selected.";
    exit();
}



$room_ID = $_POST['room_ID'];

$stmt = $db->prepare("
    SELECT Room.*, Hotel.hotel_name 
    FROM Room
    INNER JOIN Hotel ON Room.hotel_ID = Hotel.hotel_ID
    WHERE Room.room_ID = :room_ID
");

$stmt->bindValue(":room_ID", $room_ID, SQLITE3_INTEGER);
$room = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
 


if (!$room) {
    echo "Room not found.";
    exit();
}

if (isset($_POST['confirmBooking'])) {

    $check_in  = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $payment   = $_POST['payment_method'];
    $price = $room['price_per_night'];

    $torf=bookCheck($db, $room_ID, $check_in, $check_out);

    if($torf==true){
          $alreadybooked = "This timeslot has already been taken";
    }else{


    $days = (strtotime($check_out) - strtotime($check_in)) / 86400;

    if ($days < 1) {
        $error = "Check-out date must be after check-in date.";
    } else {
        $amount = $days * $price;

   
        addBooking(
            $db,
            $_SESSION['id'],
            $room_ID,
            $check_in,
            $check_out,
            $amount,
            $payment
        );

        header("Location: viewBookings.php");
        exit();


    }}
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
    <title>S&M Hotels</title>
</head>
<?php require_once "nav.php";?>
<div class="backcont">
<a href="index.php" class="backbtn">Back</a>
</div>
    <div class="middle">
<body>
    <?php if ($_SESSION['role'] === 'staff') {
    die("Staff cannot add Bookings.");
}?>

<div class="middle">
    
 <?php   $today = date("Y-m-d");  
 $maxDate = date("Y-m-d", strtotime("+1 year"));

?>
    <h2>Book Room <?= $room['room_number'] ?> from <?= $room['hotel_name'] ?></h2>


    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if (!empty($alreadybooked)) echo "<p style='color:red;'>$alreadybooked</p>"; ?>

    <form method="POST" class="addhotel">
        <input type="hidden" name="room_ID" value="<?= $room_ID ?>">

        <label>Check-in Date:</label>
        <input type="date" name="check_in" required min="<?php echo $today; ?>">

        <label>Check-out Date:</label>
        <input type="date" name="check_out" required min="<?php echo $today; ?>" max="<?php echo $maxDate; ?>">
        
        <label>Nights:</label>
        <input type="number" id="nights" name="nights" readonly>

        <label>Total Price (£):</label>
        <input type="number" id="total" name="total" readonly>



        <label>Payment Method:</label>
        <select name="payment_method" required>
            <option value="card">Card</option>
            <option value="cash">Cash</option>
            <option value="paypal">PayPal</option>
        </select>

        <button type="submit" name="confirmBooking">Confirm</button>
    </form>
</div>
</div>
<script>
const price = <?= $room['price_per_night'] ?>;

function update() {
    let inDate = new Date(document.querySelector("[name='check_in']").value);
    let outDate = new Date(document.querySelector("[name='check_out']").value);

    if (outDate > inDate) {
        let nights = (outDate - inDate) / 86400000;
        document.getElementById("nights").value = nights;
        document.getElementById("total").value = nights * price;
    }
}

document.querySelector("[name='check_in']").onchange = update;
document.querySelector("[name='check_out']").onchange = update;
</script>

</body>
</html>
