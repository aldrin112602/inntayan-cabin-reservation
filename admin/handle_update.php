<?php
require_once '../config.php';
require_once '../global.php';

$err_msg = $success_msg = null;


function sendSMS($message, $recipient) {
    $apiEndpoint = "https://app.philsms.com/api/v3/sms/send";
    $apiToken = "188|FAhI0qNgFxVs66gCpgeMuRzYr42031CxngqqOqQJ";

    $data = array(
        'recipient' => $recipient,
        'sender_id' => 'PhilSMS',
        'type' => 'plain',
        'message' => $message
    );

    $ch = curl_init($apiEndpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer ' . $apiToken,
        'Content-Type: application/json',
        'Accept: application/json'
    ));
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $err_msg = 'Curl error: ' . curl_error($ch);
        return false;
    }

    curl_close($ch);
    $result = json_decode($response, true);
    if ($result && $result['status'] === 'success') {
        return true;
    } else {
        $err_msg = "Error sending SMS to {$recipient}: " . ($result['message'] ?? "Unknown error") . "\n";
        return false;
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post = validate_post_data($_POST);
    
    $location = $post['location'];
    $date = $post['date'];
    $time = $post['time'];
    $cabin_no = $post['cabin_no'];
    $promo_code = $post['promo_code'];
    $payment_method = $post['payment_method'];
    $status = $post['status'];
    $username = $post['username'];

    $time_of_stay = $post['time_of_stay'];
    $amount_to_pay = $post['amount_to_pay'];

    
    if ($status == 'Done') {
        $users = getRows("username = '$username'", "accounts");
        if (count($users) > 0) {
            $number = trim($users[0]['contact']);
            
            if (isset($number) && !empty($number)) {
                if(sendSMS("Hi ". $number .", ", $number)) {
                    $queryUpdate = "UPDATE cabin_reservation SET cabin_no = '$cabin_no', time_of_stay = '$time_of_stay', amount_to_pay = '$amount_to_pay', time = '$time', date = '$date', location = '$location', promo_code = '$promo_code', payment_method = '$payment_method', status = '$status' WHERE id={$_GET['id']}";
                    $pushNotification = "INSERT INTO notifications (username, description) VALUES ('$username', 'An admin updated your cabin reservation.')";

                    if ($conn->query($queryUpdate) && $conn->query($pushNotification)) {
                        $success_msg = "Reservation successfully updated!";
                    } else {
                        $err_msg = "Error updating reservation: " . $conn->error;
                    }
                };
            }
        }
    }
    
}
?>

<script>
$(document).ready(function() {
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });

    <?php
            if(isset($err_msg)) {
                ?>
    Toast.fire({
        icon: "error",
        title: "<?php echo $err_msg ?>"
    });
    <?php
            }    
            ?>

    <?php
            if(isset($success_msg)) {
                ?>
    Toast.fire({
        icon: "success",
        title: "<?php echo $success_msg ?>"
    });
    <?php
            }    
            ?>
})
</script>