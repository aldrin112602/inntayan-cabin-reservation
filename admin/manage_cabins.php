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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Cabins - INNTAYAN</title>
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


    <!-- custom styles -->
    <style>
    * {
        font-family: "Poppins", sans-serif;
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        transition: all 0.5s;
    }

    .nav-left-sidebar,
    .dashboard-wrapper {
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
                    <a class="d-xl-none d-lg-none" href="#">Manage Cabins</a>
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
                            <li class="nav-item my-1">
                                <a href="./booking_records.php"
                                    class="text-center text-white d-flex align-items-center justify-content-start gap-2 ml-4 fs-6">
                                    <span class="material-symbols-outlined">book</span>
                                    Booking records
                                </a>
                            </li>
                            <li class="nav-item my-1 current-page">
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
                                    <p>Please let us know if you encountered a problem while using the app. Your
                                        feedback helps improve the System.</p>
                                    <label for="bugTitle" class="form-label">Bug Title:</label>
                                    <input type="text" id="bugTitle" name="bugTitle" class="form-control rounded"
                                        required>
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

                    <?php
                    if(isset($_POST['cabin_status'])) {
                        $status = $_POST['cabin_status'];
                        $cabin_no = $_POST['cabin_no'];
                        if(isDataExists("cabin", "*", "cabin_no='$cabin_no'")) {
                            echo '<script>
                                $(document).ready(function() {
                                    Swal.fire({
                                        title: "Ops!",
                                        text: "Cabin no. already registered",
                                        icon: "error"
                                    }).then(() => {
                                        location.href = "./manage_cabins.php"
                                    });
                                })
                            </script>';
                        } else {
                            if(mysqli_query($conn, "INSERT INTO cabin(status, cabin_no) VALUES('$status', '$cabin_no')")) {
                                echo '<script>
                                    $(document).ready(function() {
                                        Swal.fire({
                                            title: "Success!",
                                            text: "Cabin added successfully",
                                            icon: "success"
                                        }).then(() => {
                                            location.href = "./manage_cabins.php"
                                        });
                                    })
                                </script>';
                            }
                        }
                        
                    }
                    ?>

                    <form action="#" method="post" class="modal fade" id="addCabin" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5 d-flex align-items-center justify-content-between gap-3"
                                        id="staticBackdropLabel">Add Cabin</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <label class="form-label">Cabin No.:</label>
                                    <input required type="number" class="form-control form-control-sm" name="cabin_no">

                                    <label class="form-label mt-3">Status:</label>
                                    <select required class="form-select form-select-sm" name="cabin_status">
                                        <option selected disabled class="d-none"></option>
                                        <option value="Enabled">Enabled</option>
                                        <option value="Disabled">Disabled</option>
                                    </select>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="page-header">
                                <div>
                                    <h2
                                        class="pageheader-title font-weight-bold text-light d-flex align-items-center justify-content-between">
                                        Manage Cabins
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#addCabin">Add Cabin</button>
                                    </h2>
                                </div>
                                <div class="page-breadcrumb">
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href=""
                                                    class="breadcrumb-link text-light">cabin</a>
                                            </li>
                                            <li class="breadcrumb-item active text-light" aria-current="page">Home</li>
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
                    $data = getRows(null, "cabin");
                    ?>
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Cabin No.</th>
                                <th scope="col">Status</th>
                                <th scope="col">Created_at</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($data as $row) {
                                echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td>{$row['cabin_no']}</td>
                                    <td>{$row['status']}</td>
                                    <td>{$row['created_at']}</td>
                                    <td>
                                        <a class='btn btn-primary btn-sm' href='#' onclick='showStatusToggleAlert({$row['id']})'>Update status</a>
                                        <a href='#' class='btn btn-danger btn-sm' onclick='removeCabin({$row['id']})'>Delete</a>
                                    </td>
                                </tr>";
                            }
                            ?>
                            <script>
                            function showStatusToggleAlert(id) {
                                Swal.fire({
                                    title: 'Toggle Status',
                                    input: 'select',
                                    inputOptions: {
                                        enable: 'Enable',
                                        disable: 'Disable',
                                    },
                                    inputPlaceholder: 'Select status',
                                    showCancelButton: true,
                                    inputValidator: (value) => {
                                        if (!value) {
                                            return 'You need to choose a status';
                                        }
                                    },
                                    preConfirm: (selectedStatus) => {
                                        const status = selectedStatus === 'enable' ? 'Enabled' : 'Disabled';
                                        const formData = new FormData();
                                        formData.append('id', id);
                                        formData.append('status', status);

                                        fetch('./toggle_cabin_status.php', {
                                                method: 'POST',
                                                body: formData,
                                            })
                                            .then(response => response.json())
                                            .then(data => {
                                                if (data.success) {
                                                    Swal.fire(
                                                        'Success!',
                                                        'Cabin status updated successfully',
                                                        'success'
                                                    ).then(function() {
                                                        location.reload();
                                                    })
                                                } else {
                                                    Swal.fire(
                                                        'Ops!',
                                                        'Failed to update cabin.',
                                                        'error'
                                                    )
                                                }
                                            });
                                    }
                                });
                            }

                            function removeCabin(id) {
                                Swal.fire({
                                    title: "Are you sure?",
                                    text: "You won't be able to revert this!",
                                    icon: "warning",
                                    showCancelButton: true,
                                    confirmButtonColor: "#3085d6",
                                    cancelButtonColor: "#d33",
                                    confirmButtonText: "Yes, delete it!"
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        const formData = new FormData();
                                        formData.append('id', id);

                                        fetch('./remove_cabin.php', {
                                                method: 'POST',
                                                body: formData,
                                            })
                                            .then(response => response.json())
                                            .then(data => {
                                                if (data.success) {
                                                    Swal.fire(
                                                        'Deleted!',
                                                        'Cabin has been removed successfully.',
                                                        'success'
                                                    ).then(function() {
                                                        location.reload();
                                                    })
                                                } else {
                                                    Swal.fire(
                                                        'Ops!',
                                                        'Failed to remove cabin.',
                                                        'error'
                                                    )
                                                }
                                            });
                                    }
                                });
                            }
                            </script>

                        </tbody>
                    </table>

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
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>
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