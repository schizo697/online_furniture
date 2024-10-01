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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php include('includes/sidebar.php'); ?>
    <?php include('includes/header.php'); ?>

    <div class="container">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Shipping Status</h3>
                </div>
                <div class="ms-md-auto py-2 py-md-0"></div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List of Shipping</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="order-table" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Order Code</th>
                                        <th>Customer Name</th>
                                        <th>Product Names</th>
                                        <th>Total Quantity</th>
                                        <th>Total</th>
                                        <th>Mode of Payment</th>
                                        <th>GCash Receipt</th>
                                        <th>Date of Order</th>
                                        <th>Rider</th>
                                        <th>Action</th>
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
                                                        gcash_rec.receipt AS gcash_receipt, -- Include GCash receipt path
                                                        shipping.rider_name, 
                                                        shipping.rider_number 
                                                    FROM orders 
                                                    LEFT JOIN gcash_rec ON orders.order_code = gcash_rec.order_code 
                                                    LEFT JOIN userinfo ON orders.uid = userinfo.infoid 
                                                    LEFT JOIN furniture ON orders.pid = furniture.pid 
                                                    LEFT JOIN shipping ON orders.order_code = shipping.order_code 
                                                    WHERE orders.osid = 7 
                                                    GROUP BY orders.order_code, shipping.rider_name, shipping.rider_number ";
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
                                                $rider_name = htmlspecialchars($order_row['rider_name']);
                                                $gcash_receipt = isset($order_row['gcash_receipt']) ? htmlspecialchars($order_row['gcash_receipt']) : '';
                                                ?>
                                                <tr>
                                                    <td><?php echo $order_code; ?></td>
                                                    <td><?php echo $customer_name; ?></td>
                                                    <td><?php echo $product_names; ?></td>
                                                    <td><?php echo $total_quantity; ?></td>
                                                    <td><?php echo $total_amount; ?></td>
                                                    <td><?php echo $mop; ?></td>
                                                    <td>
                                                        <?php if ($mop == 'gcash' && !empty($gcash_receipt)): ?>
                                                            <a href="../customer/gcash/<?php echo $gcash_receipt; ?>"
                                                                target="_blank">View Receipt</a>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo $date; ?></td>
                                                    <td><?php echo $rider_name;?></td>
                                                    <td>
                                                        <button type="button" class="btn btn-link btn-primary btn-view"
                                                            data-order-code="<?php echo $order_code; ?>">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <!-- <button type="button" class="btn btn-link btn-primary btn-edit"
                                                            data-order-code="<?php echo $order_code; ?>"
                                                            data-current-date="<?php echo $date; ?>">
                                                            <i class="fas fa-pen"></i>
                                                        </button> -->
                                                        <button class="btn btn-primary btn-edit"
                                                            data-order-code="<?php echo $order_code; ?>"
                                                            data-current-date="<?php echo $date; ?>">Put Status</button>
                                                    </td>
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
    <!-- View Shipping Status Modal -->
    <div class="modal fade" id="viewShippingModal" tabindex="-1" role="dialog" aria-labelledby="viewShippingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewShippingModalLabel">View Shipping Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Order Code:</strong> <span id="viewOrderCode"></span></p>
                    <p><strong>Expected Date:</strong> <span id="viewExpectedDate"></span></p>
                    <p><strong>Rider Name:</strong> <span id="viewRider"></span></p>
                    <p><strong>Rider Number:</strong> <span id="viewRiderNumber"></span></p>
                    <p><strong>Shipping Status:</strong> <span id="viewShippingStatus"></span></p>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Put Shipping Status Modal -->
    <div class="modal fade" id="editShippingModal" tabindex="-1" role="dialog" aria-labelledby="editShippingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editShippingModalLabel">Put Shipping Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editShippingForm">
                        <div class="form-group">
                            <label for="expectedDate">Expected Date</label>
                            <input type="date" class="form-control" id="expectedDate" name="expectedDate">
                        </div>
                        <div class="form-group">
                            <label for="shippingStatus">Shipping Status</label>
                            <select class="form-control" id="shippingStatus" name="shippingStatus">
                                <option value="delayed">Delayed</option>
                                <option value="shipped">Shipped</option>
                                <option value="delivered">Delivered</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="riderName">Rider Name</label>
                            <input type="text" class="form-control" id="riderName" name="riderName">
                        </div>
                        <div class="form-group">
                            <label for="riderNumber">Rider Number</label>
                            <input type="number" class="form-control" id="riderNumber" name="riderNumber">
                        </div>
                        <input type="hidden" id="orderCode" name="orderCode">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveChangesBtn">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <?php include('includes/footer.php'); ?>
    <?php include('includes/tables.php'); ?>

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
            // Initialize DataTable
            $("#order-table").DataTable({
                pageLength: 5,
            });

            // Handle Edit button click
            $(document).on('click', '.btn-edit', function () {
                var orderCode = $(this).data('order-code');
                var currentDate = $(this).data('current-date');

                // Populate the modal fields
                $('#orderCode').val(orderCode);
                $('#expectedDate').val(currentDate);

                // Show the modal
                $('#editShippingModal').modal('show');
            });

            // Handle Save changes button click
            $('#saveChangesBtn').click(function () {
                var form = $('#editShippingForm');
                var formData = form.serialize();

                $.ajax({
                    url: 'update_shipping_status.php', // PHP script to handle the update
                    type: 'POST',
                    data: formData,
                    success: function (response) {
                        var result = JSON.parse(response);
                        if (result.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: result.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(function () {
                                location.reload(); // Reload the page to reflect changes
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: result.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to update shipping status.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            // Handle View button click
            $(document).on('click', '.btn-view', function () {
                var orderCode = $(this).data('order-code');

                $.ajax({
                    url: 'get_shipping_status.php', // PHP script to fetch the shipping status
                    type: 'POST',
                    data: { orderCode: orderCode },
                    success: function (response) {
                        var result = JSON.parse(response);
                        if (result.success) {
                            // Populate the view modal fields
                            $('#viewOrderCode').text(result.data.order_code);
                            $('#viewExpectedDate').text(result.data.expected_date);
                            $('#viewRider').text(result.data.rider_name);
                            $('#viewRiderNumber').text(result.data.rider_number);
                            $('#viewShippingStatus').text(result.data.shipping_status);

                            // Show the view modal
                            $('#viewShippingModal').modal('show');
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: result.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to fetch shipping status.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>