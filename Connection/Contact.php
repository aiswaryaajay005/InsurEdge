<?php
$conn = mysqli_connect("localhost", "root", "", "users");
// Check connection
if ($conn === false) {
    die("ERROR: Could not connect. "
        . mysqli_connect_error());
}


// Initialize variables
$name = $email = $message = "";
$successMessage = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Insert message into the database
    $stmt = $conn->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        $successMessage = "Your message has been sent successfully!";
    } else {
        $successMessage = "Error sending your message. Please try again.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message Sent</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center; /* Center text */
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .success-message {
            margin-bottom: 20px;
            color: green;
            font-size: 1.2rem;
        }

        .home-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 1rem;
        }

        .home-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Message Status</h2>
    <?php if (!empty($successMessage)): ?>
        <p class="success-message"><?php echo $successMessage; ?></p>
    <?php endif; ?>

    <a href="../API/Home.html" class="home-button">Go to Homepage</a> <!-- Change "index.php" to your homepage filename -->
</div>

</body>
</html>
