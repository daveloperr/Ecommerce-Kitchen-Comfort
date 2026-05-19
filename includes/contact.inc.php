<?php 
session_start(); 

if (isset($_POST['contactbtn'])) { 
    require 'dbconfig.inc.php';


    
    $userEmail = $_POST['email'];
    $userMessage = $_POST['message'];
    

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../home_user/contact.php?error=unauthorized");
        exit();
    }

    $userId = $_SESSION['user_id']; 
    $sql = "INSERT INTO support (user_id, message, inquiry_datetime) VALUES (?, ?, NOW())";

    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../home_user/contact.php?error=sqlerror");
        exit();
    } else {

        mysqli_stmt_bind_param($stmt, "is", $userId, $userMessage);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        header("Location: ../home_user/contact.php?success=supportTicketSent");
        exit();
    }
} else {
    header("Location: ../home_user/contact.php?error=accessDenied");
    exit();
}
?>
