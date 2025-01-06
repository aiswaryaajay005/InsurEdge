<?php
include('User_sidebar.php');

// Initialize a variable to track success
$ticketSubmitted = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $priority = $_POST['priority'];

    // Sanitize data to prevent SQL injection
    $name = htmlspecialchars($name);
    $email = htmlspecialchars($email);
    $subject = htmlspecialchars($subject);
    $message = htmlspecialchars($message);
    $priority = htmlspecialchars($priority);

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "users";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert data into the tickets table
    $sql = "INSERT INTO tickets (name, email, subject, message, priority) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $email, $subject, $message, $priority);

    if ($stmt->execute()) {
        $ticketSubmitted = true; // Set success flag
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close connection
    $stmt->close();
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit a Ticket</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
       * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }
        h1 {
            text-align: center;
            color: #970747;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            font-size: 16px;
            color: #34495E;
            margin-bottom: 8px;
        }
        input[type="text"],
        input[type="email"],
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            background-color: #f9f9f9;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        select:focus,
        textarea:focus {
            border-color: #1ABC9C;
            outline: none;
        }
        textarea {
            resize: none;
            height: 100px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #970747;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #970732;
        }
        .form-group select {
            padding: 10px;
        } /* Add your styles here */
    </style>
    <script>
        function validateForm() {
            const name = document.getElementById("name").value.trim();
            const email = document.getElementById("email").value.trim();
            const subject = document.getElementById("subject").value.trim();
            const message = document.getElementById("message").value.trim();
            const priority = document.getElementById("priority").value;

            // Validate name
            if (name === "") {
                alert("Please enter your name.");
                return false;
            }

            // Validate email format
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                alert("Please enter a valid email address.");
                return false;
            }

            // Validate subject
            if (subject === "") {
                alert("Please enter a subject.");
                return false;
            }

            // Validate message
            if (message === "") {
                alert("Please enter your message.");
                return false;
            }

            // Validate priority
            if (!priority) {
                alert("Please select a priority level.");
                return false;
            }

            return true; // All validations passed
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Submit a Ticket</h1>
        <form id="ticketForm" action="Submit_ticket.php" method="POST" onsubmit="return validateForm();">
            <div class="form-group">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="subject">Subject:</label>
                <input type="text" id="subject" name="subject" required>
            </div>
            <div class="form-group">
                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label for="priority">Priority:</label>
                <select id="priority" name="priority" required>
                    <option value="">Select Priority</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit">Submit Ticket</button>
            </div>
        </form>
    </div>
</body>
</html>
