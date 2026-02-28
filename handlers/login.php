<?php
// Show errors as JSON, never as HTML
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']); exit;
}

$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    echo json_encode(['status' => 'error', 'message' => 'Please fill in all fields.']); exit;
}

$stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'DB prepare failed: ' . $conn->error]); exit;
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'No account found with that email.']);
    $stmt->close(); $conn->close(); exit;
}

$user = $result->fetch_assoc();
$stmt->close();

if (!password_verify($password, $user['password'])) {
    echo json_encode(['status' => 'error', 'message' => 'Incorrect password.']);
    $conn->close(); exit;
}

// Get membership
$mem = $conn->prepare("SELECT plan, status, end_date FROM memberships WHERE user_id = ? ORDER BY id DESC LIMIT 1");
if ($mem) {
    $mem->bind_param("i", $user['id']);
    $mem->execute();
    $mresult    = $mem->get_result();
    $membership = $mresult->num_rows > 0 ? $mresult->fetch_assoc() : null;
    $mem->close();
} else {
    $membership = null;
}
$conn->close();

$_SESSION['user_id']    = $user['id'];
$_SESSION['username']   = $user['username'];
$_SESSION['role']       = $user['role'];
$_SESSION['plan']       = $membership['plan']     ?? 'None';
$_SESSION['mem_status'] = $membership['status']   ?? 'none';
$_SESSION['mem_end']    = $membership['end_date'] ?? null;

echo json_encode([
    'status'   => 'success',
    'message'  => 'Welcome back, Minion ' . $user['username'] . '!',
    'username' => $user['username'],
    'role'     => $user['role'],
    'plan'     => $membership['plan']     ?? 'None',
    'mem_end'  => $membership['end_date'] ?? null
]);
