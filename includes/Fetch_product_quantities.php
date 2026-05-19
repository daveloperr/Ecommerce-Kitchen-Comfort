<?php
// Include database connection
require '../includes/dbhandler.inc.php';

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

// Fetch product quantities and store them in an associative array
$productQuantities = [];
while ($row = mysqli_fetch_assoc($result)) {
    $productQuantities[$row['category']] = $row['total_quantity'];
}

// Return product quantities as JSON data
header('Content-Type: application/json');
echo json_encode($productQuantities);
?>
