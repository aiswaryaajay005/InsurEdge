<?php
session_start();
include '../Connection/Db_connection.php';

// Check if the cancellation request has been approved
$request_id = $_GET['id'];

// Fetch the cancellation request and check the status
$sql = "SELECT * FROM cancellation_requests WHERE id = ? AND status = 'approved'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $request_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("This request is not approved for cancellation.");
}

$request = $result->fetch_assoc();

// Display the bank details form
?>

<h2>Bank Details for Refund</h2>
<form action="Process_refund.php" method="POST">
    <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">
    
    <label for="account_holder">Account Holder Name:</label>
    <input type="text" name="account_holder" required>
    
    <label for="bank_name">Bank Name:</label>
    <input type="text" name="bank_name" required>
    
    <label for="account_number">Account Number:</label>
    <input type="text" name="account_number" required>
    
    <label for="ifsc_code">IFSC Code:</label>
    <input type="text" name="ifsc_code" required>
    
    <label for="remarks">Additional Remarks:</label>
    <textarea name="remarks"></textarea>
    
    <button type="submit">Submit Refund Details</button>
</form>
