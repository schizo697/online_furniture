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
    <?php include('includes/sidebar.php')?>

    <!-- Header -->
    <?php include('includes/header.php'); ?>

    <!-- Main Content -->
    <div class="container">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Customer Review & Rating</h3>
                </div>
                <div class="ms-md-auto py-2 py-md-0"></div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Modal -->
                        <div class="table-responsive">
                            <table id="add-row" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Product Name</th>
                                        <th>Rating</th>
                                        <th>Review</th>
                                        <th>Customer Name</th>
                                        <!-- <th>Total Quantity</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    $sql = "SELECT *, CONCAT(firstname, ' ', lastname) AS fullname FROM furniture 
                                            JOIN furniture_type ON furniture.fid = furniture_type.fid 
                                            LEFT JOIN orders ON orders.pid = furniture.pid  AND orders.osid = 3
                                            JOIN product_rating ON product_rating.pid = orders.pid
                                            JOIN useraccount ON useraccount.uid = product_rating.uid
                                            JOIN userinfo ON userinfo.infoid = useraccount.infoid
                                            WHERE furniture.status = 'Active'";
                                    $result = mysqli_query($conn, $sql);

                                    if ($result && mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $pid = $row['pid'];
                                            $image = $row['image'];
                                            $pname = $row['pname'];
                                            $quantity = $row['quantity'];
                                            $sold = $row['qty'];
                                            $total = $quantity + $sold;
                                            $rating = $row['rating'];
                                            $review = $row['review'];
                                            $name = $row['fullname'];
                                ?>
                                    <tr>
                                        <td><img src="assets/img/<?php echo $row['image']; ?>" alt="Product Image" style="max-width: 100px;"></td> 
                                        <td><?php echo $pname ?></td>
                                        <td><?php echo $rating ?> ★</td>
                                        <td><?php echo $review ?></td>
                                        <td><?php echo $name ?></td>
                                    </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "No records found";
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
            $("#add-row").DataTable({
                pageLength: 5,
            });

            // Image preview functionality
            $("#addImage").change(function() {
                readURL(this);
            });

            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#imagePreview').attr('src', e.target.result);
                        $('#imagePreview').show();
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }

            var action = 
                '<td><div class="form-button-action">' +
                '<button type="button" data-bs-toggle="tooltip" title="Edit Task" class="btn btn-link btn-primary btn-lg">' +
                '<i class="fa fa-edit"></i></button> ' +
                '<button type="button" data-bs-toggle="tooltip" title="Remove" class="btn btn-link btn-danger">' +
                '<i class="fa fa-times"></i></button></div></td>';

            $("#addRowButton").click(function () {
                var id = $("#addID").val();
                var image = $("#imagePreview").attr('src');
                var productName = $("#addProductName").val();
                var description = $("#addDescription").val();
                var quantity = $("#addQuantity").val();
                var price = $("#addPrice").val();
                var status = $("#addStatus").val();

                $("#add-row").dataTable().fnAddData([
                    id,
                    '<img src="' + image + '" alt="Product Image" style="max-width: 100px; height: auto;" />',
                    productName,
                    description,
                    quantity,
                    price,
                    status,
                    action,
                ]);
                $("#addRowModal").modal("hide");
            });
        });
    </script>
</body>
</html>
