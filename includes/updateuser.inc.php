

<?php


if (isset($_POST['updatebtn'])) {

    require 'dbconfig.inc.php';

   
    $username = $_POST['updateusername'];
    $password = $_POST['updatepassword'];
    $email = $_POST['updateemail'];


    if (isset($_FILES['updateprofilepic']) && $_FILES['updateprofilepic']['error'] === UPLOAD_ERR_OK) {
        // Retrieve the uploaded file details
        $fileTmpName = $_FILES['updateprofilepic']['tmp_name'];
        $fileData = file_get_contents($fileTmpName);

   
        $sql = "UPDATE user SET picture = ?,  WHERE username = ?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            // Error handling
            header("Location: ../home_user/update.php?error=sqlerror");
            exit();
        } else {
           
            mysqli_stmt_bind_param($stmt, "sss", $fileData, $username);
            mysqli_stmt_execute($stmt);
        }
    }

  
}
?>
