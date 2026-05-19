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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="admin_style.css">
  
    <title>Admin - Product Page</title>
    </head>

    <body>
    <?php  require("navbar.php") ?>

    
    <section class="home" style="background-color: #f8f9fa;" > 
            <div class="toggle-sidebar" style="background-color:  #1e1e1e;">
                <i class='bx bx-menu' style="color:  white;"></i>
                <div class="text" style="color:  white;">Admin - Products</div>
            </div>
        
<br>
      
      <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-body">
                  
                            <hr>
                            <div class="table-responsive">
                                <table id="ordertbl" class="table table-bordered">
                                <button type="button" class="btn btn-success add_btn" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Product</button>
                  <thead>
                    <tr>
                      <th>Product Name</th>
                      <th>Image</th>
                      <th>Category</th>
                      <th>Description</th>
                      <th>Quantity</th>
                      <th>Price</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      require '../includes/dbhandler.inc.php';
                      $sql = "SELECT * FROM products1 WHERE isDeleted != 1";
                      $stmt = mysqli_stmt_init($conn);
                      if(!mysqli_stmt_prepare($stmt, $sql)) {
                        header("location: ../admin_products.php?error=sqlerror");
                      } else {
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        while($row = mysqli_fetch_assoc($result)) {
                          ?>
                        <tr>
                              <td><?php echo $row['product_name']; ?></td>
                              <td><img src="../images/<?php echo $row['image']; ?>" width="100" height="100"></td>
                              <td><?php echo $row['category']; ?></td>
                          
                              <td><?php echo $row['description']; ?></td>
                              <td><?php echo $row['quantity']; ?></td>
                              <td><?php echo $row['price']; ?></td>
                              <td>
        <!-- Using a container with display:flex to align buttons horizontally -->
        <div style="display: flex; gap: 10px;"> <!-- 'gap' controls spacing between buttons -->
            <button type="button" class="btn btn-outline-success update_btn" data-bs-toggle="modal" data-bs-target="#updateModal" value="<?php echo $row['product_id']; ?>">Update</button>
            <button type="button" class="btn btn-outline-danger delete_btn" data-bs-toggle="modal" data-bs-target="#deleteModal" value="<?php echo $row['product_id']; ?>">Delete</button>
        </div>
    </td>
</tr>

                      <?php }
                      } ?>
                  </tbody>
                </table>
                <!--Add Product Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" >
                    <div class="modal-content">
                      <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Product</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <form method="post" action="../includes/addproduct.inc.php" enctype="multipart/form-data">
                          <input type="text" name="prodname" id="prodname" placeholder="Product Name">
                          <br>
                          <input type="text" name="category" id="category" placeholder="Product Category"> <br>
                          <input type="text" name="price" id="price" placeholder="Price"> <br>
                          <input type="text" name="qty" id="qty" placeholder="Quantity"> <br>
                          <textarea name="desc" id="desc" placeholder="Description"></textarea>

                          <label for="image"  class="form-label" >Select files:</label>
                          <input type="file"   class="form-label" id="image" name="image" multiple>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary" name="addbtn" id="addbtn" value="Add Product">
                        </form>
                      </div>
                    </div>
                  </div>
                </div>

                <!--Update Product Modal -->
                <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" >
                    <div class="modal-content">
                      <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Update Product</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <form method="post" action="../includes/updateproduct.inc.php" enctype="multipart/form-data">
                          <input type="text" name="updateprodname" id="updateprodname" placeholder="Product Name">
                          <br>
                          <input type="text" name="updatecategory" id="updatecategory" placeholder="Product Category"> <br>
                          <input type="text" name="updateprice" id="updateprice" placeholder="Price"> <br>
                          <input type="text" name="updateqty" id="updateqty" placeholder="Quantity"> <br>
                          <textarea name="updatedesc" id="updatedesc" placeholder="Description"></textarea>

                          <label for="image"  class="form-label" >Select files:</label>
                          <input type="file"   class="form-label" id="updateimage" name="updateimage" multiple>

                          <input type="hidden" name="prodid" id="prodid" value="">
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" name="updatebtn" id="updatebtn" value="Update">
                        </form>
                      </div>
                    </div>
                  </div>
                </div>

                <!--Delete Product Modal -->
                <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Product</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        Are you sure you want to delete this product?
                        <form action="../includes/deleteproduct.inc.php" method="post">
                          <input type="hidden" name="deleteproductid" id="deleteproductid" value="">
                      </div>
                          <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                          <input type="submit" class="btn btn-primary" name="deletebtn" id="deletebtn" value="Yes">
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </section>





  <script type="text/javascript">
          $(document).ready(function() {
          $('#ordertbl').DataTable();
          } );
  </script>   

<script>
    document.getElementById("updatebtn").addEventListener("click", function(event) {
        // Prevent the form submission if the quantity is not a positive integer
        var quantityInput = document.getElementById("updateqty");
        var quantity = quantityInput.value;
        if (!isPositiveInteger(quantity)) {
            event.preventDefault();
            alert("Please enter a valid positive integer for quantity.");
        }

        // Prevent the form submission if the price is not a valid number or is negative
        var priceInput = document.getElementById("updateprice");
        var price = priceInput.value;
        if (!isValidPrice(price)) {
            event.preventDefault();
            alert("Please enter a valid positive number for price.");
        }
    });

    // Function to check if a value is a positive integer
    function isPositiveInteger(value) {
        return /^\d+$/.test(value) && parseInt(value) > 0;
    }

    // Function to check if a value is a valid positive number for price
    function isValidPrice(value) {
        return !isNaN(parseFloat(value)) && parseFloat(value) >= 0;
    }
</script>

<script>
    document.getElementById("updatebtn").addEventListener("click", function(event) {
        // Prevent the form submission if the quantity is not a positive integer
        var quantityInput = document.getElementById("updateqty");
        var quantity = quantityInput.value;
        if (!isPositiveInteger(quantity)) {
            event.preventDefault();
            alert("Please enter a valid positive integer for quantity.");
        }
    });

    // Function to check if a value is a positive integer
    function isPositiveInteger(value) {
        return /^\d+$/.test(value) && parseInt(value) > 0;
    }
</script>

  <!--Assign product details in update modal using ajax -->
  <script type="text/javascript">
      $(document).on('click','.update_btn',function(){
          var pid = $(this).attr('value');
          $.ajax({
              url: "../includes/updateproduct.inc.php",
              method: "POST",
              data: {id:pid},
              dataType: "json",
              success:function(data){
                  $('#updateprodname').val(data.product_name);
                  $('#updatecategory').val(data.category);
                  $('#updateprice').val(data.price);
                  $('#updateqty').val(data.quantity);
                  $('#updatedesc').val(data.description);
                  $('#prodid').val(data.product_id);
            
              if (data.image) {
                  $('#updateimage').val(data.image);
              }
              }

          });
      });
  </script>

  <script type="text/javascript">
      $(document).on('click', '.add_btn', function() {
          $.ajax({
              url: "../includes/addproduct.inc.php", 
              method: "POST", 
              dataType: "json",
              success: function(data) {
                  // Populate modal fields with data received from the server
                  $('#prodname').val(data.product_name);
                  $('#category').val(data.category);
                  $('#price').val(data.price);
                  $('#qty').val(data.quantity);
                  $('#desc').val(data.description);
                  if (data.image) {
                          $('#image').val(data.image);
                      }
              },
            
          });
      });
  </script>

  <!--    -->
      <script type="text/javascript">
        $(document).on('click','.delete_btn',function(){
            //session the product_id to 
            var pid = $(this).attr('value');
            document.getElementById("deleteproductid").value = pid;
        });
      </script>

      <script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>

      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

      <script  src="../javascript/admin.js"></script>
  </body>
</html>
