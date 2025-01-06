<?php
session_start();
include '../Connection/Db_connection.php'; // Include the database connection

// Initialize variables
$updateSuccess = false; // To track if the update was successful
$message = ""; // To hold the message for user feedback
$policyId = ""; // To hold the original policy ID

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $newPolicyId = $_POST['policyId'];
    $policyName = $_POST['policyName'];
    $policyType = $_POST['policyType'];
    $policyDuration = $_POST['policyDuration'];
    $policyPremium = $_POST['policyPremium'];
    $policyCoverage = $_POST['policyCoverage'];
    $policyDescription = $_POST['policyDescription'];
    
    // Assuming you're passing the original policy ID through a hidden input in your form
    $policyId = $_POST['originalPolicyId']; // Capture the original policy ID

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("UPDATE policies SET id = ?, name = ?, type = ?, duration = ?, premium = ?, coverage = ?, description = ? WHERE id = ?");

    if ($stmt) {
        // Bind parameters, including the original policy ID for the WHERE clause
        $stmt->bind_param("ssssddss", $newPolicyId, $policyName, $policyType, $policyDuration, $policyPremium, $policyCoverage, $policyDescription, $policyId);

        // Execute the statement
        if ($stmt->execute()) {
            // Check if any rows were affected
            if ($stmt->affected_rows > 0) {
                $updateSuccess = true; // Mark update as successful
                $message = "Policy updated successfully. New Policy ID: " . htmlspecialchars($newPolicyId);
            } else {
                $message = "No changes made. Please check if the values are different.";
            }
        } else {
            // Capture any error during the execution
            $message = "Error updating policy: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        // Capture error if preparing the statement fails
        $message = "Error preparing statement: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Policy</title>
    <link rel="stylesheet" href="../CSS/Updatestyle.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="container">
        <h2>Update Policy</h2>
        <p><?php echo $message; ?></p> <!-- Display the message to the user -->

        <!-- Button to go back to the main policies page -->
        <button onclick="window.location.href='view_policies.php'">Go to Policies</button>
    </div>
</body>
</html>

