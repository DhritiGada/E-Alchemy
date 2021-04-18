<?php
    require("includes/classes/Header.php");

    $header = new Header("E-Alchemy","homepage.css");
    $header->output();
?>

<body>

    <?php
        require "includes/config.php";
        require "includes/components/navbar.php";
    ?>

    <section id="heroArea">
        <div class="ui container">
            <div class="mainTitle">

                <!-- Website Motto -->
                <h1><span>E-Alchemy</span>&nbsp; Learn.Excel.Achieve. </h1>

                <!-- Typing effect text -->
                <div class="subTitle">
                    <span>Learn &nbsp;</span>
                    <span id="type-string"></span>
                </div>

                <!-- View courses btn -->
                <div class="button">
                    <!-- Link to course.php -->
                    <a class="ui primary button" href="<?php echo ROOT_URL ?>courses.php">View Courses</a>
                </div>

            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include("includes/components/footer.php"); ?>

    <!-- Typed.js -->
    <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.9"></script>

    <?php 
        require("includes/classes/FooterLinks.php");

        $footerLinks = new FooterLinks("homepage.js");
        $footerLinks->output();
    ?>

</body>

</html>