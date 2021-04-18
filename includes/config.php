<?php
session_start();

// Set timezone
$timezone = date_default_timezone_set("Indian/Mauritius");

// db connection
$con = mysqli_connect("localhost", "root", "", "e-learning");

// Check if db connection success
if (mysqli_connect_errno()) {
    die("Failed to connect: " . mysqli_connect_errno());
}

// Constant for root url
define("ROOT_URL", "http://localhost/E-Alchemy/");