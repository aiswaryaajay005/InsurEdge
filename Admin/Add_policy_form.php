<?php
include './Sidebar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Policy</title>
    <link rel="stylesheet" href="../CSS/Addpolicyformstyle.css"> 
   
</head>
<body>
    <div class="container">
        <h2>Add New Policy</h2>
        <form id="addPolicyForm" action="Add_policy.php" method="POST">
            <input type="text" id="policyId" name="policyId" placeholder="Enter unique policy ID" required>

            <label for="policyName">Policy Name:</label>
            <input type="text" id="policyName" name="policyName" required>

            <label for="policyType">Policy Type:</label>
            <select id="policyType" name="policyType" required>
                <option value="" disabled selected>Select Policy Type</option>
                <option value="health">Health</option>
                <option value="life">Life</option>
                <option value="home">Home</option>
                <option value="vehicle">Vehicle</option>
            </select>

            <label for="policyDuration">Policy Duration (in years):</label>
            <input type="number" id="policyDuration" name="policyDuration" min="1" max="30" required>

            <label for="policyPremium">Total Policy Premium:</label>
            <input type="number" id="policyPremium" name="policyPremium" min="0" required>

            <label for="policyCoverage">Policy Coverage:</label>
            <input type="number" id="policyCoverage" name="policyCoverage" min="0" required>

            <label for="policyDescription">Policy Description:</label>
            <textarea id="policyDescription" name="policyDescription" aria-label="Policy Description" required></textarea>

            <button type="submit">Add Policy</button>
        </form>
    </div>

    <script src="script.js"></script>
</body>
</html>



