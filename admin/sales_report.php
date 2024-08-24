<?php
session_start();
include('../conn.php'); // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    header("Location: ../login.php");
    exit();
}

// Function to fetch sales records with optional date filter
function fetchSalesRecords($conn, $month = null, $year = null)
{
    $dateCondition = '';
    if ($month && $year) {
        $dateCondition = "AND DATE_FORMAT(orders.date, '%Y-%m') = '$year-$month'";
    }

    $query = " SELECT furniture.pid, furniture.pname, furniture.price, GROUP_CONCAT(furniture.image) AS images, 
            SUM(orders.qty) AS total_quantity, SUM(furniture.price * orders.qty) AS total_price 
            FROM furniture 
            JOIN orders ON furniture.pid = orders.pid 
            WHERE orders.osid = 3 $dateCondition 
            GROUP BY furniture.pid, furniture.pname, furniture.price";

    return mysqli_query($conn, $query);
}

// Handle form submission
$month = isset($_POST['month']) ? $_POST['month'] : null;
$year = isset($_POST['year']) ? $_POST['year'] : null;

$order_res = fetchSalesRecords($conn, $month, $year);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <?php include('includes/topbar.php'); ?>
    <link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css">
</head>

<body>
    <?php include('includes/sidebar.php'); ?>
    <?php include('includes/header.php'); ?>

    <!-- Main Content -->
    <div class="container">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Sales Report</h3>
                </div>
                <div class="ms-md-auto py-2 py-md-0"></div>
            </div>

            <!-- Print Button -->
            <div class="col-md-12 mb-3">
                <button class="btn btn-secondary" onclick="printTable()">Print Report</button>
            </div>

            <!-- Filter Form -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="month">Month:</label>
                                    <select id="month" name="month" class="form-control">
                                        <option value="">Select Month</option>
                                        <?php for ($i = 1; $i <= 12; $i++): ?>
                                            <option value="<?php echo sprintf('%02d', $i); ?>" <?php echo $month == sprintf('%02d', $i) ? 'selected' : ''; ?>>
                                                <?php echo date('F', mktime(0, 0, 0, $i, 1)); ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="year">Year:</label>
                                    <select id="year" name="year" class="form-control">
                                        <option value="">Select Year</option>
                                        <?php
                                        $currentYear = date('Y');
                                        for ($i = $currentYear; $i >= $currentYear - 5; $i--): ?>
                                            <option value="<?php echo $i; ?>" <?php echo $year == $i ? 'selected' : ''; ?>>
                                                <?php echo $i; ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sales Report Table -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Sales Report Table</h4>
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
                                                <td><?php echo htmlspecialchars($pname); ?></td>
                                                <td>
                                                    <img src="assets/img/<?php echo htmlspecialchars($images[0]); ?>"
                                                        alt="Product Image" style="max-width: 100px;">
                                                </td>
                                                <td><?php echo htmlspecialchars($total_quantity); ?></td>
                                                <td><?php echo htmlspecialchars($total_price); ?></td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='4'>No data available in table</td></tr>";
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
            });
        });

        function printTable() {
            // Get the table's HTML
            var table = document.getElementById('order-table').outerHTML;

            // Create a new window
            var newWindow = window.open('', '', 'height=600,width=800');

            // Write the table into the new window
            newWindow.document.write('<html><head><title>Print Sales Report</title>');
            newWindow.document.write('<style>');
            newWindow.document.write('table {width: 100%; border-collapse: collapse;}');
            newWindow.document.write('table, th, td {border: 1px solid black; padding: 8px; text-align: left;}');
            newWindow.document.write('</style>');
            newWindow.document.write('</head><body>');
            newWindow.document.write(table);
            newWindow.document.write('</body></html>');

            // Print the content of the new window
            newWindow.document.close();
            newWindow.print();

            // Close the new window after printing
            newWindow.close();
        }
    </script>
</body>

</html>
