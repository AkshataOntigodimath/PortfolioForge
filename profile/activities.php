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
    CREATE TABLE IF NOT EXISTS activities (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        activity_name VARCHAR(255) NOT NULL,
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
");

/* =========================
   ADD ACTIVITY
========================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $activity_name = trim($_POST["activity_name"] ?? "");
    $description = trim($_POST["description"] ?? "");

    if ($activity_name !== "") {

        $stmt = $conn->prepare("
            INSERT INTO activities
            (user_id, activity_name, description)
            VALUES (?, ?, ?)
        ");

        $stmt->bind_param(
            "iss",
            $user_id,
            $activity_name,
            $description
        );

        $stmt->execute();

        $stmt->close();

        $message = "Activity added successfully!";
    }
}

/* =========================
   GET ACTIVITIES
========================= */

$stmt = $conn->prepare("
    SELECT *
    FROM activities
    WHERE user_id = ?
    ORDER BY created_at DESC
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Extra Curricular Activities | PortfolioForge</title>

<style>

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #fff8dc;
    color: #333;
}

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

.container {
    max-width: 850px;
    margin: 40px auto;
    padding: 20px;
}

.card {
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    margin-bottom: 25px;
}

h1,
h2 {
    color: #6b4f2a;
}

label {
    display: block;
    margin-top: 15px;
    margin-bottom: 6px;
    font-weight: bold;
}

input,
textarea {
    width: 100%;
    padding: 11px;
    border: 1px solid #ccc;
    border-radius: 7px;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

textarea {
    min-height: 100px;
    resize: vertical;
}

.btn {
    margin-top: 18px;
    padding: 11px 20px;
    background: #c9a227;
    color: white;
    border: none;
    border-radius: 7px;
    cursor: pointer;
}

.btn:hover {
    background: #b58f20;
}

.success {
    background: #edf7ed;
    color: #2e6b2e;
    padding: 10px;
    border-radius: 7px;
    margin-bottom: 15px;
}

.activity {
    border-left: 4px solid #c9a227;
    padding: 12px 15px;
    margin-top: 15px;
    background: #fffdf4;
}

.activity h3 {
    margin: 0 0 6px;
    color: #6b4f2a;
}

.activity p {
    margin: 5px 0;
    line-height: 1.5;
}

</style>

</head>

<body>

<div class="navbar">

    <div class="logo">
        PortfolioForge
    </div>

    <a href="../dashboard.php" class="back">
        Back to Dashboard
    </a>

</div>

<div class="container">

    <div class="card">

        <h1>🎯 Extra Curricular Activities</h1>

        <p>
            Add activities that highlight your interests,
            participation and achievements outside academics.
        </p>

        <?php if ($message): ?>

            <div class="success">
                <?php echo htmlspecialchars($message); ?>
            </div>

        <?php endif; ?>

        <form method="POST">

            <label>
                Activity Name
            </label>

            <input
                type="text"
                name="activity_name"
                placeholder="Example: Volleyball, Dancing, NSS"
                required
            >

            <label>
                Description
            </label>

            <textarea
                name="description"
                placeholder="Describe your participation or achievement..."
            ></textarea>

            <button
                type="submit"
                class="btn"
            >
                Add Activity
            </button>

        </form>

    </div>


    <div class="card">

        <h2>Your Activities</h2>

        <?php if ($result->num_rows === 0): ?>

            <p>No activities added yet.</p>

        <?php else: ?>

            <?php while ($activity = $result->fetch_assoc()): ?>

                <div class="activity">

                    <h3>
                        <?php
                        echo htmlspecialchars(
                            $activity["activity_name"]
                        );
                        ?>
                    </h3>

                    <p>
                        <?php
                        echo nl2br(
                            htmlspecialchars(
                                $activity["description"]
                            )
                        );
                        ?>
                    </p>

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