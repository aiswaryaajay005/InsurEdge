<?php
session_start();
include '../Connection/Db_connection.php'; // Include your database connection
include 'sidebar.php'; 

// Fetch pending applications from the database
$query = "SELECT applications.id, imsuser.name AS user_name, policies.name AS policy_name, applications.status 
          FROM applications 
          JOIN imsuser ON applications.email = imsuser.email
          JOIN policies ON applications.policy_id = policies.id
          WHERE LOWER(applications.status) = 'pending'";

$result = $conn->query($query);

// Debugging: Check if any rows are returned


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Review - Pending Applications</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-top: 0;
        }

        .container {
            width: 90%;
            max-width: 800px;
            background: #ffffff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 16px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: #ffffff;
            font-weight: 600;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .button-container {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        button {
            padding: 8px 15px;
            cursor: pointer;
            border: none;
            color: white;
            border-radius: 4px;
            background-color: #28a745; /* Approve Button Color */
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #218838;
        }

        .reject-button {
            background-color: #dc3545; /* Reject Button Color */
        }

        .reject-button:hover {
            background-color: #c82333;
        }

        .no-data-message {
            text-align: center;
            font-size: 18px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Review - Pending Applications</h1>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Policy</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['policy_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td class="button-container">
                            <form method="post" action="Approve_application.php" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit">Approve</button>
                            </form>
                            <form method="post" action="Reject_application.php" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="reject-button">Reject</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data-message">No pending applications at the moment.</p>
        <?php endif; ?>

        <?php
        // Close the database connection
        $conn->close();
        ?>
    </div>
</body>
</html>
