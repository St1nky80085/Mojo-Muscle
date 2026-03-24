<?php
ini_set('display_errors', 0);
header('Content-Type: application/json');
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status'=>'error','message'=>'Invalid request.']); exit;
}

$username = trim(htmlspecialchars($_POST['username'] ?? ''));
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$plan     = $_POST['plan'] ?? 'Free';

$valid_plans = ['Free','Premium','VIP'];
if (!in_array($plan, $valid_plans)) $plan = 'Free';

if (strlen($username) < 3) {
    echo json_encode(['status'=>'error','message'=>'Username must be at least 3 characters.']); exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status'=>'error','message'=>'Invalid email address.']); exit;
}
if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
    echo json_encode(['status'=>'error','message'=>'Password needs 8+ chars, 1 uppercase, 1 number.']); exit;
}

// Check duplicates
$chk = $conn->prepare("SELECT id FROM users WHERE username=? OR email=?");
$chk->bind_param("ss", $username, $email);
$chk->execute(); $chk->store_result();
if ($chk->num_rows > 0) {
    echo json_encode(['status'=>'error','message'=>'Username or email already taken.']); exit;
}
$chk->close();

// Create user
$hashed = password_hash($password, PASSWORD_BCRYPT);
$stmt   = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?,?,?,'member')");
$stmt->bind_param("sss", $username, $email, $hashed);
if (!$stmt->execute()) {
    echo json_encode(['status'=>'error','message'=>'Registration failed: '.$conn->error]); exit;
}
$user_id = $conn->insert_id;
$stmt->close();

// Set membership duration
$start = date('Y-m-d');
$end   = $plan === 'Free' ? '9999-12-31' : date('Y-m-d', strtotime('+30 days'));

$mem = $conn->prepare("INSERT INTO memberships (user_id, plan, status, start_date, end_date) VALUES (?,?,'active',?,?)");
$mem->bind_param("isss", $user_id, $plan, $start, $end);
if (!$mem->execute()) {
    echo json_encode(['status'=>'error','message'=>'Account created but membership setup failed. Contact admin.']);
} else {
    echo json_encode(['status'=>'success','message'=>'Welcome to the Lair, '.$username.'! Please log in.']);
}
$mem->close();
$conn->close();
