<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
    session_start();
    include('includes/topbar.php'); 
    ?>
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
                                            <input type="checkbox" name="selected_item[]" class="form-check-input mt-4 cart-checkbox" data-pid="<?php echo $cartrow['pid']; ?>" value="<?php echo $cartrow['pid']; ?>">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="../admin/assets/img/<?php echo $cartrow['image'] ?>" class="img-fluid me-5 rounded-circle" style="width: 80px; height: 80px;" alt="<?php echo $cartrow['pname']; ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <p class="mb-0 mt-4"><?php echo $cartrow['pname']; ?></p>
                                        </td>
                                        <td>
                                            <p class="mb-0 mt-4"><?php echo $cartrow['price']; ?> </p>
                                        </td>
                                        <td>
                                            <div class="input-group quantity mt-4" style="width: 100px;">
                                                <div class="input-group-btn">
                                                    <button class="btn btn-sm btn-minus rounded-circle bg-light border btn-minus" data-pid="<?php echo $cartrow['pid']; ?>">
                                                        <i class="fa fa-minus"></i>
                                                    </button>
                                                </div>
                                                <input type="text" class="form-control form-control-sm text-center border-0 input-value"
                                                value="<?php echo $cartrow['qty']; ?>"
                                                data-pid="<?php echo $cartrow['pid']; ?>"
                                                data-qty="<?php echo $cartrow['qty']; ?>">
                                                <div class="input-group-btn">
                                                    <button class="btn btn-sm btn-plus rounded-circle bg-light border btn-plus" data-pid="<?php echo $cartrow['pid']; ?>">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-md rounded-circle bg-light border mt-4 btn-remove" data-pid="<?php echo $cartrow['pid']; ?>">
                                                <i class="fa fa-times text-danger"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php 
                                }
                            } else {
                                ?> 
                                <div style="text-align: center; margin-top: 20px;">
                                    <h3>Your Shopping Cart is Empty</h3>
                                    <p>Start adding items to your cart from our <a href="shop.php">shop</a>.</p>
                                    <a href="shop.php">
                                        <button type="submit" style="padding: 10px 20px; background-color: #007bff; color: #fff; border: none; cursor: pointer;">Go to Shop</button>
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
                                <tbody id="cart-table-body">
                                    <!-- Table rows will be dynamically added here -->
                                </tbody>
                            </table>

                            <div>
                                <label style="margin-left: 10px">Subtotal: </label>
                                <label id="subtotal" style="margin-left: 10px"></label>
                            </div>
                            <div>
                                <?php
                                if(isset($_POST['btnCheck'])){
                                    $selected_pid = explode(',', $_POST['selected_pid']);
                                    $selected_pid_str = implode(',', $selected_pid);
                                    $url = "checkout.php?selected_pid=" . $selected_pid_str;
                                    echo "<script>window.location.href='" . $url . "'</script>";
                                    exit();
                                }
                                ?>
                                <br><br>
                                <form action="" method="POST" id="cart">
                                    <input type="hidden" name="selected_pid" id="selected-pid" value="">
                                    <button class="btn btn-success btn-checkout" name="btnCheck">Check out</button>
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

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>

    <!-- Custom Scripts -->
    <script>
        function updateHiddenInput() {
            var checkboxes = document.querySelectorAll("input[name='selected_item[]']");
            var selectedValues = Array.from(checkboxes)
                                     .filter(checkbox => checkbox.checked)
                                     .map(checkbox => checkbox.value);
            var hiddenInput = document.querySelector("input[name='selected_pid']");
            hiddenInput.value = selectedValues.join(',');
        }

        document.querySelectorAll("input[name='selected_item[]']").forEach(checkbox => {
            checkbox.addEventListener('change', updateHiddenInput);
        });

        $(document).ready(function(){
            $('.cart-checkbox').click(function(){
                var selectedPids = $('.cart-checkbox:checked').map(function(){
                    return $(this).data('pid');
                }).get();

                // Update hidden input value
                $('#selected-pid').val(selectedPids.join(','));

                // Update table dynamically
                $.ajax({
                    url: 'cart_price.php',
                    type: 'POST',
                    data: {
                        selected_pid: selectedPids.join(',')
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        $('#cart-table-body').empty();
                        var subtotal = 0;
                        $.each(data.items, function(index, item) {
                            var row = '<tr>' +
                                        '<td>' + item.item_name + '</td>' +
                                        '<td>' + item.qty + '</td>' +
                                        '<td>' + item.price + '</td>' +
                                     '</tr>';
                            $('#cart-table-body').append(row);
                            subtotal += (item.price * item.qty);
                        });
                        $('#subtotal').text('₱' + subtotal.toFixed(2));
                    }
                });
            });

            $('.btn-minus').click(function(){
                var pid = $(this).data('pid');

                $.ajax({
                    url: 'btn_minus.php',
                    type: 'POST',
                    data: {
                        pid: pid,
                    },
                    success: function() {
                        location.reload();
                    }
                });
            });

            $('.input-value').change(function(){
                var pid = $(this).data('pid');
                var qty = $(this).val();

                $.ajax({
                    url: 'input_value.php',
                    type: 'POST',
                    data: {
                        pid: pid,
                        qty: qty
                    },
                    success: function(response) {
                        console.log('Quantity updated successfully.');
                        location.reload();
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
                    data: {
                        pid: pid,
                    },
                    success: function() {
                        location.reload();
                    }
                });
            });

            $('.btn-remove').click(function(){
                var pid = $(this).data('pid');

                $.ajax({
                    url: 'remove_item.php',
                    type: 'POST',
                    data: {
                        pid: pid
                    },
                    success: function(response) {
                        console.log('Item removed successfully.');
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error removing item:', error);
                    }
                });
            });
        });
    </script>
</body>
</html>
