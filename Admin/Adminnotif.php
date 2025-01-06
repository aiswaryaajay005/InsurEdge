<?php
include 'sidebar.php'; 
?><?php
// admin.php
include '../Connection/Db_connection.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO notification (user_email, message) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $message);
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('Notification sent!');</script>";
}
?>

 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Send Notification</title>
    <link rel="stylesheet" href="../CSS/adminnotif.css">
</head>
<body>
    <div class="container">
        <h1>Send Notification</h1>
        <form method="POST">
            <div class="form-group">
                <label for="email">User Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="message">Message:</label>
                <textarea id="message" name="message" required></textarea>
            </div>
            <button type="submit" class="btn">Send</button>
        </form>
    </div>
</body>
</html> 

