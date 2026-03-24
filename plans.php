<?php
session_start();
require_once __DIR__ . '/config/db.php';
$conn->close();

$is_logged = isset($_SESSION['user_id']);
$is_admin  = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$user_plan = $_SESSION['plan'] ?? 'Free';

// Normalize to display name
$plan_map = ['Free'=>'FREE','Monthly'=>'FREE','Premium'=>'PREMIUM','Quarterly'=>'PREMIUM','Annual'=>'VIP','VIP'=>'VIP'];
$current  = $plan_map[$user_plan] ?? 'FREE';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mojo's | Membership Plans</title>
    <link rel="icon" type="image/x-icon" href="Assets/image/icon.ico">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/bg.css">
    <link rel="stylesheet" href="assets/css/inup.css">
    <link rel="stylesheet" href="assets/css/home.css">
    <link rel="stylesheet" href="assets/css/membership.css">
</head>
<body>
<?php include 'bg.php'; ?>
<?php $page = "PLANS"; include 'navbar.php'; ?>

<div class="membership-section" style="padding-top:20px;">
    <p class="section-title">CHOOSE YOUR PLAN</p>
    <p class="section-sub">Level up your training. Pick the plan that fits your grind.</p>

    <div class="plans-grid">

        <!-- FREE -->
        <div class="plan-card <?php echo $current==='FREE' ? 'current-plan' : ''; ?>">
            <div class="plan-badge free">FREE</div>
            <div class="plan-price">
                <span class="plan-amount">₱0</span>
                <span class="plan-period">/forever</span>
            </div>
            <ul class="plan-perks">
                <li class="perk-yes">Basic gym access (limited hours)</li>
                <li class="perk-yes">View class schedule</li>
                <li class="perk-no">No class booking</li>
                <li class="perk-no">No dashboard features</li>
                <li class="perk-no">No priority slots</li>
                <li class="perk-no">No personal trainer</li>
                <li class="perk-no">No VIP lounge</li>
                <li class="perk-no">No ad-free</li>
            </ul>
            <?php if ($current === 'FREE'): ?>
                <button class="plan-btn current-btn" disabled>CURRENT PLAN</button>
            <?php elseif (!$is_logged): ?>
                <button class="plan-btn free-btn" onclick="openSigninWithPlan('Free')">GET STARTED FREE</button>
            <?php else: ?>
                <button class="plan-btn free-btn" disabled>DOWNGRADE</button>
            <?php endif; ?>
        </div>

        <!-- PREMIUM -->
        <div class="plan-card popular <?php echo $current==='PREMIUM' ? 'current-plan' : ''; ?>">
            <div class="popular-tag">MOST POPULAR</div>
            <div class="plan-badge quarterly">PREMIUM</div>
            <div class="plan-price">
                <span class="plan-amount">₱999</span>
                <span class="plan-period">/month</span>
            </div>
            <ul class="plan-perks">
                <li class="perk-yes">Full gym access</li>
                <li class="perk-yes">View class schedule</li>
                <li class="perk-yes">Class booking</li>
                <li class="perk-yes">Member dashboard</li>
                <li class="perk-yes">Priority slots</li>
                <li class="perk-yes">Personal trainer (2x/month)</li>
                <li class="perk-no">No VIP lounge</li>
                <li class="perk-yes">Ad-free experience</li>
            </ul>
            <?php if ($current === 'PREMIUM'): ?>
                <button class="plan-btn current-btn" disabled>CURRENT PLAN</button>
            <?php elseif (!$is_logged): ?>
                <button class="plan-btn quarterly-btn" onclick="openSigninWithPlan('Premium')">JOIN PREMIUM</button>
            <?php elseif ($current === 'FREE'): ?>
                <button class="plan-btn quarterly-btn" onclick="openSigninWithPlan('Premium')">UPGRADE TO PREMIUM</button>
            <?php else: ?>
                <button class="plan-btn quarterly-btn" disabled>DOWNGRADE</button>
            <?php endif; ?>
        </div>

        <!-- VIP -->
        <div class="plan-card vip <?php echo $current==='VIP' ? 'current-plan' : ''; ?>">
            <div class="vip-tag">VIP</div>
            <div class="plan-badge annual">VIP</div>
            <div class="plan-price">
                <span class="plan-amount">₱1,999</span>
                <span class="plan-period">/month</span>
            </div>
            <ul class="plan-perks">
                <li class="perk-yes">Full gym access</li>
                <li class="perk-yes">View class schedule</li>
                <li class="perk-yes">Class booking</li>
                <li class="perk-yes">Member dashboard</li>
                <li class="perk-yes">Priority slots</li>
                <li class="perk-yes">Personal trainer (unlimited)</li>
                <li class="perk-yes">VIP lounge access</li>
                <li class="perk-yes">Ad-free experience</li>
            </ul>
            <?php if ($current === 'VIP'): ?>
                <button class="plan-btn current-btn" disabled>CURRENT PLAN</button>
            <?php elseif (!$is_logged): ?>
                <button class="plan-btn annual-btn" onclick="openSigninWithPlan('VIP')">GO VIP</button>
            <?php else: ?>
                <button class="plan-btn annual-btn" onclick="openSigninWithPlan('VIP')">UPGRADE TO VIP</button>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php include 'Signin.php'; ?>
<?php include 'footer.php'; ?>
<script>
const SITE_ROOT = "<?php echo 'http://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/'; ?>";
</script>
<script src="assets/js/signin.js"></script>
</body>
</html>
