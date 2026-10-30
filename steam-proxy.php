<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { http_response_code(400); echo "[]"; exit; }

$backend = "https://radius-backend-0qv8.onrender.com";

$ch = curl_init("$backend/users/$id/steam");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$response = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($code === 200) { echo $response; }
else { http_response_code($code === 404 ? 404 : 502); echo "[]"; }