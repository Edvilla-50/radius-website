<?php

session_start();

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit;
}

$userId  = (int)$_SESSION['userId'];
$backend = "https://radius-backend-0qv8.onrender.com";

function finish($ok, $message) {
    $color = $ok ? "#4ade80" : "#f87171";
    $msg   = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

    echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Spotify</title></head>"
       . "<body style='font-family:sans-serif;background:#111827;color:#e5e7eb;"
       . "display:flex;align-items:center;justify-content:center;height:100vh;margin:0;text-align:center'>"
       . "<div><h2 style='color:$color'>$msg</h2><p>You can close this window.</p></div>"
       . ($ok ? "<script>setTimeout(function(){ window.close(); }, 1200);</script>" : "")
       . "</body></html>";
    exit;
}

$expected = $_SESSION['spotify_state'] ?? '';
unset($_SESSION['spotify_state']);

if (isset($_GET['error'])) {
    finish(false, "Spotify access was not granted.");
}

$state = $_GET['state'] ?? '';
$code  = $_GET['code'] ?? '';

if ($expected === '' || !hash_equals($expected, $state) || $code === '') {
    finish(false, "Invalid response from Spotify. Please try again.");
}

// The backend may be waking up on Render, so allow a long timeout.
$ch = curl_init("$backend/api/spotify/connect/$userId");

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS     => json_encode(['code' => $code]),
    CURLOPT_TIMEOUT        => 60,
]);

$response = curl_exec($ch);
$http     = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

if ($http !== 200) {
    finish(false, "Could not connect Spotify (error $http).");
}

finish(true, "Spotify connected!");