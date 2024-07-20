<?php 
session_start();
include('../conn.php'); // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $user_id = $_SESSION['uid'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $gender = $_POST['gender'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    
    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("UPDATE userinfo SET firstname=?, lastname=?, gender=?, contact=?, address=? WHERE infoid=?");
    $stmt->bind_param("sssssi", $firstname, $lastname, $gender, $contact, $address, $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('Profile updated successfully.');</script>";
    } else {
        echo "<script>alert('Error updating profile.');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('includes/topbar.php'); ?>
</head>

<body>
  <!-- Single Page Header start -->
  <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">My Purchase</h1>
    </div>
    <!-- Single Page Header End -->

    <!-- Main -->
    <div class="container-xl px-4 mt-4">
        <!-- Account page navigation -->
        <nav class="nav nav-borders">
         
            <a class="nav-link" href="purchase.php">My Purchase</a>
        </nav>
        <hr class="mt-0 mb-4">
        <div class="row">
            <div class="col-xl-12">
                <!-- Tabs -->
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="to-pay-tab" data-bs-toggle="tab" href="#to-pay" role="tab"
                            aria-controls="to-pay" aria-selected="true">Pending</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="to-receive-tab" data-bs-toggle="tab" href="#to-receive" role="tab"
                            aria-controls="to-receive" aria-selected="false">To Receive</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="return-tab" data-bs-toggle="tab" href="#return" role="tab"
                            aria-controls="return" aria-selected="false">Return</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="cancelled-tab" data-bs-toggle="tab" href="#cancelled" role="tab"
                            aria-controls="cancelled" aria-selected="false">Cancelled</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="completed-tab" data-bs-toggle="tab" href="#completed" role="tab"
                            aria-controls="completed" aria-selected="false">Completed</a>
                    </li>
                    
                </ul>
                <!-- Tab content -->
                <div class="tab-content mt-3" id="myTabContent">
                    <!-- Pending Orders Tab -->
                    <div class="tab-pane fade show active" id="to-pay" role="tabpanel" aria-labelledby="to-pay-tab">
                        <div class="card">
                            <div class="card-header">
                                <h2>Shopping Cart - Pending</h2>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Order Code</th>
                                                <th class="text-center">Product Name & Details</th>
                                                <th class="text-right">Product Price</th>
                                                <th class="text-center">Quantity</th>
                                                <th class="text-right">Total Price</th>
                                                <th class="text-center">Date Order</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            $orders_query = "SELECT * FROM orders 
                                                JOIN furniture ON orders.pid = furniture.pid 
                                                WHERE orders.osid = '1' AND orders.uid = ?";
                                            $stmt = $conn->prepare($orders_query);
                                            $stmt->bind_param("i", $_SESSION['uid']);
                                            $stmt->execute();
                                            $order_res = $stmt->get_result();

                                            if ($order_res && $order_res->num_rows > 0) {
                                                $orders = [];
                                                while ($order_row = $order_res->fetch_assoc()) {
                                                    $order_code = $order_row['order_code'];
                                                    if (!isset($orders[$order_code])) {
                                                        $orders[$order_code] = [
                                                            'product_details' => [],
                                                            'total' => 0,
                                                            'date' => $order_row['date']
                                                        ];
                                                    }
                                                    $orders[$order_code]['product_details'][] = [
                                                        'pname' => $order_row['pname'],
                                                        'price' => $order_row['price'],
                                                        'qty' => $order_row['qty']
                                                    ];
                                                    $orders[$order_code]['total'] += $order_row['price'] * $order_row['qty'];
                                                }

                                                foreach ($orders as $order_code => $order) {
                                                    $product_details_str = '';
                                                    $total_price_str = '';
                                                    $qty_str = '';
                                                    foreach ($order['product_details'] as $product) {
                                                        $product_details_str .= $product['pname'] . '<br>';
                                                        $total_price_str .= $product['price'] . '<br>';
                                                        $qty_str .= $product['qty'] . '<br>';
                                                    }
                                            ?>
                                            <tr>
                                                <td><?php echo $order_code; ?></td>
                                                <td><?php echo $product_details_str; ?></td>
                                                <td><?php echo $total_price_str; ?></td>
                                                <td><?php echo $qty_str; ?></td>
                                                <td><?php echo $order['total']; ?></td>
                                                <td><?php echo $order['date']; ?></td>
                                              
                                                <td>
                                                    <a href="product_view.php?order_code=<?php echo $order_code ?>"
                                                        class="btn btn-warning btn-sm">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    <button type="button" class="btn btn-danger btn-sm cancel_btn"
                                                        data-bs-toggle="modal" data-order-code="<?php echo $order_code; ?>">
                                                    Cancel
                                                </button>

                                                </td>
                                            </tr>
                                            <?php
                                                }
                                            } else {
                                                echo '<tr><td colspan="7" class="text-center">No pending orders found.</td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- To Receive Orders Tab -->
                    <div class="tab-pane fade" id="to-receive" role="tabpanel" aria-labelledby="to-receive-tab">
                        <div class="card">
                            <div class="card-header">
                                <h2>Shopping Cart - To Receive</h2>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Order Code</th>
                                                <th class="text-center">Product Name & Details</th>
                                                <th class="text-right">Product Price</th>
                                                <th class="text-center">Quantity</th>
                                                <th class="text-right">Total Price</th>
                                                <th class="text-center">Date Order</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            $orders_query = "SELECT * FROM orders 
                                                JOIN furniture ON orders.pid = furniture.pid 
                                                WHERE orders.osid = '2' AND orders.uid = ?";
                                            $stmt = $conn->prepare($orders_query);
                                            $stmt->bind_param("i", $_SESSION['uid']);
                                            $stmt->execute();
                                            $order_res = $stmt->get_result();

                                            if ($order_res && $order_res->num_rows > 0) {
                                                $orders = [];
                                                while ($order_row = $order_res->fetch_assoc()) {
                                                    $order_code = $order_row['order_code'];
                                                    if (!isset($orders[$order_code])) {
                                                        $orders[$order_code] = [
                                                            'product_details' => [],
                                                            'total' => 0,
                                                            'date' => $order_row['date']
                                                        ];
                                                    }
                                                    $orders[$order_code]['product_details'][] = [
                                                        'pname' => $order_row['pname'],
                                                        'price' => $order_row['price'],
                                                        'qty' => $order_row['qty']
                                                    ];
                                                    $orders[$order_code]['total'] += $order_row['price'] * $order_row['qty'];
                                                }

                                                foreach ($orders as $order_code => $order) {
                                                    $product_details_str = '';
                                                    $total_price_str = '';
                                                    $qty_str = '';
                                                    foreach ($order['product_details'] as $product) {
                                                        $product_details_str .= $product['pname'] . '<br>';
                                                        $total_price_str .= $product['price'] . '<br>';
                                                        $qty_str .= $product['qty'] . '<br>';
                                                    }
                                            ?>
                                            <tr>
                                                <td><?php echo $order_code; ?></td>
                                                <td><?php echo $product_details_str; ?></td>
                                                <td><?php echo $total_price_str; ?></td>
                                                <td><?php echo $qty_str; ?></td>
                                                <td><?php echo $order['total']; ?></td>
                                                <td><?php echo $order['date']; ?></td>
                                                <td>
                                                <a href="product_view.php?order_code=<?php echo $order_code ?>"
                                                        class="btn btn-warning btn-sm">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    <button type="button" class="btn btn-success btn-sm received_btn" data-order-id="<?php echo htmlspecialchars($order_code); ?>">
                                                    <i class="fas fa-check"></i> Received
                                                </button>
                                                


                                                </td>
                                            </tr>
                                            <?php
                                                }
                                            } else {
                                                echo '<tr><td colspan="7" class="text-center">No orders to receive found.</td></tr>';
                                            }
                                            ?>
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                       <!-- Return Orders Tab -->
                        <div class="tab-pane fade" id="return" role="tabpanel" aria-labelledby="return-tab">
                            <div class="card">
                                <div class="card-header">
                                    <h2>Shopping Cart - Return/Refund</h2>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-right">Order Code</th>
                                                    <th class="text-center">Product Name & Details</th>
                                                    <th class="text-right">Product Price</th>
                                                    <th class="text-center">Quantity</th>
                                                    <th class="text-right">Total Price</th>
                                                    <th class="text-center">Date Order</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Response</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $orders_query = "SELECT orders.*, furniture.*, order_return.*, orders.osid AS new_osid
                                                                    FROM orders
                                                                    JOIN furniture ON orders.pid = furniture.pid
                                                                    LEFT JOIN order_return ON orders.order_code = order_return.order_code
                                                                    WHERE orders.osid IN (4, 2, 5, 6) AND orders.uid = ?";
                                                $stmt = $conn->prepare($orders_query);
                                                $stmt->bind_param("i", $_SESSION['uid']);
                                                $stmt->execute();
                                                $order_res = $stmt->get_result();

                                                if ($order_res && $order_res->num_rows > 0) {
                                                    $orders = [];
                                                    while ($order_row = $order_res->fetch_assoc()) {
                                                        $order_code = $order_row['order_code'];
                                                        if (!isset($orders[$order_code])) {
                                                            $orders[$order_code] = [
                                                                'product_details' => [],
                                                                'total' => 0,
                                                                'date' => $order_row['date'],
                                                                'return_status' => $order_row['return_status'],
                                                                'admin_response' => $order_row['admin_response'],
                                                            ];
                                                        }
                                                        $orders[$order_code]['product_details'][] = [
                                                            'pname' => $order_row['pname'],
                                                            'price' => $order_row['price'],
                                                            'qty' => $order_row['qty']
                                                        ];
                                                        $orders[$order_code]['total'] += $order_row['price'] * $order_row['qty'];
                                                    }

                                                    foreach ($orders as $order_code => $order) {
                                                        $product_details_str = '';
                                                        $total_price_str = '';
                                                        $qty_str = '';
                                                        foreach ($order['product_details'] as $product) {
                                                            $product_details_str .= $product['pname'] . '<br>';
                                                            $total_price_str .= number_format($product['price'], 2) . '<br>'; 
                                                            $qty_str .= $product['qty'] . '<br>';
                                                        }
                                                ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $order_code; ?></td>
                                                    <td><?php echo $product_details_str; ?></td>
                                                    <td class="text-right"><?php echo $total_price_str; ?></td>
                                                    <td class="text-center"><?php echo $qty_str; ?></td>
                                                    <td class="text-right"><?php echo number_format($order['total'], 2); ?></td> 
                                                    <td class="text-center"><?php echo $order['date']; ?></td>
                                                    <td class="text-center"><?php echo $order['return_status']; ?></td>
                                                    <td class="text-center"><?php echo $order['admin_response']; ?></td>
                                                </tr>
                                                <?php
                                                    }
                                                } else {
                                                    echo '<tr><td colspan="7" class="text-center">No orders to refund found.</td></tr>';
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>


                    <!-- Cancelled Orders Tab -->
                    <div class="tab-pane fade" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
                        <div class="card">
                            <div class="card-header">
                                <h2>Shopping Cart - Cancelled</h2>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Order Code</th>
                                                <th class="text-center">Product Name & Details</th>
                                                <th class="text-right">Product Price</th>
                                                <th class="text-center">Quantity</th>
                                                <th class="text-right">Total Price</th>
                                                <th class="text-center">Date Order</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            $orders_query = "SELECT * FROM orders 
                                                JOIN furniture ON orders.pid = furniture.pid 
                                                WHERE orders.osid = '0' AND orders.uid = ?";
                                            $stmt = $conn->prepare($orders_query);
                                            $stmt->bind_param("i", $_SESSION['uid']);
                                            $stmt->execute();
                                            $order_res = $stmt->get_result();

                                            if ($order_res && $order_res->num_rows > 0) {
                                                $orders = [];
                                                while ($order_row = $order_res->fetch_assoc()) {
                                                    $order_code = $order_row['order_code'];
                                                    if (!isset($orders[$order_code])) {
                                                        $orders[$order_code] = [
                                                            'product_details' => [],
                                                            'total' => 0,
                                                            'date' => $order_row['date']
                                                        ];
                                                    }
                                                    $orders[$order_code]['product_details'][] = [
                                                        'pname' => $order_row['pname'],
                                                        'price' => $order_row['price'],
                                                        'qty' => $order_row['qty']
                                                    ];
                                                    $orders[$order_code]['total'] += $order_row['price'] * $order_row['qty'];
                                                }

                                                foreach ($orders as $order_code => $order) {
                                                    $product_details_str = '';
                                                    $total_price_str = '';
                                                    $qty_str = '';
                                                    foreach ($order['product_details'] as $product) {
                                                        $product_details_str .= $product['pname'] . '<br>';
                                                        $total_price_str .= $product['price'] . '<br>';
                                                        $qty_str .= $product['qty'] . '<br>';
                                                    }
                                            ?>
                                            <tr>
                                                <td><?php echo $order_code; ?></td>
                                                <td><?php echo $product_details_str; ?></td>
                                                <td><?php echo $total_price_str; ?></td>
                                                <td><?php echo $qty_str; ?></td>
                                                <td><?php echo $order['total']; ?></td>
                                                <td><?php echo $order['date']; ?></td>
                                                    <td>
                                                    <a href="product_view.php?order_code=<?php echo $order_code ?>"
                                                        class="btn btn-warning btn-sm">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    <button type="button" class="btn btn-success btn-sm buy_again_btn" 
                                                    data-order-code="<?php echo $order_code; ?>">
                                                    Buy Again
                                                </button>

                                                    </td>
                                                </tr>
                                                <?php
                                                    }
                                                } else {
                                                    echo '<tr><td colspan="7" class="text-center">No orders to cancel found.</td></tr>';
                                                }
                                                ?>
                                            </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
               <!-- Completed Orders Tab -->
<div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
    <div class="card">
        <div class="card-header">
            <h2>Shopping Cart - Completed</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Order Code</th>
                            <th class="text-center">Product Name & Details</th>
                            <th class="text-right">Product Price</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-right">Total Price</th>
                            <th class="text-center">Date Order</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php
                        $orders_query = "SELECT * FROM orders 
                            JOIN furniture ON orders.pid = furniture.pid 
                            WHERE orders.osid = '3' AND orders.uid = ?";
                        $stmt = $conn->prepare($orders_query);
                        $stmt->bind_param("i", $_SESSION['uid']);
                        $stmt->execute();
                        $order_res = $stmt->get_result();

                        if ($order_res && $order_res->num_rows > 0) {
                            while ($order_row = $order_res->fetch_assoc()) {
                                $order_code = $order_row['order_code'];
                                $product_name = $order_row['pname'];
                                $product_price = $order_row['price'];
                                $quantity = $order_row['qty'];
                                $total_price = $product_price * $quantity;
                                $date_order = $order_row['date'];
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $order_code; ?></td>
                        <td class="text-center"><?php echo $product_name; ?></td>
                        <td class="text-right"><?php echo number_format($product_price, 2); ?></td>
                        <td class="text-center"><?php echo $quantity; ?></td>
                        <td class="text-right"><?php echo number_format($total_price, 2); ?></td>
                        <td class="text-center"><?php echo $date_order; ?></td>
                        <td class="text-center">
                            <a href="product_view.php?order_code=<?php echo $order_code ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="return.php?order_code=<?php echo $order_row['order_code'] ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-box"></i> Return
                            </a>
                        </td>
                    </tr>
                    <?php
                            }
                        } else {
                            echo '<tr><td colspan="7" class="text-center">No orders completed found.</td></tr>';
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- End Tabs -->

                    </div>
            </div>
        </div>
    </div>
  
<!-- Return Modal -->
<div class="modal fade" id="returnModal" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="returnModalLabel">Return Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="returnOrderForm" method="post" action="return_order.php">
                    <input type="hidden" id="return_order_code" name="order_code" value="">
                    <p>Are you sure you want to return this order?</p>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Return Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Return Modal -->

<!-- Refund Modal -->
<div class="modal fade" id="refundModal" tabindex="-1" aria-labelledby="refundModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="refundModalLabel">Refund Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="refundOrderForm" method="post" action="refund_order.php">
                    <input type="hidden" id="refund_order_code" name="order_code" value="">
                    <p>Are you sure you want to refund this order?</p>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Refund Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Refund Modal -->

<br><br>
    <?php include('includes/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function showAlert(type, message) {
            Swal.fire({
                icon: type,
                text: message,
            });
        }

        function checkURLParams() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('success') && urlParams.get('success') === 'true') {
                showAlert('success', 'Order return successfully!');
            } else if (urlParams.has('success') && urlParams.get('success') === 'false') {
                showAlert('error', 'Something went wrong!');
            } 
        }
        window.onload = checkURLParams;
    </script>

<script>
    $(document).on('click', '.cancel_btn', function () {
        var orderCode = $(this).data('order-code');
        $('#cancel_order_code').val(orderCode);
        $('#cancelModal').modal('show');
    });

    $(document).on('click', '.return_btn', function () {
        var orderCode = $(this).data('order-code');
        $('#return_order_code').val(orderCode);
        $('#returnModal').modal('show');
    });

    $(document).on('click', '.refund_btn', function () {
        var orderCode = $(this).data('order-code');
        $('#refund_order_code').val(orderCode);
        $('#refundModal').modal('show');
    });
</script>

<script>
    $(document).ready(function() {
        $('.cancel_btn').click(function() {
            var orderCode = $(this).data('order-code');
            
            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to cancel this order?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, cancel it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: 'cancel_order.php',
                        data: { order_code: orderCode },
                        success: function(response) {
                            // Handle success response if needed
                            location.reload(); // Reload the page after cancellation
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            // Handle error as needed
                        }
                    });
                }
            });
        });
    });
</script>
<!-- Received -->
<script>
    $(document).ready(function() {
        $('.received_btn').click(function() {
            var orderCode = $(this).data('order-id');
            
            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to mark this order as received?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, mark it as received!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
    type: 'POST',
    url: 'update_order_status.php',
    data: { order_code: orderCode, new_osid: 3 },
    success: function(response) {
        console.log('Response from update_order_status.php:', response); // Check response from PHP
        Swal.fire(
            'Received!',
            'Order marked as received successfully.',
            'success'
        ).then(function() {
            location.reload(); // Reload the page to reflect changes
        });
    },
    error: function(xhr, status, error) {
        console.error(xhr.responseText);
        Swal.fire(
            'Error!',
            'Failed to mark order as received. Please try again.',
            'error'
        );
    }
});

                }
            });
        });
    });
</script>
<script>
$(document).ready(function() {
    $('.buy_again_btn').click(function() {
        var orderCode = $(this).data('order-code');
        $.ajax({
            type: 'POST',
            url: 'buyagain.php', // Replace with your PHP script to update osid
            data: { order_code: orderCode, new_osid: 1 },
            success: function(response) {
             
                location.reload(); 
            },
            error: function(xhr, status, error) {
                // Handle errors
                console.error(xhr.responseText);
            }
        });
    });
});
</script>

</body>
</html>
