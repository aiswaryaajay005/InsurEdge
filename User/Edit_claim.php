<?php
session_start();
include '../Connection/Db_connection.php'; // Include the database connection file
include 'User_sidebar.php'; // Include the sidebar for the user

$user_id = $_SESSION['user_id']; // Ensure the user is logged in

// Get the claim ID from the URL
$claim_id = $_GET['claim_id']; 

// Fetch the claim data from the database
$stmt = $conn->prepare("SELECT * FROM claims WHERE user_id = ? AND id = ?");
$stmt->bind_param("ii", $user_id, $claim_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $claim = $result->fetch_assoc();
} else {
    echo "No claim found or you do not have permission to edit this claim.";
    exit;
}

// Allow editing only if the status is "Pending"
if ($claim['status'] != 'Pending') {
    echo "You cannot edit this claim as it is not in 'Pending' status.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $incident_date = $_POST['incident_date'];
    $claim_amount = $_POST['claim_amount'];
    $description = $_POST['description'];

    // Update claim in the database
    $stmt = $conn->prepare("
        UPDATE claims 
        SET incident_date = ?, claim_amount = ?, description = ? 
        WHERE id = ? AND user_id = ?
    ");
    $stmt->bind_param("sdssi", $incident_date, $claim_amount, $description, $claim_id, $user_id);

    if ($stmt->execute()) {
        echo "Claim updated successfully!";
    } else {
        echo "Failed to update claim.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Claim</title>
    <link rel="stylesheet" href="../CSS/Claimsformstyle.css">
</head>
<body>
    <div class="container">
        <h2>Edit Your Claim</h2>
        
        <form action="edit_claim.php?claim_id=<?php echo $claim_id; ?>" method="post">
            <label for="incident_date">Incident Date:</label>
            <input type="datetime-local" id="incident_date" name="incident_date" value="<?php echo $claim['incident_date']; ?>" required>
            
            <label for="claim_amount">Claim Amount:</label>
            <input type="number" id="claim_amount" name="claim_amount" value="<?php echo $claim['claim_amount']; ?>" step="0.01" required>
            
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required><?php echo $claim['description']; ?></textarea>
            
            <button type="submit">Update Claim</button>
        </form>
    </div>
</body>
</html>
