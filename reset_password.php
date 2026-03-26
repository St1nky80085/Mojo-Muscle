<?php
session_start();
require_once __DIR__ . '/config/db.php';

$token = trim($_GET['token'] ?? '');
$error = '';
$valid = false;

if ($token) {
    $stmt = $conn->prepare("SELECT email FROM password_resets WHERE token=? AND expires_at > NOW() AND used=0");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    $valid = !empty($row);
    $reset_email = $row['email'] ?? '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_pass  = $_POST['new_password']     ?? '';
    $conf_pass = $_POST['confirm_password'] ?? '';
    $tok       = $_POST['token']            ?? '';

    if (strlen($new_pass) < 8 || !preg_match('/[A-Z]/', $new_pass) || !preg_match('/[0-9]/', $new_pass)) {
        $error = 'Password needs 8+ chars, 1 uppercase, 1 number.';
    } elseif ($new_pass !== $conf_pass) {
        $error = 'Passwords do not match.';
    } else {
        // Verify token again
        $s = $conn->prepare("SELECT email FROM password_resets WHERE token=? AND expires_at > NOW() AND used=0");
        $s->bind_param("s", $tok); $s->execute();
        $r = $s->get_result()->fetch_assoc(); $s->close();

        if (!$r) {
            $error = 'Reset link expired or already used.';
        } else {
            $hash = password_hash($new_pass, PASSWORD_BCRYPT);
            $u = $conn->prepare("UPDATE users SET password=? WHERE email=?");
            $u->bind_param("ss", $hash, $r['email']); $u->execute(); $u->close();

            $m = $conn->prepare("UPDATE password_resets SET used=1 WHERE token=?");
            $m->bind_param("s", $tok); $m->execute(); $m->close();

            $conn->close();
            header('Location: HOME.php?reset=success'); exit;
        }
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — Mojo Muscle</title>
    <link rel="icon" type="image/x-icon" href="Assets/image/icon.ico">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/bg.css">
    <style>
        .reset-wrap {
            display: flex; align-items: center; justify-content: center;
            min-height: 100vh; padding: 20px;
        }
        .reset-box {
            background: rgba(13,8,28,0.97);
            border: 1px solid rgba(151,95,255,0.25);
            border-radius: 16px; padding: 40px 36px;
            width: 100%; max-width: 420px;
            box-shadow: 0 24px 60px rgba(0,0,0,0.7);
            position: relative; overflow: hidden;
        }
        .reset-box::before {
            content: ''; position: absolute; top: 0; left: 10%; right: 10%; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(151,95,255,0.5), transparent);
        }
        .reset-title {
            font-family: 'Courier New', monospace; font-size: 1.4rem;
            font-weight: 900; color: #cfb2ff; letter-spacing: 3px;
            text-transform: uppercase; text-align: center; margin: 0 0 6px;
        }
        .reset-sub {
            font-family: 'Courier New', monospace; font-size: 0.72rem;
            color: #555; text-align: center; margin: 0 0 28px; letter-spacing: 1px;
        }
        .reset-label {
            display: block; font-family: 'Courier New', monospace;
            font-size: 0.65rem; color: #bbb; letter-spacing: 1.5px;
            text-transform: uppercase; margin-bottom: 6px;
        }
        .reset-input {
            width: 100%; box-sizing: border-box;
            background: rgba(255,255,255,0.04); color: #e8e8e8;
            border: none; border-bottom: 1px solid rgba(151,95,255,0.25);
            padding: 11px 10px; border-radius: 6px 6px 0 0;
            font-family: 'Courier New', monospace; font-size: 0.88rem;
            margin-bottom: 18px; outline: none; transition: all 0.25s;
        }
        .reset-input:focus { border-bottom-color: #9757ff; background: rgba(151,95,255,0.06); }
        .reset-btn {
            width: 100%; padding: 13px;
            background: linear-gradient(135deg, #4a148c, #7b2fbe);
            color: #fff; border: none; border-radius: 40px;
            font-family: 'Courier New', monospace; font-weight: bold;
            font-size: 0.88rem; letter-spacing: 2px; cursor: pointer;
            box-shadow: 0 4px 20px rgba(74,20,140,0.5); transition: all 0.3s;
        }
        .reset-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(151,95,255,0.5); }
        .reset-error { background: rgba(255,77,77,0.08); border: 1px solid rgba(255,77,77,0.25); border-left: 3px solid #ff4d4d; border-radius: 6px; padding: 10px 14px; font-family: 'Courier New', monospace; font-size: 0.78rem; color: #ff6b6b; margin-bottom: 18px; }
        .reset-invalid { text-align: center; color: #ff6b6b; font-family: 'Courier New', monospace; font-size: 0.85rem; padding: 20px 0; }
        .back-link { display: block; text-align: center; margin-top: 18px; font-family: 'Courier New', monospace; font-size: 0.72rem; color: #555; text-decoration: underline; cursor: pointer; transition: color 0.2s; }
        .back-link:hover { color: #9757ff; }
    </style>
</head>
<body>
<?php include 'bg.php'; ?>
<div class="reset-wrap">
    <div class="reset-box">
        <?php if (!$token || !$valid): ?>
            <p class="reset-title">Invalid Link</p>
            <p class="reset-invalid">This reset link is invalid or has expired.<br>Please request a new one.</p>
            <a href="HOME.php" class="back-link">Back to Home</a>
        <?php else: ?>
            <p class="reset-title">New Password</p>
            <p class="reset-sub">Set a new password for your account</p>
            <?php if ($error): ?>
                <div class="reset-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="POST">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                <label class="reset-label">New Password</label>
                <input class="reset-input" type="password" name="new_password" placeholder="8+ chars, 1 upper, 1 number" required>
                <label class="reset-label">Confirm Password</label>
                <input class="reset-input" type="password" name="confirm_password" placeholder="••••••••" required>
                <button class="reset-btn" type="submit">SET NEW PASSWORD</button>
            </form>
            <a href="HOME.php" class="back-link">Cancel — Back to Home</a>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
