<?php 
// Database connection
$conn = mysqli_connect("localhost", "root", "", "users");

// Check connection
if ($conn === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Retrieve form data with null coalescing to avoid undefined variable errors
$name = trim($_POST['name'] ?? '');  
$email = trim($_POST['email'] ?? '');
$gender = trim($_POST['gender'] ?? '');
$phone = trim($_POST['mobile'] ?? '');
$password = $_POST['password'] ?? '';
$cpass = $_POST['cpassword'] ?? '';

// Server-side validation
$nameRegex = "/^[A-Za-z\s]+$/";
$phoneRegex = "/^\d{10}$/";
$emailRegex = "/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/";

$errors = [];

// Validate each input
if (empty($name) || !preg_match($nameRegex, $name)) {
    $errors[] = "Invalid name. Name must contain only alphabets.";
}

if (empty($phone) || !preg_match($phoneRegex, $phone)) {
    $errors[] = "Invalid phone number. Phone number must be 10 digits.";
}

if (empty($email) || !preg_match($emailRegex, $email)) {
    $errors[] = "Invalid email address. Ensure the email has no spaces or uppercase letters.";
}

if (empty($password) || strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
}

if (empty($cpass) || $password !== $cpass) {
    $errors[] = "Passwords do not match.";
}

if (empty($gender) || !in_array($gender, ['Male', 'Female'])) {
    $errors[] = "Invalid gender selection.";
}

// If there are validation errors, redirect back with an error message
if (!empty($errors)) {
    echo "<script>alert('ERROR: " . implode("\\n", $errors) . "'); window.location.href='../API/Register.php';</script>";
    exit();
}

// Check if the email already exists in the database
$email_check_query = "SELECT * FROM imsuser WHERE email = ?";
$stmt = $conn->prepare($email_check_query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<script>alert('ERROR: Email already exists. Please use a different email.'); window.location.href='../API/Register.php';</script>";
    exit();
}

// Prepare an insert query using prepared statements
$insert_query = "INSERT INTO imsuser (name, email, gender, mobile, password) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($insert_query);
$stmt->bind_param("sssss", $name, $email, $gender, $phone, $password);

// Execute the query
if ($stmt->execute()) {
    echo "<script>alert('Registration successful!'); window.location.href='../User/Userlogin.php';</script>";
    exit();
} else {
    echo "<script>alert('ERROR: Could not execute query: " . $stmt->error . "'); window.location.href='../API/Register.php';</script>";
}

// Close the prepared statement and connection
$stmt->close();
$conn->close();
?>



