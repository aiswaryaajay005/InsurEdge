
<?php
// Start the session
session_start();

include_once('../Connection/Connection.php');

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize form inputs
    $username = test_input($_POST["username"]);
    $password = test_input($_POST["password"]);

    try {
        // Prepare SQL query to check credentials
        $stmt = $conn->prepare("SELECT * FROM adminlogin WHERE username = :username AND password = :password");
        // Bind parameters
        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':password', $password);
        
        // Execute the statement
        $stmt->execute();
        
        // Check if a matching admin user is found
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Set the session for the admin
            $_SESSION['admin_id'] = $user['id']; // Store admin id in the session
            $_SESSION['admin_username'] = $user['username']; // Optionally, store admin username

            // Redirect to the admin dashboard page
            header("Location: Adminpage.php");
            exit();
        } else {
            // If login fails, show an alert and redirect back to login page
            echo "<script>
                    alert('Invalid username or password. Please try again.');
                    window.location.href = 'Adminlog.html';
                  </script>";
            exit();
        }
    } catch (PDOException $e) {
        // Handle error appropriately (e.g., log it, display a friendly message)
        echo "Error: " . $e->getMessage();
    }
}

// Close the database connection (not necessary in PDO, but for clarity)
$conn = null;
?>

