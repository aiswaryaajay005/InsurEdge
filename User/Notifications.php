<?php
session_start();
include '../Connection/Db_connection.php'; // Include your database connection
include('User_sidebar.php');
// Assuming user email is stored in session
$user_email = $_SESSION['email']; 

$query = $conn->prepare("SELECT * FROM notification WHERE user_email = ? ORDER BY created_at DESC");
$query->bind_param("s", $user_email);
$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Notifications</title>
    <link rel="stylesheet" href="../CSS/notifstyle.css">
    <script>
        function markAsRead(notificationId) {
            fetch('Mark_as_read.php?id=' + notificationId)
                .then(response => response.text())
                .then(data => {
                    // Update the UI
                    document.getElementById('notification-' + notificationId).classList.add('read');
                });
        }
    </script>
</head>
<body>
    <h1>Your Notifications</h1>
    <div id="notifications">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="notification" id="notification-<?php echo $row['id']; ?>" onclick="markAsRead(<?php echo $row['id']; ?>)">
                <p><?php echo htmlspecialchars($row['message']); ?></p>
                <small><?php echo $row['created_at']; ?></small>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>