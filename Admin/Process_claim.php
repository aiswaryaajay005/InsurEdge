<?php
include '../Connection/Db_connection.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $claim_id = $_POST['claim_id'];
    $action = $_POST['action'];
    $rejection_reason = $_POST['rejection_reason'] ?? null;

    if ($action === 'accept') {
        // Update claim status to 'Accepted'
        $stmt = $conn->prepare("UPDATE claims SET status = 'Accepted' WHERE id = ?");
        $stmt->bind_param("i", $claim_id);

        if ($stmt->execute()) {
            // Generate a link for user to fill bank details
            $bank_details_url = "Fill_bank_details.php?claim_id=" . $claim_id;
            echo "Claim accepted. <a href='$bank_details_url'>Click here to fill bank details</a>";
        } else {
            echo "Error updating claim: " . $conn->error;
        }
        $stmt->close();
    } elseif ($action === 'reject') {
        // Update claim status to 'Rejected' with reason
        $stmt = $conn->prepare("UPDATE claims SET status = 'Rejected', rejection_reason = ? WHERE id = ?");
        $stmt->bind_param("si", $rejection_reason, $claim_id);

        if ($stmt->execute()) {
            echo "Claim rejected.";
        } else {
            echo "Error updating claim: " . $conn->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>
