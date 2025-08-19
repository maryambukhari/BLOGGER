<?php
// db.php - Database connection file
$host = 'localhost';
$dbname = 'db37gzt4yjaj2v'; // Use the provided database name
$user = 'uxhc7qjwxxfub';
$pass = 'g4t0vezqttq6';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET CHARACTER SET utf8mb4");
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
