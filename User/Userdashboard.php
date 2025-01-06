<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../CSS/Userdashboardstyle.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        .notification-badge {
            background-color: red;
            color: white;
            font-size: 0.8em;
            border-radius: 50%;
            padding: 2px 6px;
            margin-left: 5px;
            display: none; /* Hidden by default */
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>InsurEdge</h2>
        <ul>
            
            <li><a href="Profile.php"><i class="fas fa-user icon"></i> View Profile</a></li>
            <li><a href="Policyview.php"><i class="fas fa-search icon"></i> Search Policies <span id="new-policies-count" class="notification-badge"></span></a></li>
            <li><a href="Approved_policies.php"><i class="fas fa-thumbs-up icon"></i> Policies Approved <span id="approved-policies-count" class="notification-badge"></span></a></li>
            <li><a href="Policy_details.php"><i class="fas fa-list-alt icon"></i> My Policies</a></li>
            <li><a href="Payment_report.php"><i class="fas fa-credit-card icon"></i> My Payments</a></li>
            <li><a href="Policy_document.php"><i class="fas fa-book icon"></i> Policy Document</a></li>
            <li><a href="Claim_rules.php"><i class="fas fa-clipboard-check icon"></i> Submit Claims</a></li>
            <li><a href="Claim_status.php"><i class="fas fa-info-circle icon"></i> My Claims <span id="claim-status-count" class="notification-badge"></span></a></li>
            <li><a href="View_accepted_claims.php"><i class="fas fa-clipboard-check icon"></i> Accepted Claims</a></li>
            <li><a href="Insurance_guide.php"><i class="fas fa-book icon"></i> Insurance Guide</a></li>
            <li><a href="../User/cancel/View_cancellation_request.php"><i class="fas fa-headset icon"></i> Cancellation Status <span id="cancellation-status-count" class="notification-badge"></span></a></li>
            <li><a href="Submit_ticket.php"><i class="fas fa-headset icon"></i> Support Tickets <span id="support-replies-count" class="notification-badge"></span></a></li>
            <li><a href="Notifications.php"><i class="fas fa-bell icon"></i> Notifications <span id="general-notifications-count" class="notification-badge"></span></a></li>
            <li><a href="Payment_reminder.php"><i class="fas fa-clock icon"></i> Payment Reminder <span id="payment-reminders-count" class="notification-badge"></span></a></li>
            <li><a href="Logout.php"><i class="fas fa-sign-out-alt icon"></i> Logout</a></li>
        </ul>
        <div class="footer">
            <p>&copy; 2024 InsurEdge</p>
        </div>
    </div>

    <div class="main-content">
        <h1>Welcome to InsurEdge!</h1>
        <p>Your insurance management made easy with InsurEdge.</p>
        <button class="btn" onclick="location.href='Profile.php'">View Your Profile</button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
 
    const apiEndpoints = {
    newPolicies: './Get_new_policies.php', // Use relative paths if needed
    approvedPolicies: './Get_approved_policies.php',
    claimStatus: './Get_claim_status.php',
    cancellationStatus: './Get_cancellation_status.php',
    supportReplies: './Get_support_replies.php',
    notifications: './Get_general_notifications.php',
    paymentReminders: './Get_payment_reminders.php'
};

    const badgeIds = {
        newPolicies: 'new-policies-count',
        approvedPolicies: 'approved-policies-count',
        claimStatus: 'claim-status-count',
        cancellationStatus: 'cancellation-status-count',
        supportReplies: 'support-replies-count',
        notifications: 'general-notifications-count',
        paymentReminders: 'payment-reminders-count'
    };

    Object.keys(apiEndpoints).forEach((key) => {
        fetch(apiEndpoints[key])
  .then((response) => {
    if (!response.ok) {
      console.error(`Error fetching ${key}: ${response.statusText}`);
      return null;
    }
    return response.json();
  })
  .then((data) => {
    console.log(data); // Log the data to check its value
    if (data && data.count > 0) {
      const badge = document.getElementById(badgeIds[key]);
      if (badge) {
        badge.textContent = data.count;
        badge.style.display = 'inline-block'; // Ensure badge is visible
      }
    }
  })
  .catch((error) => console.error(`Fetch error for ${key}:`, error));

    });
});

    </script>
</body>
</html>
