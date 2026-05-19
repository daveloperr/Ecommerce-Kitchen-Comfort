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
    <title>Admin - Order Progress</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css" />

    <!-- Boxicons CSS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="admin_style.css">

</head>

<body>
    <?php require("navbar.php") ?>

  
    <section class="home" style="background-color: #f8f9fa;" > 
            <div class="toggle-sidebar" style="background-color:  #1e1e1e;">
                <i class='bx bx-menu' style="color:  white;"></i>
                <div class="text" style="color:  white;">Admin - Order Progress</div>
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
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Order ID</th>
                                            <th>Status</th> <!-- New column for status -->
                                            <th>Order Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        require '../includes/dbhandler.inc.php';
$sql = "SELECT o.user_id, u.username, o.name, o.order_id, o.address, o.email, SUM(c.qty * p.price) AS total_price,
            CASE
                WHEN o.isOrderAccepted = 1 THEN 'Accepted'
                WHEN o.isOrderDeclined = 1 THEN 'Declined'
                ELSE 'Pending'
            END AS status, o.order_date
            FROM orders o 
            JOIN cart c ON o.order_id = c.order_id 
            JOIN products1 p ON c.product_name = p.product_name
            JOIN user u ON o.user_id = u.user_id
            GROUP BY o.order_id";


                                        $stmt = mysqli_stmt_init($conn);
                                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                                            header("location: ../order_progress.php?error=sqlerror");
                                        } else {
                                            mysqli_stmt_execute($stmt);
                                            $result = mysqli_stmt_get_result($stmt);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                                <tr>
                                                    <td><?php echo $row['username'] ?></td>
                                                    <td><?php echo $row['order_id'] ?></td>

                                                    <td>
                                                        <?php
                                                        $status = $row['status'];
                                                        $buttonClass = '';
                                                        switch ($status) {
                                                            case 'Accepted':
                                                                $buttonClass = 'btn-success';
                                                                break;
                                                            case 'Declined':
                                                                $buttonClass = 'btn-danger';
                                                                break;
                                                            case 'Pending':
                                                                $buttonClass = 'btn-warning';
                                                                break;
                                                            default:
                                                                $buttonClass = 'btn-secondary';
                                                                break;
                                                        }
                                                        ?>
                                                        <button type="button" class="btn <?php echo $buttonClass; ?> btn-sm" disabled><?php echo $status; ?></button>
                                                    </td>
                                                    <td><?php echo $row['order_date'] ?></td>
                                                    <td>
                                                    <div style="display: flex; gap: 10px;"> <!-- 'gap' controls spacing between buttons -->
                                                        <button type="button" class="btn btn-primary view-btn" data-bs-toggle="modal" data-bs-target="#viewProductsModal" data-orderid="<?php echo $row['order_id'] ?>">View</button>

                                                        <button type="button" class="btn btn-outline-success accept_btn" data-bs-toggle="modal" data-bs-target="#acceptModal" data-orderid="<?php echo $row['order_id'] ?>">Accept</button>

                                                        <button type="button" class="btn btn-outline-danger decline_btn" data-bs-toggle="modal" data-bs-target="#declineModal" data-orderid="<?php echo $row['order_id'] ?>">Decline</button>
                                                    </div>
                                                    </td>
                                                </tr>
                                        <?php }
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

    <!-- Accept Order Modal -->
    <div class="modal fade" id="acceptModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Accept Order</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to accept this order?
                    <form action="../includes/accept_order.inc.php" method="post">
                        <input type="hidden" name="accept_order_id" id="accept_order_id" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <input type="submit" class="btn btn-primary" name="accept_order_btn" id="accept_order_btn" value="Yes">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Decline Order Modal -->
    <div class="modal fade" id="declineModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Decline Order</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to decline this order?
                    <form action="../includes/decline_order.inc.php" method="post">
                        <input type="hidden" name="decline_order_id" id="decline_order_id" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <input type="submit" class="btn btn-primary" name="decline_order_btn" id="decline_order_btn" value="Yes">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- View Products Modal -->
    <div class="modal fade" id="viewProductsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">View Products</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="viewProductsBody">
                    <!-- Product details will be loaded here using AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>

    <!-- Custom JavaScript -->
    <script src="../javascript/admin.js"></script>

    <script type="text/javascript">
       $(document).ready(function() {
    $('#ordertbl').DataTable();
});

$(document).on('click', '.view-btn', function() {
    var orderId = $(this).data('orderid');
    $.ajax({
        url: '../includes/get_products_by_order_id.inc.php',
        type: 'POST',
        data: {
            order_id: orderId
        },
        success: function(response) {
            $('#viewProductsBody').html(response);
            $('#viewProductsModal').modal('show');
        }
    });
});

$(document).on('click', '.accept_btn', function() {
    var orderId = $(this).data('orderid');
    var orderStatus = $(this).closest('tr').find('.status').text().trim(); // Get the status of the order

    // Check if the order is already accepted
    if (orderStatus === 'Accepted') {
        alert('This order is already accepted.');
    } else {
        $('#accept_order_id').val(orderId);
    }
});

$(document).on('click', '.decline_btn', function() {
    var orderId = $(this).data('orderid');
    $('#decline_order_id').val(orderId);
});

    </script>
</body>

</html>
