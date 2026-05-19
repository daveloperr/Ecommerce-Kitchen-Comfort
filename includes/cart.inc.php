<?php
session_start();
include '../includes/dbhandler.inc.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['productName']) && isset($_POST['productPrice']) && isset($_POST['productQuantity']) && isset($_POST['user_id'])) {
        $productName = $_POST['productName'];
        $productPrice = $_POST['productPrice'];
$productQuantity = $_POST['productQuantity'];
        $user_id = $_POST['user_id'];

        // Update quantity in the cart table
        $sql = "UPDATE cart SET qty =? WHERE user_id =? AND product_name =?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("iis", $productQuantity, $user_id, $productName);
            if ($stmt->execute()) {
                // Return a success response
                http_response_code(200);
                echo json_encode(array("message" => "Quantity updated successfully"));
            } else {
                // Return an error response if update fails
                http_response_code(500);
                echo json_encode(array("error" => "Error executing query: ". $stmt->error));
            }
            $stmt->close();
        } else {
            // Return an error response if prepare statement fails
            http_response_code(500);
            echo json_encode(array("error" => "Error preparing statement: ". $conn->error));
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