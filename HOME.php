<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mojo's | Home</title>
    <link rel="icon" type="image/x-icon" href="Assets/image/icon.ico">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/bg.css">
    <link rel="stylesheet" href="assets/music/music.css">    
</head>

<?php include 'bg.php'; ?>

    <?php 
    $page = "HOME";
    include 'navbar.php'; 
    ?>

    <?php include 'assets/music/music.php'; ?>
<body>
   

    <div class="card-container">

        <div class="main-card">
            <p class="card-title">Gym Availability</p>
            <menu class="card-menu">
                <li>Monday: 6 AM - 10 PM</li>
                <li>Tuesday: 6 AM - 10 PM</li>
                <li>Wednesday: 6 AM - 10 PM</li>
                <li>Thursday: 6 AM - 10 PM</li>
                <li>Friday: 6 AM - 10 PM</li>
                <li>Saturday: 8 AM - 8 PM</li>
                <li>Sunday: 10 AM - 6 PM</li>
            </menu>
        </div>

        <div class="main-card">
            <p class="card-title">Active Members</p>
            <p>120 Members</p>
        </div>

        <div class="main-card">
            <p class="card-title">Upcoming Events</p>
            <p>None scheduled</p>
        </div>

    </div>

    <?php include 'footer.php'; ?>

</body>
</html>