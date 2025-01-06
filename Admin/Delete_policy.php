<?php
// delete_policy.php

session_start();
$conn = new mysqli("localhost", "root", "", "users");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// // Check if the user is logged in as admin
// if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
//     header('Location: admin_login.php');
//     exit();
// }

// Check if a policy ID is provided in the URL
if (isset($_GET['id'])) {
    $policy_id = $_GET['id'];

    // Check if the policy is referenced in the approved_policies table (foreign key check)
    $check_query = "SELECT * FROM approved_policies WHERE policy_id = '$policy_id' LIMIT 1";
    $result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($result) > 0) {
        // Policy is referenced in approved_policies, cannot be deleted
        echo '<script>alert("This policy can\'t be deleted as it is an active policy."); window.location.href="Adminpage.php";</script>';
        exit();
    }

    // If the policy is not referenced in approved_policies, delete it from the database
    $delete_query = "DELETE FROM policies WHERE id='$policy_id'";

    if (mysqli_query($conn, $delete_query)) {
        echo '<script>alert("Policy deleted successfully."); window.location.href="Adminpage.php";</script>';
        exit();
    } else {
        echo "Can't delete the policy.";
    }
} else {
    echo "No policy ID provided.";
    exit();
}
?>
