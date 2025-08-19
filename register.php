<?php
// register.php - User registration
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $password]);

        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['username'] = $username;

        echo "<script>window.location.href = 'dashboard.php';</script>";
        exit;
    } catch (PDOException $e) {
        $error = "Error registering: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
        <h1>Register</h1>
    </header>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
    </form>
</body>
</html>
