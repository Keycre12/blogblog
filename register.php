<?php
session_start();
require_once 'vendor/autoload.php';

use Aries\Dbmodel\Models\User;

$userModel = new User();
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($firstName) || empty($lastName) || empty($username) || empty($password)) {
        $error = "All fields are required.";
    } else {
        $hashedPassword = hash('sha256', $password);

        $existing = $userModel->getUserByUsername($username);
        if ($existing) {
            $error = "Username is already taken.";
        } else {
            $userModel->createUser([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'username' => $username,
                'password' => $hashedPassword
            ]);
            $success = "User registered successfully! You can now <a href='login.php'>log in</a>.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Blog App</title>
    <!-- <style>
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

        .form-container {
            background: #fff;
            padding: 30px;
            border-radius: 12px; /* Rounded corners */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); /* Softer shadow */
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            margin-bottom: 25px;
            color: #343a40; /* Darker heading */
            font-size: 2.2em;
            font-weight: 500;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            text-align: left;
            margin-top: 12px; /* Slightly less top margin */
            margin-bottom: 6px; /* Slightly less bottom margin */
            font-weight: 500; /* Slightly lighter label weight */
            color: #495057; /* Darker label text */
            font-size: 1em;
        }

        input[type="text"],
        input[type="password"] {
            width: calc(100% - 30px);
            padding: 10px 15px;
            margin-bottom: 15px; /* Less bottom margin */
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
            background-color: #28a745; /* Green register button */
            border: none;
            color: white;
            font-weight: 500;
            border-radius: 6px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
            margin-top: 15px; /* Add some top margin */
        }

        input[type="submit"]:hover {
            background-color: #1e7e34; /* Darker green on hover */
        }

        .message {
            margin-top: 15px;
            font-size: 0.95em;
            text-align: center;
        }

        .error {
            color: #f44336; /* Red error text */
        }

        .success {
            color: #28a745; /* Green success text */
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

        .message a {
            color: #007bff; /* Blue link */
            text-decoration: none;
            font-weight: 500;
        }

        .message a:hover {
            text-decoration: underline;
            color: #0056b3;
        }
    </style> -->

    <style>
    body {
        background: #f5f5f5; /* Very light gray background */
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
        padding: 20px;
    }

    .form-container {
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

    form {
        margin-top: 20px;
    }

    label {
        display: block;
        text-align: left;
        margin-top: 12px;
        margin-bottom: 5px;
        font-weight: 500;
        color: #555555;
        font-size: 14px;
    }

    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 10px 12px;
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
        margin-top: 15px;
    }

    input[type="submit"]:hover {
        background-color: #000000;
    }

    .message {
        margin-top: 15px;
        font-size: 14px;
        text-align: center;
    }

    .error {
        color: #cc0000;
    }

    .success {
        color: #228822;
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

    .message a {
        color: #222222;
        text-decoration: underline;
        font-weight: 500;
    }

    .message a:hover {
        color: #000000;
    }
</style>

</head>
<body>

<div class="form-container">
    <h2>REGISTER</h2>

    <?php if ($error): ?>
        <div class="message error"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="message success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="POST">
        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" id="first_name" required>

        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" id="last_name" required>

        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <input type="submit" value="Register">
    </form>

    <a href="index.php" class="back-button">Back to Home</a>
</div>

</body>
</html>