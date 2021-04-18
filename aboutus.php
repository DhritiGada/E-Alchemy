<?php

require("includes/classes/Header.php");

$header = new Header("About E-Alchemy", "aboutus.css");
$header->output();

?>

<body>
<?php

require 'includes/config.php';
require 'includes/components/navbar.php';
?>

<div class="about-section">
    <h1>About E-Alchemy</h1>
    <p class="intro">E-Alchemy aims be a leading global technology university that provides a transformative education
        to create leaders and innovators, and generates new knowledge for society and industry.</p>

</div>

<h2 style="text-align:center">Our Faculty</h2>

<div class="row">
    <div class="column">
        <div class="card">
            <div class="container">
                <h3>Hal Abelson</h3>
                <ul>

                    <li>Class of 1922 Professor</li>
                    <li>[CS and AI&DS]</li>
                    <li>(617) 253-5856</li>
                    <li>32-386</li>
                    <li>CS AI , AI</li>
                    <li> Connections, Cybersecurity</li>
                    <li>hal@mit.edu</li>
                    <p>
                        <button class="button" onclick="location.href='mailto:support@e-alchemy.com';">Contact</button>
                    </p>
            </div>
        </div>
    </div>

    <div class="column">
        <div class="card">
            <div class="container">
                <h3>Anant Agarwal</h3>
                <ul>

                    <li>CEO, edX;</li>
                    <li>[CS and EE]</li>
                    <li>Professor of EECS;</li>
                    <li>(617) 253-1448</li>
                    <li>NE55-900</li>
                    <li>CSAIL</li>
                    <li>anantagarwal@mit.edu</li>

                    <p>
                        <button class="button" onclick="location.href='mailto:support@e-alchemy.com';">Contact</button>
                    </p>
            </div>
        </div>
    </div>

    <div class="column">
        <div class="card">
            <div class="container">
                <h3>Akintunde Akinwande</h3>
                <ul>

                    <li>Professor of EE;</li>
                    <li>Professor of EECS</li>
                    <li>[EE]</li>
                    <li>(617) 258-7974</li>
                    <li>NE55-900</li>
                    <li>MTL, ApplP</li>
                    <li>akinwand@mtl.mit.edu</li>

                    <p>
                        <button class="button" onclick="location.href='mailto:support@e-alchemy.com';">Contact</button>
                    </p>
            </div>
        </div>
    </div>
</div>
<!-- Footer -->
<?php include("includes/components/footer.php"); ?>

<?php
require("includes/classes/FooterLinks.php");

$footerLinks = new FooterLinks("");
$footerLinks->output();
?>

</body>
</html>
