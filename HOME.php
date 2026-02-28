<?php
session_start();
require_once __DIR__ . '/config/db.php';

$content = [];
$res = $conn->query("SELECT content_key, content_value FROM home_content");
while ($row = $res->fetch_assoc()) {
    $content[$row['content_key']] = $row['content_value'];
}

$is_admin  = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$is_logged = isset($_SESSION['user_id']);
$user_plan = $_SESSION['plan'] ?? 'Free';
$days      = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
$today     = strtolower(date('l'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mojo's | Home</title>
    <link rel="icon" type="image/x-icon" href="Assets/image/icon.ico">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/bg.css">
    <link rel="stylesheet" href="assets/css/inup.css">
    <link rel="stylesheet" href="assets/css/home.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="assets/css/membership.css">
</head>
<body>
<?php include 'bg.php'; ?>
<?php $page = "HOME"; include 'navbar.php'; ?>

<?php if (!empty($content['announcement']) && ($user_plan === 'Free' || !$is_logged)): ?>
<div class="announcement-banner" id="announcement-banner">
    <span>📢 <?php echo htmlspecialchars($content['announcement']); ?></span>
    <button class="dismiss-btn" onclick="document.getElementById('announcement-banner').style.display='none'">✕</button>
</div>
<?php endif; ?>

<div class="card-container">

    <div class="main-card">
        <p class="card-title">Gym Availability</p>
        <div class="today-datetime">
            <span><?php echo date('l, F j, Y'); ?></span>
            <span><?php echo date('g:i A'); ?></span>
        </div>
        <menu class="card-menu">
            <?php foreach ($days as $day):
                $status    = $content['status_'.$day] ?? 'open';
                $hours     = htmlspecialchars($content['hours_'.$day] ?? '');
                $is_closed = $status === 'closed';
                $is_today  = $day === $today;
                $cls       = $is_today ? 'hours-li today-row' : 'hours-li other-day';
                if ($is_closed) $cls .= ' day-closed';
            ?>
            <li class="<?php echo $cls; ?>">
                <span class="hours-day-label">
                    <?php if ($is_today): ?>
                        <span class="today-indicator">▶</span>
                    <?php else: ?>
                        <span class="status-dot"><?php echo $is_closed ? '🔴' : '🟢'; ?></span>
                    <?php endif; ?>
                    <?php echo ucfirst($day); ?>
                    <?php if ($is_today): ?><span class="today-tag">TODAY</span><?php endif; ?>
                </span>
                <span class="hours-value">
                    <?php echo $is_closed ? '<span class="closed-label">CLOSED</span>' : $hours; ?>
                </span>
            </li>
            <?php endforeach; ?>
        </menu>
    </div>

    <div class="main-card">
        <p class="card-title">Active Members</p>
        <p class="stat-number"><?php echo htmlspecialchars($content['active_members']); ?></p>
        <p class="stat-label">Minions Enlisted</p>
    </div>

    <div class="main-card">
        <p class="card-title">Upcoming Events</p>
        <p><?php echo nl2br(htmlspecialchars($content['upcoming_events'])); ?></p>
    </div>

</div>

<?php if (!$is_admin): ?>
<div class="home-actions">
    <a href="plans.php" class="btn-outline-purple">MEMBERSHIP PLANS</a>
    <?php if ($is_logged): ?>
    <a href="dashboard.php" class="btn-outline-green">👤 MY DASHBOARD</a>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php if ($is_admin): ?>
<div class="home-actions">
    <button class="btn-outline-purple" onclick="document.getElementById('admin-panel').classList.toggle('show')">⚙️ EDIT HOMEPAGE</button>
</div>
<?php include 'admin_panel.php'; ?>
<?php endif; ?>

<?php include 'Signin.php'; ?>
<script>
const SITE_ROOT = "<?php echo 'http://'.$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['SCRIPT_NAME']),'/\\').'/'; ?>";
</script>
<script src="assets/js/signin.js"></script>
<script src="assets/js/admin.js"></script>
<?php include 'footer.php'; ?>

</body>
</html>
