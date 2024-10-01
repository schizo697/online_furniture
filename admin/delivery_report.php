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
    <?php include('includes/sidebar.php') ?>

    <!-- Header -->
    <?php include('includes/header.php'); ?>

    <!-- Main Content -->
    <div class="container">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Delivery Report</h3>
                </div>
                <div class="ms-md-auto py-2 py-md-0"></div>
            </div>

            <!-- Filter Form -->
            <form method="GET" action="">
                <label for="furniture_type">Filter by Furniture Type:</label>
                <select name="furniture_type" id="furniture_type">
                    <option value="">All Types</option>
                    <?php
                    // Fetch distinct furniture types for the filter
                    $typeQuery = "SELECT DISTINCT type FROM furniture_type";
                    $typeResult = mysqli_query($conn, $typeQuery);
                    while ($typeRow = mysqli_fetch_assoc($typeResult)) {
                        echo "<option value='" . $typeRow['type'] . "'>" . $typeRow['type'] . "</option>";
                    }
                    ?>
                </select>
                <button type="submit" class="btn btn-primary">Apply Filter</button>
            </form>

            <!-- Print Button -->
            <div class="col-md-12 mb-3">
                <button class="btn btn-secondary" onclick="printTable()">Print Report</button>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Table -->
                        <div class="table-responsive">
                            <table id="add-row" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Image</th>
                                        <th>Product Name</th>
                                        <th>Successful Delivery</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Get filter value
                                    $furnitureType = isset($_GET['furniture_type']) ? $_GET['furniture_type'] : '';

                                    // Query to fetch inventory data and calculate total sold
                                    $sql = "SELECT furniture_type.type, furniture.image, furniture.pname, furniture.quantity, SUM(orders.qty) AS total_sold
                                            FROM furniture 
                                            JOIN furniture_type ON furniture.fid = furniture_type.fid 
                                            LEFT JOIN orders ON orders.pid = furniture.pid 
                                            WHERE furniture.status = 'Active' AND orders.osid = 3";

                                    // Apply filter if a furniture type is selected
                                    if (!empty($furnitureType)) {
                                        $sql .= " AND furniture_type.type = '$furnitureType'";
                                    }

                                    $sql .= " GROUP BY furniture_type.type, furniture.pname 
                                              ORDER BY furniture_type.type";

                                    $result = mysqli_query($conn, $sql);

                                    if ($result && mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $type = $row['type'];
                                            $image = $row['image'];
                                            $pname = $row['pname'];
                                            $total_sold = isset($row['total_sold']) ? $row['total_sold'] : 0;
                                            ?>
                                            <tr>
                                                <td><?php echo $type ?></td>
                                                <td><img src="assets/img/<?php echo $image; ?>" alt="Product Image" style="max-width: 100px;"></td>
                                                <td><?php echo $pname ?></td>

                                                <td><?php echo $total_sold ?></td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='5'>No records found</td></tr>";
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

    <!-- Datatables -->
    <script src="assets/js/plugin/datatables/datatables.min.js"></script>

    <script>
        $(document).ready(function () {
            $("#add-row").DataTable({
                pageLength: 5,
            });
        });

        function printTable() {
            var printContents = document.getElementById('add-row').outerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = "<html><head><title>Print Report</title></head><body>" + printContents + "</body></html>";
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
</body>

</html>
