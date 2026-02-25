<?php   
if(!isset($page)){ $page = "HOME";}
?>

<nav class="navbar">
    <div class="nav-brand">
        <img src="Assets/image/logo.png" alt="Mojo Jojo's Logo" class="nav-logo">
        <header class="">THE MOJO MUSCLE</header>
    </div>
        
    <ul class="nav-links">
    <li><a href="HOME.php" class="<?php echo ($page == 'HOME') ? 'active' : ''; ?>">HOME</a></li>
    <li><a href="about.php" class="<?php echo ($page == 'ABOUT') ? 'active' : ''; ?>">ABOUT</a></li>
    <li><a href="contact.php" class="<?php echo ($page == 'CONTACT') ? 'active' : ''; ?>">CONTACT</a></li>
    
    <li><a href="#" class="<?php echo ($page == 'LOGIN') ? 'active' : ''; ?>" id="open-signin-btn">SIGN IN</a></li>
</ul>
</nav>