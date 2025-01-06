<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../User/Userlogin.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "users");
if ($conn === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

$user_id = $_SESSION['user_id'];
$notifications = [
    'new_policies' => 0,
    'approved_policies' => 0,
    'claim_status' => 0,
    'support_replies' => 0,
    'general_notifications' => 0,
    'payment_reminders' => 0,
];

// Query to count new policies
$result = $conn->query("SELECT COUNT(*) as count FROM policies WHERE created_at > (SELECT last_checked FROM user_activity WHERE user_id = $user_id AND activity_type = 'policy_view')");
if ($row = $result->fetch_assoc()) {
    $notifications['new_policies'] = $row['count'];
}

// Query to count approved policies
$result = $conn->query("SELECT COUNT(*) as count FROM approved_policies WHERE user_id = $user_id AND viewed = 0");
if ($row = $result->fetch_assoc()) {
    $notifications['approved_policies'] = $row['count'];
}

// Query to count claim status updates
$result = $conn->query("SELECT COUNT(*) as count FROM claims WHERE user_id = $user_id AND status_changed = 1");
if ($row = $result->fetch_assoc()) {
    $notifications['claim_status'] = $row['count'];
}

// Query to count unread support replies
$result = $conn->query("SELECT COUNT(*) as count FROM support_tickets WHERE user_id = $user_id AND admin_reply IS NOT NULL AND viewed = 0");
if ($row = $result->fetch_assoc()) {
    $notifications['support_replies'] = $row['count'];
}

// Query to count unread general notifications
$result = $conn->query("SELECT COUNT(*) as count FROM user_notifications WHERE user_id = $user_id AND type = 'general' AND viewed = 0");
if ($row = $result->fetch_assoc()) {
    $notifications['general_notifications'] = $row['count'];
}

// Query to count unread payment reminders
$result = $conn->query("SELECT COUNT(*) as count FROM user_notifications WHERE user_id = $user_id AND type = 'payment_reminder' AND viewed = 0");
if ($row = $result->fetch_assoc()) {
    $notifications['payment_reminders'] = $row['count'];
}

// Update last checked time for policies
$conn->query("INSERT INTO user_activity (user_id, activity_type, last_checked) VALUES ($user_id, 'policy_view', NOW()) ON DUPLICATE KEY UPDATE last_checked = NOW()");

header('Content-Type: application/json');
echo json_encode($notifications);

$conn->close();
?>
