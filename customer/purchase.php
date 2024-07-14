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
                                            $user_id = $_SESSION['uid'];
                                            $orders_query = "SELECT * FROM orders 
                                                JOIN furniture ON orders.pid = furniture.pid 
                                                WHERE orders.osid = '1' AND orders.uid = '$user_id'";
                                            $order_res = mysqli_query($conn, $orders_query);

                                            if ($order_res && mysqli_num_rows($order_res) > 0) {
                                                while ($order_row = mysqli_fetch_assoc($order_res)) {
                                                    $order_code = $order_row['order_code'];
                                                    $pname = $order_row['pname'];
                                                    $price = $order_row['price'];
                                                    $total_quantity = $order_row['quantity'];
                                                    $total = $order_row['total'];
                                                    $date = $order_row['date'];
                                            ?>
                                            <tr>
                                                <td><?php echo $order_code; ?></td>
                                                <td><?php echo $pname; ?></td>
                                                <td><?php echo $price; ?></td>
                                                <td><?php echo $total_quantity; ?></td>
                                                <td><?php echo $total; ?></td>
                                                <td><?php echo $date; ?></td>
                                                <td>
                                                    <a href="product_details.php?order_code=<?php echo $order_code ?>"
                                                        class="btn btn-success btn-sm">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    <button type="button" class="btn btn-danger btn-sm cancel_btn"
                                                        data-bs-toggle="modal" data-bs-target="#exampleModal"
                                                        data-order-code="<?php echo $order_code; ?>">
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
                                                WHERE orders.osid = '2' AND orders.uid = '$user_id'";
                                            $order_res = mysqli_query($conn, $orders_query);

                                            if ($order_res && mysqli_num_rows($order_res) > 0) {
                                                while ($order_row = mysqli_fetch_assoc($order_res)) {
                                                    $order_code = $order_row['order_code'];
                                                    $pname = $order_row['pname'];
                                                    $price = $order_row['price'];
                                                    $total_quantity = $order_row['quantity'];
                                                    $total = $order_row['total'];
                                                    $date = $order_row['date'];
                                            ?>
                                            <tr>
                                                <td><?php echo $order_code; ?></td>
                                                <td><?php echo $pname; ?></td>
                                                <td><?php echo $price; ?></td>
                                                <td><?php echo $total_quantity; ?></td>
                                                <td><?php echo $total; ?></td>
                                                <td><?php echo $date; ?></td>
                                                <td>
                                                    <a href="product_details.php?order_code=<?php echo $order_code ?>"
                                                        class="btn btn-success btn-sm">
                                                        <i class="fas fa-check"></i> Order Received
                                                    </a>
                                                    <button type="button" class="btn btn-warning btn-sm cancel_btn"
                                                        data-bs-toggle="modal" data-bs-target="#exampleModal"
                                                        data-order-code="<?php echo $order_code; ?>">
                                                        <i class="fas fa-check"></i>
                                                        View
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php
                                                }
                                            } else {
                                                echo '<tr><td colspan="7" class="text-center">No orders to receive
                                                        yet.</td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Completed TAB -->
                      <!-- To Receive Orders Tab -->
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
                                                WHERE orders.osid = '3' AND orders.uid = '$user_id'";
                                            $order_res = mysqli_query($conn, $orders_query);

                                            if ($order_res && mysqli_num_rows($order_res) > 0) {
                                                while ($order_row = mysqli_fetch_assoc($order_res)) {
                                                    $order_code = $order_row['order_code'];
                                                    $pname = $order_row['pname'];
                                                    $price = $order_row['price'];
                                                    $total_quantity = $order_row['quantity'];
                                                    $total = $order_row['total'];
                                                    $date = $order_row['date'];
                                            ?>
                                            <tr>
                                                <td><?php echo $order_code; ?></td>
                                                <td><?php echo $pname; ?></td>
                                                <td><?php echo $price; ?></td>
                                                <td><?php echo $total_quantity; ?></td>
                                                <td><?php echo $total; ?></td>
                                                <td><?php echo $date; ?></td>
                                                <td>
                                                    <a href="product_details.php?order_code=<?php echo $order_code ?>"
                                                        class="btn btn-success btn-sm">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    <button type="button" class="btn btn-danger btn-sm cancel_btn"
                                                        data-bs-toggle="modal" data-bs-target="#exampleModal"
                                                        data-order-code="<?php echo $order_code; ?>">
                                                        Cancel
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php
                                                }
                                            } else {
                                                echo '<tr><td colspan="7" class="text-center">No orders complete
                                                        yet.</td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Cancelled TAB -->
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
                                                WHERE orders.osid = '0' AND orders.uid = '$user_id'";
                                            $order_res = mysqli_query($conn, $orders_query);

                                            if ($order_res && mysqli_num_rows($order_res) > 0) {
                                                while ($order_row = mysqli_fetch_assoc($order_res)) {
                                                    $order_code = $order_row['order_code'];
                                                    $pname = $order_row['pname'];
                                                    $price = $order_row['price'];
                                                    $total_quantity = $order_row['quantity'];
                                                    $total = $order_row['total'];
                                                    $date = $order_row['date'];
                                            ?>
                                            <tr>
                                                <td><?php echo $order_code; ?></td>
                                                <td><?php echo $pname; ?></td>
                                                <td><?php echo $price; ?></td>
                                                <td><?php echo $total_quantity; ?></td>
                                                <td><?php echo $total; ?></td>
                                                <td><?php echo $date; ?></td>
                                                <td>
                                                    <a href="product_details.php?order_code=<?php echo $order_code ?>"
                                                        class="btn btn-success btn-sm">
                                                        <i class="fas fa-eye"></i> Reorder
                                                    </a>
                                               
                                                </td>
                                            </tr>
                                            <?php
                                                }
                                            } else {
                                                echo '<tr><td colspan="7" class="text-center">No orders cancell
                                                        yet.</td></tr>';
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
        </div>
    </div>

    <br><br><br><br><br><br><br>
    <?php include('includes/footer.php'); ?>

   

    <!-- Back to Top -->
    <a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>
</html>
