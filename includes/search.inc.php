<?php
include '../includes/dbhandler.inc.php';

if (isset($_GET['search'])) {
    $search = $_GET['search'];

    // Search in HTML pages
    $htmlPages = ['../home_user/appliances.php', '../home_user/cookware.php', '../home_user/bakeware.php', '../home_user/utensils.php'];
    foreach ($htmlPages as $page) {
        $content = file_get_contents($page);
        if (stripos($content, $search) !== false) {
            header("Location: $page");
            exit();
        }
    }

    // Search in database
    $sql = "SELECT product_id FROM products1 WHERE product_name LIKE '%$search%'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $product_id = $row['product_id'];
            
            header("Location: ../home_user/productview.php?id=$product_id");
            exit();
        }
    }


  header("location: ../home_user/index.php?error=Cantfindproduct");
}

$conn->close();
?>
