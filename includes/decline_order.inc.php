<?php
session_start();
require 'dbhandler.inc.php';

if (isset($_POST['decline_order_btn'])) {
    $orderId = $_POST['decline_order_id'];

    // First check the current state of isOrderAccepted
    $sql = "SELECT isOrderAccepted FROM orders WHERE order_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $orderId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $orderData = mysqli_fetch_assoc($result);

        if ($orderData['isOrderAccepted'] == 1) {
            // If order is already accepted, redirect with a message
            header("Location: ../admin/order_progress.php?error=alreadyaccepted");
            exit();
        } else {
            // Update the isOrderDeclined column in the orders table
            $sql = "UPDATE orders SET isOrderDeclined = 1 WHERE order_id = ?";
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, "i", $orderId);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                // Redirect to the order progress page after declining the order
                header("Location: ../admin/order_progress.php?declined=true");
                exit();
            } else {
                // Handle SQL error
                header("Location: ../admin/order_progress.php?error=sqlerror");
                exit();
            }
        }
    } else {
        // Handle SQL error
        header("Location: ../admin/order_progress.php?error=sqlerror");
        exit();
    }
} else {
    // Redirect if accessed without form submission
    header("Location: ../admin/order_progress.php");
    exit();
}
?>
