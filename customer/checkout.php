<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('includes/topbar.php'); ?>
    <style>
    <?php include('css/checkout.css'); ?>
    </style>  
</head>
<body>

<!-- Page Header -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Checkout</h1>
</div>
<!-- End Page Header -->

<!-- Main Content -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.3.45/css/materialdesignicons.css" integrity="sha256-NAxhqDvtY0l4xn+YVa6WjAcmd94NNfttjNsDmNatFVc=" crossorigin="anonymous" />
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<br>
<div class="container">

    <div class="row">
        <div class="col-xl-8">

            <div class="card">
                <div class="card-body">
                    <ol class="activity-checkout mb-0 px-4 mt-3">
                        <li class="checkout-item">
                            <div class="avatar checkout-icon p-1">
                                <div class="avatar-title rounded-circle bg-primary">
                                    <i class="bx bxs-receipt text-white font-size-20"></i>
                                </div>
                            </div>
                            <div class="feed-item-list">
                                <div>
                                    <?php 
                                    if(isset($_SESSION['uid'])){
                                        $user_id = $_SESSION['uid'];
                                        
                                        $sql = "SELECT userinfo.firstname, userinfo.lastname, userinfo.email, userinfo.gender, userinfo.contact, userinfo.address, userinfo.city, userinfo.postal, useraccount.username FROM userinfo
                                        JOIN useraccount ON userinfo.infoid = useraccount.infoid
                                        WHERE userinfo.infoid = '$user_id'";
                                        $result = mysqli_query($conn, $sql);

                                        if($result && mysqli_num_rows($result) > 0){
                                            $row = mysqli_fetch_assoc($result);
                                            ?> 
                                                <h5 class="font-size-16 mb-1">Billing Info</h5>
                                                <p class="text-muted text-truncate mb-4">Set your address</p>
                                                <div class="mb-3">
                                                <?php 
                                                include('../conn.php'); // Include database connection

                                                // Check if the user is logged in
                                                if (!isset($_SESSION['uid'])) {
                                                    header("Location: ../login.php");
                                                    exit();
                                                }

                                                if (isset($_POST['btnUpdate'])) {
                                                    $uid = $_SESSION['uid'];
                                                    $firstname = $_POST['firstname'];
                                                    $lastname = $_POST['lastname'];
                                                    $email = $_POST['email'];
                                                    $contact = $_POST['contact'];
                                                    $address = $_POST['address'];
                                                    $city = $_POST['city'];
                                                    $postal = $_POST['postal'];
                                                    
                                                    $update_sql = "UPDATE userinfo SET firstname='$firstname', lastname='$lastname',email='$email', contact='$contact', address='$address' WHERE infoid='$uid'";
                                                    
                                                    if (mysqli_query($conn, $update_sql)) {
                                                        echo "<script>alert('Profile updated successfully.');</script>";
                                                    } else {
                                                        echo "<script>alert('Error updating profile.');</script>";
                                                    }
                                                }
                                                ?>
                                                <form action="" method="POST">
                                                    <div>
                                                        <div class="row">
                                                            <div class="col-lg-4">
                                                                <div class="mb-3">
                                                                    <label class="form-label" for="billing-name">First Name <span style="color: red;">*</span></label>
                                                                    <input class="form-control" name="firstname" type="text" placeholder="Enter your name" value="<?php echo $row['firstname']?>" required >
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label" for="billing-email-address">Email Address</label>
                                                                    <input type="email" class="form-control" name="email" id="billing-email-address" placeholder="Enter email" value="<?php echo $row['email']; ?>"  required>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="mb-3">
                                                                    <label class="form-label" for="billing-name">Last Name <span style="color: red;">*</span></label>
                                                                    <input class="form-control" name="lastname" type="text" placeholder="Enter your name" value="<?php echo $row['lastname']?>" required >
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label" for="billing-phone">Phone <span style="color: red;">*</span></label>
                                                                    <input type="text" class="form-control" name="contact"  id="billing-phone" placeholder="Enter Phone no." value="<?php echo $row['contact'] ?> "required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label" for="billing-address">Address (Street, Barangay) <span style="color: red;">*</span></label>
                                                            <input type="text" class="form-control" name="address" id="billing-address" placeholder="Enter full address" value="<?php echo $row['address'] ?> "required>                                                
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-4">
                                                                <div class="mb-4 mb-lg-0">
                                                                    <label class="form-label" for="billing-city">City <span style="color: red;">*</span></label>
                                                                    <input type="text" class="form-control" name="city" id="billing-city" placeholder="Enter City" value="<?php echo $row['city']; ?>" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="mb-0">
                                                                    <label class="form-label" for="zip-code">Zip / Postal code <span style="color: red;">*</span></label>
                                                                    <input type="text" class="form-control" name="postal" id="zip-code" placeholder="Enter Postal code" value="<?php echo $row['postal'] ?>" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <button class="btn btn-primary btn-sm" name="btnUpdate" style="margin-left: 45%">Update</button>
                                                </form>
                                                <?php
                                        }
                                    }
                                    ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                 
                        <li class="checkout-item">
                            <div class="avatar checkout-icon p-1">
                                <div class="avatar-title rounded-circle bg-primary">
                                    <i class="bx bxs-wallet-alt text-white font-size-20"></i>
                                </div>
                            </div>
                            <div class="feed-item-list">
                                <div>
                                    <h5 class="font-size-16 mb-1">Payment Info</h5>
                                    <p class="text-muted text-truncate mb-4">Choose payment method</p>
                                </div>
                                <div>
                                    <h5 class="font-size-14 mb-3">Payment method : <span style="color: red;">*</span></h5>
                                    <form action="" method="POST" id="payment-method" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6">
                                                <div data-bs-toggle="collapse">
                                                    <label class="card-radio-label">
                                                        <input type="radio" name="pay-method" id="pay-methodoption1" class="card-radio-input" value="gcash">
                                                        <span class="card-radio py-3 text-center text-truncate">
                                                            <i class="bx bx-wallet d-block h2 mb-3"></i>
                                                            Gcash
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6">
                                                <div>
                                                    <label class="card-radio-label">
                                                        <input type="radio" name="pay-method" id="pay-methodoption3" class="card-radio-input" value="cod" checked="">
                                                        <span class="card-radio py-3 text-center text-truncate">
                                                            <i class="bx bx-money d-block h2 mb-3"></i>
                                                            <span>Cash on Delivery</span>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="gcash-upload" style="display:none;">
                                            <label class="form-label" for="gcash-receipt">Send to this number and Upload Gcash Receipt</label>
                                            <input type="file" class="form-control" id="gcash-receipt" name="gcash-receipt">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </li>
                    </ol>
                </div>
            </div>

                        <div class="row my-4">
                            <div class="col">
                                <a href="ecommerce-products.html" class="btn btn-link text-muted">
                                    <i class="mdi mdi-arrow-left me-1"></i> Continue Shopping </a>
                            </div> <!-- end col -->
                            <div class="col">
                                <div class="text-end mt-2 mt-sm-0">
                                    <a href="#" class="btn btn-success">
                                        <i class="mdi mdi-cart-outline me-1"></i> Place Order </a>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row-->
                    </div>
                    <div class="col-xl-4">
                        <div class="card checkout-order-summary">
                            <div class="card-body">
                                <div class="p-3 bg-light mb-3">
                                    <h5 class="font-size-16 mb-0">Order Summary <span class="float-end ms-2">#MN0124</span></h5>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-centered mb-0 table-nowrap">
                                        <thead>
                                <tr>
                                    <th class="border-top-0" style="width: 110px;" scope="col">Product</th>
                                    <th class="border-top-0" scope="col">Product Desc</th>
                                    <th class="border-top-0" scope="col">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                            if(isset($_GET['selected_pid']) && isset($_SESSION['uid'])){
                                $uid = $_SESSION['uid'];
                                $pid = $_GET['selected_pid'];

                                // Ensure $pid is a valid comma-separated list
                                $pid_list = implode(',', array_map('intval', explode(',', $pid)));

                                // selected pid = 0
                                if(empty($pid_list)){
                                    $url = "cart.php";
                                    echo "<script>window.location.href= '$url'</script>";
                                    exit();
                                } else {
                                    $order = "SELECT * FROM cart 
                                            JOIN furniture ON cart.pid = furniture.pid 
                                            WHERE cart.pid IN ($pid_list) AND cart.uid = '$uid'";
                                    $order_res = mysqli_query($conn, $order);

                                    $subtotal = 0; 

                                    if($order_res && mysqli_num_rows($order_res) > 0){
                                        while($order_row = mysqli_fetch_assoc($order_res)){
                                            $pname = $order_row['pname'];
                                            $qty = $order_row['qty'];
                                            $price = $order_row['price'];
                                            $total = $price * $qty;
                                            $subtotal += $total; // Add to subtotal
                                            ?>
                                            <tr>
                                                <th scope="row"><img src="https://www.bootdey.com/image/280x280/FF00FF/000000" alt="product-img" title="product-img" class="avatar-lg rounded"></th>
                                                <td>
                                                    <h5 class="font-size-16 text-truncate"><a href="#" class="text-dark"><?php echo $pname; ?></a></h5>
                                                    <p class="text-muted mb-0">
                                                        <i class="bx bxs-star text-warning"></i>
                                                        <i class="bx bxs-star text-warning"></i>
                                                        <i class="bx bxs-star text-warning"></i>
                                                        <i class="bx bxs-star text-warning"></i>
                                                        <i class="bx bxs-star-half text-warning"></i>
                                                    </p>
                                                    <p class="text-muted mb-0 mt-1">₱<?php echo $price . ' * ' . $qty;?></p>
                                                </td>
                                                <td>₱<?php echo $total; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                }
                            }
                            ?>
                            <tr>
                                <td colspan="2">
                                    <h5 class="font-size-14 m-0">Sub Total :</h5>
                                </td>
                                <td>
                                    ₱<?php echo $subtotal; ?>
                                </td>
                            </tr>
                            <?php
                            $cityquery = "SELECT city FROM userinfo WHERE infoid = '$uid'";
                            $cityres = mysqli_query($conn, $cityquery);

                            if($cityres && mysqli_num_rows($cityres) > 0){
                                $cityrow = mysqli_fetch_assoc($cityres);
                                $city = $cityrow['city'];
                                
                                if($city != 'General Santos City' && $city != 'Gensan'){
                                    ?>
                                    <tr>
                                        <td colspan="2">
                                            <h5 class="font-size-14 m-0">Shipping Charge :</h5>
                                        </td>
                                        <td>
                                            ₱100
                                        </td>
                                    </tr>
                                    <tr class="bg-light">
                                        <td colspan="2">
                                            <h5 class="font-size-14 m-0">Total:</h5>
                                        </td>
                                        <td>
                                            ₱<?php echo $subtotal + 100; ?>
                                        </td>
                                    </tr>
                                    <form action="" method="POST" id="order-summary">
                                    <input type="text" name="pid" value="<?php echo $pid; ?>">
                                    <input type="text" name="uid" value ="<?php echo $uid;?>">
                                    <input type="text" name="qty" value ="<?php echo $qty; ?>">
                                    <input type="text" name="total" value ="<?php echo $subtotal; + 100?>">
                                    </form>
                                    <?php
                                } else{
                                    ?>
                                    <tr>
                                        <td colspan="2">
                                            <h5 class="font-size-14 m-0">Shipping Charge :</h5>
                                        </td>
                                        <td>
                                            ₱0
                                        </td>
                                    </tr>
                                    <tr class="bg-light">
                                        <td colspan="2">
                                            <h5 class="font-size-14 m-0">Total:</h5>
                                        </td>
                                        <td>
                                            ₱<?php echo $subtotal; ?>
                                        </td>
                                    </tr>
                                    <form action="" method="POST" id="order-summary">
                                    <input type="text" name="pid" value="<?php echo $pid; ?>">
                                    <input type="text" name="uid" value ="<?php echo $uid;?>">
                                    <input type="text" name="qty" value ="<?php echo $qty; ?>">
                                    <input type="text" name="total" value ="<?php echo $subtotal;?>">
                                    </form>
                                    <?php
                                }                                
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->
    
</div>
<!-- End Main Content -->

<?php include('includes/footer.php'); ?>

<!-- Back to Top Button -->
<a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>

<!-- JavaScript Libraries -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="lib/easing/easing.min.js"></script>
<script src="lib/waypoints/waypoints.min.js"></script>
<script src="lib/lightbox/js/lightbox.min.js"></script>
<script src="lib/owlcarousel/owl.carousel.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Template Javascript -->
<script src="js/main.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const gcashOption = document.getElementById('pay-methodoption1');
        const codOption = document.getElementById('pay-methodoption3');
        const gcashUpload = document.getElementById('gcash-upload');

        function toggleGcashUpload() {
            if (gcashOption.checked) {
                gcashUpload.style.display = 'block';
            } else {
                gcashUpload.style.display = 'none';
            }
        }

        gcashOption.addEventListener('change', toggleGcashUpload);
        codOption.addEventListener('change', toggleGcashUpload);

        // Initial check in case the page loads with Gcash selected
        toggleGcashUpload();
    });
</script>

</body>
</html>
