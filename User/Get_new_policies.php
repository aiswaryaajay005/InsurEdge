<?php 
session_start(); // Start the session
include '../Connection/Db_connection.php'; // Include database connection

header('Content-Type: application/json');

// Get the user's email from the session
if (!isset($_SESSION['email'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$user_email = $_SESSION['email']; 

// SQL query to count new policies (policies not yet applied for by the user)
$query = "
    SELECT COUNT(*) as count 
    FROM policies p
    WHERE p.id NOT IN (  -- Use 'id' instead of 'policy_id' as per your table structure
        SELECT a.policy_id 
        FROM applications a 
        WHERE a.email = ?
    )";

$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(['error' => 'Failed to prepare query']);
    exit;
}

$stmt->bind_param("s", $user_email); // Bind the email parameter to the query
$stmt->execute();
$result = $stmt->get_result();

if ($result === false) {
    echo json_encode(['error' => 'Failed to execute query']);
    exit;
}

$data = $result->fetch_assoc();
if ($data) {
    echo json_encode(['count' => $data['count']]);
} else {
    echo json_encode(['count' => 0]); // No new policies found
}

?>
