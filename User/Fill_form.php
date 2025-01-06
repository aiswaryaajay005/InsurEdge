<?php
session_start();
include '../Connection/Db_connection.php';
$policyId = $_GET['policyId'];
// Fetch policy details based on policyId
$query = "SELECT * FROM policies WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $policyId);
$stmt->execute();
$result = $stmt->get_result();
$policy = $result->fetch_assoc();
if (!$policy) {
die("Policy not found.");
}
// Define payment methods and frequencies
$paymentMethods = ['Credit Card', 'Debit Card', 'PayPal', 'Bank Transfer'];
$paymentFrequencies = ['Monthly', 'Quarterly', 'Yearly'];
// Render different forms based on the policy type
// Render different forms based on the policy type
function renderForm($policyType) {
switch ($policyType) {
case 'health':
return '
<div class="form-group">
<label for="healthCondition">Current Health Conditions:</label>
<input type="text" id="healthCondition" name="healthCondition" placeholder="e.g., Diabetes, High Blood Pressure" required>
</div>
<div class="form-group">
<label for="hospitalPreference">Preferred Hospital:</label>
<input type="text" id="hospitalPreference" name="hospitalPreference" placeholder="e.g., General Hospital" required>
</div>
<div class="form-group">
<label for="familyDoctor">Family Doctor Name:</label>
<input type="text" id="familyDoctor" name="familyDoctor" placeholder="e.g., Dr. John Doe" required>
</div>
<div class="form-group">
<label for="beneficiary">Beneficiary Name:</label>
<input type="text" id="beneficiary" name="beneficiary" placeholder="e.g., Spouse, Children" required>
</div>
<div class="form-group">
<label for="emergencyContact">Emergency Contact Name:</label>
<input type="text" id="emergencyContact" name="emergencyContact" placeholder="e.g., Jane Doe" required>
</div>
<div class="form-group">
<label for="emergencyContactNumber">Emergency Contact Number:</label>
<input type="text" id="emergencyContactNumber" name="emergencyContactNumber" placeholder="e.g., 9876543210" required>
</div>
<div class="form-group">
<label for="medicalHistory">Medical History:</label>
<textarea id="medicalHistory" name="medicalHistory" placeholder="Any medical conditions or surgeries (e.g., Heart Surgery, Allergies)" required></textarea>
</div>
<div class="form-group">
<label for="currentMedications">Current Medications:</label>
<textarea id="currentMedications" name="currentMedications" placeholder="List any current medications" required></textarea>
</div>
<div class="form-group">
<label for="familyHealthHistory">Family Health History:</label>
<textarea id="familyHealthHistory" name="familyHealthHistory" placeholder="e.g., Heart Disease, Cancer in family" required></textarea>
</div>';
break;
case 'life':
return '
<div class="form-group">
<label for="occupation">Occupation:</label>
<input type="text" id="occupation" name="occupation" placeholder="e.g., Software Engineer" required>
</div>
<div class="form-group">
<label for="smokingStatus">Smoking Status:</label>
<select id="smokingStatus" name="smokingStatus" required>
<option value="non-smoker">Non-Smoker</option>
<option value="smoker">Smoker</option>
</select>
</div>
<div class="form-group">
<label for="lifeBeneficiary">Beneficiary Name:</label>
<input type="text" id="lifeBeneficiary" name="lifeBeneficiary" placeholder="e.g., Spouse, Children" required>
</div>
<div class="form-group">
<label for="lifeBeneficiaryRelation">Beneficiary Relationship:</label>
<input type="text" id="lifeBeneficiaryRelation" name="lifeBeneficiaryRelation" placeholder="e.g., Spouse, Parent" required>
</div>
<div class="form-group">
<label for="lifeMedicalHistory">Medical History:</label>
<textarea id="lifeMedicalHistory" name="lifeMedicalHistory" placeholder="Any prior conditions, surgeries, etc." required></textarea>
</div>
<div class="form-group">
<label for="annualIncome">Annual Income:</label>
<input type="number" id="annualIncome" name="annualIncome" placeholder="e.g., 50000" required>
</div>
<div class="form-group">
<label for="outstandingDebts">Outstanding Debts (e.g., loans, mortgages):</label>
<textarea id="outstandingDebts" name="outstandingDebts" placeholder="e.g., Home Loan, Car Loan" required></textarea>
</div>
<div class="form-group">
<label for="financialDependents">Financial Dependents:</label>
<input type="number" id="financialDependents" name="financialDependents" placeholder="e.g., 2 children, 1 spouse" required>
</div>';
break;
case 'vehicle':
return '
<div class="form-group">
<label for="vehicleMake">Vehicle Make:</label>
<input type="text" id="vehicleMake" name="vehicleMake" placeholder="e.g., Toyota, Ford" required>
</div>
<div class="form-group">
<label for="vehicleModel">Vehicle Model:</label>
<input type="text" id="vehicleModel" name="vehicleModel" placeholder="e.g., Camry, F-150" required>
</div>
<div class="form-group">
<label for="vehicleYear">Vehicle Year:</label>
<input type="number" id="vehicleYear" name="vehicleYear" placeholder="e.g., 2018" required>
</div>
<div class="form-group">
<label for="licensePlate">License Plate Number:</label>
<input type="text" id="licensePlate" name="licensePlate" placeholder="e.g., ABC1234" required>
</div>
<div class="form-group">
<label for="vehicleUse">Vehicle Use:</label>
<select id="vehicleUse" name="vehicleUse" required>
<option value="personal">Personal</option>
<option value="commercial">Commercial</option>
</select>
</div>
<div class="form-group">
<label for="vehicleInsuranceHistory">Previous Insurance History:</label>
<textarea id="vehicleInsuranceHistory" name="vehicleInsuranceHistory" placeholder="Details of previous insurance coverage" required></textarea>
</div>
<div class="form-group">
<label for="vehicleDrivingHistory">Driving History (e.g., accidents, violations):</label>
<textarea id="vehicleDrivingHistory" name="vehicleDrivingHistory" placeholder="e.g., Minor accidents in the last 5 years" required></textarea>
</div>
<div class="form-group">
<label for="vehicleClaimsHistory">Previous Claims History:</label>
<textarea id="vehicleClaimsHistory" name="vehicleClaimsHistory" placeholder="e.g., Claim filed for accident in 2022" required></textarea>
</div>';
break;
case 'home':
return '
<div class="form-group">
<label for="homeAddress">Home Address:</label>
<input type="text" id="homeAddress" name="homeAddress" placeholder="e.g., 123 Main St, City" required>
</div>
<div class="form-group">
<label for="homeValue">Estimated Home Value:</label>
<input type="number" id="homeValue" name="homeValue" placeholder="e.g., 300,000" required>
</div>
<div class="form-group">
<label for="homeType">Home Type:</label>
<select id="homeType" name="homeType" required>
<option value="apartment">Apartment</option>
<option value="single-family">Single Family</option>
<option value="condo">Condominium</option>
</select>
</div>
<div class="form-group">
<label for="securitySystem">Home Security System:</label>
<select id="securitySystem" name="securitySystem" required>
<option value="yes">Yes</option>
<option value="no">No</option>
</select>
</div>
<div class="form-group">
<label for="homeDamageHistory">Previous Home Damage History:</label>
<textarea id="homeDamageHistory" name="homeDamageHistory" placeholder="Any previous damage to the home (flood, fire, etc.)" required></textarea>
</div>
<div class="form-group">
<label for="propertyConstructionMaterial">Property Construction Material:</label>
<input type="text" id="propertyConstructionMaterial" name="propertyConstructionMaterial" placeholder="e.g., Brick, Wood, Concrete" required>
</div>
<div class="form-group">
<label for="naturalDisasterRisk">Natural Disaster Risk (e.g., Flood, Earthquake):</label>
<input type="text" id="naturalDisasterRisk" name="naturalDisasterRisk" placeholder="e.g., Flood-prone area" required>
</div>
<div class="form-group">
<label for="homeClaimsHistory">Previous Home Claims:</label>
<textarea id="homeClaimsHistory" name="homeClaimsHistory" placeholder="e.g., Flood damage in 2020" required></textarea>
</div>';
break;
default:
return '<p>Unknown policy type.</p>';
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Fill Form</title>
<link rel="stylesheet" href="../CSS/Fillformstyle.css">
<style>
body {
font-family: Arial, sans-serif;
margin: 20px;
}
.form-container {
max-width: 600px;
margin: auto;
padding: 20px;
border: 1px solid #ccc;
border-radius: 5px;
}
.form-group {
margin-bottom: 15px;
}
</style>
</head>
<body>
<div class="form-container">
<h2>Fill Form for <?php echo htmlspecialchars($policy['name']); ?></h2>
<form action="Process_payment.php" method="POST">
<input type="hidden" name="policyId" value="<?php echo htmlspecialchars($policyId); ?>">
<!-- Render the policy-specific form fields -->
<?php echo renderForm($policy['type']); ?>
<!-- Payment method and frequency fields -->
<div class="form-group">
<label for="paymentMethod">Payment Method:</label>
<select id="paymentMethod" name="paymentMethod" required>
<?php foreach ($paymentMethods as $method): ?>
<option value="<?php echo htmlspecialchars($method); ?>"><?php echo htmlspecialchars($method); ?></option>
<?php endforeach; ?>
</select>
</div>
<div class="form-group">
<label for="paymentFrequency">Payment Frequency:</label>
<select id="paymentFrequency" name="paymentFrequency" required>
<?php foreach ($paymentFrequencies as $frequency): ?>
<option value="<?php echo htmlspecialchars($frequency); ?>"><?php echo htmlspecialchars($frequency); ?></option>
<?php endforeach; ?>
</select>
<p>Upload the required documents:</p>
<?php
// Display required documents based on policy type
$required_docs = [
'health' => ['Identity Proof', 'Medical History Declaration'],
'life' => ['Identity Proof', 'Proof of Income'],
'vehicle' => ['Vehicle Registration Certificate (RC)', 'Vehicle Photos'],
'home' => ['Identity Proof', 'Property Ownership Document'],
];
$policy_type = strtolower($policy['type']);
foreach ($required_docs[$policy_type] as $doc) {
echo "<label>$doc:</label><input type='file' name='documents[]' required><br>";
}
?>
</div>
<button type="submit">Proceed to Pay</button>
</form>
</div>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>


