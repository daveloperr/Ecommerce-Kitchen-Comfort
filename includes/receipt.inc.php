<?php
session_start();
include '../includes/dbhandler.inc.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['checkoutbtn'])) {
    // Check if all required fields are provided
    if (isset($_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['address'], $_POST['address2'], $_POST['zip'], $_POST['city'], $_POST['checkoutPaymentOpt'], $_POST['totalprice'])) {
        
        // Retrieve the checkout details from the POST data
        $checkoutName = $_POST['firstName'].'' . $_POST['lastName'];
        $checkoutEmail = $_POST['email'];
        $checkoutAddress = $_POST['address'];
        $checkoutCity = $_POST['city'];
        $checkoutZip = $_POST['zip'];
        $checkoutPaymentOpt = $_POST['checkoutPaymentOpt'];
        $totalPrice = $_POST['totalprice'];

        
        // Begin a transaction
        $conn->begin_transaction();

        // Insert the checkout details into the database
        $sql_order = "INSERT INTO orders (user_id, name, email, address,  city, zip, payment_opt, total_id) VALUES (?,?,?,?,?,?,?,?)";
        $stmt_order = $conn->prepare($sql_order);
        if ($stmt_order) {
            // Bind parameters and execute the statement
            $stmt_order->bind_param("isssssid", $_SESSION['user_id'], $checkoutName, $checkoutEmail, $checkoutAddress,  $checkoutCity, $checkoutZip, $checkoutPaymentOpt, $totalPrice );
            if ($stmt_order->execute()) {
                // Retrieve the auto-generated order ID
                $order_id = $stmt_order->insert_id;
                
                // Update cart table to set isActive to 2 for purchased products
                $sql_update_cart = "UPDATE cart SET isActive = 2, order_id = $order_id WHERE user_id =? AND isActive = 1";
                $stmt_update_cart = $conn->prepare($sql_update_cart);
                $stmt_update_cart->bind_param("i", $_SESSION['user_id']);
                $stmt_update_cart->execute();

                // Deduct quantity from products table
                $sql_update_products = "UPDATE products1 p
                        INNER JOIN cart c ON p.product_name = c.product_name
                        SET p.quantity = p.quantity - c.qty,
                            p.sales = p.sales + c.qty
                        WHERE c.user_id =? 
                          AND c.isActive = 2 
                          AND c.order_id =?";
            $stmt_update_products = $conn->prepare($sql_update_products);
            $stmt_update_products->bind_param("ii", $_SESSION['user_id'], $order_id); 
            $stmt_update_products->execute();

                // Commit the transaction
                $conn->commit();

                // Redirect to receipt.php with order ID and user ID as URL parameters
                header("Location:../home_user/receipt.php?order_id=$order_id&user_id=".$_SESSION['user_id']);
                exit();
            } else {
                // Rollback the transaction if execution fails
                $conn->rollback();
                // Error handling if execution fails
                echo "Error executing query: ". $stmt_order->error;
            }
            $stmt_order->close();
        } else {
            // Error handling if prepare statement fails
            echo "Error preparing statement: ". $conn->error;
        }
    } else {
        // Error handling if required fields are missing
        echo "Required fields are missing";
    }
} else {
    // Error handling for non-POST requests or if checkoutbtn is not set
    echo "Invalid request method or checkoutbtn is not set";
}
?>