<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reservation_id = mysqli_real_escape_string($conn, $_POST['reservationId']);
    $username = mysqli_real_escape_string($conn, $_SESSION['username']);

    $sql = "UPDATE cabin_reservation SET status = 'Cancelled' WHERE id = '$reservation_id' AND username='$username'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $response = array('success' => true);
    } else {
        $response = array('success' => false, 'error' => mysqli_error($conn));
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
