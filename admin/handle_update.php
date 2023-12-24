<?php
require_once '../config.php';
require_once '../global.php';

$err_msg = $success_msg = null;
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
    
    $queryUpdate = "UPDATE cabin_reservation SET cabin_no = '$cabin_no', time = '$time', date = '$date', location = '$location', promo_code = '$promo_code', payment_method = '$payment_method', status = '$status' WHERE id={$_GET['id']}";


    $pushNotification = "INSERT INTO notifications (username, description) VALUES ('$username', 'An admin updated your cabin reservation.')";

    if ($conn->query($queryUpdate) && $conn->query($pushNotification)) {
        $success_msg = "Reservation successfully updated!";
    } else {
        $err_msg = "Error updating reservation: " . $conn->error;
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