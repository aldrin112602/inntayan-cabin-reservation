<?php
require_once './config.php';
require_once './global.php';
require_once './audit_trails.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post = validate_post_data($_POST);
    $enable2FA = $post['enable2FA'];

    $sql = "UPDATE accounts SET enable2FA = ? WHERE username = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $enable2FA, $_SESSION['username']);

    if (mysqli_stmt_execute($stmt)) {
        echo 'success';
        logUser($_SESSION[ 'username' ], '2FA ('. ($enable2FA == 'false' ? 'OFF' : 'ON') .') successfully!');
    } else {
        logUser($_SESSION[ 'username' ], 'Error updating 2FA: ' . mysqli_error($conn));
        echo 'failed: ' . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
}
?>
