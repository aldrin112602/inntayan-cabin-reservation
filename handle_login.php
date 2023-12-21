<?php   
require_once './send_OTP.php';
require_once './config.php';
require_once './global.php';
require_once './audit_trails.php';

if(isset($_SESSION['reset_verification']) || isset($_SESSION['new_password'])) header('location: ./forgot_password.php');


$username = $password = $err_msg = $success_msg = null;
if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' && isset($_POST[ 'username' ])) {
    $post = validate_post_data( $_POST );
    $username = $post[ 'username' ];
    $password = $post[ 'password' ];
    $hashPass = md5($password);
    $condition = "username = '$username' AND password = '$hashPass'";
    if ( isDataExists( 'accounts', '*', $condition ) ) {
        foreach ( getRows( $condition, 'accounts' ) as $row ) {
            $token = random_int(100000, 999999);
            $body = '
                    <!DOCTYPE html>
                    <html>
                        <head>
                            <title>Verification Code</title>
                        </head>
                        <body>
                            <p>Dear '. $row['email'] .',</p>
                            <p>We received a verification code request for your account. If you did not initiate this request, please ignore this email, please enter the following verification code:</p>
                            <h2 style="text-align:center; font-size:32px;">'. $token .'</h2>
                            <p>This code is valid for <b>10 minutes</b>, so please enter it as soon as possible.</p>
                            <p>If you have any trouble entering the code, please don\'t hesitate to contact us at <a href="mailto:cabaleroaldrin02@gmail.com">cabaleroaldrin02@gmail.com</a>.</p>
                        </body>
                    </html>';

            if($row['enable2FA'] == 'true') {
                if(send_mail($row['email'], $body)) {
                    $_SESSION['validate_otp'] = $token;
                    $_SESSION[ 'username' ] = $row[ 'username' ];
                    $_SESSION[ 'password' ] = $row[ 'password' ];
                    $_SESSION['start'] = time();
                    $_SESSION['expire'] = $_SESSION['start'] + (10 * 60);
                    logUser($row[ 'username' ], 'Successfully send OTP for login security.');
                }
            } else {
                $_SESSION[ 'login' ] = true;
                $_SESSION[ 'role' ] = $row[ 'role' ];
                $_SESSION[ 'username' ] = $row[ 'username' ];
                logUser($row[ 'username' ], 'Logged in successfully.');
                $success_msg = 'Logged in successfully';
            }
        }

    } else {
        $err_msg = 'Invalid username or password';
    }

}

if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' && isset($_POST[ 'otp' ])) {
    if($_POST[ 'otp' ] == $_SESSION['validate_otp']) {
        logUser($_SESSION[ 'username' ], 'User  successfully validate OTP for Login authentication.');
        $condition = "username = '{$_SESSION[ 'username' ]}' AND password = '{$_SESSION[ 'password' ]}'";
        if ( isDataExists( 'accounts', '*', $condition ) ) {
            foreach ( getRows( $condition, 'accounts' ) as $row ) {
                $_SESSION['validate_otp'] = null;
                $_SESSION[ 'login' ] = true;
                $_SESSION[ 'role' ] = $row[ 'role' ];
                $_SESSION[ 'username' ] = $row[ 'username' ];
                $_SESSION[ 'barangay' ] = $row[ 'barangay' ];
                $_SESSION[ 'municipality' ] = $row[ 'municipality' ];
                $_SESSION[ 'province' ] = $row[ 'province' ];
                $_SESSION[ 'unique_id' ] = $row[ 'unique_id' ];
                logUser($row[ 'username' ], 'Logged in successfully.');
            }
        }

        $success_msg = 'Logged in successfully';
        
    } else {
        $err_msg = 'Invalid OTP code';
    }
}

if ( isset( $_SESSION[ 'role' ] ) ) {
    if ( $_SESSION[ 'role' ] == 'user' ) {
        header( 'location: ./user/' );
    } else {
        header( 'location: ./admin/' );
    }
}
