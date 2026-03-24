<?php
header('Content-Type: application/json');
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status'=>'error','message'=>'You must be logged in to book a class.']); exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status'=>'error','message'=>'Invalid method.']); exit;
}

$action   = $_POST['action'] ?? '';
$class_id = intval($_POST['class_id'] ?? 0);
$user_id  = $_SESSION['user_id'];

if (!$class_id) {
    echo json_encode(['status'=>'error','message'=>'Invalid class.']); exit;
}

if ($action === 'book') {
    $slots = $conn->prepare("SELECT max_slots, (SELECT COUNT(*) FROM bookings WHERE class_id=?) AS booked FROM gym_classes WHERE id=?");
    $slots->bind_param("ii", $class_id, $class_id); $slots->execute();
    $s = $slots->get_result()->fetch_assoc(); $slots->close();

    if (!$s) { echo json_encode(['status'=>'error','message'=>'Class not found.']); exit; }
    if ($s['booked'] >= $s['max_slots']) { echo json_encode(['status'=>'error','message'=>'Class is full.']); exit; }

    $stmt = $conn->prepare("INSERT INTO bookings (user_id, class_id) VALUES (?,?)");
    $stmt->bind_param("ii", $user_id, $class_id);
    echo $stmt->execute()
        ? json_encode(['status'=>'success','message'=>'Class booked! See you there, Minion.'])
        : json_encode(['status'=>'error','message'=>'Already booked or an error occurred.']);
    $stmt->close();

} elseif ($action === 'cancel') {
    $stmt = $conn->prepare("DELETE FROM bookings WHERE user_id=? AND class_id=?");
    $stmt->bind_param("ii", $user_id, $class_id); $stmt->execute(); $stmt->close();
    echo json_encode(['status'=>'success','message'=>'Booking cancelled.']);

} else {
    echo json_encode(['status'=>'error','message'=>'Unknown action.']);
}
$conn->close();
