<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cart</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <!-- Include your additional CSS files here -->
                <style>
           body{
                 background-color: #f0f2f5;
                 Font-family: 'Poppins', sans-serif;
            }
            h1, td{
                Font-family: 'Poppins', sans-serif;
            }
            
            input {
                text-align: center;
            }

            button {
                border: none;
                background-color: transparent;
                background-repeat: no-repeat;
            }

            a {
                font-size: 12px;
                color: black;
                text-decoration: none;
            }

            a:hover {
                color: blue;
            }
            .table{
               margin-bottom: 120px;
            }
            .cart{
                box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.1), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            }


        </style>
    </head>
    <body>

        <?php require 'header.php';?>

        <div id="cart">
            <h1>Cart</h1>
            <table class="table" id="myTable">
                <thead>
                    <tr>
                        <th>Select</th>
                        <th width="15%"></th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include '../includes/dbhandler.inc.php';
                    $totalPrice = 0;
                    if(isset($_SESSION['user_id'])) {
                        $user_id = $_SESSION['user_id'];
                        $sql = "SELECT * FROM cart WHERE user_id = '$user_id' AND isDeleted = 0 AND isActive <= 1";
                        $stmt = mysqli_stmt_init($conn);
                        mysqli_stmt_prepare($stmt, $sql);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        if (mysqli_num_rows($result) == 0) {
                            echo ("<tr><td colspan='6'>Your cart is empty.</td></tr>");
                        }
                       ?> <?php
                        while($row = mysqli_fetch_assoc($result)) {?>
                            <tr>
                                <td>
                                       <input type="checkbox" class="isActiveCheck" name="isActiveCheckbox" value="<?php echo $row['product_name']. '-'. $row['user_id'];?>" <?php if($row['isActive']) {?> checked <?php }?> data-product-name="<?php echo $row['product_name'];?>" data-user-id="<?php echo $row['user_id'];?>">
                                </td>
                                <td><img src="../images/<?php echo $row['image'];?>" width="100%"></td>
                                <td><?php echo $row['product_name']?></td>
                                <td><?php echo $row['price']?></td>
                                <td>
                                    <div>
                                        <button class="quantity-btn" data-action="decrease"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-dash-circle" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/><path d="M4 8a.5.5 0 0 1.5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8"/></svg></button>
                                        <span class="quantity-value"><?php echo $row['qty']?></span>
                                        <button class="quantity-btn" data-action="increase"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/></svg></button>
                                        <br>
                                    <button class="remove-btn btn btn-dark " style="width:100px;" name="deletebtn" id="deletebtn">Remove</button> <!-- Change to a button -->
                                    <input type="hidden" class="delete-product-name" value="<?php echo $row['product_name'];?>">
                                    </div>
                                </td>
                                <td><?php echo number_format($row['price'] * $row['qty'], 2); ?></td>
                                <?php $totalPrice += $row['price'] * $row['qty'];?>
                            </tr>
                        <?php }
                    }
                    ?>
                    <tr>
                        <td colspan="5" class="text-end"><strong>Total:</strong></td>
                        <td id="totalPrice"><?php echo number_format($totalPrice, 2); ?></td>
                    </tr>
                </tbody>
            </table>

           <a href="../home_user/checkout.php" class="btn btn-success" <?php if(mysqli_num_rows($result) == 0) {?> hidden <?php } ?>>Proceed to Checkout</a>
          
        </div>

        <?php require 'footer.php'; ?>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->
       
        <script>
$(document).ready(function() {
        $('.quantity-btn').on('click', function() {
            const action = $(this).attr('data-action');
            const row = $(this).closest('tr');
            const productName = row.find('td:nth-child(3)').text();
            const productPrice = parseFloat(row.find('td:nth-child(4)').text());
            const quantityElement = row.find('.quantity-value');
            let quantity = parseInt(quantityElement.text());

            if (action === 'decrease') {
                quantity = quantity > 1 ? quantity - 1 : 1; // Minimum quantity is 1
            } else {
                quantity += 1;
            }

            quantityElement.text(quantity);

            // Send AJAX request to update quantity in cart
            $.ajax({
                url: '../includes/cart.inc.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    productName: productName,
                    productPrice: productPrice,
                    productQuantity: quantity,
                    user_id: '<?php echo $_SESSION['user_id'];?>'
                },
                success: function(response) {
                    // Update total price in the current row
                    const totalPrice = productPrice * quantity;
                    row.find('td:nth-child(6)').text(new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(totalPrice));

                    // Update total price of all items in the cart
                    updateTotalPrice();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });

        function updateTotalPrice() {
            let totalPrice = 0;
            $('.quantity-value').each(function() {
                const row = $(this).closest('tr');
                const productPrice = parseFloat(row.find('td:nth-child(4)').text());
                const quantity = parseInt($(this).text());
                totalPrice += productPrice * quantity;
            });
            $('#totalPrice').text(new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(totalPrice));
        }
    });

    </script>

    <script  type="text/javascript">
    $(document).ready(function() {
        // Event handler for remove button (using event delegation)
        $(document).on('click', '.remove-btn', function() {
            const productName = $(this).siblings('.delete-product-name').val();
            const removeButton = $(this); 
            // Send AJAX request to delete the product using the product name
            $.ajax({
                url: '../includes/remove_from_cart.inc.php',
                method: 'POST',
                data: { deletebtn: productName, user_id: '<?php echo $_SESSION['user_id']; ?>' },
                success: function(response) {
                  
                    // Remove the row from the table
                    removeButton.closest('tr').remove(); // Use the stored reference to the remove button
                    // Update total price after removing the product
                    updateTotalPrice();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
    });
    </script>
<script type="text/javascript">
$(document).on('change', '.isActiveCheck', function() {
const productName = $(this).data('product-name');
const user_id = $(this).data('user-id');
const isActive = $(this).is(':checked') ? 1 : 0;

// Send AJAX request to update isActive in the cart table
$.ajax({
    url: '../includes/isActive.inc.php',
    type: 'POST',
    dataType: 'json',
    data: {
        productName: productName,
        user_id: user_id,
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
</script>
    </body>
</html>