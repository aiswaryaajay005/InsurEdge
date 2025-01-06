<?php
session_start();
include '../Connection/Db_connection.php'; // Include database connection
include 'User_sidebar.php'; // Include the user sidebar

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Fetch all claims for the logged-in user
$query = "SELECT id, policy_id, incident_date, claim_amount, status, rejection_reason, payout_amount 
          FROM claims WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Claim Status</title>
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
            color: #333;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h2 {
            color: #970747;
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        thead {
            background-color: #970747;
            color: #fff;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            font-weight: bold;
        }

        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tbody tr:hover {
            background-color: #ffe6f2;
        }

        td a {
            color: #970747;
            text-decoration: none;
            font-weight: bold;
        }

        td a:hover {
            text-decoration: underline;
        }

        p {
            text-align: center;
            color: #970747;
            font-size: 16px;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            table {
                font-size: 14px;
            }

            th, td {
                padding: 8px 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Claim Status</h2>
        
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Claim ID</th>
                        <th>Policy ID</th>
                       
                        <th>Claim Amount</th>
                        <th>Status</th>
                        <th>Rejection Reason</th>
                        <th>Payout Amount</th>
                        <th>View Details</th>
                        <th>View Claim Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['policy_id']; ?></td>
                           
                            <td>$<?php echo number_format($row['claim_amount'], 2); ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td><?php echo $row['status'] === 'Rejected' ? $row['rejection_reason'] : 'N/A'; ?></td>
                            <td><?php echo $row['status'] === 'Accepted' ? '$' . number_format($row['payout_amount'], 2) : 'N/A'; ?></td>
                            <td><a href="View_accepted_claims.php?claim_id=<?php echo $row['id']; ?>">View Details</a></td>
                            <td><a href="View_claims.php?claim_id=<?php echo $row['id']; ?>">View Details</a></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No claims found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
