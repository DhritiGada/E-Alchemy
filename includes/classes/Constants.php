<?php
class Constants
{

    // Registration errors
    public static $passwordsDoNoMatch = "Your passwords don't match";
    public static $passwordNotAlphanumeric = "Your password can only contain numbers and letters";
    public static $passwordCharacters = "Your password must be between 5 and 30 characters";
    public static $emailInvalid = "Email is invalid";
    public static $emailTaken = "This email is already in use";
    public static $lastNameCharacters = "Your last name must be between 2 and 25 characters";
    public static $firstNameCharacters = "Your first name must be between 2 and 25 characters";
    public static $usernameNotAlphanumeric = "Your username can only contain numbers and letters";
    public static $usernameCharacters = "Your username must be between 5 and 25 characters";
    public static $usernameTaken = "This username already exists";

    // Login errors
    public static $loginFailed = "Your username or password was incorrect";

    // Recaptcha key
    public static $captchaSecretKey = '6LeKFY4UAAAAACm-gpvwPuvzbMoZ3ktLm8fVNnVy';

    // Image errors
    public static $invalidImageFormat = "Your image must end with .jpg";
    public static $imageTooLarge = "Your image size is greater than 2mb! Upload another one.";

    // Course errors
    public static $titleCharacters = "Your course title must be between 5 and 30 characters";
    public static $courseTaken = "This course title already exists";
    public static $invalidVideoFormat = "Your video must end with .mp4";
    public static $moveVideoError = "Error moving video to server.";

}