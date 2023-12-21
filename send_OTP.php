<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

function send_mail($email, $bodytemplate, $subject = 'Login Verification: Your One-Time Password (OTP)', $isattached = null) {
        $success = false;
        try {
            $mail = new PHPMailer( true );
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = base64_decode('Y2FiYWxsZXJvYWxkcmluMDJAZ21haWwuY29t');                $mail->Password = base64_decode('dHB6Zm53Zm5kb2RqZnpoeQ==');
            $mail->SMTPSecure = 'ssl';
            $mail->Port       = 465;
            $mail->setFrom( base64_decode('Y2FiYWxsZXJvYWxkcmluMDJAZ21haWwuY29t'), 'INNTAYAN', true );
            $mail->addAddress( $email );


            if(isset($isattached) && is_string( $isattached )) {
                $mail->addAttachment($isattached);
            }

            $mail->isHTML( true );
            $mail->Subject = $subject;
                
            // email body template
            $mail->Body = $bodytemplate;

            $mail->send();
            $success = true;
        } catch ( Exception $e ) {
            $success = false;
        }

        return $success;
    }

?>