<?php
// Start the session
session_start();
include('User_sidebar.php');
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../User/Userlogin.php"); // Redirect to login page if not logged in
    exit();
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "users");

// Check connection
if ($conn === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Fetch user data based on session user_id
$user_id = $_SESSION['user_id']; // Assuming user_id is stored in session upon login
$query = "SELECT name, email, gender, mobile FROM imsuser WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id); // "i" specifies the type (integer)
$stmt->execute();
$result = $stmt->get_result();

// Fetch the result as an associative array
$user_data = $result->fetch_assoc();

// Close the connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="../CSS/ProfileStyle.css">
   
</head>

<body>

    <div class="container">
       
    <a href="Userdashboard.php" class="edit-btn">Back home</a>
        <h1>User Profile</h1>
        <div id="profile">
            <h3>Personal Details</h3>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user_data['name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user_data['email']); ?></p>
            <p><strong>Gender:</strong> <?php echo htmlspecialchars($user_data['gender']); ?></p>
            <p><strong>Mobile Number:</strong> <?php echo htmlspecialchars($user_data['mobile']); ?></p>

            <a href="Edit_profile.php" class="edit-btn">Edit Profile</a>
            <a href="../User/Logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

</body>

</html>
