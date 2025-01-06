<?php
include 'Sidebar.php'; // Sidebar for navigation
include '../Connection/Db_connection.php'; // Database connection file

// Fetch accepted claims that have not been paid
$query = "SELECT c.id AS claim_id, c.policy_id, c.claim_amount, c.user_id, u.name AS customer_name, 
                 u.email AS customer_email, b.bank_name, b.account_number, b.ifsc_code 
          FROM claims c
          INNER JOIN imsuser u ON c.user_id = u.id
          INNER JOIN bank_details b ON c.user_id = b.user_id
          WHERE c.status = 'Accepted'";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Payouts</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #970747;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table thead {
            background-color: #970747;
            color: #ffffff;
        }

        table th, table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f2f2f2;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        label {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        input[type="number"], select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #970747;
            color: #ffffff;
            border: none;
            padding: 10px 15px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #750532;
        }

        p {
            text-align: center;
            color: #555;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            table {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Pending Payouts</h2>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Claim ID</th>
                        <th>Policy ID</th>
                        <th>Claim Amount</th>
                        <th>Customer Name</th>
                        <th>Customer Email</th>
                        <th>Bank Name</th>
                        <th>Account Number</th>
                        <th>IFSC Code</th>
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
                            <td><?php echo $row['bank_name']; ?></td>
                            <td><?php echo $row['account_number']; ?></td>
                            <td><?php echo $row['ifsc_code']; ?></td>
                            <td>
                                <form method="post" action="Process_payout.php">
                                    <input type="hidden" name="claim_id" value="<?php echo $row['claim_id']; ?>">
                                    <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                                    <label for="payout_amount">Payout Amount:</label>
                                    <input type="number" name="payout_amount" step="0.01" required>
                                    <label for="payment_method">Payment Method:</label>
                                    <select name="payment_method" required>
                                        <option value="Bank Transfer">Bank Transfer</option>
                                        <option value="PayPal">PayPal</option>
                                    </select>
                                    <button type="submit">Make Payout</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No pending payouts.</p>
        <?php endif; ?>
    </div>
</body>
</html>

