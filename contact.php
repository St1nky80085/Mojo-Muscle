<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mojo's | Contact</title>
    <link rel="icon" type="image/x-icon" href="Assets/image/icon.ico">
    <link rel="stylesheet" href="assets/css/bg.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/home.css">
    <link rel="stylesheet" href="assets/css/contact.css">
</head>
<body>
    <?php include 'bg.php'; ?>
    <?php $page = "CONTACT"; include 'navbar.php'; ?>

    <div class="contact-hero">
        <p class="eyebrow">GET IN TOUCH</p>
        <h1>CONTACT <span>THE LAIR</span></h1>
        <p class="sub">Questions? Concerns? Want to challenge Mojo Jojo? We're listening.</p>
    </div>

    <div class="contact-wrap">

        <div class="info-col">
            <div class="info-card">
                
                <div>
                    <h4>Location</h4>
                    <p>123 Townsville St.,<br>Mojo's Fortress, PH</p>
                </div>
            </div>
            <div class="info-card">
                
                <div>
                    <h4>Phone</h4>
                    <p>+63 912 345 6789</p>
                </div>
            </div>
            <div class="info-card">
                
                <div>
                    <h4>Email</h4>
                    <p>mojo@themojomuscle.com</p>
                </div>
            </div>
            <div class="info-card">
                
                <div>
                    <h4>Support Hours</h4>
                    <p>Mon–Fri: 8 AM – 8 PM<br>Sat–Sun: 10 AM – 5 PM</p>
                </div>
            </div>
            <div class="social-box">
                <h4>Follow The Lair</h4>
                <div class="social-links">
                    <a href="#">Facebook</a>
                    <a href="#">Instagram</a>
                    <a href="#">Twitter</a>
                    <a href="#">YouTube</a>
                </div>
            </div>
        </div>

        <div class="form-card">
            <h3>Send a Message</h3>
            <p>We usually respond within 24 hours.</p>

            <form id="contact-form">
                <div class="row-2">
                    <div class="field">
                        <label>Your Name</label>
                        <input type="text" placeholder="MojoMinion01" required />
                    </div>
                    <div class="field">
                        <label>Email</label>
                        <input type="email" placeholder="minion@mojojojo.com" required />
                    </div>
                </div>
                <div class="field">
                    <label>Subject</label>
                    <select>
                        <option value="">Select a topic...</option>
                        <option>Membership Inquiry</option>
                        <option>Class Booking Help</option>
                        <option>Technical Issue</option>
                        <option>Personal Training</option>
                        <option>VIP Lounge Access</option>
                        <option>Other</option>
                    </select>
                </div>
                <div class="field">
                    <label>Message</label>
                    <textarea placeholder="Tell us what's on your mind..." required></textarea>
                </div>
                <button type="submit" id="send-btn">SEND MESSAGE</button>
            </form>

            <div id="success-msg">
                ✅ Message transmitted! We'll get back to you within 24 hours, Minion.
            </div>
        </div>

    </div>

    <?php include 'footer.php'; ?>
    <script>
    document.getElementById('contact-form').addEventListener('submit', function(e) {
        e.preventDefault();
        var btn = document.getElementById('send-btn');
        btn.textContent = 'TRANSMITTING...'; btn.disabled = true;
        setTimeout(function() {
            document.getElementById('contact-form').style.display = 'none';
            document.getElementById('success-msg').style.display  = 'block';
        }, 1200);
    });
    </script>
</body>
</html>
