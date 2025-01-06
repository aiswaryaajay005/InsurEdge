
<?php
session_start();
include '../Connection/Db_connection.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Get form data
$policyId = $_POST['policyId'];
$user_email = $_SESSION['email']; // Assuming the email is stored in session upon login
$paymentMethod = $_POST['paymentMethod'] ?? null;
$paymentFrequency = $_POST['paymentFrequency'] ?? null;
if (!$paymentMethod || !$paymentFrequency) {
die('Payment method or frequency not selected.');
}
// Fetch the policy details
$query = "SELECT type, premium, duration FROM policies WHERE id = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("s", $policyId);
$stmt->execute();
$result = $stmt->get_result();
$policy = $result->fetch_assoc();
if ($policy) {
$policyType = $policy['type'];
$premium = $policy['premium'];
$policyDuration = $policy['duration'];
// Define the insert query and bind parameters based on policy type
switch ($policyType) {
case 'health':
// Additional fields for health policy
$healthCondition = $_POST['healthCondition'];
$beneficiary = $_POST['beneficiary'];
$hospitalPreference = $_POST['hospitalPreference'];
$familyDoctor = $_POST['familyDoctor'];
$emergencyContact = $_POST['emergencyContact'];
$emergencyContactNumber = $_POST['emergencyContactNumber'];
$medicalHistory = $_POST['medicalHistory'];
$currentMedications = $_POST['currentMedications'];
$familyHealthHistory = $_POST['familyHealthHistory'];
$query = "INSERT INTO health_policies (policy_id, health_condition, beneficiary, hospital_preference, family_doctor, emergency_contact, emergency_contact_number, medical_history, current_medications, family_health_history, payment_method, payment_frequency)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssssssssssss", $policyId, $healthCondition, $beneficiary, $hospitalPreference, $familyDoctor, $emergencyContact, $emergencyContactNumber, $medicalHistory, $currentMedications, $familyHealthHistory, $paymentMethod, $paymentFrequency);
break;
case 'life':
// Additional fields for life policy
$lifeCoverageAmount = $_POST['lifeCoverageAmount'];
$occupation = $_POST['occupation'];
$smokingStatus = $_POST['smokingStatus'];
$lifeBeneficiary = $_POST['lifeBeneficiary'];
$lifeBeneficiaryRelation = $_POST['lifeBeneficiaryRelation'];
$lifeMedicalHistory = $_POST['lifeMedicalHistory'];
$annualIncome = $_POST['annualIncome'];
$outstandingDebts = $_POST['outstandingDebts'];
$financialDependents = $_POST['financialDependents'];
$query = "INSERT INTO life_policies (policy_id, coverage_amount, occupation, smoking_status, beneficiary, beneficiary_relation, medical_history, annual_income, outstanding_debts, financial_dependents, payment_method, payment_frequency)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("sissssssssss", $policyId, $lifeCoverageAmount, $occupation, $smokingStatus, $lifeBeneficiary, $lifeBeneficiaryRelation, $lifeMedicalHistory, $annualIncome, $outstandingDebts, $financialDependents, $paymentMethod, $paymentFrequency);
break;
case 'vehicle':
// Additional fields for vehicle policy
$vehicleMake = $_POST['vehicleMake'];
$vehicleModel = $_POST['vehicleModel'];
$vehicleYear = $_POST['vehicleYear'];
$licensePlate = $_POST['licensePlate'];
$vehicleUse = $_POST['vehicleUse'];
$vehicleInsuranceHistory = $_POST['vehicleInsuranceHistory'];
$vehicleDrivingHistory = $_POST['vehicleDrivingHistory'];
$vehicleClaimsHistory = $_POST['vehicleClaimsHistory'];
$query = "INSERT INTO vehicle_policies (policy_id, vehicle_make, vehicle_model, vehicle_year, license_plate, vehicle_use, insurance_history, driving_history, claims_history, payment_method, payment_frequency)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("sssssssssss", $policyId, $vehicleMake, $vehicleModel, $vehicleYear, $licensePlate, $vehicleUse, $vehicleInsuranceHistory, $vehicleDrivingHistory, $vehicleClaimsHistory, $paymentMethod, $paymentFrequency);
break;
case 'home':
$policyId = $_POST['policyId'];
$homeAddress = $_POST['homeAddress'];
$homeValue = $_POST['homeValue'];
$homeType = $_POST['homeType'];
$securitySystem = $_POST['securitySystem'];
$damageHistory = isset($_POST['damageHistory']) ? $_POST['damageHistory'] : ''; // Default to empty string if not set
$constructionMaterial = isset($_POST['constructionMaterial']) ? $_POST['constructionMaterial'] : 'Unknown'; // Default if missing
$disasterRisk = isset($_POST['disasterRisk']) ? $_POST['disasterRisk'] : 'Unknown'; // Default if missing
$homeClaimsHistory = $_POST['homeClaimsHistory'];
$paymentMethod = $_POST['paymentMethod'];
$paymentFrequency = $_POST['paymentFrequency'];
// Prepare the SQL query
$query = "INSERT INTO home_policies (
policy_id, home_address, home_value, home_type, security_system,
damage_history, construction_material, disaster_risk, home_claims_history,
payment_method, payment_frequency
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
// Prepare the statement
$stmt = $conn->prepare($query);
// Bind the parameters
$stmt->bind_param("sssssssssss", 
$policyId, $homeAddress, $homeValue, $homeType, $securitySystem,
$damageHistory, $constructionMaterial, $disasterRisk, $homeClaimsHistory,
$paymentMethod, $paymentFrequency);
break;
default:
die("Unknown policy type.");
}
// Execute the statement
if ($stmt->execute()) {
echo "Policy details saved successfully!";
} else {
echo "Error saving policy details: " . $stmt->error;
}
// Execute the statement and check for errors
if ($stmt->execute()) {
// Calculate payment amount and next payment date
switch ($paymentFrequency) {
case 'Monthly':
$totalPayments = 12 * $policyDuration;
$interval_amount = $premium / $totalPayments;
$nextPaymentDate = (new DateTime())->modify('+1 month')->format('Y-m-d');
break;
case 'Quarterly':
$totalPayments = 4 * $policyDuration;
$interval_amount = $premium / $totalPayments;
$nextPaymentDate = (new DateTime())->modify('+3 months')->format('Y-m-d');
break;
case 'Yearly':
$totalPayments = $policyDuration;
$interval_amount = $premium / $totalPayments;
$nextPaymentDate = (new DateTime())->modify('+1 year')->format('Y-m-d');
break;
default:
die("Invalid payment frequency.");
}
// Store session data
$_SESSION['policyId'] = $policyId;
$_SESSION['paymentMethod'] = $paymentMethod;
$_SESSION['paymentAmount'] = $interval_amount;
$_SESSION['paymentFrequency'] = $paymentFrequency;
$_SESSION['interval_amount'] = $interval_amount;
$_SESSION['nextPaymentDate'] = $nextPaymentDate;
$_SESSION['from_payment_report'] = true;
// Update approved_policies table
$updateFormFilledQuery = "UPDATE approved_policies SET form_filled = 1 WHERE policy_id = ? AND email_id = ?";
$updateStmt = $conn->prepare($updateFormFilledQuery);
$updateStmt->bind_param("ss", $policyId, $user_email);
$updateStmt->execute();
// Redirect to Payment_method.php
header("Location: Payment_method.php");
exit();
} else {
die("Error executing statement: " . $stmt->error);
}
} else {
die("Policy not found.");
}
}
?>




