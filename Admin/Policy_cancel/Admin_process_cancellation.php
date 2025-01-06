<?php
session_start();
include '../../Connection/Db_connection.php';

if (!isset($_SESSION['admin_username'])) {
    die("Unauthorized access. Please log in as admin.");
}

$request_id = $_GET['id'] ?? null;
$action = $_GET['action'] ?? null;

if (!$request_id || !$action) {
    die("Invalid request.");
}

$requestQuery = "SELECT * FROM cancellation_requests WHERE id = ?";
$requestStmt = $conn->prepare($requestQuery);
$requestStmt->bind_param("i", $request_id);
$requestStmt->execute();
$requestResult = $requestStmt->get_result();
$request = $requestResult->fetch_assoc();

if (!$request) {
    die("Cancellation request not found.");
}

$policy_id = $request['policy_id'];
$user_id = $request['user_id'];

$paymentQuery = "SELECT * FROM payments WHERE policy_id = ? AND user_id = ?";
$paymentStmt = $conn->prepare($paymentQuery);
$paymentStmt->bind_param("si", $policy_id, $user_id);
$paymentStmt->execute();
$paymentResult = $paymentStmt->get_result();
$payment = $paymentResult->fetch_assoc();

if (!$payment) {
    die("No payment details found for this policy.");
}

if ($action === 'approve') {
    // Calculate refund amount
    $policy_duration_years = $payment['end_date'] 
        ? (new DateTime($payment['end_date']))->diff(new DateTime($payment['start_date']))->y 
        : 1;

    $amount_paid = $payment['paid_amount'];
    $current_date = new DateTime();
    $start_date = new DateTime($payment['start_date']);
    $months_used = $current_date->diff($start_date)->m + ($current_date->diff($start_date)->y * 12);

    $remaining_months = max(0, $policy_duration_years * 12 - $months_used);
    $refund_amount = ($remaining_months / ($policy_duration_years * 12)) * $amount_paid;

    if ($months_used <= 6) {
        $refund_amount -= $refund_amount * 0.10;
    }

    // Update cancellation request status to approved
    $updateQuery = "UPDATE cancellation_requests SET refund_amount = ?, status = 'approved', message = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $message = "Your cancellation request has been approved. Refund amount: $" . number_format($refund_amount, 2);
    $updateStmt->bind_param("dsi", $refund_amount, $message, $request_id);
    $updateStmt->execute();

    // Redirect back to the review page with a success message
    $_SESSION['success_message'] = "Cancellation request (ID: $request_id) has been approved successfully.";
    header("Location: Admin_review_cancellations.php");
    exit;

} elseif ($action === 'reject') {
    // Update cancellation request status to rejected
    $updateQuery = "UPDATE cancellation_requests SET status = 'rejected', message = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $message = "Your cancellation request has been rejected.";
    $updateStmt->bind_param("si", $message, $request_id);
    $updateStmt->execute();

    // Redirect back to the review page with a success message
    $_SESSION['success_message'] = "Cancellation request (ID: $request_id) has been rejected.";
    header("Location: Admin_review_cancellations.php");
    exit;
} else {
    die("Invalid action.");
}
