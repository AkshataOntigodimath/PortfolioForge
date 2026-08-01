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
   SAVE CUSTOMIZATION
   ========================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $theme_color = $_POST["theme_color"] ?? "#6b4f2a";
    $template = $_POST["template"] ?? "classic";


    /* Validate theme color */

    if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $theme_color)) {
        $theme_color = "#6b4f2a";
    }


    /* Validate template */

    $allowed_templates = ["classic", "modern", "minimal"];

    if (!in_array($template, $allowed_templates)) {
        $template = "classic";
    }


    /* Update database */

    $sql = "UPDATE profiles
            SET theme_color = ?, template = ?
            WHERE user_id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssi",
        $theme_color,
        $template,
        $user_id
    );


    if ($stmt->execute()) {

        $message = "Customization saved successfully! 🎨";

    } else {

        $message = "Failed to save customization.";

    }

    $stmt->close();
}


/* =========================
   GET CURRENT SETTINGS
   ========================= */

$sql = "SELECT theme_color, template
        FROM profiles
        WHERE user_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();

$settings = $result->fetch_assoc();

$stmt->close();


$current_color = $settings["theme_color"] ?? "#6b4f2a";

$current_template = $settings["template"] ?? "classic";

?><!DOCTYPE html><html lang="en"><head><meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Customize Portfolio | Portfolia</title>


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


    .box {
        padding: 25px;
        border: 1px solid #ddd;
        border-radius: 12px;
        margin-bottom: 25px;
    }


    label {
        display: block;
        font-weight: bold;
        color: #4d3925;
        margin-bottom: 12px;
    }


    input[type="color"] {
        width: 100px;
        height: 55px;
        border: none;
        cursor: pointer;
    }


    .templates {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
    }


    .template-option {
        border: 2px solid #ddd;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
    }


    .template-option:hover {
        border-color: #c9a227;
    }


    .template-option input {
        margin-bottom: 10px;
    }


    .template-option strong {
        display: block;
        margin-bottom: 5px;
    }


    .template-option span {
        color: #777;
        font-size: 13px;
    }


    button {
        width: 100%;
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


    .message {
        background: #e8f5e9;
        color: #2e7d32;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
    }


    @media (max-width: 650px) {

        .templates {
            grid-template-columns: 1fr;
        }

    }

</style>

</head><body><div class="navbar">Portfolia

</div><div class="container"><h1>
    Customize Your Portfolio 🎨
</h1>


<p class="subtitle">
    Choose a theme color and portfolio template.
</p>


<?php if ($message != "") { ?>

    <div class="message">

        <?php echo htmlspecialchars($message); ?>

    </div>

<?php } ?>


<form method="POST">


    <!-- THEME COLOR -->

    <div class="box">

        <label for="theme_color">

            Choose Theme Color

        </label>


        <input
            type="color"
            id="theme_color"
            name="theme_color"
            value="<?php echo htmlspecialchars($current_color); ?>"
        >

    </div>


    <!-- TEMPLATES -->

    <div class="box">

        <label>
            Choose Portfolio Template
        </label>


        <div class="templates">


            <label class="template-option">

                <input
                    type="radio"
                    name="template"
                    value="classic"
                    <?php
                    if ($current_template == "classic") {
                        echo "checked";
                    }
                    ?>
                >

                <strong>
                    🟤 Classic
                </strong>

                <span>
                    Clean and professional
                </span>

            </label>


            <label class="template-option">

                <input
                    type="radio"
                    name="template"
                    value="modern"
                    <?php
                    if ($current_template == "modern") {
                        echo "checked";
                    }
                    ?>
                >

                <strong>
                    💜 Modern
                </strong>

                <span>
                    Bold and stylish
                </span>

            </label>


            <label class="template-option">

                <input
                    type="radio"
                    name="template"
                    value="minimal"
                    <?php
                    if ($current_template == "minimal") {
                        echo "checked";
                    }
                    ?>
                >

                <strong>
                    🌿 Minimal
                </strong>

                <span>
                    Simple and elegant
                </span>

            </label>


        </div>

    </div>


    <button type="submit">

        Save Customization

    </button>


</form>

</div></body></html>