<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mojo's | About Us</title>
    <link rel="icon" type="image/x-icon" href="Assets/image/icon.ico">
    <link rel="stylesheet" href="assets/css/bg.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/inup.css">
    <link rel="stylesheet" href="assets/css/home.css">
    <link rel="stylesheet" href="assets/css/about.css">
</head>
<body>
    <?php include 'bg.php'; ?>
    <?php $page = "ABOUT"; include 'navbar.php'; ?>

    <div class="about-hero">
        <p class="eyebrow">EST. 2026</p>
        <h1>THE MOJO<br><span>MUSCLE</span></h1>
        <p class="sub">Where ordinary minions become extraordinary machines.</p>
        <div class="divider"></div>
    </div>

    <section class="section">
        <div class="two-col">
            <div>
                <p class="label">01 — WHO WE ARE</p>
                <h2>Built for <em>Domination</em></h2>
                <p>The Mojo Muscle isn't just a gym — it's a fortress of iron and ambition. 
                Inspired by Mojo Jojo himself, we built a space where every minion can unlock their hidden power.</p>
                <p>Modern equipment, expert coaching, and a community that pushes you beyond your limits. 
                Whether you're just starting or already forged in steel — this is your domain.</p>
                <div class="stats-row">
                    <div class="stat"><strong>500+</strong><span>Minions Trained</span></div>
                    <div class="stat"><strong>15+</strong><span>Expert Trainers</span></div>
                    <div class="stat"><strong>24/7</strong><span>VIP Access</span></div>
                </div>
            </div>
            <div class="quote-box">
                <div class="quote-inner">
                    <span class="quote-mark">&ldquo;</span>
                    <blockquote>"Growing old is mandatory, growing up is optional"</blockquote>
                    <cite>— Jake Cris Bataluna</cite>
                </div>
            </div>
        </div>
    </section>

    <section class="section dark-strip">
        <p class="label centered">02 — WHAT WE OFFER</p>
        <h2 class="centered">Our <em>Arsenal</em></h2>
        <div class="features-grid">
            <div class="feature-card">
                <span class="feature-num">01</span>
                <h3>World-Class Equipment</h3>
                <p>State-of-the-art machines, free weights, and functional training zones.</p>
            </div>
            <div class="feature-card">
                <span class="feature-num">02</span>
                <h3>Personal Training</h3>
                <p>Certified trainers craft programs to maximize your gains and crush your weaknesses.</p>
            </div>
            <div class="feature-card">
                <span class="feature-num">03</span>
                <h3>Group Classes</h3>
                <p>From HIIT to Yoga — join scheduled classes led by elite instructors.</p>
            </div>
            <div class="feature-card">
                <span class="feature-num">04</span>
                <h3>VIP Lounge</h3>
                <p>Exclusive lounge for VIP members. Recover and refuel in premium comfort.</p>
            </div>
            <div class="feature-card">
                <span class="feature-num">05</span>
                <h3>Secure Lockers</h3>
                <p>State-of-the-art locker rooms with biometric security. Your gear is safe.</p>
            </div>
            <div class="feature-card">
                <span class="feature-num">06</span>
                <h3>Nutrition Guidance</h3>
                <p>Nutrition advisors help you fuel your body for peak performance.</p>
            </div>
        </div>
    </section>

    <section class="section">
        <p class="label centered">03 — THE GENERALS</p>
        <h2 class="centered">Meet The <em class="purple">Team</em></h2>

        <img src="assets/image/GROUP.png" alt="Group Photo" class="group-photo">

        <div class="team-grid">
            <div class="team-card">
                <img src="Assets/image/Jake.jpg" alt="Coach Jake">
                <h3>Coach Jake</h3>
                <span>Front-end & Documentation</span>
                <p>Karaang Tao</p>
            </div>
            <div class="team-card">
                <img src="Assets/image/Lester.jpg" alt="Coach Lester">
                <h3>Coach Lester</h3>
                <span>Database & Front-end</span>
                <p>SYBAU</p>
            </div>
            <div class="team-card">
                <img src="Assets/image/Jelyn.jpg" alt="Coach Jelyn">
                <h3>Coach Jelyn</h3>
                <span>Front-end & Testing</span>
                <p>?????</p>
            </div>
            <div class="team-card">
                <img src="Assets/image/jet.jpg" alt="Coach Jet">
                <h3>Coach Jet</h3>
                <span>Back-end & Database</span>
                <p>?????</p>
            </div>
            <div class="team-card">
                <img src="Assets/image/Jonalyn.jpg" alt="Coach Jonalyn">
                <h3>Coach Jonalyn</h3>
                <span>Front-end & Back-end</span>
                <p>?????</p>
            </div>
            <div class="team-card">
                <img src="Assets/image/Mariane.jpg" alt="Coach Mariane">
                <h3>Coach Mariane</h3>
                <span>Front-end & Testing</span>
                <p>?????</p>
            </div>
        </div>
    </section>

    <section class="cta-strip">
        <h2>Ready to Join the Lair?</h2>
        <p>Stop watching. Start training. Your transformation starts today.</p>
        <div>
            <a href="plans.php" class="btn-primary">VIEW PLANS</a>
            <a href="contact.php" class="btn-outline">CONTACT US</a>
        </div>
    </section>


    <?php include 'Signin.php'; ?>
    <div id="mojo-toast" class="mojo-toast"></div>
    <script>
    const SITE_ROOT = "<?php echo 'http://'.$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['SCRIPT_NAME']),'/\\').'/'; ?>";
    </script>
    <script src="assets/js/signin.js"></script>
    <?php include 'footer.php'; ?>
</body>
</html>
