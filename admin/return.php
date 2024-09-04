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
    $order_id = $_POST['order_id'];
    $admin_response = $_POST['admin_response']; // Get the admin response from the form
    
    $conn->begin_transaction(); // Start transaction

    try {
        // Restore product quantities in furniture table
        $restore_qty_query = "UPDATE furniture
                              INNER JOIN orders ON furniture.pid = orders.pid
                              SET furniture.quantity = furniture.quantity + orders.qty
                              WHERE orders.order_id = ?";
        $stmt = $conn->prepare($restore_qty_query);
        $stmt->bind_param("i", $order_id); // Assuming order_id is an integer
        if (!$stmt->execute()) {
            throw new Exception("Error updating product quantities: " . $stmt->error);
        }

        // Update order status to declined (osid = 5)
        $update_order_query = "UPDATE orders SET osid = '5' WHERE order_id = ?";
        $stmt = $conn->prepare($update_order_query);
        $stmt->bind_param("i", $order_id);
        if (!$stmt->execute()) {
            throw new Exception("Error updating order status: " . $stmt->error);
        }

        // Update return status to Not Approve and save admin response
        $update_return_query = "UPDATE order_return SET return_status = 'Not Approve', admin_response = ? WHERE order_id = ?";
        $stmt = $conn->prepare($update_return_query);
        $stmt->bind_param("si", $admin_response, $order_id);
        if (!$stmt->execute()) {
            throw new Exception("Error updating return status: " . $stmt->error);
        }

        $conn->commit(); // Commit transaction
        echo "Order declined successfully.";

    } catch (Exception $e) {
        $conn->rollback(); // Rollback transaction
        echo "Error: " . $e->getMessage();
    }

    $stmt->close();
}

// Handle confirm action
if (isset($_POST['confirm'])) {
    $order_id = $_POST['order_id'];
    $admin_response = $_POST['admin_response']; // Get the admin response from the form

    $conn->begin_transaction(); // Start transaction

    try {
        // Update order status to confirmed (osid = 6)
        $update_order_query = "UPDATE orders SET osid = '6' WHERE order_id = ?";
        $stmt = $conn->prepare($update_order_query);
        $stmt->bind_param("i", $order_id);
        if (!$stmt->execute()) {
            throw new Exception("Error updating order status: " . $stmt->error);
        }

        // Update return status to Approved and save admin response
        $update_return_query = "UPDATE order_return SET return_status = 'Approved', admin_response = ? WHERE order_id = ?";
        $stmt = $conn->prepare($update_return_query);
        $stmt->bind_param("si", $admin_response, $order_id);
        if (!$stmt->execute()) {
            throw new Exception("Error updating return status: " . $stmt->error);
        }

        $conn->commit(); // Commit transaction
        echo "Order confirmed successfully.";

    } catch (Exception $e) {
        $conn->rollback(); // Rollback transaction
        echo "Error: " . $e->getMessage();
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
                                    $orders_query = "SELECT orders.order_id, CONCAT(userinfo.firstname, ' ', userinfo.lastname) AS customer_name, furniture.pname AS product_name,
                                                        orders.qty AS quantity, orders.total AS total_amount, orders.mop, orders.date AS order_date, gcash_rec.receipt AS gcash_receipt, order_return.reason AS return_reason, order_return.description AS return_description,
                                                        order_return.img AS img, order_return.return_status AS return_status
                                                    FROM 
                                                        orders 
                                                    LEFT JOIN gcash_rec ON orders.order_id = gcash_rec.order_code 
                                                    LEFT JOIN order_return ON orders.order_id = order_return.order_id
                                                    LEFT JOIN userinfo ON orders.uid = userinfo.infoid 
                                                    LEFT JOIN furniture ON orders.pid = furniture.pid 
                                                    WHERE 
                                                        orders.osid = 4";
                                    $order_res = $conn->query($orders_query);

                                    if ($order_res) {
                                        if ($order_res->num_rows > 0) {
                                            while ($order_row = $order_res->fetch_assoc()) {
                                                $order_id = htmlspecialchars($order_row['order_id']);
                                                $customer_name = htmlspecialchars($order_row['customer_name']);
                                                $product_names = htmlspecialchars($order_row['product_name']);
                                                $total_amount = htmlspecialchars($order_row['total_amount']);
                                                $return_reason = htmlspecialchars($order_row['return_reason']);
                                                $return_description = htmlspecialchars($order_row['return_description']);
                                                $return_img = htmlspecialchars($order_row['img']);
                                    ?>
                                    <tr>
                                        <td><?php echo $order_id; ?></td>
                                        <td><?php echo $customer_name; ?></td>
                                        <td><?php echo $product_names; ?></td>
                                        <td><?php echo $total_amount; ?></td>
                                        <td><?php echo $return_reason; ?></td>
                                        <td><?php echo $return_description; ?></td>
                                        <td><img src="../customer/return/<?php echo $return_img; ?>" alt="Product Image" style="max-width: 100px;"></td>
                                        <td>
                                            <div class="form-button-action">
                                                <form action="view_order.php" method="GET" style="display: inline;">
                                                    <input type="hidden" name="order_code" value="<?php echo $order_id; ?>">
                                                    <button type="submit" data-bs-toggle="tooltip" title="View" class="btn btn-link btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-link btn-primary" onclick="confirmOrder('<?php echo $order_id; ?>')">
                                                    <i class="fas fa-check-square"></i>
                                                </button>
                                                <button type="button" class="btn btn-link btn-danger" onclick="declineOrder('<?php echo $order_id; ?>')">
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
                    <input type="hidden" id="confirmOrderCode" name="order_id">
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
                    <input type="hidden" id="declineOrderCode" name="order_id">
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

    function confirmOrder(orderId) {
        document.getElementById('confirmOrderCode').value = orderId;
        var confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'), {
            keyboard: false
        });
        confirmModal.show();
    }

    function declineOrder(orderId) {
        document.getElementById('declineOrderCode').value = orderId;
        var declineModal = new bootstrap.Modal(document.getElementById('declineModal'), {
            keyboard: false
        });
        declineModal.show();
    }
</script>

</html>
