<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $sql = "DELETE FROM notifications WHERE id = '$id'";
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
