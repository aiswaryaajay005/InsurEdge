<?php
session_start();
include '../Connection/Db_connection.php'; // Include your database connection

// Check if the policy ID is set
if (isset($_POST['policyId'])) {
    $policyId = intval($_POST['policyId']);
    
    // Fetch the policy details based on policy ID
    $query = "SELECT id, type FROM policies WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $policyId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $policy = $result->fetch_assoc();
        $policyType = $policy['type'];

        // Handle form submission based on policy type
        switch ($policyType) {
            case 'life':
                $insuredAmount = $_POST['lifeInsuredAmount'];
                $nominee = $_POST['lifeNominee'];
                $nomineeRelation = $_POST['lifeNomineeRelation'];

                // Validate and process the data, then save it to the database
                $insertQuery = "INSERT INTO life_insurance (policy_id, insured_amount, nominee, nominee_relation) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($insertQuery);
                $stmt->bind_param("iiss", $policyId, $insuredAmount, $nominee, $nomineeRelation);
                break;

            case 'health':
                $insuredAmount = $_POST['healthInsuredAmount'];
                $preExisting = $_POST['healthPreExisting'];
                $preferredHospitals = $_POST['healthHospitals'];

                // Save health insurance details
                $insertQuery = "INSERT INTO health_insurance (policy_id, insured_amount, pre_existing_conditions, preferred_hospitals) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($insertQuery);
                $stmt->bind_param("iiss", $policyId, $insuredAmount, $preExisting, $preferredHospitals);
                break;

            case 'vehicle':
                $vehicleNumber = $_POST['vehicleNumber'];
                $vehicleType = $_POST['vehicleType'];
                $vehicleManufacturer = $_POST['vehicleManufacturer'];

                // Save vehicle insurance details
                $insertQuery = "INSERT INTO vehicle_insurance (policy_id, vehicle_number, vehicle_type, vehicle_manufacturer) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($insertQuery);
                $stmt->bind_param("isss", $policyId, $vehicleNumber, $vehicleType, $vehicleManufacturer);
                break;

            case 'home':
                $homeAddress = $_POST['homeAddress'];
                $homeValue = $_POST['homeValue'];
                $homeType = $_POST['homeType'];

                // Save home insurance details
                $insertQuery = "INSERT INTO home_insurance (policy_id, home_address, home_value, home_type) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($insertQuery);
                $stmt->bind_param("isss", $policyId, $homeAddress, $homeValue, $homeType);
                break;

            default:
                die("Invalid policy type.");
        }

        // Execute the insert query
        if ($stmt->execute()) {
            // Redirect to the confirmation page with success message
            header("Location: Confirmation.php?status=success&policyType=" . $policyType);
            exit;
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Policy not found.";
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>
