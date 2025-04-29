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
        background: #f5f5f5;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
        padding: 20px;
    }

    .login-container {
        background: #ffffff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
        text-align: center;
    }

    h2 {
        margin-bottom: 20px;
        color: #222222;
        font-size: 2em;
        font-weight: 600;
    }

    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 10px 12px;
        margin-top: 10px;
        margin-bottom: 15px;
        border: 1px solid #cccccc;
        border-radius: 5px;
        background-color: #fafafa;
        font-size: 14px;
        color: #333333;
        box-sizing: border-box;
        transition: border-color 0.2s;
    }

    input[type="text"]:focus,
    input[type="password"]:focus {
        border-color: #333333;
        outline: none;
        background-color: #ffffff;
    }

    input[type="submit"] {
        width: 100%;
        padding: 12px;
        background-color: #222222;
        border: none;
        color: white;
        font-weight: 500;
        border-radius: 5px;
        font-size: 15px;
        cursor: pointer;
        transition: background-color 0.2s;
        margin-top: 10px;
    }

    input[type="submit"]:hover {
        background-color: #000000;
    }

    .error {
        color: #cc0000;
        margin-top: 10px;
        font-size: 14px;
    }

    .link {
        margin-top: 20px;
        font-size: 14px;
        color: #666666;
    }

    .link a {
        color: #222222;
        text-decoration: underline;
        font-weight: 500;
    }

    .link a:hover {
        color: #000000;
    }

    .back-button {
        display: inline-block;
        margin-top: 20px;
        background-color: #555555;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        font-weight: 500;
        text-decoration: none;
        transition: background-color 0.2s;
        font-size: 14px;
    }

    .back-button:hover {
        background-color: #333333;
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