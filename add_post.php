<?php
session_start();
require_once 'vendor/autoload.php';

use Aries\Dbmodel\Models\Blog;

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$blog = new Blog();
$error = '';
$title = '';
$content = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if ($title === '' || $content === '') {
        $error = "Both title and content are required.";
    } else {
        $authorId = $_SESSION['user']['id'];

        $blog->createPost([
            'title' => $title,
            'content' => $content,
            'author_id' => $authorId
        ]);

        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Post - Blog App</title>
    <!-- <style>
        body {
            background:rgb(8, 9, 9); /* Light gray background */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* More modern font */
            margin: 0;
            padding: 20px; /* Add some padding around the whole body */
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh; /* Ensure full viewport height */
        }

        .form-container {
            background: #fff;
            padding: 30px; /* Slightly reduce padding */
            border-radius: 12px; /* More rounded corners */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); /* Softer shadow */
            width: 100%;
            max-width: 600px; /* Slightly wider container */
        }

        h2 {
            text-align: center;
            color: #343a40; /* Darker, more readable heading */
            margin-bottom: 25px;
            font-weight: 500; /* Slightly lighter font weight */
        }

        label {
            display: block;
            margin-bottom: 6px; /* Slightly less margin */
            color: #495057; /* Darker label text */
            font-size: 15px;
            font-weight: 600; /* Make labels a bit bolder */
        }

        input[type="text"],
        textarea {
            width: calc(100% - 30px); /* Adjust width for padding */
            padding: 10px 15px; /* Slightly less vertical padding */
            margin-bottom: 15px; /* Less margin below input */
            border: 1px solid #ced4da; /* Light gray border */
            border-radius: 6px; /* More rounded input fields */
            background: #f8f9fa; /* Very light gray background */
            font-size: 15px;
            color: #343a40;
            transition: border-color 0.2s ease-in-out; /* Smoother transition */
        }

        input[type="text"]:focus,
        textarea:focus {
            border-color: #007bff; /* Blue focus color */
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25); /* Subtle focus shadow */
            background: #fff;
        }

        input[type="submit"] {
            width: 100%;
            background: #007bff; /* Blue submit button */
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }

        input[type="submit"]:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        .error {
            background: #ffebee; /* Light red background */
            border-left: 4px solid #f44336; /* Red error indicator */
            color: #d32f2f; /* Darker red text */
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            font-size: 15px;
        }

        .back-link {
            text-align: center;
            margin-top: 15px;
        }

        .back-link a {
            color: #007bff; /* Blue link color */
            text-decoration: none;
            font-size: 15px;
            transition: color 0.2s ease-in-out;
        }

        .back-link a:hover {
            color: #0056b3;
            text-decoration: underline;
        }
    </style> -->
    <style>
    body {
        background: #f5f5f5; /* Very light gray */
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
    }

    .form-container {
        background: #ffffff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 500px;
    }

    h2 {
        text-align: center;
        color: #222222;
        margin-bottom: 20px;
        font-weight: 600;
    }

    label {
        display: block;
        margin-bottom: 5px;
        color: #555555;
        font-size: 14px;
        font-weight: 500;
    }

    input[type="text"],
    textarea {
        width: 100%;
        padding: 10px 12px;
        margin-bottom: 15px;
        border: 1px solid #cccccc;
        border-radius: 5px;
        background: #fafafa;
        font-size: 14px;
        color: #333333;
        transition: border-color 0.2s;
    }

    input[type="text"]:focus,
    textarea:focus {
        border-color: #333333;
        outline: none;
        background: #ffffff;
    }

    input[type="submit"] {
        width: 100%;
        background: #222222;
        color: #ffffff;
        border: none;
        padding: 12px;
        font-size: 15px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    input[type="submit"]:hover {
        background-color: #000000;
    }

    .error {
        background: #ffe6e6;
        border-left: 4px solid #cc0000;
        color: #cc0000;
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 5px;
        font-size: 14px;
    }

    .back-link {
        text-align: center;
        margin-top: 15px;
    }

    .back-link a {
        color: #222222;
        text-decoration: none;
        font-size: 14px;
    }

    .back-link a:hover {
        text-decoration: underline;
    }
</style>

</head>
<body>

<div class="form-container">
    <h2>Add New Post</h2>

    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="title">Title</label>
        <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($title); ?>" required>

        <label for="content">Content</label>
        <textarea name="content" id="content" rows="6" required><?php echo htmlspecialchars($content); ?></textarea>

        <input type="submit" value="Publish Post">
    </form>

    <div class="back-link">
        <a href="index.php">← Back to Home</a>
    </div>
</div>

</body>
</html>