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
        <h1 class="text-center text-white display-6">Profile</h1>
    </div>
    <!-- Single Page Header End -->

    <!-- Main -->
    <div class="container-xl px-4 mt-4">
        <!-- Account page navigation -->
        <nav class="nav nav-borders">
            <a class="nav-link active ms-0" href="profile.php">Profile</a>
            <a class="nav-link" href="purchase.php">My Purchase</a>
        </nav>
        <hr class="mt-0 mb-4">
        <div class="row">
            <div class="col-xl-4">
                <!-- Add your content for the left column here -->
            </div>
            <div class="col-xl-8">
                <!-- Add Tabs Here -->
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="to-pay-tab" data-bs-toggle="tab" href="#to-pay" role="tab" aria-controls="to-pay" aria-selected="true">Pending</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="to-receive-tab" data-bs-toggle="tab" href="#to-receive" role="tab" aria-controls="to-receive" aria-selected="false">To Receive</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="cancelled-tab" data-bs-toggle="tab" href="#cancelled" role="tab" aria-controls="cancelled" aria-selected="false">Cancelled</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="completed-tab" data-bs-toggle="tab" href="#completed" role="tab" aria-controls="completed" aria-selected="false">Completed</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="to-pay" role="tabpanel" aria-labelledby="to-pay-tab">
                        <div class="container px-3 my-5 clearfix">
                            <!-- Shopping cart table for To Pay -->
                            <div class="card">
                                <div class="card-header">
                                    <h2>Shopping Cart - Pending</h2>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered m-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-center py-3 px-4">Order Code</th>
                                                    <th class="text-center py-3 px-4">Product Name &amp; Details</th>
                                                    <th class="text-right py-3 px-4">Product Price</th>
                                                    <!-- <th class="text-center py-3 px-4">Quantity</th>
                                                    <th class="text-center py-3 px-4">Total Price</th> -->
                                                    <!-- <th class="text-center py-3 px-4">Date Order</th> -->
                                                    <th class="text-right py-3 px-4">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            if(isset($_SESSION['uid'])){
                                                $user_id = $_SESSION['uid'];
                                                $orders = "SELECT * FROM furniture 
                                            JOIN furniture_type ON furniture.fid = furniture_type.fid WHERE furniture.status = 'Active'";
                                                $order_res = mysqli_query($conn, $orders);
                                                if($order_res && mysqli_num_rows($order_res) > 0){
                                                    while($order_row = mysqli_fetch_assoc($order_res)){
                                                        $pid = $order_row['pid'];
                                                        $pname = $order_row['pname'];
                                                        $price = $order_row['price'];
                                                        // $total_price = $order_row['total_price'];
                                                        // $total_quantity = $order_row['total_quantity'];
                                                        // $date_order = $order_row['date_order_formatted'];
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $pid; ?></td>
                                                            <td><?php echo $pname; ?></td>
                                                            <td><?php echo $price; ?></td>
                                                            <!-- <td><?php echo $total_quantity; ?></td>
                                                            <td><?php echo $total_price; ?></td> -->
                                                            <!-- <td><?php echo $date_order; ?></td> -->
                                                            <td>
                                                                <a href="product_details.php?order_code=<?php echo $order_code ?>" class="btn btn-success btn-small">
                                                                    <i class="fas fa-times"></i> View
                                                                </a>
                                                                <button type="button" class="btn btn-danger btn-small cancel_btn" data-bs-toggle="modal" data-bs-target="#exampleModal" data-order-code="<?php echo $order_code;?>">
                                                                    Cancel
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- / Shopping cart table -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Add content for other tabs here -->
                </div>
            </div>
        </div>
    </div>
    
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
