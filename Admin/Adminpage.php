<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../CSS/adminstyle.css">
    <!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<!-- Font Awesome for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
 <!-- Link to external CSS file -->
</head>
<body>
    <div class="sidebar">
        <h2>Admin Menu</h2>
        <ul>
            <li><a href="Add_policy_form.php">Add New Policy</a></li>
            <li><a href="View_policies.php">View All Policies</a></li>
            <li><a href="Admin_review.php">Pending Policies</a></li>
            <li><a href="Accepted_policies.php">View Accepted Policies</a></li>
            <li><a href="View_customers.php">View All Customers</a></li>
            <li><a href="Admin_claims.php">Pending Claims</a></li>
            <li><a href="Admin_payout.php">Pending Payouts</a></li>
            <li><a href="Completed_payouts.php">Completed Payouts</a></li>
            <li><a href="../Admin/Policy_cancel/Admin_review_cancellations.php">Cancellation Request</a></li>
            <li><a href="../Admin/Policy_cancel/Accepted_cancel.php">Pending Cancellation Refund</a></li>
            <li><a href="../Admin/Policy_cancel/View_cancelled_policies.php">Cancelled policies</a></li>
            <li><a href="Userview.php">View User Details</a></li>
            <li><a href="Logindetails.php">View Login Details</a></li>
            <li><a href="Admin_view_messages.php">View Feedbacks</a></li>
            <li><a href="Ticket_list.php">View Tickets</a></li>
            <li><a href="Payment_due.php">Premium due</a></li>
            <li><a href="Adminnotif.php">Add Notification</a></li>
            <li><a href="AdminLogout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h1>Admin Dashboard</h1>

        <div class="summary-section">
        <div class="summary-box">
        <i class="fas fa-file-alt"></i>
        <h2>Total Policies</h2>
                <p>
                    <?php
                    // Database connection
                    $servername = "localhost";
                    $username = "root";  // Replace with your database username
                    $password = "";      // Replace with your database password
                    $dbname = "users";   // Replace with your database name

                    // Create connection
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // Fetch total number of policies
                    $sql = "SELECT COUNT(*) AS total_policies FROM policies";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        echo $row['total_policies'];
                    } else {
                        echo "0";
                    }
                    ?>
                </p>
            </div>

            <div class="summary-box">
            <i class="fas fa-users"></i>
            <h2>Total Customers</h2>
                <p>
                    <?php
                    // Fetch total number of customers
                    $sql = "SELECT COUNT(DISTINCT user_email) AS total_customers FROM payments";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        echo $row['total_customers'];
                    } else {
                        echo "0";
                    }
                    ?>
                </p>
            </div>

            <div class="summary-box">
            <i class="fas fa-check-circle"></i>
            <h2>Active Policies</h2>
                <p>
                    <?php
                    // Fetch total number of active policies
                    $sql = "SELECT COUNT(*) AS active_policies FROM payments";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        echo $row['active_policies'];
                    } else {
                        echo "0";
                    }
                    ?>
                </p>
            </div>
            <div class="summary-box">
            <i class="fas fa-exclamation-circle"></i>
            <h2>Pending Claims</h2>
    <p>
        <?php
        // Fetch total number of pending claims with status "Under Review"
        $sql = "SELECT COUNT(*) AS pending_claims FROM claims WHERE status = 'Under Review'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo $row['pending_claims'];
        } else {
            echo "0";
        }
        ?>
    </p>
</div>
            <!-- New Summary Box for Pending Policies -->
            <div class="summary-box">
            <i class="fas fa-clock"></i>
            <h2>Pending Policies</h2>
                <p>
                    <?php
                    // Fetch total number of pending policies
                    $sql = "SELECT COUNT(*) AS pending_policies FROM applications WHERE status = 'Pending'";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        echo $row['pending_policies'];
                    } else {
                        echo "0";
                    }

                    $conn->close();
                    ?>
                </p>
            </div>
        </div>
    </div>
  

    <script src="script.js"></script> <!-- Link to an external JS file -->
</body>
</html>



