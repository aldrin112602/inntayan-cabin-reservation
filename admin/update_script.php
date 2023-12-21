<?php
require_once '../config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['column']) && isset($_POST['value']) && isset($_POST['table'])) {
    $id = $_POST['id'];
    $column = $_POST['column'];
    $newValue = $_POST['value'];
    $table = $_POST['table'];
    $updateQuery = "UPDATE " . $table . " SET " . $column . "='$newValue' WHERE id=$id";
    mysqli_query($conn, $updateQuery);
}