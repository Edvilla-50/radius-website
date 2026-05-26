<link rel="icon" type="image/jpeg" href="/image/radius-image.jpg">
<?php /* index.php */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
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
        backdrop-filter: blur(5px); /* Adds a nice frosted effect to match the dark theme */
        max-width: 90%;
    }
    h1 { font-size: 3rem; margin-bottom: 10px; }
    p { font-size: 1.2rem; margin-bottom: 30px; }
    
    .button-group {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
    }

    a {
        display: inline-block;
        padding: 12px 24px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: bold;
        background: #ff8c42;
        color: black;
        transition: 0.2s;
        text-shadow: none; /* Keeps text legible on the bright button */
    }
    a:hover { background: #ffa866; transform: translateY(-1px); }
    
    a.secondary {
        background: rgba(255,255,255,0.15);
        color: white;
        border: 1px solid rgba(255,255,255,0.4);
    }
    a.secondary:hover { background: rgba(255,255,255,0.25); }
</style>
</head>
<body>
<div class="container">
    <h1>Radius</h1>
    <p>Upload your personal profile page here! With HTML!</p>
    
    <div class="button-group">
        <a href="login.php">Login</a>
        <a href="opt-in.php">SMS Opt-In</a>
        <a href="-and_conditions.php" class="secondary">Terms</a>
        <a href="privacy-policy.php" class="secondary">Privacy</a>
    </div>
</div>
</body>
</html>