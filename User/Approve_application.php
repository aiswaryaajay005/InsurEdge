<?php
include '../Connection/Db_connection.php'; // Include your database connection

$appId = intval($_GET['id']);

// Update the application status to 'Approved'
$stmt = $conn->prepare("UPDATE applications SET status = 'Approved' WHERE id = ?");
$stmt->bind_param("i", $appId);
if ($stmt->execute()) {
    // Redirect the user to the application form
    header("Location: Application_form.php?applicationId=" . $appId);
    exit();
} else {
    echo "Error approving application.";
}

$stmt->close();
$conn->close();
?>
