<link rel="icon" type="image/jpeg" href="/image/radius-image.jpg">
<?php
session_start();

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {

        $data = [
            "email" => $email,
            "password" => $password
        ];

        $ch = curl_init("https://radius-backend-0qv8.onrender.com/user/login");
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

            $_SESSION['userId'] = $json['userId'];
            $_SESSION['name'] = $json['name'];

            header("Location: dashboard.php?id=" . $json['userId']);
            exit;
        } else {
            $error = "Invalid email or password";
        }

    } else {
        $error = "Please enter email and password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login • Radius</title>

<style>
    body {
        margin: 0;
        font-family: 'Inter', Arial, sans-serif;
        background: radial-gradient(circle at top, #1a1a1a, #0d0d0d 70%);
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
        overflow: hidden;
    }

    /* Floating blurred circles */
    .bg-circle {
        position: absolute;
        width: 350px;
        height: 350px;
        border-radius: 50%;
        filter: blur(120px);
        opacity: 0.35;
        animation: float 12s infinite ease-in-out;
    }
    .c1 { background: #ff6b00; top: -80px; left: -80px; }
    .c2 { background: #ff3c00; bottom: -80px; right: -80px; animation-delay: 3s; }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(40px); }
    }

    .card {
        width: 380px;
        padding: 40px 32px;
        border-radius: 22px;
        background: rgba(255,255,255,0.08);
        backdrop-filter: blur(18px);
        box-shadow: 0 10px 40px rgba(0,0,0,0.55);
        animation: fadeIn 0.7s ease;
        position: relative;
        z-index: 10;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .logo {
        text-align: center;
        font-size: 34px;
        font-weight: 800;
        margin-bottom: 6px;
        letter-spacing: 1.5px;
        color: #ff6b00;
        text-shadow: 0 0 12px rgba(255,107,0,0.4);
    }

    h2 {
        text-align: center;
        margin-bottom: 28px;
        font-weight: 500;
        color: #eaeaea;
        font-size: 18px;
    }

    input {
        width: 100%;
        padding: 14px;
        margin: 12px 0;
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.15);
        background: rgba(255,255,255,0.12);
        color: white;
        font-size: 15px;
        transition: 0.2s;
    }

    input:focus {
        outline: none;
        border-color: #ff6b00;
        background: rgba(255,255,255,0.18);
    }

    input::placeholder {
        color: #cfcfcf;
    }

    button {
        width: 100%;
        padding: 14px;
        margin-top: 18px;
        border: none;
        border-radius: 12px;
        background: linear-gradient(135deg, #ff6b00, #ff8533);
        color: white;
        font-size: 17px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s;
    }

    button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(255,107,0,0.35);
    }

    .error {
        color: #ff4d4d;
        text-align: center;
        margin-bottom: 10px;
        font-size: 14px;
    }
</style>
</head>

<body>

<div class="bg-circle c1"></div>
<div class="bg-circle c2"></div>

<div class="card">
    <div class="logo">RADIUS</div>
    <h2>Sign in to continue</h2>

    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
        <input type="text" name="email" placeholder="Email">
        <input type="password" name="password" placeholder="Password">
        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>

