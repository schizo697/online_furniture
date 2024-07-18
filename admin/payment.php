<?php
session_start();
include('../conn.php'); // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    header("Location: ../login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('includes/topbar.php'); ?>
</head>
<body>
    <?php include('includes/sidebar.php'); ?>
    <?php include('includes/header.php'); ?>

    <div class="container">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Payment</h3>
                </div>
                <div class="ms-md-auto py-2 py-md-0"></div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List of Payments</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="order-table" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Customer Name</th>
                                        <th>Product Names</th>
                                        <th>Total</th>
                                        <th>Mode of Payment</th>
                                        <th>GCash Receipt</th> 
                                        <th>Date of Payment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    $uid = mysqli_real_escape_string($conn, $_SESSION['uid']);
                                    $orders_query = "SELECT orders.order_code, 
                                                            CONCAT(userinfo.firstname, ' ', userinfo.lastname) AS customer_name, 
                                                            GROUP_CONCAT(furniture.pname SEPARATOR ', ') AS product_names,
                                                            SUM(orders.qty) AS total_quantity, 
                                                            SUM(orders.total) AS total_amount, 
                                                            orders.mop, 
                                                            orders.date AS order_date,
                                                            gcash_rec.receipt AS gcash_receipt -- Include GCash receipt path
                                                    FROM orders 
                                                    LEFT JOIN gcash_rec ON orders.order_code = gcash_rec.order_code 
                                                    LEFT JOIN userinfo ON orders.uid = userinfo.infoid 
                                                    LEFT JOIN furniture ON orders.pid = furniture.pid 
                                                    WHERE orders.osid = 1 
                                                    GROUP BY orders.order_code";
                                    $order_res = mysqli_query($conn, $orders_query);

                                    if ($order_res) {
                                        if (mysqli_num_rows($order_res) > 0) {
                                            while ($order_row = mysqli_fetch_assoc($order_res)) {
                                                $order_code = htmlspecialchars($order_row['order_code']);
                                                $customer_name = htmlspecialchars($order_row['customer_name']);
                                                $product_names = htmlspecialchars($order_row['product_names']);
                                                $total_quantity = htmlspecialchars($order_row['total_quantity']);
                                                $total_amount = htmlspecialchars($order_row['total_amount']);
                                                $mop = htmlspecialchars($order_row['mop']);
                                                $date = htmlspecialchars($order_row['order_date']);
                                                $gcash_receipt = isset($order_row['gcash_receipt']) ? htmlspecialchars($order_row['gcash_receipt']) : ''; 

                                ?>
                                    <tr>                                     
                                        <td><?php echo $customer_name; ?></td>
                                        <td><?php echo $product_names; ?></td>
                                        <td><?php echo $total_amount; ?></td>
                                        <td><?php echo $mop; ?></td>
                                        <td>
                                            <?php if ($mop == 'gcash' && !empty($gcash_receipt)) : ?>
                                                <a href="../customer/gcash/<?php echo $gcash_receipt; ?>" target="_blank">View Receipt</a>
                                            <?php endif; ?>
                                        </td>
                                        <td>-- </td>
                                        
                                    </tr>
                                <?php
                                            }
                                        } else {
                                            echo "<tr><td colspan='9'>No data available in table</td></tr>";
                                        }
                                    } else {
                                        echo "Error: " . mysqli_error($conn);
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

    <?php include('includes/footer.php'); ?>
    <?php include('includes/tables.php'); ?>
</body>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
<script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>

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
    });
</script>

</html>
