<?php
    require "includes/classes/Header.php";

    $header = new Header("Lectures","lectures.css");
    $header->output();
?>

<body>
    <?php 
        require "includes/config.php"; 
        include("includes/components/navbar.php");

        // Redirect user if not logged in
        if(!isset($_SESSION["userLoggedInName"])){
            header("Location: homepage.php");
        }
    ?>

    <!-- Video -->
    <div class="right">
        <video src="" controls id="video">

        </video>
    </div>

    <div class="left">
        <!-- Course title -->
        <div class="title">
            <?php       
                // Fetch course title  
                $courseId = $_GET['id'];
                $courseIdQuery = "SELECT title FROM course WHERE id = '$courseId'"; 
                $courseIdResult = mysqli_query($con,$courseIdQuery);
                $course = mysqli_fetch_assoc($courseIdResult);

                echo "<h1 id='courseName'>". $course['title'] ."</h1>";
             ?>
        </div>



        <hr><br>
        <!-- video titles -->
        <div class="ui small divided vertical list">

            <?php 
                // Fetch all videos titles
                $courseId = $_GET['id'];
                $videosQuery = "SELECT * FROM videos WHERE courseId = '$courseId'";

                $videosResult = mysqli_query($con,$videosQuery);
                while($video = mysqli_fetch_assoc($videosResult)){
                    echo "<a class='item'>".$video['videoTitle'] . "</a>";
                }
            ?>
        </div>
        </div>
        <div>

        <iframe src="https://docs.google.com/forms/d/e/1FAIpQLSeMjUNmnKdeQC3U8uxKxA9xBf__jzudyU7dJtENud3VadEdkA/viewform?embedded=true" width="640" height="2152" frameborder="0" marginheight="0" marginwidth="0">Loading…</iframe>
        </div>
    <!-- Clear float -->
    <div class="clearfix">
    </div>

    <!-- Footer -->
    <?php include("includes/components/footer.php"); ?>

    <?php 
        require("includes/classes/FooterLinks.php");

        $footerLinks = new FooterLinks("lectures.js");
        $footerLinks->output();
    ?>

</body>

</html>