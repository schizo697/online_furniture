<?php
session_start();
include('../conn.php'); // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    header("Location: ../login.php");
    exit();
}

// Get the order code from the URL
if (isset($_GET['order_code'])) {
    $order_code = $_GET['order_code'];

    // Fetch order details
    $order_query = "SELECT furniture.image, furniture.pname, cart.total_price AS price, orders.qty, orders.total, cart.color, cart.width, cart.height, 
                    cart.length, cart.materials, cart.foot_part, cart.foam, cart.fabric, cart.spring, orders.osid
                        FROM orders 
                        JOIN cart ON orders.cid = cart.cid
                        JOIN furniture ON orders.pid = furniture.pid
                        WHERE orders.order_code = '$order_code'";
    $order_res = mysqli_query($conn, $order_query);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('includes/topbar.php'); ?>

    <style>
        .rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: left;
            margin: 10px 0;
        }

        .rating input {
            display: none;
        }

        .rating label {
            font-size: 2em;
            color: #ccc;
            cursor: pointer;
        }

        .rating input:checked~label {
            color: #f5b301;
        }

        .rating label:hover,
        .rating label:hover~label {
            color: #f5b301;
        }
    </style>
</head>

<body>

    <!-- Page Header -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">View Furniture Order</h1>
    </div>
    <!-- End Page Header -->
    <!-- Main Start -->
    <div class="container">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Order Details</h3>
                </div>
                <div class="ms-md-auto py-2 py-md-0"></div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Order Code: <?php echo htmlspecialchars($order_code); ?></h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php
                            if ($order_res && mysqli_num_rows($order_res) > 0) {
                                while ($order_row = mysqli_fetch_assoc($order_res)) {
                                    $image = $order_row['image'];
                                    $product_name = $order_row['pname'];
                                    $quantity = $order_row['qty'];
                                    $price = $order_row['price'];
                                    $color = $order_row['color'];
                                    $size = $order_row['width'] . " x " . $order_row['length'] . " x " . $order_row['height'];
                                    $material = $order_row['materials'] . ','. $order_row['foam'] . ','. $order_row['fabric']. ',' . $order_row['spring'];
                                    $foot = $order_row['foot_part'];
                                    ?>
                                    <div class="col-md-4">
                                        <div class="card">
                                            <img src="../admin/assets/img/<?php echo htmlspecialchars($image); ?>"
                                                class="card-img-top" alt="Product Image" style="max-width: 100%; height: auto;">
                                            <div class="card-body">
                                                <h5 class="card-title"><?php echo htmlspecialchars($product_name); ?></h5>
                                                <p class="card-text">Quantity: <?php echo htmlspecialchars($quantity); ?></p>
                                                <p class="card-text">Color: <?php echo htmlspecialchars($color); ?></p>
                                                <p class="card-text">Size: <?php echo htmlspecialchars($size); ?> Inches</p>
                                                <p class="card-text">Material: <?php echo htmlspecialchars($material); ?></p>
                                                <p class="card-text">Foot Part: <?php echo htmlspecialchars($foot); ?></p>
                                                <p class="card-text">Price: <?php echo htmlspecialchars($price); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                echo "<div class='col-12'><p>No order details found.</p></div>";
                            }
                            ?>
                        </div>
                        <div class="form-button-action mt-4">
                            <a href="purchase.php" class="btn btn-secondary">Back to Purchase</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Main Content -->



    <br><br><br>

    <?php include('includes/footer.php'); ?>

    <!-- Back to Top Button -->
    <a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i
            class="fa fa-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function showModal() {
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Your review has been submitted successfully!',
                showConfirmButton: false
            });
        }

        function checkExistParam() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('success') && urlParams.get('success') === 'true') {
                showModal();
            }
        }
        <script>

        </body>
</html >