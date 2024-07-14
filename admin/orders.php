<?php
session_start();
include('../conn.php'); // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    header("Location: ../login.php");
    exit();
}

// Handle decline action
if (isset($_POST['decline'])) {
    $order_id = $_POST['decline'];
    $update_query = "UPDATE orders SET osid = '0' WHERE order_id = '$order_id'";
    mysqli_query($conn, $update_query);
}

// Handle confirm action
if (isset($_POST['confirm'])) {
    $order_id = $_POST['confirm'];
    $update_query = "UPDATE orders SET osid = '2' WHERE order_id = '$order_id'";
    mysqli_query($conn, $update_query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('includes/topbar.php'); ?>
    <title>Registered User</title>
    <!-- Include your CSS and other head content here -->
</head>
<body>
    <?php include('includes/sidebar.php'); ?>

    <!-- Header -->
    <?php include('includes/header.php'); ?>

    <!-- Main Content -->
    <div class="container">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Orders</h3>
                </div>
                <div class="ms-md-auto py-2 py-md-0"></div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Orders Table</h4>
                            
                        </div>
                    </div>
                    <div class="card-body">
                      

                        <div class="table-responsive">
                            <table id="order-table" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Order Code</th>
                                        <th>Image</th>
                                        <th>Customer Name</th>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                        <th>Mode of Payment</th>
                                        <th>Date of Order</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $user_id = $_SESSION['uid'];
                                    $orders_query = "SELECT * 
                                                    FROM orders 
                                                    JOIN furniture ON orders.pid = furniture.pid 
                                                    JOIN userinfo ON orders.uid = userinfo.infoid
                                                    WHERE orders.osid = '1' AND orders.uid ='$user_id'";
                                    $order_res = mysqli_query($conn, $orders_query);

                                    if ($order_res && mysqli_num_rows($order_res) > 0) {
                                        while ($order_row = mysqli_fetch_assoc($order_res)) {
                                            $order_code = $order_row['order_code'];
                                            $order_id = $order_row['order_id'];
                                            $image = $order_row['image'];
                                            $customer_name = $order_row['firstname'] . ' ' . $order_row['lastname'];
                                            $product_name = $order_row['pname'];
                                            $quantity = $order_row['qty'];
                                            $total = $order_row['total']; 
                                            $mop = $order_row['mop'];
                                            $date = $order_row['date']; 
                                    ?>
                                    <tr>
                                        <td><?php echo $order_code; ?></td>
                                        <td><img src="assets/img/<?php echo $image; ?>" alt="Order Image" style="max-width: 100px;"></td>
                                        <td><?php echo $customer_name; ?></td>
                                        <td><?php echo $product_name; ?></td>
                                        <td><?php echo $quantity; ?></td>
                                        <td><?php echo $total; ?></td>
                                        <td><?php echo $mop; ?></td>
                                        <td><?php echo $date; ?></td>
                                        <td>
                                            <div class="form-button-action">
                                                <form action="" method="POST" style="display: inline;">
                                                    <input type="hidden" name="confirm" value="<?php echo $order_id; ?>">
                                                    <button type="submit" data-bs-toggle="tooltip" title="Confirm" class="btn btn-link btn-primary">
                                                        <i class="fas fa-check-square"></i>
                                                    </button>
                                                </form>
                                                <form action="" method="POST" style="display: inline;">
                                                    <input type="hidden" name="decline" value="<?php echo $order_id; ?>">
                                                    <button type="submit" data-bs-toggle="tooltip" title="Decline" class="btn btn-link btn-danger">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <!-- <?php include('includes/footer.php'); ?> -->

    <!-- Scripts -->
    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

    <!-- Chart JS -->
    <script src="assets/js/plugin/chart.js/chart.min.js"></script>

    <!-- jQuery Sparkline -->
    <script src="assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Chart Circle -->
    <script src="assets/js/plugin/chart-circle/circles.min.js"></script>

    <!-- Datatables -->
    <script src="assets/js/plugin/datatables/datatables.min.js"></script>

    <!-- Sweet Alert -->
    <script src="admin/assets/js/plugin/sweetalert/sweetalert.min.js"></script>

    <!-- Kaiadmin JS -->
    <script src="assets/js/kaiadmin.min.js"></script>

    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="assets/js/setting-demo.js"></script>
    <script src="assets/js/demo.js"></script>

    <script>
        $(document).ready(function () {
            $("#order-table").DataTable({
                pageLength: 5,
            });

            var action = '<button type="button" data-bs-toggle="tooltip" title="Edit Task" class="btn btn-link btn-primary btn-lg"><i class="fa fa-edit"></i></button> ' +
                         '<button type="button" data-bs-toggle="tooltip" title="Remove" class="btn btn-link btn-danger btn-lg"><i class="fa fa-times"></i></button>';

            $("#addRowButton").click(function () {
                $("#order-table").dataTable().fnAddData([
                    $("#addName").val(),
                    $("#addPosition").val(),
                    $("#addOffice").val(),
                    action,
                ]);
                $("#addRowModal").modal("hide");
            });
        });
    </script>
</body>
</html>
