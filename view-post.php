<?php
// view-post.php - Individual post page
include 'config.php';

try {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        echo "<script>window.location.href = 'index.php';</script>";
        exit;
    }

    $comm_stmt = $pdo->prepare("SELECT * FROM comments WHERE post_id = ? ORDER BY comment_date DESC");
    $comm_stmt->execute([$id]);
    $comments = $comm_stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'])) {
        $author = isLoggedIn() ? $_SESSION['username'] : $_POST['author'];
        $content = $_POST['content'];

        $stmt = $pdo->prepare("INSERT INTO comments (post_id, author, content) VALUES (?, ?, ?)");
        $stmt->execute([$id, $author, $content]);

        echo "<script>window.location.href = 'view-post.php?id=$id';</script>";
        exit;
    }

    $rel_stmt = $pdo->prepare("SELECT * FROM posts WHERE category = ? AND id != ? ORDER BY publish_date DESC LIMIT 3");
    $rel_stmt->execute([$post['category'], $id]);
    $related = $rel_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching post: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
    <style>
        body { font-family: 'Arial', sans-serif; margin: 0; padding: 0; background: linear-gradient(to right, #e0f7fa, #ffffff); color: #333; }
        header { background: #0288d1; color: white; padding: 20px; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.2); }
        .post-content { max-width: 800px; margin: auto; padding: 20px; background: white; border-radius: 15px; box-shadow: 0 6px 20px rgba(0,0,0,0.15); }
        .post-content h1 { color: #0288d1; font-size: 28px; }
        .meta { font-style: italic; color: #666; margin-bottom: 15px; }
        .comments { max-width: 800px; margin: auto; padding: 20px; }
        .comment { background: #f5f5f5; padding: 15px; margin: 10px 0; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        form { margin-top: 20px; }
        input, textarea { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; font-size: 16px; }
        button { padding: 12px 25px; background: #0288d1; color: white; border: none; border-radius: 25px; cursor: pointer; font-size: 16px; transition: background 0.3s; }
        button:hover { background: #0277bd; }
        .related { max-width: 800px; margin: auto; padding: 20px; }
        .related-post { margin: 10px 0; }
        .related-post a { color: #ff6f00; text-decoration: none; font-size: 18px; }
        .related-post a:hover { text-decoration: underline; }
        .edit-link { display: block; text-align: center; margin: 15px; }
        .edit-link a { color: #d32f2f; text-decoration: none; font-weight: bold; font-size: 16px; }
        @media (max-width: 768px) { .post-content, .comments, .related { padding: 15px; } }
    </style>
</head>
<body>
    <header>
        <h1><?php echo htmlspecialchars($post['title']); ?></h1>
    </header>
    <div class="post-content">
        <div class="meta">By <?php echo htmlspecialchars($post['author']); ?> on <?php echo $post['publish_date']; ?> | Category: <?php echo $post['category']; ?></div>
        <?php echo $post['content']; ?>
        <?php if (isLoggedIn() && $post['author'] == $_SESSION['username']): ?>
            <div class="edit-link"><a href="edit-post.php?id=<?php echo $id; ?>">Edit Post</a> | <a href="delete-post.php?id=<?php echo $id; ?>" onclick="return confirm('Delete this post?')">Delete Post</a></div>
        <?php endif; ?>
    </div>
    <div class="related">
        <h2>Related Posts</h2>
        <?php if (empty($related)): ?>
            <p>No related posts found.</p>
        <?php else: ?>
            <?php foreach ($related as $rel): ?>
                <div class="related-post"><a href="view-post.php?id=<?php echo $rel['id']; ?>"><?php echo htmlspecialchars($rel['title']); ?></a></div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="comments">
        <h2>Comments</h2>
        <?php if (empty($comments)): ?>
            <p>No comments yet.</p>
        <?php else: ?>
            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <strong><?php echo htmlspecialchars($comment['author']); ?></strong> on <?php echo $comment['comment_date']; ?><br>
                    <?php echo nl2br(htmlspecialchars($comment['content'])); ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="author" placeholder="Your Name" <?php echo isLoggedIn() ? 'value="' . htmlspecialchars($_SESSION['username']) . '" readonly' : 'required'; ?>>
            <textarea name="content" placeholder="Your Comment" required></textarea>
            <button type="submit" name="comment">Add Comment</button>
        </form>
    </div>
</body>
</html>
