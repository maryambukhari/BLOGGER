<?php
// edit-post.php - Edit post
include 'config.php';
requireLogin();

try {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ? AND author = ?");
    $stmt->execute([$id, $_SESSION['username']]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        echo "<script>window.location.href = 'dashboard.php';</script>";
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $excerpt = substr(strip_tags($content), 0, 200) . '...';
        $category = $_POST['category'];

        $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ?, excerpt = ?, category = ? WHERE id = ?");
        $stmt->execute([$title, $content, $excerpt, $category, $id]);

        echo "<script>window.location.href = 'view-post.php?id=$id';</script>";
        exit;
    }
} catch (PDOException $e) {
    die("Error editing post: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <style>
        body { font-family: 'Arial', sans-serif; margin: 0; padding: 0; background: linear-gradient(to right, #e0f7fa, #ffffff); color: #333; }
        header { background: #0288d1; color: white; padding: 20px; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.2); }
        form { max-width: 800px; margin: auto; padding: 20px; background: white; border-radius: 15px; box-shadow: 0 6px 20px rgba(0,0,0,0.15); }
        input, textarea, select { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; font-size: 16px; }
        button { padding: 12px 25px; background: #ff6f00; color: white; border: none; border-radius: 25px; cursor: pointer; font-size: 16px; transition: background 0.3s; }
        button:hover { background: #ef6c00; }
        #editor { border: 1px solid #ccc; min-height: 300px; padding: 15px; margin: 10px 0; border-radius: 5px; background: #fff; font-size: 16px; }
        .toolbar { margin: 10px 0; }
        .toolbar button { background: #0288d1; margin-right: 10px; border-radius: 5px; }
        .toolbar button:hover { background: #0277bd; }
        @media (max-width: 768px) { form { padding: 15px; } #editor { min-height: 200px; } }
    </style>
</head>
<body>
    <header>
        <h1>Edit Post</h1>
    </header>
    <form method="POST">
        <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
        <select name="category" required>
            <option value="Technology" <?php if($post['category']=='Technology') echo 'selected'; ?>>Technology</option>
            <option value="Lifestyle" <?php if($post['category']=='Lifestyle') echo 'selected'; ?>>Lifestyle</option>
            <option value="Business" <?php if($post['category']=='Business') echo 'selected'; ?>>Business</option>
            <option value="Travel" <?php if($post['category']=='Travel') echo 'selected'; ?>>Travel</option>
        </select>
        <div class="toolbar">
            <button type="button" onclick="formatText('bold')">Bold</button>
            <button type="button" onclick="formatText('italic')">Italic</button>
            <button type="button" onclick="formatText('underline')">Underline</button>
            <button type="button" onclick="formatText('insertUnorderedList')">Bullet List</button>
            <button type="button" onclick="formatText('insertOrderedList')">Number List</button>
        </div>
        <div id="editor" contenteditable="true"><?php echo $post['content']; ?></div>
        <input type="hidden" name="content" id="content">
        <button type="submit" onclick="document.getElementById('content').value = document.getElementById('editor').innerHTML;">Update</button>
    </form>
    <script>
        function formatText(command) {
            document.execCommand(command, false, null);
        }
    </script>
</body>
</html>
