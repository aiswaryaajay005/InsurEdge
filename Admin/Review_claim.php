<?php
// Connect to database
// Database connection
$conn = mysqli_connect("localhost", "root", "", "users");

// Check connection
if ($conn === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

$claim_id = $_GET['claim_id'];

// Fetch claim details
$sql = "SELECT * FROM claims WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $claim_id);
$stmt->execute();
$claim = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update claim status and review notes
    $status = $_POST['status'];
    $review_notes = $_POST['review_notes'];

    $sql = "UPDATE claims SET status = ?, review_notes = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $status, $review_notes, $claim_id);
    $stmt->execute();

    header("Location: Admin_claims_dashboard.php"); // Redirect to dashboard
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Review Claim</title>
    <style>
        /* Styling */
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; }
        .container { max-width: 500px; margin: 50px auto; padding: 20px; background-color: white; border-radius: 8px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); }
        h1 { text-align: center; }
        label { display: block; margin-top: 15px; }
        select, textarea, button { width: 100%; padding: 10px; border-radius: 5px; margin-top: 5px; }
        button { background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #45a049; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Review Claim ID: <?php echo htmlspecialchars($claim['id']); ?></h1>
        <p><strong>Policy ID:</strong> <?php echo htmlspecialchars($claim['policy_id']); ?></p>
        <p><strong>Incident Date:</strong> <?php echo htmlspecialchars($claim['incident_date']); ?></p>
        <p><strong>Claim Amount:</strong> $<?php echo htmlspecialchars($claim['claim_amount']); ?></p>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($claim['description']); ?></p>

        <form method="post">
            <label for="status">Update Status:</label>
            <select id="status" name="status" required>
                <option value="Under Review" <?php if ($claim['status'] == 'Under Review') echo 'selected'; ?>>Under Review</option>
                <option value="Approved" <?php if ($claim['status'] == 'Approved') echo 'selected'; ?>>Approved</option>
                <option value="Rejected" <?php if ($claim['status'] == 'Rejected') echo 'selected'; ?>>Rejected</option>
            </select>

            <label for="review_notes">Review Notes:</label>
            <textarea id="review_notes" name="review_notes" rows="4"><?php echo htmlspecialchars($claim['review_notes']); ?></textarea>

            <button type="submit">Submit Review</button>
        </form>
    </div>
</body>
</html>
