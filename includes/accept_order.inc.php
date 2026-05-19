<?php
session_start();
require 'dbhandler.inc.php';

if (isset($_POST['accept_order_btn'])) {
    $orderId = $_POST['accept_order_id'];

    // First check the current state of isOrderDeclined
    $sql = "SELECT isOrderDeclined, isOrderAccepted FROM orders WHERE order_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $orderId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $orderData = mysqli_fetch_assoc($result);

        if ($orderData['isOrderDeclined'] == 1) {
            // If order is already declined, redirect with a message
            header("Location: ../admin/order_progress.php?error=alreadydeclined");
            exit();
        } elseif ($orderData['isOrderAccepted'] == 1) {
            // If order is already accepted, redirect with a message
            header("Location: ../admin/order_progress.php?error=alreadyaccepted");
            exit();
        } else {
            // Proceed with accepting the order
            $sql = "UPDATE orders SET isOrderAccepted = 1 WHERE order_id = ?";
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, "i", $orderId);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                // Redirect to the order progress page after accepting the order
                header("Location: ../admin/order_progress.php?accepted=true");
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

