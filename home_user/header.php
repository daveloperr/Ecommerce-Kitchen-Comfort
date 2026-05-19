<?php               
session_start();
include '../includes/dbconfig.inc.php';
?>



    <style>
        .user-image {
            width: 60px; 
            height: 60px;
            border-radius: 50%;
            border: 2px solid  white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        /* Scope styles to elements within the header section */
        #header {
            background-color: white;
            /* Add any other header-specific styles here */
        }
        .search-container {
            text-align: center;
            margin-top: 50px;
        }
        .search-box {
            width: 500px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 35px;
        }
    </style>

<section id="header">
        <a href="index.php"><img src="img_home/kitchencomfortlogo1.png" class="logo" alt="" style="width:250px; height:45px;"></a>
        <div class="search-container">
    <form action="../includes/search.inc.php" method="GET">
        <input type="text" name="search" class="search-box" placeholder="Search products...">
        
    </form>
</div>
        <div>
            <ul id="navbar">
                <li><a class="active" href="index.php">Home</a></li>
                <div class="dropdown">
                    <li><a href="index.php">Products</a></li>
                    <div class="dropdown-content">
                      <a href="cookware.php">Cookware</a>
                      <a href="bakeware.php">Bakeware</a>
                      <a href="utensils.php">Utensils</a>
                      <a href="tableware.php">Tableware</a>
                      <a href="appliances.php">Appliances</a>
                    </div>
                </div> 

                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                
  <?php
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];

            $sql = "SELECT username, picture FROM user WHERE user_id =?";
            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $picture = $row['picture'];

                    // Assuming the images folder is in the same directory as this PHP file
                    echo '<li><a href="account.php"><img src="../images/'. $picture. '" class="user-image" alt="User Image"></a></li>';
                } else {
                    echo "User not found or picture not available.";
                }
            } else {
                echo "Error: Unable to prepare SQL statement.";
            }
        } else {
            echo '<li><a href="login.php">Login</a></li>';
        }
       ?>

                <li><a href="../home_user/cart.php"><i class="bi bi-cart2" style="color:black;"></i></a></li>
            </ul>
        </div>
    </section>
