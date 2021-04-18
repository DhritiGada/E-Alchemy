<?php
require "../config.php";

if (isset($_POST["search-value"])) {
    $searchTarget = $_POST["search-value"];

    $query = "SELECT * FROM course WHERE title LIKE '%$searchTarget%'";
    $searchResult = mysqli_query($con, $query);

    $courses = array();

    while ($course = mysqli_fetch_assoc($searchResult)) {
        array_push($courses, $course);
    }

    echo json_encode($courses);

    // Close db connection
    mysqli_close($con);
}