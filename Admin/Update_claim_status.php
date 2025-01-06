<?php
session_start();
include '../Connection/Db_connection.php';

// Ensure user is admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: Adminlog.html"); // Redirect to admin login page if not logged in
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['claim_id']) && isset($_POST['status'])) {
    $claimId = $_POST['claim_id'];
    $status = $_POST['status'];

    // Update the claim status in the database
    $updateQuery = "UPDATE claims SET status = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("si", $status, $claimId);

    if ($updateStmt->execute()) {
        header("Location: Admin_claims.php"); // Redirect back to claims dashboard
        exit();
    } else {
        die("Error updating claim status: " . $updateStmt->error);
    }
} else {
    die("Invalid request.");
}
?>
