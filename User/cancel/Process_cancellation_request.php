<?php
session_start();
include '../../Connection/Db_connection.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access. Please log in.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $policy_id = $_POST['policy_id'];
    $policy_name = $_POST['policy_name'];
    $reason = $_POST['reason'];
    $cancellation_date = $_POST['cancellation_date'];
    $status = 'pending'; // Default status
    $refund_amount = 0.0; // Default refund

    $query = "INSERT INTO cancellation_requests (user_id, policy_id, policy_name, reason, cancellation_date, status, refund_amount) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isssssd", $user_id, $policy_id, $policy_name, $reason, $cancellation_date, $status, $refund_amount);

    if ($stmt->execute()) {
        echo "<div class='message success'>Your cancellation request has been submitted successfully.</div>";
        echo '<a href="View_cancellation_request.php" class="button">View Request Status</a>';
    } else {
        echo "<div class='message error'>Error: " . htmlspecialchars($stmt->error) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Cancellation Request</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #ff9a9e, #fad0c4);
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
            color: #d50057;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            font-weight: bold;
        }

        input, select, textarea {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            width: 100%;
        }

        input[type="date"] {
            font-family: inherit;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .button {
            display: inline-block;
            padding: 12px;
            background: #d50057;
            color: #fff;
            border: none;
            text-align: center;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
            text-decoration: none;
            text-align: center;
        }

        .button:hover {
            background: #c2185b;
        }

        .message {
            text-align: center;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .message.success {
            background: #4caf50;
            color: #fff;
        }

        .message.error {
            background: #f44336;
            color: #fff;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Submit Cancellation Request</h1>
        <form method="POST">
            <label for="policy_id">Policy ID</label>
            <input type="text" id="policy_id" name="policy_id" required>

            <label for="policy_name">Policy Name</label>
            <input type="text" id="policy_name" name="policy_name" required>

            <label for="reason">Reason for Cancellation</label>
            <textarea id="reason" name="reason" required></textarea>

            <label for="cancellation_date">Cancellation Date</label>
            <input type="date" id="cancellation_date" name="cancellation_date" required>

            <button type="submit" class="button">Submit Request</button>
        </form>
    </div>

    <div class="footer">
        <p>&copy; 2024 InsurEdge. All rights reserved.</p>
    </div>
</body>
</html>

