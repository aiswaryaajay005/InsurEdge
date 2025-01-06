<?php
session_start();
include '../Connection/Db_connection.php';

$user_id = $_SESSION['user_id']; // Ensure the user is logged in
$claim_id = $_GET['claim_id']; // Get the claim ID from the URL

// Fetch the claim details based on the auto-incremented ID
$stmt = $conn->prepare("SELECT * FROM claims WHERE user_id = ? AND id = ?");
$stmt->bind_param("ii", $user_id, $claim_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $claim = $result->fetch_assoc();
    
    // Determine if the claim can be edited
    $can_edit = ($claim['status'] === 'Pending'); // Example condition
} else {
    echo "No claim found or you do not have permission to view this claim.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Claim</title>
    <link rel="stylesheet" href="../CSS/Claimsformstyle.css">
</head>
<body>
    <div class="container">
        <h2>Claim Details</h2>
        <p><strong>Claim ID:</strong> <?php echo $claim['id']; ?></p>
        <p><strong>Policy ID:</strong> <?php echo $claim['policy_id']; ?></p>
        <p><strong>Customer Name:</strong> <?php echo $claim['customer_name']; ?></p>
        <p><strong>Email:</strong> <?php echo $claim['customer_email']; ?></p>
        <p><strong>Incident Date:</strong> <?php echo $claim['incident_date']; ?></p>
        <p><strong>Claim Amount:</strong> $<?php echo number_format($claim['claim_amount'], 2); ?></p>
        <p><strong>Description:</strong> <?php echo $claim['description']; ?></p>
        <p><strong>Status:</strong> <?php echo $claim['status']; ?></p>

        <?php if (!empty($claim['supporting_documents'])): ?>
            <h3>Supporting Documents:</h3>
            <p><a href="../<?php echo $claim['supporting_documents']; ?>" target="_blank">View Supporting Document</a></p>
        <?php else: ?>
            <p>No supporting documents uploaded.</p>
        <?php endif; ?>

        <?php if ($can_edit): ?>
            <p><a href="Edit_claim.php?claim_id=<?php echo $claim['id']; ?>">Edit Claim</a></p>
        <?php else: ?>
            <p>You cannot edit this claim as it is already <?php echo $claim['status']; ?>.</p>
        <?php endif; ?>

        <a href="Claim_status.php">Back to Claim History</a>
    </div>
</body>
</html>
