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
    $status = 'Pending'; // Default status
    $refund_amount = 0.0; // Default refund

    $query = "INSERT INTO cancellation_requests (user_id, policy_id, policy_name, reason, cancellation_date, status, refund_amount) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isssssd", $user_id, $policy_id, $policy_name, $reason, $cancellation_date, $status, $refund_amount);

    $message = "";
    if ($stmt->execute()) {
        $message = "Your cancellation request has been submitted successfully.";
        $success = true;
    } else {
        $message = "Error: " . htmlspecialchars($stmt->error);
        $success = false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancellation Request Status</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .status-container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 90%;
            max-width: 400px;
        }

        .status-container h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }

        .status-message {
            font-size: 18px;
            margin: 20px 0;
            color: #555;
        }

        .status-message.success {
            color: #4caf50;
        }

        .status-message.error {
            color: #f44336;
        }

        .button {
            padding: 10px 20px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="status-container">
        <h1>Cancellation Request Status</h1>
        <div class="status-message <?php echo isset($success) && $success ? 'success' : 'error'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
        <?php if (isset($success) && $success): ?>
            <a href="View_cancellation_request.php" class="button">View Request Status</a>
        <?php else: ?>
            <a href="javascript:history.back()" class="button">Go Back</a>
        <?php endif; ?>
    </div>
</body>
</html>
