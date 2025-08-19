<?php
// create_post.php - Post creation page
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $excerpt = substr(strip_tags($content), 0, 200) . '...';
    $author = $_POST['author'];
    $category = $_POST['category'];

    $stmt = $pdo->prepare("INSERT INTO posts (title, content, excerpt, author, category) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$title, $content, $excerpt, $author, $category]);

    echo "<script>window.location.href = 'index.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: linear-gradient(to right, #f0f2f5, #ffffff); color: #333; }
        header { background: #4285f4; color: white; padding: 20px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        form { max-width: 800px; margin: auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        input, textarea, select { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; }
        button { padding: 10px 20px; background: #0f9d58; color: white; border: none; border-radius: 5px; cursor: pointer; transition: background 0.3s; }
        button:hover { background: #0b8043; }
        #editor { border: 1px solid #ddd; min-height: 200px; padding: 10px; margin: 10px 0; border-radius: 5px; background: #fff; }
        .toolbar { margin: 10px 0; }
        .toolbar button { background: #4285f4; margin-right: 5px; }
        @media (max-width: 768px) { form { padding: 10px; } }
    </style>
</head>
<body>
    <header>
        <h1>Create New Post</h1>
    </header>
    <form method="POST">
        <input type="text" name="title" placeholder="Title" required>
        <input type="text" name="author" placeholder="Author" required>
        <select name="category" required>
            <option value="Technology">Technology</option>
            <option value="Lifestyle">Lifestyle</option>
            <option value="Business">Business</option>
            <option value="Travel">Travel</option>
        </select>
        <div class="toolbar">
            <button type="button" onclick="formatText('bold')">Bold</button>
            <button type="button" onclick="formatText('italic')">Italic</button>
            <button type="button" onclick="formatText('underline')">Underline</button>
            <button type="button" onclick="formatText('insertUnorderedList')">Bullet List</button>
            <button type="button" onclick="formatText('insertOrderedList')">Number List</button>
        </div>
        <div id="editor" contenteditable="true"></div>
        <input type="hidden" name="content" id="content">
        <button type="submit" onclick="document.getElementById('content').value = document.getElementById('editor').innerHTML;">Publish</button>
    </form>
    <script>
        function formatText(command) {
            document.execCommand(command, false, null);
        }
    </script>
</body>
</html>
