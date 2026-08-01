<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/db.php";

$user_id = $_SESSION["user_id"];

$message = "";


/* =========================
   DELETE PROJECT
   ========================= */

if (
    $_SERVER["REQUEST_METHOD"] == "POST" &&
    isset($_POST["delete_project"])
) {

    $project_id = intval($_POST["delete_project"]);

    $sql = "DELETE FROM projects
            WHERE id = ? AND user_id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ii",
        $project_id,
        $user_id
    );

    if ($stmt->execute()) {

        $message = "Project deleted successfully.";

    } else {

        $message = "Failed to delete project.";

    }

    $stmt->close();
}


/* =========================
   GET USER PROJECTS
   ========================= */

$sql = "SELECT *
        FROM projects
        WHERE user_id = ?
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $user_id
);

$stmt->execute();

$result = $stmt->get_result();

?><!DOCTYPE html><html lang="en"><head><meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>
    My Projects | Portfolia
</title>


<style>

    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background: #fff8dc;
    }


    /* =========================
       NAVBAR
       ========================= */

    .navbar {
        background: #6b4f2a;
        color: white;
        padding: 18px 40px;
        font-size: 24px;
        font-weight: bold;
    }


    /* =========================
       CONTAINER
       ========================= */

    .container {
        max-width: 1000px;
        margin: 40px auto;
        padding: 35px;
    }


    h1 {
        color: #6b4f2a;
        margin-bottom: 10px;
    }


    .subtitle {
        color: #777;
        margin-bottom: 25px;
    }


    /* =========================
       MESSAGE
       ========================= */

    .message {
        background: #e8f5e9;
        color: #2e7d32;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-weight: bold;
    }


    /* =========================
       TOP BAR
       ========================= */

    .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }


    .add-btn {
        background: #c9a227;
        color: white;
        padding: 12px 18px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
    }


    .add-btn:hover {
        background: #a88316;
    }


    /* =========================
       PROJECT GRID
       ========================= */

    .projects {
        display: grid;

        grid-template-columns:
            repeat(
                auto-fit,
                minmax(280px, 1fr)
            );

        gap: 20px;
    }


    /* =========================
       PROJECT CARD
       ========================= */

    .project-card {
        background: white;

        padding: 25px;

        border-radius: 15px;

        box-shadow:
            0 8px 20px rgba(0,0,0,0.08);
    }


    .project-card h2 {
        color: #6b4f2a;

        margin-top: 0;
    }


    .project-card p {
        color: #555;

        line-height: 1.6;
    }


    /* =========================
       TECHNOLOGIES
       ========================= */

    .tech {
        background: #fff8dc;

        padding: 10px;

        border-radius: 8px;

        margin-top: 15px;

        font-size: 14px;
    }


    /* =========================
       GITHUB
       ========================= */

    .github {
        display: inline-block;

        margin-top: 15px;

        color: #6b4f2a;

        font-weight: bold;

        text-decoration: none;
    }


    .github:hover {
        text-decoration: underline;
    }


    /* =========================
       EDIT BUTTON
       ========================= */

    .edit-btn {
        display: block;

        width: 100%;

        margin-top: 20px;

        padding: 10px;

        border-radius: 8px;

        background: #6b4f2a;

        color: white;

        text-align: center;

        text-decoration: none;

        font-weight: bold;

        box-sizing: border-box;
    }


    .edit-btn:hover {
        background: #4d3925;
    }


    /* =========================
       DELETE BUTTON
       ========================= */

    .delete-btn {
        margin-top: 10px;

        width: 100%;

        padding: 10px;

        border: none;

        border-radius: 8px;

        background: #d9534f;

        color: white;

        font-weight: bold;

        cursor: pointer;
    }


    .delete-btn:hover {
        background: #b52b27;
    }


    /* =========================
       EMPTY STATE
       ========================= */

    .empty {
        background: white;

        padding: 40px;

        border-radius: 15px;

        text-align: center;

        color: #777;
    }


    /* =========================
       BACK LINK
       ========================= */

    .back-link {
        display: block;

        margin-top: 30px;

        color: #6b4f2a;

        text-decoration: none;

        font-weight: bold;
    }


    .back-link:hover {
        text-decoration: underline;
    }


    /* =========================
       MOBILE
       ========================= */

    @media (max-width: 600px) {

        .top-bar {
            flex-direction: column;

            align-items: flex-start;

            gap: 15px;
        }

    }

</style>

</head><body><!-- =========================
     NAVBAR
     ========================= --><div class="navbar">Portfolia

</div><div class="container"><!-- =========================
     TOP BAR
     ========================= -->

<div class="top-bar">


    <div>

        <h1>
            My Projects 🚀
        </h1>


        <p class="subtitle">
            Manage the projects displayed on your portfolio.
        </p>

    </div>


    <a
        href="add_project.php"
        class="add-btn"
    >
        + Add Project
    </a>


</div>


<!-- =========================
     MESSAGE
     ========================= -->

<?php if ($message != "") { ?>

    <div class="message">

        <?php
        echo htmlspecialchars($message);
        ?>

    </div>

<?php } ?>


<!-- =========================
     PROJECTS
     ========================= -->

<?php if ($result->num_rows > 0) { ?>


    <div class="projects">


        <?php while (
            $project =
            $result->fetch_assoc()
        ) { ?>


            <div class="project-card">


                <!-- PROJECT NAME -->

                <h2>

                    <?php
                    echo htmlspecialchars(
                        $project["project_name"]
                    );
                    ?>

                </h2>


                <!-- DESCRIPTION -->

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


                <!-- TECHNOLOGIES -->

                <?php
                if (
                    !empty(
                        $project["technologies"]
                    )
                ) {
                ?>

                    <div class="tech">

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


                <!-- GITHUB -->

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
                        class="github"
                    >
                        🔗 View on GitHub
                    </a>

                <?php } ?>


                <!-- EDIT -->

                <a
                    href="edit_project.php?id=<?php
                        echo $project["id"];
                    ?>"
                    class="edit-btn"
                >
                    ✏️ Edit Project
                </a>


                <!-- DELETE -->

                <form
                    method="POST"

                    onsubmit="
                        return confirm(
                            'Are you sure you want to delete this project?'
                        );
                    "
                >

                    <input
                        type="hidden"
                        name="delete_project"
                        value="<?php
                            echo $project["id"];
                        ?>"
                    >


                    <button
                        type="submit"
                        class="delete-btn"
                    >
                        🗑️ Delete Project
                    </button>

                </form>


            </div>


        <?php } ?>


    </div>


<?php } else { ?>


    <!-- =========================
         NO PROJECTS
         ========================= -->

    <div class="empty">

        <h2>
            No Projects Yet 📂
        </h2>


        <p>
            Add your first project to showcase it on your portfolio.
        </p>


    </div>


<?php } ?>


<!-- =========================
     BACK TO DASHBOARD
     ========================= -->

<a
    href="../dashboard.php"
    class="back-link"
>
    ← Back to Dashboard
</a>

</div></body></html><?php

$stmt->close();

?>