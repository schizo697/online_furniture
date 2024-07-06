<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('includes/topbar.php'); ?>
</head>
<body>
    <!-- Single Page Header start -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Cart</h1>
    </div>
    <!-- Single Page Header End -->

    <!-- Cart Page Start -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Select</th>
                            <th scope="col">Products</th>
                            <th scope="col">Name</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        include ('../conn.php');
                        if(isset($_SESSION['uid'])){
                            $uid = mysqli_real_escape_string($conn, $_SESSION['uid']);
                            $cart = "SELECT * FROM furniture JOIN cart ON furniture.pid = cart.pid WHERE cart.uid = '$uid' AND cart.qty > 0";
                            $cartres = mysqli_query($conn, $cart);

                            if($cartres && mysqli_num_rows($cartres) > 0){
                                while($cartrow = mysqli_fetch_assoc($cartres)){
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="form-check-input mt-4 item-checkbox" data-pid="<?php echo htmlspecialchars($cartrow['pid']); ?>" data-price="<?php echo htmlspecialchars($cartrow['price'] * $cartrow['qty']); ?>">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="../admin/assets/img/<?php echo htmlspecialchars($cartrow['image']); ?>" class="img-fluid me-5 rounded-circle" style="width: 80px; height: 80px;" alt="<?php echo htmlspecialchars($cartrow['pname']); ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <p class="mb-0 mt-4"><?php echo htmlspecialchars($cartrow['pname']); ?></p>
                                        </td>
                                        <td>
                                            <p class="mb-0 mt-4"><?php echo htmlspecialchars($cartrow['price']); ?></p>
                                        </td>
                                        <td>
                                            <div class="input-group quantity mt-4" style="width: 100px;">
                                                <div class="input-group-btn">
                                                    <button class="btn btn-sm btn-minus rounded-circle bg-light border" data-pid="<?php echo htmlspecialchars($cartrow['pid']); ?>">
                                                        <i class="fa fa-minus"></i>
                                                    </button>
                                                </div>
                                                <input type="text" class="form-control form-control-sm text-center border-0 input-value" value="<?php echo htmlspecialchars($cartrow['qty']); ?>" data-pid="<?php echo htmlspecialchars($cartrow['pid']); ?>" data-qty="<?php echo htmlspecialchars($cartrow['qty']); ?>">
                                                <div class="input-group-btn">
                                                    <button class="btn btn-sm btn-plus rounded-circle bg-light border" data-pid="<?php echo htmlspecialchars($cartrow['pid']); ?>">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-md rounded-circle bg-light border mt-4" onclick="removeFromCart(<?php echo htmlspecialchars($cartrow['pid']); ?>)">
                                                <i class="fa fa-times text-danger"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php 
                                }
                            } else {
                                ?> 
                                <div class="text-center mt-4">
                                    <h3>Your Shopping Cart is Empty</h3>
                                    <p>Start adding items to your cart from our <a href="shop.php">shop</a>.</p>
                                    <a href="shop.php">
                                        <button class="btn btn-primary">Go to Shop</button>
                                    </a>
                                </div>
                                <?php 
                            }
                        }
                        ?>
                    </tbody>
                </table>
                <!-- Cart Total Section Start -->
                <div class="d-flex justify-content-end mt-4">
                    <div class="card w-25">
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody id="cart-summary">
                                <?php
                                $total = 0;
                                $cart = "SELECT furniture.pname, cart.qty, furniture.price FROM furniture JOIN cart ON furniture.pid = cart.pid WHERE cart.uid = '$uid' AND cart.qty > 0";
                                $cartres = mysqli_query($conn, $cart);

                                if ($cartres && mysqli_num_rows($cartres) > 0) {
                                    while ($cartrow = mysqli_fetch_assoc($cartres)) {
                                        $price = $cartrow['price'];
                                        $pname = $cartrow['pname'];
                                        $qty = $cartrow['qty'];
                                        $total += $price * $qty;
                                        ?> 
                                        <tr>
                                            <td><?php echo htmlspecialchars($pname); ?></td>
                                            <td><?php echo htmlspecialchars($qty); ?></td>
                                            <td><?php echo htmlspecialchars($price); ?></td>
                                        </tr>
                                        <?php 
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="3">No items found in cart.</td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                            <div>
                                <label style="margin-left: 10px">Total:</label>
                                <label id="total-price" style="margin-left: 65%;"><?php echo htmlspecialchars($total); ?></label>
                                <form action="checkout.php" method="POST" id="checkout-form">
                                    <input type="hidden" name="selected_products" id="selected-products">
                                    <button type="submit" class="btn btn-success btn-checkout">Check out</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Cart Total Section End -->
            </div>
        </div>
    </div>
    <!-- Cart Page End -->

    <?php include('includes/footer.php'); ?>

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

    <script>
        $(document).ready(function(){
            $('.btn-minus').click(function(){
                var pid = $(this).data('pid');
                $.ajax({
                    url: 'btn_minus.php',
                    type: 'POST',
                    data: { pid: pid },
                    success: function(response) {
                        location.reload(); // Reload the page to update the cart view
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating quantity:', error);
                    }
                });
            });

            $('.btn-plus').click(function(){
                var pid = $(this).data('pid');
                $.ajax({
                    url: 'btn_plus.php',
                    type: 'POST',
                    data: { pid: pid },
                    success: function(response) {
                        location.reload(); // Reload the page to update the cart view
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating quantity:', error);
                    }
                });
            });

            $('.input-value').change(function(){
                var pid = $(this).data('pid');
                var qty = $(this).val();
                $.ajax({
                    url: 'input_value.php',
                    type: 'POST',
                    data: { pid: pid, qty: qty },
                    success: function(response) {
                        console.log('Quantity updated successfully.');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating quantity:', error);
                    }
                });
            });

            // Checkbox change event to update total
            $('.item-checkbox').change(function(){
                updateTotalPrice();
                updateSelectedProducts();
            });

            // Function to update total price
            function updateTotalPrice() {
                let total = 0;
                $('.item-checkbox:checked').each(function() {
                    total += parseFloat($(this).data('price'));
                });
                $('#total-price').text(total);
            }

            // Function to update selected products
            function updateSelectedProducts() {
                let selectedProducts = [];
                $('.item-checkbox:checked').each(function() {
                    selectedProducts.push($(this).data('pid'));
                });
                $('#selected-products').val(selectedProducts.join(','));
            }

            // Remove from cart function
            window.removeFromCart = function(pid) {
                $.ajax({
                    url: 'remove_item.php',
                    type: 'POST',
                    data: { pid: pid },
                    success: function(response) {
                        console.log('Item removed successfully.');
                        location.reload(); // Reload the page to update the cart view
                    },
                    error: function(xhr, status, error) {
                        console.error('Error removing item:', error);
                    }
                });
            }
        });
    </script>
</body>
</html>
