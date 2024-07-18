<?php
session_start();
include('../conn.php'); // Include database connection

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
    <!-- Page Header -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Return/Refund</h1>
    </div>
    <!-- End Page Header -->

    <!-- Main Content -->
    <div class="container-xl px-4 mt-4">
        <?php
        if (isset($_GET['order_code'])) {
            $order_code = $_GET['order_code'];
            $query = "SELECT orders.*, furniture.pname, furniture.price, furniture.image FROM orders
                      JOIN furniture ON orders.pid = furniture.pid
                      WHERE orders.order_code = ? AND orders.uid = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $order_code, $_SESSION['uid']);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $order = $result->fetch_assoc();
        ?>
        <div class="row">
            <div class="col-md-6">
                <img src="../admin/assets/img/<?php echo $order['image']; ?>" class="img-fluid" alt="Product Image">
            </div>
            <div class="col-md-6">
                <h3><?php echo $order['pname']; ?></h3>
                <p>Price: ₱<?php echo $order['price']; ?></p>
                <p>Quantity: <?php echo $order['qty']; ?></p>
            </div>
        </div>

        <form action="submit_return.php" method="post" enctype="multipart/form-data" class="mt-4">
            <input type="hidden" name="order_code" value="<?php echo $order_code; ?>">
            <div class="mb-3">
                <label for="reason" class="form-label">Reason for Return</label>
                <select class="form-control" id="reason" name="reason" required>
                    <option value="">Select a reason</option>
                    <option value="damaged">Damaged</option>
                    <option value="not_as_described">Not as described</option>
                    <option value="wrong_item">Wrong item</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Upload Image</label>
                <input type="file" class="form-control" id="image" name="image">
            </div>
            <button type="submit" class="btn btn-success">Submit Return Request</button>
        </form>

         <!-- Refund Policy Section -->
         <div class="mt-5">
            <h2>Refund Policy for Furniture</h2>
            <p>We offer a 30-day return policy for all furniture items. If you are not satisfied with your purchase, you can return the item within 30 days from the date of delivery for a full refund or exchange.</p>
            <p>To be eligible for a return, your furniture must be unused, in the same condition that you received it, and in the original packaging. You will also need to provide a receipt or proof of purchase.</p>
            <p>Non-returnable items include:</p>
            <ul>
                <li>Customized or personalized furniture</li>
                <li>Clearance or final sale items</li>
                <li>Furniture that has been assembled or modified</li>
            </ul>
            <p>Refunds will be processed once the returned item has been received and inspected. You will be notified via email regarding the status of your refund. Please note that it may take some time for the refund to appear on your original payment method.</p>
            <p>If your furniture arrives damaged or defective, please contact us immediately with photos of the damage. We will arrange for a replacement or a full refund.</p>
            <p>For any questions or concerns about our refund policy, please contact our customer service team.</p>
        </div>
        <!-- End Refund Policy Section -->

        <?php
            } else {
                echo '<div class="alert alert-danger">Order not found or you do not have permission to view this order.</div>';
            }
            $stmt->close();
        } else {
            echo '<div class="alert alert-danger">No order code provided.</div>';
        }
        ?>
    </div>
    <!-- End Main Content -->

    <?php include('includes/footer.php'); ?>
    
    <!-- Back to Top Button -->
    <a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>
</html>
