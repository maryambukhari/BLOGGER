<<?php
// index.php - Homepage
include 'config.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['cat']) ? trim($_GET['cat']) : '';
$valid_categories = ['Technology', 'Lifestyle', 'Business', 'Travel'];

try {
    $query = "SELECT * FROM posts WHERE 1=1";
    $params = [];

    if ($search) {
        $query .= " AND (title LIKE ? OR content LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }

    if ($category && in_array($category, $valid_categories)) {
        $query .= " AND category = ?";
        $params[] = $category;
    }

    $query .= " ORDER BY publish_date DESC LIMIT 10";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
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
    <title>Blog Homepage</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #e0f7fa, #ffffff);
            color: #333;
        }
        header {
            background: #0288d1;
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
        nav {
            display: flex;
            justify-content: center;
            background: #fff;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        nav a {
            margin: 0 20px;
            text-decoration: none;
            color: #0288d1;
            font-weight: bold;
            font-size: 18px;
            transition: color 0.3s;
        }
        nav a:hover {
            color: #ff6f00;
        }
        .search-bar {
            text-align: center;
            margin: 20px;
        }
        .search-bar input {
            padding: 12px;
            width: 350px;
            border: 1px solid #ccc;
            border-radius: 25px;
            font-size: 16px;
        }
        .search-bar button {
            padding: 12px 25px;
            background: #0288d1;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }
        .search-bar button:hover {
            background: #0277bd;
        }
        .posts {
            max-width: 1200px;
            margin: auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .post {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .post:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .post h2 {
            margin: 0 0 10px;
            color: #0288d1;
            font-size: 24px;
        }
        .post p {
            margin: 5px 0;
            line-height: 1.6;
        }
        .post a {
            color: #ff6f00;
            text-decoration: none;
            font-weight: bold;
        }
        .post a:hover {
            text-decoration: underline;
        }
        .no-posts {
            text-align: center;
            padding: 20px;
            font-size: 18px;
            color: #666;
        }
        .no-posts a {
            color: #ff6f00;
            text-decoration: none;
            font-weight: bold;
        }
        .no-posts a:hover {
            text-decoration: underline;
        }
        footer {
            text-align: center;
            padding: 15px;
            background: #0288d1;
            color: white;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
        @media (max-width: 768px) {
            .posts {
                grid-template-columns: 1fr;
            }
            nav {
                flex-direction: column;
            }
            .search-bar input {
                width: 80%;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>My Blog</h1>
    </header>
    <nav>
        <a href="index.php">Home</a>
        <a href="blog.php?cat=Technology">Technology</a>
        <a href="blog.php?cat=Lifestyle">Lifestyle</a>
        <a href="blog.php?cat=Business">Business</a>
        <a href="blog.php?cat=Travel">Travel</a>
        <?php if (isLoggedIn()): ?>
            <a href="dashboard.php">Dashboard</a>
            <a href="new-post.php">Create Post</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>
    <div class="search-bar">
        <form action="index.php" method="GET">
            <input type="text" name="search" placeholder="Search posts..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>
    </div>
    <div class="posts">
        <?php if (empty($posts)): ?>
            <div class="no-posts">
                <p>No posts found<?php echo $category ? ' in the ' . htmlspecialchars($category) . ' category' : ''; ?>.</p>
                <?php if (isLoggedIn()): ?>
                    <p><a href="new-post.php">Create a new post</a> to share your thoughts!</p>
                <?php else: ?>
                    <p><a href="login.php">Log in</a> or <a href="register.php">register</a> to create a post!</p>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="post">
                    <h2><a href="view-post.php?id=<?php echo $post['id']; ?>"><?php echo htmlspecialchars($post['title']); ?></a></h2>
                    <p><?php echo htmlspecialchars($post['excerpt']); ?></p>
                    <p>By <?php echo htmlspecialchars($post['author']); ?> on <?php echo $post['publish_date']; ?></p>
                    <p>Category: <?php echo $post['category']; ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <footer>&copy; 2025 My Blog</footer>
</body>
</html>
