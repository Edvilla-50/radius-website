<?php

session_start();

header("Content-Type: application/json");

if (!isset($_SESSION['userId'])) {
    http_response_code(401);
    echo json_encode(["error" => "Not logged in"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
    exit;
}

$userId  = (int)$_SESSION['userId'];
$backend = "https://radius-backend-0qv8.onrender.com";

$ch = curl_init("$backend/api/spotify/disconnect/$userId");

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST  => "DELETE",
    CURLOPT_TIMEOUT        => 30,
]);

curl_exec($ch);
$http = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

if ($http === 200 || $http === 204) {
    http_response_code(204);
} else {
    http_response_code(502);
    echo json_encode(["error" => "Backend error"]);
}