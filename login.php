<?php
session_start();

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$data = [
    "email" => $email,
    "password" => $password
];

$ch = curl_init("https://api.radius-create.com/user/login");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    $json = json_decode($response, true);

    // Save session data
    $_SESSION['userId'] = $json['userId'];
    $_SESSION['name'] = $json['name'];

    header("Location: dashboard.php");
    exit;
} else {
    echo "Invalid email or password";
}
?>
