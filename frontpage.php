<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FRONT PAGE</title>
    <link rel="icon" type="image/x-icon" href="Assets/image/icon.ico">
    <link rel="stylesheet" href="assets/css/style.css">
    
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
    
    <div class="card-container">
        <div class="main-card">
            <p class="card-title"> WELCOME TO</p>
            <img src="Assets/image/logo.png" alt="Mojo Jojo's Logo" class="logo">
            <!-- added inline css as well as small description bwahahahhahahaaha -->
            <p style="font-size: small; padding-bottom: 20px; color: #92ff77;">A friendly and motivating  place where everyone can work toward a healthier and stronger lifestyle!</p>

            <button class="Btn"><a href="HOME.php" id="start-btn">Get Started</a></button>
        </div>
    </div>
    
    <script src="assets/js/loading.js"></script>
    
</body>
</html>