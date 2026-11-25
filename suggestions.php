<?php
session_start();

// ---- DB connection ----
// Reads from the website service's Environment tab on Render:
// RADIUS_WEB_HOST, RADIUS_WEB_NAME, RADIUS_WEB_USER, RADIUS_WEB_PASS
function env(string $key): string {
    $v = getenv($key);
    if ($v === false || $v === '') {
        $v = $_ENV[$key] ?? $_SERVER[$key] ?? '';
    }
    return (string)$v;
}

function db(): PDO {
    $host = env('RADIUS_WEB_HOST');
    $name = env('RADIUS_WEB_NAME');
    $user = env('RADIUS_WEB_USER');
    $pass = env('RADIUS_WEB_PASS');

    if ($host === '' || $name === '' || $user === '' || $pass === '') {
        throw new RuntimeException('Database environment variables are not set');
    }

    return new PDO(
        "pgsql:host=$host;port=5432;dbname=$name",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
}

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

$message = '';
$ok = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $text = trim($_POST['suggestion'] ?? '');
    $text = preg_replace('/\s+/', ' ', $text);

    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
        $message = 'Session expired. Please reload and try again.';
    } elseif (!empty($_POST['website'])) {
        // honeypot filled in (bot): pretend success, save nothing
        $ok = true;
        $message = 'Thanks for your suggestion!';
    } elseif ($text === '' || mb_strlen($text) > 50) {
        $message = 'Please enter an interest between 1 and 50 characters.';
    } elseif (!preg_match('/^[\p{L}\p{N} &\'\-\.\+\/]+$/u', $text)) {
        $message = 'Please use letters, numbers, and basic punctuation only.';
    } else {
        try {
            $pdo = db();
            $ipHash = hash('sha256', $_SERVER['REMOTE_ADDR'] ?? 'unknown');

            // rate limit: 3 suggestions per IP per hour
            $stmt = $pdo->prepare(
                "SELECT COUNT(*) FROM interest_suggestions
                 WHERE ip_hash = ? AND created_at > NOW() - INTERVAL '1 hour'"
            );
            $stmt->execute([$ipHash]);

            if ($stmt->fetchColumn() >= 3) {
                $message = "You've sent a few already. Please try again later.";
            } else {
                $ins = $pdo->prepare(
                    "INSERT INTO interest_suggestions (text, ip_hash) VALUES (?, ?)"
                );
                $ins->execute([$text, $ipHash]);
                $ok = true;
                $message = "Thanks! We'll review your suggestion.";
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            $message = 'Something went wrong. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" type="image/png" href="image/appstore.png">
<title>Suggest an Interest | Radius</title>
<style>
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: url('https://images.unsplash.com/photo-1529333166437-7750a6dd5a70?auto=format&fit=crop&w=1400&q=80') center/cover no-repeat;
    min-height: 100vh;
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
    backdrop-filter: blur(5px);
    max-width: 90%;
    width: 420px;
}
h1 { font-size: 2.2rem; margin-bottom: 10px; }
p { font-size: 1.05rem; margin-bottom: 24px; }
input[type=text] {
    width: 100%;
    box-sizing: border-box;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid rgba(255,255,255,0.4);
    background: rgba(255,255,255,0.15);
    color: white;
    font-size: 1rem;
}
input[type=text]::placeholder { color: rgba(255,255,255,0.7); }
button, a.back {
    display: inline-block;
    margin-top: 14px;
    padding: 12px 24px;
    border-radius: 6px;
    border: none;
    font-weight: bold;
    font-size: 1rem;
    background: #ff8c42;
    color: black;
    cursor: pointer;
    text-decoration: none;
    text-shadow: none;
    transition: 0.2s;
}
button:hover, a.back:hover { background: #ffa866; }
a.back.secondary {
    background: rgba(255,255,255,0.15);
    color: white;
    border: 1px solid rgba(255,255,255,0.4);
}
.msg { margin-top: 16px; font-weight: bold; }
.msg.ok { color: #9be89b; }
.msg.err { color: #ffb3b3; }
.hp { position: absolute; left: -9999px; }
</style>
</head>
<body>
<div class="container">
    <h1>Suggest an Interest</h1>
    <p>Is there something you'd like to see on Radius? Tell us!</p>

    <form method="post" autocomplete="off">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
        <input class="hp" type="text" name="website" tabindex="-1" aria-hidden="true">
        <input type="text" name="suggestion" maxlength="50" placeholder="e.g. Rock climbing" required>
        <button type="submit">Submit</button>
    </form>

    <?php if ($message): ?>
        <div class="msg <?= $ok ? 'ok' : 'err' ?>"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <a class="back secondary" href="index.php">Back</a>
</div>
</body>
</html>