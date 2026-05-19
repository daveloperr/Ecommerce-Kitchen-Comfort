<?php
if(isset($_POST['registerbtn'])) {
    
    require 'dbconfig.inc.php'; 

    $username = $_POST['uname'];
    $password = $_POST['pword'];
    $confirmpass = $_POST['cpword'];
    $email = $_POST['email'];

    // Check if email already exists in the database
    $sql = "SELECT email FROM user WHERE email = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../home_user/register.php?error=sqlerror");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $result = mysqli_stmt_num_rows($stmt);
        if($result > 0) {
            header("Location: ../home_user/register.php?error=emailexist");
            exit();
        }
    }

    // Check if username already exists in the database
    $sql = "SELECT username FROM user WHERE username = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../home_user/register.php?error=sqlerror");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $result = mysqli_stmt_num_rows($stmt);
        if($result > 0) {
            header("Location: ../home_user/register.php?error=usernametaken");
            exit();
        }
    }

    // Perform additional validation checks
    if (empty($username) || empty($password) || empty($confirmpass) || empty($email)) {
        header("Location: ../home_user/register.php?error=required");
        exit();
    }
   
    if ($password !== $confirmpass) {
        header("Location: ../home_user/register.php?error=passwordnotmatch");
        exit();
    }

    // Check if a file was uploaded
    if (isset($_FILES['fileupload'])) {
        $file = $_FILES['fileupload'];
        $fileName = $_FILES['fileupload']['name'];
        $fileTmpName = $_FILES['fileupload']['tmp_name'];
        $fileSize = $_FILES['fileupload']['size'];
        $fileError = $_FILES['fileupload']['error'];
        $fileType = $_FILES['fileupload']['type'];

        // Perform file upload logic
        if ($fileSize === 0 || $fileError !== 0) {
            header("Location: ../home_user/register.php?error=uploadfileerror");
            exit();
        }

        $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if (!in_array($extension, $allowedExtensions)) {
            header('Location: ../home_user/register.php?error=filetypenotaccepted');
            exit();
        }

        $newFileName = $username . "." . $extension;
        $fileDirectory = "../images/" . $newFileName;

        if (move_uploaded_file($fileTmpName, $fileDirectory)) {
            // File uploaded successfully, continue with registration
            $hashedpass = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO user (username, password, email, picture) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../home_user/register.php?error=sqlerror");
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "ssss", $username, $hashedpass, $email, $newFileName);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                header("Location: ../home_user/register.php?registration=success");
                exit();
            }
        } else {
            header('Location: ../home_user/register.php?error=uploadfileerror');
            exit();
        }
    } else {
        // If no file uploaded, continue with registration without a picture
        $hashedpass = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO user (username, password, email) VALUES (?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../home_user/register.php?error=sqlerror");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "sss", $username, $hashedpass, $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            header("Location: ../home_user/register.php?registration=success");
            exit();
        }
    }
} else {
    header("Location: ../home_user/register.php");
    exit();
}
?>
