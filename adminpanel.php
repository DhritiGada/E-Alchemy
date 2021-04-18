<?php
    require "includes/classes/Header.php";

    $header = new Header("Admin","adminpanel.css");
    $header->output();
?>

<body>
    <?php
    require "includes/config.php";
    include "includes/components/navbar.php";
    require "includes/classes/Course.php";

    // check if admin is logged in
        if(!isset($_SESSION["adminLoggedInName"])){
            header("Location: homepage.php");
        }

        if(isset($_GET["deleteid"])){

            $course = new Course($con);
            
            $courseIdToDelete = $_GET["deleteid"];
            
            $courseNameResult = mysqli_query($con,"SELECT title FROM course WHERE id = '$courseIdToDelete'");
            $courseName = mysqli_fetch_assoc($courseNameResult)["title"];

            $wasDeleted = $course->deleteCourseFromDatabase($courseIdToDelete,$courseName);
        
        if(!$wasDeleted){
            echo "Course not deleted";            
        }else{
            
            // Remove directory from xammp
            $course->deleteDirectoryWithFiles("assets/courses/".$courseName);
            
            // Refresh page
            header("Refresh:0; url=adminpanel.php");
        }
    }


    ?>
    <section id="adminpanel">
        <div class="ui container">
            <div class="ui divided items">
                <h2>All courses</h2>

                <?php 
                    $coursesResult = mysqli_query($con,"SELECT * FROM course");
                    
                    if($coursesResult){
                        
                        while($course = mysqli_fetch_assoc($coursesResult)){
                            // Html list template
                            echo 
                                "<div class='item'>   
                                    <div class='left'>
                                            <div class='ui tiny image'>
                                                <img src='assets/courses/".$course["title"]."/" .$course["title"].".jpg' alt=''>
                                            </div>
                                            <h3>".$course["title"]."</h3>
                                        </div>
                                
                                    <div class='right'>
                                        <div class='button'>
                                            <a class='ui primary button' href='". ROOT_URL ."details.php?id=".$course["id"]."'>View</a>
                                        </div>
                                        <form action='adminpanel.php' method='GET'>
                                            <button class='ui red button' type='submit' name='deleteid' value='". $course["id"] ."'>Delete</button>
                                        </form>
                                    </div>
                                </div>";
                        }
                    }
                ?>
            </div>
        </div>
    </section>



    <!-- Footer -->
    <?php include("includes/components/footer.php"); ?>

    <?php 
        require("includes/classes/FooterLinks.php");

        $footerLinks = new FooterLinks("");
        $footerLinks->output();
    ?>

</body>

</html>