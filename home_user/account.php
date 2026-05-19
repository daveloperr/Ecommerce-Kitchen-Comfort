<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitchen Comfort Co.</title>
  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel ="stylesheet" href="style.css">
    <style>
       
        .center {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh; 
        }   
     
        .user-container {
            background-color: white;
            padding: 100px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        
        .submit-btn, .orders-btn {
            display: block;
            width: 100%;
            padding: 10px 20px;
            background-color: #4CAF50;  
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .user-image {
            width: 120px; 
            height: 1200px;
            border-radius: 50%;
            border: 2px solid  #088178;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        body{
        background-color: #f0f2f5;
      }
    </style>
</head>
<body>

 

     <?php require 'header.php'; ?>
  <?php
    if(isset($_SESSION['user_id'])) {
        // Include the database configuration file
        require '../includes/dbconfig.inc.php';

        $user_id = $_SESSION['user_id']; // Change 'username' to 'user_id'

        $sql = "SELECT username, picture FROM user WHERE user_id = ?";
        $stmt = mysqli_stmt_init($conn);
        if(mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $user_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $username = $row['username'];
                $picture = $row['picture'];

                echo '<div class="center">';
                echo '<div class="user-container">';
                echo '<img src="../images/'.$picture.'" class="user-image" alt="User Image" style="height: 250px; width: 250px;">';
                echo '<p>'.$username.'</p>';

                echo '<form action="../includes/logout.inc.php" method="post">'; // Form for logout
                echo '<a href="orders.php" class="btn btn-primary orders-btn">View Orders</a> <br>';
                echo '<button type="submit" class="btn btn-primary submit-btn">Logout</button>'; // Logout button
                echo '</form>';
                echo '</div>';
                echo '</div>';
            } else {
                echo "User not found or picture not available.";
            }
        } else {
            echo "Error: Unable to prepare SQL statement.";
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    } else {
        // If user is not logged in, provide link to login page
        echo '<div class="center">';
        echo '<div class="user-container">';
        echo '<a href="login.php" class="btn btn-primary">Login</a>'; // Login button
        echo '</div>';
        echo '</div>';
    }
?>

    <?php require 'footer.php'; ?>



  
 
</body>
</html>
