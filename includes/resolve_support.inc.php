<?php
session_start();
require '../includes/dbhandler.inc.php';

// Check if the support_id parameter is set
if (isset($_POST['support_id'])) {
    // Get the support ticket ID
    $supportId = $_POST['support_id'];

    // Prepare the SQL statement to update the isResolved column to 1
    $stmt = $conn->prepare("UPDATE support SET isResolved = 1 WHERE support_no = ?");
    $stmt->bind_param("i", $supportId);

    // Execute the SQL statement
    $stmt->execute();

    // Close the statement and the connection
    $stmt->close();
    $conn->close();

    // Return a success message
    echo "Support ticket resolved successfully.";
} else {
    // Return an error message
    echo "Error: Support ticket ID not provided.";
}
?>