<?php
session_start();
include '../Connection/Db_connection.php';
include('User_sidebar.php');

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    die("Error: No user logged in");
}

$user_email = $_SESSION['email']; // Get user email from session

// Initialize search query variables
$searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : '';
$policyType = isset($_POST['policyType']) ? $_POST['policyType'] : '';

// Build the WHERE clause based on the search criteria
$whereClause = "WHERE p.name LIKE ? OR p.description LIKE ?";
$params = array('%' . $searchTerm . '%', '%' . $searchTerm . '%');

// Add policy type filter if selected
if ($policyType) {
    $whereClause .= " AND p.type = ?";
    $params[] = $policyType;
}

// Fetch policies and their approval status for the current user with optional filters
$query = "
    SELECT p.id, p.name, p.type, p.duration, p.premium, p.coverage, p.description, 
           ap.status AS application_status, ap.form_filled 
    FROM policies p
    LEFT JOIN approved_policies ap ON ap.policy_id = p.id AND ap.email_id = ?
    $whereClause
";

// Prepare the statement with correct number of parameters
$stmt = $conn->prepare($query);

// Adjust bind_param based on the number of parameters
if ($policyType) {
    $stmt->bind_param("ssss", $user_email, $params[0], $params[1], $params[2]);  // Including policy type
} else {
    $stmt->bind_param("sss", $user_email, $params[0], $params[1]);  // No policy type filter
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Policies</title>
    <link rel="stylesheet" href="../CSS/styles.css"> <!-- Link to external CSS -->
</head>
<body>
    <div class="container">
        <h1>Available Policies</h1>
        
        <!-- Search Form with Type Filter and Keyword Search -->
        <form method="POST" action="Available_Policies.php">
            <input type="text" name="searchTerm" value="<?php echo htmlspecialchars($searchTerm); ?>" placeholder="Search by Name or Description..." class="search-input">
            
            <!-- Policy Type Filter Dropdown -->
            <select name="policyType" class="search-input">
                <option value="">Select Insurance Type</option>
                <option value="Health" <?php echo $policyType == 'Health' ? 'selected' : ''; ?>>Health</option>
                <option value="Life" <?php echo $policyType == 'Life' ? 'selected' : ''; ?>>Life</option>
                <option value="Vehicle" <?php echo $policyType == 'Vehicle' ? 'selected' : ''; ?>>Vehicle</option>
                <option value="Home" <?php echo $policyType == 'Home' ? 'selected' : ''; ?>>Home</option>
                <!-- Add more policy types as needed -->
            </select>
            
            <button type="submit" class="search-button">Search</button>
        </form>

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
