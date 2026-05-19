<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitchen Comfort Co.</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel ="stylesheet" href="style.css">
    

</head>
<body>

    <?php require '../includes/dbhandler.inc.php'; ?>
    <?php require 'header.php'?>


    
    <form action="cart.inc.php" method="post">

    <section id="product1" class="section-p1">
        <h2>Kitchen Appliances</h2>
        <p>"Effortless Cooking Starts Here with Our Innovative Appliances"</p>
        <div class="pro-container">
        <?php

       

      $sql = "SELECT * FROM products1 WHERE category_id = 1 AND isDeleted = 0;";

        $result = $conn->query($sql);

        while($row = $result->fetch_assoc()) {
        ?>
            <div class="pro">
            <div onclick="window.location.href='productview.php?id=<?php echo $row['product_id']; ?>'">
                <img src="../images/<?php echo $row['image']; ?>" >
                <div class="des">
                    <span>Kitchen Comfort</span>
                    <div class="star">
                        <i class="fa fa-star checked"></i>
                        <i class="fa fa-star checked"></i>
                        <i class="fa fa-star checked"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>
                    <h5><?php echo $row['product_name'] ?></h5>
                    <h4>₱<?php echo $row['price'] ?></h4>
                </div>
             <div onclick="window.location.href='productview.php?id=<?php echo $row['product_id']; ?>'">

    <i class="bi bi-cart2 cart"></i>
</div>
            </div>
        </div>
        <?php
        }
        ?>
        </div>
    </section>
</form>


    <?php require 'footer.php'?>
    
</body>
</html>