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
   ADD PROJECT
   ========================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $project_name = trim($_POST["project_name"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $technologies = trim($_POST["technologies"] ?? "");
    $github_link = trim($_POST["github_link"] ?? "");


    if ($project_name == "") {

        $message = "Project name is required.";

    } else {

        $sql = "INSERT INTO projects
                (
                    user_id,
                    project_name,
                    description,
                    technologies,
                    github_link
                )
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "issss",
            $user_id,
            $project_name,
            $description,
            $technologies,
            $github_link
        );


        if ($stmt->execute()) {

            $message = "Project added successfully! 🎉";

        } else {

            $message = "Failed to add project.";

        }

        $stmt->close();
    }
}

?><!DOCTYPE html><html lang="en"><head><meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Add Project | Portfolia</title>


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
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
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
    Add a Project 🚀
</h1>


<p class="subtitle">
    Add a project that you want to showcase on your portfolio.
</p>


<?php if ($message != "") { ?>

    <div class="message">

        <?php echo htmlspecialchars($message); ?>

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
        placeholder="Example: Smart Irrigation System"
        required
    >


    <label for="description">
        Project Description
    </label>


    <textarea
        id="description"
        name="description"
        placeholder="Describe what your project does..."
    ></textarea>


    <label for="technologies">
        Technologies Used
    </label>


    <input
        type="text"
        id="technologies"
        name="technologies"
        placeholder="Example: Arduino, IoT, C++, ThingSpeak"
    >


    <label for="github_link">
        GitHub Link
    </label>


    <input
        type="url"
        id="github_link"
        name="github_link"
        placeholder="https://github.com/yourname/project"
    >


    <button type="submit">
        Add Project
    </button>


</form>


<a
    href="../dashboard.php"
    class="back-link"
>
    ← Back to Dashboard
</a>

</div></body></html>