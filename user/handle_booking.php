<?php   
require_once '../config.php';
require_once '../global.php';
if(isset($_SESSION['reset_verification']) || isset($_SESSION['new_password'])) header('location: ../forgot_password.php');

$username = $err_msg = $success_msg = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post = validate_post_data($_POST);
    $username = $_SESSION['username'];
    $location = $post['location'];
    $date = $post['date'];
    $time = $post['time'];
    $cabin_no = $post['cabin_no'];
    $promo_code = $post['promo_code'];
    $payment_method = $post['payment_method'];

    $status = 'Pending'; // Default status
    $queryInsert = "INSERT INTO cabin_reservation (username, location, date, time, cabin_no, promo_code, payment_method, status)
                    VALUES ('$username', '$location', '$date', '$time', $cabin_no, '$promo_code', '$payment_method', '$status')";

    if ($conn->query($queryInsert)) {
        $success_msg = "Reservation successfully created!";
    } else {
        $err_msg = "Error creating reservation: " . $conn->error;
    }

    
    

}

$querySelect = "SELECT * FROM cabin_reservation WHERE username = '{$_SESSION['username']}'";
$result = $conn->query($querySelect);