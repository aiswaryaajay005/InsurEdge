<?php
session_start();
include '../Connection/Db_connection.php'; // Include database connection

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate the inputs
    $policyId = filter_var(trim($_POST['policyId']), FILTER_SANITIZE_STRING); // Keep as string for VARCHAR(10)
    $policyName = filter_var(trim($_POST['policyName']), FILTER_SANITIZE_STRING);
    $policyType = filter_var(trim($_POST['policyType']), FILTER_SANITIZE_STRING);
    $policyDuration = intval($_POST['policyDuration']); // Ensure duration is an integer
    $policyPremium = floatval($_POST['policyPremium']); // Ensure premium is a float
    $policyCoverage = floatval($_POST['policyCoverage']); // Ensure coverage is a float
    $policyDescription = trim($_POST['policyDescription']); // Just trim to avoid sanitizing the description too much

    // Check if the policy ID is unique before insertion
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM policies WHERE id = ?");
    $checkStmt->bind_param("s", $policyId);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count > 0) {
        // Policy ID already exists
        echo "<script>alert('Policy ID already exists. Please enter a unique ID.');</script>";
    } else {
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO policies (id, name, type, duration, premium, coverage, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssidss", $policyId, $policyName, $policyType, $policyDuration, $policyPremium, $policyCoverage, $policyDescription);
        
        // Execute the statement
        if ($stmt->execute()) {
            echo "<script>alert('Policy added successfully! Policy ID: " . htmlspecialchars($policyId) . "');</script>";
            echo "<script>window.location.href = 'View_policies.php';</script>"; // Redirect to a success page
        } else {
            // Error inserting the policy
            echo "<script>alert('Error adding policy: " . htmlspecialchars($stmt->error) . "');</script>";
        }
        
        // Close the statement
        $stmt->close();
    }
}

// Close the database connection
$conn->close();
?>

