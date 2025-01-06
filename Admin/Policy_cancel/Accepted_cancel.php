<?php
session_start();
include '../../Connection/Db_connection.php';
include '../Sidebar.php';
// Check if the admin is logged in
if (!isset($_SESSION['admin_username'])) {
    die("Unauthorized access. Please log in as admin.");
}

$query = "SELECT * FROM cancellation_requests WHERE status = 'approved'";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();



if ($result->num_rows == 0) {
    echo "<p>No approved cancellation requests found.</p>";
} else {
    echo "<h2>Approved Cancellation Requests</h2>";
    echo "<div class='table-box'>
            <table class='styled-table'>
                <thead>
                    <tr>
                        <th>Policy ID</th>
                        <th>User ID</th>
                        <th>Refund Amount</th>
                        <th>Bank Details</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>";
    
    while ($request = $result->fetch_assoc()) {
        $user_id = $request['user_id'];
        $cancellation_request_id = $request['id'];
        
        // Fetch the bank details for the user
        $bankQuery = "SELECT * FROM refund_bank_details WHERE user_id = ?";
        $bankStmt = $conn->prepare($bankQuery);
        $bankStmt->bind_param("i", $user_id);
        $bankStmt->execute();
        $bankResult = $bankStmt->get_result();
        $bankDetails = $bankResult->fetch_assoc();
        
        echo "<tr>
                <td>" . htmlspecialchars($request['policy_id']) . "</td>
                <td>" . htmlspecialchars($user_id) . "</td>
                <td>$" . number_format($request['refund_amount'], 2) . "</td>
                <td>";
        
        if ($bankDetails) {
            echo "Account Holder: " . htmlspecialchars($bankDetails['account_holder']) . "<br>";
            echo "Account Number: " . htmlspecialchars($bankDetails['account_number']) . "<br>";
            echo "Bank Name: " . htmlspecialchars($bankDetails['bank_name']) . "<br>";
            echo "IFSC Code: " . htmlspecialchars($bankDetails['ifsc_code']);
        } else {
            echo "No bank details found.";
        }
        
        echo "</td>
              <td><a href='../Policy_cancel/Admin_process_refund.php?id=" . htmlspecialchars($cancellation_request_id) . "' class='process-refund-btn'>Process Refund</a></td>
            </tr>";
    }
    
    echo "</tbody>
        </table>
    </div>";
}
?>

<!-- Styling -->
<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #fff;
        margin: 0;
        padding: 0;
        color: #333;
    }

    h2 {
        text-align: center;
        font-size: 32px;
        color: #970747;
        margin-bottom: 30px;
        font-weight: bold;
    }

    .table-box {
        width: 90%;
        margin: 20px auto;
        background-color: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
    }

    .styled-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .styled-table th, .styled-table td {
        padding: 12px;
        text-align: center;
        border: 1px solid #ddd;
        color: #333;
    }

    .styled-table th {
        background-color: #970747;
        color: white;
        font-size: 16px;
    }

    .styled-table td {
        font-size: 14px;
        color: #555;
    }

    .styled-table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .styled-table tbody tr:hover {
        background-color: #f1e0e6;
        cursor: pointer;
    }

    .process-refund-btn {
        display: inline-block;
        padding: 8px 20px;
        background-color: #970747;
        color: white;
        text-decoration: none;
        font-size: 14px;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .process-refund-btn:hover {
        background-color: #8b0236;
    }

    .process-refund-btn:active {
        background-color: #660029;
    }

    p {
        text-align: center;
        color: #970747;
        font-size: 18px;
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .table-box {
            width: 100%;
            padding: 15px;
        }

        .styled-table th, .styled-table td {
            font-size: 12px;
        }

        .process-refund-btn {
            padding: 6px 15px;
            font-size: 12px;
        }
    }
</style>
