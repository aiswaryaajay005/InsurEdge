<?php
include '../Connection/Db_connection.php'; // Database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $claim_id = $_POST['claim_id'];
    $user_id = $_POST['user_id'];
    $payout_amount = $_POST['payout_amount'];
    $payment_method = $_POST['payment_method'];

    // Fetch bank details ID for the user
    $bankQuery = "SELECT id FROM bank_details WHERE user_id = $user_id";
    $bankResult = $conn->query($bankQuery);
    $bankDetails = $bankResult->fetch_assoc();
    $bank_details_id = $bankDetails['id'];

    // Insert payout record into payouts table
    $payoutQuery = "INSERT INTO payouts (claim_id, user_id, payout_amount, payment_method, bank_details_id) 
                    VALUES ($claim_id, $user_id, $payout_amount, '$payment_method', $bank_details_id)";
    $conn->query($payoutQuery);

    // Update claim status to 'Paid'
    $updateClaimQuery = "UPDATE claims SET status = 'Paid' WHERE id = $claim_id";
    $conn->query($updateClaimQuery);

    echo "<script>alert('Payout processed successfully.'); window.location.href = 'Completed_payouts.php';</script>";
}
?>
