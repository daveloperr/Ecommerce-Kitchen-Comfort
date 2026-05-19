<?php
if(isset($_POST['deletebtn'])) {
    // Get the post data of deleteproductid
    $prodid = $_POST['deleteproductid'];
    $isDeleted = 1;
    
    // Include database handler
    include('dbhandler.inc.php');
    
    $sql = "UPDATE products1 SET isDeleted = 1 WHERE product_id = ?";
    
    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sql)) {
        // Handle SQL error
        echo "SQL error: " . mysqli_error($conn);
    } else {
        // Bind the parameters
        mysqli_stmt_bind_param($stmt, "i", $prodid);
        
        // Execute the statement
        mysqli_stmt_execute($stmt);
        
        // Redirect back to admin products page
        header("Location: ../admin/admin_products.php");
        exit(); // Make sure to exit after redirection
    }
}
?>