<?php /* index.php */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Radius</title>
<style>
    body {
        margin: 0;
        font-family: 'Segoe UI', sans-serif;
        background: url('https://images.unsplash.com/photo-1529333166437-7750a6dd5a70?auto=format&fit=crop&w=1400&q=80') center/cover no-repeat;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
        text-shadow: 0 2px 8px rgba(0,0,0,0.6);
    }
    .container {
        text-align: center;
        background: rgba(0,0,0,0.45);
        padding: 40px;
        border-radius: 12px;
    }
    h1 { font-size: 3rem; margin-bottom: 10px; }
    p { font-size: 1.2rem; margin-bottom: 30px; }
    a {
        display: inline-block;
        padding: 12px 24px;
        margin: 8px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: bold;
        background: #ff8c42;
        color: black;
        transition: 0.2s;
    }
    a:hover { background: #ffa866; }
</style>
</head>
<body>
<div class="container">
    <h1>Radius</h1>
    <p>Upload your personal profile page here!.</p>
    <a href="login.php">Login</a>
    <a href="register.php">Sign Up</a>
</div>
</body>
</html>
