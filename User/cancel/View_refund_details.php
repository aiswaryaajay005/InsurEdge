<?php
session_start();

// Check if the user has an email in the session
if (!isset($_SESSION['email'])) {
    die("No user information available. Please log in.");
}

// Retrieve the user's email from the session
$email = $_SESSION['email'];

// Connect to the database
include '../../Connection/Db_connection.php';

// Step 1: Retrieve the policy_id from the URL
if (!isset($_GET['policy_id'])) {
    die("Policy ID is missing.");
}

$policyId = $_GET['policy_id'];

// Step 2: Get the policy details
$policyQuery = "SELECT * FROM policies WHERE id = ?";
$policyStmt = $conn->prepare($policyQuery);
$policyStmt->bind_param("s", $policyId);
$policyStmt->execute();
$policyResult = $policyStmt->get_result();

if ($policyResult->num_rows === 0) {
    die("Policy not found.");
}

$policy = $policyResult->fetch_assoc();

// Step 3: Get the cancellation and refund details (if any)
$refundQuery = "
    SELECT cr.*, rbd.account_holder, rbd.account_number, rbd.bank_name, rbd.ifsc_code 
    FROM cancellation_requests cr 
    LEFT JOIN refund_bank_details rbd ON cr.id = rbd.cancellation_request_id
    WHERE cr.policy_id = ? 
    AND (cr.status = 'approved' OR cr.status = 'canceled')
";
$refundStmt = $conn->prepare($refundQuery);
$refundStmt->bind_param("s", $policyId);
$refundStmt->execute();
$refundResult = $refundStmt->get_result();

// Check if refund exists for this policy
$refund = $refundResult->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund Details</title>
    <link rel="stylesheet" href="styles.css"> 
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #f8bbd0, #f50057);
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            border-radius: 12px;
        }
        h1, h2, h3 {
            color: #970747;
        }
        .policy-container {
            margin-bottom: 30px;
            padding: 20px;
            border: 2px solid #970747;
            border-radius: 12px;
            background: #f8bbd0;
        }
        .refund-details {
             background: #f8bbd0;
            padding: 10px;
            border-radius: 8px;
            margin-top: 15px;
            border: 1px solid #970747;
        }
        .refund-details strong {
            color: #970747;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            color: #546e7a;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Refund Details for Canceled Policy</h1>

        <div class="policy-container">
            <h2>Policy Information:</h2>
            <ul>
                <li><strong>Policy ID:</strong> <?php echo htmlspecialchars($policy['id']); ?></li>
                <li><strong>Policy Name:</strong> <?php echo htmlspecialchars($policy['name']); ?></li>
                <li><strong>Policy Type:</strong> <?php echo htmlspecialchars($policy['type']); ?></li>
                <li><strong>Premium:</strong> $<?php echo number_format($policy['premium'], 2); ?></li>
                <li><strong>Coverage Duration:</strong> <?php echo htmlspecialchars($policy['duration']); ?> years</li>
            </ul>
        </div>

        <?php if ($refund): ?>
            <div class="refund-details">
                <h3>Refund Information:</h3>
                <ul>
                    <li><strong>Refund Amount:</strong> $<?php echo number_format($refund['refund_amount'], 2); ?></li>
                    <li><strong>Refund Status:</strong> <?php echo htmlspecialchars($refund['status']); ?></li>
                    <li><strong>Refund Date:</strong> <?php echo htmlspecialchars($refund['cancellation_date']); ?></li>
                    <li><strong>Bank Details Provided:</strong>
                        <?php 
                        // Display bank details if available
                        if ($refund['account_holder'] && $refund['account_number']) {
                            echo htmlspecialchars($refund['account_holder']) . "<br>";
                            echo "Account Number: " . htmlspecialchars($refund['account_number']) . "<br>";
                            echo "Bank Name: " . htmlspecialchars($refund['bank_name']) . "<br>";
                            echo "IFSC Code: " . htmlspecialchars($refund['ifsc_code']);
                        } else {
                            echo "No bank details provided.";
                        }
                        ?>
                    </li>
                    <li><strong>Admin Remarks:</strong> <?php echo nl2br(htmlspecialchars($refund['message'])); ?></li>
                </ul>
            </div>
        <?php else: ?>
            <p>No refund details found for this canceled policy.</p>
        <?php endif; ?>

        <div class="footer">
            <p>&copy; 2024 InsurEdge. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
