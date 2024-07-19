<?php
session_start();
include('../conn.php'); // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    header("Location: ../login.php");
    exit();
}

// Function to fetch sales records
function fetchSalesRecords($conn) {
    $query = "SELECT furniture.pid, furniture.pname, furniture.price, GROUP_CONCAT(furniture.image) AS images, SUM(orders.qty) AS total_quantity, SUM(furniture.price * orders.qty) AS total_price
              FROM furniture
              JOIN orders ON furniture.pid = orders.pid
              WHERE orders.osid = 3
              GROUP BY furniture.pid, furniture.pname, furniture.price";

    return mysqli_query($conn, $query);
}

$order_res = fetchSalesRecords($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Record</title>
    <!-- Include your CSS files and other meta tags here -->
    <?php include('includes/topbar.php'); ?>
    <link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css">
</head>
<body>
    <!-- Include your sidebar and header navigation -->
    <?php include('includes/sidebar.php'); ?>
    <?php include('includes/header.php'); ?>

    <!-- Main Content -->
    <div class="container">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Sales Record</h3>
                </div>
                <div class="ms-md-auto py-2 py-md-0"></div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Sales Table</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="order-table" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                   
                                        <th>Product Name</th>
                                        <th>Image</th>
                                        <th>Total Quantity Sold</th>
                                        <th>Total Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($order_res && mysqli_num_rows($order_res) > 0) {
                                        while ($order_row = mysqli_fetch_assoc($order_res)) {
                                            $pid = $order_row['pid'];
                                            $pname = $order_row['pname'];
                                            $images = explode(',', $order_row['images']);
                                            $total_quantity = $order_row['total_quantity'];
                                            $total_price = $order_row['total_price'];
                                    ?>
                                    <tr>
                                      
                                        <td>
                                            <img src="assets/img/<?php echo htmlspecialchars($images[0]); ?>" alt="Product Image" style="max-width: 100px;">
                                        </td>
                                        <td><?php echo htmlspecialchars($pname); ?></td>
                                        
                                        <td><?php echo htmlspecialchars($total_quantity); ?></td>
                                        <td><?php echo htmlspecialchars($total_price); ?></td>
                                    </tr>
                                    <?php
                                        }
                                    } else {
                                        // Display a message if no results are found
                                        echo "<tr><td colspan='5'>No data available in table</td></tr>";
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

    <!-- Footer and additional includes -->
    <!-- Include your footer here if needed -->
    
    <!-- Scripts -->
    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <script src="assets/js/plugin/chart.js/chart.min.js"></script>
    <script src="assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>
    <script src="assets/js/plugin/chart-circle/circles.min.js"></script>
    <script src="assets/js/plugin/datatables/datatables.min.js"></script>
    <script src="admin/assets/js/plugin/sweetalert/sweetalert.min.js"></script>
    <script src="assets/js/kaiadmin.min.js"></script>
    <script src="assets/js/setting-demo.js"></script>
    <script src="assets/js/demo.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
    <script src="assets/js/dataTables/dataTables.bootstrap4.min.js"></script>

    <!-- Initialize DataTables -->
    <script>
        $(document).ready(function () {
            $("#order-table").DataTable({
                pageLength: 5,
                // Add any additional configurations here
            });
        });
    </script>
</body>
</html>
