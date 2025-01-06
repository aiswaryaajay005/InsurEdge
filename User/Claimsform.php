
<?php
session_start();
include '../Connection/Db_connection.php';

// Ensure user is logged in
if (!isset($_SESSION['email'])) {
    die("Please log in to submit a claim.");
}

// Retrieve Policy ID from URL
$policy_id = isset($_GET['policy_id']) ? htmlspecialchars($_GET['policy_id']) : null;

// Retrieve user's details
$email = $_SESSION['email'];
$userQuery = "SELECT name FROM imsuser WHERE email = ?";
$userStmt = $conn->prepare($userQuery);
$userStmt->bind_param("s", $email);
$userStmt->execute();
$userResult = $userStmt->get_result();
$user = $userResult->fetch_assoc();
$customer_name = $user ? $user['name'] : '';

if (!$policy_id) {
    die("Policy ID is missing.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Claim</title>
    <link rel="stylesheet" href="../CSS/Claimsformstyle.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap">
    <style>
        /* Basic styling for the form */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f9;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            width: 400px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-top: 15px;
            color: #333;
        }
        input[type="text"],
        input[type="email"],
        input[type="datetime-local"],
        input[type="number"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        textarea {
            resize: vertical;
        }
        button[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
        }
        button[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Submit an Insurance Claim</h2>
 
        <form action="Submit_claim.php" method="post" enctype="multipart/form-data">
    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" value="<?php echo htmlspecialchars($customer_name); ?>" readonly>

    <label for="customer_email">Customer Email:</label>
    <input type="email" id="customer_email" name="customer_email" value="<?php echo htmlspecialchars($email); ?>" readonly>

    <label for="policy_id">Policy ID:</label>
    <input type="text" id="policy_id" name="policy_id" value="<?php echo htmlspecialchars($policy_id); ?>" readonly>

    <label for="incident_date">Incident Date:</label>
    <input type="datetime-local" id="incident_date" name="incident_date" required>

    <label for="claim_amount">Claim Amount:</label>
    <input type="number" id="claim_amount" name="claim_amount" step="0.01" required placeholder="Enter Claim Amount">

    <label for="description">Description of Incident:</label>
    <textarea id="description" name="description" rows="4" required placeholder="Describe the incident..."></textarea>

    <label for="supporting_documents">Upload Supporting Documents:</label>
    <input type="file" id="supporting_documents" name="supporting_documents" accept=".jpg,.jpeg,.png,.pdf" required>

    <label for="policy_document">Upload Policy Document:</label>
    <input type="file" id="policy_document" name="policy_document" accept=".jpg,.jpeg,.png,.pdf" required>

    <button type="submit">Submit Claim</button>
</form>

    </div>
</body>
</html>
