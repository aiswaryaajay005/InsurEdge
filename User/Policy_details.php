<?php
session_start();
include 'User_sidebar.php';

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    die("No user information available. Please log in.");
}

// Retrieve the logged-in user's email
$email = $_SESSION['email'];

// Connect to the database
include '../Connection/Db_connection.php';

// Step 1: Get the user_id using the email
$userQuery = "SELECT id FROM imsuser WHERE email = ?";
$userStmt = $conn->prepare($userQuery);
$userStmt->bind_param("s", $email);
$userStmt->execute();
$userResult = $userStmt->get_result();
$user = $userResult->fetch_assoc();

if (!$user) {
    die("User not found.");
}

$user_id = $user['id']; // Get user ID from the result

// Step 2: Fetch all payment details using the user_id
$paymentQuery = "SELECT *, status FROM payments WHERE policy_id IN (SELECT id FROM policies WHERE user_id = ?) ORDER BY payment_date DESC";
$paymentStmt = $conn->prepare($paymentQuery);
$paymentStmt->bind_param("s", $user_id);
$paymentStmt->execute();
$paymentResult = $paymentStmt->get_result();

if ($paymentResult->num_rows === 0) {
    die("No payment information available for this user.");
}

$_SESSION['from_payment_report'] = true;

// Step 3: Loop through all payment details and fetch corresponding policy information
$policies = [];
while ($paymentDetails = $paymentResult->fetch_assoc()) {
    $policyId = $paymentDetails['policy_id'];

    // Fetch policy details using the policy_id from the payment details
    $policyQuery = "SELECT * FROM policies WHERE id = ?";
    $policyStmt = $conn->prepare($policyQuery);
    $policyStmt->bind_param("s", $policyId);
    $policyStmt->execute();
    $policyResult = $policyStmt->get_result();
    $policy = $policyResult->fetch_assoc();

    if ($policy) {
        // Check if the policy is canceled
        $isCancelled = ($paymentDetails['status'] === 'canceled');

        // Check if a claim exists for this policy and user
        $claimQuery = "SELECT status FROM claims WHERE policy_id = ? AND user_id = ? ORDER BY submitted_at DESC LIMIT 1";
        $claimStmt = $conn->prepare($claimQuery);
        $claimStmt->bind_param("ss", $policyId, $user_id);
        $claimStmt->execute();
        $claimResult = $claimStmt->get_result();
        $claim = $claimResult->fetch_assoc();

        // Set claim status
        $claimStatus = $claim ? $claim['status'] : 'No Claim Submitted';

        // Store policy and payment details in an array
        $policies[] = [
            'policy' => $policy,
            'payment' => $paymentDetails,
            'isCancelled' => $isCancelled,
            'claimStatus' => $claimStatus
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Policy Details</title>
    <link rel="stylesheet" href="styles.css"> 
    <style>
        /* Your previous styles */
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #f8bbd0, #f50057);
            margin: 0;
            padding: 0;
            color: #ffffff;
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

        ul {
            list-style-type: none;
            padding: 0;
        }

        ul li {
            margin-bottom: 8px;
            font-size: 16px;
            color: #333;
        }

        .policy-container {
            margin-bottom: 30px;
            padding: 20px;
            border: 2px solid #f48fb1;
            border-radius: 12px;
            background: #f8bbd0;
        }

        .policy-container h2 {
            color: #970747;
        }

        .overdue {
            color: #d32f2f;
        }

        .cancelled {
            color: #f44336;
            font-weight: bold;
        }

        .button {
            display: inline-block;
            padding: 12px 18px;
            margin-top: 10px;
            color: #ffffff;
            background: linear-gradient(to right, #e91e63, #c2185b);
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            text-align: center;
            transition: background 0.3s ease, transform 0.3s ease;
            font-size: 14px;
        }

        .button:hover {
            background: linear-gradient(to right, #c2185b, #d50057);
            transform: scale(1.05);
        }

        .button.disabled {
            background: #f8bbd0;
            color: #aaa;
            pointer-events: none;
            cursor: default;
        }

        li strong {
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
        <h1>Policy Details</h1>

        <?php foreach ($policies as $policyData): ?>
            <div class="policy-container">
                <h2>Policy Information:</h2>
                <ul>
                    <li><strong>Policy ID:</strong> <?php echo htmlspecialchars($policyData['policy']['id']); ?></li>
                    <li><strong>Policy Name:</strong> <?php echo htmlspecialchars($policyData['policy']['name']); ?></li>
                    <li><strong>Policy Type:</strong> <?php echo htmlspecialchars($policyData['policy']['type']); ?></li>
                    <li><strong>Premium:</strong> ₹<?php echo number_format($policyData['policy']['premium'], 2); ?></li>
                    <li><strong>Coverage Duration:</strong> <?php echo htmlspecialchars($policyData['policy']['duration']); ?> years</li>
                </ul>

                <h2>Payment Information:</h2>
                <ul>
                    <li><strong>Amount Paid:</strong> ₹<?php echo number_format($policyData['payment']['paid_amount'], 2); ?></li>
                    <li><strong>Balance to be Paid:</strong> ₹<?php echo number_format($policyData['policy']['premium'] - $policyData['payment']['paid_amount'], 2); ?></li>
                    <li><strong>Last Payment Date:</strong> <?php echo htmlspecialchars($policyData['payment']['payment_date']); ?></li>
                    <li><strong>Next Payment Date:</strong> <?php echo htmlspecialchars($policyData['payment']['next_payment_date']); ?></li>
                    <li><strong>Payment Frequency:</strong> <?php echo htmlspecialchars($policyData['payment']['payment_frequency']); ?></li>
<li><strong>Start Date:</strong> <?php echo htmlspecialchars($policyData['payment']['start_date']); ?></li>
<li><strong>End Date:</strong> <?php echo htmlspecialchars($policyData['payment']['end_date']); ?></li>
                    <li><strong>Status:</strong> <?php echo htmlspecialchars($policyData['payment']['status']); ?></li>
                </ul>

                <h2>Claim Information:</h2>
                <ul>
                    <li><strong>Claim Status:</strong> <?php echo htmlspecialchars($policyData['claimStatus']); ?></li>
                </ul>

               <!-- Submit Claim Button -->
<a href="Claimsform.php?policy_id=<?php echo urlencode($policyData['policy']['id']); ?>&policy_name=<?php echo urlencode($policyData['policy']['name']); ?>&email=<?php echo urlencode($email); ?>" 
   class="button <?php echo $policyData['isCancelled'] || $policyData['claimStatus'] == 'Paid' ? 'disabled' : ''; ?>">
   Submit Claim
</a>

<!-- Payment Button -->
<a href="Payment_report.php" class="button">
    Make Payments
</a>

<!-- Cancellation Status and Refund Details -->
<?php if ($policyData['isCancelled']): ?>
    <div class="cancelled">This policy has been canceled.</div>
    <a href="../User/cancel/View_refund_details.php?policy_id=<?php echo urlencode($policyData['policy']['id']); ?>" class="button">
        View Refund Details
    </a>
<?php else: ?>
    <!-- Disable Cancellation Button if Claim Submitted -->
    <?php if ($policyData['claimStatus'] == 'Paid'): ?>
        <a href="#" class="button disabled">Cancellation Disabled (Claim Submitted)</a>
    <?php else: ?>
        <a href="../User/cancel/Policy_cancellation_request_form.php?policy_id=<?php echo urlencode($policyData['policy']['id']); ?>&policy_name=<?php echo urlencode($policyData['policy']['name']); ?>&email=<?php echo urlencode($email); ?>" 
           class="button">
           Request Cancellation
        </a>
    <?php endif; ?>

    <!-- Disable Claim Button if Claim Already Accepted -->
    <?php if ($policyData['claimStatus'] == 'Paid'): ?>
        <a href="#" class="button disabled">Claim Already Accepted</a>
    <?php endif; ?>
<?php endif; ?>

            </div>
        <?php endforeach; ?>
    </div>

    <div class="footer">
        <p>&copy; 2024 InsurEdge. All rights reserved.</p>
    </div>
</body>
</html>
