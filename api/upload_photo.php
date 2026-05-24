<?php
/**
 * api/upload_photo.php
 * Accepts a multipart image upload, saves it, returns the public URL as JSON.
 * Called by the "Upload Image" button in dashboard.php / edit.php.
 */
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

// ── validate file was actually sent ──
if (empty($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['error' => 'No file received or upload error']);
    exit;
}

$file = $_FILES['photo'];

// ── size limit: 5 MB ──
if ($file['size'] > 5 * 1024 * 1024) {
    http_response_code(400);
    echo json_encode(['error' => 'File too large (max 5 MB)']);
    exit;
}

// ── verify it's actually an image (check bytes, not just extension) ──
$allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
$finfo   = new finfo(FILEINFO_MIME_TYPE);
$mime    = $finfo->file($file['tmp_name']);

if (!in_array($mime, $allowed, true)) {
    http_response_code(400);
    echo json_encode(['error' => 'Only JPG, PNG, WEBP, GIF allowed']);
    exit;
}

$extMap = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
$ext    = $extMap[$mime];

// ── build save path ──
// files land at: /uploads/photos/{userId}/{random}.{ext}
// publicly accessible at: /uploads/photos/{userId}/{random}.{ext}
$uploadDir = '/var/www/html/uploads/photos/' . $userId;
$uploadDir = '/var/www/html/uploads/photos/' . $userId;
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0777, true)) {
        echo json_encode(['error' => 'mkdir failed, is_writable: ' . (is_writable('/var/www/html/uploads/photos') ? 'yes' : 'no')]);
        exit;
    }
}
$filename = bin2hex(random_bytes(8)) . '.' . $ext; // e.g. a3f2b1c0d4e5f6a7.jpg
$savePath = $uploadDir . '/' . $filename;

if (!move_uploaded_file($file['tmp_name'], $savePath)) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save file']);
    exit;
}

$publicUrl = 'https://www.radius-create.com/uploads/photos/' . $userId . '/' . $filename;

header('Content-Type: application/json');
echo json_encode(['url' => $publicUrl]);