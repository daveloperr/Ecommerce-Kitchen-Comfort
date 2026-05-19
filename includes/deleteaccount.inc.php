<?php
if (isset($_POST['deletebtn'])) {
 
    $userId = $_POST['deleteUserId'];

    
    include 'dbhandler.inc.php';

    
    $sql = "UPDATE user SET isDeleted = 1 WHERE user_id = ?";


    $stmt = mysqli_stmt_init($conn);


    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../admin/admin_accounts.php?error=sqlerror");
        exit();
    } else {
        
        mysqli_stmt_bind_param($stmt, "i", $userId);

        
        mysqli_stmt_execute($stmt);

  
        header("Location: ../admin/admin_accounts.php?success=hidden");
        exit();
    }
}
?>