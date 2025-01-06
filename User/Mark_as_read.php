<?php
session_start();
include './Connection/Db_connection.php'; // Include your database connection

if (isset($_GET['id'])) {
    $notification_id = $_GET['id'];
    $stmt = $conn->prepare("UPDATE notifications SET read_status = 1 WHERE id = ? AND user_email = ?");
    $stmt->bind_param("is", $notification_id, $_SESSION['user_email']);
    $stmt->execute();
    $stmt->close();
    echo "Notification marked as read.";
}
?>