<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $id = mysqli_real_escape_string($conn, $_POST['id']);

    // Delete cabin
    $sql = "UPDATE cabin SET status = '$status' WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {
        $response = array('success' => true);
    } else {
        $response = array('success' => false, 'error' => mysqli_error($conn));
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
