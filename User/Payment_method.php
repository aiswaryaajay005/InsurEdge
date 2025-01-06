<?php
session_start();

// Check if the user accessed this page from an allowed source
if (!isset($_SESSION['from_payment_report']) || !$_SESSION['from_payment_report']) {
    die("Access Denied: You cannot access this page directly.");
}

// Clear the flag to prevent direct URL access later
unset($_SESSION['from_payment_report']);  // Clear the flag

// Retrieve and validate session variables
$paymentMethod = $_SESSION['paymentMethod'] ?? 'Unknown Method'; // Ensure correct session key
  // Make sure it's from session
$policyId = $_SESSION['policyId'] ?? '';
$interval_amount = $_SESSION['interval_amount'] ?? 0;
$paymentAmount = $_SESSION['paymentAmount'] ?? 0;
$paymentFrequency = $_SESSION['paymentFrequency'] ?? '';

// Check if session variables are missing or invalid
if (empty($policyId) || $interval_amount == 0) {
    die("Error: Payment details are not available for this policy.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment - <?php echo htmlspecialchars($paymentMethod); ?></title>
    <link rel="stylesheet" href="../CSS/Paymentmethodstyle.css">
</head>
<body>
    <div class="container">
        <h2>Complete Your Payment</h2>
        <p>Payment Method: <?php echo htmlspecialchars($paymentMethod); ?></p>
        <p>Amount to be Paid: $<?php echo number_format($interval_amount, 2); ?></p>

        <form action="Process_payment_final.php" method="POST">
            <input type="hidden" name="policyId" value="<?php echo htmlspecialchars($policyId); ?>">
            <input type="hidden" name="paymentMethod" value="<?php echo htmlspecialchars($paymentMethod, ENT_QUOTES, 'UTF-8');?>">
            <input type="hidden" name="paymentAmount" value="<?php echo htmlspecialchars($paymentAmount); ?>">
            <input type="hidden" name="interval_amount" value="<?php echo htmlspecialchars($interval_amount); ?>">
            <input type="hidden" name="paymentFrequency" value="<?php echo htmlspecialchars($paymentFrequency); ?>">

            <!-- Payment forms based on method -->
            <?php if ($paymentMethod === 'Credit Card' || $paymentMethod === 'Debit Card'): ?>
            <div>
                <label for="cardNumber">Card Number:</label>
                <input type="text" name="cardNumber" required>
            </div>
            <div>
                <label for="expiryDate">Expiry Date (MM/YY):</label>
                <input type="text" name="expiryDate" required>
            </div>
            <div>
                <label for="cvv">CVV:</label>
                <input type="text" name="cvv" required>
            </div>
        <?php elseif ($paymentMethod === 'PayPal'): ?>
            <div>
                <label for="paypalEmail">PayPal Email:</label>
                <input type="email" name="paypalEmail" required>
            </div>
        <?php elseif ($paymentMethod === 'Bank Transfer'): ?>
            <div>
                <label for="bankAccount">Bank Account Number:</label>
                <input type="text" name="bankAccount" required>
            </div>
            <div>
                <label for="bankName">Bank Name:</label>
                <input type="text" name="bankName" required>
            </div>
        <?php endif; ?>

            <button type="submit">Pay $<?php echo number_format($interval_amount, 2); ?></button>
        </form>
    </div>
</body>
</html>
