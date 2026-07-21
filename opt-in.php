<link rel="icon" type="image/jpeg" href="logo.jpg">
<?php // sms-opt-in.php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Radius – SMS Opt-In</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #0d0d0d;
            color: #e8e4dc;
            min-height: 100vh;
        }

        .hero {
            background: linear-gradient(135deg, #1a0a00 0%, #2e1200 50%, #0d0d0d 100%);
            padding: 80px 24px 60px;
            text-align: center;
            border-bottom: 1px solid #ff8c4222;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -80px; left: 50%;
            transform: translateX(-50%);
            width: 500px; height: 300px;
            background: radial-gradient(ellipse, #ff8c4218 0%, transparent 70%);
            pointer-events: none;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #ff8c42;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 36px;
            opacity: 0.85;
            transition: opacity 0.2s;
        }
        .back-btn:hover { opacity: 1; }
        .back-btn::before { content: '←'; font-size: 16px; }

        .hero-tag {
            display: inline-block;
            background: #ff8c4218;
            border: 1px solid #ff8c4244;
            color: #ff8c42;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            padding: 6px 16px;
            border-radius: 100px;
            margin-bottom: 20px;
        }

        .hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.4rem, 6vw, 4rem);
            color: #fff;
            line-height: 1.1;
            margin-bottom: 16px;
        }

        .hero h1 span { color: #ff8c42; }

        .hero p {
            font-size: 15px;
            color: #888;
            max-width: 480px;
            margin: 0 auto;
            line-height: 1.7;
            font-weight: 300;
        }

        .content {
            max-width: 740px;
            margin: 0 auto;
            padding: 60px 24px 100px;
        }

        .card {
            background: #161616;
            border: 1px solid #2a2a2a;
            border-radius: 14px;
            padding: 32px 36px;
            margin-bottom: 20px;
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 16px;
        }

        .icon-circle {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #ff8c4218;
            border: 1px solid #ff8c4233;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .card-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.25rem;
            color: #fff;
            font-weight: 700;
        }

        p {
            font-size: 15px;
            line-height: 1.8;
            color: #b0aa9e;
            font-weight: 300;
        }

        .highlight-box {
            background: #ff8c4210;
            border: 1px solid #ff8c4228;
            border-left: 3px solid #ff8c42;
            border-radius: 8px;
            padding: 18px 22px;
            margin-top: 24px;
        }
        .highlight-box p {
            color: #d4cec6;
            font-size: 14px;
        }

        .opt-out-row {
            display: flex;
            gap: 16px;
            margin-top: 16px;
        }

        .pill {
            background: #1e1e1e;
            border: 1px solid #2e2e2e;
            border-radius: 100px;
            padding: 8px 18px;
            font-size: 13px;
            font-weight: 500;
            color: #d4cec6;
            letter-spacing: 0.06em;
        }

        .pill strong { color: #ff8c42; }

        .divider {
            border: none;
            border-top: 1px solid #1e1e1e;
            margin: 40px 0;
        }

        .footer-note {
            text-align: center;
            font-size: 13px;
            color: #555;
            line-height: 1.8;
        }

        .footer-note a {
            color: #ff8c42;
            text-decoration: none;
        }
        .footer-note a:hover { text-decoration: underline; }

        footer {
            text-align: center;
            padding: 32px;
            border-top: 1px solid #1a1a1a;
            font-size: 12px;
            color: #444;
            letter-spacing: 0.06em;
        }
    </style>
</head>
<body>

<div class="hero">
    <a href="index.php" class="back-btn">Back to Radius</a>
    <div class="hero-tag">SMS Consent</div>
    <h1>Emergency <span>Alerts</span></h1>
    <p>When someone adds you as an emergency contact in Radius, here's what that means for you.</p>
</div>

<div class="content">

    <div class="card">
        <div class="card-header">
            <div class="icon-circle">🚨</div>
            <div class="card-title">What You'll Receive</div>
        </div>
        <p>If a Radius user lists your phone number as an emergency contact, you may receive SMS alerts when they trigger an emergency. Messages include their name, a live location link, and an optional note.</p>
        <div class="highlight-box">
            <p>Message frequency depends on the user's activity. Message and data rates may apply.</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="icon-circle">✅</div>
            <div class="card-title">Your Consent</div>
        </div>
        <p>By being added as an emergency contact in Radius, you consent to receive these emergency SMS messages. You are never charged by Radius — standard carrier rates may apply.</p>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="icon-circle">🛑</div>
            <div class="card-title">How to Opt Out</div>
        </div>
        <p>You can stop receiving messages at any time by replying to any alert.</p>
        <div class="opt-out-row">
            <div class="pill">Reply <strong>STOP</strong> to unsubscribe</div>
            <div class="pill">Reply <strong>HELP</strong> for support</div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="icon-circle">🔒</div>
            <div class="card-title">Your Privacy</div>
        </div>
        <p>Your phone number is only used to deliver emergency alerts. We never sell, share, or use it for marketing purposes.</p>
    </div>

    <hr class="divider">

    <p class="footer-note">
        For full details on how we handle your data, read our
        <a href="privacy-policy.php">Privacy Policy</a>.<br>
        Questions? Contact us at <a href="mailto:support@radius-create.com">support@radius-create.com</a>
    </p>

</div>

<footer>
    &copy; <?php echo date('Y'); ?> Radius &nbsp;·&nbsp; All rights reserved
</footer>

</body>
</html>