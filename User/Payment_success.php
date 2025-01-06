<?php
session_start();

// Check if the user has made a payment
if (!isset($_SESSION['policyId'])) {
    die("No payment information available. Please go back and try again.");
}

$paidAmount = isset($_SESSION['paidAmount']) && is_numeric($_SESSION['paidAmount']) ? (float)$_SESSION['paidAmount'] : 0;
$balanceAmount = isset($_SESSION['balanceAmount']) && is_numeric($_SESSION['balanceAmount']) ? (float)$_SESSION['balanceAmount'] : 0;
$interval_amount = $_SESSION['interval_amount'] ?? 0;

// Retrieve payment details from the session
$policyId = $_SESSION['policyId'];
$nextPaymentDate = $_SESSION['nextPaymentDate'];

// Optionally, you can also retrieve policy details from the database
include '../Connection/Db_connection.php';

$query = "SELECT * FROM policies WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $policyId);
$stmt->execute();
$result = $stmt->get_result();
$policy = $result->fetch_assoc();

if (!$policy) {
    die("Policy not found.");
}

// Display payment success message and details
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            color: #970747;
            font-size: 36px;
        }

        p {
            font-size: 18px;
            color: #555;
            line-height: 1.6;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            font-size: 16px;
            margin: 10px 0;
        }

        li strong {
            color: #333;
        }

        .payment-details, .policy-details {
            margin-top: 30px;
        }

        .button {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 20px;
            background-color: #970747;
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: lightcoral;
        }

        .card {
            background-color: #ecf0f1;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card h2 {
            margin-top: 0;
            color: #970747;
        }

        .card ul {
            padding-left: 20px;
        }

        .card ul li {
            font-size: 15px;
            line-height: 1.8;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Payment Successful</h1>
        <p>Thank you for your payment! Your transaction has been successfully processed. Below are the details of your payment and policy.</p>

        <!-- Payment Details Section -->
        <div class="payment-details card">
            <h2>Payment Details</h2>
            <ul>
                <li><strong>Policy ID:</strong> <?php echo htmlspecialchars($policyId); ?></li>
                <li><strong>Policy Name:</strong> <?php echo htmlspecialchars($policy['name']); ?></li>
                <li><strong>Amount Paid:</strong> ₹<?php echo number_format($paidAmount, 2); ?></li>
                <li><strong>Remaining Balance:</strong> ₹<?php echo number_format($balanceAmount, 2); ?></li>
                <li><strong>Next Payment Date:</strong> <?php echo htmlspecialchars($nextPaymentDate); ?></li>
                <li><strong>Payment Date:</strong> <?php echo date('Y-m-d'); ?></li>
            </ul>
        </div>

        <!-- Policy Information Section -->
        <div class="policy-details card">
            <h2>Your Policy Information</h2>
            <ul>
                <li><strong>Type:</strong> <?php echo htmlspecialchars($policy['type']); ?></li>
                <li><strong>Premium:</strong> ₹<?php echo number_format($policy['premium'], 2); ?></li>
                <li><strong>Coverage Duration:</strong> <?php echo htmlspecialchars($policy['duration']); ?> years</li>
            </ul>
        </div>

        <!-- Call to Action Button -->
        <a href="Policy_details.php" class="button">Go to Policy Details</a>
    </div>
</body>
</html>
