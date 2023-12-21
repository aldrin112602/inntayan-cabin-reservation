<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register | Inntayan</title>
    <link rel="stylesheet" href="src/bootstrap.min.css" />
    <script src="./src/jquery.min.js"></script>
    <script src="./src/sweetalert2/sweetalert2.all.min.js"></script>
    <!-- Favicon -->
    <link rel="icon" href="img/favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon" />

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

    <!-- custom styles -->
    <style>
    * {
        font-family: "Poppins", sans-serif;
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        transition: all 0.5s;
    }

    .form {
        background-color: rgba(0, 0, 0, 0.8);
        border-radius: 50px;
        box-shadow: 0 0 10px 100vw rgba(0, 0, 20, 0.7);
        backdrop-filter: blur(1px);
    }

    .form .input {
        background-color: rgba(255, 255, 255, 0.2);
        border-radius: 70px;
        color: white;
        border: 0;
    }

    div img {
        z-index: 100;
    }

    .form button {
        background-color: #1D5B79;
        border-radius: 70px;
        color: white;
        width: 50%;
    }

    .form button:hover {
        border: 1px solid white;
    }
    </style>
</head>

<body>
    <?php 
        require_once './handle_register.php';
    ?>
    <div style="
        min-height: 100vh;
        /* background: url(img/bg.jpeg); */
        background-repeat: no-repeat;
        background-size: 100% 100%;
        background-image: linear-gradient(-45deg, #FFE5E5, #AC87C5);
      " class="w-full d-flex align-items-center justify-content-center flex-column px-3">
        <img src="img/logo.png" alt="logo" class="img-fluid mb-0" width="200px">
        <form action="" method="post" class="form p-5 text-white col-12 col-md-4 mb-4">
            <h1 class="text-center fw-bolder">Register</h1>
            <div class="container my-3">
                <small for="fullname" class="mx-2" style="letter-spacing: 1px;">FULLNAME</small>
                <input value="<?php echo $fullname ?? null ?>" required type="text" class="form-control input"
                    id="fullname" name="fullname">
            </div>
            
            <div class="container my-3">
                <small for="email" class="mx-2" style="letter-spacing: 1px;">EMAIL</small>
                <input value="<?php echo $email ?? null ?>" required type="text" class="form-control input"
                    id="email" name="email">
            </div>
            <div class="container my-3">
                <small for="username" class="mx-2" style="letter-spacing: 1px;">USERNAME</small>
                <input value="<?php echo $username ?? null ?>" required type="text" class="form-control input"
                    id="username" name="username">
            </div>
            <div class="container my-3">
                <small for="username" class="mx-2" style="letter-spacing: 1px;">PASSWORD</small>
                <input value="<?php echo $password ?? null ?>" required type="password"
                    class="form-control input" id="password" name="password">
            </div>
            <div class="container my-3">
                <div class="form-check">
                    <input required class="form-check-input" type="checkbox" value="" id="termsOfService">
                    <label class="form-check-label" for="termsOfService">
                        <small><a href="#" class="text-muted">Agree to Tersm and Privacy Policy</a></small>
                    </label>
                </div>
            </div>
            <div class="container my-3 text-center">
                <button type="submit" class="btn mb-3 btn-sm">Signup</button>
                <p>Already registered? <a href="./login.php" class="nav-link btn d-inline text-primary">Login now</a></p>
            </div>
        </form>
    </div>
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
</body>

</html>