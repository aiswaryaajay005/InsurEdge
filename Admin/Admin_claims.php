<?php
include 'Sidebar.php';
?> 
<style>
  body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f6f9;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    height: 100vh;
    padding-top: 20px;
}

.container {
    width: 90%;  /* Container width */
    max-width: 1100px; /* Max-width for larger screens */
    margin: 0 auto;
    padding: 30px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
    z-index: 1;
}

.header {
    text-align: center;
    margin-bottom: 30px;
}

.header h1 {
    font-size: 32px;
    color: #970747;
    margin-bottom: 10px;
    font-weight: bold;
}

.header p {
    font-size: 16px;
    color: #888;
}

.claim-container {
    background-color: #fff;
    padding: 20px;
    margin-bottom: 20px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s ease, box-shadow 0.3s ease;
}

.claim-container:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

.claim-container p {
    font-size: 16px;
    color: #333;
    line-height: 1.6;
    margin: 10px 0;
}

.claim-container p strong {
    color: #970747;
}

.claim-action-form {
    margin-top: 20px;
    display: flex;
    flex-direction: column;
}

.action-buttons {
    margin-bottom: 10px;
    display: flex;
    justify-content: space-between;
}

.accept-btn,
.reject-btn {
    padding: 12px 24px;
    font-size: 16px;
    border: none;
    cursor: pointer;
    border-radius: 8px;
    transition: background-color 0.3s ease, transform 0.2s ease;
    width: 48%;
}

.accept-btn {
    background-color: #2ecc71;
    color: white;
}

.accept-btn:hover {
    background-color: #27ae60;
    transform: translateY(-2px);
}

.reject-btn {
    background-color: #e74c3c;
    color: white;
}

.reject-btn:hover {
    background-color: #c0392b;
    transform: translateY(-2px);
}

.reason-textarea {
    width: 100%;
    height: 120px;
    margin-top: 15px;
    padding: 12px;
    font-size: 14px;
    border: 1px solid #ddd;
    border-radius: 8px;
    resize: vertical;
    background-color: #fafafa;
    color: #333;
    transition: border-color 0.3s ease;
}

.reason-textarea::placeholder {
    color: #aaa;
}

.reason-textarea:focus {
    border-color: #3498db;
    outline: none;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 10;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
    padding-top: 50px;
}

.modal-content {
    background-color: #fff;
    margin: auto;
    padding: 20px;
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    width: 80%;
    max-width: 900px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
}

.close-btn {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    transition: color 0.3s ease;
}

.close-btn:hover,
.close-btn:focus {
    color: #000;
    text-decoration: none;
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
    .container {
        width: 95%;
    }

    .action-buttons {
        flex-direction: column;
        align-items: stretch;
    }

    .accept-btn,
    .reject-btn {
        width: 100%;
        margin-bottom: 10px;
    }

    .modal-content {
        width: 95%;
    }
}

</style>

<div class="container">
    <!-- Header Section -->
    <div class="header">
        <h1>Pending Claim Applications</h1>
        <p>Manage the pending claim applications from your users. Accept or reject claims based on the provided information.</p>
    </div>

    <!-- Pending Claims List -->
    <?php
    include '../Connection/Db_connection.php'; // Include database connection

    // Query to fetch only pending claims
    $result = $conn->query("SELECT * FROM claims WHERE status = 'Pending'");

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='claim-container'>";
            echo "<p><strong>Claim ID:</strong> " . $row['id'] . "</p>";
            echo "<p><strong>Customer Name:</strong> " . $row['customer_name'] . "</p>";
            echo "<p><strong>Claim Amount:</strong> $" . number_format($row['claim_amount'], 2) . "</p>";
            echo "<p><strong>Status:</strong> " . $row['status'] . "</p>";

            // Check if supporting document exists and is not empty
            if (!empty($row['supporting_documents']) && file_exists('../uploads/' . $row['supporting_documents'])) {
                echo "<p><strong>Supporting Document:</strong> 
                        <a href='#' class='view-document-btn' data-document='../uploads/" . $row['supporting_documents'] . "'>View Document</a>
                      </p>";
            } else {
                echo "<p><strong>Supporting Document:</strong> No supporting document uploaded or file not found.</p>";
            }

            // Check if policy document exists and is not empty
            if (!empty($row['policy_document']) && file_exists('../uploads/' . $row['policy_document'])) {
                echo "<p><strong>Policy Document:</strong> 
                        <a href='#' class='view-document-btn' data-document='../uploads/" . $row['policy_document'] . "'>View Document</a>
                      </p>";
            } else {
                echo "<p><strong>Policy Document:</strong> No policy document uploaded or file not found.</p>";
            }

            // Provide Accept/Reject options
            echo "
            <form method='post' action='Process_claim.php' class='claim-action-form'>
                <input type='hidden' name='claim_id' value='" . $row['id'] . "'>
                <div class='action-buttons'>
                    <button name='action' value='accept' class='accept-btn'>Accept</button>
                    <button name='action' value='reject' class='reject-btn'>Reject</button>
                </div>
                <textarea name='rejection_reason' class='reason-textarea' placeholder='Reason for rejection (if any)'></textarea>
            </form>";

            echo "</div>"; // End of claim-container
        }
    } else {
        echo "<p>No pending claims found.</p>";
    }
    ?>
</div>

<!-- Modal for Document Viewing -->
<div id="document-modal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <embed id="document-preview" src="" width="100%" height="600px">
    </div>
</div>

<script>
    // Modal functionality
    const modal = document.getElementById("document-modal");
    const modalContent = document.getElementById("document-preview");
    const closeBtn = document.querySelector(".close-btn");

    // Event listener for "View Document" buttons
    const viewDocumentBtns = document.querySelectorAll('.view-document-btn');
    viewDocumentBtns.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const documentPath = this.getAttribute('data-document');
            modal.style.display = "block";
            modalContent.setAttribute('src', documentPath);
        });
    });

    // Close the modal when the close button is clicked
    closeBtn.addEventListener('click', function() {
        modal.style.display = "none";
    });

    // Close the modal if the user clicks outside of it
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });
</script>

