<?php
session_start();
include '../Connection/Db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to mark notifications as read.");
}

$userId = $_SESSION['user_id'];
$notificationId = $_GET['notification_id'];

// Update the notification to mark it as read
$query = "UPDATE user_notifications SET is_read = 1 WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $notificationId, $userId);

if ($stmt->execute()) {
    header('Location: Userdashboard.php');  // Redirect back to the dashboard
    exit;
} else {
    echo "Error marking notification as read.";
}

$stmt->close();
$conn->close();
?>
