<?php
include 'sidebar.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <link rel="stylesheet" href="../CSS/Userviewstyle.css"> <!-- Link to external CSS file -->
</head>
<body>
    <div class="container">
        <h1>All User Details</h1>
        
        <?php
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

        echo "<table>
            <tr>
                
                <th>Name</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Mobile Number</th>
            </tr>";

        $sql = "SELECT * FROM imsuser";
        $rs = mysqli_query($conn, $sql);
        
        while ($row = mysqli_fetch_array($rs)) {
            echo "<tr>
               
                <td>".$row['name']."</td>
                <td>".$row['email']."</td>
                <td>".$row['gender']."</td>
                <td>".$row['mobile']."</td>
            </tr>";
        }
        
        echo "</table>";
        
        // Close connection
        $conn->close();
        ?>
    </div>
</body>
</html>
