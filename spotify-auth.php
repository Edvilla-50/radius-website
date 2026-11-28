<?php

session_start();

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit;
}

// The Client ID is public, so it is fine here. The Client Secret is NOT needed
// in PHP at all: the Spring Boot backend does the token exchange.
$clientId = "YOUR_SPOTIFY_CLIENT_ID";

// Must be IDENTICAL to the redirect URI in the Spotify dashboard and to
// SPOTIFY_REDIRECT_URI on Render, and must be on the same host (www or not)
// that you use to open the profile builder, or the login session is lost.
$redirectUri = "https://radius-create.com/spotify-callback.php";

$state = bin2hex(random_bytes(16));
$_SESSION['spotify_state'] = $state;

$params = http_build_query([
    'client_id'     => $clientId,
    'response_type' => 'code',
    'redirect_uri'  => $redirectUri,
    'scope'         => 'user-read-recently-played',
    'state'         => $state,
]);

header("Location: https://accounts.spotify.com/authorize?" . $params);
exit;