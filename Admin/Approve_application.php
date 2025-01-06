<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'header.php'; // Path to the header file
include '../Connection/Db_connection.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $application_id = $_POST['id'];

    // Validate that the application ID is a valid integer
    if (!filter_var($application_id, FILTER_VALIDATE_INT)) {
        echo "Invalid application ID.";
        exit();
    }
    
    // Fetch the application details including email and policy_id
    $query = "SELECT email, policy_id FROM applications WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $application_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $application = $result->fetch_assoc();
        $email_id = $application['email'];
        $policy_id = $application['policy_id']; // policy_id is varchar(10)

        // Insert into the approved_policies table
        $insertQuery = "INSERT INTO approved_policies (email_id, policy_id, status) VALUES (?, ?, 'Approved')";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("ss", $email_id, $policy_id); // Bind policy_id as string
        if (!$insertStmt->execute()) {
            echo "Error approving policy: " . $insertStmt->error;
            exit();
        }

        // Update the status in the applications table
        $updateQuery = "UPDATE applications SET status = 'Approved' WHERE id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("i", $application_id);
        if (!$updateStmt->execute()) {
            echo "Error updating application status: " . $updateStmt->error;
            exit();
        }

        // Redirect to the accepted policies page
        header("Location: Accepted_policies.php");
        exit();
    } else {
        echo "Application not found.";
        exit();
    }
}
?>


