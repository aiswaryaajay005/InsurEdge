<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../User/Userlogin.php");
    exit();
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "users");

// Check connection
if ($conn === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Fetch user data based on session user_id
$user_id = $_SESSION['user_id'];
$query = "SELECT name, email, gender, mobile FROM imsuser WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $mobile = $_POST['mobile'];

    // Update user data
    $updateQuery = "UPDATE imsuser SET name = ?, email = ?, gender = ?, mobile = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("sssii", $name, $email, $gender, $mobile, $user_id);

    if ($updateStmt->execute()) {
        $_SESSION['message'] = "Profile updated successfully!";
        header("Location: Profile.php");
        exit();
    } else {
        echo "Error updating profile: " . $conn->error;
    }

    $updateStmt->close();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../CSS/ProfileStyle.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h2 {
            color: #970747;
            text-align: center;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
            color: #555;
        }

        input[type="text"], input[type="email"], select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn-submit {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #970747;
            color: #fff;
            text-align: center;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-submit:hover {
            background-color: lightcoral;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Edit Profile</h2>
        <form action="Edit_profile.php" method="POST">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($user_data['name']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>

            <label for="gender">Gender:</label>
            <select name="gender" id="gender">
                <option value="Male" <?php if ($user_data['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if ($user_data['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                <option value="Other" <?php if ($user_data['gender'] == 'Other') echo 'selected'; ?>>Other</option>
            </select>

            <label for="mobile">Mobile Number:</label>
            <input type="text" name="mobile" id="mobile" value="<?php echo htmlspecialchars($user_data['mobile']); ?>" required>

            <button type="submit" class="btn-submit">Save Changes</button>
        </form>
    </div>

</body>

</html>
