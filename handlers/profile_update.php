<?php
ini_set('display_errors', 0);
header('Content-Type: application/json');
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status'=>'error','message'=>'Not logged in.']); exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status'=>'error','message'=>'Invalid method.']); exit;
}

$action  = $_POST['action'] ?? '';
$user_id = $_SESSION['user_id'];

// ── UPDATE USERNAME ──────────────────────────────
if ($action === 'update_username') {
    $username = trim(htmlspecialchars($_POST['username'] ?? ''));
    if (strlen($username) < 3) {
        echo json_encode(['status'=>'error','message'=>'Username must be at least 3 characters.']); exit;
    }
    // Check duplicate
    $chk = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
    $chk->bind_param("si", $username, $user_id);
    $chk->execute(); $chk->store_result();
    if ($chk->num_rows > 0) {
        echo json_encode(['status'=>'error','message'=>'Username already taken.']); exit;
    }
    $chk->close();

    $stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
    $stmt->bind_param("si", $username, $user_id);
    if ($stmt->execute()) {
        $_SESSION['username'] = $username;
        echo json_encode(['status'=>'success','message'=>'Username updated!','username'=>$username]);
    } else {
        echo json_encode(['status'=>'error','message'=>'Update failed.']);
    }
    $stmt->close();

// ── UPDATE EMAIL ─────────────────────────────────
} elseif ($action === 'update_email') {
    $email    = trim($_POST['email'] ?? '');
    $cur_pass = $_POST['current_password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status'=>'error','message'=>'Invalid email address.']); exit;
    }
    // Verify current password
    $s = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $s->bind_param("i", $user_id); $s->execute();
    $row = $s->get_result()->fetch_assoc(); $s->close();
    if (!password_verify($cur_pass, $row['password'])) {
        echo json_encode(['status'=>'error','message'=>'Current password is incorrect.']); exit;
    }
    // Check duplicate email
    $chk = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $chk->bind_param("si", $email, $user_id);
    $chk->execute(); $chk->store_result();
    if ($chk->num_rows > 0) {
        echo json_encode(['status'=>'error','message'=>'Email already in use.']); exit;
    }
    $chk->close();

    $stmt = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
    $stmt->bind_param("si", $email, $user_id);
    if ($stmt->execute()) {
        echo json_encode(['status'=>'success','message'=>'Email updated successfully!']);
    } else {
        echo json_encode(['status'=>'error','message'=>'Update failed.']);
    }
    $stmt->close();

// ── CHANGE PASSWORD ──────────────────────────────
} elseif ($action === 'change_password') {
    $cur_pass  = $_POST['current_password'] ?? '';
    $new_pass  = $_POST['new_password'] ?? '';
    $conf_pass = $_POST['confirm_password'] ?? '';

    // Verify current
    $s = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $s->bind_param("i", $user_id); $s->execute();
    $row = $s->get_result()->fetch_assoc(); $s->close();
    if (!password_verify($cur_pass, $row['password'])) {
        echo json_encode(['status'=>'error','message'=>'Current password is incorrect.']); exit;
    }
    if (strlen($new_pass) < 8 || !preg_match('/[A-Z]/', $new_pass) || !preg_match('/[0-9]/', $new_pass)) {
        echo json_encode(['status'=>'error','message'=>'New password needs 8+ chars, 1 uppercase, 1 number.']); exit;
    }
    if ($new_pass !== $conf_pass) {
        echo json_encode(['status'=>'error','message'=>'Passwords do not match.']); exit;
    }

    $hashed = password_hash($new_pass, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $hashed, $user_id);
    if ($stmt->execute()) {
        echo json_encode(['status'=>'success','message'=>'Password changed successfully!']);
    } else {
        echo json_encode(['status'=>'error','message'=>'Update failed.']);
    }
    $stmt->close();

// ── DELETE ACCOUNT ───────────────────────────────
} elseif ($action === 'delete_account') {
    $cur_pass = $_POST['password'] ?? '';

    $s = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $s->bind_param("i", $user_id); $s->execute();
    $row = $s->get_result()->fetch_assoc(); $s->close();
    if (!password_verify($cur_pass, $row['password'])) {
        echo json_encode(['status'=>'error','message'=>'Incorrect password.']); exit;
    }

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        session_destroy();
        echo json_encode(['status'=>'success','message'=>'Account deleted. Farewell, Minion.']);
    } else {
        echo json_encode(['status'=>'error','message'=>'Deletion failed.']);
    }
    $stmt->close();

} else {
    echo json_encode(['status'=>'error','message'=>'Unknown action.']);
}
$conn->close();
