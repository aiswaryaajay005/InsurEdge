<?php
session_start();
include '../../Connection/Db_connection.php'; // Database connection
// Sidebar navigation
if (!isset($_SESSION['admin_username'])) {
    die("Unauthorized access. Please log in as admin.");
}

if (isset($_SESSION['success_message'])) {
    echo '<div class="success-message" style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border: 1px solid #c3e6cb; border-radius: 5px;">
        ' . $_SESSION['success_message'] . '
    </div>';
    unset($_SESSION['success_message']); // Clear the message after displaying
}


// Fetch all pending cancellation requests
$sql = "SELECT * FROM cancellation_requests WHERE status = 'pending' ORDER BY created_at DESC";
$result = $conn->query($sql);

// Check if there are any pending requests
$no_requests = ($result->num_rows === 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Pending Cancellation Requests</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            z-index: 1;
        }

        h2 {
            text-align: center;
            font-size: 28px;
            color: #333;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .table-box {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
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
            color: #555;
        }

        .styled-table th {
            background-color: #970747;
            color: white;
            font-size: 16px;
        }

        .styled-table td {
            font-size: 14px;
        }

        .styled-table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .styled-table tbody tr:hover {
            background-color: #d1e7ff;
            cursor: pointer;
        }

        .approve-btn, .reject-btn {
            padding: 8px 20px;
            font-size: 14px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
        }

        .approve-btn {
            background-color: #4caf50;
            color: white;
        }

        .approve-btn:hover {
            background-color: #45a049;
        }

        .reject-btn {
            background-color: #f44336;
            color: white;
        }

        .reject-btn:hover {
            background-color: #e53935;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .container {
                width: 95%;
            }

            .styled-table th, .styled-table td {
                font-size: 12px;
            }

            .approve-btn, .reject-btn {
                padding: 6px 15px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Pending Cancellation Requests</h2>
        <div class="table-box">
            <?php if ($no_requests): ?>
                <p>No pending cancellation requests found.</p>
            <?php else: ?>
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Policy ID</th>
                            <th>Policy Name</th>
                            <th>Reason</th>
                            <th>Requested Cancellation Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['policy_id']); ?></td>
                                <td><?= htmlspecialchars($row['policy_name']); ?></td>
                                <td><?= htmlspecialchars($row['reason']); ?></td>
                                <td><?= htmlspecialchars($row['cancellation_date']); ?></td>
                                <td>
                                    <a href="Admin_process_cancellation.php?action=approve&id=<?= $row['id']; ?>" class="approve-btn">Approve</a>
                                    <a href="Admin_process_cancellation.php?action=reject&id=<?= $row['id']; ?>" class="reject-btn">Reject</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
