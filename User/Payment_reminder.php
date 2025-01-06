<?php
session_start();
include '../Connection/Db_connection.php';  // Include your database connection
include 'User_sidebar.php';
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to view your notifications.");
}

$userId = $_SESSION['user_id'];

// Fetch unread notifications for the logged-in user
$query = "SELECT * FROM user_notifications WHERE user_id = ? AND is_read = 0 ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

// Display notifications
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Notifications</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            height: 100%;
            width: 250px;
            background-color: #2c3e50;
            color: #fff;
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar h2 {
            color: #ecf0f1;
            font-size: 24px;
            margin-bottom: 30px;
        }

        .sidebar a {
            color: #ecf0f1;
            display: block;
            font-size: 18px;
            text-decoration: none;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #34495e;
        }

        .main-content {
            margin-left: 270px;
            padding: 40px;
        }

        .notifications-container {
            margin: 0 auto;
            width: 80%;
        }

        .notification {
            background-color: #fff;
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .notification p {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .notification a {
            display: inline-block;
            padding: 8px 15px;
            font-size: 14px;
            background-color: #970747;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .notification a:hover {
            background-color: #970747;
        }

        h1 {
            text-align: center;
            font-size: 30px;
            color: #970747;
            margin-bottom: 20px;
        }

        .no-notifications {
            text-align: center;
            font-size: 18px;
            color: #333;
        }
    </style>
</head>
<body>



<!-- Main content -->
<div class="main-content">
    <h1>Payment Reminder</h1>
    
    <?php
    if ($result->num_rows > 0) {
        echo '<div class="notifications-container">';
        while ($row = $result->fetch_assoc()) {
            echo '<div class="notification">';
            echo '<p>' . htmlspecialchars($row['message']) . '</p>';
            echo '<a href="Mark_notification_read.php?notification_id=' . $row['id'] . '">Mark as read</a>';
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo "<p class='no-notifications'>No new notifications.</p>";
    }

    $stmt->close();
    $conn->close();
    ?>
</div>

</body>
</html>
