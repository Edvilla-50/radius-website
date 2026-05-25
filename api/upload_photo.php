<?php
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['userId'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}
$userId = (int)$_SESSION['userId'];
if (empty($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['error' => 'No file received or upload error']);
    exit;
}
$file = $_FILES['photo'];
if ($file['size'] > 5 * 1024 * 1024) {
    http_response_code(400);
    echo json_encode(['error' => 'File too large (max 5 MB)']);
    exit;
}
$allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
$finfo   = new finfo(FILEINFO_MIME_TYPE);
$mime    = $finfo->file($file['tmp_name']);
if (!in_array($mime, $allowed, true)) {
    http_response_code(400);
    echo json_encode(['error' => 'Only JPG, PNG, WEBP, GIF allowed']);
    exit;
}
$cloudName = getenv('CLOUDNAME');
$apiKey    = getenv('APIKEY');
$apiSecret = getenv('APISECRET');
$timestamp = time();
$folder    = "radius/users/$userId";
$signature = sha1("folder=$folder&timestamp=$timestamp" . $apiSecret);
$ch = curl_init("https://api.cloudinary.com/v1_1/$cloudName/image/upload");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    'file'      => new CURLFile($file['tmp_name'], $mime, $file['name']),
    'api_key'   => $apiKey,
    'timestamp' => $timestamp,
    'folder'    => $folder,
    'signature' => $signature,
]);
$raw      = curl_exec($ch);
$response = json_decode($raw, true);
curl_close($ch);
if (!isset($response['secure_url'])) {
    http_response_code(500);
    echo json_encode([
        'error'               => 'Cloudinary upload failed',
        'cloudinary_response' => $response,
        'raw'                 => $raw,
        'cloud_name'          => $cloudName,
        'api_key'             => $apiKey,
        'secret_set'          => !empty($apiSecret),
        'signature'           => $signature,
    ]);
    exit;
}
echo json_encode(['url' => $response['secure_url']]);