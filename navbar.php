<?php
if (!isset($page)) { $page = "HOME"; }
$is_logged = isset($_SESSION['user_id']);
$is_admin  = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$username  = $_SESSION['username'] ?? '';
?>

<nav class="navbar">
    <div class="nav-brand">
        <img src="Assets/image/logo.png" alt="Mojo Jojo's Logo" class="nav-logo">
        <header>THE MOJO MUSCLE</header>
    </div>

    <ul class="nav-links">
        <li><a href="HOME.php"    class="<?php echo ($page == 'HOME')    ? 'active' : ''; ?>">HOME</a></li>
        <li><a href="about.php"   class="<?php echo ($page == 'ABOUT')   ? 'active' : ''; ?>">ABOUT</a></li>
        <li><a href="contact.php" class="<?php echo ($page == 'CONTACT') ? 'active' : ''; ?>">CONTACT</a></li>

        <?php if ($is_logged): ?>
        <!-- LOGGED IN: show user dropdown -->
        <li class="nav-dropdown">
            <button class="nav-user-btn" id="nav-user-btn">
                <span class="nav-avatar">🧠</span>
                <span class="nav-username"><?php echo htmlspecialchars($username); ?></span>
                <span class="nav-chevron">▾</span>
            </button>
            <div class="nav-dropdown-menu" id="nav-dropdown-menu">
                <?php if (!$is_admin): ?>
                <a href="dashboard.php" class="dropdown-item">👤 My Dashboard</a>
                <?php endif; ?>
                <a href="profile.php" class="dropdown-item">⚙️ Account Settings</a>
                <?php if ($is_admin): ?>
                <div class="dropdown-divider"></div>
                <a href="HOME.php" class="dropdown-item admin-item">🧪 Admin — Home</a>
                <?php endif; ?>
                <div class="dropdown-divider"></div>
                <a href="handlers/logout.php" class="dropdown-item logout-item">🚪 Log Out</a>
            </div>
        </li>
        <?php else: ?>
        <!-- NOT LOGGED IN: show sign in -->
        <li><a href="#" id="open-signin-btn" class="<?php echo ($page == 'LOGIN') ? 'active' : ''; ?>">SIGN IN</a></li>
        <?php endif; ?>
    </ul>
</nav>

<style>
/* --- USER DROPDOWN --- */
.nav-dropdown { position: relative; display: flex; align-items: center; }

.nav-user-btn {
    display: flex; align-items: center; gap: 6px;
    background: rgba(151, 95, 255, 0.15);
    border: 1px solid #4a148c;
    border-radius: 30px;
    padding: 8px 16px;
    color: #92ff77;
    font-family: 'Segoe UI', sans-serif;
    font-weight: 600; font-size: 13px;
    cursor: pointer; transition: all 0.3s ease;
    white-space: nowrap;
}
.nav-user-btn:hover { background: rgba(151,95,255,0.3); border-color: #92ff77; }
.nav-avatar   { font-size: 1rem; }
.nav-username { max-width: 100px; overflow: hidden; text-overflow: ellipsis; }
.nav-chevron  { font-size: 0.7rem; transition: transform 0.3s; }
.nav-user-btn.open .nav-chevron { transform: rotate(180deg); }

.nav-dropdown-menu {
    display: none;
    position: absolute;
    top: calc(100% + 10px);
    right: 0;
    background: #2d2d2d;
    border: 2px solid #4a148c;
    border-radius: 8px;
    min-width: 190px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.5);
    z-index: 9999;
    overflow: hidden;
    animation: dropIn 0.2s ease;
}
.nav-dropdown-menu.open { display: block; }

@keyframes dropIn {
    from { opacity: 0; transform: translateY(-8px); }
    to   { opacity: 1; transform: translateY(0); }
}

.dropdown-item {
    display: block;
    padding: 11px 18px;
    color: #e0e0e0;
    text-decoration: none;
    font-size: 0.85rem;
    font-family: 'Courier New', monospace;
    transition: background 0.2s, color 0.2s;
}
.dropdown-item:hover    { background: rgba(151,95,255,0.15); color: #92ff77; }
.admin-item             { color: #cfb2ff; }
.logout-item            { color: #ff6b6b; }
.logout-item:hover      { background: rgba(255,77,77,0.1); color: #ff4d4d; }
.dropdown-divider       { height: 1px; background: #4a148c; margin: 4px 0; }
</style>

<script>
// Dropdown toggle
(function() {
    var btn  = document.getElementById('nav-user-btn');
    var menu = document.getElementById('nav-dropdown-menu');
    if (!btn || !menu) return;

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
