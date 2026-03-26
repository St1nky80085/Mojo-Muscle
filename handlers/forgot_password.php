<?php
ini_set('display_errors', 0);
header('Content-Type: application/json');
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/mail.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status'=>'error','message'=>'Invalid request.']); exit;
}

$email = trim($_POST['email'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status'=>'error','message'=>'Invalid email address.']); exit;
}

// Check if email exists
$stmt = $conn->prepare("SELECT id, username FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
    // Don't reveal if email exists for security — always show success
    echo json_encode(['status'=>'success','message'=>'If that email exists, a reset link has been sent.']);
    $conn->close(); exit;
}

// Delete any old tokens for this email
$conn->query("DELETE FROM password_resets WHERE email = '$email'");

// Generate secure token
$token     = bin2hex(random_bytes(32));
$expires   = date('Y-m-d H:i:s', strtotime('+1 hour'));

$ins = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?,?,?)");
$ins->bind_param("sss", $email, $token, $expires);
$ins->execute();
$ins->close();
$conn->close();

// Build reset link
$reset_link = SITE_URL . 'reset_password.php?token=' . $token;

// Send email via PHPMailer
$result = sendMail(
    $email,
    $user['username'],
    'Reset Your Mojo Muscle Password',
    buildResetEmail($user['username'], $reset_link)
);

if ($result === true) {
    echo json_encode(['status'=>'success','message'=>'Reset link sent! Check your email (also check spam).']);
} else {
    echo json_encode(['status'=>'error','message'=>'Failed to send email. Try again later.']);
}

function buildResetEmail($username, $link) {
    return "
    <div style='font-family: Courier New, monospace; background: #0d0d0d; color: #e0e0e0; padding: 40px; max-width: 520px; margin: 0 auto; border-radius: 12px; border: 1px solid rgba(151,95,255,0.3);'>
        <h1 style='color: #cfb2ff; letter-spacing: 3px; font-size: 1.2rem; margin-bottom: 4px;'>MOJO MUSCLE</h1>
        <p style='color: #555; font-size: 0.75rem; letter-spacing: 2px; margin-top: 0;'>PASSWORD RESET REQUEST</p>
        <hr style='border: none; border-top: 1px solid rgba(151,95,255,0.2); margin: 20px 0;'>
        <p style='color: #bbb;'>Hey <strong style='color: #cfb2ff;'>$username</strong>,</p>
        <p style='color: #bbb; line-height: 1.7;'>Someone requested a password reset for your account. Click the button below to set a new password. This link expires in <strong style='color: #92ff77;'>1 hour</strong>.</p>
        <div style='text-align: center; margin: 32px 0;'>
            <a href='$link' style='background: linear-gradient(135deg, #4a148c, #7b2fbe); color: #fff; padding: 14px 36px; border-radius: 40px; text-decoration: none; font-weight: bold; letter-spacing: 2px; font-size: 0.9rem;'>RESET PASSWORD</a>
        </div>
        <p style='color: #555; font-size: 0.75rem; line-height: 1.6;'>If you didn't request this, ignore this email — your password won't change.<br>Link: <a href='$link' style='color: #9757ff;'>$link</a></p>
        <hr style='border: none; border-top: 1px solid rgba(151,95,255,0.1); margin: 20px 0;'>
        <p style='color: #333; font-size: 0.65rem; text-align: center; letter-spacing: 1px;'>MOJO MUSCLE GYM — TOWNSVILLE, PH</p>
    </div>";
}
