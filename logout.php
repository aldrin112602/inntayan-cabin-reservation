<?php 
    session_start();
    require_once './audit_trails.php';
    logUser($_SESSION[ 'username' ], 'User logged out successfully.');
    
    session_destroy();

    header("location: ./index.php");