<?php
// TEMPORARY DEBUG FILE - DELETE AFTER FIXING
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/plain');

echo "=== STEP 1: PHP is working ===\n";

// Test session
if (session_status() === PHP_SESSION_NONE) session_start();
echo "=== STEP 2: Session OK ===\n";

// Test DB connection
require_once __DIR__ . '/../config/db.php';
echo "=== STEP 3: DB Connected ===\n";

// Test users table
$result = $conn->query("SELECT id, username, email, role FROM users LIMIT 5");
if (!$result) {
    echo "ERROR querying users: " . $conn->error . "\n";
} else {
    echo "=== STEP 4: Users table OK ===\n";
    while ($row = $result->fetch_assoc()) {
        echo "  - ID:{$row['id']} | {$row['username']} | {$row['email']} | role:{$row['role']}\n";
    }
}

// Test memberships table
$result2 = $conn->query("SELECT * FROM memberships LIMIT 5");
if (!$result2) {
    echo "ERROR querying memberships: " . $conn->error . "\n";
} else {
    echo "=== STEP 5: Memberships table OK ===\n";
    while ($row = $result2->fetch_assoc()) {
        echo "  - user_id:{$row['user_id']} | {$row['plan']} | {$row['status']}\n";
    }
}

echo "=== ALL CHECKS PASSED ===\n";
$conn->close();
