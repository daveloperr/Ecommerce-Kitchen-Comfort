<?php
session_start();
include '../includes/dbhandler.inc.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['productName']) && isset($_POST['user_id'])) {
        $productName = $_POST['productName'];
        $user_id = $_POST['user_id']; 

        // Set isActive to 3
        $isActive = 3;

        // Update isActive in the cart table
        $sql_update_cart = "UPDATE cart SET isActive = ? WHERE product_name = ? AND user_id = ?"; 
        $stmt_update_cart = $conn->prepare($sql_update_cart);
        if ($stmt_update_cart) {
            $stmt_update_cart->bind_param("iss", $isActive, $productName, $user_id); 
            if ($stmt_update_cart->execute()) {
                // Deduct quantity from products1 table
                $sql_update_products1 = "UPDATE products1 p
                                         JOIN cart c ON p.product_name = c.product_name
                                         SET p.quantity = p.quantity - c.qty
                                         WHERE c.product_name = ? AND c.user_id = ?"; 
                $stmt_update_products1 = $conn->prepare($sql_update_products1);
                if ($stmt_update_products1) {
                    $stmt_update_products1->bind_param("ss", $productName, $user_id); 
                    if ($stmt_update_products1->execute()) {
                        // Return a success response
                        http_response_code(200);
                        echo json_encode(array("message" => "isActive updated successfully and quantity deducted from products1 table"));
                    } else {
                        // Return an error response if update fails for products1 table
                        http_response_code(500);
                        echo json_encode(array("error" => "Error executing query for products1 table: " . $stmt_update_products1->error));
                    }
                    $stmt_update_products1->close();
                } else {
                    // Return an error response if prepare statement fails for products1 table
                    http_response_code(500);
                    echo json_encode(array("error" => "Error preparing statement for products1 table: " . $conn->error));
                }
            } else {
                // Return an error response if update fails for cart table
                http_response_code(500);
                echo json_encode(array("error" => "Error executing query for cart table: " . $stmt_update_cart->error));
            }
            $stmt_update_cart->close();
        } else {
            // Return an error response if prepare statement fails for cart table
            http_response_code(500);
            echo json_encode(array("error" => "Error preparing statement for cart table: " . $conn->error));
        }
    } else {
        // Return an error response if product details are not provided
        http_response_code(400);
        echo json_encode(array("error" => "Product details are missing"));
    }
} else {
    // Return an error response for non-POST requests
    http_response_code(405);
    echo json_encode(array("error" => "Method Not Allowed"));
}
?>
