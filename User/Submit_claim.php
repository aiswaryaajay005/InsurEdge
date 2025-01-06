<?php
session_start();
include '../Connection/Db_connection.php'; // Include the database connection file
include 'User_sidebar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $customer_name = htmlspecialchars(trim($_POST['customer_name']));
    $customer_email = filter_var(trim($_POST['customer_email']), FILTER_VALIDATE_EMAIL);
    $policy_id = htmlspecialchars(trim($_POST['policy_id']));
    $claim_amount = floatval($_POST['claim_amount']);
    $description = htmlspecialchars(trim($_POST['description']));

    // Validate and parse incident_date
    $incident_date_raw = trim($_POST['incident_date']);
    $incident_date_obj = DateTime::createFromFormat('Y-m-d\TH:i', $incident_date_raw); // Handle datetime-local format
    if ($incident_date_obj) {
        $incident_date = $incident_date_obj->format('Y-m-d H:i:s'); // Convert to DATETIME format
    } else {
        echo "Invalid incident date format. Please use the correct format (YYYY-MM-DDTHH:MM).";
        exit();
    }
    
    // Validate required fields
    if (empty($customer_name) || empty($customer_email) || empty($policy_id) || empty($incident_date) || empty($claim_amount) || empty($description)) {
        echo "All fields are required.";
        exit();
    }

    if (!$customer_email) {
        echo "Invalid email address.";
        exit();
    }

    // Handle file uploads
    $target_dir = "../uploads/claims/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Supporting document upload
    if (isset($_FILES["supporting_documents"]) && $_FILES["supporting_documents"]["error"] === UPLOAD_ERR_OK) {
        $supporting_documents_file = basename($_FILES["supporting_documents"]["name"]);
        $supporting_documents_path = $target_dir . time() . "_" . $supporting_documents_file;

        // Validate file type
        $allowed_extensions = ["pdf", "doc", "docx", "jpg", "jpeg", "png"];
        $file_extension = pathinfo($supporting_documents_file, PATHINFO_EXTENSION);
        if (!in_array(strtolower($file_extension), $allowed_extensions)) {
            echo "Unsupported file type for supporting documents.";
            exit();
        }

        move_uploaded_file($_FILES["supporting_documents"]["tmp_name"], $supporting_documents_path);
    } else {
        echo "Error uploading supporting document.";
        exit();
    }

    // Policy document upload
    if (isset($_FILES["policy_document"]) && $_FILES["policy_document"]["error"] === UPLOAD_ERR_OK) {
        $policy_document_file = basename($_FILES["policy_document"]["name"]);
        $policy_document_path = $target_dir . time() . "_policy_" . $policy_document_file;

        // Validate file type
        $file_extension = pathinfo($policy_document_file, PATHINFO_EXTENSION);
        if (!in_array(strtolower($file_extension), $allowed_extensions)) {
            echo "Unsupported file type for policy documents.";
            exit();
        }

        move_uploaded_file($_FILES["policy_document"]["tmp_name"], $policy_document_path);
    } else {
        echo "Error uploading policy document.";
        exit();
    }

    // Prepare and execute the database insert
    $stmt = $conn->prepare("
        INSERT INTO claims 
        (user_id, customer_name, customer_email, policy_id, incident_date, claim_amount, description, supporting_documents, policy_document, status, submitted_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending', NOW())
    ");
    
    $stmt->bind_param(
        "isssdssss", 
        $_SESSION['user_id'], 
        $customer_name, 
        $customer_email, 
        $policy_id, 
        $incident_date, 
        $claim_amount, 
        $description, 
        $supporting_documents_path, 
        $policy_document_path
    );

    if ($stmt->execute()) {
        $claim_id = $conn->insert_id;
        header("Location:../User/Claim_status.php");
        exit();
    } else {
        echo "Failed to submit claim. Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

