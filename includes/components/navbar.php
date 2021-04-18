<!-- Navbar -->
<nav class="ui menu">
    <div class="ui container">

        <!-- Logo -->
        <div class="logo">
            <?php
                // Change url to homepage or dashboard
                $url = ROOT_URL . "dashboard.php";

                if (!isset($_SESSION['userLoggedInName'])) {

                    if (!isset($_SESSION['adminLoggedInName'])){
                        $url = ROOT_URL;
                    }else{
                        $url = ROOT_URL . "adminpanel.php";
                    }
                 }
                echo "<a href='".$url."'>
                <img id='logo-icon' src='assets/images/seismic.png' class='logo-image'>
                <span id='subLogo'>&nbsp;</span> E-Alchemy </a>";
            ?>
        </div>

        <!-- Courses dropdown -->

        <div class="ui pointing dropdown" id="courses-dropdown">
            <span class="text">Courses</span>
            <i class="dropdown icon"></i>
            <div class="menu">
                <a class="item" href="<?php echo ROOT_URL ?>courses.php#computer-science">Computer Science</a>
                <a class="item" href="<?php echo ROOT_URL ?>courses.php#information technology">Information Technology</a>
                <a class="item" href="<?php echo ROOT_URL ?>courses.php#electronics & telecommunications">Electronics & Telecommunications</a>
                <a class="item" href="<?php echo ROOT_URL ?>courses.php#bio-medical">Bio-Medical</a></div></div>

        
        

        <!-- Search field -->
        <div class="ui search">
            <div class="ui icon input">
                <input class="prompt" type="text" placeholder="Search for courses" id="searchField">
                <i class="search icon"></i>
            </div>
            <div class="results"></div>
        </div>

        <!-- User Space -->

        <?php
            // Check if user is logged in
            if (!isset($_SESSION['userLoggedInName'])) {
                
                // Check if admin ?
                if (!isset($_SESSION['adminLoggedInName'])){

                    // Display login button
                    echo "<div class='buttons'>";
                    echo "<a class='ui basic grey button' id='loginBtn' href='login.php'>Log In</a>";
                    echo "<a class='ui primary button' id='signUpBtn' href='register.php'>Sign up</a>";
                    echo "</div>";
                }else{
                    // Admin logged in
                    echo "<a class='ui basic button' href='upload.php' id='uploadBtn'>Upload</a>";

                    echo
                    "<div class='ui pointing dropdown' id='userDrop' tabindex='0'>
                        <div class='text'>". $_SESSION['adminLoggedInName'] .
                    "</div>
                        <i class='dropdown icon'></i>
                        <div class='menu' tabindex='-1'>
                            <span class='text' id='user'></span>
                            <a class='item' href='adminpanel.php'>View all Courses</a>
                            <a class='item' href='includes/handlers/logout-handler.php'>Log out</a>
                        </div>
                    </div>";
                }
                    
            } else  {

            //Display dropdown with options..
            echo
                "<div class='ui pointing dropdown' id='userDrop' tabindex='0'>
                    <div class='text'>
                        <img class='ui avatar image' src='assets/images/profilePictures/" . $_SESSION['userLoggedInName'] . ".jpg'>" .
                $_SESSION['userLoggedInName'] .
                "</div>
                    <i class='dropdown icon'></i>
                    <div class='menu' tabindex='-1'>
                        <span class='text' id='user'></span>
                        <a class='item' href='dashboard.php'>My Courses</a>
                        <!-- <a class='item' href='#'>Edit Profile</a> -->
                        <a class='item' href='includes/handlers/logout-handler.php'>Log out</a>
                    </div>
                </div>";
            }
            ?>
    </div>
</nav>