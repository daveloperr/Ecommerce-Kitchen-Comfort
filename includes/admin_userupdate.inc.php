<?php

// Include database handler
include('dbhandler.inc.php');

// Check if the request is to fetch user data for the update modal
if (isset($_POST['id'])) {
    $userId = $_POST['id'];
    $sql = "SELECT * FROM user WHERE user_id = ?";
    
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo json_encode(['error' => 'SQL preparation error']);
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Check if a record is found
        if ($result->num_rows > 0) {
            $user = mysqli_fetch_assoc($result);
            echo json_encode($user); // Send the user data as JSON
        } else {
            echo json_encode(['error' => 'User not found']);
        }
    }

} else if (isset($_POST['updatebtn'])) {
    // If the update button is clicked, execute the update logic
    
    $username = $_POST['updateusername'];
    $password = $_POST['updatepassword'];
    $email = $_POST['updateemail'];
    $userId = $_POST['userid'];

    // Hash the password before updating
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Check if a new image file is uploaded
    if (isset($_FILES['updatepicture']) && $_FILES['updatepicture']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['updatepicture'];
        $fileName = $_FILES['updatepicture']['name'];
        $fileTmpName = $_FILES['updatepicture']['tmp_name'];
        $fileSize = $_FILES['updatepicture']['size'];
        $fileType = $_FILES['updatepicture']['type'];

        // Validate the file type (optional)
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($fileType, $allowedTypes)) {
            header("Location: ../admin/admin_accounts.php?error=invalidfiletype");
            exit();
        }

        // Directory where the uploaded image will be stored
        $uploadDir = "../images/";
        // Generate a unique name for the image
        $newFileName = uniqid('', true) . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
        $uploadPath = $uploadDir . $newFileName;

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($fileTmpName, $uploadPath)) {
            // Update user data including the image file name
            $sql = "UPDATE user SET username = ?, password = ?, email = ?, picture = ? WHERE user_id = ?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../admin/admin_accounts.php?error=sqlerror");
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "ssssi", $username, $hashedPassword, $email, $newFileName, $userId);
                mysqli_stmt_execute($stmt);
                // Redirect upon successful update
                header("Location: ../admin/admin_accounts.php?status=updated");
                exit();
            }
        } else {
            // Error uploading file
            header("Location: ../admin/admin_accounts.php?error=uploaderror");
            exit();
        }
    } else {
        // If no new image uploaded, update user data without image
        $sql = "UPDATE user SET username = ?, password = ?, email = ? WHERE user_id = ?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../admin/admin_accounts.php?error=sqlerror");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "sssi", $username, $hashedPassword, $email, $userId);
            mysqli_stmt_execute($stmt);
            // Redirect upon successful update
            header("Location: ../admin/admin_accounts.php?status=updated");
            exit();
        }
    }
} else {
    // Handle invalid requests
    echo json_encode(['error' => 'Invalid request']);
}
?>