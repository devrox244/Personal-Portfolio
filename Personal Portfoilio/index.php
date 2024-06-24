<?php require_once("Back\Db.php"); ?>
<?php require_once("Back\Functions.php"); ?>
<?php require_once("Back\Sessions.php"); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devadyumna's Portfolio</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <!-- nav-bar -->

    <header>
        <nav class="navi-bar">
            <div class="name">Devadyumna Vijay Kumar</div>
            <div class="navigations">
                <ul>
                    <a href="#home">
                        <li>Home</li>
                    </a>
                    <a href="#about">
                        <li>About</li>
                    </a>
                    <a href="#posts">
                        <li>Achievements</li>
                    </a>
                    <a href="#contact">
                        <li>Contact Me</li>
                    </a>
                </ul>
            </div>
        </nav>
    </header>

    <div style="width: 100%; border: 1px solid red;"></div>

    <!-- home section -->

    <div class="home" id="home">
        <div class="intro">
            <p>Hello, I am <span>Devadyumna Vijay Kumar</span></p><br>
            <span id="element"></span>
        </div>
        <div class="picture">
            <img src="pic2.png">
        </div>
    </div>

    <!-- about section -->

    <div class="about" id="about">
        <div class="container">
            <div class="row">
                <div class="col-1">
                    <img src="pic3.png" alt="">
                </div>
                <div class="col-2">
                    <h1>About Me</h1>
                    <p>Aspiring intern with a strong foundation in DSA, fueled by a passion for continuous learning.
                        Eager to explore uncharted realms of technology, adept at adapting. Committed to contributing
                        innovative solutions, fostering growth, and thriving in dynamic environments.
                    </p>
                    <div class="tab-titles">
                        <h5 class="tab-links active-title" onclick="opencon('skill')">Skill</h5>
                        <h5 class="tab-links" onclick="opencon('education')">Education</h5>
                        <h5 class="tab-links" onclick="opencon('experience')">Experience</h5>
                    </div>
                    <div class="tab-content active-tab" id="skill">
                        <ul>
                            <li>Full Stack Development</li>
                            <li>C</li>
                            <li>Python</li>
                            <li>Java</li>
                            <li>DSA</li>
                            <li>OOPS</li>
                        </ul>
                    </div>
                    <div class="tab-content" id="experience">
                        <ul>
                            <li>Wizgrad Internship<br>Full Stack Development<br>Feb - March, 2024</li>
                            <li>Softapper<br>Full Stack Development<br>May 2024 - present</li>
                        </ul>
                    </div>
                    <div class="tab-content" id="education">
                        <ul>
                            <li>Fiitjee World School<br> 11th - 12th <br>2020 - 2022</li>
                            <li>Manipal University Jaipur <br> B.Tech CSE with IOT (hons) <br> 2022 - present</li>
                        </ul>
                        p
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- achievements and posts section -->

    <div class="ach-pos" id="posts">
        <div class="add">
            <h1>Achievements and Posts</h1>
            <a href="post.php">
                <button class="add-btn" name="add">+ New</button>
            </a>
        </div>

        <div class="cards">
            <?php
            global $conn;
            $sql = "SELECT * FROM pp_post ORDER BY id desc";
            $stmt = $conn->query($sql);

            while ($DataRows = $stmt->fetch()) {
                $id = $DataRows["id"];
                $title = $DataRows["title"];
                $image = $DataRows["image"];
                $description = $DataRows["text"];
            ?>
                <a href="FullPost.php?id=<?php echo $id; ?>" class="card-link">
                    <div class="card">
                        <img src="Upload/<?php echo $image; ?>" alt="<?php echo $title; ?>">
                        <div class="card-body">
                            <h4><?php echo $title; ?></h4>
                            <hr>
                            <p><?php echo $description; ?></p>
                        </div>
                    </div>
                </a>
            <?php } ?>
        </div>
    </div>

    <div style="width: 100%; border: 1px solid red;"></div>
    <!-- Contact me -->

    <div class="Contact" id="contact">
        <div class="ContactInfo">
            <h1>Contact Me</h1>
            <p><i class="fa-solid fa-envelope"></i> devadyumavijay@outlook.com</p>
            <p><i class="fa-solid fa-phone"></i> +91 89059 59835</p>
            <div class="SocialIcons">
                <a href="https://www.instagram.com/devadyumnavijay_3750?igsh=MWJuM2E4bjgyYm51Zg=="><i class="fa-brands fa-instagram"></i></a>
                <a href="https://www.linkedin.com/in/devadyumna-vijay-kumar-667633250?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=android_app"><i class="fa-brands fa-linkedin"></i></a>
            </div>
            <a href="C:\Users\pc\Documents\MUJ\Resume.docx" download>
                <button class="resume-btn">Download Resume</button>
            </a>
        </div>

        <div class="ContactForm">
            <form name="submit-to-google-sheet">
                <input type="text" class="PostName" placeholder="Enter your Name" name="PostName" required><br>
                <input type="email" class="email" placeholder="Enter your Email" name="Email" required><br>
                <textarea name="Message" cols="30" placeholder="Enter your message (optional)"></textarea>
                <button type="submit" name="submit" class="Submit"><i class="fa-solid fa-paper-plane"></i>Submit</button>
            </form>
        </div>
    </div>

    <div class="copyright">
        <p>All rights reserved&copy. <br>Made by Devadyumna Vijay Kumar.</p>
    </div>

    <script src="https://kit.fontawesome.com/bd8c73bde2.js" crossorigin="anonymous"></script>

    <script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>

    <script src="script.js"></script>

    <!-- Script for contact form info to be sent to a google sheet -->
    <script>
        const scriptURL = 'https://script.google.com/macros/s/AKfycbzho5xT2r5bCBOTs6Ab0DqtE8_8ixOqifRs6CI3UAdHsgsyIkcBO4IdqQrWhRMbLtbXIQ/exec';
        const form = document.forms['submit-to-google-sheet'];

        form.addEventListener('submit', e => {
            e.preventDefault(),
                fetch(scriptURL, {
                    method: 'POST',
                    body: new FormData(form)
                })
                .then(response => console.log('Success!', response))
                .catch(error => console.error('Error!', error.message))
        })
    </script>
</body>

</html>