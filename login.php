<?php
// login.php - User login
include 'config.php';

if (isLoggedIn()) {
    echo "<script>window.location.href = 'dashboard.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            echo "<script>window.location.href = 'dashboard.php';</script>";
            exit;
        } else {
            $error = "Invalid username or password";
        }
    } catch (PDOException $e) {
        $error = "Error logging in: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body { font-family: 'Arial', sans-serif; margin: 0; padding: 0; background: linear-gradient(to right, #e0f7fa, #ffffff); color: #333; }
        header { background: #0288d1; color: white; padding: 20px; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.2); }
        form { max-width: 400px; margin: auto; padding: 20px; background: white; border-radius: 15px; box-shadow: 0 6px 20px rgba(0,0,0,0.15); margin-top: 20px; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; font-size: 16px; }
        button { padding: 12px 25px; background: #ff6f00; color: white; border: none; border-radius: 25px; cursor: pointer; font-size: 16px; transition: background 0.3s; }
        button:hover { background: #ef6c00; }
        .error { color: #d32f2f; text-align: center; }
        @media (max-width: 768px) { form { padding: 15px; } }
    </style>
</head>
<body>
    <header>
        <h1>Login</h1>
    </header>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
    </form>
</body>
</html>
