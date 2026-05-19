<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel ="stylesheet" href="style.css">
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
include '../includes/dbhandler.inc.php';
?>


<?php 
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
}
?>

<br><br>
<div class="container">
    <br>
    <div class="row">
        <div class="col-md-4 order-md-1 mb-4" style="border-right: 1px solid #C7C8C9;">
            <h3 class="d-flex justify-content-between align-items-center mb-3">
                <span style="color: #088178;">Got questions?</span><br>
            </h3>
            <vr>
            <p>Feel free to reach out to us at <strong>+63 2 123 4567</strong>
            <br><br>
            10:00 - 18:00, Mon - Sat <br>
            10:00 - 14:00, Sun
            </p>
        </div>
        <div class="col-md-8 order-md-2">
            <h2 >Contact Form</h2>
            <hr>
            <form class="needs-validation" action="../includes/contact.inc.php"  method="post" novalidate=""> 
                <div class="row">
                <div class="mb-3">
                     <label for="email">Username</label>
    <input type="email" class="form-control" name="username" id="username" value="<?php echo $username; ?>" disabled>
     <input type="hidden" name="email" value="<?php echo $email; ?>">
                </div>
                </div>
                <div class="mb-3">
                     <label for="email">Email</label>
    <input type="email" class="form-control" name="email" id="email" value="<?php echo $email; ?>" disabled>
     <input type="hidden" name="email" value="<?php echo $email; ?>">
                </div>
                <div class="mb-3">
                    <label for="message">Message</label><br>
                    <textarea class="form-control" name="message" id="message" rows="6" maxlength="300" cols="75"  required=""></textarea>
                </div>
                <br>
                <button type="submit" class="btn btn-success" name="contactbtn" id="contactbtn" >Submit</button>
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
