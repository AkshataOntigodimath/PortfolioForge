<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/db.php";

$user_id = $_SESSION["user_id"];


/* =========================
   GET LOGGED-IN USER PROFILE
   ========================= */

$sql = "SELECT * FROM profiles WHERE user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

$profile = $result->fetch_assoc();

$stmt->close();


/* If profile doesn't exist */

if (!$profile) {

    echo "
        <h2 style='
            font-family: Arial;
            text-align:center;
            margin-top:50px;
        '>
            Please complete your profile first.
        </h2>
    ";

    exit;
}


/* =========================
   PROFILE DATA
   ========================= */

$full_name = $profile["full_name"] ?? "";
$title = $profile["title"] ?? "";
$bio = $profile["bio"] ?? "";
$education = $profile["education"] ?? "";
$skills = $profile["skills"] ?? "";
$experience = $profile["experience"] ?? "";
$phone = $profile["phone"] ?? "";
$location = $profile["location"] ?? "";
$linkedin = $profile["linkedin"] ?? "";
$github = $profile["github"] ?? "";

$theme_color =
    $profile["theme_color"] ?? "#6b4f2a";

$template =
    $profile["template"] ?? "classic";

$profile_photo =
    $profile["profile_photo"] ?? "";


/* =========================
   LOAD USER PROJECTS
   ========================= */

$projects_sql = "
    SELECT *
    FROM projects
    WHERE user_id = ?
    ORDER BY created_at DESC
";

$projects_stmt =
    $conn->prepare($projects_sql);

$projects_stmt->bind_param(
    "i",
    $user_id
);

$projects_stmt->execute();

$projects_result =
    $projects_stmt->get_result();

?><!DOCTYPE html><html lang="en"><head><meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>
    <?php echo htmlspecialchars($full_name); ?> | Portfolio
</title>


<style>

    * {
        box-sizing: border-box;
    }


    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background: #fff8dc;
        color: #333;
    }


    /* =========================
       HERO
       ========================= */

    .hero {
        background:
            <?php echo htmlspecialchars($theme_color); ?>;

        color: white;

        padding: 70px 20px;

        text-align: center;
    }


    .profile-photo {
        width: 150px;
        height: 150px;

        object-fit: cover;

        border-radius: 50%;

        border: 5px solid white;

        margin-bottom: 20px;

        box-shadow:
            0 5px 15px rgba(0,0,0,0.2);
    }


    .hero h1 {
        font-size: 45px;
        margin: 0 0 10px;
    }


    .hero h2 {
        font-size: 22px;
        font-weight: normal;
        margin: 0;

        color: #f7df8a;
    }


    /* =========================
       MAIN CONTAINER
       ========================= */

    .container {
        max-width: 1000px;

        margin: 40px auto;

        padding: 0 20px;
    }


    /* =========================
       SECTIONS
       ========================= */

    .section {
        background: white;

        margin-bottom: 25px;

        padding: 30px;

        border-radius: 15px;

        box-shadow:
            0 6px 18px rgba(0,0,0,0.08);
    }


    .section h2 {
        color: #6b4f2a;

        margin-top: 0;

        border-bottom:
            2px solid #c9a227;

        padding-bottom: 10px;
    }


    .section p {
        line-height: 1.7;
    }


    /* =========================
       SKILLS
       ========================= */

    .skills {
        display: flex;

        flex-wrap: wrap;

        gap: 10px;
    }


    .skill {
        background: #f4df91;

        color: #5c431f;

        padding: 9px 15px;

        border-radius: 20px;

        font-weight: bold;
    }


    /* =========================
       PROJECTS
       ========================= */

    .project-grid {
        display: grid;

        grid-template-columns:
            repeat(
                auto-fit,
                minmax(270px, 1fr)
            );

        gap: 20px;

        margin-top: 20px;
    }


    .project-card {
        background: #fff;

        padding: 25px;

        border-radius: 12px;

        border-top:
            4px solid
            <?php echo htmlspecialchars($theme_color); ?>;

        box-shadow:
            0 5px 15px rgba(0,0,0,0.08);

        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }


    .project-card:hover {
        transform: translateY(-5px);

        box-shadow:
            0 10px 25px rgba(0,0,0,0.12);
    }


    .project-card h3 {
        color:
            <?php echo htmlspecialchars($theme_color); ?>;

        margin-top: 0;

        margin-bottom: 12px;

        font-size: 22px;
    }


    .project-card p {
        color: #555;

        line-height: 1.6;
    }


    .project-tech {
        margin-top: 15px;

        padding: 10px;

        background: #fff8dc;

        border-radius: 8px;

        font-size: 14px;

        color: #5c431f;
    }


    .project-link {
        display: inline-block;

        margin-top: 15px;

        color:
            <?php echo htmlspecialchars($theme_color); ?>;

        font-weight: bold;

        text-decoration: none;
    }


    .project-link:hover {
        text-decoration: underline;
    }


    .no-projects {
        color: #777;

        text-align: center;

        padding: 20px;
    }


    /* =========================
       CONTACT
       ========================= */

    .contact {
        display: flex;

        flex-wrap: wrap;

        gap: 15px;
    }


    .contact a {
        text-decoration: none;

        background: #c9a227;

        color: white;

        padding: 11px 18px;

        border-radius: 8px;
    }


    .contact a:hover {
        background: #a88316;
    }


    /* =========================
       FOOTER
       ========================= */

    .footer {
        text-align: center;

        padding: 30px;

        color: #777;
    }


    /* =========================
       CLASSIC TEMPLATE
       ========================= */

    body.classic .hero {
        padding: 70px 20px;

        text-align: center;
    }


    body.classic .section {
        border-radius: 15px;
    }


    /* =========================
       MODERN TEMPLATE
       ========================= */

    body.modern {
        background: #f4f4f8;
    }


    body.modern .hero {
        padding: 90px 20px;

        text-align: left;
    }


    body.modern .hero h1 {
        max-width: 1000px;

        margin: auto;

        font-size: 55px;
    }


    body.modern .hero h2 {
        max-width: 1000px;

        margin: 10px auto 0;
    }


    body.modern .section {
        border-radius: 5px;

        border-left:
            5px solid
            <?php echo htmlspecialchars($theme_color); ?>;
    }


    body.modern .section h2 {
        border-bottom: none;
    }


    /* =========================
       MINIMAL TEMPLATE
       ========================= */

    body.minimal {
        background: #ffffff;
    }


    body.minimal .hero {
        background: #ffffff;

        color: #333;

        padding: 60px 20px;

        text-align: center;

        border-bottom:
            1px solid #ddd;
    }


    body.minimal .hero h1 {
        color: #333;

        font-weight: 500;
    }


    body.minimal .hero h2 {
        color: #777;
    }


    body.minimal .section {
        box-shadow: none;

        border-radius: 0;

        border-bottom:
            1px solid #ddd;
    }


    body.minimal .section h2 {
        border-bottom: none;
    }


    /* =========================
       MOBILE
       ========================= */

    @media (max-width: 600px) {

        .hero h1 {
            font-size: 32px;
        }


        .hero h2 {
            font-size: 18px;
        }


        .section {
            padding: 22px;
        }


        .profile-photo {
            width: 120px;
            height: 120px;
        }


        body.modern .hero {
            text-align: center;
        }


        body.modern .hero h1 {
            font-size: 38px;
        }

    }

</style>

</head><body class="<?php echo htmlspecialchars($template); ?>"><!-- =========================
     HERO SECTION
     ========================= --><div class="hero"><?php if (!empty($profile_photo)) { ?>

    <img
        src="../<?php echo htmlspecialchars($profile_photo); ?>"
        alt="Profile Photo"
        class="profile-photo"
    >

<?php } ?>


<h1>
    <?php
    echo htmlspecialchars($full_name);
    ?>
</h1>


<h2>
    <?php
    echo htmlspecialchars($title);
    ?>
</h2>

</div><div class="container"><!-- =========================
     ABOUT
     ========================= -->

<?php if (!empty($bio)) { ?>

    <div class="section">

        <h2>
            About Me
        </h2>

        <p>
            <?php
            echo nl2br(
                htmlspecialchars($bio)
            );
            ?>
        </p>

    </div>

<?php } ?>


<!-- =========================
     EDUCATION
     ========================= -->

<?php if (!empty($education)) { ?>

    <div class="section">

        <h2>
            Education
        </h2>

        <p>
            <?php
            echo nl2br(
                htmlspecialchars($education)
            );
            ?>
        </p>

    </div>

<?php } ?>


<!-- =========================
     SKILLS
     ========================= -->

<?php if (!empty($skills)) { ?>

    <div class="section">

        <h2>
            Skills
        </h2>


        <div class="skills">

            <?php

            $skill_list =
                explode(",", $skills);


            foreach ($skill_list as $skill) {

                $skill = trim($skill);


                if ($skill != "") {

                    echo
                        "<span class='skill'>" .
                        htmlspecialchars($skill) .
                        "</span>";

                }

            }

            ?>

        </div>

    </div>

<?php } ?>


<!-- =========================
     PROJECTS
     ========================= -->

<div class="section">

    <h2>
        Projects 🚀
    </h2>


    <?php if ($projects_result->num_rows > 0) { ?>


        <div class="project-grid">


            <?php while (
                $project =
                $projects_result->fetch_assoc()
            ) { ?>


                <div class="project-card">


                    <h3>

                        <?php
                        echo htmlspecialchars(
                            $project["project_name"]
                        );
                        ?>

                    </h3>


                    <?php
                    if (
                        !empty(
                            $project["description"]
                        )
                    ) {
                    ?>

                        <p>

                            <?php
                            echo nl2br(
                                htmlspecialchars(
                                    $project["description"]
                                )
                            );
                            ?>

                        </p>

                    <?php } ?>


                    <?php
                    if (
                        !empty(
                            $project["technologies"]
                        )
                    ) {
                    ?>

                        <div class="project-tech">

                            <strong>
                                Technologies:
                            </strong>

                            <?php
                            echo htmlspecialchars(
                                $project["technologies"]
                            );
                            ?>

                        </div>

                    <?php } ?>


                    <?php
                    if (
                        !empty(
                            $project["github_link"]
                        )
                    ) {
                    ?>

                        <a
                            href="<?php
                                echo htmlspecialchars(
                                    $project["github_link"]
                                );
                            ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="project-link"
                        >
                            🔗 View Project
                        </a>

                    <?php } ?>


                </div>


            <?php } ?>


        </div>


    <?php } else { ?>


        <div class="no-projects">

            <p>
                No projects added yet.
            </p>

        </div>


    <?php } ?>


</div>


<!-- =========================
     EXPERIENCE
     ========================= -->

<?php if (!empty($experience)) { ?>

    <div class="section">

        <h2>
            Experience
        </h2>

        <p>
            <?php
            echo nl2br(
                htmlspecialchars($experience)
            );
            ?>
        </p>

    </div>

<?php } ?>


<!-- =========================
     CONTACT
     ========================= -->

<div class="section">

    <h2>
        Contact
    </h2>


    <?php if (!empty($location)) { ?>

        <p>
            📍
            <?php
            echo htmlspecialchars($location);
            ?>
        </p>

    <?php } ?>


    <?php if (!empty($phone)) { ?>

        <p>
            📞
            <?php
            echo htmlspecialchars($phone);
            ?>
        </p>

    <?php } ?>


    <div class="contact">


        <?php if (!empty($linkedin)) { ?>

            <a
                href="<?php
                    echo htmlspecialchars($linkedin);
                ?>"
                target="_blank"
                rel="noopener noreferrer"
            >
                LinkedIn
            </a>

        <?php } ?>


        <?php if (!empty($github)) { ?>

            <a
                href="<?php
                    echo htmlspecialchars($github);
                ?>"
                target="_blank"
                rel="noopener noreferrer"
            >
                GitHub
            </a>

        <?php } ?>


    </div>

</div>

</div><!-- =========================
     FOOTER
     ========================= --><div class="footer">© 2026 Portfolia | Built with PHP & MySQL

</div></body></html><?php

$projects_stmt->close();

?>