<?php
session_start();
include '../Connection/Db_connection.php';

$claim_id = $_GET['claim_id']; // Get the claim ID from the URL
$user_id = $_SESSION['user_id']; // Ensure the user is logged in

// Form to fill bank details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $account_holder_name = $_POST['account_holder_name'];
    $bank_name = $_POST['bank_name'];
    $branch_name = $_POST['branch_name'];
    $account_number = $_POST['account_number'];
    $ifsc_code = $_POST['ifsc_code'];

    $stmt = $conn->prepare("
        INSERT INTO bank_details (claim_id, user_id, account_holder_name, bank_name, branch_name, account_number, ifsc_code)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("iisssss", $claim_id, $user_id, $account_holder_name, $bank_name, $branch_name, $account_number, $ifsc_code);

    if ($stmt->execute()) {
        // Success message with a button
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Submission Successful</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f9;
                    color: #333;
                    margin: 0;
                    padding: 0;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                }

                .container {
                    background: #ffffff;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                    border-radius: 8px;
                    padding: 20px;
                    text-align: center;
                    max-width: 400px;
                }

                h2 {
                    color: #28a745;
                    margin-bottom: 10px;
                }

                p {
                    margin: 15px 0;
                    color: #555;
                }

                .btn {
                    display: inline-block;
                    padding: 10px 20px;
                    font-size: 16px;
                    color: #fff;
                    background-color: #007bff;
                    text-decoration: none;
                    border-radius: 5px;
                    transition: background-color 0.3s;
                }

                .btn:hover {
                    background-color: #0056b3;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h2>Submission Successful!</h2>
                <p>Your bank details have been submitted successfully. Thank you!</p>
                <a href="Userdashboard.php" class="btn">Go to Dashboard</a>
            </div>
        </body>
        </html>
        <?php
        exit;
    } else {
        echo "Failed to submit bank details.";
    }

} else {
    ?>
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fill Bank Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 20px;
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            color: #555;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        input {
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 3px rgba(0, 123, 255, 0.5);
        }

        button {
            padding: 10px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Fill Bank Details</h2>
        <form method="post">
            <label for="account_holder_name">Account Holder Name:</label>
            <input type="text" id="account_holder_name" name="account_holder_name" required>
            
            <label for="bank_name">Bank Name:</label>
            <input type="text" id="bank_name" name="bank_name" required>
            
            <label for="branch_name">Branch Name:</label>
            <input type="text" id="branch_name" name="branch_name" required>
            
            <label for="account_number">Account Number:</label>
            <input type="text" id="account_number" name="account_number" required>
            
            <label for="ifsc_code">IFSC Code:</label>
            <input type="text" id="ifsc_code" name="ifsc_code" required>
            
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>

    <?php
}
?>
