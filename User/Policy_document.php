<?php
session_start();
include '../Connection/Db_connection.php'; // Include database connection
require('fpdf/fpdf.php'); // Include FPDF library

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Get user_id from session
$user_id = $_SESSION['user_id'];

// Fetch all policies for the logged-in user
$query = "
    SELECT p.id AS policy_id, p.name AS policy_name, p.type AS policy_type, p.premium, 
           p.duration, p.coverage, p.description, p.created_at, pay.start_date, pay.end_date,pay.status AS payment_status
    FROM payments pay
    JOIN policies p ON pay.policy_id = p.id
    WHERE pay.user_id = ?
";

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
    <title>Policy Document</title>
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
            color: #970747;
            margin-bottom: 20px;
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
            background-color: pink;
        }
        .btn-container {
            text-align: center;
            margin-top: 20px;
        }
        .downloaded {
            background-color: #28a745;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Your Policies</h1>
        <table>
            <thead>
                <tr>
                    <th>Policy Name</th>
                    <th>Type</th>
                    <th>Premium</th>
                    <th>Duration</th>
                    <th>Coverage</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Policy Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $downloadedClass = isset($_GET['downloaded']) && $_GET['downloaded'] == $row['policy_id'] ? 'downloaded' : '';
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['policy_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['policy_type']) . "</td>";
                        echo "<td>" . number_format($row['premium'], 2) . "</td>";
                        echo "<td>" . htmlspecialchars($row['duration']) . " Years</td>";
                        echo "<td>" . number_format($row['coverage'], 2) . "</td>";
                        echo "<td>" . htmlspecialchars($row['start_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['end_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['payment_status']) . "</td>";
                        echo "<td><a href='Generate_policy_pdf.php?policy_id=" . $row['policy_id'] . "&downloaded=" . $row['policy_id'] . "' class='btn " . $downloadedClass . "'>" . ($downloadedClass ? "Downloaded" : "Download") . "</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No policies found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <div class="btn-container">
            <a href="Userdashboard.php" class="btn">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
