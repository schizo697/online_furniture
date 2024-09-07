<?php
session_start();
include('../conn.php'); // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    $uid = $_SESSION['uid'];

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
                            aria-controls="to-receive" aria-selected="false">To Ship</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="shipping-tab" data-bs-toggle="tab" href="#shipping" role="tab"
                            aria-controls="shipping" aria-selected="false">To Receive</a>
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
                                                <th class="text-center">Date Order</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $orders = "SELECT orders.order_code, orders.uid, orders.total AS totalPrice, orders.qty, orders.date, furniture.pname,
                                            cart.color, cart.width, cart.height, cart.length, cart.materials, cart.foot_part, cart.foam, cart.fabric, cart.spring, cart.total_price AS productPrice , orders.osid
                                            FROM orders
                                            JOIN furniture ON orders.pid = furniture.pid
                                            JOIN order_status ON orders.osid = order_status.osid
                                            JOIN cart ON orders.cid = cart.cid 
                                            WHERE orders.uid = '$uid' AND orders.osid = 1";
                                            
                                            $ordersres = mysqli_query($conn, $orders);

                                            if($ordersres && mysqli_num_rows($ordersres) > 0){
                                                while($orderrow = mysqli_fetch_assoc($ordersres)){
                                                    ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $orderrow['order_code']; ?></td>
                                                        <td class="text-center">
                                                            <?php 
                                                                echo $orderrow['pname']; 
                                                                echo "<br><small>Color: " . $orderrow['color'] . "</small>";
                                                                echo "<br><small>Dimensions: " . $orderrow['width'] . " x " . $orderrow['length'] . " x " . $orderrow['height'] . "</small>";
                                                                echo "<br><small>Material: " . $orderrow['materials'] . ', ' . $orderrow['foam'] . ', ' . $orderrow['fabric'] . ', ' . $orderrow['spring'] . "</small>";
                                                                echo "<br><small>Foot Part: " . $orderrow['foot_part'] . "</small>";
                                                            ?>
                                                        </td>
                                                        <td class="text-right"><?php echo number_format($orderrow['productPrice'], 2); ?></td>
                                                        <td class="text-center"><?php echo $orderrow['qty']; ?></td>
                                                        <td class="text-center"><?php echo date('Y-m-d', strtotime($orderrow['date'])); ?></td>
                                                        <td class="text-center">
                                                            <!-- You can add action buttons here, e.g., view or cancel order -->
                                                            <a href="product_view.php?order_code=<?php echo $orderrow['order_code']; ?>" class="btn btn-sm btn-info">View</a>
                                                            
                                                            <button type="button" class="btn btn-danger btn-sm cancel_btn"
    data-order-code="<?php echo $orderrow['order_code']; ?>">
    Cancel
</button>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                echo "<tr><td colspan='7' class='text-center'>No orders found.</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- To Shipping Orders Tab -->
                    <div class="tab-pane fade" id="to-receive" role="tabpanel" aria-labelledby="to-receive-tab">
                        <div class="card">
                            <div class="card-header">
                                <h2>Shopping Cart - To Ship</h2>
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
                                                <th class="text-center">Date Order</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $orders = "SELECT orders.order_code, orders.uid, orders.total AS totalPrice, orders.qty, orders.date, furniture.pname,
                                            cart.color, cart.width, cart.height, cart.length, cart.materials, cart.foot_part, cart.foam, cart.fabric, cart.spring, cart.total_price AS productPrice , orders.osid
                                            FROM orders
                                            JOIN furniture ON orders.pid = furniture.pid
                                            JOIN order_status ON orders.osid = order_status.osid
                                            JOIN cart ON orders.cid = cart.cid 
                                            WHERE orders.uid = '$uid' AND orders.osid = 7";
                                            
                                            $ordersres = mysqli_query($conn, $orders);

                                            if($ordersres && mysqli_num_rows($ordersres) > 0){
                                                while($orderrow = mysqli_fetch_assoc($ordersres)){
                                                    ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $orderrow['order_code']; ?></td>
                                                        <td class="text-center">
                                                            <?php 
                                                                echo $orderrow['pname']; 
                                                                echo "<br><small>Color: " . $orderrow['color'] . "</small>";
                                                                echo "<br><small>Dimensions: " . $orderrow['width'] . " x " . $orderrow['length'] . " x " . $orderrow['height'] . "</small>";
                                                                echo "<br><small>Material: " . $orderrow['materials'] . ', ' . $orderrow['foam'] . ', ' . $orderrow['fabric'] . ', ' . $orderrow['spring'] . "</small>";
                                                                echo "<br><small>Foot Part: " . $orderrow['foot_part'] . "</small>";
                                                            ?>
                                                        </td>
                                                        <td class="text-right"><?php echo number_format($orderrow['productPrice'], 2); ?></td>
                                                        <td class="text-center"><?php echo $orderrow['qty']; ?></td>
                                                        <td class="text-center"><?php echo date('Y-m-d', strtotime($orderrow['date'])); ?></td>
                                                        <td class="text-center">
                                                            <!-- You can add action buttons here, e.g., view or cancel order -->
                                                            <a href="product_view.php?order_code=<?php echo $orderrow['order_code']; ?>" class="btn btn-sm btn-info">View</a>
                                                            <!-- <a href="cancel_order.php?order_code=<?php echo $orderrow['order_code']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to cancel this order?');">Cancel</a> -->
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                echo "<tr><td colspan='7' class='text-center'>No orders found.</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                <!-- Received Orders Tab -->
                <div class="tab-pane fade" id="shipping" role="tabpanel" aria-labelledby="shipping-tab">
                        <div class="card">
                            <div class="card-header">
                                <h2>To Received</h2>
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
                                              
                                                <th class="text-right">Status</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            $orders_query = "SELECT *, shipping.shipping_status, shipping.expected_date FROM shipping 
                                                JOIN orders ON orders.order_code = shipping.order_code
                                                JOIN furniture ON furniture.pid = orders.pid 
                                                WHERE orders.uid = ? AND orders.osid = 7";
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
                                                            'shipping_status' => $order_row['shipping_status'], // Added shipping_status
                                        'expected_date' => $order_row['expected_date'] // Added expected_date
                                                            
                                                        ];
                                                    }
                                                    $orders[$order_code]['product_details'][] = [
                                                        'pname' => $order_row['pname'],
                                                        'price' => $order_row['price'],
                                                        'qty' => $order_row['qty'],
                                                       
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
                                                <!-- <td class="text-center"><?php echo htmlspecialchars($order['date']); ?></td> -->
                                                <td class="text-center"><?php echo htmlspecialchars($order['shipping_status']); ?>, <?php echo htmlspecialchars($order['expected_date']); ?></td>

                                                
                                                <td class="text-center">
                                                    <a href="product_view.php?order_code=<?php echo urlencode($order_code); ?>"
                                                        class="btn btn-sm btn-info">
                                                        View
                                                    </a>
                                                    <button type="button" class="btn btn-success btn-sm received_btn" data-order-id="<?php echo htmlspecialchars($order_code); ?>">
                                                        <i class="fas fa-check"></i> Received
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
                                                    <th class="text-right">Order ID</th>
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
                                            $orders = "SELECT orders.order_code, orders.uid, orders.total AS totalPrice, orders.qty, orders.date, furniture.pname,orders.total,order_return.return_status, order_return.admin_response,
                                            cart.color, cart.width, cart.height, cart.length, cart.materials, cart.foot_part, cart.foam, cart.fabric, cart.spring, cart.total_price AS productPrice , orders.osid
                                            FROM orders
                                            JOIN furniture ON orders.pid = furniture.pid
                                            JOIN order_return ON orders.order_id = order_return.order_id
                                            JOIN order_status ON orders.osid = order_status.osid
                                            JOIN cart ON orders.cid = cart.cid 
                                            WHERE orders.uid = '$uid' AND orders.osid IN (4, 5, 6)";
                                            
                                            $ordersres = mysqli_query($conn, $orders);

                                            if($ordersres && mysqli_num_rows($ordersres) > 0){
                                                while($orderrow = mysqli_fetch_assoc($ordersres)){
                                                    ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $orderrow['order_code']; ?></td>
                                                        <td class="text-center">
                                                            <?php 
                                                                echo $orderrow['pname']; 
                                                                echo "<br><small>Color: " . $orderrow['color'] . "</small>";
                                                                echo "<br><small>Dimensions: " . $orderrow['width'] . " x " . $orderrow['length'] . " x " . $orderrow['height'] . "</small>";
                                                                echo "<br><small>Material: " . $orderrow['materials'] . ', ' . $orderrow['foam'] . ', ' . $orderrow['fabric'] . ', ' . $orderrow['spring'] . "</small>";
                                                                echo "<br><small>Foot Part: " . $orderrow['foot_part'] . "</small>";
                                                            ?>
                                                        </td>
                                                        <td class="text-right"><?php echo number_format($orderrow['productPrice'], 2); ?></td>
                                                        <td class="text-center"><?php echo $orderrow['qty']; ?></td>
                                                        <td class="text-center"><?php echo $orderrow['total']; ?></td>
                                                        <td class="text-center"><?php echo date('Y-m-d', strtotime($orderrow['date'])); ?></td>
                                                        <td class="text-center"><?php echo $orderrow['return_status']; ?></td>
                                                        <td class="text-center"><?php echo $orderrow['admin_response']; ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                echo "<tr><td colspan='7' class='text-center'>No orders found.</td></tr>";
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
                                                <th class="text-center">Date Order</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $orders = "SELECT orders.order_code, orders.uid, orders.total AS totalPrice, orders.qty, orders.date, furniture.pname,
                                            cart.color, cart.width, cart.height, cart.length, cart.materials, cart.foot_part, cart.foam, cart.fabric, cart.spring, cart.total_price AS productPrice , orders.osid
                                            FROM orders
                                            JOIN furniture ON orders.pid = furniture.pid
                                            JOIN order_status ON orders.osid = order_status.osid
                                            JOIN cart ON orders.cid = cart.cid 
                                            WHERE orders.uid = '$uid' AND orders.osid = 0";
                                            
                                            $ordersres = mysqli_query($conn, $orders);

                                            if($ordersres && mysqli_num_rows($ordersres) > 0){
                                                while($orderrow = mysqli_fetch_assoc($ordersres)){
                                                    ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $orderrow['order_code']; ?></td>
                                                        <td class="text-center">
                                                            <?php 
                                                                echo $orderrow['pname']; 
                                                                echo "<br><small>Color: " . $orderrow['color'] . "</small>";
                                                                echo "<br><small>Dimensions: " . $orderrow['width'] . " x " . $orderrow['length'] . " x " . $orderrow['height'] . "</small>";
                                                                echo "<br><small>Material: " . $orderrow['materials'] . ', ' . $orderrow['foam'] . ', ' . $orderrow['fabric'] . ', ' . $orderrow['spring'] . "</small>";
                                                                echo "<br><small>Foot Part: " . $orderrow['foot_part'] . "</small>";
                                                            ?>
                                                        </td>
                                                        <td class="text-right"><?php echo number_format($orderrow['productPrice'], 2); ?></td>
                                                        <td class="text-center"><?php echo $orderrow['qty']; ?></td>
                                                        <td class="text-center"><?php echo date('Y-m-d', strtotime($orderrow['date'])); ?></td>
                                                        <td class="text-center">
                                                            <!-- You can add action buttons here, e.g., view or cancel order -->
                                                            <a href="product_view.php?order_code=<?php echo $orderrow['order_code']; ?>" class="btn btn-sm btn-info">View</a>
                                                            <a href="cancel_order.php?order_code=<?php echo $orderrow['order_code']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to cancel this order?');">Cancel</a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                echo "<tr><td colspan='7' class='text-center'>No orders found.</td></tr>";
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
                                                $orders_query = "SELECT orders.order_code, orders.uid, orders.total AS totalPrice, orders.qty, orders.date, furniture.pname,orders.order_id, furniture.price,
                                                    cart.color, cart.width, cart.height, cart.length, cart.materials, cart.foot_part, cart.foam, cart.fabric, cart.spring, cart.total_price AS productPrice , orders.osid
                                                    FROM orders
                                                    JOIN furniture ON orders.pid = furniture.pid
                                                    JOIN order_status ON orders.osid = order_status.osid
                                                    JOIN cart ON orders.cid = cart.cid 
                                                    WHERE orders.uid = ? AND orders.osid = 3";
                                                $stmt = $conn->prepare($orders_query);
                                                $stmt->bind_param("i", $_SESSION['uid']);
                                                $stmt->execute();
                                                $order_res = $stmt->get_result();

                                                if ($order_res && $order_res->num_rows > 0) {
                                                    while ($order_row = $order_res->fetch_assoc()) {
                                                        $order_id = $order_row['order_id']; // Assuming 'order_id' is the primary key column in the 'orders' table
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
                                                    <a href="product_rate.php?order_code=<?php echo $order_code ?>" class="btn btn-sm btn-info">
                                                        <!-- <i class="fas fa-eye"></i>  -->
                                                        View
                                                    </a>
                                                    
                                                    <a href="return.php?order_id=<?php echo $order_id ?>" class="btn btn-warning btn-sm">
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
                showAlert('success', 'Order successfully!');
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
        $(document).ready(function () {
            $('.cancel_btn').click(function () {
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
                            success: function (response) {
                                // Handle success response if needed
                                location.reload(); // Reload the page after cancellation
                            },
                            error: function (xhr, status, error) {
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
        $(document).ready(function () {
            $('.received_btn').click(function () {
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
                            success: function (response) {
                                console.log('Response from update_order_status.php:', response); // Check response from PHP
                                Swal.fire(
                                    'Received!',
                                    'Order marked as received successfully.',
                                    'success'
                                ).then(function () {
                                    location.reload(); // Reload the page to reflect changes
                                });
                            },
                            error: function (xhr, status, error) {
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
        $(document).ready(function () {
            $('.buy_again_btn').click(function () {
                var orderCode = $(this).data('order-code');
                $.ajax({
                    type: 'POST',
                    url: 'buyagain.php', // Replace with your PHP script to update osid
                    data: { order_code: orderCode, new_osid: 1 },
                    success: function (response) {

                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        // Handle errors
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>

</body>

</html>