<?php 
session_start();
include('../conn.php'); // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    header("Location: ../login.php");
    exit();
}

// Get the order code from the URL
if (isset($_GET['pid'])) {
    $pid = $_GET['pid'];

    // Fetch order details
    $order_query = "SELECT * FROM furniture WHERE pid='$pid'";
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
                            <h4 class="card-title">Furniture: <?php echo htmlspecialchars($pid); ?></h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php
                            if ($order_res && mysqli_num_rows($order_res) > 0) {
                                while ($pid_row = mysqli_fetch_assoc($order_res)) {
                                    $image = $pid_row['image'];
                                    $product_name = $pid_row['pname'];
                                    $quantity = $pid_row['quantity'];
                                    $description = $pid_row['description'];
                                    $price = $pid_row['price'];
                                    $color = $pid_row['color'];
                                    $height = $pid_row['height'];
                                    $width = $pid_row['width'];
                                    $length = $pid_row['length'];
                                    $date = $pid_row['date_added'];
                                   
                            ?>
                            <div class="col-md-4">
                                <div class="card">
                                    <img src="assets/img/<?php echo htmlspecialchars($image); ?>" class="card-img-top" alt="Product Image" style="max-width: 100%; height: auto;">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($product_name); ?></h5>
                                        <p class="card-text">Quantity: <?php echo htmlspecialchars($quantity); ?></p>
                                        <p class="card-text">Price: <?php echo htmlspecialchars($price); ?></p>
                                        <p class="card-text">Color: <?php echo htmlspecialchars($color); ?></p>
                                        <p class="card-text">Height: <?php echo htmlspecialchars($height); ?></p>
                                        <p class="card-text">Width: <?php echo htmlspecialchars($width); ?></p>
                                        <p class="card-text">Length: <?php echo htmlspecialchars($length); ?></p>
                                        <p class="card-text">Description: <?php echo htmlspecialchars($description); ?></p>
                                        <p class="card-text">Date Added: <?php echo htmlspecialchars($date); ?></p>
                                        
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
                            <a href="products_listing.php" class="btn btn-secondary">Back to Products</a>
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
