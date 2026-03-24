<?php
if (!isset($page)) $page = 'HOME';
$is_logged = isset($_SESSION['user_id']);
$is_admin  = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$username  = $_SESSION['username'] ?? '';
?>
<nav class="navbar">
    <a href="HOME.php" class="nav-brand">
        <img src="Assets/image/logo.png" alt="Mojo Muscle Logo" class="nav-logo">
    </a>
    <ul class="nav-links">
        <li><a href="HOME.php"    class="<?php echo $page==='HOME'    ? 'active':'' ?>">HOME</a></li>
        <li><a href="about.php"   class="<?php echo $page==='ABOUT'   ? 'active':'' ?>">ABOUT</a></li>
        <li><a href="contact.php" class="<?php echo $page==='CONTACT' ? 'active':'' ?>">CONTACT</a></li>
        <?php if ($is_logged): ?>
        <li class="nav-dropdown">
            <button class="nav-user-btn" id="nav-user-btn">
                <span class="nav-username"><?php echo htmlspecialchars($username); ?></span>
                <span class="nav-chevron">▾</span>
            </button>
            <div class="nav-dropdown-menu" id="nav-dropdown-menu">
                <?php if (!$is_admin): ?>
                <a href="dashboard.php" class="dropdown-item">My Dashboard</a>
                <?php endif; ?>
                <a href="profile.php" class="dropdown-item">Account Settings</a>
                <?php if ($is_admin): ?>
                <div class="dropdown-divider"></div>
                <a href="HOME.php" class="dropdown-item admin-item">Admin Panel</a>
                <?php endif; ?>
                <div class="dropdown-divider"></div>
                <a href="handlers/logout.php" class="dropdown-item logout-item">Log Out</a>
            </div>
        </li>
        <?php else: ?>
        <li><a href="#" id="open-signin-btn">SIGN IN</a></li>
        <?php endif; ?>
    </ul>
</nav>
<script>
(function() {
    var btn  = document.getElementById('nav-user-btn');
    var menu = document.getElementById('nav-dropdown-menu');
    if (!btn) return;
    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        btn.classList.toggle('open');
        menu.classList.toggle('open');
    });
    document.addEventListener('click', function() {
        btn.classList.remove('open');
        menu.classList.remove('open');
    });
})();
</script>
