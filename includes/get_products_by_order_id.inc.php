<?php
session_start();
include 'dbhandler.inc.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id'])) {
    $orderId = $_POST['order_id'];

    // Fetch user details from orders table
    $userSql = "SELECT name, email, address FROM orders WHERE order_id = ?";
    $userStmt = $conn->prepare($userSql);
    if ($userStmt) {
        $userStmt->bind_param("i", $orderId);
        $userStmt->execute();
        $userResult = $userStmt->get_result();
        if ($userResult->num_rows > 0) {
            $userData = $userResult->fetch_assoc();
            $userName = $userData['name'];
            $userEmail = $userData['email'];
            $userAddress = $userData['address'];

            // Fetch product details from cart and products1 tables
            $productSql = "SELECT c.product_name, p.price, c.qty, (p.price * c.qty) AS total_price
                            FROM cart c
                            JOIN products1 p ON c.product_name = p.product_name
                            WHERE c.order_id = ?";
            $productStmt = $conn->prepare($productSql);
            if ($productStmt) {
                $productStmt->bind_param("i", $orderId);
                $productStmt->execute();
                $productResult = $productStmt->get_result();
                if ($productResult->num_rows > 0) {
                    $output = "<h5>Order ID: $orderId</h5>";
                    $output .= "<h6>User Details:</h6>";
                    $output .= "<p><strong>Name:</strong> $userName</p>";
                    $output .= "<p><strong>Email:</strong> $userEmail</p>";
                    $output .= "<p><strong>Address:</strong> $userAddress</p>";
                    $output .= "<hr>";
                    $totalPrice = 0;
                    while ($row = $productResult->fetch_assoc()) {
                        // Output product details
                        $output .= "<p><strong>Product Name:</strong> " . $row['product_name'] . "</p>";
                        $output .= "<p><strong>Quantity:</strong> " . $row['qty'] . "</p>";
                        $output .= "<p><strong>Price:</strong> $" . $row['price'] . "</p>";
                        $output .= "<p><strong>Total Price:</strong> $" . $row['total_price'] . "</p>";
                        $output .= "<hr>";
                        $totalPrice += $row['total_price'];
                    }
                    // Output total order price
                    $output .= "<p><strong>Total Order Price:</strong> $" . $totalPrice . "</p>";
                    echo $output;
                } else {
                    echo "No product details found for order ID: $orderId";
                }
            } else {
                echo "Error: Unable to prepare product SQL statement.";
            }
        } else {
            echo "No user details found for order ID: $orderId";
        }
    } else {
        echo "Error: Unable to prepare user SQL statement.";
    }
} else {
    echo "Error: Invalid request.";
}
?>
