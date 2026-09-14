<?php
// music-proxy.php
// Forwards to the real backend so the browser only ever sees this domain,
// not radius-backend-0qv8.onrender.com directly.

header('Content-Type: application/json');

$userId = $_GET['id'] ?? null;
if (!$userId || !ctype_digit($userId)) {
    http_response_code(400);
    echo json_encode(['error' => 'missing or invalid id']);
    exit;
}

$backend = "https://radius-backend-0qv8.onrender.com";
$ch = curl_init("$backend/api/music/$userId");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

http_response_code($httpCode);
echo $response;