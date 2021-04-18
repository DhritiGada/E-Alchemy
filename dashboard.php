<?php
    require "includes/classes/Header.php";

    $header = new Header("Dashboard","dashboard.css");
    $header->output();
?>

<body>
    <?php
        require "includes/config.php";
        include "includes/components/navbar.php";

        // Redirect to homepage if user not logged in
        if(!isset($_SESSION["userLoggedInName"])){
            header("Location: homepage.php");
        }
?>
<div>
    <section id="myCourses">
        <div class="ui container">
            <div class="middle">
                <h1>My courses</h1><br>

                <?php
                $username = $_SESSION['userLoggedInName'];
                $result = mysqli_query($con, "SELECT * FROM users WHERE username = '$username'");
                $userId = mysqli_fetch_assoc($result)['id'];

                // Get all courses of current user
                $courseQuery = "SELECT * FROM enrolled WHERE userId = '$userId'";
                $courseResult = mysqli_query($con, $courseQuery);

                if (mysqli_num_rows($courseResult) == 0) {
                    echo "<h2>Congratulations for registering! Now Enroll in a course and start learning.</h2></br>";

                    // View courses button
                    echo "
                        <div class='button'>
                            <a class='ui primary button' id='view-courses-btn' href='" . ROOT_URL . "courses.php'>View Courses</a>
                        </div>";
                } else {
                    // User has enrolled in some courses
                    while ($course = mysqli_fetch_assoc($courseResult)) {

                        // Get course id
                        $courseId = $course['courseId'];

                        // Get course details
                        $myCourseResult = mysqli_query($con, "SELECT * FROM course WHERE id = '$courseId'");
                        $myCourse = mysqli_fetch_assoc($myCourseResult);

                        // Html card template
                        echo "<div class='ui link cards' id='myCoursesCards'>" .
                            "<div class='link card'>
                                <a class='image' href='lectures.php?id=" . $myCourse['id'] . "'>
                                    <img src='assets/courses/" . $myCourse['title'] . "/" . $myCourse['title'] . ".jpg'>
                                </a>
                                <div class='content'>
                                    <div class='header'>" . $myCourse['title'] . "</div>
                                    <div class='meta'>
                                        <a>" . $myCourse['category'] . "</a>
                                    </div><br>
                                </div>
                                <div class='extra content'>
                                        Continue Learning
                                </div>
                        </div>
                        </div>";
                    }
                }
                mysqli_close($con); 
                ?>
            </div>
        </div>
        
        </section>
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <!-- Footer -->
    <?php include("includes/components/footer.php"); ?>

        <?php 
            require("includes/classes/FooterLinks.php");

            $footerLinks = new FooterLinks("");
            $footerLinks->output();
        ?>

</body>

</html>