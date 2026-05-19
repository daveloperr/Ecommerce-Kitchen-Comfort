<?php
session_start();
// Redirect if not logged in as admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../home_user/login.php"); // Redirect to login page or a 'denied' page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Dashboard</title>
    <!-- Box Icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>



<div class="navigation bar">
    <div class="sidebar close">
        <a href="#" class="logo-box">
            <i class='bx bxl-xing'></i>
            <div class="logo-name">Kitchen Comfort</div>
        </a>

    <!--- ========== List ============ -->
        <ul class="sidebar-list">
            <!-- -------- Non Dropdown List Items------------ -->
            <li>
                <div class="title">
                    <a href="#" class="link">
                        <i class='bx bx-grid-alt'></i>
                        <span class="name">Dashboard</span>
                    </a>
                </div>
                <div class="submenu">
                    <a href="#" class="link submenu-title">Dashboard </a>
                </div>
            </li>

            <li>
                <div class="title">
                    <a href="#" class="link">
                        <i class='bx bx-grid-alt'></i>
                        <span class="name">Products</span>
                    </a>
    
                </div>
                <div class="submenu">
                    <a href="#" class="link submenu-title">Products </a>

                </div>
            </li>

            <li>
                <div class="title">
                    <a href="#" class="link">
                        <i class='bx bx-grid-alt'></i>
                        <span class="name">Orders</span>
                    </a>

                </div>
                <div class="submenu">
                    <a href="#" class="link submenu-title">Orders </a>
    
                </div>
            </li>

            <li>
                <div class="title">
                    <a href="#" class="link">
                        <i class='bx bx-grid-alt'></i>
                        <span class="name">Stocks</span>
                    </a>

                </div>
                <div class="submenu">
                    <a href="#" class="link submenu-title">Stocks </a>
                </div>
            </li>

            <li>
                <div class="title">
                    <a href="#" class="link">
                        <i class='bx bx-user'></i>
                        <span class="name">Accounts</span>
                    </a>
                </div>
                <div class="submenu">
                    <a href="#" class="link submenu-title">Accounts</a>
                </div>
            </li>

            </li>


            <li>
                    <a href="../includes/logoutadmin.inc.php" class="link">
                        <i class='bx bx-log-out'></i>
                        <span class="name">Logout</span>
                    </a>
                </li>
        </ul>
    </div>

    <section class="home">
        <div class="toggle-sidebar" >
            <i class='bx bx-menu'></i>
            <div class="text">Toggle</div>
        </div>
    </section>
    </div>
 


<script  src="javaadmin.js"></script>
</body>
</html>