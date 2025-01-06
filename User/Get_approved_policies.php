<?php
session_start(); // Start the session to access session variables
include '../Connection/Db_connection.php'; // Include database connection

header('Content-Type: application/json');

// Get the user's email from the session
if (!isset($_SESSION['email'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$user_email = $_SESSION['email']; 

$query = "SELECT COUNT(*) as count FROM applications WHERE email = ? AND status = 'Approved'";
$stmt = $conn->prepare($query);

// Check if the query preparation is successful
if (!$stmt) {
    echo json_encode(['error' => 'Failed to prepare query']);
    exit;
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

echo json_encode(['count' => $result['count']]);
?>
