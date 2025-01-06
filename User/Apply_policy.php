
<?php
session_start();
include '../Connection/Db_connection.php'; // Include your database connection

// Check if the policyId is set in the URL
if (isset($_GET['policyId'])) {
    $policyId = $_GET['policyId'];

    // Get the user's email from the session
    $userEmail = $_SESSION['email'];  // Adjusted to match the session variable used in policyview.php

    // Prepare an SQL statement to insert the application
    $stmt = $conn->prepare("INSERT INTO applications (policy_id, email) VALUES (?, ?)");
    $stmt->bind_param("ss", $policyId, $userEmail); // Bind the parameters

    if ($stmt->execute()) {
        // Successfully inserted the application
        echo "<div class='success-message'>Application submitted successfully!</div>";
        echo "<a href='Userdashboard.php' class='dashboard-button'>Go to Dashboard</a>";
    } else {
        // Error handling
        echo "<div class='error-message'>Error: " . $stmt->error . "</div>";
    }

    // Close the statement
    $stmt->close();
} else {
    echo "<div class='error-message'>No policy ID provided.</div>";
}

// Close the database connection
$conn->close();
?>

<!-- Style the success message and button -->
<style>
    .success-message {
        font-size: 18px;
        color: green;
        margin-bottom: 20px;
    }

    .error-message {
        font-size: 18px;
        color: red;
        margin-bottom: 20px;
    }

    .dashboard-button {
        display: inline-block;
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-size: 16px;
    }

    .dashboard-button:hover {
        background-color: #45a049;
    }
</style>
