<?php
include '../Connection/Db_connection.php'; 
$type = 'Home'; // Set the policy type
$query = "SELECT * FROM policies WHERE type = '$type'"; // Query to fetch health policies
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Insurance Policies</title>
    <link rel="stylesheet" href="../CSS/Vhhleachstyle.css">
</head>
<body>
    <header>
        <h1>Home Insurance Policies</h1>
    </header>
    <main>
        <section class="policy-list">
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <div class="policy-item">
                    <h2><?php echo htmlspecialchars($row['name']); ?></h2>
                    <p><?php echo htmlspecialchars($row['description']); ?></p>
                    <p>Premium: $<?php echo number_format($row['premium'], 2); ?></p>
                    <p>Coverage Duration: <?php echo htmlspecialchars($row['duration']); ?> years</p>
                </div>
            <?php endwhile; ?>
        </section>
        <div class="login-prompt">
            <p>To choose a policy, please <a href="../User/Userlogin.html">Login</a> or <a href="../API/Register.php">Register</a>.</p>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 Insurance Management System. All rights reserved.</p>
    </footer>
</body>
</html>