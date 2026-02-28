<?php
ini_set('display_errors', 0);
header('Content-Type: application/json');
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['status'=>'error','message'=>'Unauthorized.']); exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status'=>'error','message'=>'Invalid method.']); exit;
}

$action = $_POST['action'] ?? 'update_home';

// ── UPDATE HOME CONTENT ──────────────────────────────────
if ($action === 'update_home') {
    $allowed_keys = [
        'active_members','upcoming_events','announcement',
        'hours_monday','hours_tuesday','hours_wednesday',
        'hours_thursday','hours_friday','hours_saturday','hours_sunday',
        'status_monday','status_tuesday','status_wednesday',
        'status_thursday','status_friday','status_saturday','status_sunday'
    ];
    $updated = 0;
    foreach ($allowed_keys as $key) {
        if (isset($_POST[$key])) {
            $val  = trim(htmlspecialchars($_POST[$key]));
            $stmt = $conn->prepare("INSERT INTO home_content (content_key, content_value) VALUES (?,?) ON DUPLICATE KEY UPDATE content_value=?");
            $stmt->bind_param("sss", $key, $val, $val);
            $stmt->execute(); $stmt->close();
            $updated++;
        }
    }
    echo json_encode(['status'=>'success','message'=>'Saved! ('.$updated.' fields updated)']);

// ── TOGGLE CLASS STATUS ──────────────────────────────────
} elseif ($action === 'toggle_class_status') {
    $class_id  = intval($_POST['class_id'] ?? 0);
    $newstatus = in_array($_POST['new_status']??'', ['open','closed']) ? $_POST['new_status'] : 'open';
    $stmt = $conn->prepare("UPDATE gym_classes SET status=? WHERE id=?");
    $stmt->bind_param("si", $newstatus, $class_id);
    echo $stmt->execute()
        ? json_encode(['status'=>'success','new_status'=>$newstatus])
        : json_encode(['status'=>'error','message'=>'Update failed.']);
    $stmt->close();

// ── ADD CLASS ────────────────────────────────────────────
} elseif ($action === 'add_class') {
    $name       = trim(htmlspecialchars($_POST['class_name'] ?? ''));
    $instructor = trim(htmlspecialchars($_POST['instructor'] ?? ''));
    $day        = $_POST['schedule_day'] ?? '';
    $start      = $_POST['start_time'] ?? '';
    $end        = $_POST['end_time'] ?? '';
    $slots      = intval($_POST['max_slots'] ?? 20);
    $valid_days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
    if (empty($name)||empty($instructor)||!in_array($day,$valid_days)||empty($start)||empty($end)) {
        echo json_encode(['status'=>'error','message'=>'All fields required.']); exit;
    }
    $stmt = $conn->prepare("INSERT INTO gym_classes (class_name,instructor,schedule_day,start_time,end_time,max_slots,status) VALUES (?,?,?,?,?,?,'open')");
    $stmt->bind_param("sssssi", $name, $instructor, $day, $start, $end, $slots);
    if ($stmt->execute()) {
        echo json_encode(['status'=>'success','message'=>'Class added!','class_id'=>$conn->insert_id,
            'class_name'=>$name,'instructor'=>$instructor,'schedule_day'=>$day,
            'start_time'=>$start,'end_time'=>$end,'max_slots'=>$slots]);
    } else { echo json_encode(['status'=>'error','message'=>'Failed: '.$conn->error]); }
    $stmt->close();

// ── DELETE CLASS ─────────────────────────────────────────
} elseif ($action === 'delete_class') {
    $class_id = intval($_POST['class_id'] ?? 0);
    $stmt = $conn->prepare("DELETE FROM gym_classes WHERE id=?");
    $stmt->bind_param("i", $class_id);
    echo $stmt->execute()
        ? json_encode(['status'=>'success','message'=>'Class deleted.'])
        : json_encode(['status'=>'error','message'=>'Delete failed.']);
    $stmt->close();

// ── DELETE USER ──────────────────────────────────────────
} elseif ($action === 'delete_user') {
    $target_id = intval($_POST['user_id'] ?? 0);
    if ($target_id === (int)$_SESSION['user_id']) {
        echo json_encode(['status'=>'error','message'=>'Cannot delete your own account.']); exit;
    }
    $chk = $conn->prepare("SELECT role FROM users WHERE id=?");
    $chk->bind_param("i", $target_id); $chk->execute();
    $row = $chk->get_result()->fetch_assoc(); $chk->close();
    if (!$row) { echo json_encode(['status'=>'error','message'=>'User not found.']); exit; }
    if ($row['role']==='admin') { echo json_encode(['status'=>'error','message'=>'Cannot delete admins.']); exit; }
    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i", $target_id);
    echo $stmt->execute()
        ? json_encode(['status'=>'success','message'=>'Account deleted.'])
        : json_encode(['status'=>'error','message'=>'Delete failed.']);
    $stmt->close();

// ── UPDATE MEMBERSHIP ────────────────────────────────────
} elseif ($action === 'update_membership') {
    $target_id = intval($_POST['user_id'] ?? 0);
    $plan      = $_POST['plan']     ?? 'Free';
    $status    = $_POST['status']   ?? 'active';
    $end_date  = $_POST['end_date'] ?? '';

    $valid_plans    = ['Free','Premium','VIP','Monthly','Quarterly','Annual'];
    $valid_statuses = ['active','expired','cancelled'];
    if (!in_array($plan, $valid_plans))       $plan   = 'Free';
    if (!in_array($status, $valid_statuses))  $status = 'active';

    // Validate date
    if (empty($end_date) || !strtotime($end_date)) {
        $end_date = date('Y-m-d', strtotime('+30 days'));
    }

    // Check if membership exists
    $chk = $conn->prepare("SELECT id FROM memberships WHERE user_id=? ORDER BY id DESC LIMIT 1");
    $chk->bind_param("i", $target_id); $chk->execute(); $chk->store_result();

    if ($chk->num_rows > 0) {
        // Update existing
        $stmt = $conn->prepare("UPDATE memberships SET plan=?, status=?, end_date=? WHERE user_id=? ORDER BY id DESC LIMIT 1");
        $stmt->bind_param("sssi", $plan, $status, $end_date, $target_id);
    } else {
        // Insert new
        $start = date('Y-m-d');
        $stmt = $conn->prepare("INSERT INTO memberships (user_id,plan,status,start_date,end_date) VALUES (?,?,?,?,?)");
        $stmt->bind_param("issss", $target_id, $plan, $status, $start, $end_date);
    }
    $chk->close();

    echo $stmt->execute()
        ? json_encode(['status'=>'success','message'=>'Membership updated!','plan'=>$plan,'mem_status'=>$status])
        : json_encode(['status'=>'error','message'=>'Update failed: '.$conn->error]);
    $stmt->close();

} else {
    echo json_encode(['status'=>'error','message'=>'Unknown action.']);
}
$conn->close();
