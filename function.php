<?php 
require_once("connect.php");

 

function queryFetchHotel($db,$records_per_page,$offset) {
    $stmt=$db->prepare("SELECT * FROM Hotel LIMIT $records_per_page OFFSET $offset");
    return $stmt;
}

function queryFetchReview($db,$records_per_page,$offset) {
   $stmt=$db->prepare("SELECT 
        Feedback.feedback_ID,
        Feedback.rating,
        Feedback.comments,
        Feedback.submitted_at,
        User.user_id AS id,
        User.username,
        User.first_name,
        User.last_name,
        Hotel.hotel_name,
        Room.room_number
    FROM Feedback
    INNER JOIN Booking ON Feedback.booking_ID = Booking.booking_ID
    INNER JOIN User ON Booking.user_ID = User.user_ID
    INNER JOIN Room ON Booking.room_ID = Room.room_ID
    INNER JOIN Hotel ON Room.hotel_ID = Hotel.hotel_ID
    ORDER BY Feedback.submitted_at DESC
    LIMIT $records_per_page OFFSET $offset");
    return $stmt;
}




function queryFetchReview2($db,$records_per_page,$offset) {
    $stmt= $db->prepare("SELECT 
        Feedback.feedback_ID,
        Feedback.rating,
        Feedback.comments,
        Feedback.submitted_at,
        User.user_id AS id,
        User.username,
        User.first_name,
        User.last_name,
        Hotel.hotel_name,
        Room.room_number
    FROM Feedback
    INNER JOIN Booking ON Feedback.booking_ID = Booking.booking_ID
    INNER JOIN User ON Booking.user_ID = User.user_ID
    INNER JOIN Room ON Booking.room_ID = Room.room_ID
    INNER JOIN Hotel ON Room.hotel_ID = Hotel.hotel_ID
    WHERE User.user_id = :id
    ORDER BY Feedback.submitted_at DESC
    LIMIT $records_per_page OFFSET $offset");

    return $stmt;
}

function queryFetchRoom($db,$records_per_page,$offset) {
    $stmt=$db->prepare("SELECT 
        Room.room_ID,
        Room.room_number,
        Room.floor_number,
        Room.room_type_ID AS roomtypeid,
        Room.hotel_ID,
        Room.price_per_night,
        Room.status,
        Hotel.hotel_name,
        Room.description AS roomdesc,
        RoomType.type_name,
        RoomType.description AS roomtypedesc
    FROM Room
    INNER JOIN RoomType ON Room.room_type_ID = RoomType.room_type_ID
    INNER JOIN Hotel ON Room.hotel_ID = Hotel.hotel_ID
    LIMIT $records_per_page OFFSET $offset");

    return $stmt;
}

function queryFetchBooking($db,$records_per_page,$offset) {
    $stmt=$db->prepare("SELECT 
        Room.room_number,
        User.first_name || ' ' || COALESCE(User.middle_name || ' ', '') || User.last_name AS name,
        Booking.booking_date,
        Booking.booking_ID,
        Booking.check_in_date,
        Booking.check_out_date,
        Booking.status,
        Booking.amount_paid,
        Booking.payment_date,
        Booking.payment_status,
        Booking.payment_method
    FROM Booking
    INNER JOIN User ON User.user_ID = Booking.user_ID
    INNER JOIN Room ON Room.room_ID = Booking.room_ID
    LIMIT $records_per_page OFFSET $offset");

return $stmt;
}

function queryUserBooking($db,$records_per_page,$offset) {
    $stmt=$db->prepare("SELECT 
        Room.room_ID,               
        Hotel.hotel_name,
        Room.room_number,
        Room.price_per_night,
        Room.description AS roomdesc,
        RoomType.type_name,
        RoomType.description AS typedesc
    FROM Hotel
    INNER JOIN Room ON Room.hotel_ID = Hotel.hotel_ID
    INNER JOIN RoomType ON Room.room_type_ID = RoomType.room_type_ID
    WHERE Room.status = 'Available'
    LIMIT $records_per_page OFFSET $offset
    ");
    return $stmt;

}


function queryBookingList($db,$records_per_page,$offset) {
    $stmt=$db->prepare("SELECT 
        Booking.booking_ID,
        Booking.room_ID,
        Hotel.hotel_name,
        Room.room_number,
        Room.price_per_night,
        Room.description AS roomdesc,
        RoomType.type_name,
        RoomType.description AS typedesc,
        Booking.check_in_date,
        Booking.check_out_date,
        Booking.booking_date,
        Booking.status AS status,
        julianday(Booking.check_out_date) - julianday(Booking.check_in_date) AS nights,
        Booking.amount_paid,
        Booking.payment_status,
        Booking.payment_method

    FROM Booking
    INNER JOIN Room ON Booking.room_ID = Room.room_ID
    INNER JOIN Hotel ON Room.hotel_ID = Hotel.hotel_ID
    INNER JOIN RoomType ON Room.room_type_ID = RoomType.room_type_ID
    INNER JOIN User ON Booking.user_ID = User.user_ID

    WHERE User.user_ID = :user_ID
    ORDER BY Booking.booking_date DESC
    LIMIT $records_per_page OFFSET $offset");

    return $stmt;

}

 

function queryViewUserProfile() {
    return "SELECT 
        user_ID AS id,
        username,
        profilepic,
        email,
        phone,
        first_name,
        COALESCE(middle_name, '') AS middle_name,
        last_name,
        'guest' AS account_type
    FROM User
    WHERE user_ID = :id";
}

function queryViewStaffProfile() {
    return "SELECT
        staff_ID AS id,
        username,
        profilepic,
        email,
        first_name,
        COALESCE(middle_name, '') AS middle_name,
        phone,
        last_name,
        'staff' AS account_type
    FROM Staff
    WHERE staff_ID = :id";
}

 

function updateViewUserProfile($db, $username, $pfp, $email, $fname, $mname, $lname, $id) {
    $stmt = $db->prepare("UPDATE User
        SET  
            username = :username,
            profilepic = :profilepic,
            email = :email,
            first_name = :firstname,
            middle_name = COALESCE(:middlename, ''),
            last_name = :lastname
        WHERE user_ID = :id
    ");

    $stmt->bindValue(":username", $username, SQLITE3_TEXT);
    $stmt->bindValue(":profilepic", $pfp, SQLITE3_TEXT);
    $stmt->bindValue(":email", $email, SQLITE3_TEXT);
    $stmt->bindValue(":firstname", $fname, SQLITE3_TEXT);
    $stmt->bindValue(":middlename", $mname, SQLITE3_TEXT);
    $stmt->bindValue(":lastname", $lname, SQLITE3_TEXT);
    $stmt->bindValue(":id", $id, SQLITE3_INTEGER);

    if (!$stmt->execute()) {
        echo "Error updating record: " . $db->lastErrorMsg();
    }

    $stmt->close();
}
function updateViewStaffProfile($db, $username, $pfp, $email, $fname, $mname, $lname, $id) {
    $stmt = $db->prepare("UPDATE Staff
        SET  
            username   = :username,
            profilepic = :profilepic,
            email      = :email,
            first_name = :firstname,
            middle_name = COALESCE(:middlename, ''),
            last_name  = :lastname
        WHERE staff_ID = :id
    ");

    $stmt->bindValue(":username",   $username, SQLITE3_TEXT);
    $stmt->bindValue(":profilepic", $pfp,      SQLITE3_TEXT);
    $stmt->bindValue(":email",      $email,    SQLITE3_TEXT);
    $stmt->bindValue(":firstname",  $fname,    SQLITE3_TEXT);
    $stmt->bindValue(":middlename", $mname,    SQLITE3_TEXT);
    $stmt->bindValue(":lastname",   $lname,    SQLITE3_TEXT);
    $stmt->bindValue(":id",         $id,       SQLITE3_INTEGER);

    $stmt->execute();
    $stmt->close();
}

function oldUserPassword($db, $id) {
    $stmt = $db->prepare("SELECT password FROM User WHERE user_ID = :id");
    $stmt->bindValue(":id", $id, SQLITE3_INTEGER);
    $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
    return $result['password'];
}

function oldStaffPassword($db, $id) {
    $stmt = $db->prepare("SELECT password FROM Staff WHERE staff_ID = :id");
    $stmt->bindValue(":id", $id, SQLITE3_INTEGER);
    $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
    return $result['password'];
}

function updateUserPassword($db, $id, $newpw) {
    $stmt = $db->prepare("UPDATE User SET password = :pw WHERE user_ID = :id");
    $stmt->bindValue(":pw", $newpw, SQLITE3_TEXT);
    $stmt->bindValue(":id", $id, SQLITE3_INTEGER);
    $stmt->execute();
}

function updateStaffPassword($db, $id, $newpw) {
    $stmt = $db->prepare("UPDATE Staff SET password = :pw WHERE staff_ID = :id");
    $stmt->bindValue(":pw", $newpw, SQLITE3_TEXT);
    $stmt->bindValue(":id", $id, SQLITE3_INTEGER);
    $stmt->execute();
}

function addUser($db,$name,$email,$phone,$password,$fname,$mname,$lname){
    $stmt = $db->prepare("INSERT INTO User(username,email,phone,password,first_name,middle_name,last_name)  VALUES (:username,:email,:phone,:password,:fname,:mname,:lname)") ;
    $stmt->bindValue(":username", $name, SQLITE3_TEXT);
    $stmt->bindValue(":email", $email, SQLITE3_TEXT);
    $stmt->bindValue(":password", $password, SQLITE3_TEXT);
    $stmt->bindValue(":phone", $phone, SQLITE3_INTEGER);
    $stmt->bindValue(":fname", $fname, SQLITE3_TEXT);
    $stmt->bindValue(":mname", $mname, SQLITE3_TEXT);
    $stmt->bindValue(":lname", $lname, SQLITE3_TEXT);

    if ($stmt->execute()) {
        echo "A new profile called ".$name." has been made";
    } else {
        echo "Failed to create a record. Try a diffrenet email or username";
    }
    $db->close();

    
}

function addBooking($db, $user_ID, $room_ID, $check_in, $check_out, $amount_paid, $payment_method) {

    $stmt = $db->prepare("
        INSERT INTO Booking 
        (user_ID, room_ID, check_in_date, check_out_date, amount_paid, payment_status, payment_method)
        VALUES 
        (:user_ID, :room_ID, :check_in, :check_out, :amount_paid, 'completed', :payment_method)
    ");

    $stmt->bindValue(":user_ID", $user_ID, SQLITE3_INTEGER);
    $stmt->bindValue(":room_ID", $room_ID, SQLITE3_INTEGER);
    $stmt->bindValue(":check_in", $check_in, SQLITE3_TEXT);
    $stmt->bindValue(":check_out", $check_out, SQLITE3_TEXT);
    $stmt->bindValue(":amount_paid", $amount_paid, SQLITE3_FLOAT);
    $stmt->bindValue(":payment_method", $payment_method, SQLITE3_TEXT);

    return $stmt->execute();
}

function bookCheck($db, $room_ID, $check_in, $check_out) {

    $stmt = $db->prepare("
        SELECT COUNT(*) AS taken
        FROM Booking
        WHERE room_ID = :room_ID
        AND (
            :check_in < check_out_date
            AND :check_out > check_in_date
        )
    ");

    $stmt->bindValue(":room_ID", $room_ID, SQLITE3_INTEGER);
    $stmt->bindValue(":check_in", $check_in, SQLITE3_TEXT);
    $stmt->bindValue(":check_out", $check_out, SQLITE3_TEXT);

    $result = $stmt->execute();                      
    $row = $result->fetchArray(SQLITE3_ASSOC);       

    return ($row['taken'] > 0);                      
}

function cancelBooking($db, $booking_ID, $user_ID) {
    $stmt = $db->prepare("UPDATE Booking
        SET status = 'Cancelled'
        WHERE booking_ID = :booking_ID AND user_ID = :user_ID");

    $stmt->bindValue(":booking_ID", $booking_ID, SQLITE3_INTEGER);
    $stmt->bindValue(":user_ID", $user_ID, SQLITE3_INTEGER);

    $stmt->execute();

    return $db->changes();   
}

function addFeedback($db, $booking_ID, $rating, $comments) {
    $stmt = $db->prepare("
        INSERT INTO Feedback (booking_ID, rating, comments, submitted_at)
        VALUES (:booking_ID, :rating, :comments, CURRENT_TIMESTAMP)
    ");

    $stmt->bindValue(":booking_ID", $booking_ID, SQLITE3_INTEGER);
    $stmt->bindValue(":rating", $rating, SQLITE3_INTEGER);
    $stmt->bindValue(":comments", $comments, SQLITE3_TEXT);

    return $stmt->execute();
}


function addHotel($db,$hotelname,$address,$city,$postcode,$tel){
    $stmt = $db->prepare("INSERT INTO Hotel(hotel_name,hotel_address,city,postcode,hotel_tel_no) VALUES (:hotelname,:address,:city,:postcode,:tel);");
    $stmt->bindValue(":hotelname", $hotelname, SQLITE3_TEXT);
    $stmt->bindValue(":address", $address, SQLITE3_TEXT);
    $stmt->bindValue(":city", $city, SQLITE3_TEXT);
    $stmt->bindValue(":postcode", $postcode, SQLITE3_TEXT);
    $stmt->bindValue(":tel", $tel, SQLITE3_TEXT);

    return $stmt->execute();
}

function addRoom($db, $hotelid, $roomno, $floor, $price, $text, $roomtypeid) {
    $stmt = $db->prepare("
        INSERT INTO Room(hotel_ID, room_number, floor_number, price_per_night, description, room_type_ID)
        VALUES (:hotelid, :roomno, :floor, :price, :text, :roomtypeid)
    ");

    $stmt->bindValue(":hotelid", $hotelid, SQLITE3_INTEGER);
    $stmt->bindValue(":roomno", $roomno, SQLITE3_TEXT);
    $stmt->bindValue(":floor", $floor, SQLITE3_INTEGER);
    $stmt->bindValue(":price", $price, SQLITE3_FLOAT);
    $stmt->bindValue(":text", $text, SQLITE3_TEXT);
    $stmt->bindValue(":roomtypeid", $roomtypeid, SQLITE3_INTEGER);

    return $stmt->execute();
}

function updateFeedback($db, $feedbackid, $rating, $comments) {

    $stmt = $db->prepare("
        UPDATE Feedback
        SET comments = :comments,
            submitted_at = CURRENT_TIMESTAMP,
            rating = :rating
        WHERE feedback_ID = :id
    ");

    $stmt->bindValue(":comments", $comments, SQLITE3_TEXT);
    $stmt->bindValue(":rating", $rating, SQLITE3_INTEGER);
    $stmt->bindValue(":id", $feedbackid, SQLITE3_INTEGER);

    return $stmt->execute();
}



function addBooking2($db,$id,$room_ID,$check_in,$check_out,$amount_paid,$paymentstatus,$payment_method){
    $stmt = $db->prepare("INSERT INTO Booking(user_ID,room_ID,check_in_date,check_out_date,amount_paid,payment_status,payment_method) VALUES (:id,:roomid,:checkin,:checkout,:amountpaid,:paystay,:paymeth)");
$stmt->bindValue(":id", $id, SQLITE3_INTEGER);
$stmt->bindValue(":roomid", $room_ID, SQLITE3_INTEGER);
$stmt->bindValue(":checkin", $check_in, SQLITE3_TEXT);
$stmt->bindValue(":checkout", $check_out, SQLITE3_TEXT);
$stmt->bindValue(":amountpaid", $amount_paid, SQLITE3_FLOAT);
$stmt->bindValue(":paystay", $paymentstatus, SQLITE3_TEXT);
$stmt->bindValue(":paymeth", $payment_method, SQLITE3_TEXT);

return $stmt->execute();

}

function deletefromhotel($db,$hotelid){
    $stmt = $db->prepare("DELETE FROM hotel WHERE hotel_ID=:hotelid");
    $stmt->bindValue(":hotelid", $hotelid, SQLITE3_INTEGER);

    return $stmt->execute();

}

function updatefromhotel($db,$hotelid,$hotelname,$hoteladdress,$city,$postcode,$telno){

    $stmt = $db->prepare("UPDATE Hotel
    SET hotel_name=:hotelname,
    hotel_address=:address,
    city=:city,
    postcode=:postcode,
    hotel_tel_no=:telno
    WHERE hotel_ID =:id");

    $stmt->bindValue(":id", $hotelid, SQLITE3_INTEGER);
    $stmt->bindValue(":hotelname", $hotelname, SQLITE3_TEXT);
    $stmt->bindValue(":address", $hoteladdress, SQLITE3_TEXT);#
    $stmt->bindValue(":city", $city, SQLITE3_TEXT);
    $stmt->bindValue(":postcode", $postcode, SQLITE3_TEXT);
    $stmt->bindValue(":telno", $telno, SQLITE3_TEXT);
   

    return $stmt->execute();
}

function deletefromroom($db, $roomid) {
    $stmt = $db->prepare("DELETE FROM Room WHERE room_ID = :roomID");
    $stmt->bindValue(":roomID", $roomid, SQLITE3_INTEGER);
    return $stmt->execute();
}

function updatefromroom($db, $roomid, $roomnum, $floor, $price, $status, $desc) {

    $stmt = $db->prepare("
        UPDATE Room
        SET room_number = :num,
            floor_number = :floor,
            price_per_night = :price,
            status = :status,
            description = :descr
        WHERE room_ID = :id
    ");

    $stmt->bindValue(":id", $roomid, SQLITE3_INTEGER);
    $stmt->bindValue(":num", $roomnum, SQLITE3_TEXT);
    $stmt->bindValue(":floor", $floor, SQLITE3_INTEGER);
    $stmt->bindValue(":price", $price, SQLITE3_FLOAT);
    $stmt->bindValue(":status", $status, SQLITE3_TEXT);
    $stmt->bindValue(":descr", $desc, SQLITE3_TEXT);

    return $stmt->execute();
}

function updatefrombooking($db, $bookingID, $checkin, $checkout, $amount, $paymentmethod) {
    $stmt = $db->prepare("
        UPDATE Booking
        SET check_in_date = :checkin,
            check_out_date = :checkout,
            amount_paid = :amount,
            payment_method = :method
        WHERE booking_ID = :id
    ");

    $stmt->bindValue(":checkin", $checkin, SQLITE3_TEXT);
    $stmt->bindValue(":checkout", $checkout, SQLITE3_TEXT);
    $stmt->bindValue(":amount", $amount, SQLITE3_FLOAT);
    $stmt->bindValue(":method", $paymentmethod, SQLITE3_TEXT);
    $stmt->bindValue(":id", $bookingID, SQLITE3_INTEGER);

    return $stmt->execute();
}


function deletefrombooking($db, $bookingID) {
    $stmt = $db->prepare("DELETE FROM Booking WHERE booking_ID = :id");
    $stmt->bindValue(":id", $bookingID, SQLITE3_INTEGER);
    return $stmt->execute();
}

function deleteFeedback($db, $feedbackID) {
    $stmt = $db->prepare('DELETE FROM Feedback WHERE feedback_ID = :id');
    $stmt->bindValue(':id', $feedbackID, SQLITE3_INTEGER);
    return $stmt->execute();
}


