<?php
session_start();
include '../Connection/Db_connection.php';
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['emailid'] ?? '');
    $password = trim($_POST['password'] ?? '');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($password)) {
        die("Invalid email or password format.");
    }
    $query = "SELECT id, password FROM imsuser WHERE email = ?";
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            if ($password == $row['password']) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['email'] = $email;
                $login_time = date('Y-m-d H:i:s');
                $insert_query = "INSERT INTO login_details (email, login_time) VALUES (?, ?)";
                $insert_stmt = $conn->prepare($insert_query);
                if ($insert_stmt === false) {
                    die("Error preparing login details insert statement: " . $conn->error);
                }
                $insert_stmt->bind_param("ss", $email, $login_time);
                if ($insert_stmt->execute()) {
                    echo "Login details inserted successfully.";
                    header("Location: Userdashboard.php");
                    exit();
                } else {
                    die("Error executing insert query: " . $insert_stmt->error);
                }
            } else {
                die("Invalid email or password.");
            }
        } else {
            die("Invalid email or password.");
        }
    } else {
        die("Error executing query.");
    }
} else {
    die("Invalid request method.");
}
?>
