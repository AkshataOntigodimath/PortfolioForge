<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: auth/login.php");
    exit;
}

$user_name = $_SESSION["user_name"];
$user_type = $_SESSION["user_type"];
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

        .container {
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
        }

        .welcome {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        h1 {
            color: #6b4f2a;
        }

        .type {
            color: #8a6a18;
            font-weight: bold;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 30px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .card h3 {
            color: #6b4f2a;
        }

        .card p {
            color: #777;
        }

        .btn {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 18px;
            background: #c9a227;
            color: white;
            text-decoration: none;
            border-radius: 7px;
        }

        @media (max-width: 700px) {
            .cards {
                grid-template-columns: 1fr;
            }
        }
    </style>

</head>

<body>

    <div class="navbar">

        <div class="logo">
            Portfolia
        </div>

        <a href="auth/logout.php" class="logout">
            Logout
        </a>

    </div>

    <div class="container">

        <div class="welcome">

            <h1>
                Welcome, <?php echo htmlspecialchars($user_name); ?>! 👋
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

        <div class="cards">

            <div class="card">
                <h3>👤 My Profile</h3>
                <p>Build your professional profile.</p>
               <a href="profile/profile.php" class="btn">My Profile</a>
            </div>

            <div class="card">
                <h3>🎨 Customize</h3>
                <p>Choose colors, layouts and templates.</p>
                <a href="#" class="btn">Coming Soon</a>
            </div>

            <div class="card">
                <h3>👀 Preview</h3>
                <p>Preview your portfolio before publishing.</p>
                <a href="#" class="btn">Coming Soon</a>
            </div>

        </div>

    </div>

</body>

</html>