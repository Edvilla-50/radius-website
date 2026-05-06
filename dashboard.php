<?php
session_start();

if (!isset($_SESSION['userId'])) {
    header("Location: login.html");
    exit;
}

echo "Welcome, " . htmlspecialchars($_SESSION['name']);
?>
