<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: HOME.php'); exit; }
require_once __DIR__ . '/config/db.php';

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, email, created_at FROM users WHERE id=?");
$stmt->bind_param("i", $user_id); $stmt->execute();
$user = $stmt->get_result()->fetch_assoc(); $stmt->close();

$mem = $conn->prepare("SELECT plan, status, start_date, end_date FROM memberships WHERE user_id=? ORDER BY id DESC LIMIT 1");
$mem->bind_param("i", $user_id); $mem->execute();
$membership = $mem->get_result()->fetch_assoc(); $mem->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mojo's | Account Settings</title>
    <link rel="icon" type="image/x-icon" href="Assets/image/icon.ico">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/bg.css">
    <link rel="stylesheet" href="assets/css/inup.css">
    <link rel="stylesheet" href="assets/css/home.css">
    <link rel="stylesheet" href="assets/css/profile.css">
</head>
<body>
<?php include 'bg.php'; ?>
<?php $page = "PROFILE"; include 'navbar.php'; ?>

<div class="profile-wrapper">

    <div class="profile-header">
        <div class="profile-big-avatar">🧠</div>
        <div>
            <h1><?php echo htmlspecialchars($user['username']); ?></h1>
            <p class="email"><?php echo htmlspecialchars($user['email']); ?></p>
            <p class="joined">Minion since <?php echo date('F j, Y', strtotime($user['created_at'])); ?></p>
            <?php if ($membership): ?>
            <span class="mem-pill <?php echo $membership['status']; ?>">
                <?php echo $membership['plan']; ?> — <?php echo strtoupper($membership['status']); ?>
            </span>
            <?php endif; ?>
        </div>
    </div>

    <div class="settings-grid">

        <div class="settings-card">
            <h3>✏️ Username</h3>
            <form id="update-username-form">
                <label>New Username</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required />
                <button type="submit">SAVE USERNAME</button>
            </form>
        </div>

        <div class="settings-card">
            <h3>📧 Email</h3>
            <form id="update-email-form">
                <label>New Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required />
                <label>Current Password <small>(required)</small></label>
                <input type="password" name="current_password" placeholder="••••••••" required />
                <button type="submit">SAVE EMAIL</button>
            </form>
        </div>

        <div class="settings-card">
            <h3>🔑 Password</h3>
            <form id="reset-password-form">
                <label>Current Password</label>
                <input type="password" name="current_password" placeholder="••••••••" required />
                <label>New Password</label>
                <input type="password" name="new_password" placeholder="8+ chars, 1 upper, 1 number" required />
                <label>Confirm New Password</label>
                <input type="password" name="confirm_password" placeholder="••••••••" required />
                <button type="submit">CHANGE PASSWORD</button>
            </form>
        </div>

        <div class="settings-card">
            <h3>💳 Membership</h3>
            <?php if ($membership):
                $days_left = max(0, (int)((strtotime($membership['end_date']) - time()) / 86400));
            ?>
            <div class="info-row"><span>Plan</span><span><?php echo $membership['plan']; ?></span></div>
            <div class="info-row"><span>Status</span>
                <span style="color:<?php echo $membership['status']==='active'?'#92ff77':'#ff6b6b'; ?>">
                    <?php echo strtoupper($membership['status']); ?>
                </span>
            </div>
            <div class="info-row"><span>Started</span><span><?php echo date('M j, Y', strtotime($membership['start_date'])); ?></span></div>
            <div class="info-row"><span>Expires</span><span><?php echo date('M j, Y', strtotime($membership['end_date'])); ?></span></div>
            <div class="info-row">
                <span>Days Left</span>
                <span style="color:<?php echo $days_left < 7 ? '#ff6b6b' : '#92ff77'; ?>"><?php echo $days_left; ?>d</span>
            </div>
            <a href="plans.php" class="upgrade-link" style="color:#92ff77; margin-top:14px; display:inline-block; font-size:0.8rem; font-family:'Courier New',monospace; text-decoration:none;">⬆️ Change Plan</a>
            <?php else: ?>
            <p style="color:#666; font-size:0.83rem;">No active membership.</p>
            <a href="plans.php" style="color:#92ff77; font-size:0.8rem; font-family:'Courier New',monospace;">View Plans →</a>
            <?php endif; ?>
        </div>

        <div class="settings-card danger-card">
            <h3 style="color:#ff6b6b;">⚠️ Danger Zone</h3>
            <p>Permanently delete your account. This cannot be undone.</p>
            <button id="delete-account-btn" class="danger-btn">DELETE MY ACCOUNT</button>
        </div>

    </div>
</div>

<div id="mojo-toast" class="mojo-toast"></div>

<div id="confirm-delete-modal" class="modal-overlay">
    <div class="confirm-box">
        <p style="color:#ff6b6b; font-family:'Courier New',monospace; font-weight:bold; font-size:1rem; margin-bottom:8px;">⚠️ ARE YOU SURE?</p>
        <p style="color:#888; font-size:0.83rem; margin-bottom:18px;">This will permanently delete your account and all your data.</p>
        <label style="color:#cfb2ff; font-size:0.8rem; font-family:'Courier New',monospace;">Type your password to confirm</label>
        <input type="password" id="delete-confirm-pwd" placeholder="••••••••" />
        <div style="display:flex; gap:10px; margin-top:14px;">
            <button id="confirm-delete-btn" class="danger-btn" style="flex:1;">YES, DELETE</button>
            <button id="cancel-delete-btn" style="flex:1; background:#333; color:#aaa; border:1px solid #444; border-radius:6px; padding:10px; font-family:'Courier New',monospace; cursor:pointer;">CANCEL</button>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<script>
const SITE_ROOT = "<?php echo 'http://'.$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['SCRIPT_NAME']),'/\\').'/'; ?>";
</script>
<script src="assets/js/profile.js"></script>
</body>
</html>
