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
    $order_code = mysqli_real_escape_string($conn, $_POST['decline']);
    
    // Restore product quantities in furniture table
    $restore_qty_query = "UPDATE furniture
                          INNER JOIN orders ON furniture.pid = orders.pid
                          SET furniture.quantity = furniture.quantity + orders.qty
                          WHERE orders.order_code = '$order_code'";
    if (!mysqli_query($conn, $restore_qty_query)) {
        echo "Error updating record: " . mysqli_error($conn);
    } else {
        // Update order status to declined (osid = 0)
        $update_query = "UPDATE orders SET osid = '0' WHERE order_code = '$order_code'";

        // Get customer id
        $customer = "SELECT * FROM orders WHERE order_code = '$order_code'";
        $fetch = mysqli_query($conn, $customer);
        if ($fetch && mysqli_num_rows($fetch) > 0) {
            $row = mysqli_fetch_assoc($fetch);
            $uid = $row['uid'];

            $notification_message = "Order Code: $order_code has been declined!";
            $notification_status = "unread"; // Set the initial status as unread
                
            // Insert the notification into the database
            $insert_notification_query = "INSERT INTO notification (uid, message, status, timestamp) 
                                            VALUES ('$uid', '$notification_message', '$notification_status', NOW())";
                
            $notif = mysqli_query($conn, $insert_notification_query);
        }
        if (!mysqli_query($conn, $update_query)) {
            echo "Error updating record: " . mysqli_error($conn);
        }
    }
}

// Handle confirm action
if (isset($_POST['confirm'])) {
    $order_code = mysqli_real_escape_string($conn, $_POST['confirm']);
    
    // Update order status to confirmed (osid = 2)
    $update_query = "UPDATE orders SET osid = '7' WHERE order_code = '$order_code'";

    // Get customer id
    $customer = "SELECT * FROM orders WHERE order_code = '$order_code'";
    $fetch = mysqli_query($conn, $customer);
    if ($fetch && mysqli_num_rows($fetch) > 0) {
        $row = mysqli_fetch_assoc($fetch);
        $uid = $row['uid'];

        $notification_message = "Order Code: $order_code is being prepared to ship!";
        $notification_status = "unread"; // Set the initial status as unread
            
        // Insert the notification into the database
        $insert_notification_query = "INSERT INTO notification (uid, message, status, timestamp) 
                                        VALUES ('$uid', '$notification_message', '$notification_status', NOW())";
            
        $notif = mysqli_query($conn, $insert_notification_query);
    }
    if (!mysqli_query($conn, $update_query)) {
        echo "Error updating record: " . mysqli_error($conn);
    }

    // Note: Removed the quantity reduction logic
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
                    <h3 class="fw-bold mb-3">Orders</h3>
                </div>
                <div class="ms-md-auto py-2 py-md-0"></div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List of Orders</h4>
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
                                                            gcash_rec.receipt AS gcash_receipt -- Include GCash receipt path
                                                    FROM orders 
                                                    LEFT JOIN gcash_rec ON orders.order_code = gcash_rec.order_code 
                                                    LEFT JOIN userinfo ON orders.uid = userinfo.infoid 
                                                    LEFT JOIN furniture ON orders.pid = furniture.pid 
                                                    WHERE orders.osid = 1 
                                                    GROUP BY orders.order_code";
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
                                            <?php if ($mop == 'gcash' && !empty($gcash_receipt)) : ?>
                                                <a href="../customer/gcash/<?php echo $gcash_receipt; ?>" target="_blank">View Receipt</a>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $date; ?></td>
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
            text: "Do you want to confirm this order?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, confirm it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form to confirm the order
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = '';
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'confirm';
                input.value = orderCode;
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function declineOrder(orderCode) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to decline this order?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, decline it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form to decline the order
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = '';
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'decline';
                input.value = orderCode;
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>

</html>
