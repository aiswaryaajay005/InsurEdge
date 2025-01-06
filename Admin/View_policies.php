
<?php
include 'sidebar.php'; 
?>
<!DOCTYPE html>
<!-- view_policies.php -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Policies</title>
    <link rel="stylesheet" href="../CSS/Viewpolicystyle.css">
</head>
<body>
    <h2>All Policies</h2>
    <div class="table-container">
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Type</th>
                <th>Duration (years)</th>
                <th>Premium</th>
                <th>Coverage</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
            <?php
            // Database connection
            
            $conn = new mysqli("localhost", "root", "", "users");

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT * FROM policies";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row["id"] . "</td>
                            <td>" . $row["name"] . "</td>
                            <td>" . $row["type"] . "</td>
                            <td>" . $row["duration"] . "</td>
                            <td>" . $row["premium"] . "</td>
                            <td>" . $row["coverage"] . "</td>
                            <td>" . $row["description"] . "</td>
                            <td class='action-links'>
                                <a href='Edit_policy.php?id=" . $row["id"] . "'>Edit</a> | 
                                <a href='Delete_policy.php?id=" . $row["id"] . "'>Delete</a>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='8' class='no-data'>No policies found</td></tr>";
            }

            $conn->close();
            ?>
        </table>
    </div>
</body>
</html>
