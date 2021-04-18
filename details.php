<?php
    require "includes/config.php";
    require "includes/classes/Header.php";

    // Get Course id from url
    $courseId = $_GET['id'];
    $courseIdquery = "SELECT * FROM course WHERE id = '$courseId'";
    $courseIdResult = mysqli_query($con,$courseIdquery);
    $course = mysqli_fetch_assoc($courseIdResult);

    $header = new Header($course['title'],"course-details.css");
    $header->output();

?>

<body>
    <!-- Navbar -->
    <?php include('includes/components/navbar.php'); ?>

    <div class="main">
        <div class="ui container">
            <div class="left">
                <div class="ui fluid styled accordion">
                    <div class="title">
                        <i class="dropdown icon"></i>
                        Requirements
                    </div>
                    <div class="content">
                        <p class="transition hidden">
                            <ul>
                                <?php 
                                // Explode string to array
                                    $requirements = explode(".",$course['requirements']);

                                    for ($i=0; $i < sizeof($requirements)-1; $i++) { 
                                        echo "<li>".$requirements[$i]."</li>";
                                    }
                                ?>
                            </ul>
                        </p>
                    </div>
                    <div class="title">
                        <i class="dropdown icon"></i>
                        Description
                    </div>
                    <div class="content">
                        <p class="transition hidden">
                            <ul>
                                <?php
                                // Explode string to array
                                $descriptions = explode(".",$course['description']);

                                for ($i=0; $i < sizeof($descriptions)-1; $i++) { 
                                    echo "<li>".$descriptions[$i]."</li>";
                                }
                            ?>
                            </ul>
                        </p>
                    </div>
                    <!-- <div class="title">
                        <i class="dropdown icon"></i>
                        Target audience
                    </div>  -->
                    <div class="content">
                        <p class="transition hidden">
                            <ul>
                                <?php 
                                // Explode string to array
                                    $audiences = explode(".",$course['target']);
                                    for ($i=0; $i < sizeof($audiences)-1; $i++) { 
                                        echo "<li>" .$audiences[$i]."</li>";
                                    }
                                ?>
                            </ul>
                        </p>
                    </div>
                </div>
            </div>

            <aside>
                <h1 class="title"> <?php echo $course['title'] ?></h1>

                <!-- Image -->
                <!-- Path of image -->
                <img src="assets/courses/<?php $path = $course['title'] .'//'.$course['title'].'.jpg'; echo $path;?>"
                    alt="">

                <div class="ui vertical steps">
                    <div class="completed step">
                        <i class="truck icon"></i>
                        <div class="content">
                            <div class="title">Enrolments</div>
                            <div class="description">
                                <?php 
                                // Check number of enrollments
                                    $enrolmentsQuery = "SELECT * FROM enrolled WHERE courseId = '$courseId'";
                                    $enrolmentsQueryResult = mysqli_query($con, $enrolmentsQuery);
                                    $numberOfEnrolments = mysqli_num_rows($enrolmentsQueryResult);

                                    if($numberOfEnrolments <= 1){
                                        echo $numberOfEnrolments . " enrolment";
                                    }else{
                                        echo $numberOfEnrolments . " enrolments";
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="completed step">
                        <i class="credit card icon"></i>
                        <div class="content">
                            <div class="title">Duration</div>
                            <div class="description"><?php echo $course['duration'] ." "?>Hours</div>
                        </div>
                    </div>
                    <div class="completed step">
                        <i class="info icon"></i>
                        <div class="content">
                            <div class="title">Uploaded On</div>
                            <div class="description"><?php echo $course['date'] ?></div>
                        </div>
                    </div>
                    <div class="completed step">
                        <i class="info icon"></i>
                        <div class="content">
                            <div class="title">Instructions</div>
                            <div class="description"><?php echo $course['instructions'] ?></div>
                        </div>
                    </div>
                    <div class="completed step">
                        <i class="truck icon"></i>
                        <div class="content">
                            <div class="title">Videos</div>
                            <div class="description"><?php echo $course['numofvideos'] ?></div>
                        </div>
                    </div>
                </div>

                <form action="includes/handlers/enroll-course.php" method="POST">

                    <?php 
                    // check if user is already enrolled in course
                    if(isset($_SESSION['userLoggedInName'])){

                        $username = $_SESSION['userLoggedInName'];

                        $userIdResult = mysqli_query($con, "SELECT * FROM users WHERE username = '$username'");
                        $userId = mysqli_fetch_assoc($userIdResult)['id'];

                        // Get course id from url
                        $courseId = $_GET['id'];

                        $enrolledCheckQuery = "SELECT * FROM enrolled WHERE userId = '$userId' AND courseId = '$courseId'";
                        $enrolledCheckQueryResult = mysqli_query($con,$enrolledCheckQuery);

                        if(mysqli_num_rows($enrolledCheckQueryResult) == 0 ){

                            // User is not enrolled
                            echo " <button class='positive ui button' type='submit' name='enrollBtn' value='". $courseId ."'>Enroll Now</button>";
                        }else {
                            echo " <button class='ui disabled button'>Already enrolled.</button>";
                        }
                        // Prevent admin from enrolling in course
                    }else if(!isset($_SESSION['adminLoggedInName'])){
                        echo" <button class='positive ui positive button' type='submit' name='enrollBtn' value='". $courseId ."'>Enroll Now</button>";
                    }
                ?>
                </form>
            </aside>
        </div>
        <div class="clearfix"></div>
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
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

    <script>
    $(".ui.dropdown").dropdown();
    $('.ui.accordion').accordion('open', 1);
    </script>

</body>

</html>