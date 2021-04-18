<?php

require "../../config.php";
require "../../classes/Constants.php";
require "../../classes/Course.php";

$course = new Course($con);

if (isset($_POST["title"])) {
    $title = $_POST["title"];
    $course->validateTitle($title);

    $errors = $course->getErrors();
    echo json_encode($errors);
}