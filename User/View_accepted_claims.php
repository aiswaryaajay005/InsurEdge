<?php
session_start();
include '../Connection/Db_connection.php'; // Include the database connection file
include 'User_sidebar.php';
$user_id = $_SESSION['user_id']; // Assuming user is logged in

// Fetch accepted claims for the logged-in customer
$query = "SELECT * FROM claims WHERE user_id = ? AND status = 'Accepted'";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='claim-details'>";
        echo "<p><strong>Claim ID:</strong> " . $row['id'] . "</p>";
        echo "<p><strong>Policy ID:</strong> " . $row['policy_id'] . "</p>";
        echo "<p><strong>Claim Amount:</strong> $" . number_format($row['claim_amount'], 2) . "</p>";
        echo "<p><strong>Status:</strong> " . $row['status'] . "</p>";
        
        if ($row['status'] == 'Accepted' && ($row['bank_details_submitted'] == 0 || is_null($row['bank_details_submitted']))) {
            // Provide the link to fill in bank details
            echo "<p><a href='Fill_bank_details.php?claim_id=" . $row['id'] . "' class='action-link'>Click here to fill in your bank details</a></p>";
        } else {
            echo "<p>Your bank details have already been submitted or the claim is pending further review.</p>";
        }
        
        echo "</div>";
    }
} else {
    echo "<p>No accepted claims found.</p>";
}
?>

<!-- Add some style for this page -->
<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f9f9f9;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 1200px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    h2 {
        text-align: center;
        color: #333;
        margin-bottom: 30px;
    }

    .claim-details {
        background-color: #ffffff;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .claim-details p {
        font-size: 16px;
        color: #333;
        line-height: 1.6;
    }

    .claim-details p strong {
        color: #3498db;
    }

    .action-link {
        text-decoration: none;
        font-weight: bold;
        color: #3498db;
        border-bottom: 1px solid #3498db;
        padding-bottom: 2px;
        transition: all 0.3s ease;
    }

    .action-link:hover {
        color: #1d6fa5;
        border-bottom: 1px solid #1d6fa5;
    }

    .container p {
        text-align: center;
        font-size: 18px;
        color: #666;
    }
</style>
