<?php
session_start();
include '../../Connection/Db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access. Please log in.");
}

$user_id = $_SESSION['user_id'];

// Fetch the cancellation request for the user
$cancellationQuery = "SELECT * FROM cancellation_requests WHERE user_id = ? ORDER BY created_at DESC LIMIT 1";
$cancellationStmt = $conn->prepare($cancellationQuery);
$cancellationStmt->bind_param("i", $user_id);
$cancellationStmt->execute();
$cancellationResult = $cancellationStmt->get_result();
$cancellation = $cancellationResult->fetch_assoc();

if (!$cancellation) {
    echo "<p>No cancellation request found.</p>";
} else {
    echo "<div class='cancellation-container'>";
    echo "<h2>Your Cancellation Request</h2>";
    echo "<p><strong>Policy ID:</strong> " . htmlspecialchars($cancellation['policy_id']) . "</p>";
    echo "<p><strong>Status:</strong> " . htmlspecialchars($cancellation['status']) . "</p>";
    echo "<p><strong>Refund Amount:</strong> $ " . number_format($cancellation['refund_amount'], 2) . "</p>";
    echo "<button id='viewDetailsBtn' class='view-details-btn'>View Details</button>";

    // Hidden message details
    echo "<div id='cancellationMessage' class='cancellation-message' style='display:none;'>";
    echo "<strong>Your Cancellation Request Details</strong><br>";
    echo "<p><strong>Status:</strong> " . htmlspecialchars($cancellation['status']) . "</p>";
    echo "<p><strong>Refund Amount:</strong> $ " . number_format($cancellation['refund_amount'], 2) . "</p>";
    echo "<p>" . htmlspecialchars($cancellation['message']) . "</p>";

    if (strtolower($cancellation['status']) === 'approved') {
        echo "<p><strong>Next Step:</strong> Please fill in your bank details for the refund.</p>";
        echo "<a href='../../User/cancel/User_fill_bank_details.php?id=" . htmlspecialchars($cancellation['id']) . "' class='bank-details-link'>Click here to fill in your bank details</a>";
    }
    echo "</div>";
    
    // Go to Dashboard button
    echo "<a href='../Userdashboard.php' class='btn-dashboard'>Go to Dashboard</a>";

    echo "</div>";
}
?>

<script>
    // Add an event listener for the button
    const viewDetailsBtn = document.getElementById('viewDetailsBtn');
    if (viewDetailsBtn) {
        viewDetailsBtn.addEventListener('click', function () {
            const messageDiv = document.getElementById('cancellationMessage');
            if (messageDiv.style.display === "none") {
                messageDiv.style.display = "block";
            } else {
                messageDiv.style.display = "none";
            }
        });
    } else {
        console.error("View Details button not found in the DOM.");
    }
</script>

<style>
    /* General container */
    .cancellation-container {
        font-family: Arial, sans-serif;
        max-width: 800px;
        margin: 30px auto;
        padding: 20px;
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    /* Header */
    .cancellation-container h2 {
        font-size: 24px;
        color: #333;
        text-align: center;
        margin-bottom: 20px;
    }

    /* Paragraphs */
    .cancellation-container p {
        font-size: 16px;
        color: #555;
        line-height: 1.6;
    }

    /* Button */
    .view-details-btn {
        padding: 10px 20px;
        background-color: #970747;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 15px;
        display: block;
        width: 100%;
    }

    .view-details-btn:hover {
        background-color: #970736;
    }

    /* Hidden message */
    .cancellation-message {
        border: 1px solid #ccc;
        padding: 15px;
        margin-top: 20px;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
    }

    .cancellation-message p {
        font-size: 16px;
        color: #333;
        margin-bottom: 10px;
    }

    .cancellation-message a {
        display: inline-block;
        padding: 10px 15px;
        background-color: #28a745;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        margin-top: 15px;
    }

    .cancellation-message a:hover {
        background-color: #218838;
    }

    /* Style for the refund amount */
    .cancellation-container strong {
        color: #333;
    }

    /* Go to Dashboard Button */
    .btn-dashboard {
        display: inline-block;
        padding: 10px 20px;
        background-color: #970747;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        margin-top: 20px;
        text-align: center;
        width: 100%;
    }

    .btn-dashboard:hover {
        background-color: #660f30;
    }

</style>
