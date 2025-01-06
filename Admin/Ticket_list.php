<?php
//ticket_list.php

include 'sidebar.php'; 

// Database connection
$servername = "localhost"; // Update with your server details
$username = "root"; // Update with your MySQL username
$password = ""; // Update with your MySQL password
$dbname = "users"; // Update with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all tickets
$sql = "SELECT id, name, subject, priority, created_at, admin_reply FROM tickets";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View Tickets</title>
    <link rel="stylesheet" href="../CSS/Ticketliststyle.css"> <!-- Optional: For styling -->
</head>
<body>
    <div class="container">
        <h1>All Tickets</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Subject</th>
                    <th>Priority</th>
                    <th>Date Submitted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["subject"]) . "</td>";
                        echo "<td>" . ucfirst(htmlspecialchars($row["priority"])) . "</td>";
                        echo "<td>" . htmlspecialchars($row["created_at"]) . "</td>";

                        // Check if admin_reply is not empty
                        if (!empty($row["admin_reply"])) {
                            echo "<td><span class='replied-label'>Replied</span></td>";
                        } else {
                            echo "<td><a href='Ticket_view_reply.php?id=" . htmlspecialchars($row["id"]) . "'>View & Reply</a></td>";
                        }
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='no-data'>No tickets found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
// Close connection
$conn->close();
?>

