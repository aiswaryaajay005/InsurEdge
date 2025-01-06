<?php
session_start(); // Start the session
include '../Connection/Db_connection.php'; // Include database connection

// Check if the user is logged in as an admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: Adminlog.html"); // Redirect to admin login page if not logged in
    exit();
}
include 'sidebar.php'; 

$query = "
    SELECT u.id AS user_id, u.name, u.email, u.mobile, u.gender, 
           p.id AS policy_id, p.name AS policy_name, p.premium, p.duration, 
           pay.start_date, pay.end_date, pay.next_payment_date
    FROM imsuser u
    LEFT JOIN payments pay ON u.id = pay.user_id  -- Use user_id to join with imsuser
    LEFT JOIN policies p ON pay.policy_id = p.id  -- Use pay.policy_id = p.id to join with policies
    WHERE pay.next_payment_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
    ORDER BY u.name ASC
";


$result = $conn->query($query);

// Check if query execution was successful
if ($result === false) {
    die("Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Customers with Payment Due Soon</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color:#970747;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #970747;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #970747;
        }
        .btn-container {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Customers with Payment Due Soon</h1>
        
        <table>
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Gender</th>
                    <th>Policy Name</th>
                    <th>Premium</th>
                    <th>Frequency</th>
                    <th>Next Payment Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Check if any records were found
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['mobile']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['policy_name']) . "</td>";
                        echo "<td>" . number_format($row['premium'], 2) . "</td>";
                        echo "<td>" . htmlspecialchars($row['duration']) . " months</td>";
                        echo "<td>" . htmlspecialchars($row['next_payment_date']) . "</td>";
                        
                        // Action button to send notification
                        echo "<td><a href='Send_internal_notification.php?user_id=" . $row['user_id'] . "&email=" . $row['email'] . "&policy_id=" . $row['policy_id'] . "' class='btn'>Send Reminder</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No payments due in the next 7 days.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="btn-container">
            <a href="Adminpage.php" class="btn">Back to Admin Page</a>
        </div>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
