<?php
require_once "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $user_type = $_POST["user_type"];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, password, user_type)
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssss",
        $name,
        $email,
        $hashed_password,
        $user_type
    );

    if ($stmt->execute()) {
        echo "<script>
                alert('Account created successfully! 🎉');
                window.location.href = 'login.php';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('Registration failed. Please try again.');
              </script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | Portfolia</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #fff8dc;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .register-box {
            width: 400px;
            background: white;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }

        h1 {
            text-align: center;
            color: #6b4f2a;
            margin-bottom: 8px;
        }

        .subtitle {
            text-align: center;
            color: #777;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 6px;
            font-weight: bold;
            color: #4d3925;
        }

        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 15px;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #c9a227;
        }

        button {
            width: 100%;
            margin-top: 25px;
            padding: 13px;
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

        .login-link {
            text-align: center;
            margin-top: 20px;
        }

        .login-link a {
            color: #8a6a18;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="register-box">

    <h1>Create Account</h1>

    <p class="subtitle">
        Start building your professional portfolio
    </p>

    <form method="POST">

        <label for="name">Full Name</label>
        <input
            type="text"
            id="name"
            name="name"
            placeholder="Enter your full name"
            required
        >

        <label for="email">Email</label>
        <input
            type="email"
            id="email"
            name="email"
            placeholder="Enter your email"
            required
        >

        <label for="password">Password</label>
        <input
            type="password"
            id="password"
            name="password"
            placeholder="Create a password"
            required
        >

        <label for="user_type">I am a</label>

        <select id="user_type" name="user_type" required>
            <option value="">Select your type</option>
            <option value="student">Student</option>
            <option value="professional">Corporate / Professional</option>
        </select>

        <button type="submit">
            Create Account
        </button>

    </form>

    <div class="login-link">
        Already have an account?
        <a href="login.php">Login</a>
    </div>

</div>

</body>
</html>