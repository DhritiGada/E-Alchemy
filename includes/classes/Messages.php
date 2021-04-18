<?php
class Messages
{

    public static function setMsg($text, $type)
    {
        // Check type of message (then) create session
        if ($type == 'error') {
            $_SESSION['errorMsg'] = $text;
        } else {
            $_SESSION['successMsg'] = $text;
        }
    }

    public static function display()
    {
        // Check if message session exists
        // Display message accordingly

        if (isset($_SESSION['errorMsg'])) {
            echo '<div class="ui red message">' . $_SESSION['errorMsg'] . '</div>';
            unset($_SESSION['errorMsg']);
        }

        if (isset($_SESSION['successMsg'])) {
            echo '<div class="ui blue message">' . $_SESSION['successMsg'] . '</div>';
            unset($_SESSION['successMsg']);
        }
    }
}