<?php
include 'admin_sidebar.php'; // Sidebar for navigation
include '../Connection/Db_connection.php'; // Database connection file

// Fetch accepted claims
$query = "SELECT c.id AS claim_id, c.policy_id, c.claim_amount, u.name AS customer_name, u.email AS customer_email
          FROM claims c
          INNER JOIN imsuser u ON c.user_id = u.id
          WHERE c.status = 'Accepted'";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accepted Claims</title>
    <link rel="stylesheet" href="../CSS/adminstyle.css">
</head>
<body>
    <div class="container">
        <h2>Accepted Claims</h2>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Claim ID</th>
                        <th>Policy ID</th>
                        <th>Claim Amount</th>
                        <th>Customer Name</th>
                        <th>Customer Email</th>
                        <th>Payout</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['claim_id']; ?></td>
                            <td><?php echo $row['policy_id']; ?></td>
                            <td><?php echo $row['claim_amount']; ?></td>
                            <td><?php echo $row['customer_name']; ?></td>
                            <td><?php echo $row['customer_email']; ?></td>
                            <td><a href="Process_payout.php?claim_id=<?php echo $row['claim_id']; ?>&user_id=<?php echo $row['user_id']; ?>">Process Payout</a></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No accepted claims.</p>
        <?php endif; ?>
    </div>
</body>
</html>
