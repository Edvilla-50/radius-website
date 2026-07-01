<link rel="icon" type="image/jpeg" href="/image/radius-image.jpg">
<?php // privacy-policy.php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Radius – Privacy Policy</title>
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

        .hero-meta {
            font-size: 13px;
            color: #888;
            letter-spacing: 0.04em;
        }

        .content {
            max-width: 740px;
            margin: 0 auto;
            padding: 60px 24px 100px;
        }

        .section {
            margin-bottom: 48px;
            padding-bottom: 48px;
            border-bottom: 1px solid #1e1e1e;
        }
        .section:last-child { border-bottom: none; }

        .section-number {
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #ff8c42;
            margin-bottom: 10px;
        }

        h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            color: #fff;
            font-weight: 700;
            margin-bottom: 18px;
            line-height: 1.25;
        }

        h3 {
            font-size: 0.85rem;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #ff8c42;
            margin: 24px 0 10px;
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
            margin-top: 20px;
        }
        .highlight-box p {
            color: #d4cec6;
            font-size: 14px;
        }

        .contact-card {
            background: #161616;
            border: 1px solid #2a2a2a;
            border-radius: 12px;
            padding: 28px 32px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .contact-row {
            display: flex;
            gap: 16px;
            font-size: 14px;
        }
        .contact-label {
            color: #555;
            min-width: 80px;
            font-weight: 500;
        }
        .contact-value { color: #d4cec6; }
        .contact-value a { color: #ff8c42; text-decoration: none; }
        .contact-value a:hover { text-decoration: underline; }

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
    <div class="hero-tag">Legal</div>
    <h1>Privacy <span>Policy</span></h1>
    <p class="hero-meta">Last updated &nbsp;·&nbsp; May 2026</p>
</div>

<div class="content">

    <div class="section">
        <div class="section-number">Overview</div>
        <h2>Your data, your trust</h2>
        <p>Radius is developed by Eddie. This Privacy Policy explains how Radius collects, uses, and protects your information when you use the app.</p>
    </div>

    <div class="section">
        <div class="section-number">01</div>
        <h2>Information We Collect</h2>

        <h3>1.1 Location Information</h3>
        <p>Radius collects your real-time location only when you grant permission and when using features that require it. Your location is not permanently stored or shared unless you choose to share it.</p>

        <h3>1.2 Account Information</h3>
        <p>We may collect your email, username, profile photo, and any profile details you choose to provide.</p>

        <h3>1.3 Device Information</h3>
        <p>We may collect non-personal device data such as device model, OS version, app version, and crash logs.</p>

        <h3>1.4 Usage Data</h3>
        <p>We may collect anonymous analytics to improve app performance and features.</p>
    </div>

    <div class="section">
        <div class="section-number">02</div>
        <h2>How We Use Your Information</h2>
        <p>We use your information to provide app features, improve performance, personalize your experience, maintain security, and communicate updates.</p>
        <div class="highlight-box">
            <p>We do not sell your data — ever.</p>
        </div>
    </div>

    <div class="section">
        <div class="section-number">03</div>
        <h2>Sharing Your Information</h2>
        <p>We may share limited information only with your consent, with service providers, or if required by law. We do not share your personal information with advertisers.</p>
    </div>

    <div class="section">
        <div class="section-number">04</div>
        <h2>Data Security</h2>
        <p>We use reasonable technical measures to protect your data. No method of transmission over the internet is 100% secure, but we take this seriously.</p>
    </div>

    <div class="section">
        <div class="section-number">05</div>
        <h2>Your Choices</h2>
        <p>You can disable location permissions at any time, delete your account, or request data deletion by contacting us directly.</p>
    </div>

    <div class="section">
        <div class="section-number">06</div>
        <h2>Children's Privacy</h2>
        <p>Radius is not intended for users under 18.</p>
    </div>

    <div class="section">
        <div class="section-number">07</div>
        <h2>Changes to This Policy</h2>
        <p>We may update this Privacy Policy from time to time. Significant changes will be communicated within the app.</p>
    </div>

    <div class="section">
        <div class="section-number">08</div>
        <h2>Contact Us</h2>
        <p style="margin-bottom: 20px;">Have questions? Reach out directly.</p>
        <div class="contact-card">
            <div class="contact-row">
                <span class="contact-label">Developer</span>
                <span class="contact-value">Eddie</span>
            </div>
            <div class="contact-row">
                <span class="contact-label">Email</span>
                <span class="contact-value"><a href="mailto:support@radius-create.com">support@radius-create.com</a></span>
            </div>
            <div class="contact-row">
                <span class="contact-label">App</span>
                <span class="contact-value">Radius</span>
            </div>
        </div>
    </div>

</div>

<footer>
    &copy; <?php echo date('Y'); ?> Radius &nbsp;·&nbsp; All rights reserved
</footer>

</body>
</html>