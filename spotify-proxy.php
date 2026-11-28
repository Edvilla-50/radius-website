<?php

// Public, read-only: returns only track info (never tokens).
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$backend = "https://radius-backend-0qv8.onrender.com";

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(["error" => "Missing id"]);
    exit;
}

$ch = curl_init("$backend/api/spotify/$id");

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 30,
]);

$response = curl_exec($ch);
$http     = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

if ($http === 200) {
    echo $response;
} elseif ($http === 204) {
    http_response_code(204);   // not connected / nothing played yet
} else {
    http_response_code(502);
    echo json_encode(["error" => "Backend error"]);
}