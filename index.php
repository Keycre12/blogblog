<?php
session_start();
require_once 'vendor/autoload.php';

use Aries\Dbmodel\Models\Blog;

$blog = new Blog();
$posts = [];

if (isset($_SESSION['user'])) {
    $userId = $_SESSION['user']['id'];
    $posts = $blog->getPostsByUser($userId);
} else {
    $posts = $blog->getAllPosts();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blog Posts</title>
    <style>
        body {
            background: #e9ecef; /* Light gray background */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* More modern font */
            margin: 0;
            padding: 20px; /* Add some padding around the whole body */
        }

        h1 {
            text-align: center;
            color: #343a40; /* Darker heading */
            margin-bottom: 35px;
            font-size: 2.5em;
            font-weight: 500;
        }

        .buttons {
            text-align: center;
            margin-bottom: 25px;
        }

        .buttons a {
            display: inline-block;
            background-color: #007bff; /* Blue buttons */
            padding: 10px 20px;
            margin: 0 8px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            border-radius: 6px;
            transition: background-color 0.2s ease-in-out;
            font-size: 1em;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); /* Subtle button shadow */
        }

        .buttons a:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        .post-container {
            background: #fff;
            margin: 20px auto;
            padding: 20px;
            border-radius: 12px; /* More rounded corners */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); /* Softer shadow */
            max-width: 700px;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            border: 1px solid #dee2e6; /* Light border */
        }

        .post-container:hover {
            transform: translateY(-3px); /* Slight lift on hover */
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12); /* More pronounced shadow on hover */
        }

        .post-title {
            font-size: 1.6em;
            color: #007bff; /* Blue title */
            margin-top: 0;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .post-meta {
            font-size: 0.95em;
            color: #6c757d; /* Gray meta text */
            margin-bottom: 12px;
        }

        .post-content {
            font-size: 1.05em;
            color: #495057; /* Darker content text */
            line-height: 1.6;
            white-space: pre-line; /* Preserve line breaks from textarea */
        }
    </style>
</head>
<body>

    <h1><?php echo isset($_SESSION['user']) ? 'My Blog Posts' : 'Recent Blog Posts'; ?></h1>

    <div class="buttons">
        <?php if (isset($_SESSION['user'])): ?>
            <a href="add_post.php">Add New Post</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </div>

    <?php if (empty($posts)): ?>
        <p style="text-align: center; color: #6c757d;">No posts available.</p>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
            <div class="post-container">
                <h2 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h2>
                <p class="post-meta">
                    Posted on <?php echo date("F j, Y, g:i a", strtotime($post['created_at'])); ?>
                    <?php if (isset($_SESSION['user'])): ?>
                        by User ID <?php echo $post['author_id']; ?>
                    <?php endif; ?>
                </p>
                <p class="post-content"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</body>
</html>