<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Report</title>
   <link rel="stylesheet" href="../CSS/Reportstyle.css">

</head>
<body>
    <h1>Payment Report</h1>
</body>
</html>

<?php
session_start();
include '../Connection/Db_connection.php';
include('User_sidebar.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to view your payment report.");
}

// Get user ID from session
$userId = $_SESSION['user_id'];

// Fetch payments, including status
$query = "
    SELECT p.id AS payment_id, p.policy_id, p.paid_amount, p.payment_date, p.payment_frequency, p.next_payment_date, 
           p.interval_amount, pol.premium, pol.duration, SUM(p.paid_amount) AS total_paid, p.status
    FROM payments p
    INNER JOIN policies pol ON p.policy_id = pol.id
    WHERE p.user_id = ?
    GROUP BY p.policy_id, p.payment_date
";

$stmt = $conn->prepare($query);
if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("i", $userId);
if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
}

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo '<div class="payment-container">';
    echo '<table class="payment-table">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Policy ID</th>';
    echo '<th>Paid Amount</th>';
    echo '<th>Total Paid</th>';
    echo '<th>Balance Amount</th>';
    echo '<th>Next Payment Date</th>';
    echo '<th>Action</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    while ($row = $result->fetch_assoc()) {
        $balance = $row['premium'] - $row['total_paid'];
        $nextPaymentDate = strtotime($row['next_payment_date']); // Convert the next payment date to timestamp
        $currentDate = strtotime(date('Y-m-d')); // Current date (today's date)

        // Calculate 7 days before and 7 days after the next payment date
        $sevenDaysBefore = strtotime('-7 days', $nextPaymentDate); // 7 days before next payment date
        $sevenDaysAfter = strtotime('+7 days', $nextPaymentDate); // 7 days after next payment date

        // Check if current date is within the 7-day window (before or after the next payment date)
        $isWithinAllowedPeriod = ($currentDate >= $sevenDaysBefore && $currentDate <= $sevenDaysAfter);
        $isCanceled = strtolower($row['status']) === 'canceled'; // Check if the policy is canceled

        // Calculate late fee (5% if overdue by more than 7 days)
        $lateFee = 0;
        if ($currentDate > $sevenDaysAfter) {
            $lateFee = $row['premium'] * 0.05; // 5% late fee
            $balance += $lateFee; // Add late fee to balance
        }
        $nominalFee=5 ;
        $totalAmountToPay = $row['interval_amount'] + $nominalFee + $lateFee;

        // Add styling for overdue payments
        $rowClass = ($currentDate > $nextPaymentDate && !$isWithinAllowedPeriod) ? 'overdue-row' : '';

        echo '<tr class="' . $rowClass . '">';
        echo '<td>' . htmlspecialchars($row['policy_id']) . '</td>';
        echo '<td>' . htmlspecialchars($row['paid_amount']) . '</td>';
        echo '<td>' . number_format($row['total_paid'], 2) . '</td>';
        echo '<td>' . number_format($balance, 2) . '</td>';
        echo '<td>' . htmlspecialchars($row['next_payment_date']) . '</td>';

        // Handle canceled policies
        if ($isCanceled) {
            echo '<td><button disabled style="background-color: gray; cursor: not-allowed;">Canceled</button></td>';
        } else {
            // Handle overdue or within payment window
            if ($isWithinAllowedPeriod || $currentDate > $nextPaymentDate) {
                $_SESSION['from_payment_report'] = true;
                $_SESSION['policyId'] = $row['policy_id'];
                $_SESSION['interval_amount'] = $row['interval_amount'];
                $_SESSION['paymentAmount'] = $totalAmountToPay; // Including late fee
                $_SESSION['paymentMethod'] = 'Credit Card';
                $_SESSION['paymentFrequency'] = $row['payment_frequency'];

                echo '<td><a href="Payment_method.php"><button>Proceed to Pay</button></a></td>';
            } else {
                echo '<td><button disabled>Payment not available yet</button></td>';
            }
        }

        echo '</tr>';

        // Send a reminder if the payment is overdue
        if ($currentDate > $nextPaymentDate) {
            $reminderMessage = "Your premium payment is overdue. Please make the payment as soon as possible.";
            $notificationQuery = "INSERT INTO user_notifications (user_id, message, created_at) VALUES (?, ?, NOW())";
            $stmtNotification = $conn->prepare($notificationQuery);
            if ($stmtNotification === false) {
                die("Error preparing notification statement: " . $conn->error);
            }
            $stmtNotification->bind_param("is", $userId, $reminderMessage);
            $stmtNotification->execute();
            $stmtNotification->close(); // Close notification statement
        }

       
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';
} else {
    echo "<p>No payment records found.</p>";
}

$stmt->close(); // Close the main query statement
$conn->close(); // Close the database connection
?>
