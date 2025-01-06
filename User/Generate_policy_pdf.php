



<?php
session_start();
include '../Connection/Db_connection.php'; // Include database connection
require('fpdf/fpdf.php'); // Include FPDF library

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: Userlogin.php"); // Redirect to login page if not logged in
    exit();
}

// Get the logged-in user's ID from the session
$user_id = $_SESSION['user_id'];

// Fetch user details from the imsuser table
$user_query = "
    SELECT name, email, mobile 
    FROM imsuser 
    WHERE id = ?
";
$user_stmt = $conn->prepare($user_query);
$user_stmt->bind_param("i", $user_id); // Use 'i' for integer
$user_stmt->execute();
$user_result = $user_stmt->get_result();

// Check if the user exists
if ($user_result->num_rows > 0) {
    $user = $user_result->fetch_assoc();
} else {
    die('User details not found.');
}

// Get the policy_id from the URL
if (isset($_GET['policy_id'])) {
    $policy_id = $_GET['policy_id'];
} else {
    die('Policy ID is missing.');
}

// Fetch policy details from the database
$query = "
    SELECT p.id AS policy_id, p.name AS policy_name, p.type AS policy_type, p.premium, 
           p.duration, p.coverage, p.description, p.created_at, pay.start_date, pay.end_date, pay.status AS payment_status
    FROM payments pay
    JOIN policies p ON pay.policy_id = p.id
    WHERE p.id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $policy_id); // Use 's' for string
$stmt->execute();
$result = $stmt->get_result();

// Check if policy exists
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Create a new instance of the FPDF class
    $pdf = new FPDF();
    $pdf->AddPage();

    // Set title and company logo
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(200, 10, 'INSUREDGE INSURANCE', 0, 1, 'C'); // Company name at the top
    $pdf->Image('../assets/images/Screenshot (31).png', 10, 10, 30); // Company logo (adjust path)

    // Add some space
    $pdf->Ln(20);

    // User details section
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(200, 10, 'Policy Document', 0, 1, 'C');
    $pdf->Ln(10);

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(100, 10, 'Policyholder Details:', 0, 1);
    $pdf->Ln(5);
    $pdf->Cell(100, 10, 'Name: ' . $user['name']);
    $pdf->Ln(10);
    $pdf->Cell(100, 10, 'Email: ' . $user['email']);
    $pdf->Ln(10);
    $pdf->Cell(100, 10, 'Phone: ' . $user['mobile']);
    $pdf->Ln(20);

    // Policy details section
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(100, 10, 'Policy Details:', 0, 1);
    $pdf->Ln(5);

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(100, 10, 'Policy ID: ' . $row['policy_id']);
    $pdf->Ln(10);
    $pdf->Cell(100, 10, 'Policy Name: ' . $row['policy_name']);
    $pdf->Ln(10);
    $pdf->Cell(100, 10, 'Policy Type: ' . $row['policy_type']);
    $pdf->Ln(10);
    $pdf->Cell(100, 10, 'Premium: ' . number_format($row['premium'], 2));
    $pdf->Ln(10);
    $pdf->Cell(100, 10, 'Duration: ' . $row['duration'] . ' years');
    $pdf->Ln(10);
    $pdf->Cell(100, 10, 'Coverage: ' . number_format($row['coverage'], 2));
    $pdf->Ln(10);
    $pdf->Cell(100, 10, 'Start Date: ' . $row['start_date']);
    $pdf->Ln(10);
    $pdf->Cell(100, 10, 'End Date: ' . $row['end_date']);
    $pdf->Ln(10);

    // Display the payment status
    $pdf->Cell(100, 10, 'Policy Status: ' . $row['payment_status']);
    $pdf->Ln(20);

    // Add Terms and Conditions Section
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(200, 10, 'Terms and Conditions', 0, 1, 'C');
    $pdf->Ln(10);

    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(0, 10, '1. Non-payment of Premiums: If the policyholder fails to pay the premium within the due date, the policy may be deemed lapsed...');
    // Claims Processing
$pdf->MultiCell(0, 10, '2. Claims Processing: Claims will only be processed for active policies. If premiums are unpaid, the insurer may deny or reduce the claim amount based on the premiums paid.');

// Claim Denial Due to Unpaid Premiums
$pdf->MultiCell(0, 10, '3. Claim Denial Due to Unpaid Premiums: Claims may be denied or deferred if premiums are not paid. Policies lapsed for 3 months or more will be considered forfeited, and claims made after that may be rejected.');

// Policyholder’s Responsibilities
$pdf->MultiCell(0, 10, '4. Policyholder’s Responsibilities: The policyholder must ensure timely premium payments. Failure to do so may result in loss of coverage and claim settlement rights.');

// Refund of Premiums
$pdf->MultiCell(0, 10, '5. Refund of Premiums: If the policyholder cancels the policy within the free-look period, a full refund will be issued. After that period, the insurer may offer a pro-rata refund based on the coverage period.');
$pdf->Ln(10);
// Claim Payout and Non-payment of Premiums
$pdf->MultiCell(0, 10, '6. Claim Payout and Non-payment of Premiums: If a policyholder files a claim and receives a payout, they are still obligated to pay the premiums for the continued coverage of the policy. Failure to pay premiums after a claim may result in the policy being considered lapsed, terminated, or cancelled. The insurer reserves the right to recover the claim payout amount if premiums remain unpaid for a specified period, as outlined in the policy agreement.');
$pdf->Ln(10);

// Recovery of Paid Claims
$pdf->MultiCell(0, 10, '7. Recovery of Paid Claims: In cases where a claim has been paid out but premiums have not been paid, the insurer may seek to recover the payout amount by deducting it from future premiums, or through legal action if necessary. Non-payment of premiums after a claim may lead to cancellation of the policy and forfeiture of further coverage.');
$pdf->Ln(10);

// Denial of Future Claims
$pdf->MultiCell(0, 10, '8. Denial of Future Claims: If premiums remain unpaid for a prolonged period, the insurer reserves the right to deny any future claims or reduce the claim payout based on the premiums paid. The policy may be considered void if premiums are not paid and the policy is lapsed or cancelled.');
$pdf->Ln(10);

// Revival Terms
$pdf->MultiCell(0, 10, '9. Revival Terms: If the policyholder fails to pay premiums after a claim payout, they may apply for revival of the policy, subject to payment of the outstanding premiums along with any applicable interest or charges. A medical checkup may be required depending on the insurer\'s terms.');
$pdf->Ln(10);


    // Add footer with company contact details
    $pdf->Ln(20);
    $pdf->SetFont('Arial', 'I', 12);
    $pdf->Cell(100, 10, 'Contact Us:', 0, 1);
    $pdf->Ln(5);
    $pdf->Cell(100, 10, 'Email: insuredgeinsurance@gmail.com');
    $pdf->Ln(5);
    $pdf->Cell(100, 10, 'Phone: +91 9048820456');
    $pdf->Ln(30);
   // Add a seal or footer (optional)
   $pdf->Ln(30);
   $pdf->Image('../assets/images/White Black Elegant Concept Football Club Logo.png', 100, 200, 30); 
    // Footer note
    $pdf->SetY(-15);
    $pdf->SetFont('Arial', 'I', 8);
    $pdf->Cell(0, 10, 'This is a computer-generated document and does not require a signature.', 0, 0, 'C');

    // Output the document
    $pdf->Output('D', 'Policy_Document_' . $policy_id . '.pdf'); 

} else {
    echo "Policy not found.";
}

$stmt->close();
$user_stmt->close();
$conn->close();
?>
