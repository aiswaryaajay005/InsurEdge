<?php
session_start();
include '../Connection/Db_connection.php'; // Include your database connection
include('User_sidebar.php');
// Check if user is logged in
if (!isset($_SESSION['email'])) {
    echo "You must be logged in to view this page.";
    exit();
}

$email = $_SESSION['email']; // Get the user's email from session

// Fetch approved policies for the logged-in user
$query = "SELECT applications.id AS application_id, policies.name AS policy_name, policies.description, policies.type, policies.duration, policies.premium, policies.coverage
          FROM applications
          JOIN policies ON applications.policy_id = policies.id
          WHERE applications.email = ? AND applications.status = 'Approved'";  // Fetch only approved policies
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Close the statement
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Approved Policies</title>
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
            width: 80%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f5f5f5;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h1 {
            color: #970747;
            text-align: center;
            margin-bottom: 20px;
        }

        .policy-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .policy-card {
            width: 300px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .policy-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .policy-card h3 {
            color: #970747;
            margin-bottom: 10px;
        }

        .policy-card p {
            font-size: 14px;
            line-height: 1.6;
            color: #555;
        }

        .policy-card p strong {
            color: #970747;
        }

        /* No approved policies message */
        p {
            text-align: center;
            font-size: 16px;
            color: #970747;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Your Approved Policies</h1>
        <div class="policy-grid">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="policy-card">
                        <h3><?php echo htmlspecialchars($row['policy_name']); ?></h3>
                        <p>
                            <strong>Type:</strong> <?php echo htmlspecialchars($row['type']); ?><br>
                            <strong>Duration:</strong> <?php echo htmlspecialchars($row['duration']); ?> years<br>
                            <strong>Premium:</strong> ₹<?php echo htmlspecialchars($row['premium']); ?><br>
                            <strong>Coverage:</strong> ₹<?php echo htmlspecialchars($row['coverage']); ?><br>
                            <strong>Description:</strong> <?php echo htmlspecialchars($row['description']); ?><br>
                        </p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>You have no approved policies at the moment.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php
    // Close the database connection
    $conn->close();
    ?>
</body>
</html>
