<?php 
session_start();
include('../conn.php'); // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    header("Location: ../login.php");
    exit();
}

// Get the order code from the URL
if (isset($_GET['order_code'])) {
    $order_code = $_GET['order_code'];

    // Fetch order details
    $order_query = "SELECT furniture.image, furniture.pname, orders.qty, orders.total 
                    FROM orders 
                    JOIN furniture ON orders.pid = furniture.pid 
                    WHERE orders.order_code = '$order_code'";
    $order_res = mysqli_query($conn, $order_query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('includes/topbar.php'); ?>
    <title>View Order</title>
    <!-- Include your CSS and other head content here -->
    <link rel="stylesheet" href="assets/css/styles.css"> <!-- Add your custom styles here -->
</head>
<body>
    <?php include('includes/sidebar.php')?>

    <!-- Header -->
    <?php include('includes/header.php'); ?>

    <!-- Main Content -->
    <div class="container">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Order Details</h3>
                </div>
                <div class="ms-md-auto py-2 py-md-0"></div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Order Code: <?php echo htmlspecialchars($order_code); ?></h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php
                            if ($order_res && mysqli_num_rows($order_res) > 0) {
                                while ($order_row = mysqli_fetch_assoc($order_res)) {
                                    $image = $order_row['image'];
                                    $product_name = $order_row['pname'];
                                    $quantity = $order_row['qty'];
                                    
                                    $total = $order_row['total'];
                            ?>
                            <div class="col-md-4">
                                <div class="card">
                                    <img src="assets/img/<?php echo htmlspecialchars($image); ?>" class="card-img-top" alt="Product Image" style="max-width: 100%; height: auto;">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($product_name); ?></h5>
                                        <p class="card-text">Quantity: <?php echo htmlspecialchars($quantity); ?></p>
                                       
                                        <p class="card-text">Total: <?php echo htmlspecialchars($total); ?></p>
                                    </div>
                                </div>
                            </div>
                            <?php
                                }
                            } else {
                                echo "<div class='col-12'><p>No order details found.</p></div>";
                            }
                            ?>
                        </div>
                        <div class="form-button-action mt-4">
                            <a href="orders.php" class="btn btn-secondary">Back to Orders</a>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
