<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitchen Comfort Co. - Receipt</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding-top: 20px;
        }
        .receipt-container {
            max-width: 600px;
            MARGIN: 80px 0 80px 0;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            
        
        }
        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .receipt-header img {
            max-width: 200px;
            margin-bottom: 10px;
        }
        .receipt-footer {
            text-align: center;
            margin-top: 20px;
        }
        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .receipt-table th,
        .receipt-table td {
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: left;
        }
        .receipt-total {
            font-weight: bold;
            font-size: 1.2em;
        }
        .submit-btn {
            display: block;
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            background-color: #4CAF50;  
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
        }
        
    </style>
</head>
<body>
<center>
    <?php require 'header.php'; ?>
    <?php include '../includes/dbhandler.inc.php'; ?>
    <?php
    if(isset($_GET['order_id']) && isset($_GET['user_id'])) {
        $order_id = $_GET['order_id'];
        $user_id = $_GET['user_id']; // Changed from 'username' to 'user_id'
        $sql_order = "SELECT * FROM orders WHERE order_id = ?";
        $stmt_order = $conn->prepare($sql_order);
        $stmt_order->bind_param("i", $order_id);
        $stmt_order->execute();
        $result_order = $stmt_order->get_result();
        $order = $result_order->fetch_assoc();
        $sql_cart = "SELECT * FROM cart WHERE user_id = ? AND order_id = ?"; // Changed from 'username' to 'user_id'
        $stmt_cart = $conn->prepare($sql_cart);
        $stmt_cart->bind_param("si", $user_id, $order_id); // Changed from 'username' to 'user_id'
        $stmt_cart->execute();
        $result_cart = $stmt_cart->get_result();
    } else {
        header("Location: ../home_user/index.php");
        exit();
    }
    ?>
    <div class="receipt-container">
        <div class="receipt-header">
        <br>
            <img src="../img/kitchencomfortlogo1.png" alt="Company Logo">
            <br>
            <h1>Receipt</h1>
            <p>Thank you for shopping with us!</p>
        </div>
        <div class="receipt-details">
            <h3>Order Details</h3>
            <p><strong>Order ID:</strong> <?php echo $order['order_id']; ?></p>
            <p><strong>Date:</strong> <?php echo date('Y-m-d'); ?></p>
            <p><strong>Payment Method:</strong> Cash</p>
        </div>
        <div class="receipt-products">
            <h3>Products</h3>
            <table class="receipt-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_amount = 0;
                    while($row = $result_cart->fetch_assoc()) {
                        $total_price = $row['price'] * $row['qty'];
                        $total_amount += $total_price;
                    ?>
                    <tr>
                        <td><?php echo $row['product_name']; ?></td>
                        <td><?php echo $row['qty']; ?></td>
                        <td>PHP<?php echo number_format($total_price, 2); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="receipt-total">
            <p><strong>Total(PHP):</strong> <?php echo number_format($total_amount, 2); ?></p>
        </div>
      <a href="index.php" class="submit-btn mt-3" name="doneButton" id="doneButton">Done</a>

        <div class="receipt-footer">
            <p>For any inquiries, please contact us at:</p>
            <p>Email: info@example.com | Phone: 123-456-7890</p>
        </div>
    </div>
    <?php require 'footer.php'; ?>
    <center>
<script type="text/javascript">
$(document).on('click', '#doneButton', function() {
    // Iterate over all checkboxes with class isActiveCheck and set isActive to 3
    $('.isActiveCheck').each(function() {
        const productName = $(this).data('product-name');
        const user_id = $(this).data('user-id'); // Changed from 'username' to 'user_id'
        const isActive = 3; // Set isActive to 3 when the "Done" button is clicked

        // Send AJAX request to update isActive in the cart table
        $.ajax({
            url: '../includes/orderdone.inc.php',
            type: 'POST',
            dataType: 'json',
            data: {
                productName: productName,
                user_id: user_id, // Changed from 'username' to 'user_id'
                isActive: isActive
            },
            success: function(response) {
                // Handle success response
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
});
</script>
</body>
</html>
