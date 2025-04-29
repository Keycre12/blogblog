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