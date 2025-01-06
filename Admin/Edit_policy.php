<?php
session_start();
include '../Connection/Db_connection.php'; // Include database connection

// Check if the policy ID is provided
if (!isset($_GET['id'])) {
    echo "Invalid policy selection.";
    exit();
}

$policyId = $_GET['id']; // Get the policy ID from the URL

// Fetch the current policy details from the database
$stmt = $conn->prepare("SELECT * FROM policies WHERE id = ?");
$stmt->bind_param("s", $policyId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Policy not found.";
    exit();
}

$policy = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Policy - <?php echo htmlspecialchars($policy['name']); ?></title>
    <link rel="stylesheet" href="../CSS/Addpolicyformstyle.css"> <!-- Link to the external CSS file -->
</head>
<body>
    <div class="container">
        <h2>Edit Policy: <?php echo htmlspecialchars($policy['name']); ?></h2>
        <form id="editPolicyForm" action="Update_policy.php" method="POST">
            <input type="hidden" name="policyId" value="<?php echo htmlspecialchars($policy['id']); ?>">
            <input type="hidden" name="originalPolicyId" value="<?php echo htmlspecialchars($policyId); ?>">

            <label for="policyId">Policy ID:</label>
            <input type="text" id="policyId" name="policyId" value="<?php echo htmlspecialchars($policy['id']); ?>" required>



            <label for="policyName">Policy Name:</label>
            <input type="text" id="policyName" name="policyName" value="<?php echo htmlspecialchars($policy['name']); ?>" required>

            <label for="policyType">Policy Type:</label>
            <select id="policyType" name="policyType" required>
                <option value="health" <?php echo $policy['type'] === 'health' ? 'selected' : ''; ?>>Health</option>
                <option value="life" <?php echo $policy['type'] === 'life' ? 'selected' : ''; ?>>Life</option>
                <option value="home" <?php echo $policy['type'] === 'home' ? 'selected' : ''; ?>>Home</option>
                <option value="vehicle" <?php echo $policy['type'] === 'vehicle' ? 'selected' : ''; ?>>Vehicle</option>
            </select>

            <label for="policyDuration">Policy Duration (in years):</label>
            <input type="number" id="policyDuration" name="policyDuration" min="1" max="30" value="<?php echo htmlspecialchars($policy['duration']); ?>" required>

            <label for="policyPremium">Policy Premium:</label>
            <input type="number" id="policyPremium" name="policyPremium" min="0" value="<?php echo htmlspecialchars($policy['premium']); ?>" required>

            <label for="policyCoverage">Policy Coverage:</label>
            <input type="number" id="policyCoverage" name="policyCoverage" min="0" value="<?php echo htmlspecialchars($policy['coverage']); ?>" required>

            <label for="policyDescription">Policy Description:</label>
            <textarea id="policyDescription" name="policyDescription" required><?php echo htmlspecialchars($policy['description']); ?></textarea>

            <button type="submit">Update Policy</button>
        </form>
    </div>

    <script src="script.js"></script> <!-- Link to an optional external JS file -->
</body>
</html>

