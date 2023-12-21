<?php
require_once './send_OTP.php';
require_once './config.php';
require_once './global.php';

if(isset($_SESSION['email'])) {
    $token = random_int(100000, 999999);
            $body = '
                <!DOCTYPE html>
                <html>
                    <head>
                        <title>Verification Code</title>
                    </head>
                    <body>
                        <p>Dear ' . $row['email'] . ',</p>
                        <p>We received a reset verification code request for your account. If you did not initiate this request, please ignore this email, please enter the following verification code:</p>
                        <h2 style="text-align:center; font-size:32px;">' . $token . '</h2>
                        <p>This code is valid for <b>10 minutes</b>, so please enter it as soon as possible.</p>
                        <p>If you have any trouble entering the code, please don\'t hesitate to contact us at <a href="mailto:cabaleroaldrin02@gmail.com">cabaleroaldrin02@gmail.com</a>.</p>
                    </body>
                </html>';
            if (send_mail($_SESSION['email'], $body, 'Reset Password Verification (INNTAYAN)')) {
                $_SESSION['reset_verification'] = $token;
                $_SESSION['start'] = time();
                $_SESSION['expire'] = $_SESSION['start'] + (10 * 60);
                $_SESSION['email'] = $row['email'];
            }
}

header('location: forgot_password.php');