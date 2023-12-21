<?php 
    require_once './config.php';
    require_once './global.php';

    if(isset($_SESSION['role'])) {
        if($_SESSION['role'] == 'admin') {
            header('location: ./admin/');
        } else {
            header('location: ./user/');        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WELCOME TO INNTAYAN</title>
    <link rel="stylesheet" href="src/bootstrap.min.css" />
    <!-- Favicon -->
    <link rel="icon" href="img/favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon" />
    <script src="src/bootstrap.bunddle.min.js"></script>
    <script src="src/jquery.min.js"></script>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
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

    body::-webkit-scrollbar {
        width: 0;
    }
    </style>
</head>

<body>
    <div style="min-height: 100vh;" class="position-relative">
        <img src="./img/bg.jpeg" alt="" class="img-fluid position-absolute"
            style="top: 0; left: 0; filter: brightness(30%); height: 100%; width: 100%;">


        <div id="carouselPage" class="carousel carousel-white slide pt-4 position-relative" data-bs-ride="carousel"
            style="min-height: 100%;">
            <div class="d-flex align-items-center justify-content-end px-5">
                <a href="./login.php" role="button" class="btn text-white border border-3"
                    style="z-index: 100; letter-spacing: 3px; border-radius: 50px;">LOGIN</a>
            </div>

            <div class="carousel-inner">
                <div class="carousel-item active" data-bs-interval="10000">
                    <div class="row gap-3 px-5 justify-content-around">
                        <div class="col-12 col-md-5 text-white px-md-5">
                            <img src="./img/logo.png" class="img-fluid" width="60%"><br>
                            <h1 style="letter-spacing: 5px;" class="mt-4">
                                ITRODUCING <br>
                                <span style="letter-spacing: 2px; font-size: 7rem; color: #009FBD;">PIMS</span>
                            </h1>
                            <h4 style="line-height: 35px;">
                                The Future of Population Management: Tanay Municipality's Information Monitoring
                                System
                            </h4>
                        </div>
                        <div class="col-12 col-md-5 px-md-5 d-sm-none d-md-block    ">
                            <img src="./img/home icon.png" class="img-fluid"><br>
                        </div>
                    </div>
                    <br>
                </div>


                <div class="carousel-item" data-bs-interval="4000">
                    <img src="./img/logo.png" class="img-fluid text-start" style="margin-left: 100px;"
                        width="300px">
                    <div class="row gap-3 px-5 justify-content-around mt-5">
                        <div class="col-12 col-md-6 text-white px-md-5">
                            <div
                                style="max-width:100%;list-style:none; transition: none;overflow:hidden;width:100%;height:400px;">
                                <div id="google-maps-canvas" style="height:100%; width:100%;max-width:100%;"
                                    class="rounded overflow-hidden">
                                    <iframe style="height:100%;width:100%;border:0;" frameborder="0"
                                        src="https://www.google.com/maps/embed/v1/place?q=Rizal,+Tanay,+Philippines&key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8"></iframe>
                                </div>
                                <style>
                                #google-maps-canvas img {
                                    max-height: none;
                                    max-width: none !important;
                                    background: none !important;
                                }
                                </style>
                            </div>
                        </div>
                        <div class="col-12 col-md-5 px-md-5 bg-white rounded p-4 text-center">
                            <h1 class="fw-bold">
                                Tanay, Rizal
                            </h1>
                            <br><br>
                            <h2 class="fs-1">
                                <?php echo number_format(getPopulation()) ?> <br>
                                <span class="fw-bold">Population</span> <br>
                                <br><br>
                                <span class="mt-5 d-block">as of <?php echo date("F d, Y") ?></span>
                            </h2>
                        </div>
                    </div>
                    <br><br><br><br>
                </div>




                <div class="carousel-item text-white" data-bs-interval="4000">
                    <img src="./img/logo.png" class="img-fluid text-start" style="margin-left: 100px;"
                        width="300px">
                    <h1 class="text-center">Key Features</h1>
                    <div class="row gap-3 px-5 justify-content-around">
                        <div
                            class="col p-md-5 text-center d-flex align-items-center justify-content-between flex-column">
                            <img src="./img/icons (3).png" width="100%" height="200px" class="img-fluid">
                            <h4>Real-time Updates</h4>
                        </div>

                        <div
                            class="col p-md-5 text-center d-flex align-items-center justify-content-between flex-column">
                            <img src="./img/icons (4).png" width="100%" height="200px" class="img-fluid">
                            <h4>Effortless Data Collection</h4>
                        </div>

                        <div
                            class="col p-md-5 text-center d-flex align-items-center justify-content-between flex-column">
                            <img src="./img/icons (2).png" width="100%" height="200px" class="img-fluid">
                            <h4>User-friendly Interface</h4>
                        </div>

                        <div
                            class="col p-md-5 text-center d-flex align-items-center justify-content-between flex-column">
                            <img src="./img/icons (1).png" width="100%" height="200px" class="img-fluid">
                            <h4>Secure Data Management</h4>
                        </div>
                    </div>
                    <br>
                </div>



                <div class="carousel-item pb-5" data-bs-interval="4000">
                    <img src="./img/logo.png" class="img-fluid text-start" style="margin-left: 100px;"
                        width="300px">
                    <div class="text-center text-white pb-5">
                        <h1 class="fw-bold">About</h1>
                        <h3>
                            The Population Information Monitoring System is a game-changer <br>
                            for Tanay, Rizal Municipality's Population Office, by enhancing their <br>
                            capacity to make data-driven decisions and ultimately create a <br>
                            towards a smarter, more informed future. Explore the <br>
                            possibilities with our system today!
                            </p>
                    </div>
                </div>



                <div class="carousel-item" data-bs-interval="4000">
                    <div class="px-4 px-md-5 mx-md-5">
                        <img src="./img/logo.png" class="img-fluid" width="300px">
                    </div>
                    <div class="text-white pb-5 px-4 px-md-5 mx-md-5">
                        <h1 class="fw-bold mt-4">Let us help you.</h1>
                        <h4>Reach out for an exploratory conversation.</h4>
                        <h4 class="text-primary fw-bold my-4">
                            PHONE
                        </h4>
                        <h4>(+63) 951-279-3354</h4><br>
                        <h4 class="text-primary fw-bold my-4">
                            EMAIL
                        </h4>
                        <h4>caballeroaldrin02@gmail.com</h4><br>
                        <h4 class="text-primary fw-bold my-4">
                            SOCIAL
                        </h4>
                        <div class="d-flex align-items-center justify-content-start gap-3">
                            <a href="#" class="nav-link fs-3">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="nav-link fs-3">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="nav-link fs-3">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-indicators position-fixed" style="bottom: 30px;">
                <button type="button" data-bs-target="#carouselPage" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselPage" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselPage" data-bs-slide-to="2" aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#carouselPage" data-bs-slide-to="3" aria-label="Slide 4"></button>
                <button type="button" data-bs-target="#carouselPage" data-bs-slide-to="4" aria-label="Slide 5"></button>
            </div>
        </div>
    </div>
</body>

</html>