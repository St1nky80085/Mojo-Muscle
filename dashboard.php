<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: HOME.php'); exit; }
require_once __DIR__ . '/config/db.php';

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT u.username, u.email, u.created_at, m.plan, m.status, m.start_date, m.end_date
    FROM users u
    LEFT JOIN memberships m ON m.id = (SELECT id FROM memberships WHERE user_id = u.id ORDER BY id DESC LIMIT 1)
    WHERE u.id = ?
");
$stmt->bind_param("i", $user_id); $stmt->execute();
$user = $stmt->get_result()->fetch_assoc(); $stmt->close();

$cs = $conn->prepare("
    SELECT gc.*,
           (SELECT COUNT(*) FROM bookings WHERE class_id=gc.id) AS booked_count,
           (SELECT COUNT(*) FROM bookings WHERE class_id=gc.id AND user_id=?) AS is_booked
    FROM gym_classes gc
    ORDER BY FIELD(gc.schedule_day,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'), gc.start_time
");
$cs->bind_param("i", $user_id); $cs->execute();
$all_classes = $cs->get_result()->fetch_all(MYSQLI_ASSOC); $cs->close();
$conn->close();

$plan       = $user['plan']   ?? 'Free';
$status     = $user['status'] ?? '';
$mem_active = $status === 'active';
$pd_map     = [
    'Free'=>['FREE','#aaa','🆓'], 'Monthly'=>['FREE','#aaa','🆓'],
    'Premium'=>['PREMIUM','#92ff77','⭐'], 'Quarterly'=>['PREMIUM','#92ff77','⭐'],
    'VIP'=>['VIP','#ffd700','👑'], 'Annual'=>['VIP','#ffd700','👑'],
];
[$label, $color, $icon] = $pd_map[$plan] ?? ['FREE','#aaa','🆓'];
$can_book = $mem_active && $label !== 'FREE';

$days_left = $pct = 0;
if ($mem_active && !empty($user['end_date']) && !empty($user['start_date'])) {
    $days_left  = max(0, (int)((strtotime($user['end_date']) - time()) / 86400));
    $total_days = (strtotime($user['end_date']) - strtotime($user['start_date'])) / 86400;
    $pct = $total_days > 0 ? max(0, min(100, round(($days_left / $total_days) * 100))) : 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mojo's | Dashboard</title>
    <link rel="icon" type="image/x-icon" href="Assets/image/icon.ico">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/bg.css">
    <link rel="stylesheet" href="assets/css/inup.css">
    <link rel="stylesheet" href="assets/css/home.css">
</head>
<body>
<?php include 'bg.php'; ?>
<?php $page = "DASHBOARD"; include 'navbar.php'; ?>

<div class="dash-wrapper">

    <div class="dash-section">

        <div class="dash-card profile-card">
            <div class="profile-avatar">🧠</div>
            <div class="profile-info">
                <h2 class="profile-name"><?php echo htmlspecialchars($user['username']); ?></h2>
                <p class="profile-email"><?php echo htmlspecialchars($user['email']); ?></p>
                <p class="profile-joined">Minion since <?php echo date('F j, Y', strtotime($user['created_at'])); ?></p>
                <a href="profile.php" class="dash-settings-link">⚙️ Account Settings</a>
            </div>
        </div>

        <div class="dash-card membership-card">
            <p class="card-title">Membership Status</p>
            <?php if ($mem_active): ?>
            <div class="mem-badge" style="border-color:<?php echo $color; ?>; color:<?php echo $color; ?>;">
                <?php echo $icon.' '.$label; ?> — ACTIVE
            </div>
            <?php if ($label !== 'FREE'): ?>
            <div class="mem-detail"><span>📅 Started</span><span><?php echo date('M j, Y', strtotime($user['start_date'])); ?></span></div>
            <div class="mem-detail"><span>⏳ Expires</span><span><?php echo date('M j, Y', strtotime($user['end_date'])); ?></span></div>
            <div class="mem-detail">
                <span>🗓️ Days Left</span>
                <span style="color:<?php echo $days_left < 7 ? '#ff6b6b' : '#92ff77'; ?>"><?php echo $days_left; ?> days</span>
            </div>
            <?php if ($pct > 0): ?>
            <div class="mem-bar-wrap">
                <div class="mem-bar"><div class="mem-bar-fill" style="width:<?php echo $pct; ?>%; background:<?php echo $color; ?>;"></div></div>
                <small><?php echo $pct; ?>% remaining</small>
            </div>
            <?php endif; ?>
            <?php else: ?>
            <p style="color:#888; font-size:0.82rem; margin-top:12px;">You're on the free plan. Upgrade to unlock class booking, priority slots & more.</p>
            <?php endif; ?>
            <a href="plans.php" class="upgrade-link" style="color:<?php echo $label==='FREE'?'#92ff77':($label==='PREMIUM'?'#ffd700':'#cfb2ff'); ?>">
                <?php echo $label==='FREE'?'⬆️ Upgrade Plan':($label==='PREMIUM'?'👑 Go VIP':'✅ You\'re on the best plan!'); ?>
            </a>
            <?php else: ?>
            <div class="mem-badge" style="border-color:#ff6b6b; color:#ff6b6b;">❌ NO MEMBERSHIP</div>
            <a href="plans.php" class="upgrade-link" style="color:#92ff77;">⬆️ View Plans</a>
            <?php endif; ?>
        </div>

    </div>

    <div class="dash-card full-width">
        <p class="card-title">🏋️ Class Schedule & Booking</p>
        <?php if (!$can_book): ?>
        <div class="upgrade-notice">
            <?php if ($label === 'FREE'): ?>
            🔒 Class booking requires a <strong>Premium</strong> or <strong>VIP</strong> membership.
            <a href="plans.php" style="color:#92ff77; margin-left:8px;">Upgrade now →</a>
            <?php else: ?>
            Your membership is inactive. <a href="plans.php" style="color:#92ff77;">View plans →</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <div class="classes-grid">
            <?php foreach ($all_classes as $cls):
                $slots_left = $cls['max_slots'] - $cls['booked_count'];
                $is_booked  = (bool)$cls['is_booked'];
                $is_full    = $slots_left <= 0 && !$is_booked;
                $is_closed  = ($cls['status'] ?? 'open') === 'closed';
                $cls_class  = $is_closed ? 'closed-class' : ($is_booked ? 'booked' : ($is_full ? 'full' : ''));
                $time_str   = date('g:i A', strtotime($cls['start_time'])).' – '.date('g:i A', strtotime($cls['end_time']));
            ?>
            <div class="class-card <?php echo $cls_class; ?>">
                <div class="class-day"><?php echo $cls['schedule_day']; ?></div>
                <span class="class-status-badge <?php echo $is_closed ? 'closed' : 'open'; ?>">
                    <?php echo $is_closed ? '🔴 CLOSED' : '🟢 OPEN'; ?>
                </span>
                <div class="class-name"><?php echo htmlspecialchars($cls['class_name']); ?></div>
                <div class="class-meta">🕐 <?php echo $time_str; ?></div>
                <div class="class-meta">👤 <?php echo htmlspecialchars($cls['instructor']); ?></div>
                <?php if (!$is_closed): ?>
                <div class="class-slots <?php echo $slots_left <= 3 ? 'low' : ''; ?>">
                    <?php if ($is_booked): ?>✅ Booked
                    <?php elseif ($is_full): ?>🔴 Full
                    <?php else: ?>🟢 <?php echo $slots_left; ?>/<?php echo $cls['max_slots']; ?> slots
                    <?php endif; ?>
                </div>
                <?php if ($can_book): ?>
                    <?php if ($is_booked): ?>
                        <button class="class-btn cancel-btn" data-id="<?php echo $cls['id']; ?>">CANCEL</button>
                    <?php elseif (!$is_full): ?>
                        <button class="class-btn book-btn" data-id="<?php echo $cls['id']; ?>">BOOK CLASS</button>
                    <?php else: ?>
                        <button class="class-btn" disabled>FULL</button>
                    <?php endif; ?>
                <?php else: ?>
                    <button class="class-btn" disabled style="opacity:0.35;">🔒 MEMBERS ONLY</button>
                <?php endif; ?>
                <?php else: ?>
                <button class="class-btn" disabled style="background:#2a0000; color:#ff6b6b; border:1px solid #4a0000;">UNAVAILABLE</button>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>

<div id="mojo-toast" class="mojo-toast"></div>
<?php include 'footer.php'; ?>
<script>
const SITE_ROOT = "<?php echo 'http://'.$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['SCRIPT_NAME']),'/\\').'/'; ?>";
</script>
<script src="assets/js/signin.js"></script>
<script src="assets/js/dashboard.js"></script>
</body>
</html>
