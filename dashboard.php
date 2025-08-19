<?php
// dashboard.php - User dashboard
include 'config.php';
requireLogin();

try {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE author = ? ORDER BY publish_date DESC");
    $stmt->execute([$_SESSION['username']]);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching posts: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body { font-family: 'Arial', sans-serif; margin: 0; padding: 0; background: linear-gradient(to right, #e0f7fa, #ffffff); color: #333; }
        header { background: #0288d1; color: white; padding: 20px; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.2); }
        .posts { max-width: 800px; margin: auto; padding: 20px; }
        .post { background: white; padding: 20px; margin: 10px 0; border-radius: 15px; box-shadow: 0 6px 20px rgba(0,0,0,0.15); }
        .post h2 { margin: 0 0 10px; color: #0288d1; font-size: 24px; }
        .post a { color: #ff6f00; text-decoration: none; margin-right: 10px; }
        .post a:hover { text-decoration: underline; }
        .post .delete { color: #d32f2f; }
        @media (max-width: 768px) { .posts { padding: 15px; } }
    </style>
</head>
<body>
    <header>
        <h1>My Dashboard</h1>
    </header>
    <div class="posts">
        <h2>My Posts</h2>
        <a href="new-post.php">Create New Post</a>
        <?php if (empty($posts)): ?>
            <p>No posts created yet.</p>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="post">
                    <h2><a href="view-post.php?id=<?php echo $post['id']; ?>"><?php echo htmlspecialchars($post['title']); ?></a></h2>
                    <p><?php echo htmlspecialchars($post['excerpt']); ?></p>
                    <p>Category: <?php echo $post['category']; ?> | Published: <?php echo $post['publish_date']; ?></p>
                    <a href="edit-post.php?id=<?php echo $post['id']; ?>">Edit</a>
                    <a href="delete-post.php?id=<?php echo $post['id']; ?>" class="delete" onclick="return confirm('Delete this post?')">Delete</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
