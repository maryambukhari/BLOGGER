<?php
// delete-post.php - Delete a post
include 'config.php';
requireLogin();

try {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ? AND author = ?");
    $stmt->execute([$id, $_SESSION['username']]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($post) {
        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->execute([$id]);
    }
    echo "<script>window.location.href = 'dashboard.php';</script>";
    exit;
} catch (PDOException $e) {
    die("Error deleting post: " . $e->getMessage());
}
?>
