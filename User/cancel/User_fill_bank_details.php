<?php
session_start();
include '../../Connection/Db_connection.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access. Please log in.");
}

$user_id = $_SESSION['user_id'];

$cancellation_request_id = isset($_GET['id']) ? (int)$_GET['id'] : null;

if (!$cancellation_request_id) {
    die("Invalid request: No ID passed.");
}



$query = "SELECT * FROM cancellation_requests WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $cancellation_request_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$stmt) {
    die("Query preparation failed: " . $conn->error);
}

$stmt->bind_param("ii", $cancellation_request_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();



if ($result->num_rows == 0) {
    die("Cancellation request not found or you do not have permission to view this.");
}

$request = $result->fetch_assoc();


if (strtolower($request['status']) != 'approved') {
    die("Your cancellation request has not been approved yet.");
}


// Generate a CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fill Bank Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            color: #970747;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center; /* Center horizontally */
            align-items: center;     /* Center vertically */
            height: 100vh;
            box-sizing: border-box;
        }

        .container {
            text-align: center;
            width: 100%;
            max-width: 450px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #970747;
            margin-top: 0;
        }

        form {
            margin-top: 20px;
        }

        label {
            font-size: 14px;
            font-weight: bold;
            display: block;
            margin-bottom: 6px;
            color: #970747;
        }

        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #970747;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #a61c5c;
        }

        input[type="submit"]:focus {
            outline: none;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        }

        input[type="text"]:focus {
            border-color: #970747;
            box-shadow: 0 0 5px rgba(151, 7, 71, 0.5);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Fill in Your Bank Details for Refund</h2>
        <form action="Submit_bank_details.php" method="post">
            <label for="account_holder">Account Holder Name:</label>
            <input type="text" id="account_holder" name="account_holder" required><br><br>

            <label for="account_number">Account Number:</label>
            <input type="text" id="account_number" name="account_number" required><br><br>

            <label for="bank_name">Bank Name:</label>
            <input type="text" id="bank_name" name="bank_name" required><br><br>

            <label for="ifsc_code">IFSC Code:</label>
            <input type="text" id="ifsc_code" name="ifsc_code" pattern="^[A-Za-z]{4}\d{7}$" title="Please enter a valid IFSC code" required><br><br>

            <input type="hidden" name="request_id" value="<?php echo $cancellation_request_id; ?>">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

            <input type="submit" value="Submit Bank Details">
        </form>
    </div>
</body>
</html>

