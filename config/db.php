<?php
// =============================================
// THE MOJO MUSCLE - Database Connection
// File: config/db.php
// =============================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // Default XAMPP username
define('DB_PASS', '');           // Default XAMPP password (empty)
define('DB_NAME', 'mojo_muscle'); // Database name

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die(json_encode([
        'status' => 'error',
        'message' => 'Database connection failed: ' . $conn->connect_error
    ]));
}

$conn->set_charset("utf8mb4");
?>
