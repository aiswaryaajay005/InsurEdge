<?php
include '../Connection/Db_connection.php';  // Include your database connection

// Get today's date
$today = date('Y-m-d');

// Get the list of users with upcoming payment dates
$query = "
    SELECT p.user_id, p.policy_id, p.next_payment_date, pol.name AS policy_name, u.name AS user_name
    FROM payments p
    INNER JOIN policies pol ON p.policy_id = pol.id
    INNER JOIN imsuser u ON p.user_id = u.id
    WHERE p.next_payment_date IN (DATE_ADD('$today', INTERVAL 7 DAY), DATE_ADD('$today', INTERVAL 3 DAY), DATE_ADD('$today', INTERVAL 1 DAY))
";

$stmt = $conn->prepare($query);
if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
}

$result = $stmt->get_result();
$notificationsSent = 0; // Counter to track successful notifications

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Reminder Notifications</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .message {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007BFF;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            text-align: center;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn-container {
            text-align: center;
            margin-top: 30px;
        }

        .alert {
            color: #fff;
            background-color: #4CAF50;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .no-notifications {
            color: #fff;
            background-color: #f44336;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Payment Reminder Notifications</h1>
    
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $userId = $row['user_id'];
            $policyId = $row['policy_id'];
            $nextPaymentDate = $row['next_payment_date'];
            $policyName = $row['policy_name'];
            $userName = $row['user_name'];

            // Calculate how many days left
            $daysLeft = (strtotime($nextPaymentDate) - strtotime($today)) / (60 * 60 * 24);

            // Create the message based on days left
            $message = "Hello $userName, your payment for the policy '$policyName' (Policy ID: $policyId) is due in $daysLeft days on $nextPaymentDate. Please ensure the payment is made before the due date to avoid any interruption in coverage.";

            // Insert notification into the user_notifications table
            $insertQuery = "INSERT INTO user_notifications (user_id, message, notification_type) VALUES (?, ?, ?)";
            $insertStmt = $conn->prepare($insertQuery);
            $notificationType = "Payment Reminder";  // Set notification type
            $insertStmt->bind_param('iss', $userId, $message, $notificationType);
            
            if ($insertStmt->execute()) {
                $notificationsSent++; // Increment the counter if the notification is successfully inserted
            }
        }

        // Show success message
        if ($notificationsSent > 0) {
            echo "<div class='alert'>Successfully sent $notificationsSent payment reminders.</div>";
        } else {
            echo "<div class='no-notifications'>No notifications sent.</div>";
        }
    } else {
        echo "<div class='no-notifications'>No upcoming payments.</div>";
    }

    // Close the prepared statement and connection
    $stmt->close();
    $conn->close();
    ?>
    
    <div class="btn-container">
        <a href="Adminpage.php" class="btn">Back to Admin Page</a>
    </div>
</div>

</body>
</html>

