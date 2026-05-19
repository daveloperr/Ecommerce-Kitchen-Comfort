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
  
        <title>Admin - Accounts</title>
    </head>

    <body>
    <?php require("navbar.php")  ?>

    
    <section class="home" style="background-color: #f8f9fa;" > 
            <div class="toggle-sidebar" style="background-color:  #1e1e1e;">
                <i class='bx bx-menu' style="color:  white;"></i>
                <div class="text" style="color:  white;">Admin - Accounts</div>
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
                                <button type="button" class="btn btn-success add_btn" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Accounts</button>
            <thead>
                                 <tr>
                                    <th>ID</th>
                                    <th>Picture</th>
                                    <th>Username</th>
                                    <th>Password</th>
                                    <th>Email</th>
                          
                                    <th>Actions</th>
                                </tr>
                        </thead>
            <tbody>
            <?php 

                require '../includes/dbhandler.inc.php';

                $sql = "SELECT * FROM user WHERE isDeleted != 1";

                $stmt = mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt, $sql)) {
                    header("location: ../admin_accounts.php?error=sql");
                } else {
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    while($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['user_id']?></td>
                                        <td><img src="../images/<?php echo $row['picture']; ?>" height="100" width="100" alt="User Image"></td>
                                        <td><?php echo $row['username']?></td>
                                        <td><?php echo $row['password']?></td>
                                        <td><?php echo $row['email']?></td>
                                        <td>
                                        <div style="display: flex; gap: 10px;"> <!-- 'gap' controls spacing between buttons -->
                                        <button type="button" class="btn btn-outline-success update_btn" data-bs-toggle="modal" data-bs-target="#updateModal" value="<?php echo $row['user_id'] ?>">Update</button>
                                       <button type="button" class="btn btn-outline-danger delete_btn" data-bs-toggle="modal" data-bs-target="#deleteModal" value="<?php echo $row['user_id'] ?>">Delete</button>
                                        </div>
                                      </td>
                                    </tr>
                                <?php } ?>
                    <?php } ?>
             </tbody>
         </table>
        </div>   
      </section>



<!--Delete Product Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Account</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this account?
        <form action="../includes/deleteaccount.inc.php" method="post">
        <input type="hidden" name="deleteUserId" id="deleteUserId" value="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
        <input type="submit" class="btn btn-primary" name="deletebtn" id="deletebtn" value="Yes">
        </form>
      </div>
    </div>
  </div>
</div>



    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Add Accounts</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form method="post" action="../includes/addaccounts.inc.php" enctype="multipart/form-data">
                <input type="text" name="username" id="username" placeholder="Username">
                <br>
                <input type="text" name="password" id="password" placeholder="Password"> <br>
                <input type="text" name="email" id="email" placeholder="Email"> <br>
                <label for="image"  class="form-label" >Select Image:</label>
                <input type="file"   class="form-label" id="userpicture" name="userpicture" multiple>

            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <input type="submit" class="btn btn-primary" name="addbtn" id="addbtn" value="Add Account">
            </form>
          </div>
        </div>
      </div>
    </div>

<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Update Account</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form method="post" action="../includes/admin_userupdate.inc.php" enctype="multipart/form-data">
                <input type="text" name="updateusername" id="updateusername" placeholder="Username"> <br>
                <input type="text" name="updatepassword" id="updatepassword" placeholder="Password"> <br>
                <input type="text" name="updateemail" id="updateemail" placeholder="Email"> <br>
      
                  <label for="updatepicture" class="form-label">Select Image:</label>
<input type="file" class="form-control" id="updatepicture" name="updatepicture">
                <input type="hidden" name="userid" id="userid" value="">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <input type="submit" class="btn btn-primary" name="updatebtn" id="updatebtn" value="Update">
            </form>
          </div>
        </div>
      </div>
    </div>

    </section>


    <script type="text/javascript">
       $(document).ready(function() {
    $('#ordertbl').DataTable();
});
</script>  

    <script type="text/javascript">
        $(document).on('click','.update_btn',function(){
            var pid = $(this).attr('value');
            $.ajax({
                url: "../includes/admin_userupdate.inc.php",
                method: "POST",
                data: {id:pid},
                dataType: "json",
                success:function(data){
                    $('#updateusername').val(data.username);
                    $('#updatepassword').val(data.password);
                    $('#updateemail').val(data.email);
                    $('#userid').val(data.user_id);
                    if (data.picture) {
                        $('#updatepicture').val(data.picture);
                    }
                }
                
            });
        });
    </script>

    <script type="text/javascript">
        $(document).on('click','.delete_btn',function(){
            // Set the user_id to the hidden input in the delete modal
            var userId = $(this).attr('value');
            document.getElementById("deleteUserId").value = userId;
        });
    </script>

<script type="text/javascript">
    $(document).on('click', '.add_btn', function() {
        $.ajax({
            url: "../includes/addaccounts.inc.php", 
            method: "POST", 
            dataType: "json",
            success: function(data) {
                // Populate modal fields with data received from the server
                $('#username').val(data.username);
                $('#password').val(data.password);
                $('#email').val(data.email);
                 if (data.picture) {
                        $('#userpicture').val(data.picture);
                    }
            },
           
        });
    });
</script>

    </div>
 <script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    

    <script  src="../javascript/admin.js"></script>
</body>
</html>



