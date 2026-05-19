<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<style>
    body{
    background-color: #f0f2f5;
  }
 .list-group {
  z-index: 100; /* or another appropriate value */
}
.container{
    background-color: white;
    border-radius: 14px;
    max-width: 1200px; /* or another desired width */
    padding: 15px; /* or desired padding */
    margin: auto; /* center align */
    padding-left: 50px; /* or desired value */
    padding-right: 50px; /* or desired value */
}

</style>

<body>
<?php require 'header.php';
include '../includes/dbhandler.inc.php';?>
<br><br>
<div class="container">
    <br>
        <h2 >Checkout Form</h2>
        <hr>
        <br><br>
    <div class="row">
        <div class="col-md-4 order-md-2 mb-4">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Your cart</span>
                <span class="badge badge-secondary badge-pill">3</span>
            </h4>
            <ul class="list-group mb-3">
                <?php
                $totalPrice = 0;
                if(isset($_SESSION['user_id'])) {
                    $uid = $_SESSION['user_id'];
                    

                      $sql = "SELECT username, email FROM user WHERE user_id = '$uid'";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if user exists
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $username = $row['username'];
        $email = $row['email'];
    } else {
        // Handle the case where user is not found
        $username = "";
        $email = "";
    }

                    $sql = "SELECT * FROM cart WHERE user_id = '$uid' AND isActive = 1 AND isDeleted = 0";
                    $stmt = mysqli_stmt_init($conn);
                    mysqli_stmt_prepare($stmt, $sql);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    if (mysqli_num_rows($result) == 0) {
                        echo ("<tr><td colspan='4'>Your cart is empty.</td></tr>");
                    }
                    while($row = mysqli_fetch_assoc($result)) {?>
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <div>
                                <h6 class="my-0">
                                    <div class="single-pro-image">
                                        <img src="../images/<?php echo $row['image'];?>" width="20%">
                                    </div>
                                </h6>
                                <small class="text-muted"><?php echo $row['product_name'];?></small>
                                <?php 
                                    if ($row['qty']>1) {?>
                                        <strong>x <?php echo $row['qty'];?></strong><?php
                                    }
                               ?>
                            </div>
                            <span class="text-muted"><?php echo $row['price']*$row['qty']; 
                                                            $totalPrice += $row['price']*$row['qty'];?>
                            </span>
                        </li>
                    <?php }
                    }
               ?>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Total (PHP)</span>
                    <strong><?php echo $totalPrice;?></strong>
                </li>
            </ul>
        </div>
        <div class="col-md-8 order-md-1">
            <h4 class="mb-3">Billingaddress</h4>
            <form class="needs-validation" action="../includes/receipt.inc.php" method="post" novalidate="">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="firstName">First name</label>
                        <input type="text" class="form-control" name="firstName" id="firstName" placeholder="" value="" required="">
                        <div class="invalid-feedback"> Valid first name is required. </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lastName">Last name</label>
                        <input type="text" class="form-control" name="lastName" id="lastName" placeholder="" value="" required="">
                        <div class="invalid-feedback"> Valid last name is required. </div>
                    </div>
                </div>
               <div class="mb-3">
    <label for="username">Username</label>
    <input type="text" class="form-control" name="username" id="username" value="<?php echo $username; ?>" disabled>
    <input type="hidden" name="user_id" value="<?php echo $username; ?>">
<input type="hidden" name="email" value="<?php echo $email; ?>">
</div>
<div class="mb-3">
    <label for="email">Email</label>
    <input type="email" class="form-control" name="email" id="email" value="<?php echo $email; ?>" disabled>
</div>
                <div class="mb-3">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" name="address" id="address" placeholder="1234 Main St" required="">
                    <div class="invalid-feedback"> Please enter your shipping address. </div>
                </div>
                <div class="mb-3">
                    <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
                    <input type="text" class="form-control" name="address2" id="address2" placeholder="Apartment or suite">
                </div>
                <div class="row">
                    <div class="col-md-6 d-flex justify-content-between">
                        <!-- Zip Input -->
                        <div class="w-50 me-2"> <!-- Adjusts width and margin-right -->
                            <label for="zip">City</label>
                            <input type="text" class="form-control" name="city" id="city" placeholder="" required>
                            <div class="invalid-feedback">Zip code required.</div>
                        </div>

                        <!-- City Input -->
                        <div class="w-50 ms-2"> <!-- Adjusts width and margin-left -->
                            <label for="city">Zip</label>
                            <input type="text" class="form-control" name="zip" id="zip" placeholder="" required>
                            <div class="invalid-feedback">City required.</div>
                        </div>
                    </div>
                </div>
                <br>
                <h4 class="mb-3">Payment</h4>
                                <select name="checkoutPaymentOpt" id="checkoutPaymentOpt" style="height: 30px;">
                                    <option value="default" selected>Select Payment Method</option>
                                    <option value="cash">Cash</option>
                                </select>   

                                <input type="hidden" name="totalprice" id="totalprice" value="<?php echo $totalPrice; ?>">
                <br><br>
                <button type="submit" class="btn btn-success" name="checkoutbtn" id="checkoutbtn" >Continue to checkout</button>
                <br><br>
            </form>
        </div>
    </div>
</div>
<br><br>

   

    <?php require 'footer.php' ?>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
  'use strict'

  window.addEventListener('load', function () {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation')

    // Loop over them and prevent submission
    Array.prototype.filter.call(forms, function (form) {
      form.addEventListener('submit', function (event) {
        if (form.checkValidity() === false) {
          event.preventDefault()
          event.stopPropagation()
        }
        form.classList.add('was-validated')
      }, false)
    })
  }, false)
}())
    </script>
</body>
</html>