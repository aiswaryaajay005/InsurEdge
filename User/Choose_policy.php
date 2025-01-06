<?php
session_start();
include '../Connection/Db_connection.php'; // Include your database connection

// Get policy ID from the URL
$policyId = isset($_GET['policyId']) ? intval($_GET['policyId']) : 0;

// Fetch policy details securely
$query = "SELECT id, name, type, duration, premium, coverage, description FROM policies WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $policyId);
$stmt->execute();
$result = $stmt->get_result();


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Policy: <?php echo htmlspecialchars($policy['name']); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        input, select, textarea {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            width: 100%;
        }
        button {
            padding: 10px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
    <script>
        function showPolicyForm(policyType) {
            document.querySelectorAll('.policy-form').forEach(form => {
                form.style.display = 'none'; // Hide all forms
            });
            document.getElementById(policyType + 'Form').style.display = 'block'; // Show the selected form
        }

        window.onload = function() {
            // Auto-show the relevant form based on the policy type
            showPolicyForm('<?php echo $policy["type"]; ?>');
        };
    </script>
</head>
<body>
    <div class="container">
        <h1>Choose Policy: <?php echo htmlspecialchars($policy['name']); ?></h1>
        <p>Policy Type: <?php echo htmlspecialchars($policy['type']); ?></p>
        <p>Duration: <?php echo htmlspecialchars($policy['duration']); ?> years</p>
        <p>Premium: <?php echo htmlspecialchars($policy['premium']); ?> USD</p>
        <p>Coverage: <?php echo htmlspecialchars($policy['coverage']); ?> USD</p>
        <p>Description: <?php echo htmlspecialchars($policy['description']); ?></p>

        <!-- Forms for different policy types -->
        <!-- Life Insurance Form -->
        <form id="lifeForm" class="policy-form" style="display:none;" action="Submit_policy.php" method="POST">
            <input type="hidden" name="policyId" value="<?php echo $policyId; ?>">
            <h2>Life Insurance Details</h2>
            <label for="lifeInsuredAmount">Insured Amount</label>
            <input type="number" name="lifeInsuredAmount" required>
            <label for="lifeNominee">Nominee Name</label>
            <input type="text" name="lifeNominee" required>
            <label for="lifeNomineeRelation">Nominee Relation</label>
            <input type="text" name="lifeNomineeRelation" required>
            <button type="submit">Submit Life Insurance Details</button>
        </form>

        <!-- Health Insurance Form -->
        <form id="healthForm" class="policy-form" style="display:none;" action="Submit_policy.php" method="POST">
            <input type="hidden" name="policyId" value="<?php echo $policyId; ?>">
            <h2>Health Insurance Details</h2>
            <label for="healthInsuredAmount">Insured Amount</label>
            <input type="number" name="healthInsuredAmount" required>
            <label for="healthPreExisting">Pre-Existing Conditions</label>
            <textarea name="healthPreExisting" required></textarea>
            <label for="healthHospitals">Preferred Hospitals</label>
            <textarea name="healthHospitals" required></textarea>
            <button type="submit">Submit Health Insurance Details</button>
        </form>

        <!-- Motor Insurance Form -->
        <form id="vehicleForm" class="policy-form" style="display:none;" action="Submit_policy.php" method="POST">
            <input type="hidden" name="policyId" value="<?php echo $policyId; ?>">
            <h2>Vehicle Insurance Details</h2>
            <label for="vehicleNumber">Vehicle Number</label>
            <input type="text" name="vehicleNumber" required>
            <label for="vehicleType">Vehicle Type</label>
            <select name="vehicleType" required>
                <option value="car">Car</option>
                <option value="bike">Bike</option>
                <option value="truck">Truck</option>
            </select>
            <label for="vehicleManufacturer">Manufacturer</label>
            <input type="text" name="vehicleManufacturer" required>
            <button type="submit">Submit Vehicle Insurance Details</button>
        </form>

        <!-- Home Insurance Form -->
        <form id="homeForm" class="policy-form" style="display:none;" action="Submit_policy.php" method="POST">
            <input type="hidden" name="policyId" value="<?php echo $policyId; ?>">
            <h2>Home Insurance Details</h2>
            <label for="homeAddress">Home Address</label>
            <textarea name="homeAddress" required></textarea>
            <label for="homeValue">Home Value</label>
            <input type="number" name="homeValue" required>
            <label for="homeType">Type of Home</label>
            <select name="homeType" required>
                <option value="apartment">Apartment</option>
                <option value="villa">Villa</option>
                <option value="bungalow">Bungalow</option>
            </select>
            <button type="submit">Submit Home Insurance Details</button>
        </form>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
