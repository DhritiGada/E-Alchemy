<?php
require "../config.php";

// Check if a user is logged in
if (isset($_SESSION["userLoggedInName"])) {

    $username = $_SESSION["userLoggedInName"];

    // Get id of user currently logged in
    $result = mysqli_query($con, "SELECT * FROM users WHERE username = '$username'");
    $userId = mysqli_fetch_assoc($result)["id"];

    // Get course id from enroll button
    $courseId = $_POST["enrollBtn"];

    $date = date("Y/m/d");

    // Insert into enrolled table
    $insertQuery = "INSERT INTO enrolled VALUES ('$userId','$courseId','$date')";
    $wasInserted = mysqli_query($con, $insertQuery);

    if ($wasInserted) {
        // Go to dashboard
        header("Location: ../../dashboard.php");
    } else {
        echo json_encode("Error inserting to database!");
    }
} else {
    // Go to login page
    header("Location: ../../login.php");
}

// Close db connection
mysqli_close($con);