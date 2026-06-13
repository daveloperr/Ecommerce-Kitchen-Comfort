<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitchen Comfort Co.</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="style.css?v=123">

</head>
<body>
    <?php require '../includes/dbhandler.inc.php'; ?>
    <?php require 'header.php'?>

    <section id="hero">
        <!-- <h4>Discover Exquisite Kitchenware Essentials</h4> -->
        <h2>Cook with love, <br/>create with comfort.</h2>
        <!-- <h1>Top-Quality & Excellence</h1>
        <p>Transform your kitchen with our premium, stylish essentials.</p> -->
        <!-- <a href="cookware.php"> <button style="width:150px;">Shop Now</button> </a> -->
    </section>


    <?php
// Updated SQL query to fetch the top 10 best-selling items based on accepted orders and active cart items
$sql_best_sellers = "
SELECT 
    cart.product_name, 
    SUM(cart.qty) AS total_sales,
    products1.price,
    products1.category,
    products1.product_id,
    products1.image
FROM 
    orders
INNER JOIN 
    cart 
ON 
    orders.order_id = cart.order_id
INNER JOIN
    products1
ON
    cart.product_name = products1.product_name
WHERE 
    orders.isOrderAccepted = 1
    AND cart.isActive = 2
GROUP BY 
    cart.product_name
ORDER BY 
    total_sales DESC
LIMIT 
    10
";

$result = $conn->query($sql_best_sellers); // Execute the query
?>

<section id="product1" class="section-p1">
    <h2>Best Selling Items</h2>
    <p>Discover our curated selection of top-rated and trending products, handpicked just for you.</p>
    <div class="pro-container">
        <?php
        $i = 0;
        if ($result->num_rows > 0) { 
            while ($row = $result->fetch_assoc()) {
                ?>
                <div class="pro">

                    <div onclick="window.location.href='productview.php?id=<?php echo htmlspecialchars($row['product_id']); ?>'">

                        <img src="../images/<?php echo htmlspecialchars($row['image']); ?>" alt="Product Image">
                    </div>
                    <div class="des">
                        <h5><?php echo htmlspecialchars($row['product_name']); ?></h5>

                        <div class="star">
                            <i class="fa fa-star checked"></i>
                            <i class="fa fa-star checked"></i>
                            <i class="fa fa-star checked"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        </div>
                        <h4>₱<?php echo htmlspecialchars($row['price']); ?></h4>
                    </div>
                </div>
                <?php
                $i++; 
                if ($i == 8) break; 
            }
        } else {
            echo "<p>No best-selling items found.</p>"; 
        }
        ?>
    </div>
</section>

    </div>
</section>

        </div>
    </section>


    <?php require 'footer.php' ?>
 
</body>
</html>