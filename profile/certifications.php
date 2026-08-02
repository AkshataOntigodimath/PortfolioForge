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
   CREATE TABLE IF NEEDED
========================= */

$conn->query("
    CREATE TABLE IF NOT EXISTS certifications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        certification_name VARCHAR(255) NOT NULL,
        issuing_organization VARCHAR(255),
        issue_date VARCHAR(100),
        credential_url VARCHAR(500),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
");


/* =========================
   ADD CERTIFICATION
========================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $certification_name =
        trim($_POST["certification_name"] ?? "");

    $issuing_organization =
        trim($_POST["issuing_organization"] ?? "");

    $issue_date =
        trim($_POST["issue_date"] ?? "");

    $credential_url =
        trim($_POST["credential_url"] ?? "");


    if ($certification_name !== "") {

        $stmt = $conn->prepare("
            INSERT INTO certifications
            (
                user_id,
                certification_name,
                issuing_organization,
                issue_date,
                credential_url
            )
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "issss",
            $user_id,
            $certification_name,
            $issuing_organization,
            $issue_date,
            $credential_url
        );

        $stmt->execute();

        $stmt->close();

        $message = "Certification added successfully!";
    }
}


/* =========================
   DELETE CERTIFICATION
========================= */

if (isset($_GET["delete"])) {

    $id = intval($_GET["delete"]);

    $stmt = $conn->prepare("
        DELETE FROM certifications
        WHERE id = ? AND user_id = ?
    ");

    $stmt->bind_param(
        "ii",
        $id,
        $user_id
    );

    $stmt->execute();

    $stmt->close();

    header("Location: certifications.php");

    exit;
}


/* =========================
   GET CERTIFICATIONS
========================= */

$stmt = $conn->prepare("
    SELECT *
    FROM certifications
    WHERE user_id = ?
    ORDER BY created_at DESC
");

$stmt->bind_param(
    "i",
    $user_id
);

$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Certifications | PortfolioForge</title>

<style>

body {

    margin: 0;

    font-family: Arial, sans-serif;

    background: #fff8dc;

    color: #333;
}


/* NAVBAR */

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

.back {

    color: white;

    text-decoration: none;

    background: #c9a227;

    padding: 9px 16px;

    border-radius: 7px;
}


/* CONTAINER */

.container {

    max-width: 850px;

    margin: 40px auto;

    padding: 20px;
}


/* CARD */

.card {

    background: white;

    padding: 30px;

    border-radius: 15px;

    margin-bottom: 25px;

    box-shadow:
        0 5px 15px
        rgba(0,0,0,0.08);
}


/* HEADINGS */

h1,
h2 {

    color: #6b4f2a;
}


/* FORM */

label {

    display: block;

    margin-top: 15px;

    margin-bottom: 6px;

    font-weight: bold;
}

input {

    width: 100%;

    padding: 11px;

    border: 1px solid #ccc;

    border-radius: 7px;

    box-sizing: border-box;

    font-family: Arial, sans-serif;
}


/* BUTTON */

.btn {

    display: inline-block;

    margin-top: 18px;

    padding: 11px 20px;

    background: #c9a227;

    color: white;

    border: none;

    border-radius: 7px;

    text-decoration: none;

    cursor: pointer;
}

.btn:hover {

    background: #b58f20;
}


/* DELETE */

.delete {

    background: #b33a3a;

    margin-top: 8px;
}


/* MESSAGE */

.success {

    background: #edf7ed;

    color: #2e6b2e;

    padding: 12px;

    border-radius: 7px;

    margin-bottom: 20px;
}


/* CERTIFICATION */

.certification {

    border-left:
        4px solid #c9a227;

    padding:
        12px 15px;

    margin-top: 18px;

    background: #fffdf4;
}

.certification h3 {

    margin: 0 0 8px;

    color: #6b4f2a;
}

.certification p {

    margin: 6px 0;

    line-height: 1.5;
}

.certification a {

    color: #6b4f2a;

    font-weight: bold;
}


/* MOBILE */

@media (max-width: 700px) {

    .navbar {

        padding:
            15px 20px;
    }

    .container {

        margin:
            20px auto;

        padding:
            15px;
    }

}

</style>

</head>


<body>


<!-- NAVBAR -->

<div class="navbar">

    <div class="logo">
        PortfolioForge
    </div>

    <a
        href="../dashboard.php"
        class="back"
    >
        Back to Dashboard
    </a>

</div>


<div class="container">


<!-- ADD CERTIFICATION -->

<div class="card">

    <h1>
        🏆 Certifications
    </h1>

    <p>
        Add your professional certifications,
        courses and technical credentials.
    </p>


    <?php if ($message !== ""): ?>

        <div class="success">

            <?php
            echo htmlspecialchars($message);
            ?>

        </div>

    <?php endif; ?>


    <form method="POST">


        <label>
            Certification Name
        </label>

        <input
            type="text"
            name="certification_name"
            placeholder="Example: Python for Data Science"
            required
        >


        <label>
            Issuing Organization
        </label>

        <input
            type="text"
            name="issuing_organization"
            placeholder="Example: IBM / NPTEL / Infosys"
        >


        <label>
            Issue Date / Year
        </label>

        <input
            type="text"
            name="issue_date"
            placeholder="Example: 2026"
        >


        <label>
            Credential URL
        </label>

        <input
            type="url"
            name="credential_url"
            placeholder="https://..."
        >


        <button
            type="submit"
            class="btn"
        >
            Add Certification
        </button>


    </form>

</div>


<!-- EXISTING CERTIFICATIONS -->

<div class="card">

    <h2>
        Your Certifications
    </h2>


    <?php if ($result->num_rows === 0): ?>

        <p>
            No certifications added yet.
        </p>


    <?php else: ?>


        <?php while ($cert = $result->fetch_assoc()): ?>


            <div class="certification">


                <h3>

                    <?php

                    echo htmlspecialchars(
                        $cert["certification_name"]
                    );

                    ?>

                </h3>


                <?php if (
                    !empty(
                        $cert["issuing_organization"]
                    )
                ): ?>

                    <p>

                        <strong>
                            Organization:
                        </strong>

                        <?php

                        echo htmlspecialchars(
                            $cert["issuing_organization"]
                        );

                        ?>

                    </p>

                <?php endif; ?>


                <?php if (
                    !empty($cert["issue_date"])
                ): ?>

                    <p>

                        <strong>
                            Date:
                        </strong>

                        <?php

                        echo htmlspecialchars(
                            $cert["issue_date"]
                        );

                        ?>

                    </p>

                <?php endif; ?>


                <?php if (
                    !empty($cert["credential_url"])
                ): ?>

                    <p>

                        <a
                            href="<?php
                            echo htmlspecialchars(
                                $cert["credential_url"]
                            );
                            ?>"
                            target="_blank"
                        >
                            View Credential
                        </a>

                    </p>

                <?php endif; ?>


                <a
                    href="certifications.php?delete=<?php
                    echo $cert["id"];
                    ?>"
                    class="btn delete"
                    onclick="return confirm(
                        'Delete this certification?'
                    );"
                >
                    Delete
                </a>


            </div>


        <?php endwhile; ?>


    <?php endif; ?>


</div>


</div>

</body>

</html>

<?php

$stmt->close();

?>