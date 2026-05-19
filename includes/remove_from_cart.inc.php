<?php
include('dbhandler.inc.php');

if(isset($_POST['deletebtn']) && isset($_POST['user_id'])) {
    // Get the product name to be deleted
    $productName = $_POST['deletebtn']; // Retrieve the product name from the $_POST array

    $isDeleted = 1;
    
    // Update the cart table to set isDeleted to 1 for the specified product name
    $sql = "UPDATE cart SET isDeleted = ?, qty = 0 WHERE product_name = ?";
    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../home_user/cart.php?error=sqlerror");
    } else {
        // Bind the parameters
        mysqli_stmt_bind_param($stmt, "is", $isDeleted, $productName);
        // Execute the statement
        mysqli_stmt_execute($stmt);
        // No need to fetch the result since this is an update query
        // Instead, we navigate the user back to the cart.php page
        header("location: ../home_user/cart.php");
    }
} else {
    // Handle the case when deleteproduct is not set
    header("location: ../home_user/cart.php?error=invalidrequest");
}
?>
