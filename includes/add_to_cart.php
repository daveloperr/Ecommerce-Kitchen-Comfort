<?php
if(isset($_POST['user_id'])) {
    require 'dbconfig.inc.php';

    $prodName = $_POST['productName'];
    $prodPrice = $_POST['productPrice'];
    $prodQuantity = $_POST['productQuantity'];
    $prodImage = $_POST['productImage'];
    $user_id = $_POST['user_id'];

    $sql = "SELECT * FROM `cart` WHERE product_name = ? AND user_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location:../home_user/index.php?error=sqlerror");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "si", $prodName, $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Record is found
        if($result->num_rows > 0) {
            // Update existing record in the cart
            $row = $result->fetch_assoc();
            $currentQuantity = $row['qty'];
            $newQuantity = $currentQuantity + $prodQuantity;
            $sql = "UPDATE `cart` SET `qty` = ?, `isDeleted` = 0 WHERE `product_name` = ? AND user_id = ?";
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location:../home_user/index.php?error=sqlerror");
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "isi", $newQuantity, $prodName, $user_id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                header("Location:../home_user/cart.php?addtocartsuccessfuly");
                exit();
            }
        } else {
            // Insert new record into the cart
            $sql = "INSERT INTO `cart`(`user_id`, `product_name`, `price`, `qty`, `image`) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location:../home_user/index.php?error=sqlerror");
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "isiss", $user_id, $prodName, $prodPrice, $prodQuantity, $prodImage);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                header("Location:../home_user/cart.php?addtocartsuccessfuly");
                exit();
            }
        }
    }
} else {
    header("Location:../home_user/index.php");
    exit();
}
?>
