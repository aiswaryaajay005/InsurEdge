
<?php
//ticket_view_reply.php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "users";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get ticket ID from URL
$ticket_id = $_GET['id'];

// Fetch ticket details
$sql = "SELECT * FROM tickets WHERE id = $ticket_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $ticket = $result->fetch_assoc();
} else {
    die("Ticket not found.");
}

// Handle reply submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_reply = $_POST['reply'];
    $admin_reply = htmlspecialchars($admin_reply);

    $sql = "UPDATE tickets SET admin_reply = '$admin_reply' WHERE id = $ticket_id";

    if ($conn->query($sql) === TRUE) {
        echo "Reply posted successfully.";
        // Refresh the page to show the reply
        header("Location: Ticket_view_reply.php?id=$ticket_id");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View Ticket</title>
    <link rel="stylesheet" href="../CSS/Ticketviewreplystyle.css">
</head>
<body>
    <div class="container">
        <h1>Ticket Details</h1>
        <p><strong>From:</strong> <?php echo $ticket['name']; ?></p>
        <p><strong>Email:</strong> <?php echo $ticket['email']; ?></p>
        <p><strong>Subject:</strong> <?php echo $ticket['subject']; ?></p>
        <p><strong>Priority:</strong> <?php echo ucfirst($ticket['priority']); ?></p>
        <p><strong>Message:</strong> <?php echo nl2br($ticket['message']); ?></p>
        <p><strong>Date Submitted:</strong> <?php echo $ticket['created_at']; ?></p>

        <h2>Admin Reply</h2>
        <?php if ($ticket['admin_reply']) { ?>
            <p><?php echo nl2br($ticket['admin_reply']); ?></p>
        <?php } else { ?>
            <p>No reply yet.</p>
        <?php } ?>

        <h3>Post a Reply</h3>
        <form action="" method="POST">
            <div class="form-group">
                <textarea name="reply" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <button type="submit">Submit Reply</button>
            </div>
        </form>

        <a href="Ticket_list.php">Back to Tickets List</a>
    </div>
</body>
</html>
