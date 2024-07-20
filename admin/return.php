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
    $order_code = $_POST['order_code'];
    $admin_response = $_POST['admin_response']; // Get the admin response from the form
    
    // Restore product quantities in furniture table
    $restore_qty_query = "UPDATE furniture
                          INNER JOIN orders ON furniture.pid = orders.pid
                          SET furniture.quantity = furniture.quantity + orders.qty
                          WHERE orders.order_code = ?";
    $stmt = $conn->prepare($restore_qty_query);
    $stmt->bind_param("s", $order_code);
    if (!$stmt->execute()) {
        echo "Error updating record: " . $stmt->error;
    } else {
        // Update order status to declined (osid = 5)
        $update_order_query = "UPDATE orders SET osid = '5' WHERE order_code = ?";
        $stmt = $conn->prepare($update_order_query);
        $stmt->bind_param("s", $order_code);
        if (!$stmt->execute()) {
            echo "Error updating order status: " . $stmt->error;
        } else {
            // Update return status to Not Approve and save admin response
            $update_return_query = "UPDATE order_return SET return_status = 'Not Approve', admin_response = ? WHERE order_code = ?";
            $stmt = $conn->prepare($update_return_query);
            $stmt->bind_param("ss", $admin_response, $order_code);
            if (!$stmt->execute()) {
                echo "Error updating return status: " . $stmt->error;
            } else {
                echo "Order declined successfully.";
            }
        }
    }
    $stmt->close();
}

// Handle confirm action
if (isset($_POST['confirm'])) {
    $order_code = $_POST['order_code'];
    $admin_response = $_POST['admin_response']; // Get the admin response from the form
    
    // Update order status to confirmed (osid = 6)
    $update_order_query = "UPDATE orders SET osid = '6' WHERE order_code = ?";
    $stmt = $conn->prepare($update_order_query);
    $stmt->bind_param("s", $order_code);
    if (!$stmt->execute()) {
        echo "Error updating order status: " . $stmt->error;
    } else {
        // Update return status to Approved and save admin response
        $update_return_query = "UPDATE order_return SET return_status = 'Approved', admin_response = ? WHERE order_code = ?";
        $stmt = $conn->prepare($update_return_query);
        $stmt->bind_param("ss", $admin_response, $order_code);
        if (!$stmt->execute()) {
            echo "Error updating return status: " . $stmt->error;
        } else {
            echo "Order confirmed successfully.";
        }
    }
    $stmt->close();
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
                    <h3 class="fw-bold mb-3">Return & Refund Item</h3>
                </div>
                <div class="ms-md-auto py-2 py-md-0"></div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List of Return & Refund Item</h4>
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
                                        <th>Total</th>
                                        <th>Reason</th>
                                        <th>Description</th>
                                        <th>Image of Product</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $orders_query = "SELECT orders.order_code, CONCAT(userinfo.firstname, ' ', userinfo.lastname) AS customer_name, furniture.pname AS product_name,
                                                        orders.qty AS quantity, orders.total AS total_amount, orders.mop, orders.date AS order_date, gcash_rec.receipt AS gcash_receipt, order_return.reason AS return_reason, order_return.description AS return_description,
                                                        order_return.img AS img, order_return.return_status AS return_status
                                                    FROM 
                                                        orders 
                                                    LEFT JOIN gcash_rec ON orders.order_code = gcash_rec.order_code 
                                                    LEFT JOIN order_return ON orders.order_code = order_return.order_code
                                                    LEFT JOIN userinfo ON orders.uid = userinfo.infoid 
                                                    LEFT JOIN furniture ON orders.pid = furniture.pid 
                                                    WHERE 
                                                        orders.osid = 4";
                                    $order_res = $conn->query($orders_query);

                                    if ($order_res) {
                                        if ($order_res->num_rows > 0) {
                                            while ($order_row = $order_res->fetch_assoc()) {
                                                $order_code = htmlspecialchars($order_row['order_code']);
                                                $customer_name = htmlspecialchars($order_row['customer_name']);
                                                $product_names = htmlspecialchars($order_row['product_name']);
                                                $total_amount = htmlspecialchars($order_row['total_amount']);
                                                $return_reason = htmlspecialchars($order_row['return_reason']);
                                                $return_description = htmlspecialchars($order_row['return_description']);
                                                $return_img = htmlspecialchars($order_row['img']);
                                    ?>
                                    <tr>
                                        <td><?php echo $order_code; ?></td>
                                        <td><?php echo $customer_name; ?></td>
                                        <td><?php echo $product_names; ?></td>
                                        <td><?php echo $total_amount; ?></td>
                                        <td><?php echo $return_reason; ?></td>
                                        <td><?php echo $return_description; ?></td>
                                        <td><img src="../customer/return/<?php echo $return_img; ?>" alt="Product Image" style="max-width: 100px;"></td>
                                        <td>
                                            <div class="form-button-action">
                                                <form action="view_order.php" method="GET" style="display: inline;">
                                                    <input type="hidden" name="order_code" value="<?php echo $order_code; ?>">
                                                    <button type="submit" data-bs-toggle="tooltip" title="View" class="btn btn-link btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-link btn-primary" onclick="confirmOrder('<?php echo $order_code; ?>')">
                                                    <i class="fas fa-check-square"></i>
                                                </button>
                                                <button type="button" class="btn btn-link btn-danger" onclick="declineOrder('<?php echo $order_code; ?>')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                            }
                                        } else {
                                            echo "<tr><td colspan='8'>No data available in table</td></tr>";
                                        }
                                    } else {
                                        echo "Error: " . $conn->error;
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
    
<!-- Modal for Confirm Reason -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirm Reason</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="confirmForm" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="adminConfirmResponse" class="form-label">Reason for Confirm</label>
                        <textarea class="form-control" id="adminConfirmResponse" name="admin_response" required></textarea>
                    </div>
                    <input type="hidden" id="confirmOrderCode" name="order_code">
                    <input type="hidden" name="confirm" value="1">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for Decline Reason -->
<div class="modal fade" id="declineModal" tabindex="-1" aria-labelledby="declineModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="declineModalLabel">Decline Reason</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="declineForm" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="adminDeclineResponse" class="form-label">Reason for Decline</label>
                        <textarea class="form-control" id="adminDeclineResponse" name="admin_response" required></textarea>
                    </div>
                    <input type="hidden" id="declineOrderCode" name="order_code">
                    <input type="hidden" name="decline" value="1">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Decline</button>
                </div>
            </form>
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

    function confirmOrder(orderCode) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to confirm this Refund/Return order?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, confirm it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Show the modal for confirm reason
                $('#confirmOrderCode').val(orderCode);
                $('#confirmModal').modal('show');
            }
        });
    }

    function declineOrder(orderCode) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to decline this Refund/Return order?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, decline it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Show the modal for decline reason
                $('#declineOrderCode').val(orderCode);
                $('#declineModal').modal('show');
            }
        });
    }

    // Handle confirm form submission
    $('#confirmForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: '', // The same page or separate PHP file to handle confirm
            data: $(this).serialize(),
            success: function(response) {
                Swal.fire({
                    title: 'Confirmed!',
                    text: 'The order has been confirmed.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Error!',
                    text: 'There was an error confirming the order.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Handle decline form submission
    $('#declineForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: '', // The same page or separate PHP file to handle decline
            data: $(this).serialize(),
            success: function(response) {
                Swal.fire({
                    title: 'Declined!',
                    text: 'The order has been declined.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Error!',
                    text: 'There was an error declining the order.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
</script>

</html>
