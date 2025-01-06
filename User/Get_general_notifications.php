<?php
session_start(); // Start the session to access session variables
include '../Connection/Db_connection.php'; // Include database connection

header('Content-Type: application/json');

// Check if user_id is set in the session
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id']; // Get the user's ID from the session

$query = "SELECT COUNT(*) as count FROM user_notifications WHERE user_id = ? AND notification_type = 'support_reply' AND is_read = 0";
$stmt = $conn->prepare($query);

// Check if the query preparation is successful
if (!$stmt) {
    echo json_encode(['error' => 'Failed to prepare query']);
    exit;
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

echo json_encode(['count' => $result['count']]);
?>
