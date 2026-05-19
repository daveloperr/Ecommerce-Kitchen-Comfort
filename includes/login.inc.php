<?php
session_start();

// Initialize the failed login attempts and lockout time if not already set
if (!isset($_SESSION['failed_attempts'])) {
    $_SESSION['failed_attempts'] = 0;
    $_SESSION['lockout_time'] = 0;
}

if (isset($_POST['loginbtn'])) {
    require 'dbconfig.inc.php'; 

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the account is currently locked
    if (time() < $_SESSION['lockout_time']) {
        $remaining_lock_time = $_SESSION['lockout_time'] - time();
        header("Location:../home_user/login.php?message=Account locked. Try gain in {$remaining_lock_time} seconds.");
        exit();
    }

    // Authentication logic for both admin and regular users
    $adminCredentials = $username === 'admin' && $password === 'letran1620';
    $sql = "SELECT * FROM user WHERE username=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location:../home_user/login.php?error=sqlerror");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $userExists = mysqli_fetch_assoc($result);

        if ($adminCredentials || ($userExists && password_verify($password, $userExists['password']))) {
            $_SESSION['user_id'] = $userExists['user_id']; // Set user_id in session
            $_SESSION['role'] = $adminCredentials? 'admin' : 'user';
            $_SESSION['failed_attempts'] = 0; // Reset failed attempts after successful login
            $redirectPage = $adminCredentials? "../admin/dashboard.php" : "../home_user/index.php";
            header("Location: ". $redirectPage);
            exit();
        } else {
            $_SESSION['failed_attempts']++;
            if ($_SESSION['failed_attempts'] >= 5) {
                $_SESSION['lockout_time'] = time() + 30; // Lock the account for 30 seconds
                $_SESSION['failed_attempts'] = 0; // Reset failed attempts
                header("Location:../home_user/login.php?message=Too many failed attempts. Account locked for 30 seconds.");
                exit();
            }
            $attemptsLeft = 5 - $_SESSION['failed_attempts'];
            header("Location:../home_user/login.php?message=Incorrect password. Attempts left: ". $attemptsLeft);
            exit();
        }
    }
} else {
    header("Location:../home_user/login.php");
    exit();
}
?>