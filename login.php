<?php
session_start();
require_once 'vendor/autoload.php';

use Aries\Dbmodel\Models\User;

if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $userModel = new User();
    $user = $userModel->getUserByUsername($username);

    if ($user && hash('sha256', $password) === $user['password']) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'first_name' => $user['first_name']
        ];
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Blog App</title>
    <style>
        body {
            background: #e9ecef; /* Light gray background */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Modern font */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .login-container {
            background: #fff;
            padding: 30px;
            border-radius: 12px; /* Rounded corners */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); /* Softer shadow */
            width: 100%;
            max-width: 400px; /* Slightly wider */
            text-align: center;
        }

        h2 {
            margin-bottom: 25px;
            color: #343a40; /* Darker heading */
            font-size: 2.2em;
            font-weight: 500;
        }

        input[type="text"],
        input[type="password"] {
            width: calc(100% - 30px);
            padding: 10px 15px;
            margin-top: 10px;
            margin-bottom: 18px;
            border: 1px solid #ced4da; /* Light gray border */
            border-radius: 6px; /* Rounded inputs */
            background-color: #f8f9fa; /* Light background */
            font-size: 1em;
            box-sizing: border-box;
            transition: border-color 0.2s ease-in-out;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #007bff; /* Blue focus */
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25); /* Subtle focus shadow */
            background-color: #fff;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #007bff; /* Blue button */
            border: none;
            color: white;
            font-weight: 500;
            border-radius: 6px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }

        input[type="submit"]:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        .error {
            color: #f44336; /* Red error text */
            margin-top: 10px;
            font-size: 0.95em;
        }

        .link {
            margin-top: 20px;
            font-size: 0.95em;
            color: #6c757d; /* Gray link text */
        }

        .link a {
            color: #007bff; /* Blue link */
            text-decoration: none;
            font-weight: 500;
        }

        .link a:hover {
            text-decoration: underline;
            color: #0056b3;
        }

        .back-button {
            display: inline-block;
            margin-top: 20px;
            background-color: #6c757d; /* Gray back button */
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            text-decoration: none;
            transition: background-color 0.2s ease-in-out;
            font-size: 0.95em;
        }

        .back-button:hover {
            background-color: #495057; /* Darker gray on hover */
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Login">
        </form>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="link">
            Don't have an account? <a href="register.php">Register here</a>
        </div>

        <a href="index.php" class="back-button">Back to Home</a>
    </div>
</body>
</html>