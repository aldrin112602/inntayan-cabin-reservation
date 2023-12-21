<?php   
require_once './config.php';
require_once './global.php';
if(isset($_SESSION['reset_verification']) || isset($_SESSION['new_password'])) header('location: ./forgot_password.php');

$username = $password = $fullname = $email = $err_msg = $success_msg = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post = validate_post_data($_POST);
    $username = $post['username'];
    $password = $post['password'];
    $email = $post['email'];
    $fullname = $post['fullname'];
    $hashPass = md5($password);

    if(str_word_count($fullname) < 2) {
        $err_msg = "Fullname is not valid.";
    } elseif(strlen($password) < 6) {
        $err_msg = "Password should be at least 6 characters long.";
    } else {
        $checkDuplicateSql = "SELECT COUNT(*) FROM accounts WHERE username = ? OR email = ?";
        $checkDuplicateStmt = $conn->prepare($checkDuplicateSql);
        $checkDuplicateStmt->bind_param("ss", $username, $email);
        $checkDuplicateStmt->execute();
        $checkDuplicateStmt->bind_result($count);
        $checkDuplicateStmt->fetch();
        $checkDuplicateStmt->close();

        if ($count > 0) {
            $err_msg = "Username or email already taken.";
        } else {
            // save into table
            $insertSql = "INSERT INTO accounts (username, password, email, fullname, enable2FA, role) VALUES (?, ?, ?, ?, ?, ?)";
            $insertStmt = $conn->prepare($insertSql);

            $enable2FA = 'false';
            $role = 'user';

            $insertStmt->bind_param("ssssss", $username, $hashPass, $email, $fullname, $enable2FA, $role);
            $insertStmt->execute();

            if ($insertStmt->affected_rows > 0) {
                $success_msg = "Account created successfully!";
            } else {
                $err_msg = "Error inserting data into the database.";
            }
            $insertStmt->close();
        }
    }

} else {
    if ( isset( $_SESSION[ 'role' ] ) ) {
        if ( $_SESSION[ 'role' ] == 'user' ) {
            header( 'location: ./user/' );
        } else {
            header( 'location: ./admin/' );
        }
    }
}
