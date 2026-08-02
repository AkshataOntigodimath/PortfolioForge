<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/db.php";
require_once "../vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;

$user_id = $_SESSION["user_id"];


/* =====================================================
   HELPER
===================================================== */

function e($value)
{
    return htmlspecialchars(
        $value ?? "",
        ENT_QUOTES,
        "UTF-8"
    );
}


/* =====================================================
   GET PROFILE
===================================================== */

$sql = "SELECT *
        FROM profiles
        WHERE user_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();

$profile = $result->fetch_assoc();

$stmt->close();


if (!$profile) {

    echo "
    <h2 style='
        font-family: Arial, sans-serif;
        text-align: center;
        margin-top: 100px;
    '>
        Please complete your profile first.
    </h2>
    ";

    exit;
}


/* =====================================================
   PROFILE DATA
===================================================== */

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


/* =====================================================
   SKILLS
===================================================== */

$skills_html = "";

$skill_list = explode(",", $skills);

foreach ($skill_list as $skill) {

    $skill = trim($skill);

    if ($skill !== "") {

        $skills_html .= "
            <span class='skill'>
                " . e($skill) . "
            </span>
        ";
    }
}


/* =====================================================
   CONTACT
===================================================== */

$contact_items = [];

if (!empty($phone)) {
    $contact_items[] = e($phone);
}

if (!empty($location)) {
    $contact_items[] = e($location);
}

if (!empty($linkedin)) {
    $contact_items[] = e($linkedin);
}

if (!empty($github)) {
    $contact_items[] = e($github);
}

$contact_html = implode(
    " <span class='separator'>|</span> ",
    $contact_items
);


/* =====================================================
   PROJECTS
===================================================== */

$projects_html = "";

$projects_sql = "
    SELECT *
    FROM projects
    WHERE user_id = ?
    ORDER BY created_at DESC
";

$projects_stmt = $conn->prepare($projects_sql);

$projects_stmt->bind_param(
    "i",
    $user_id
);

$projects_stmt->execute();

$projects_result = $projects_stmt->get_result();


while ($project = $projects_result->fetch_assoc()) {

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

        <div class='project-name'>
            " . e($project_name) . "
        </div>

        <div class='project-description'>
            " . nl2br(e($description)) . "
        </div>
    ";


    if (!empty($technologies)) {

        $projects_html .= "

        <div class='project-details'>
            <strong>Technologies:</strong>
            " . e($technologies) . "
        </div>

        ";
    }


    if (!empty($github_link)) {

        $projects_html .= "

        <div class='project-details'>
            <strong>GitHub:</strong>
            " . e($github_link) . "
        </div>

        ";
    }


    $projects_html .= "

    </div>

    ";
}


$projects_stmt->close();


/* =====================================================
   PDF HTML
===================================================== */

$html = "

<!DOCTYPE html>

<html>

<head>

<meta charset='UTF-8'>

<style>

/* =========================
   A4 PAGE
========================= */

@page {

    size: A4 portrait;

    margin: 18mm;
}


/* =========================
   GENERAL
========================= */

body {

    margin: 0;

    padding: 0;

    font-family: Arial, Helvetica, sans-serif;

    font-size: 12px;

    line-height: 1.5;

    color: #222222;

    background: #ffffff;
}


/* =========================
   HEADER
========================= */

.header {

    text-align: center;

    padding-bottom: 12px;

    margin-bottom: 16px;

    border-bottom: 2px solid #6b4f2a;
}


.name {

    font-size: 25px;

    font-weight: bold;

    color: #4f3a20;

    margin-bottom: 4px;

    text-transform: uppercase;
}


.title {

    font-size: 14px;

    color: #555555;

    margin-bottom: 7px;
}


.contact {

    font-size: 10.5px;

    color: #444444;

    line-height: 1.5;
}


.separator {

    color: #c9a227;

    padding: 0 4px;
}


/* =========================
   SECTIONS
========================= */

.section {

    margin-bottom: 15px;

    page-break-inside: auto;
}


.section-title {

    font-size: 14px;

    font-weight: bold;

    color: #5b421f;

    text-transform: uppercase;

    border-bottom: 1px solid #c9a227;

    padding-bottom: 3px;

    margin-bottom: 7px;
}


/* =========================
   NORMAL TEXT
========================= */

.text {

    font-size: 12px;

    line-height: 1.5;

    text-align: justify;

    margin: 0;
}


/* =========================
   EDUCATION
========================= */

.education {

    font-size: 12px;

    line-height: 1.5;
}


/* =========================
   SKILLS
========================= */

.skills {

    line-height: 1.9;
}


.skill {

    display: inline-block;

    border: 1px solid #c9a227;

    color: #4f3a20;

    padding: 2px 7px;

    margin-right: 5px;

    margin-bottom: 3px;

    font-size: 11px;

    border-radius: 2px;
}


/* =========================
   PROJECTS
========================= */

.project {

    margin-bottom: 10px;

    page-break-inside: avoid;
}


.project-name {

    font-size: 13px;

    font-weight: bold;

    color: #4f3a20;

    margin-bottom: 2px;
}


.project-description {

    font-size: 12px;

    line-height: 1.45;

    margin-bottom: 3px;
}


.project-details {

    font-size: 10.5px;

    line-height: 1.4;

    color: #444444;
}


/* =========================
   EXPERIENCE
========================= */

.experience {

    font-size: 12px;

    line-height: 1.5;

    text-align: justify;
}


/* =========================
   FOOTER
========================= */

.footer {

    margin-top: 20px;

    padding-top: 6px;

    border-top: 1px solid #dddddd;

    text-align: center;

    font-size: 9px;

    color: #888888;
}

</style>

</head>


<body>


<!-- =========================
     HEADER
========================= -->

<div class='header'>

    <div class='name'>

        " . e($full_name) . "

    </div>


    <div class='title'>

        " . e($title) . "

    </div>


    <div class='contact'>

        " . $contact_html . "

    </div>

</div>


";


/* =====================================================
   ABOUT
===================================================== */

if (!empty(trim($bio))) {

    $html .= "

    <div class='section'>

        <div class='section-title'>
            About Me
        </div>

        <div class='text'>
            " . nl2br(e($bio)) . "
        </div>

    </div>

    ";
}


/* =====================================================
   EDUCATION
===================================================== */

if (!empty(trim($education))) {

    $html .= "

    <div class='section'>

        <div class='section-title'>
            Education
        </div>

        <div class='education'>
            " . nl2br(e($education)) . "
        </div>

    </div>

    ";
}


/* =====================================================
   SKILLS
===================================================== */

if (!empty(trim($skills))) {

    $html .= "

    <div class='section'>

        <div class='section-title'>
            Skills
        </div>

        <div class='skills'>
            " . $skills_html . "
        </div>

    </div>

    ";
}


/* =====================================================
   PROJECTS
===================================================== */

if (!empty($projects_html)) {

    $html .= "

    <div class='section'>

        <div class='section-title'>
            Projects
        </div>

        " . $projects_html . "

    </div>

    ";
}


/* =====================================================
   EXPERIENCE
===================================================== */

if (!empty(trim($experience))) {

    $html .= "

    <div class='section'>

        <div class='section-title'>
            Experience
        </div>

        <div class='experience'>
            " . nl2br(e($experience)) . "
        </div>

    </div>

    ";
}


/* =====================================================
   FOOTER
===================================================== */

$html .= "

<div class='footer'>

    PortfolioForge | Generated Portfolio

</div>


</body>

</html>

";


/* =====================================================
   DOMPDF
===================================================== */

$options = new Options();

$options->set(
    "isRemoteEnabled",
    true
);

$options->set(
    "defaultFont",
    "DejaVu Sans"
);


$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);

$dompdf->setPaper(
    "A4",
    "portrait"
);

$dompdf->render();


/* =====================================================
   DOWNLOAD
===================================================== */

$filename = preg_replace(
    "/[^a-zA-Z0-9_-]/",
    "_",
    $full_name
);


if (empty($filename)) {

    $filename = "Portfolio";

}


$dompdf->stream(

    $filename . "_Portfolio.pdf",

    [
        "Attachment" => true
    ]

);

?>