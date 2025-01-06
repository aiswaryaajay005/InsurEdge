<?php
session_start();
include '../../Connection/Db_connection.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_username'])) {
    die("Unauthorized access. Please log in as admin.");
}

// Query to get cancelled policies
$query = "SELECT * FROM cancellation_requests WHERE status = 'canceled' ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

// Back to Admin Dashboard Button
echo "<div class='header-container'>";
echo "<a href='../Adminpage.php' class='btn-back'>Back to Admin Dashboard</a>";
echo "</div>";

// Check if any cancelled policies exist
if ($result->num_rows > 0) {
    echo "<div class='cancelled-policies-container'>";
    echo "<h2>Cancelled Policies</h2>";
    echo "<table>";
    echo "<thead>
            <tr>
                <th>Policy ID</th>
                <th>User ID</th>
                <th>Refund Amount</th>
                <th>Status</th>
                <th>Cancellation Date</th>
            </tr>
          </thead>";
    echo "<tbody>";
    
    // Display each cancelled policy
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['policy_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
        echo "<td>₹" . number_format($row['refund_amount'], 2) . "</td>";
        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
        echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
    echo "</div>";
} else {
    echo "<p>No cancelled policies found.</p>";
}
?>

<!-- Style for the page -->
<style>
    .cancelled-policies-container {
        font-family: Arial, sans-serif;
        margin: 30px auto;
        padding: 20px;
        max-width: 1000px;
        background-color: #f9f9f9;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        color: #970747;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table, th, td {
        border: 1px solid #ddd;
    }

    th, td {
        padding: 12px;
        text-align: center;
    }

    th {
        background-color: #970747;
        color: white;
    }

    td {
        background-color: #f9f9f9;
        color: #333;
    }

    tr:hover {
        background-color: #f1f1f1;
    }

    /* Back to Admin Dashboard Button */
    .header-container {
        text-align: center;
        margin: 20px 0;
    }

    .btn-back {
        display: inline-block;
        padding: 10px 20px;
        background-color: #970747;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-size: 16px;
    }

    .btn-back:hover {
        background-color: #970747;
    }
</style>
