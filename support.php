<link rel="icon" type="image/jpeg" href="/image/radius-image.jpg">
<?php // support.php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Radius – Support</title>
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

        p a { color: #ff8c42; text-decoration: none; }
        p a:hover { text-decoration: underline; }

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

        .cta-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 24px;
        }
        .cta-btn {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            background: #ff8c42;
            color: #0d0d0d;
            transition: 0.2s;
        }
        .cta-btn:hover { background: #ffa866; transform: translateY(-1px); }
        .cta-btn.secondary {
            background: transparent;
            color: #e8e4dc;
            border: 1px solid #2a2a2a;
        }
        .cta-btn.secondary:hover { border-color: #ff8c4244; color: #fff; }

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
    <div class="hero-tag">Help Center</div>
    <h1>How can we <span>help?</span></h1>
    <p class="hero-meta">Support &nbsp;·&nbsp; Updated June 2026</p>
</div>

<div class="content">

    <div class="section">
        <div class="section-number">Contact</div>
        <h2>Get in touch</h2>
        <p style="margin-bottom: 20px;">Have a question, ran into a bug, or need help with your account? Send us an email and we'll get back to you as soon as we can.</p>
        <div class="contact-card">
            <div class="contact-row">
                <span class="contact-label">Email</span>
                <span class="contact-value"><a href="mailto:support@radius-create.com">support@radius-create.com</a></span>
            </div>
            <div class="contact-row">
                <span class="contact-label">App</span>
                <span class="contact-value">Radius</span>
            </div>
        </div>
        <div class="cta-row">
            <a href="mailto:support@radius-create.com" class="cta-btn">Email Support</a>
            <a href="index.php" class="cta-btn secondary">Back to Radius</a>
        </div>
    </div>

    <div class="section">
        <div class="section-number">01</div>
        <h2>How do I report someone?</h2>
        <p>Open the profile of the person you'd like to report and tap the flag icon in the top right corner. Choose a reason and add any details that might help — our team reviews every report that comes through.</p>
    </div>

    <div class="section">
        <div class="section-number">02</div>
        <h2>Is my location shared with anyone?</h2>
        <p>Your live location is only shared with a matched user during an active, mutually-confirmed meetup — so you can find each other safely. It's never visible to unmatched users or shown publicly on your profile.</p>
        <div class="highlight-box">
            <p>Use the in-app SOS button anytime you feel unsafe during a meetup. It immediately shares your location and cancels the session.</p>
        </div>
    </div>

    <div class="section">
        <div class="section-number">03</div>
        <h2>How do I delete my account?</h2>
        <p>Email <a href="mailto:support@radius-create.com">support@radius-create.com</a> from the address associated with your account and we'll process your deletion request promptly.</p>
    </div>

    <div class="section">
        <div class="section-number">04</div>
        <h2>I'm running into a technical issue</h2>
        <p>Let us know what you were doing when it happened, what device you're using, and any error messages you saw. The more detail you can share, the faster we can track it down and fix it.</p>
    </div>

    <div class="section">
        <div class="section-number">05</div>
        <h2>Legal & Policies</h2>
        <p>For details on how Radius works, your data, and your responsibilities as a user, see our <a href="terms-and-conditions.php">Terms of Service</a> and <a href="privacy-policy.php">Privacy Policy</a>.</p>
    </div>

</div>

<footer>
    &copy; <?php echo date('Y'); ?> Radius &nbsp;·&nbsp; All rights reserved
</footer>

</body>
</html>