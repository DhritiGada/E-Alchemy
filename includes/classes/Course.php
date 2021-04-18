<?php

class Course
{
    private $con; // db connection
    private $errorArray;

    public function __construct($con)
    {
        $this->con = $con;
        $this->errorArray = array();
    }

    public function validateTitle($title)
    {
        // Check length
        if (strlen($title) > 25 || strlen($title) < 5) {
            array_push($this->errorArray, Constants::$titleCharacters);
            return;
        }
        // Check if title exists
        $checkTitleQuery = mysqli_query($this->con, "SELECT title FROM course WHERE title = '$title';");

        if (mysqli_num_rows($checkTitleQuery) != 0) {
            array_push($this->errorArray, Constants::$courseTaken);
            return;
        }

    }
    public function validateVideos($videos)
    {

        // Valid file extensions
        $videoFileType = array("mp4");

        for ($i = 0; $i < count($videos["name"]); $i++) {
            // Select file type
            $file_extension = strtolower(pathinfo($videos["name"][$i], PATHINFO_EXTENSION));

            // Check extension
            if (!in_array($file_extension, $videoFileType)) {
                array_push($this->errorArray, "Video named: " . $videos["name"][$i] . " is invalid. " . Constants::$invalidVideoFormat);
                return;
            }
        }

    }

    public function moveVideos($videos, $courseTitle)
    {
        // path to videos: folder name = course title
        $path = "..//..//assets//courses//" . $courseTitle;

        // Create path
        if (!file_exists($path) && !is_dir($path)) {
            mkdir($path);
        }

        for ($i = 0; $i < count($videos["name"]); $i++) {

            // Select file type
            $file_extension = strtolower(pathinfo($videos["name"][$i], PATHINFO_EXTENSION));

            // Move video to path
            move_uploaded_file($videos['tmp_name'][$i], $path . "//" . $videos["name"][$i]);
        }
    }

    public function validateImage($image)
    {
        // Select file type
        $file_extension = strtolower(pathinfo($image["name"], PATHINFO_EXTENSION));

        // Valid file extensions
        $imageFileType = array("jpg");

        // Check extension
        if (!in_array($file_extension, $imageFileType)) {
            array_push($this->errorArray, Constants::$invalidImageFormat);
            return;
        }
        if (($image["size"] > 2000000)) {
            array_push($this->errorArray, Constants::$imageTooLarge);
            return;
        }
    }

    public function moveImage($image, $courseTitle)
    {
        // Select file type
        $file_extension = strtolower(pathinfo($image["name"], PATHINFO_EXTENSION));

        // path to folder
        $path = "..//..//assets//courses//" . $courseTitle;

        // Move video to path
        move_uploaded_file($image['tmp_name'], $path . "//" . $courseTitle . '.' . $file_extension);
    }

    public function insertCourseToDatabase($title, $category, $instructions, $date, $requirements, $description,$numOfVideos,$duration)
    {
        $date = date("Y/m/d");

        // Insert values to db
        $result = mysqli_query($this->con, "INSERT INTO course VALUES ('', '$title', '$category', '$instructions','$date','$requirements', '$description','$numOfVideos','$duration','$date')");
        return $result;
    }

    public function insertVideoTitlesToDatabase($title, $videos)
    {
        $insertQuery = "";

        $idQuery = "SELECT id FROM course WHERE title = '$title'";
        $result = mysqli_query($this->con, $idQuery);
        $courseId = mysqli_fetch_assoc($result)['id'];

        for ($i = 0; $i < count($videos["name"]); $i++) {

            // Remove extension from name
            $videoName = str_replace(".mp4", '', $videos["name"][$i]);

            $insertQuery .= "INSERT INTO videos VALUES ('$courseId','$videoName');";

        }

        $insertionWasSuccessful = mysqli_multi_query($this->con, $insertQuery);
        return $insertionWasSuccessful;
    }

    public function deleteCourseFromDatabase($courseId,$courseName){  
        $deleteQueries = "";
        
        $videosQuery = "SELECT * FROM videos WHERE courseId = '$courseId'";
        $videosResult = mysqli_query($this->con,$videosQuery);
        
        while ($video = mysqli_fetch_assoc($videosResult)) {
            $videoTitle = $video["videoTitle"];
            $deleteQueries .= "DELETE FROM videos WHERE courseId = '$courseId' AND videoTitle = '$videoTitle';";
        }
    
        $deleteQueries .= "DELETE FROM enrolled WHERE courseId = '$courseId';";
        $deleteQueries .= "DELETE FROM course WHERE id = '$courseId';";
        
        $result = mysqli_multi_query($this->con,$deleteQueries);

        return $result;
    }

    public function deleteDirectoryWithFiles($str) {
        //It it's a file.
        if (is_file($str)) {
            //Attempt to delete it.
            return unlink($str);
        }
        //If it's a directory.
        elseif (is_dir($str)) {
            //Get a list of the files in this directory.
            $scan = glob(rtrim($str,'/').'/*');
            //Loop through the list of files.
            foreach($scan as $index=>$path) {
                //Call our recursive function.
                $this->deleteDirectoryWithFiles($path);
            }
            //Remove the directory itself.
            return @rmdir($str);
        }
    }

    public function validateAll($title, $videos, $image)
    {
        $this->validateTitle($title);
        $this->validateVideos($videos);
        $this->validateImage($image);

        return empty($this->errorArray);
    }

    public function getErrors()
    {
        return $this->errorArray;
    }
}