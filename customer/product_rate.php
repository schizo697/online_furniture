<?php 
session_start();
include('../conn.php'); // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    header("Location: ../login.php");
    exit();
}

$review_exists = false;

// Get the order code from the URL
if (isset($_GET['order_code'])) {
    $order_code = $_GET['order_code'];

    // Fetch order details
    $order_query = "SELECT furniture.image, furniture.pname, furniture.price, orders.qty, orders.total
                    FROM orders 
                    JOIN furniture ON orders.pid = furniture.pid
                    WHERE orders.order_code = '$order_code'";
    $order_res = mysqli_query($conn, $order_query);

    // Check if the user has already reviewed this product
    $uid = $_SESSION['uid'];
    $review_query = "SELECT * FROM product_rating WHERE uid = '$uid' AND order_code = '$order_code'";
    $review_res = mysqli_query($conn, $review_query);

    if (mysqli_num_rows($review_res) > 0) {
        $review_exists = true;
    }
}

// Submit Review
if (isset($_POST['submitreview']) && !$review_exists) {
    $rating = $_POST['rating'];
    $review = $_POST['review'];
    $uid = $_SESSION['uid'];
    $order_code = $_GET['order_code'];

    $getpid =  mysqli_query($conn, "SELECT *, furniture.pid FROM orders JOIN furniture ON orders.pid = furniture.pid WHERE orders.order_code = '$order_code'");

    if (mysqli_num_rows($getpid) > 0) {
        $row = mysqli_fetch_assoc($getpid);
        $_SESSION['pid'] = $row['pid'];
        $pid = $_SESSION['pid'];

        $sql = "INSERT INTO product_rating (pid, rating, review, uid, order_code) VALUES ('$pid', '$rating', '$review', '$uid', '$order_code')";
                             
        if(mysqli_query($conn, $sql)) {
            echo "<script>
                    alert('You have successfully rated this product!');
                    window.location.href = 'shop.php';
                  </script>";
            exit(); 
        } else {
            echo '<script>window.location.href="purchase.php?error=true";</script>';
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('includes/topbar.php');?>

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
        .rating input:checked ~ label {
            color: #f5b301;
        }
        .rating label:hover,
        .rating label:hover ~ label {
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
                                $total = $order_row['total'];
                        ?>
                        <div class="col-md-4">
                            <div class="card">
                                <img src="../admin/assets/img/<?php echo htmlspecialchars($image); ?>" class="card-img-top" alt="Product Image" style="max-width: 100%; height: auto;">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($product_name); ?></h5>
                                    <p class="card-text">Quantity: <?php echo htmlspecialchars($quantity); ?></p>
                                    <p class="card-text">Price: <?php echo htmlspecialchars($price); ?></p>
                                    <p class="card-text">Total: <?php echo htmlspecialchars($total); ?></p>
                                </div>
                            </div>
                        </div>
                        <?php
                            }
                        } else {
                            echo "<div class='col-12'><p>No order details found.</p></div>";
                        }
                        ?>

                        <div class="col-md-6">
                            <div class="review-form">
                                <h4>Rate Product</h4>
                                <?php if ($review_exists): ?>
                                    <p>You have already reviewed this product.</p>
                                <?php else: ?>
                                    <form id="reviewForm" method="POST">
                                        <div class="rating">
                                            <input type="radio" id="star5" name="rating" value="5"><label for="star5">★</label>
                                            <input type="radio" id="star4" name="rating" value="4"><label for="star4">★</label>
                                            <input type="radio" id="star3" name="rating" value="3"><label for="star3">★</label>
                                            <input type="radio" id="star2" name="rating" value="2"><label for="star2">★</label>
                                            <input type="radio" id="star1" name="rating" value="1"><label for="star1">★</label>
                                        </div>
                                        <textarea id="reviewText" name="review" class="form-control" rows="5" placeholder="Write your review here..." required></textarea>
                                        <br>
                                        <button class="btn btn-secondary" type="submit" name="submitreview">Submit</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
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
<a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="lib/easing/easing.min.js"></script>
<script src="lib/waypoints/waypoints.min.js"></script>
<script src="lib/lightbox/js/lightbox.min.js"></script>
<script src="lib/owlcarousel/owl.carousel.min.js"></script>

<!-- Template Javascript -->
<script src="main/js/main.js"></script>

</body>
</html>
