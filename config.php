<?php
session_start();
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'inntayan';
$conn = new mysqli($host, $user, $password);
if ($conn->connect_error) die('Database connection failed: ' . $conn->connect_error);
$query = "CREATE DATABASE IF NOT EXISTS $database";
if (!$conn->query($query)) {
    echo "Error creating database: " . $conn->error; 
}
$conn->close();
$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

$queryCreateTable = "CREATE TABLE IF NOT EXISTS accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    fullname VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    enable2FA VARCHAR(255) NOT NULL,
    address VARCHAR(500),
    contact VARCHAR(255),
    email VARCHAR(255),
    profile VARCHAR(255),
    role VARCHAR(255),
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

if (!$conn->query($queryCreateTable)) {
    die("Error creating table: " . $conn->error);
}

$queryCreateTable = "CREATE TABLE IF NOT EXISTS cabin_reservation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    cabin_no INT NOT NULL,
    promo_code VARCHAR(50) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    time_of_stay VARCHAR(50) NOT NULL,
    amount_to_pay VARCHAR(50) NOT NULL,
    proof_of_payment VARCHAR(500) NOT NULL,
    status VARCHAR(50) DEFAULT 'Pending' NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($queryCreateTable)) {
    die("Error creating table: " . $conn->error);
}



$queryCreateTable = "CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    description VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($queryCreateTable)) {
    die("Error creating table: " . $conn->error);
}


$queryCreateTable = "CREATE TABLE IF NOT EXISTS cabin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    status VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($queryCreateTable)) {
    die("Error creating table: " . $conn->error);
}