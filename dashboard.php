<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: /Portfolio-builder/auth/login.php");
    exit;
}

$user_name = $_SESSION["user_name"] ?? "";
$user_type = $_SESSION["user_type"] ?? "";

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard | Portfolia</title>

    <style>

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #fff8dc;
        }

        /* =========================
           NAVBAR
        ========================== */

        .navbar {
            background: #6b4f2a;
            color: white;
            padding: 18px 40px;

            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
        }

        .logout {
            background: #c9a227;
            color: white;

            padding: 9px 18px;

            border-radius: 7px;

            text-decoration: none;
        }

        .logout:hover {
            background: #b58f20;
        }

        /* =========================
           CONTAINER
        ========================== */

        .container {
            max-width: 1000px;

            margin: 50px auto;

            padding: 20px;
        }

        /* =========================
           WELCOME
        ========================== */

        .welcome {
            background: white;

            padding: 30px;

            border-radius: 15px;

            box-shadow:
                0 8px 20px
                rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #6b4f2a;
        }

        .type {
            color: #8a6a18;

            font-weight: bold;
        }

        /* =========================
           CARDS
        ========================== */

        .cards {
            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 20px;

            margin-top: 30px;
        }

        .card {
            background: white;

            padding: 25px;

            border-radius: 12px;

            text-align: center;

            box-shadow:
                0 5px 15px
                rgba(0, 0, 0, 0.08);
        }

        .card h3 {
            color: #6b4f2a;
        }

        .card p {
            color: #777;

            min-height: 40px;

            line-height: 1.5;
        }

        /* =========================
           BUTTON
        ========================== */

        .btn {
            display: inline-block;

            margin-top: 10px;

            padding: 10px 18px;

            background: #c9a227;

            color: white;

            text-decoration: none;

            border-radius: 7px;
        }

        .btn:hover {
            background: #b58f20;
        }

        /* =========================
           MOBILE
        ========================== */

        @media (max-width: 700px) {

            .cards {
                grid-template-columns: 1fr;
            }

            .navbar {
                padding: 15px 20px;
            }

            .container {
                margin: 25px auto;
                padding: 15px;
            }

        }

    </style>

</head>


<body>


<!-- =========================
     NAVBAR
========================== -->

<div class="navbar">

    <div class="logo">
        Portfolia
    </div>

    <a
        href="/Portfolio-builder/auth/logout.php"
        class="logout"
    >
        Logout
    </a>

</div>


<!-- =========================
     MAIN CONTAINER
========================== -->

<div class="container">


    <!-- =========================
         WELCOME
    ========================== -->

    <div class="welcome">

        <h1>
            Welcome,
            <?php echo htmlspecialchars($user_name); ?>! 👋
        </h1>

        <p>
            You are logged in as

            <span class="type">
                <?php echo htmlspecialchars($user_type); ?>
            </span>.
        </p>

        <p>
            Your portfolio journey starts here. 🚀
        </p>

    </div>


    <!-- =========================
         DASHBOARD CARDS
    ========================== -->

    <div class="cards">


        <!-- PROFILE -->

        <div class="card">

            <h3>
                👤 My Profile
            </h3>

            <p>
                Build your professional profile.
            </p>

            <a
                href="/Portfolio-builder/profile/profile.php"
                class="btn"
            >
                My Profile
            </a>

        </div>


        <!-- CUSTOMIZE -->

        <div class="card">

            <h3>
                🎨 Customize
            </h3>

            <p>
                Choose colors, layouts and templates.
            </p>

            <a
                href="/Portfolio-builder/customize/customize.php"
                class="btn"
            >
                Customize
            </a>

        </div>


        <!-- PROJECTS -->

        <div class="card">

            <h3>
                🚀 My Projects
            </h3>

            <p>
                Add and manage the projects displayed
                on your portfolio.
            </p>

            <a
                href="/Portfolio-builder/projects/manage_projects.php"
                class="btn"
            >
                Manage Projects
            </a>

        </div>
        <!-- EXTRA CURRICULAR ACTIVITIES -->

<div class="card">

    <h3>🎯 Activities</h3>

    <p>
        Add your extra-curricular activities,
        interests and achievements.
    </p>

    <a
        href="profile/activities.php"
        class="btn"
    >
        Manage Activities
    </a>

</div>


<!-- CERTIFICATIONS -->

<div class="card">

    <h3>🏆 Certifications</h3>

    <p>
        Add your certifications,
        courses and credentials.
    </p>

    <a
        href="profile/certifications.php"
        class="btn"
    >
        Manage Certifications
    </a>

</div>


        <!-- PREVIEW -->

        <div class="card">

            <h3>
                👀 Preview
            </h3>

            <p>
                Preview your portfolio before publishing.
            </p>

            <a
                href="/Portfolio-builder/preview/portfolio.php"
                class="btn"
            >
                View Portfolio
            </a>

        </div>


        <!-- =========================
             DOWNLOAD PDF
        ========================== -->

        <div class="card">

            <h3>
                📄 Download PDF
            </h3>

            <p>
                Download your portfolio as an A4 PDF.
            </p>

            <a
                href="/Portfolio-builder/pdf/generate_pdf.php"
                class="btn"
            >
                Download PDF
            </a>

        </div>


    </div>

</div>


</body>

</html>