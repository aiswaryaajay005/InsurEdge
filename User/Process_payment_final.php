<?php
session_start();
include '../Connection/Db_connection.php';

// Ensure required POST data and session variables are set
if (!isset($_POST['policyId'], $_POST['paymentMethod'], $_POST['interval_amount'], $_POST['paymentAmount'], $_SESSION['email'], $_SESSION['paymentFrequency'])) {
    die("Error: Required data is missing. Please check the form and session data.");
}

// Retrieve form data
$policyId = $_POST['policyId'];
$paymentMethod = $_POST['paymentMethod'];
$intervalAmount = (float)$_POST['interval_amount'];
$paidAmount = (float)$_POST['paymentAmount']; // Ensure numeric float
$userEmail = $_SESSION['email'];
$paymentFrequency = $_SESSION['paymentFrequency'];
$currentPaymentDate = date('Y-m-d');

// Fetch the user ID from the imsuser table using the email
$userQuery = "SELECT id FROM imsuser WHERE email = ?";
$userStmt = $conn->prepare($userQuery);
$userStmt->bind_param("s", $userEmail);
$userStmt->execute();
$userResult = $userStmt->get_result();
$user = $userResult->fetch_assoc();

if (!$user) {
    die("Error: User not found for the provided email.");
}
$userId = $user['id'];

// Fetch the policy details
$policyQuery = "SELECT premium, duration, type, coverage, description FROM policies WHERE id = ?";
$policyStmt = $conn->prepare($policyQuery);
$policyStmt->bind_param("s", $policyId);
$policyStmt->execute();
$policyResult = $policyStmt->get_result();
$policy = $policyResult->fetch_assoc();

if (!$policy) {
    die("Error: Policy not found for the provided ID.");
}

// Extract policy details
$totalPremium = (float)$policy['premium'];
$duration = (int)$policy['duration'];
$policyType = $policy['type'];
$coverage = $policy['coverage'];
$description = $policy['description'];

// Fetch total paid so far
$paymentQuery = "SELECT COALESCE(SUM(paid_amount), 0) AS total_paid_so_far FROM payments WHERE policy_id = ?";
$paymentStmt = $conn->prepare($paymentQuery);
$paymentStmt->bind_param("s", $policyId);
$paymentStmt->execute();
$paymentResult = $paymentStmt->get_result();
$paymentData = $paymentResult->fetch_assoc();
$totalPaidSoFar = (float)$paymentData['total_paid_so_far'];

// Calculate updated amounts
$updatedPaidSoFar = $totalPaidSoFar + $paidAmount;
$balanceAmount = $totalPremium - $updatedPaidSoFar;
$nextPaymentDate = calculateNextPaymentDate($currentPaymentDate, $paymentFrequency);
$startDate = $currentPaymentDate;
$endDate = date('Y-m-d', strtotime("+{$duration} years", strtotime($startDate)));

function calculateNextPaymentDate($currentDate, $frequency) {
    $timestamp = strtotime($currentDate);
    switch ($frequency) {
        case 'Monthly': 
            return date('Y-m-d', strtotime("+1 month", $timestamp));
        case 'Quarterly': 
            return date('Y-m-d', strtotime("+3 months", $timestamp));
        case 'Yearly': 
            return date('Y-m-d', strtotime("+1 year", $timestamp));
        default: 
            throw new Exception("Invalid payment frequency.");
    }
}

// Check if the policy already exists in the payments table
$policyExistsQuery = "SELECT COUNT(*) FROM payments WHERE policy_id = ? AND user_id = ?";
$policyExistsStmt = $conn->prepare($policyExistsQuery);
$policyExistsStmt->bind_param("si", $policyId, $userId);
$policyExistsStmt->execute();
$policyExistsCount = $policyExistsStmt->get_result()->fetch_row()[0];

if ($policyExistsCount == 0) {
    // Insert a new entry into the payments table
    $insertPaymentQuery = "INSERT INTO payments (policy_id, payment_method, paid_amount, payment_date, next_payment_date, payment_frequency, user_email, user_id, start_date, end_date, interval_amount, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')";
    $insertPaymentStmt = $conn->prepare($insertPaymentQuery);
    $insertPaymentStmt->bind_param("ssdsdsssssd", $policyId, $paymentMethod, $paidAmount, $currentPaymentDate, $nextPaymentDate, $paymentFrequency, $userEmail, $userId, $startDate, $endDate, $intervalAmount);
    
    if (!$insertPaymentStmt->execute()) {
        die("Error inserting payment details: " . $insertPaymentStmt->error);
    }
    
    // Insert into new tables: policy_details and payment_details
    $insertPolicyQuery = "INSERT INTO policy_details (policy_id, user_id, premium, coverage, policy_type, duration, description, user_email) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $insertPolicyStmt = $conn->prepare($insertPolicyQuery);
    $insertPolicyStmt->bind_param("siississ", $policyId, $userId, $totalPremium, $coverage, $policyType, $duration, $description, $userEmail);
    $insertPolicyStmt->execute();

    $insertPaymentDetailsQuery = "INSERT INTO payment_details (policy_id, user_id, paid_amount, payment_date, next_payment_date, payment_method, payment_frequency, balance, user_email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $insertPaymentDetailsStmt = $conn->prepare($insertPaymentDetailsQuery);
    $insertPaymentDetailsStmt->bind_param("sdsdsssss", $policyId, $userId, $paidAmount, $currentPaymentDate, $nextPaymentDate, $paymentMethod, $paymentFrequency, $balanceAmount, $userEmail);
    $insertPaymentDetailsStmt->execute();

} else {
    // Update the existing entry in the payments table
    $updatePaymentQuery = "UPDATE payments SET paid_amount = paid_amount + ?, payment_date = ?, next_payment_date = ?, user_email = ?, status = 'active' WHERE policy_id = ? AND user_id = ?";
    $updatePaymentStmt = $conn->prepare($updatePaymentQuery);
    $updatePaymentStmt->bind_param("dssssi", $paidAmount, $currentPaymentDate, $nextPaymentDate, $userEmail, $policyId, $userId);

    if (!$updatePaymentStmt->execute()) {
        die("Error updating payment details: " . $updatePaymentStmt->error);
    }

    // Insert new details for payment tracking
    $updatePaymentDetailsQuery = "INSERT INTO payment_details (policy_id, user_id, paid_amount, payment_date, next_payment_date, payment_method, payment_frequency, balance, user_email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $updatePaymentDetailsStmt = $conn->prepare($updatePaymentDetailsQuery);
    $updatePaymentDetailsStmt->bind_param("sdsdsssss", $policyId, $userId, $paidAmount, $currentPaymentDate, $nextPaymentDate, $paymentMethod, $paymentFrequency, $balanceAmount, $userEmail);
    $updatePaymentDetailsStmt->execute();
}

// Redirect to success page
$_SESSION['policyId'] = $policyId;
$_SESSION['paidAmount'] = $paidAmount;
$_SESSION['balanceAmount'] = $balanceAmount;
$_SESSION['nextPaymentDate'] = $nextPaymentDate;
$_SESSION['interval_amount'] = $intervalAmount;
header("Location: Payment_success.php");
exit();
?>
