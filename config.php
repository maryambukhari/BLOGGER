<?php
// config.php - Shared configuration and utility functions
session_start();
include 'db.php';

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Redirect to login if not authenticated
function requireLogin() {
    if (!isLoggedIn()) {
        echo "<script>window.location.href = 'login.php';</script>";
        exit;
    }
}
?>
