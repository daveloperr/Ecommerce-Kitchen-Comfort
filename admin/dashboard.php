<?php
session_start();
// Redirect if not logged in as admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../home_user/login.php"); // Redirect to login page or a 'denied' page
    exit();
}
?>

<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link rel="stylesheet" href="admin_style.css">
  
        <title>Admin - Dashboard</title>
        <style>
            .table{
                border: 2px solid black;
                width: 100%;
            }
        </style>
    </head>

    <body>
        <?php require("navbar.php"); ?>
        <section class="home" style="background-color: #f8f9fa;" > 
            <div class="toggle-sidebar" style="background-color:  #1e1e1e;">
                <i class='bx bx-menu' style="color:  white;"></i>
                <div class="text" style="color:  white;">Admin - Dashboard</div>
            </div>
            <br>

    <?php 
   require '../includes/dbhandler.inc.php';

$sql_total_users = "SELECT COUNT(*) AS total_users FROM user WHERE isDeleted != 1";
$stmt_total_users = $conn->prepare($sql_total_users); 
$stmt_total_users->execute(); 
$result_total_users = $stmt_total_users->get_result(); 
$total_users_row = $result_total_users->fetch_assoc();
$total_users = $total_users_row['total_users'];


$sql_total_orders = "SELECT COUNT(*) AS total_orders FROM orders";
$stmt_total_orders = $conn->prepare($sql_total_orders);
$stmt_total_orders->execute(); 
$result_total_orders = $stmt_total_orders->get_result(); 
$total_orders_row = $result_total_orders->fetch_assoc();
$total_orders = $total_orders_row['total_orders']; 

$sql_total_messages = "SELECT COUNT(*) AS total_messages FROM support"; 
$stmt_total_messages = $conn->prepare($sql_total_messages); 
$stmt_total_messages->execute();
$result_total_messages = $stmt_total_messages->get_result(); 
$total_messages_row = $result_total_messages->fetch_assoc(); 
$total_messages = $total_messages_row['total_messages']; 

// Query for the total number of products
$sql_total_products = "SELECT COUNT(*) AS total_products FROM products1 WHERE isDeleted != 1"; // Query for total products
$stmt_total_products = $conn->prepare($sql_total_products); // prepare statement
$stmt_total_products->execute(); // Execute the statement
$result_total_products = $stmt_total_products->get_result(); // Get the result
$total_products_row = $result_total_products->fetch_assoc(); // Fetch the result as an assoc array
$total_products = $total_products_row['total_products']; // Get the total  count

// Helper function to execute a query and return a single result
function getTotalSales($conn, $sql) {
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    return $row['total_sales'];
}

// Current date for reference
$current_date = date("Y-m-d");

//total sales: current day
$sql_daily_sales = "SELECT SUM(total_id) AS total_sales FROM orders WHERE DATE(order_date) = '$current_date'";

//total sales: current week
$sql_weekly_sales = "SELECT SUM(total_id) AS total_sales FROM orders WHERE YEARWEEK(order_date, 1) = YEARWEEK('$current_date', 1)";

//total sales :current month
$sql_monthly_sales = "SELECT SUM(total_id) AS total_sales FROM orders WHERE YEAR(order_date) = YEAR('$current_date') AND MONTH(order_date) = MONTH('$current_date')";

//total sales:  current year
$sql_yearly_sales = "SELECT SUM(total_id) AS total_sales FROM orders WHERE YEAR(order_date) = YEAR('$current_date')";

//total sales for each timeframe
$total_daily_sales = getTotalSales($conn, $sql_daily_sales);
$total_weekly_sales = getTotalSales($conn, $sql_weekly_sales);
$total_monthly_sales = getTotalSales($conn, $sql_monthly_sales);
$total_yearly_sales = getTotalSales($conn, $sql_yearly_sales);



      
    ?>

    <div class="flex-container">
    <div class="flex1" id="grad">
        <span style="font-size: 30px;"><?php echo $total_users ?></td></span>
        <h5>Total Users</h5>
    </div>
    <div class="flex2" id="grad">
        <span style="font-size: 30px;"><?php echo $total_messages ?></span>
        <h5>Messages</h5>
    </div>
    <div class="flex3" id="grad">
        <span style="font-size: 30px;"><?php echo $total_products ?></span>
        <h5>Total Products</h5>
    </div>
    <div class="flex4" id="grad">
        <span style="font-size: 30px;"> <?php echo $total_orders ?></span>
        <h5>Orders</h5>
    </div>
    </div>

<?php
// Assuming `$current_date` is defined, and `orders` has `isOrderAccepted`
// and `cart` has `isActive` to indicate valid orders and active carts

// total sales for current day with isOrderAccepted and isActive conditions
$sql_daily_sales = "
    SELECT SUM(cart.qty * products1.price) AS total_sales
    FROM orders
    JOIN cart ON orders.order_id = cart.order_id
    JOIN products1 ON cart.product_name = products1.product_name
    WHERE DATE(orders.order_date) = '$current_date'
      AND orders.isOrderAccepted = 1
      AND cart.isActive = 2
";

// total sales for current week
$sql_weekly_sales = "
    SELECT SUM(cart.qty * products1.price) AS total_sales
    FROM orders
    JOIN cart ON orders.order_id = cart.order_id
    JOIN products1 ON cart.product_name = products1.product_name
    WHERE YEARWEEK(orders.order_date, 1) = YEARWEEK('$current_date', 1)
      AND orders.isOrderAccepted = 1
      AND cart.isActive = 2
";

// total sales for current month
$sql_monthly_sales = "
    SELECT SUM(cart.qty * products1.price) AS total_sales
    FROM orders
    JOIN cart ON orders.order_id = cart.order_id
    JOIN products1 ON cart.product_name = products1.product_name
    WHERE YEAR(orders.order_date) = YEAR('$current_date')
      AND MONTH(orders.order_date) = MONTH('$current_date')
      AND orders.isOrderAccepted = 1
      AND cart.isActive = 2
";

// total sales for current year
$sql_yearly_sales = "
    SELECT SUM(cart.qty * products1.price) AS total_sales
    FROM orders
    JOIN cart ON orders.order_id = cart.order_id
    JOIN products1 ON cart.product_name = products1.product_name
    WHERE YEAR(orders.order_date) = YEAR('$current_date')
      AND orders.isOrderAccepted = 1
      AND cart.isActive = 2
";


// Getting the total sales for each timeframe
$total_daily_sales = getTotalSales($conn, $sql_daily_sales);
$total_weekly_sales = getTotalSales($conn, $sql_weekly_sales);
$total_monthly_sales = getTotalSales($conn, $sql_monthly_sales);
$total_yearly_sales = getTotalSales($conn, $sql_yearly_sales);

?>

    <div class="flex-container3">
        <div class="flx1">
            <h5>Total Sales (Per Day) :</h5> 
            <h1 style="margin-left: 350px; margin-top: -35px; color: #088178">₱<?php echo $total_daily_sales; ?></h1>
        </div>
        <div class="flx1">
            <h5>Total Sales (Per Month) :</h5> 
            <h1 style="margin-left: 350px; margin-top: -35px; color: #088178">₱<?php echo $total_monthly_sales; ?></h1>
        </div>
    </div>
    <div class="flex-container3">
        <div class="flx2">
            <h5>Total Sales (Per Week) :</h5> 
            <h1 style="margin-left: 350px; margin-top: -35px; color: #088178">₱<?php echo $total_weekly_sales; ?></h1>
        </div>
        <div class="flx2">
            <h5>Total Sales (Per Year) :</h5> 
            <h1 style="margin-left: 350px; margin-top: -35px; color: #088178">₱<?php echo $total_yearly_sales; ?></h1>
        </div>
    </div>

    <br><br>
    
    <?php
// Assuming you have already established a database connection in $conn
$sql_best_sellers = "
SELECT 
    cart.product_name, 
    SUM(cart.qty) AS total_sales, -- Total quantity sold
    products1.price,
    products1.category,
    products1.product_id
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
    orders.isOrderAccepted = 1 -- Consider only accepted orders
    AND cart.isActive = 2       -- Consider only active items in cart
GROUP BY 
    cart.product_name          -- Group by product name
ORDER BY 
    total_sales DESC           -- Order by total sales in descending order
LIMIT 
    10                         -- Limit to top 10 best-selling products
";

$result = $conn->query($sql_best_sellers); // Execute the query
?>

<div class="container mt-5">
    <h2>Best Selling Products</h2>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Product Name</th>
                <th scope="col">Category</th>
                <th scope="col">Price</th>
                <th scope="col">Total Sales</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                $index = 1;
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<th scope="row">' . $index . '</th>'; // Serial number
                    echo '<td>' . htmlspecialchars($row['product_name']); '</td>'; // Product name
                    echo '<td>' . htmlspecialchars($row['category']); '</td>'; // Category
                    echo '<td>₱' . htmlspecialchars($row['price']); '</td>'; // Price
                    echo '<td>' . htmlspecialchars($row['total_sales']); '</td>'; // Total sales
                    echo '</tr>';
                    $index++;
                }
            } else {
                echo '<tr><td colspan="5">No best-selling products found.</td></tr>'; // Handle no results case
            }
            ?>
        </tbody>
    </table>
</div>

<?php
// Assuming you have already established a database connection in $conn
$sql_best_sellers = "
SELECT cart.order_id, cart.product_name, orders.name, cart.price, cart.qty
FROM cart
INNER JOIN orders ON cart.order_id = orders.order_id
WHERE orders.isOrderAccepted = 1
ORDER BY cart.order_id DESC;
";

$result = $conn->query($sql_best_sellers); // Execute the query
?>

<div class="container mt-5">
    <h2>Recent Purchases</h2>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Product</th>
                <th scope="col">Buyer</th>
                <th scope="col">Quantity</th>
                <th scope="col">Price</th>
                <th scope="col">Total Sales</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                $index = 1;
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['order_id']); '</td>'; // Order ID
                    echo '<td>' . htmlspecialchars($row['product_name']); '</td>'; // Product Name
                    echo '<td>' . htmlspecialchars($row['name']); '</td>'; // Buyer Name
                    echo '<td>' . htmlspecialchars($row['qty']); '</td>'; // Quantity
                    echo '<td>₱' . htmlspecialchars($row['price']); '</td>'; // Price
                    echo '<td>₱' . htmlspecialchars($row['price']*$row['qty']); '</td>'; // Total Price
                    echo '</tr>';
                    $index++;
                }
            } else {
                echo '<tr><td colspan="5">No orders found.</td></tr>'; // Handle no results case
            }
            ?>
        </tbody>
    </table>
</div>

<?php
$conn->close(); // Close the database connection
?>

        </section>
        </div>
    
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
        <script  src="../javascript/admin.js"></script>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</body>
</html>