
<?php
include 'sidebar.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Customers</title>
    <link rel="stylesheet" href="../CSS/Logindetailsstyle.css"> <!-- Link to the external CSS file -->
</head>
<body>
    <div class="container">
        <h2>User Login Details</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Email ID</th>
                <th>Time of Login</th>
            </tr>
            <?php
            // Database connection
            $conn = new mysqli("localhost", "root", "", "users");

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT * FROM login_details";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row["id"] . "</td>
                            <td>" . $row["email"] . "</td>
                            <td>" . $row["login_time"] . "</td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No details found</td></tr>";
            }

            $conn->close();
            ?>
        </table>
    </div>
</body>
</html>
