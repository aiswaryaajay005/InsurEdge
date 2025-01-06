<?php 
session_start();
include '../User/User_sidebar.php';


// Retrieve policy details from the URL parameters
$policy_id = isset($_GET['policy_id']) ? $_GET['policy_id'] : '';
$policy_name = isset($_GET['policy_name']) ? $_GET['policy_name'] : '';
$email = isset($_GET['email']) ? $_GET['email'] : '';

// Your existing HTML and PHP code for displaying the rules and handling cancellation
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Policy Cancellation Rules</title>
    <link rel="stylesheet" href="styles.css"> 
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1, h2 {
            color: #0d47a1;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        ul li {
            margin-bottom: 15px;
            font-size: 16px;
        }
        .rules-list {
            margin-top: 20px;
            font-size: 16px;
        }
        .footer {
            text-align: center;
            color: #546e7a;
            font-size: 14px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Policy Cancellation Rules</h1>

        <p>We understand that there may be circumstances where you need to cancel your policy. Please read the following rules and terms carefully before submitting a cancellation request.</p>

        <div class="rules-list">
            <h2>1. Eligibility for Cancellation:</h2>
            <ul>
                <li>Policies can only be canceled if they are active. Expired policies are not eligible for cancellation.</li>
                <li>Cancellation requests must be submitted at least 15 days before the next payment due date.</li>
            </ul>

            <h2>2. Premium Refund Policy:</h2>
            <ul>
                <li>Only unused portions of the premium will be refunded.</li>
                <li>Refunds will exclude administrative fees, applicable taxes, and processing charges.</li>
                <li>No refund will be issued for policies canceled after 75% of the policy duration has elapsed.</li>
            </ul>

            <h2>3. Cancellation Fees:</h2>
            <ul>
                <li>A standard cancellation fee of 5-10% of the total premium amount will be deducted.</li>
                <li>If a claim has been processed during the policy term, no refund will be provided, and cancellation will only terminate further coverage.</li>
            </ul>

            <h2>4. Impact on Benefits:</h2>
            <ul>
                <li>Upon cancellation, all benefits, including claim eligibility and coverage, will cease immediately.</li>
                <li>Any claims submitted after the cancellation request will be invalid.</li>
            </ul>

            <h2>5. Documentation Requirements:</h2>
            <ul>
                <li>Users must provide a valid reason for cancellation in the request form.</li>
                <li>Additional documents (e.g., identity proof, policy details) may be required for processing the request.</li>
            </ul>

            <h2>6. Refund Processing Time:</h2>
            <ul>
                <li>Refunds will be processed within 14 working days after approval.</li>
                <li>The refund amount will be transferred to the bank account details provided during the cancellation process.</li>
            </ul>

            <h2>7. Non-Refundable Scenarios:</h2>
            <ul>
                <li>Policies canceled due to fraudulent activities by the policyholder are not eligible for refunds.</li>
                <li>Add-ons or riders purchased with the policy are non-refundable.</li>
            </ul>

            <h2>8. Pending Payments:</h2>
            <ul>
                <li>If there are any pending payments at the time of cancellation, they must be cleared before the cancellation request can be processed.</li>
            </ul>

            <h2>9. Reinstatement:</h2>
            <ul>
                <li>Once a policy is canceled, it cannot be reinstated. Users must purchase a new policy if coverage is required.</li>
            </ul>

            <h2>10. Company's Right to Decline:</h2>
            <ul>
                <li>The company reserves the right to decline cancellation requests if terms and conditions are violated or documentation is incomplete.</li>
            </ul>
        </div>

        <h3>Next Steps:</h3>
        <p>If you wish to proceed with canceling your policy, please submit the cancellation request form after reviewing these rules.</p>
        
   
        <div class="footer">Powered by InsurEdge | Enhancing Your Insurance Experience</div>
    </div>
</body>
</html>
<?php
$policy_id = $row['policy_id']; // Assuming $row is fetched from the database
$policy_name = $row['policy_name'];
$email = $row['user_email']; // Assuming the user's email is stored in the database

// Create the link with the correct parameters
echo '<a href="Policy_cancellation_request_form.php?policy_id=' . urlencode($policy_id) . '&policy_name=' . urlencode($policy_name) . '&email=' . urlencode($email) . '">Request Policy Cancellation</a>';
?>