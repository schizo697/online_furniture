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
                    <h3 class="fw-bold mb-3">Supplies</h3>
                </div>
                <div class="ms-md-auto py-2 py-md-0"></div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Supplies List</h4>
                            <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addRowModal">
                                <i class="fa fa-plus"></i> Add Product
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Modal -->
                        <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title">
                                            <span class="fw-mediumbold"> New</span>
                                            <span class="fw-light"> Product </span>
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="small">Create a new product using this form, make sure you fill them all</p>
                                        <form>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group form-group-default">
                                                        <label>Name</label>
                                                        <input id="addProductName" type="text" class="form-control" placeholder="Product Name" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group form-group-default">
                                                        <label>Supplier</label>
                                                        <input id="addDescription" type="text" class="form-control" placeholder="Product Description" />
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="button" id="addRowButton" class="btn btn-primary">Add</button>
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="add-row" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Supplier Name</th>
                                        <th>Quantity</th>
                                        <th style="width: 10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td></td>
                                        <td>Supplier 1</td>
                                        <td>3</td>
                                        <td>
                                            <div class="form-button-action">
                                                <button type="button" data-bs-toggle="tooltip" title="Edit Task" class="btn btn-link btn-primary btn-lg">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button type="button" data-bs-toggle="tooltip" title="Remove" class="btn btn-link btn-danger">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
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
