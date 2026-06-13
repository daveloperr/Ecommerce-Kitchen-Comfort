<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product View</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<style>
body{
    
background-color: white;
}


#addToCartBtn {
       background-color: #088178 !important;
    color: white !important;
    width: 80% !important;
    height: 50px !important;
}

</style>
<body>

<?php require 'header.php';?>
<?php
    include '../includes/dbhandler.inc.php';

    $product_name = "";
    $product_price = "";
    $product_image = "";
    $product_category = "";
    $product_description = "";

    if(isset($_GET['id'])) {
        $product_id = $_GET['id'];
        $sql = "SELECT p.*, c.category FROM products1 p JOIN product_category c ON p.category_id = c.category_id WHERE p.product_id = $product_id";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $product_name = $row['product_name'];
            $product_price = $row['price'];
            $product_image = $row['image'];
            $product_category = $row['category'];
            $product_description = $row['description'];
        } else {
            echo "Product not found.";
        }
    } else {
        echo "Product ID is not provided.";
    }

    $conn->close();
?>

<section id="prodetails" class="section-p1">
    <div class="single-pro-image">
        <img src="../images/<?php echo $product_image;?>" width="100%">
    </div>
    <div class="single-pro-details">
        <h5><?php echo $product_category;?></h5><br>
        <h2><?php echo $product_name;?></h2>
        <h2>₱<?php echo number_format ($product_price, 2);?></h2><hr style="width:90%;">
        <br>
        <input type="number" value="1">
        <button id="addToCartBtn" class="addToCartBtn">Add to Cart</button>
        <h4>Product Details</h4>
        <span>
            <ul>
                <div style="width:80%;">
                    <?php echo $product_description;?>
                </div>
            </ul>  
        </span>
    </div>
</section>

<?php require 'footer.php';?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById("addToCartBtn").addEventListener("click", function() {
            var productName = "<?php echo $product_name;?>";
            var productPrice = "<?php echo $product_price;?>";
            var productQuantity = document.querySelector("input[type='number']").value;
            var productImage = "<?php echo $product_image?>";
            var user_id = "<?php echo isset($_SESSION['user_id'])? $_SESSION['user_id'] : ''?>";
            

            if (productQuantity > 50) {
                alert("You cannot order more than " + "50" + " units of this product.");
                return;
            }

            // Send an AJAX request to add the product to the cart
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../includes/add_to_cart.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Handle the response if needed
                        alert("Product added to cart successfully!");
                    } else {
                        // Handle errors
                        alert("Error adding product to cart.");
                    }
                }
            };
            xhr.send("productName=" + encodeURIComponent(productName) + "&productPrice=" + encodeURIComponent(productPrice) + "&productQuantity=" + encodeURIComponent(productQuantity) + "&productImage=" + encodeURIComponent(productImage) + "&user_id=" + encodeURIComponent(user_id));
        });
    });
</script>

</body>
</html>