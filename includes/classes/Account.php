<?php

class Account
{
    private $con; // db connection
    private $errorArray;

    public function __construct($con)
    {
        $this->con = $con;
        $this->errorArray = array();
    }

    public function validateUserLogin($username, $password)
    {
        // Md5 encryption
        $password = md5($password);

        // Check if username & password exists
        $query = mysqli_query($this->con, "SELECT * FROM users WHERE username='$username' AND pwd='$password'");

        if (mysqli_num_rows($query) == 1) {
            return true;
        } else {
            array_push($this->errorArray, Constants::$loginFailed);
            return false;
        }
    }
    public function validateAdminLogin($username, $password)
    {
        // Md5 encryption
        $password = md5($password);

        // Check if username & password exists
        $query = mysqli_query($this->con, "SELECT * FROM administrator WHERE username='$username' AND pwd='$password'");

        if (mysqli_num_rows($query) == 1) {
            return true;
        } else {
            array_push($this->errorArray, Constants::$loginFailed);
            return false;
        }
    }

    public function validateUsername($username)
    {
        // Check if username is alphanumeric
        if (preg_match('/[^A-Za-z0-9]/', $username)) {
            array_push($this->errorArray, Constants::$usernameNotAlphanumeric);
            return;
        }
        // Check length
        if (strlen($username) > 25 || strlen($username) < 5) {
            array_push($this->errorArray, Constants::$usernameCharacters);
            return;
        }
        // Check if username exists
        $checkUsernameQuery = mysqli_query($this->con, "SELECT username FROM users WHERE username='$username'");

        if (mysqli_num_rows($checkUsernameQuery) != 0) {
            array_push($this->errorArray, Constants::$usernameTaken);
            return;
        }

    }

    public function validateFirstName($firstName)
    {
        // Check length
        if (strlen($firstName) > 25 || strlen($firstName) < 2) {
            array_push($this->errorArray, Constants::$firstNameCharacters);
            return;
        }
    }

    public function validateLastName($lastName)
    {
        // Check length
        if (strlen($lastName) > 25 || strlen($lastName) < 2) {
            array_push($this->errorArray, Constants::$lastNameCharacters);
            return;
        }
    }

    public function validateEmail($email)
    {
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($this->errorArray, Constants::$emailInvalid);
            return;
        }

        // Check if email already exists
        $checkEmailQuery = mysqli_query($this->con, "SELECT email FROM users WHERE email='$email'");

        if (mysqli_num_rows($checkEmailQuery) != 0) {
            array_push($this->errorArray, Constants::$emailTaken);
            return;
        }

    }

    public function validatePassword($password)
    {
        // Check if password is alphanumeric
        if (preg_match('/[^A-Za-z0-9]/', $password)) {
            array_push($this->errorArray, Constants::$passwordNotAlphanumeric);
            return;
        }
        // Check length
        if (strlen($password) > 30 || strlen($password) < 5) {
            array_push($this->errorArray, Constants::$passwordCharacters);
            return;
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
        // Check size of img
        if (($image["size"] > 2000000)) {
            array_push($this->errorArray, Constants::$imageTooLarge);
            return;
        }
        return true;
    }

    public function validateCaptcha($responseKey)
    {
        // Get ip address of user
        $userIP = $_SERVER['REMOTE_ADDR'];

        // Url for captcha verification
        $url = "https://www.google.com/recaptcha/api/siteverify?secret=" . Constants::$captchaSecretKey . "&response=$responseKey&remoteip=$userIP";

        $response = file_get_contents($url);
        $response = json_decode($response);

        // Check response
        return $response->success;
    }

    public function register($firstName, $lastName, $username, $email, $password)
    {
        // Encrypt password
        $encryptedPwd = md5($password);
        $date = date("Y/m/d");

        // Insert values to db
        $result = mysqli_query($this->con, "INSERT INTO users VALUES ('', '$firstName', '$lastName', '$username','$email', '$encryptedPwd','$date')");
        return $result;
    }

    public function getErrors()
    {
        return $this->errorArray;
    }
}