<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/db.php";

/* Dompdf */

require_once "../vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;


$user_id = $_SESSION["user_id"];


/* =========================
   GET PROFILE
   ========================= */

$sql = "SELECT *
        FROM profiles
        WHERE user_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $user_id
);

$stmt->execute();

$result = $stmt->get_result();

$profile = $result->fetch_assoc();

$stmt->close();


/* Profile not found */

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

$full_name =
    $profile["full_name"] ?? "";

$title =
    $profile["title"] ?? "";

$bio =
    $profile["bio"] ?? "";

$education =
    $profile["education"] ?? "";

$skills =
    $profile["skills"] ?? "";

$experience =
    $profile["experience"] ?? "";

$phone =
    $profile["phone"] ?? "";

$location =
    $profile["location"] ?? "";

$linkedin =
    $profile["linkedin"] ?? "";

$github =
    $profile["github"] ?? "";

$profile_photo =
    $profile["profile_photo"] ?? "";


/* =========================
   GET PROJECTS
   ========================= */

$projects_sql = "SELECT *
                 FROM projects
                 WHERE user_id = ?
                 ORDER BY created_at DESC";

$projects_stmt =
    $conn->prepare($projects_sql);

$projects_stmt->bind_param(
    "i",
    $user_id
);

$projects_stmt->execute();

$projects_result =
    $projects_stmt->get_result();


/* =========================
   ESCAPE HTML
   ========================= */

function e($value)
{
    return htmlspecialchars(
        $value ?? "",
        ENT_QUOTES,
        "UTF-8"
    );
}


/* =========================
   CREATE PROJECT HTML
   ========================= */

$projects_html = "";


while (
    $project =
    $projects_result->fetch_assoc()
) {

    $project_name =
        $project["project_name"] ?? "";

    $description =
        $project["description"] ?? "";

    $technologies =
        $project["technologies"] ?? "";

    $github_link =
        $project["github_link"] ?? "";


    $projects_html .= "

        <div class='project'>

            <h3>" .
                e($project_name) .
            "</h3>

            <p>" .
                nl2br(
                    e($description)
                ) .
            "</p>

            <p>
                <strong>
                    Technologies:
                </strong>
                " .
                e($technologies) .
            "</p>
    ";


    if (!empty($github_link)) {

        $projects_html .= "

            <p>
                <strong>
                    GitHub:
                </strong>
                " .
                e($github_link) .
            "</p>

        ";

    }


    $projects_html .= "

        </div>

    ";

}


$projects_stmt->close();


/* =========================
   PROFILE PHOTO
   ========================= */

$photo_html = "";


if (!empty($profile_photo)) {

    $photo_path =
        __DIR__ . "/../" . $profile_photo;


    if (file_exists($photo_path)) {

        $photo_type =
            mime_content_type($photo_path);

        $photo_data =
            base64_encode(
                file_get_contents($photo_path)
            );

        $photo_html = "

            <img
                src='data:" .
                $photo_type .
                ";base64," .
                $photo_data .
                "'
                class='profile-photo'
            >

        ";

    }

}


/* =========================
   CREATE PDF HTML
   ========================= */

$html = "

<!DOCTYPE html>

<html>

<head>

<meta charset='UTF-8'>

<style>

body {

    font-family:
        DejaVu Sans,
        sans-serif;

    margin: 35px;

    color: #333;

}

.header {

    text-align: center;

    background: #6b4f2a;

    color: white;

    padding: 25px;

    border-radius: 10px;

}

.profile-photo {

    width: 100px;

    height: 100px;

    object-fit: cover;

    border-radius: 50%;

    border: 3px solid white;

    margin-bottom: 10px;

}

.header h1 {

    margin: 5px 0;

    font-size: 28px;

}

.header h2 {

    margin: 5px 0;

    font-size: 16px;

    font-weight: normal;

}

.section {

    margin-top: 25px;

}

.section-title {

    color: #6b4f2a;

    font-size: 18px;

    font-weight: bold;

    border-bottom: 2px solid #c9a227;

    padding-bottom: 5px;

    margin-bottom: 10px;

}

p {

    line-height: 1.6;

}

.skills {

    line-height: 1.8;

}

.skill {

    display: inline-block;

    background: #f4df91;

    padding: 5px 10px;

    margin: 3px;

    border-radius: 10px;

}

.project {

    border: 1px solid #ddd;

    padding: 12px;

    margin-bottom: 12px;

    border-radius: 8px;

}

.project h3 {

    color: #6b4f2a;

    margin-top: 0;

}

.contact {

    line-height: 1.8;

}

.footer {

    margin-top: 30px;

    text-align: center;

    color: #777;

    font-size: 11px;

}

</style>

</head>


<body>


<div class='header'>

    " .
    $photo_html .
    "

    <h1>
        " .
        e($full_name) .
        "
    </h1>

    <h2>
        " .
        e($title) .
        "
    </h2>

</div>


<div class='section'>

    <div class='section-title'>
        About Me
    </div>

    <p>
        " .
        nl2br(e($bio)) .
        "
    </p>

</div>


<div class='section'>

    <div class='section-title'>
        Education
    </div>

    <p>
        " .
        nl2br(e($education)) .
        "
    </p>

</div>


<div class='section'>

    <div class='section-title'>
        Skills
    </div>

    <div class='skills'>
";


$skill_list =
    explode(",", $skills);


foreach ($skill_list as $skill) {

    $skill =
        trim($skill);


    if ($skill != "") {

        $html .= "

            <span class='skill'>
                " .
                e($skill) .
                "
            </span>

        ";

    }

}


$html .= "

    </div>

</div>


<div class='section'>

    <div class='section-title'>
        Projects
    </div>

    " .
    $projects_html .
    "

</div>


<div class='section'>

    <div class='section-title'>
        Experience
    </div>

    <p>
        " .
        nl2br(e($experience)) .
        "
    </p>

</div>


<div class='section'>

    <div class='section-title'>
        Contact
    </div>

    <div class='contact'>

        <strong>
            Location:
        </strong>
        " .
        e($location) .
        "

        <br>

        <strong>
            Phone:
        </strong>
        " .
        e($phone) .
        "

        <br>

        <strong>
            LinkedIn:
        </strong>
        " .
        e($linkedin) .
        "

        <br>

        <strong>
            GitHub:
        </strong>
        " .
        e($github) .
        "

    </div>

</div>


<div class='footer'>

    Portfolia
    |
    Generated Portfolio

</div>


</body>

</html>

";


/* =========================
   DOMPDF SETTINGS
   ========================= */

$options =
    new Options();

$options->set(
    "isRemoteEnabled",
    true
);

$options->set(
    "defaultFont",
    "DejaVu Sans"
);


$dompdf =
    new Dompdf($options);


/* Load HTML */

$dompdf->loadHtml($html);


/* Paper */

$dompdf->setPaper(
    "A4",
    "portrait"
);


/* Render */

$dompdf->render();


/* Download PDF */

$filename =
    preg_replace(
        "/[^a-zA-Z0-9_-]/",
        "_",
        $full_name
    );


$dompdf->stream(
    $filename . "_Portfolio.pdf",
    [
        "Attachment" => true
    ]
);

?>