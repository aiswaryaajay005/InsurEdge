<?php
session_start();
include '../Connection/Db_connection.php'; // Include database connection

// Validate and get policy type and ID from URL
if (!isset($_GET['type']) || !isset($_GET['id'])) {
    echo "Invalid policy selection.";
    exit();
}

// Sanitize the inputs
$policyType = filter_var($_GET['type'], FILTER_SANITIZE_STRING);
$policyId = intval($_GET['id']); // Ensure the ID is an integer

// Fetch policy details from the database
$query = "SELECT * FROM policies WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $policyId);
$stmt->execute();
$policy = $stmt->get_result()->fetch_assoc();
$stmt->close();

// If no matching policy is found, display error
if (!$policy) {
    echo "Invalid policy selection.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form - <?php echo ucfirst($policyType); ?> Policy</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1><?php echo ucfirst($policyType); ?> Insurance Application Form</h1>
    
    <!-- Optional: Display policy details -->
    <h2>Policy: <?php echo htmlspecialchars($policy['name']); ?></h2>
    <p><?php echo htmlspecialchars($policy['description']); ?></p>

    <form action="payment_page.php" method="POST">
        <input type="hidden" name="policyId" value="<?php echo $policyId; ?>"> <!-- Fixed $id to $policyId -->
        <input type="hidden" name="policyType" value="<?php echo $policyType; ?>"> <!-- Fixed $type to $policyType -->

        <!-- Render fields based on policy type -->
        <?php if ($policyType == 'life'): ?>
            <!-- Life Insurance Form Fields -->
            <label for="age">Age:</label>
            <input type="number" id="age" name="age" required><br>

            <label for="coverage_amount">Coverage Amount:</label>
            <input type="number" id="coverage_amount" name="coverage_amount" required><br>

        <?php elseif ($policyType == 'health'): ?>
            <!-- Health Insurance Form Fields -->
            <label for="pre_existing_conditions">Pre-existing Conditions:</label>
            <textarea id="pre_existing_conditions" name="pre_existing_conditions"></textarea><br>

            <label for="hospital_network">Preferred Hospital Network:</label>
            <input type="text" id="hospital_network" name="hospital_network"><br>

        <?php elseif ($policyType == 'motor'): ?>
            <!-- Motor Insurance Form Fields -->
            <label for="vehicle_make">Vehicle Make:</label>
            <input type="text" id="vehicle_make" name="vehicle_make" required><br>

            <label for="vehicle_model">Vehicle Model:</label>
            <input type="text" id="vehicle_model" name="vehicle_model" required><br>

            <label for="year_of_manufacture">Year of Manufacture:</label>
            <input type="number" id="year_of_manufacture" name="year_of_manufacture" required><br>

        <?php elseif ($policyType == 'home'): ?>
            <!-- Home Insurance Form Fields -->
            <label for="property_value">Property Value:</label>
            <input type="number" id="property_value" name="property_value" required><br>

            <label for="home_address">Home Address:</label>
            <input type="text" id="home_address" name="home_address" required><br>
        <?php endif; ?>

        <!-- Payment Method and Frequency -->
        <h3>Payment Options</h3>
        <label for="payment_method">Payment Method:</label>
        <select id="payment_method" name="payment_method" required>
            <option value="credit_card">Credit Card</option>
            <option value="bank_transfer">Bank Transfer</option>
            <option value="paypal">PayPal</option>
        </select><br>

        <label for="payment_frequency">Payment Frequency:</label>
        <select id="payment_frequency" name="payment_frequency" required>
            <option value="monthly">Monthly</option>
            <option value="quarterly">Quarterly</option>
            <option value="annually">Annually</option>
        </select><br>

        <input type="submit" value="Submit and Proceed to Payment">
    </form>
</body>
</html>

