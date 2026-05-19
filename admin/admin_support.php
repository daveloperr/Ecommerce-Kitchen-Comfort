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
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="admin_style.css">
    <title>Admin - Dashboard</title>
</head>
<body>
    <!-- Your PHP code for session handling and table generation -->
    <?php require("navbar.php"); ?>
    
    <section class="home" style="background-color: #f8f9fa;">
        <div class="toggle-sidebar" style="background-color: #1e1e1e;">
            <i class='bx bx-menu' style="color: white;"></i>
            <div class="text" style="color: white;">Admin - Support Tickets</div>
        </div>
        <br>
        <br>
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-body">
                            <hr>
                            <div class="table-responsive">
                                <table id="ordertbl" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>User ID</th>
                                            <th>Message</th>
                                            <th>Resolve</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Your PHP loop to generate table rows -->
                                        <?php 
                                        require '../includes/dbhandler.inc.php';
                                        $sql = "SELECT * FROM support WHERE isResolved = 0";
                                        $stmt = mysqli_stmt_init($conn);
                                        if(!mysqli_stmt_prepare($stmt, $sql)) {
                                            header("location: ../admin_accounts.php?error=sql");
                                        } else {
                                            mysqli_stmt_execute($stmt);
                                            $result = mysqli_stmt_get_result($stmt);
                                            while($row = mysqli_fetch_assoc($result)) { ?>
                                                <tr>
                                                    <td><?php echo $row['support_no']?></td>
                                                    <td><?php echo $row['user_id']?></td>
                                                    <td><?php echo $row['message']?></td>
                                                    <td>
                                                        <button class="resolve-btn btn btn-success" data-support-id="<?php echo $row['support_no']; ?>">Resolve</button>
                                                    </td>
                                                </tr>
                                            <?php 
                                            }
                                        } ?>
                                    </tbody>
                                </table>
                            </div>   
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
    <script src="../javascript/admin.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#ordertbl').DataTable();
            $('.resolve-btn').click(function() {
                var button = $(this);
                var supportId = button.data('support-id');
                $.ajax({
                    type: 'POST',
                    url: '../includes/resolve_support.inc.php',
                    data: { support_id: supportId },
                    success: function(response) {
                        // Change the button text and disable it
                        button.text('Resolved').prop('disabled', true);
                        // Optionally, you can also change the button's background color
                        button.removeClass('btn-success').addClass('btn-secondary');
                    }
                });
            });
        });
    </script>
</body>
</html>