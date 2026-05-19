<?php
    if(isset($_POST['addbtn'])) {
        
        require 'dbconfig.inc.php'; 

        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];

        // Check if email already exists in the database
        // Perform additional validation checks
        if (empty($username) || empty($password) ||  empty($email)) {
            header("Location: ../admin/admin_accounts.php?error=required"); // Adjusted redirect URL
            exit();
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: ../admin/admin_accounts.php?error=emailinvalid"); // Adjusted redirect URL
            exit();
        }
    

        // Check if a file was uploaded
        if (isset($_FILES['userpicture'])) { // Adjusted input name
            $file = $_FILES['userpicture'];
            $fileName = $_FILES['userpicture']['name'];
            $fileTmpName = $_FILES['userpicture']['tmp_name'];
            $fileSize = $_FILES['userpicture']['size'];
            $fileError = $_FILES['userpicture']['error'];
            $fileType = $_FILES['userpicture']['type'];

            // Perform file upload logic
            if ($fileSize === 0 || $fileError !== 0) {
                header("Location: ../admin/admin_accounts.php?error=uploadfileerror"); // Adjusted redirect URL
                exit();
            }

            $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            if (!in_array($extension, $allowedExtensions)) {
                header('Location: ../admin/admin_accounts.php?error=filetypenotaccepted'); // Adjusted redirect URL
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
                    header("Location: ../admin/admin_accounts.php?error=sqlerror"); // Adjusted redirect URL
                    exit();
                } else {
                    mysqli_stmt_bind_param($stmt, "ssss", $username, $hashedpass, $email, $newFileName);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                    mysqli_close($conn);
                    header("Location: ../admin/admin_accounts.php?registration=success"); // Adjusted redirect URL
                    exit();
                }
            } else {
                header('Location: ../admin/admin_accounts.php?error=uploadfileerror'); // Adjusted redirect URL
                exit();
            }
        } else {
            // If no file uploaded, continue with registration without a picture
            $hashedpass = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO user (username, password, email) VALUES (?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../admin/admin_accounts.php?error=sqlerror"); // Adjusted redirect URL
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "sss", $username, $hashedpass, $email);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                header("Location: ../admin/admin_accounts.php?registration=success"); // Adjusted redirect URL
                exit();
            }
        }
    } else {
        header("Location: ../admin/admin_accounts.php");
        exit();
    }
?>
