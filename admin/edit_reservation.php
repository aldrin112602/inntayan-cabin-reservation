<?php 
    require_once '../config.php';
    require_once '../global.php';

    if(isset($_SESSION['role'])) {
        if($_SESSION['role'] == 'user') {
            header('location: ../user');
        }
    } else {
        header('location: ../index.php');
    }

    $sql = "SELECT * FROM accounts WHERE username = '{$_SESSION['username']}' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $profile = !empty($row['profile']) ? $row['profile'] : 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/7e/Circle-icons-profile.svg/1200px-Circle-icons-profile.svg.png';
    $username = $row['username'] ?? null;


    if(!isset($_GET['id']) || empty($_GET['id']) || !isDataExists( "cabin_reservation", "*", "id={$_GET['id']}" )) {
        header('Location: ./booking_records.php');
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>HOME - INNTAYAN</title>
    <link rel="stylesheet" href="../src/bootstrap.min.css" />
    <!-- Favicon -->
    <link rel="icon" href="../img/favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon" />

    <!-- For mobile devices -->
    <link rel="apple-touch-icon" sizes="180x180" href="img/logo.png" />
    <meta name="msapplication-TileImage" content="img/logo.png" />
    <meta name="msapplication-TileColor" content="#ffffff" />

    <!-- Poppins font -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />

    <!-- google icons -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />

    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
    <link href="../assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/libs/css/style.css">
    <link rel="stylesheet" href="../assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <link rel="stylesheet" href="../assets/vendor/charts/chartist-bundle/chartist.css">
    <link rel="stylesheet" href="../assets/vendor/charts/morris-bundle/morris.css">
    <link rel="stylesheet" href="../assets/vendor/fonts/material-design-iconic-font/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../assets/vendor/charts/c3charts/c3.css">
    <link rel="stylesheet" href="../assets/vendor/fonts/flag-icon-css/flag-icon.min.css">

    <script src="../src/jquery.min.js"></script>
    <script src="../src/sweetalert2/sweetalert2.all.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


    <!-- custom styles -->
    <style>
    * {
        font-family: "Poppins", sans-serif;
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        transition: all 0.5s;
    }
    .nav-left-sidebar, .dashboard-wrapper {
        background-repeat: no-repeat;
        background-size: 100% 100%;
        background-image: linear-gradient(45deg, #1B4242, #9EC8B9);
    }

    </style>
</head>

<body>
    <!-- ============================================================== -->
    <!-- main wrapper -->
    <!-- ============================================================== -->
    <div>
        <!-- ============================================================== -->
        <!-- left sidebar -->
        <!-- ============================================================== -->
        <div class="nav-left-sidebar">
            <div class="menu-list">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <a class="d-xl-none d-lg-none" href="#">Dashboard</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav flex-column">
                            <li class="nav-item text-center">
                                <img src="../img/logo.png" alt="pims logo" class="img-fluid" width="200px">
                            </li>
                            <li class="nav-item text-center my-4">
                                <img src="<?php echo $profile ?>" alt="Profile avatar" class="img-fluid rounded-circle"
                                    width="100px"><br>
                                <h3 class="text-white py-2"><?php echo $username ?></h3>
                            </li>
                            <li class="nav-item my-1">
                                <a href="./index.php"
                                    class="text-center text-white d-flex align-items-center justify-content-start gap-2 ml-4 fs-6">
                                    <span class="material-symbols-outlined">dashboard</span>
                                    Dashboard
                                </a>
                            </li>
                            <li class="nav-item my-1 current-page">
                                <a href="./booking_records.php"
                                    class="text-center text-white d-flex align-items-center justify-content-start gap-2 ml-4 fs-6">
                                    <span class="material-symbols-outlined">book</span>
                                    Booking records
                                </a>
                            </li>
                            <li class="nav-item my-1">
                                <a href="./manage_cabins.php"
                                    class="text-center text-white d-flex align-items-center justify-content-start gap-2 ml-4 fs-6">
                                    <span class="material-symbols-outlined">book</span>
                                    Manage Cabins
                                </a>
                            </li>
                            <li class="nav-item my-1">
                                <a href="setting.php"
                                    class="text-center text-white d-flex align-items-center justify-content-start gap-2 ml-4 fs-6">
                                    <span class="material-symbols-outlined">settings</span>
                                    Settings
                                </a>
                            </li>
                            <li class="nav-item my-1">
                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#bugReport"
                                    class="text-center text-white d-flex align-items-center justify-content-start gap-2 ml-4 fs-6">
                                    <span class="material-symbols-outlined">bug_report</span>
                                    Bug report
                                </a>
                            </li>
                            <li class="nav-item mt-3">
                                <?php  require_once './logout_confirmation.php'; ?>
<a href="#" onclick="logoutConfirmation()"
                                    class="text-center text-white d-flex align-items-center justify-content-start gap-2 ml-4 fs-6">
                                    <span class="material-symbols-outlined">logout</span>
                                    Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- end left sidebar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- wrapper  -->
        <!-- ============================================================== -->
        <div class="dashboard-wrapper">
            <div class="dashboard-ecommerce">
                <div class="container-fluid dashboard-content ">
                    <!-- ============================================================== -->
                    <!-- pageheader  -->
                    <!-- ============================================================== -->
                    <!-- Modal -->

                    <?php 
                        require_once('../bug_report.php');
                        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['bugTitle'])) {
                            $bugTitle = trim($_POST['bugTitle']);
                            $bugDescription = trim($_POST['bugDescription']);
                            $expectedOutcome = trim($_POST['expectedOutcome']);
                            $actualOutcome = trim($_POST['actualOutcome']);
                            $email = 'caballeroaldrin02@gmail.com';

                            $body = '
                            <!DOCTYPE html>
                            <html>
                                <head>
                                    <title>Bug reported</title>
                                </head>
                                <body>
                                    <p>Dear ' . $email . ',</p>
                                    <p>A bug has been reported with the following details:</p>
                                    <p><b>Title:</b> ' . $bugTitle . '</p>
                                    <p><b>Description:</b> ' . $bugDescription . '</p>
                                    <p><b>Expected Outcome:</b> ' . $expectedOutcome . '</p>
                                    <p><b>Actual Outcome:</b> ' . $actualOutcome . '</p>
                                    <p>If you have any additional information or if further clarification is needed, please respond to this email.</p>
                                    <p>Thank you for your attention to this matter.</p>
                                    <p>Sincerely,<br>Your Bug Reporting System</p>
                                </body>
                            </html>';
                            if(send_mail($email, $body)) {
                                ?>
                                <script>
                                    $(document).ready(function() {
                                        Swal.fire({
                                            title: "Success!",
                                            text: "Bug reported successfully",
                                            icon: "success"
                                        });
                                    })
                                </script>
                                <?php
                            } else {
                                ?>
                                <script>
                                    $(document).ready(function() {
                                        Swal.fire({
                                            title: "Error!",
                                            text: "Something went wrong, please try again",
                                            icon: "error"
                                        });
                                    })
                                </script>
                                <?php
                            }

                        }
                    ?>
                    <form action="#" method="post" class="modal fade" id="bugReport" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5 d-flex align-items-center justify-content-between gap-3"
                                        id="staticBackdropLabel">Bug report <span
                                            class="material-symbols-outlined fs-3">bug_report</span></h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Please let us know if you encountered a problem while using the app. Your feedback helps improve the System.</p>
                                    <label for="bugTitle" class="form-label">Bug Title:</label>
                                    <input type="text" id="bugTitle" name="bugTitle" class="form-control rounded" required>
                                    <br>
                                    <label for="bugDescription" class="form-label">Bug Description:</label>
                                    <textarea id="bugDescription" name="bugDescription" class="form-control rounded"
                                        required></textarea>
                                    <br>
                                    <label for="expectedOutcome" class="form-label">Expected Outcome:</label>
                                    <textarea id="expectedOutcome" name="expectedOutcome" class="form-control rounded"
                                        required></textarea>
                                    <br>
                                    <label for="actualOutcome" class="form-label">Actual Outcome:</label>
                                    <textarea id="actualOutcome" name="actualOutcome" class="form-control rounded"
                                        required></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="page-header">
                                <div>
                                    <h2 class="pageheader-title font-weight-bold text-light">Edit reservation</h2>
                                </div>
                                <div class="page-breadcrumb">
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="" class="breadcrumb-link text-light">id</a>
                                            </li>
                                            <li class="breadcrumb-item active text-light" aria-current="page"><?php echo $_GET['id'] ?? 0 ?></li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ============================================================== -->
                    <!-- end pageheader  -->
                    <!-- ============================================================== -->
                    <!-- content -->

                    <?php
                    require_once './handle_update.php';
                    $data = getRows("id={$_GET['id']}", "cabin_reservation");
                    ?>
                    <form action="" method="POST" class="col-12 px-5 col-md-8 mx-auto">
                        <input value="<?php echo $data[0]['username'] ?? null ?>" type="hidden" name="username">
                        <h2 class="text-light fw-bold">BOOKING SLEEPING CABIN</h2>
                        <div class="my-2">
                            <small class="form-label fs-6 text-light" for="">Location</small>
                            <input value="<?php echo $data[0]['location'] ?? null ?>" autocomplete="off" required
                                class="form-control form-control-md" type="text" name="location">
                        </div>
                        <div class="my-2">
                            <small class="form-label fs-6 text-light" for="">Time of stay</small>
                            <select required id="time_of_stay" name="time_of_stay" class="form-select form-select-md">
                            </select>
                        </div>

                        <div class="my-2 position-relative">
                            <small class="form-label fs-6 text-light" for="">Amount to pay</small>
                            <input id="amount_to_pay" value="<?php echo $data[0]['amount_to_pay'] ?? null ?>" style="padding-left: 40px;" readonly
                                class="form-control form-control-md" type="number" name="amount_to_pay">
                            <i class="fa-solid fa-peso-sign"
                                style="position: absolute; top: 50%; left: 20px; top: 57%;"></i>
                        </div>

                        <script>
                        (function() {
                            const d = document;
                            const time_of_stay_select = d.getElementById('time_of_stay'),
                                amount_to_pay = d.getElementById('amount_to_pay'),
                                time = '<?php echo $data[0]['time_of_stay'] ?? null ?>';
                            for (let i = 1; i <= 24; i++) {
                                const option = d.createElement('option');
                                const hoursText = i === 1 ? 'hour' : 'hours';
                                option.value = `${i} ${hoursText}`;
                                option.innerHTML = `${i} ${hoursText}`;
                                if(time == `${i} ${hoursText}`) {
                                     option.selected = true;
                                }
                                time_of_stay_select.appendChild(option);
                            }

                            time_of_stay_select.addEventListener('change', () => {
                                const time_of_stay_value = parseInt(time_of_stay_select.value.split(' ')[
                                    0]);
                                if (time_of_stay_value > 24) {
                                    amount_to_pay.value = 150;
                                    return
                                }
                                amount_to_pay.value = time_of_stay_value * 300;

                            })
                        })();
                        </script>
                        <div class="d-block d-md-flex justify-content-between align-items-center px-0">
                            <div class="my-2 col-12 col-md-5 px-0">
                                <small class="form-label fs-6 text-light" for="">Date</small>
                                <input value="<?php echo $data[0]['date'] ?? null ?>" autocomplete="off" required
                                    class="form-control form-control-md" type="date" name="date">
                            </div>
                            <div class="my-2  col-12 col-md-5 px-0">
                                <small class="form-label fs-6 text-light" for="">Time</small>
                                <input value="<?php echo $data[0]['time'] ?? null ?>" autocomplete="off" required
                                    class="form-control form-control-md" type="time" name="time">
                            </div>
                        </div>
                        <div class="my-2">
                            <small class="form-label fs-6 text-light" for="">Cabin No.</small>
                            <select name="cabin_no" class="form-select form-select-md">
                                <?php
                                    $sql = "SELECT * FROM cabin";
                                    $results = mysqli_query($conn, $sql);
                                    while ($row = mysqli_fetch_assoc($results)) {
                                    ?>
                                        <option <?php echo $row['cabin_no'] == $data[0]['cabin_no'] ? 'selected' : null ?> <?php echo $row['status'] == 'Disabled' ? 'disabled' : null ?> value="<?= $row['cabin_no']?>"><?= $row['cabin_no']?></option>
                                    <?php
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="my-2">
                            <small class="form-label fs-6 text-light" for="">Payment Method</small>
                            <select name="payment_method" class="form-select form-select-md">
                                <option value="Cash" <?php echo ($data[0]['payment_method'] ?? null) == 'Cash' ? 'selected' : null ?>>Cash</option>
                                <option value="Credit Card" <?php echo ($data[0]['payment_method'] ?? null) == 'Credit Card' ? 'selected' : null ?>>Credit Card</option>
                                <option value="Gcash" <?php echo ($data[0]['payment_method'] ?? null) == 'Gcash' ? 'selected' : null ?>>Gcash</option>
                                <option value="Paypal" <?php echo ($data[0]['payment_method'] ?? null) == 'Paypal' ? 'selected' : null ?>>Paypal</option>
                                <option value="Debit Card" <?php echo ($data[0]['payment_method'] ?? null) == 'Debit Card' ? 'selected' : null ?>>Debit Card</option>
                                <option value="UPI" <?php echo ($data[0]['payment_method'] ?? null) == 'UPI' ? 'selected' : null ?>>UPI</option>
                                <option value="Paymaya" <?php echo ($data[0]['payment_method'] ?? null) == 'Paymaya' ? 'selected' : null ?>>Paymaya</option>
                                <option value="Others" <?php echo ($data[0]['payment_method'] ?? null) == 'Others' ? 'selected' : null ?>>Others</option>
                            </select>
                        </div>
                        <div class="my-2 d-block d-md-flex align-items-center justify-content-between">
                            <div>
                                <small class="form-label fs-6 text-light" for="">Proof of payment</small>
                                <a href="../user/payments/<?php echo ($data[0]['proof_of_payment'] ?? null)  ?>">
                                    <img class="d-block rounded" title="click to view" width="200px" src="../user/payments/<?php echo ($data[0]['proof_of_payment'] ?? null)  ?>">
                                </a>
                            </div>

                            <div class="col px-0 pl-3">
                                <div>
                                    <small class="form-label fs-6 text-light" for="">Status</small>
                                    <select name="status" class="form-select form-select-md">
                                        <option value="Pending" <?php echo ($data[0]['status'] ?? null) == 'Pending' ? 'selected' : null ?>>Pending</option>
                                        <option value="Approved" <?php echo ($data[0]['status'] ?? null) == 'Approved' ? 'selected' : null ?>>Approved</option>
                                        <option value="Declined" <?php echo ($data[0]['status'] ?? null) == 'Declined' ? 'selected' : null ?>>Declined</option>
                                        <option value="Cancelled" <?php echo ($data[0]['status'] ?? null) == 'Cancelled' ? 'selected' : null ?>>Cancelled</option>
                                    </select>
                                </div>
                                <div class="mt-3">
                                    <small class="form-label fs-6 text-light" for="">Promo Code</small>
                                    <input value="<?php echo $data[0]['promo_code'] ?? null ?>" autocomplete="off" required
                                        class="form-control form-control-md" type="number" name="promo_code">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button class="btn btn-primary btn-sm" type="submit">Update booking</button>
                        </div>
                    </form>

                    <!-- end content -->
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- ============================================================== -->
    <!-- end wrapper  -->
    <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- end main wrapper  -->
    <!-- ============================================================== -->
    <script src="../assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <!-- bootstap bundle js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <!-- slimscroll js -->
    <script src="../assets/vendor/slimscroll/jquery.slimscroll.js"></script>
    <!-- main js -->
    <script src="../assets/libs/js/main-js.js"></script>
    <!-- chart chartist js -->
    <script src="../assets/vendor/charts/chartist-bundle/chartist.min.js"></script>
    <!-- sparkline js -->
    <script src="../assets/vendor/charts/sparkline/jquery.sparkline.js"></script>
    <!-- morris js -->
    <script src="../assets/vendor/charts/morris-bundle/raphael.min.js"></script>
    <script src="../assets/vendor/charts/morris-bundle/morris.js"></script>
    <!-- chart c3 js -->
    <script src="../assets/vendor/charts/c3charts/c3.min.js"></script>
    <script src="../assets/vendor/charts/c3charts/d3-5.4.0.min.js"></script>
    <script src="../assets/vendor/charts/c3charts/C3chartjs.js"></script>
    <script src="../assets/libs/js/dashboard.js"></script>
</body>

</html>