<?php
session_start();
include '../Connection/Db_connection.php'; // Include your database connection
include 'Notification.php'; // Include your notification utility

// Check if the request method is POST and validate the CSRF token if used
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $applicationId = intval($_POST['id']); // Sanitize the input

    // Optionally, validate CSRF token here
    // if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    //     die("Invalid CSRF token");
    // }

    // Fetch the email of the user applying for the policy
    $stmt = $conn->prepare("SELECT email FROM applications WHERE id = ?");
    $stmt->bind_param("i", $applicationId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $application = $result->fetch_assoc();
        $email_id = $application['email'];

        // Update the status of the application to "Rejected"
        $updateStmt = $conn->prepare("UPDATE applications SET status = 'Rejected' WHERE id = ?");
        $updateStmt->bind_param("i", $applicationId);

        if ($updateStmt->execute()) {
            // Send Email Notification about rejection
            $subject = "Policy Rejected";
            $message = "Dear User,<br><br>We regret to inform you that your policy application has been <b>rejected</b>.<br><br>Thank you for your understanding.";
            if (!sendEmailNotification($email_id, $subject, $message)) {
                echo "Failed to send rejection email notification.";
                exit();
            }

            $message = "Application rejected and user notified successfully!";
            $messageClass = "success";
        } else {
            $message = "Error rejecting the application: " . $conn->error;
            $messageClass = "error";
        }

        $updateStmt->close();
    } else {
        $message = "Application not found.";
        $messageClass = "error";
    }

    $stmt->close();
} else {
    $message = "Invalid request.";
    $messageClass = "error";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Status Update</title>
    <link rel="stylesheet" href="../CSS/Rejectapplystyle.css">
</head>
<body>

<div class="container">
    <h1>Application Status</h1>
    <div class="message <?php echo $messageClass; ?>">
        <?php echo $message; ?>
    </div>
    <a href="Admin_review.php" class="button">Go Back to Pending Applications</a>
</div>

</body>
</html>
