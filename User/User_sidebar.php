<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard Sidebar</title>
    <link rel="stylesheet" href="../CSS/Usersidebarstyle.css">
</head>
<body>

<!-- Sidebar -->
<div id="sidebar" class="sidebar">
    <h2>User Dashboard</h2>
    <button id="hide-button" class="toggle-button">
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
    </button>
    <ul>
    <li><a href="userdashboard.php"><i class="fas fa-user icon"></i> Home</a></li>
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
</div>

<!-- Content Area -->
<div id="content" class="content">
    <!-- Show Sidebar Button -->
    <button id="show-button">
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
    </button>

  
</div>

<script>
    // Get elements
    const sidebar = document.getElementById('sidebar');
    const hideButton = document.getElementById('hide-button');
    const showButton = document.getElementById('show-button');

    // Show the sidebar when clicking the show button
    showButton.addEventListener('click', function() {
        sidebar.classList.add('show');
        showButton.style.display = 'none';
        hideButton.style.display = 'block';
    });

    // Hide the sidebar when clicking the hide button
    hideButton.addEventListener('click', function() {
        sidebar.classList.remove('show');
        hideButton.style.display = 'none';
        showButton.style.display = 'block';
    });

    // Initially hide the hide button
    hideButton.style.display = 'none';
</script>

</body>
</html>
