<?php
// TEMPORARY FILE - DELETE AFTER USE
require_once __DIR__ . '/../config/db.php';

$password = 'Admin@1234';
$hash = password_hash($password, PASSWORD_BCRYPT);

$stmt = $conn->prepare("UPDATE users SET password = ?, role = 'admin' WHERE email = 'admin@mojomuscle.com'");
$stmt->bind_param("s", $hash);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    echo "✅ Admin password reset successfully! Hash: " . $hash;
} else {
    // Try inserting instead
    $stmt2 = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES ('MojoAdmin', 'admin@mojomuscle.com', ?, 'admin')");
    $stmt2->bind_param("s", $hash);
    if ($stmt2->execute()) {
        echo "✅ Admin account created! Hash: " . $hash;
    } else {
        echo "❌ Error: " . $conn->error;
    }
}
$conn->close();
?>
