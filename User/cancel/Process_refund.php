<?php
session_start();
include '../Connection/Db_connection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $request_id = $_POST['request_id'];
    $account_holder = $_POST['account_holder'];
    $bank_name = $_POST['bank_name'];
    $account_number = $_POST['account_number'];
    $ifsc_code = $_POST['ifsc_code'];
    $remarks = $_POST['remarks'];
    
    // Insert the refund details into the database
    $sql = "INSERT INTO refund_details (request_id, account_holder, bank_name, account_number, ifsc_code, remarks) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $request_id, $account_holder, $bank_name, $account_number, $ifsc_code, $remarks);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Refund details submitted successfully.";
        header("Location: user_dashboard.php");
    } else {
        $_SESSION['error'] = "Error processing refund details. Please try again.";
        header("Location: bank_details_form.php");
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
