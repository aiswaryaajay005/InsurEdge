<?php
session_start();
include '../../Connection/Db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access. Please log in.");
}

$user_id = $_SESSION['user_id'];
$cancellation_request_id = $_POST['request_id'] ?? null;
$account_holder = $_POST['account_holder'] ?? null;
$account_number = $_POST['account_number'] ?? null;
$bank_name = $_POST['bank_name'] ?? null;
$ifsc_code = $_POST['ifsc_code'] ?? null;

if (!$cancellation_request_id || !$account_holder || !$account_number || !$bank_name || !$ifsc_code) {
    die("All fields are required.");
}


// Store the bank details in the database
$insertQuery = "INSERT INTO refund_bank_details (user_id, cancellation_request_id, account_holder, account_number, bank_name, ifsc_code) 
                VALUES (?, ?, ?, ?, ?, ?)";
$insertStmt = $conn->prepare($insertQuery);
$insertStmt->bind_param("iissss", $user_id, $cancellation_request_id, $account_holder, $account_number, $bank_name, $ifsc_code);
$insertStmt->execute();

// Update cancellation request status to "Bank details submitted"
$updateQuery = "UPDATE cancellation_requests SET status = 'Bank details submitted' WHERE id = ?";
$updateStmt = $conn->prepare($updateQuery);
$updateStmt->bind_param("i", $cancellation_request_id);
$updateStmt->execute();

echo "<div class='success-message'>";
echo "<p>Your bank details have been successfully submitted. The admin will process your refund soon.</p>";
echo '<a href="../Userdashboard.php" class="dashboard-link">Go back to your dashboard</a>';
echo "</div>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bank Details Submitted</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            box-sizing: border-box;
        }

        .success-message {
            background-color: #e4f7e2;
            color: #38761d;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 450px;
        }

        .success-message p {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
        }

        .dashboard-link {
            display: inline-block;
            margin-top: 15px;
            font-size: 16px;
            color: #970747;
            text-decoration: none;
            background-color: #e4f7e2;
            padding: 10px 15px;
            border-radius: 5px;
            border: 1px solid #970747;
            transition: background-color 0.3s;
        }

        .dashboard-link:hover {
            background-color: #d4f1d0;
            color: #6b1c39;
        }

        .dashboard-link:focus {
            outline: none;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
</body>
</html>
