<?php
$userId = $_GET['id'] ?? null;
if (!$userId) {
    http_response_code(400);
    echo "Missing id";
    exit;
}

$backend = "https://radius-backend-0qv8.onrender.com";
$response = @file_get_contents("$backend/user/$userId/profile-html");

if ($response === false) {
    http_response_code(404);
    echo "<html><body style='font-family:Arial;padding:20px;'><h2>Profile not found</h2></body></html>";
    exit;
}

$data = json_decode($response, true);
header('Content-Type: text/html');
echo $data['html'] ?? '';