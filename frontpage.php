<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mojo's | Welcome</title>
    <link rel="icon" type="image/x-icon" href="Assets/image/icon.ico">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/bg.css">
    <style>
        .front-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
            padding: 40px 20px;
            gap: 0;
        }
        .front-eyebrow {
            font-family: 'Courier New', monospace;
            font-size: 0.62rem;
            letter-spacing: 6px;
            color: #4a148c;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        .front-logo {
            width: 380px;
            max-width: 82vw;
            display: block;
            margin: 0 auto 28px;
            filter: drop-shadow(0 0 40px rgba(151,95,255,0.45));
            animation: logoFadeIn 0.8s ease both;
        }
        @keyframes logoFadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .front-tagline {
            font-family: 'Courier New', monospace;
            font-size: 0.82rem;
            color: #555;
            max-width: 340px;
            line-height: 1.75;
            margin: 0 auto 34px;
            letter-spacing: 0.3px;
        }
        .front-divider {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 34px;
            opacity: 0.4;
        }
        .front-divider span {
            width: 48px; height: 1px;
            background: linear-gradient(90deg, transparent, #4a148c);
        }
        .front-divider span:last-child {
            background: linear-gradient(90deg, #4a148c, transparent);
        }
        .front-divider i {
            font-family: 'Courier New', monospace;
            font-size: 0.55rem;
            letter-spacing: 3px;
            color: #4a148c;
            font-style: normal;
        }
        .front-cta {
            display: inline-block;
            padding: 13px 38px;
            background: transparent;
            border: 1px solid #9757ff;
            border-radius: 40px;
            color: #cfb2ff;
            font-family: 'Courier New', monospace;
            font-weight: bold;
            font-size: 0.82rem;
            letter-spacing: 3px;
            text-decoration: none;
            transition: all 0.3s;
            cursor: pointer;
        }
        .front-cta:hover {
            background: #9757ff;
            color: #fff;
            box-shadow: 0 0 24px rgba(151,95,255,0.45);
            transform: translateY(-2px);
        }
        .front-stats {
            display: flex;
            gap: 36px;
            margin-top: 52px;
            padding-top: 28px;
            border-top: 1px solid rgba(74,20,140,0.3);
        }
        .front-stat strong {
            display: block;
            font-family: 'Courier New', monospace;
            font-size: 1.3rem;
            font-weight: 900;
            color: #92ff77;
        }
        .front-stat span {
            font-family: 'Courier New', monospace;
            font-size: 0.58rem;
            color: #444;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <?php include 'bg.php'; ?>

    <div class="loader-wrapper" id="loader-overlay">
        <div class="loader">
            <div class="circle"></div>
            <div class="circle"></div>
            <div class="circle"></div>
            <div class="circle"></div>
        </div>
    </div>

    <div class="front-wrap">
        <p class="front-eyebrow">Est. 2026 &nbsp;—&nbsp; Townsville, PH</p>
        <img src="Assets/image/logo.png" alt="Mojo Muscle Logo" class="front-logo">
        <p class="front-tagline">Where ordinary minions become extraordinary machines. Train hard. Train smart.</p>
        <div class="front-divider">
            <span></span>
            <i>ENTER THE LAIR</i>
            <span></span>
        </div>
        <a href="HOME.php" id="start-btn" class="front-cta">GET STARTED</a>
        <div class="front-stats">
            <div class="front-stat"><strong>500+</strong><span>Members</span></div>
            <div class="front-stat"><strong>15+</strong><span>Trainers</span></div>
            <div class="front-stat"><strong>6</strong><span>Classes</span></div>
            <div class="front-stat"><strong>24/7</strong><span>VIP Access</span></div>
        </div>
    </div>

    <script src="assets/js/loading.js"></script>
</body>
</html>
