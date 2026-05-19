<?php
session_start();
require '../includes/dbhandler.inc.php'; // Include database connection

// Redirect if not logged in as admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../home_user/login.php"); // Redirect to login page or a 'denied' page
    exit();
}

// SQL query to get total quantity of products per category
$sql = "SELECT category, SUM(quantity) AS total_quantity FROM products1 GROUP BY category";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    // Handle SQL error
    exit();
} else {
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
}

// Fetch out-of-stock products
$outOfStockSql = "SELECT * FROM products1 WHERE quantity = 0";
$outOfStockResult = mysqli_query($conn, $outOfStockSql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Monitoring</title>
    <!-- Include Bootstrap CSS and other necessary scripts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>

        <?php require("navbar.php")  ?>
     
    <section class="home" style="background-color: #f8f9fa;" > 
            <div class="toggle-sidebar" style="background-color:  #1e1e1e;">
                <i class='bx bx-menu' style="color:  white;"></i>
                <div class="text" style="color:  white;">Admin - Stocks</div>
            </div>


    <div class="container">
      <BR><BR><BR>
        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="col-md-4 mb-4">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['category']; ?></h5>
                            <p class="card-text">Total Quantity: <?php echo $row['total_quantity']; ?></p>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Display out-of-stock products in a table -->

        
<div class="container mt-5">
    <h2>Out of Stock Products</h2>
    <table class="table">
        <thead>
        <tr>
            <th>Category</th>
            <th>Product Name</th>
             <th>Quantity</th>
        </tr>
        </thead>
        <tbody>
                        <?php while ($outOfStockRow = mysqli_fetch_assoc($outOfStockResult)) { ?>
                            <tr>
                                <td><?php echo $outOfStockRow['category']; ?></td>
                                <td><?php echo $outOfStockRow['product_name']; ?></td>
                                <td><?php echo $outOfStockRow['quantity']; ?></td>
                            </tr>
                        <?php } ?>
                        <?php if(mysqli_num_rows($outOfStockResult) == 0) { ?>
                            <tr>
                                <td colspan="3" class="text-center">No out of stock products</td>
                            </tr>
                        <?php } ?>
                    </tbody>
    </table>
</div>


</section>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
    <script src="../javascript/admin.js"></script>
</body>
</html>

