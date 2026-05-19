<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Additional styles for more vibrant order status colors */
        .alert-order-pending {
            background-color: #ffdd57; /* Bright yellow */
            color: #333; /* Dark text for better readability */
        }
        .alert-order-accepted {
            background-color: #28a745; /* Bright green */
            color: white;
        }
        .alert-order-declined {
            background-color: #dc3545; /* Bright red */
            color: white;
        }
    </style>
</head>
<body>

    <?php 
    require 'header.php';
    include '../includes/dbhandler.inc.php';
    $orders = [];
    $user_id = $_SESSION['user_id']; // Change 'username' to 'user_id'
    $sql = "SELECT * FROM orders WHERE user_id = ?"; // Change 'username' to 'user_id'
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while($row = mysqli_fetch_assoc($result)) {
            $orders[] = $row;
        }
    }
    ?>

    <section id="about-head" class="section-p1">
        <h3>Order History</h3>
        <div id="cart">
            <?php 
            foreach ($orders as $order) {
                $order_id = $order['order_id'];
                $sql = "SELECT * FROM cart WHERE order_id = ?";
                $stmt = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmt, $sql);
                mysqli_stmt_bind_param($stmt, "i", $order_id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt); 

                if(mysqli_num_rows($result) > 0) {
                    echo "<div class='card mb-3'>";
                    echo "<div class='card-header'>Order ID: $order_id</div>";
                    echo "<div class='card-body'>";
                    $totalPrice = 0;
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<div class='row g-0 align-items-center'>";
                        echo "<div class='col-3'><img src='../images/{$row['image']}' class='img-fluid rounded-start' alt='{$row['product_name']}'></div>";
                        echo "<div class='col-9'>";
                        echo "<div class='card-body'>";
                        echo "<h5 class='card-title'>{$row['product_name']}</h5>";
                        echo "<p class='card-text'>Price: ₱{$row['price']}</p>";
                        echo "<p class='card-text'>Quantity: {$row['qty']}</p>";
                        echo "<p class='card-text'>Subtotal: ₱" . number_format($row['qty'] * $row['price'], 2) . "</p>";
                        $totalPrice += $row['price'] * $row['qty'];
                        echo "</div>"; // card-body
                        echo "</div>"; // col-9
                        echo "</div>"; // row
                    }
                    echo "<p class='card-text'><small class='text-muted'>Total: ₱" . number_format($totalPrice, 2) . "</small></p>";
                    // Status display
                    displayStatus($order);
                    echo "</div>"; // card-body
                    echo "</div>"; // card
                }
            }
            function displayStatus($order) {
                // Check order status and add color-coded text
                if ($order['isOrderAccepted'] == 1 && $order['isOrderDeclined'] == 0) {
                    echo "<div class='alert alert-order-accepted' role='alert'>Order Accepted</div>";
                } elseif ($order['isOrderDeclined'] == 1 && $order['isOrderAccepted'] == 0) {
                    echo "<div class='alert alert-order-declined' role='alert'>Order Declined</div>";
                } elseif ($order['isOrderAccepted'] == 0 && $order['isOrderDeclined'] == 0) {
                    echo "<div class='alert alert-order-pending' role='alert'>Order Pending</div>";
                }
            }
            ?>
        </div>
    </section>

    <?php require 'footer.php'; ?>

</body>
</html>
