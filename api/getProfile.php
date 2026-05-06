<?php
$userId = $_GET['id'] ?? null;
if(!$userId){
    http_response_code(400);
    echo "Missing id";
    exit;
}

$backend = "https://radius-backend-0qv8.onrender.com";


$response = file_get_contents("$backend/user/$userId/profile-html");
http_response_code(200);
echo $response;
