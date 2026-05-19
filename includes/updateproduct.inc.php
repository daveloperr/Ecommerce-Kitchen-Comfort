<?php

// Include database handler
include('dbhandler.inc.php');

// Check if the request is to fetch product data for the update modal
if(isset($_POST['id'])) {
    $productId = $_POST['id'];
    $sql = "SELECT * FROM products1 WHERE product_id = ?";
    
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../admin/admin_products.php?error=sqlerror");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "i", $productId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Check if a record is found
        if($result->num_rows > 0) {
            $product = mysqli_fetch_assoc($result);
            echo json_encode($product); // Send the product data as JSON
        } else {
            echo json_encode(['error' => 'Product not found']);
        }
    }

} else if(isset($_POST['updatebtn'])) {
    // If the update button is clicked, execute the update logic
    
    // Retrieve form data
    $productName = $_POST['updateprodname'];
    $description = $_POST['updatedesc'];
    $quantity = $_POST['updateqty'];
    $price = $_POST['updateprice'];
    $category = strtolower($_POST['updatecategory']); // Convert to lowercase
    $productId = $_POST['prodid'];

    // Mapping of lowercase category names to IDs
    $categoryMap = array(
        'appliances' => 1,
        'bakeware' => 2,
        'cookware' => 3,
        'tableware' => 4,
        'utensils' => 5
    );

    // Convert category name to lowercase before comparing
    $categoryLower = strtolower($category);

    // Check if the category name exists in the mapping
    if(!isset($categoryMap[$categoryLower])) {
        header("location: ../admin/admin_products.php?error=invalidcategory");
        exit();
    }

    $categoryId = $categoryMap[$categoryLower]; // Get the category ID from the mapping

    // Check if a new image file is uploaded
    if(isset($_FILES['updateimage']) && $_FILES['updateimage']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['updateimage'];
        $fileName = $_FILES['updateimage']['name'];
        $fileTmpName = $_FILES['updateimage']['tmp_name'];
        $fileSize = $_FILES['updateimage']['size'];
        $fileType = $_FILES['updateimage']['type'];

        // Validate the file type (optional)
        $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if(!in_array($extension, $allowedExtensions)) {
            header("Location: ../admin/admin_products.php?error=invalidfiletype");
            exit();
        }

        // Directory where the uploaded image will be stored
        $uploadDir = "../images/";
        // Generate a unique name for the image
        $newFileName = uniqid('', true) . '.' . $extension;
        $uploadPath = $uploadDir . $newFileName;

        // Move the uploaded file to the specified directory
        if(move_uploaded_file($fileTmpName, $uploadPath)) {
            // Update product data including the image file name
            $sql = "UPDATE products1 SET product_name = ?, description = ?, price = ?, quantity = ?, category = ?, category_id = ?, image = ? WHERE product_id = ?";
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../admin/admin_products.php?error=sqlerror");
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "ssdissisi", $productName, $description, $price, $quantity, $category, $categoryId, $newFileName, $productId);
                mysqli_stmt_execute($stmt);
                // Redirect upon successful update
                header("Location: ../admin/admin_products.php?status=updated");
                exit();
            }
        } else {
            // Error uploading file
            header("Location: ../admin/admin_products.php?error=uploaderror");
            exit();
        }
    } else {
        // If no new image uploaded, update product data without image
        $sql = "UPDATE products1 SET product_name = ?, description = ?, price = ?, quantity = ?, category = ?, category_id = ? WHERE product_id = ?";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../admin/admin_products.php?error=sqlerror");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "ssdisii", $productName, $description, $price, $quantity, $category, $categoryId, $productId);
            mysqli_stmt_execute($stmt);
            // Redirect upon successful update
            header("Location: ../admin/admin_products.php?status=updated");
            exit();
        }
    }
} else {
    // Handle invalid requests
    echo json_encode(['error' => 'Invalid request']);
}
?>
