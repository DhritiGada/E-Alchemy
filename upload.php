<?php
    require "includes/classes/Header.php";

    $header = new Header("upload-course.php","upload.css");
    $header->output();
?>

<body>
    <?php
        // Navbar
        require "includes/config.php";
        include("includes/components/navbar.php");

        // Redirect to homepage if admin not logged in
        if(!isset($_SESSION["adminLoggedInName"])){
            header("Location: homepage.php");
        }
    ?>

    <div class="ui container">
        <form class="ui form" id="upload-form">
            <div class="ui four fields">

                <!-- Course title -->
                <!-- First name -->
                <div class="field">
                    <label>Title</label>
                    <div class="ui icon input">
                        <input type="text" name="title" placeholder="Title" id="title-input" required>
                        <i class="notched circle loading icon"></i>
                        <i class="check icon"></i>
                    </div>
                </div>

                <!-- Course category -->
                <div class="field">
                    <label>Category</label>
                    <div class="ui selection dropdown">
                        <input type="hidden" name="category">
                        <i class="dropdown icon"></i>
                        <div class="default text">Category</div>
                        <div class="menu">
                            <div class="item" data-value="computer-science">Computer Science</div>
                            <div class="item" data-value="information technology">Information Technology</div>
                            <div class="item" data-value="electronics & telecommunications">Electronics & Telecommunications</div>
                            <div class="item" data-value="bio-medical">Bio-Medical</div>
                        </div>
                    </div>
                </div>

                <!-- Course teaser -->
                <div class="field">
                    <label>Uploaded On</label>
                    <input type="date" name="date" placeholder="Date" required>
                </div>

                <!-- Course benefit -->
                <div class="field">
                    <label>Instructions</label>
                    <input type="text" name="instructions" placeholder="Instructions" required>
                </div>

                <!-- Course duration -->
                <div class="field">
                    <label>Duration in Hours</label>
                    <input type="number" name="duration" placeholder="Duration" required>
                </div>

            </div>

            <div class="ui three fields">

                <!-- Course requirements -->
                <div class="field">
                    <label>Requirements</label>
                    <textarea name="requirements" cols="30" rows="12" placeholder="Use dot(.) to separate requirements"
                        required></textarea>
                </div>

                <!-- Course description -->
                <div class="field">
                    <label>Description</label>
                    <textarea name="description" cols="30" rows="12" placeholder="Use dot(.) to separate description"
                        required></textarea>
                </div>

              

            </div>

            <div class="fields">
                <!-- Course image -->
                <div class="field">
                    <label>Image (only .jpg)</label>
                    <input type="file" name="image" required>
                </div>
                <!-- course videos -->
                <div class="field">
                    <label>Videos (only .mp4)</label>
                    <input id="videos-upload" type="file" name="videos[]" multiple required>
                </div>

            </div>

            <!-- Progress -->
            <div class="ui small progress" id="progressBar">
                <div class="bar">
                    <div class="progress"></div>
                </div>
                <div class="label">Uploading Files</div>
            </div>

            <!-- Messages -->
            <div id="messages"></div><br>

            <button class="ui primary button fluid" type="submit" name="submit">Save</button>
        </form>
    </div>

    <!-- Footer -->
    <?php include("includes/components/footer.php"); ?>

    <?php 
        require("includes/classes/FooterLinks.php");

        $footerLinks = new FooterLinks("upload.js");
        $footerLinks->output();
    ?>

</body>

</html>