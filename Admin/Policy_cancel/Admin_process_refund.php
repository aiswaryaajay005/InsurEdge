<?php
session_start();
include '../../Connection/Db_connection.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_username'])) {
    die("Unauthorized access. Please log in as admin.");
}

// Get the cancellation request ID
$cancellation_request_id = $_GET['id'] ?? null;
if (!$cancellation_request_id) {
    die("Invalid request.");
}

// Fetch the cancellation request details
$query = "SELECT * FROM cancellation_requests WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $cancellation_request_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Cancellation request not found.");
}

$request = $result->fetch_assoc();
$user_id = $request['user_id'];
$refund_amount = $request['refund_amount'];
$policy_id = $request['policy_id']; // Get the specific policy_id from the cancellation request

// Fetch the bank details submitted by the user
$bank_details_query = "SELECT * FROM refund_bank_details WHERE cancellation_request_id = ?";
$bank_details_stmt = $conn->prepare($bank_details_query);
$bank_details_stmt->bind_param("i", $cancellation_request_id);
$bank_details_stmt->execute();
$bank_details_result = $bank_details_stmt->get_result();

if ($bank_details_result->num_rows == 0) {
    die("No bank details found for this cancellation request.");
}

$bank_details = $bank_details_result->fetch_assoc();

// Display bank details and refund amount
echo "<div class='container'>";
echo "<h3>Refund Amount: ₹" . number_format($refund_amount, 2) . "</h3>";
echo "<h4>User Bank Details:</h4>";
echo "<p>Bank Name: " . htmlspecialchars($bank_details['bank_name']) . "</p>";
echo "<p>Account Number: " . htmlspecialchars($bank_details['account_number']) . "</p>";
echo "<p>IFSC Code: " . htmlspecialchars($bank_details['ifsc_code']) . "</p>";
echo "<p>Account Holder Name: " . htmlspecialchars($bank_details['account_holder']) . "</p>";

echo "<form method='post'>
    <button type='submit' name='process_refund' class='btn-submit'>Pay Refund</button>
</form>";

if (isset($_POST['process_refund'])) {
    // Assuming the refund is processed successfully
    // Update the payments table to reflect the cancellation

    // Update the payments table: Mark the policy as canceled and set the status
    $updateStatusQuery = "UPDATE payments SET status = 'canceled', paid_amount = ?, next_payment_date = NULL WHERE policy_id = ? AND user_id = ?";
    $stmt = $conn->prepare($updateStatusQuery);
    $stmt->bind_param("dsi", $refund_amount, $policy_id, $user_id);
    $stmt->execute();

    // After updating payments, update the cancellation request status
    $update_request_query = "UPDATE cancellation_requests SET status = 'canceled' WHERE id = ?";
    $update_request_stmt = $conn->prepare($update_request_query);
    $update_request_stmt->bind_param("i", $cancellation_request_id);
    $update_request_stmt->execute();

    // Assuming refund is processed
    echo "<p>Refund of ₹" . number_format($refund_amount, 2) . " processed successfully. The user will no longer be able to make payments for this policy.</p>";
    echo '<a href="../Adminpage.php" class="btn-dashboard">Go back to the admin dashboard</a>';
}

echo "</div>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Refund Processing</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            box-sizing: border-box;
        }

        .container {
            background-color: #f9f9f9;
            color: #970747;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }

        h3 {
            color: #970747;
            font-size: 24px;
        }

        h4 {
            font-size: 20px;
            margin-top: 10px;
            color: #970747;
        }

        p {
            font-size: 16px;
            color: #333;
            margin-bottom: 10px;
        }

        .btn-submit {
            background-color: #970747;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .btn-submit:hover {
            background-color: #a61c5c;
        }

        .btn-dashboard {
            display: inline-block;
            margin-top: 20px;
            font-size: 16px;
            color: #970747;
            text-decoration: none;
            background-color: #f9f9f9;
            padding: 10px 15px;
            border-radius: 5px;
            border: 1px solid #970747;
            transition: background-color 0.3s;
        }

        .btn-dashboard:hover {
            background-color: #f1f1f1;
        }

        .btn-dashboard:focus {
            outline: none;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
</body>
</html>
