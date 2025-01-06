<div id="sidebar" class="sidebar">
    <h2>Admin Dashboard</h2>
    <!-- Hide Button with Hamburger Lines (for closing sidebar) -->
    <button id="hide-button" class="toggle-button">
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
    </button>
    <ul>
        <li><a href="Adminpage.php">Home</a></li>
        <li><a href="Add_policy_form.php">Add New Policy</a></li>
            <li><a href="View_policies.php">View All Policies</a></li>
            <li><a href="Admin_review.php">Pending Policies</a></li>
            <li><a href="Accepted_policies.php">View Accepted Policies</a></li>
            <li><a href="View_customers.php">View All Customers</a></li>
            <li><a href="Admin_claims.php">Pending Claims</a></li>
            <li><a href="Admin_payout.php">Pending Payouts</a></li>
            <li><a href="Completed_payouts.php">Completed Payouts</a></li>
            <li><a href="../Admin/Policy_cancel/Admin_review_cancellations.php">Cancellation Request</a></li>
            <li><a href="../Admin/Policy_cancel/Accepted_cancel.php">Pending Cancellation Refund</a></li>
            <li><a href="../Admin/Policy_cancel/View_cancelled_policies.php">Cancelled policies</a></li>
            <li><a href="Userview.php">View User Details</a></li>
            <li><a href="Logindetails.php">View Login Details</a></li>
            <li><a href="Admin_view_messages.php">View Feedbacks</a></li>
            <li><a href="Ticket_list.php">View Tickets</a></li>
            <li><a href="Payment_due.php">Premium due</a></li>
            <li><a href="Adminnotif.php">Add Notification</a></li>
            <li><a href="AdminLogout.php">Logout</a></li>
    </ul>
</div>

<div id="content" class="content">
    <!-- Show Button with Hamburger Lines (for opening sidebar) -->
    <button id="show-button">
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
    </button>
</div>

<style>
    /* Sidebar Styling */
    .sidebar {
        width: 250px;
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        background-color: #970747; /* Dark blue background */
        padding-top: 60px; /* Add some padding at the top to avoid overlap with the heading */
        color: #ecf0f1; /* Light text color */
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        transition: transform 0.3s ease; /* Smooth transition */
        transform: translateX(-100%); /* Initially hide sidebar */
        z-index: 1000; /* Ensure sidebar is on top */
        overflow-y: auto; /* Enable vertical scrolling if content exceeds height */
    }

    .sidebar.show {
        transform: translateX(0); /* Show the sidebar */
    }

    /* Sidebar Header */
    .sidebar h2 {
        text-align: center;
        color: #fff;
        font-size: 22px;
        margin-bottom: 20px;
    }

    /* Sidebar Links Styling */
    .sidebar ul {
        list-style-type: none;
        padding: 0;
    }

    .sidebar ul li {
        padding: 12px 20px;
        margin: 5px 0;
        border-radius: 4px;
    }

    .sidebar ul li a {
        color: #ffffff; /* Light text color */
        text-decoration: none;
        display: block;
        font-size: 16px;
        transition: background-color 0.3s, padding-left 0.3s;
    }

    .sidebar ul li a:hover {
        background-color: #34495e; /* Hover background color */
        padding-left: 10px; /* Slight padding for hover effect */
    }

    /* Toggle Button Styling (Both buttons) */
    .toggle-button {
        display: block;
        width: 40px;
        height: 40px;
        margin: 10px;
        background-color: #970747; /* Blue background for the button */
        border: none;
        cursor: pointer;
        padding: 0;
        position: absolute; /* Positioning it absolutely within the sidebar */
        top: 20px; /* Position it from the top */
        right: 20px; /* Position it from the right */
        z-index: 1050; /* Ensure it’s clickable */
        border-radius: 50%;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2); /* Subtle shadow for effect */
    }

    .toggle-button:hover {
        background-color: #2980b9; /* Darker blue on hover */
    }

    /* Hamburger Lines Styling (for both buttons) */
    .hamburger-line {
        width: 30px;
        height: 4px;
        background-color: #fff;
        margin: 6px 0; /* Spacing between lines */
        border-radius: 5px;
        transition: all 0.3s ease;
    }

    /* Tooltip Text for Hover (Sidebar text on hover) */
    .toggle-button:hover::after {
        content: "Sidebar"; /* The text to display */
        position: absolute;
        left: 50px; /* Position it next to the button */
        top: 50%; /* Vertically center it */
        transform: translateY(-50%); /* Center it vertically */
        background-color: #970747; /* Same as button background */
        color: white;
        padding: 8px 12px;
        border-radius: 5px;
        font-size: 14px;
        white-space: nowrap; /* Prevent text wrapping */
        opacity: 1; /* Ensure the text is visible */
        transition: opacity 0.3s ease;
    }

    /* Show Button Styling */
    #show-button {
        position: fixed;
        top: 20px;
        left: 20px;
        width: 40px;
        height: 40px;
        background-color: #970747;
        border: none;
        cursor: pointer;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1100; /* Ensure it's on top of content */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    #show-button:hover {
        background-color: lightpink;
    }

    /* Hide Button Styling (same as show button) */
    #hide-button {
        position: fixed;
        top: 20px;
        left: 20px;
        width: 40px;
        height: 40px;
        background-color: #970747;
        border: none;
        cursor: pointer;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1100;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    #hide-button:hover {
        background-color: #970732;
    }

    /* Content Area Styling */
    .content {
        margin-left: 260px; /* Adjust content to the right of sidebar */
        padding: 20px;
        transition: margin-left 0.3s ease; /* Smooth transition for content shift */
    }

    .sidebar.show ~ .content {
        margin-left: 0; /* Adjust when sidebar is open */
    }

    /* Responsive Design for Smaller Screens */
    @media (max-width: 768px) {
        .sidebar {
            width: 200px;
        }

        .content {
            margin-left: 0;
        }

        #show-button {
            display: flex;
        }

        #hide-button {
            display: flex;
        }
    }
</style>

<script>
    // Get elements
    const sidebar = document.getElementById('sidebar');
    const hideButton = document.getElementById('hide-button');
    const showButton = document.getElementById('show-button');

    // Show the sidebar initially when clicking on show button
    showButton.addEventListener('click', function() {
        sidebar.classList.add('show'); // Show the sidebar
        showButton.style.display = 'none'; // Hide the show button
        hideButton.style.display = 'flex'; // Show the hide button with hamburger lines
    });

    // Hide the sidebar when clicking on hide button
    hideButton.addEventListener('click', function() {
        sidebar.classList.remove('show'); // Hide the sidebar
        hideButton.style.display = 'none'; // Hide the hide button
        showButton.style.display = 'flex'; // Show the show button with hamburger lines
    });
</script>
