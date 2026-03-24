<?php
ini_set('display_errors', 0);
header('Content-Type: application/json');
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status'=>'error','message'=>'Invalid request.']); exit;
}

$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!$email || !$password) {
    echo json_encode(['status'=>'error','message'=>'Please fill in all fields.']); exit;
}

$stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE email=?");
$stmt->bind_param("s", $email); $stmt->execute();
$user = $stmt->get_result()->fetch_assoc(); $stmt->close();

if (!$user || !password_verify($password, $user['password'])) {
    echo json_encode(['status'=>'error','message'=>'Invalid email or password.']); exit;
}

$mem = $conn->prepare("SELECT plan, status, end_date FROM memberships WHERE user_id=? ORDER BY id DESC LIMIT 1");
$mem->bind_param("i", $user['id']); $mem->execute();
$membership = $mem->get_result()->fetch_assoc(); $mem->close();
$conn->close();

$_SESSION['user_id']    = $user['id'];
$_SESSION['username']   = $user['username'];
$_SESSION['role']       = $user['role'];
$_SESSION['plan']       = $membership['plan']   ?? 'Free';
$_SESSION['mem_status'] = $membership['status'] ?? 'none';
$_SESSION['mem_end']    = $membership['end_date'] ?? null;

echo json_encode([
    'status'  => 'success',
    'message' => 'Welcome back, ' . $user['username'] . '!',
    'role'    => $user['role'],
    'plan'    => $membership['plan'] ?? 'Free',
]);
