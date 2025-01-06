
<?php
session_start();
include '../Connection/Db_connection.php'; // Include your database connection
include 'sidebar.php'; 
// Fetch the approved policies along with customer names
$query = "
    SELECT imsuser.name as customer_name, policies.name as policy_name, policies.type, policies.premium, approved_policies.status
    FROM approved_policies
    JOIN imsuser ON approved_policies.email_id = imsuser.email
    JOIN policies ON approved_policies.policy_id = policies.id
    WHERE approved_policies.status = 'Approved'
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accepted Policies</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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
            padding: 15px;
            text-align: left;
        }
        th {
            background-color: #970747;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Accepted Policies</h1>

        <table>
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Policy Name</th>
                    <th>Policy Type</th>
                    <th>Premium</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['policy_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['type']); ?></td>
                        <td><?php echo htmlspecialchars($row['premium']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No accepted policies found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
