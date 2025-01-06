<?php
session_start();
include '../Connection/Db_connection.php';
include('User_sidebar.php');

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    die("Error: No user logged in");
}

$user_email = $_SESSION['email']; // Get user email from session

// Fetch policies and their approval status for the current user
$query = "
    SELECT p.id, p.name, p.type, p.duration, p.premium, p.coverage, p.description, 
           ap.status AS application_status, ap.form_filled 
    FROM policies p
    LEFT JOIN approved_policies ap ON ap.policy_id = p.id AND ap.email_id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user_email); // Bind the user's email
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Policies</title>
    <link rel="stylesheet" href="../CSS/Policyviewstyles.css">
    <script>
        function applyPolicy(policyId) {
            // Redirect to Apply_policy.php with the policyId
            window.location.href = "Apply_policy.php?policyId=" + encodeURIComponent(policyId);
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Available Policies</h1>
        <div class="policy-grid">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="policy-card">
                    <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                    <p>
                        ID: <?php echo htmlspecialchars($row['id']); ?><br>
                        Type: <?php echo htmlspecialchars($row['type']); ?><br>
                        Duration: <?php echo htmlspecialchars($row['duration']); ?> years<br>
                        Premium: $<?php echo number_format($row['premium'], 2); ?><br>
                        Coverage: $<?php echo number_format($row['coverage'], 2); ?><br>
                        Description: <?php echo htmlspecialchars($row['description']); ?><br>
                    </p>
                    <p>Status: <?php echo htmlspecialchars($row['application_status']); ?></p>
                    <p>Form Filled: <?php echo htmlspecialchars($row['form_filled']); ?></p>

                    <?php if (strtolower($row['application_status']) === 'approved'): ?>
                        <!-- Fill Form button for approved policies -->
                        <button class="fill-form-button <?php echo $row['form_filled'] ? 'disabled' : ''; ?>" 
                                <?php echo $row['form_filled'] ? 'disabled' : ''; ?>
                                onclick="<?php echo $row['form_filled'] ? 'return false;' : 'window.location.href=\'Fill_form.php?policyId=' . htmlspecialchars($row['id']) . '\' '; ?>">
                            <?php echo $row['form_filled'] ? 'Form Filled' : 'Fill Form'; ?>
                        </button>
                    <?php else: ?>
                        <!-- Apply button for unapproved policies -->
                        <button class="apply-button <?php echo $row['form_filled'] ? 'disabled' : ''; ?>" 
                                <?php echo $row['form_filled'] ? 'disabled' : ''; ?>
                                onclick="<?php echo $row['form_filled'] ? 'return false;' : 'applyPolicy(\''.htmlspecialchars($row['id']).'\')'; ?>">
                            Apply
                        </button>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>

