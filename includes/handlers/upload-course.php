<?php

require "../config.php";
require "../classes/Course.php";
require "../classes/Constants.php";
require "../classes/Messages.php";

$course = new Course($con);

// Remove html tags
$title = strip_tags($_POST['title']);
$category = strip_tags($_POST['category']);
$instructions = strip_tags($_POST['instructions']);
$date = strip_tags($_POST['date']);
$requirements = strip_tags($_POST['requirements']);
$description = strip_tags($_POST['description']);
// $target = strip_tags($_POST['target']);
$duration = strip_tags($_POST['duration']);

// Uploaded files
$videos = $_FILES['videos'];
$image = $_FILES['image'];

// Get num of videos uploaded
$numOfVideos = count($videos['name']);

if ($course->validateAll($title, $videos, $image) == true) {

    if ($course->insertCourseToDatabase($title, $category, $instructions, $date, $requirements, $description, $numOfVideos,$duration) == true) {

        if ($course->insertVideoTitlesToDatabase($title, $videos) == true) {

            // move videos to server
            $course->moveVideos($videos, $title);

            // move img to server
            $course->moveImage($image, $title);

            // $courseIdQuery = "SELECT * FROM course WHERE title = '$title' AND category = '$category'";
            // $courseIdResult = mysqli_query($con, $courseIdQuery);
            // $courseId = mysqli_fetch_assoc($courseIdResult)["id"];
            // echo $courseId;

            // Success msg
            
            echo json_encode("success");

        } else {

            echo json_encode("Error inserting videos data into database!");
        }

    } else {
        echo json_encode("Error inserting data into database!");
    }
} else {
    $errors = '';

    // Get all errors
    foreach ($course->getErrors() as $error) {
        $errors = $errors . $error . "</br>";
    }
    echo json_encode($errors);
}

mysqli_close($con);