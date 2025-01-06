<?php
include 'Sidebar.php'; // Sidebar for navigation
include '../Connection/Db_connection.php'; // Database connection file

// Fetch completed payouts
$query = "SELECT p.id AS payout_id, p.claim_id, p.payout_amount, p.payment_date, p.payment_method, u.name AS customer_name, u.email AS customer_email
          FROM payouts p
          INNER JOIN imsuser u ON p.user_id = u.id
          ORDER BY p.payment_date DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completed Payouts</title>
<style>/* General Styling */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.container {
    width: 80%;
    margin: 20px auto;
    background-color: #ffffff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    color: #970747;
    margin-bottom: 20px;
}

/* Table Styling */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table th, table td {
    padding: 10px;
    text-align: left;
    border: 1px solid #ddd;
}

table th {
    background-color: #970747;
    color: white;
    font-weight: bold;
}

table td {
    background-color: #f9f9f9;
}

table tr:hover {
    background-color: #f1f1f1;
}

/* Button Styling */
.button {
    padding: 10px 15px;
    background-color: #970747;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    display: inline-block;
    text-align: center;
    font-size: 14px;
    margin-top: 5px;
}

.button:hover {
    background-color: #970747;
    transition: background-color 0.3s ease;
}

/* Responsive Design */
@media screen and (max-width: 768px) {
    .container {
        width: 95%;
    }

    table th, table td {
        font-size: 12px;
        padding: 8px;
    }

    h2 {
        font-size: 24px;
    }
}
</style><!-- Link to external CSS file -->
</head>
<body>
    <div class="container">
        <h1>Completed Payouts</h1>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Payout ID</th>
                        <th>Claim ID</th>
                        <th>Payout Amount</th>
                        <th>Payment Method</th>
                        <th>Payment Date</th>
                        <th>Customer Name</th>
                        <th>Customer Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['payout_id']; ?></td>
                            <td><?php echo $row['claim_id']; ?></td>
                            <td>₹<?php echo number_format($row['payout_amount'], 2); ?></td>
                            <td><?php echo $row['payment_method']; ?></td>
                            <td><?php echo $row['payment_date']; ?></td>
                            <td><?php echo $row['customer_name']; ?></td>
                            <td><?php echo $row['customer_email']; ?></td>
                            <td>
                                <a href="Adminnotif.php" class="button">Notify Customer</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No completed payouts available.</p>
        <?php endif; ?>
    </div>
</body>
</html>
