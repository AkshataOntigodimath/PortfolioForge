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
   GET PROJECT ID
   ========================= */

$project_id = intval($_GET["id"] ?? 0);


if ($project_id <= 0) {

    header("Location: manage_projects.php");
    exit;

}


/* =========================
   UPDATE PROJECT
   ========================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $project_name =
        trim($_POST["project_name"] ?? "");

    $description =
        trim($_POST["description"] ?? "");

    $technologies =
        trim($_POST["technologies"] ?? "");

    $github_link =
        trim($_POST["github_link"] ?? "");


    if ($project_name == "") {

        $message =
            "Project name is required.";

    } else {

        $sql = "UPDATE projects SET
                project_name = ?,
                description = ?,
                technologies = ?,
                github_link = ?
                WHERE id = ? AND user_id = ?";


        $stmt =
            $conn->prepare($sql);


        $stmt->bind_param(
            "ssssii",
            $project_name,
            $description,
            $technologies,
            $github_link,
            $project_id,
            $user_id
        );


        if ($stmt->execute()) {

            $message =
                "Project updated successfully! 🎉";

        } else {

            $message =
                "Failed to update project.";

        }


        $stmt->close();

    }

}


/* =========================
   GET PROJECT
   ========================= */

$sql = "SELECT *
        FROM projects
        WHERE id = ? AND user_id = ?";


$stmt =
    $conn->prepare($sql);


$stmt->bind_param(
    "ii",
    $project_id,
    $user_id
);


$stmt->execute();


$result =
    $stmt->get_result();


$project =
    $result->fetch_assoc();


$stmt->close();


/* Project not found */

if (!$project) {

    echo "
        <h2 style='
            font-family: Arial;
            text-align:center;
            margin-top:50px;
        '>
            Project not found.
        </h2>
    ";

    exit;

}


/* Project values */

$project_name =
    $project["project_name"] ?? "";

$description =
    $project["description"] ?? "";

$technologies =
    $project["technologies"] ?? "";

$github_link =
    $project["github_link"] ?? "";

?><!DOCTYPE html><html lang="en"><head><meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>
    Edit Project | Portfolia
</title>


<style>

    body {
        margin: 0;

        font-family: Arial, sans-serif;

        background: #fff8dc;
    }


    .navbar {
        background: #6b4f2a;

        color: white;

        padding: 18px 40px;

        font-size: 24px;

        font-weight: bold;
    }


    .container {
        max-width: 750px;

        margin: 50px auto;

        padding: 35px;

        background: white;

        border-radius: 15px;

        box-shadow:
            0 8px 25px rgba(0,0,0,0.1);
    }


    h1 {
        color: #6b4f2a;

        margin-top: 0;
    }


    .subtitle {
        color: #777;

        margin-bottom: 30px;
    }


    .message {
        background: #e8f5e9;

        color: #2e7d32;

        padding: 12px;

        border-radius: 8px;

        margin-bottom: 20px;

        font-weight: bold;
    }


    label {
        display: block;

        margin-top: 18px;

        margin-bottom: 7px;

        font-weight: bold;

        color: #4d3925;
    }


    input,
    textarea {
        width: 100%;

        padding: 12px;

        box-sizing: border-box;

        border: 1px solid #ddd;

        border-radius: 8px;

        font-size: 15px;

        font-family: Arial, sans-serif;
    }


    textarea {
        min-height: 120px;

        resize: vertical;
    }


    input:focus,
    textarea:focus {
        outline: none;

        border-color: #c9a227;
    }


    button {
        width: 100%;

        margin-top: 30px;

        padding: 14px;

        border: none;

        border-radius: 8px;

        background: #c9a227;

        color: white;

        font-size: 16px;

        font-weight: bold;

        cursor: pointer;
    }


    button:hover {
        background: #a88316;
    }


    .back-link {
        display: block;

        text-align: center;

        margin-top: 20px;

        color: #6b4f2a;

        text-decoration: none;

        font-weight: bold;
    }


    .back-link:hover {
        text-decoration: underline;
    }

</style>

</head><body><div class="navbar">
    Portfolia
</div><div class="container"><h1>
    Edit Project ✏️
</h1>


<p class="subtitle">
    Update the details of your project.
</p>


<?php if ($message != "") { ?>

    <div class="message">

        <?php
        echo htmlspecialchars($message);
        ?>

    </div>

<?php } ?>


<form method="POST">


    <label for="project_name">
        Project Name
    </label>


    <input
        type="text"
        id="project_name"
        name="project_name"
        value="<?php
            echo htmlspecialchars(
                $project_name
            );
        ?>"
        required
    >


    <label for="description">
        Project Description
    </label>


    <textarea
        id="description"
        name="description"
    ><?php
        echo htmlspecialchars(
            $description
        );
    ?></textarea>


    <label for="technologies">
        Technologies Used
    </label>


    <input
        type="text"
        id="technologies"
        name="technologies"
        value="<?php
            echo htmlspecialchars(
                $technologies
            );
        ?>"
    >


    <label for="github_link">
        GitHub Link
    </label>


    <input
        type="url"
        id="github_link"
        name="github_link"
        value="<?php
            echo htmlspecialchars(
                $github_link
            );
        ?>"
    >


    <button type="submit">
        Update Project
    </button>


</form>


<a
    href="manage_projects.php"
    class="back-link"
>
    ← Back to My Projects
</a>

</div></body></html>