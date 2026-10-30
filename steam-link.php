<?php
session_start();

if (!isset($_SESSION['userId'])) { http_response_code(401); echo "Not logged in."; exit; }
$userId = (int)$_SESSION['userId'];   // never trust an id sent by the browser

$method = $_SERVER['REQUEST_METHOD'];
if ($method !== 'POST' && $method !== 'DELETE') { http_response_code(405); exit; }

$backend = "https://radius-backend-0qv8.onrender.com";

// 1) environment variable, 2) config file next to this one
$secret = getenv('RADIUS_WRITE_KEY');
$secretFile = __DIR__ . '/radius-secrets.php';
if (!$secret && file_exists($secretFile)) {
    $secret = require $secretFile;
}
$secret = trim((string)$secret);

if ($secret === '') {
    http_response_code(500);
    echo "Server key not configured.";
    exit;
}

$ch = curl_init("$backend/users/$userId/steam");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json", "X-Radius-Key: $secret"]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);

if ($method === 'POST') {
    $in = json_decode(file_get_contents('php://input'), true);
    $input = is_array($in) ? (string)($in['input'] ?? '') : '';
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['input' => $input]));
}

$response = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

http_response_code($code ?: 502);
echo $response === false ? "Backend unreachable." : $response;