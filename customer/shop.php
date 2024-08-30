<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('includes/topbar.php'); ?>

    <style>
        .product-item {
            position: relative;
            border: 1px solid #ced4da;
            border-radius: 10px;
            overflow: hidden;
        }

        .product-item .product-img img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px 10px 0 0;
        }

        .product-item .product-content {
            padding: 15px;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .product-item h4 {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .product-item p {
            font-size: 1rem;
            margin-bottom: 15px;
        }

        .product-item .btn {
            align-self: center;
        }

        .button-group {
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>
    <!-- Page Header -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Shop</h1>
    </div>
    <!-- End Page Header -->

    <!-- Products Shop Start -->
    <div class="container-fluid product py-5">
        <div class="container py-5">
            <div class="tab-class text-center">
                <div class="row g-4">
                    <div class="col-lg-4 text-start">
                        <h1>Our Products</h1>
                    </div>
                    <!-- TAB -->
                    <div class="col-lg-8 text-end">
                        <ul class="nav nav-pills d-inline-flex text-center mb-5">
                            <li class="nav-item">
                                <a class="d-flex m-2 py-2 bg-light rounded-pill active" data-bs-toggle="pill" href="#tab-1">
                                    <span class="text-dark" style="width: 130px;">All Products</span>
                                </a>
                            </li>
                            <?php
                            include "../conn.php";

                            // Fetch furniture types
                            $getTypesQuery = "SELECT DISTINCT type FROM furniture_type";
                            $fetchTypes = $conn->query($getTypesQuery);

                            // Display each type as a tab
                            while($typeRow = mysqli_fetch_assoc($fetchTypes)) {
                                $type = $typeRow['type'];
                                $tabID = strtolower(str_replace(' ', '-', $type)); // Generate tab ID from type

                                echo '<li class="nav-item">';
                                echo '<a class="d-flex m-2 py-2 bg-light rounded-pill" data-bs-toggle="pill" href="#'.$tabID.'">';
                                echo '<span class="text-dark" style="width: 130px;">'.$type.'</span>';
                                echo '</a>';
                                echo '</li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane fade show p-0 active">
                        <div class="row g-4">
                            <?php 
                            $getlisting = "SELECT * FROM furniture 
                                           JOIN furniture_type ON furniture.fid = furniture_type.fid WHERE furniture.status = 'Active'";
                            $fetch = $conn->query($getlisting);

                            while($row = mysqli_fetch_assoc($fetch)) { 
                            ?>
                                <div class="col-md-6 col-lg-4 col-xl-3">  
                                    <div class="rounded border border-secondary position-relative product-item text-center">
                                        <div class="product-img">
                                            <img src="<?php echo "../admin/assets/img/".$row['image']; ?>" class="img-fluid w-100 rounded-top" alt="">
                                        </div>
                                        <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;"><?php echo $row['type']; ?></div>
                                        <div class="p-4 border-top-0 rounded-bottom">
                                            <h4><?php echo $row['pname']; ?></h4>
                                            <p class="text-dark fs-5 fw-bold mb-2">₱<?php echo $row['price']; ?></p>
                                            <p class="text-dark fs-5 fw-bold mb-2">Available Stock: <?php echo $row['quantity']; ?></p>
                                            <div class="button-group">
                                                <button class="btn border border-secondary rounded-pill px-3 text-primary add-to-cart" data-pid="<?php echo $row['pid'];?>">
                                                    <i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart
                                                </button>
                                                <a href="view_product.php?id=<?php echo $row['pid']; ?>" class="btn border border-secondary rounded-pill px-3 text-primary">
                                                    <i class="fa fa-eye me-2 text-primary"></i> View
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- Additional tabs (based on furniture types) -->
                    <?php
                    // Reset the query to fetch again for tab content
                    $fetch->data_seek(0);

                    // Fetch furniture types again for separate queries per tab
                    $fetchTypes->data_seek(0);

                    while($typeRow = mysqli_fetch_assoc($fetchTypes)) {
                        $type = $typeRow['type'];
                        $tabID = strtolower(str_replace(' ', '-', $type)); // Generate tab ID from type

                        echo '<div id="'.$tabID.'" class="tab-pane fade show p-0">';
                        echo '<div class="row g-4">';

                        // Query to fetch products of specific type
                        $getProductsByType = "SELECT * FROM furniture 
                                             JOIN furniture_type ON furniture.fid = furniture_type.fid 
                                             WHERE furniture.status = 'Active' AND furniture_type.type = '$type'";
                        $fetchProductsByType = $conn->query($getProductsByType);

                        // Display products of this type
                        while ($productRow = mysqli_fetch_assoc($fetchProductsByType)) {
                            ?>
                            <div class="col-md-6 col-lg-4 col-xl-3">
                                <div class="rounded border border-secondary position-relative product-item text-center">
                                    <div class="product-img">
                                        <img src="../admin/assets/img/<?php echo $productRow['image']; ?>" class="img-fluid w-100 rounded-top" alt="">
                                    </div>
                                    <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;">
                                        <?php echo $productRow['type']; ?>
                                    </div>
                                    <div class="p-4 border-top-0 rounded-bottom">
                                        <h4><?php echo $productRow['pname']; ?></h4>
                                        <p class="text-dark fs-5 fw-bold mb-2">₱<?php echo $productRow['price']; ?></p>
                                        <p class="text-dark fs-5 fw-bold mb-2">Available Stock: <?php echo $productRow['quantity']; ?></p>
                                        <div class="button-group">
                                            <button class="btn border border-secondary rounded-pill px-3 text-primary add-to-cart" data-pid="<?php echo $productRow['pid']; ?>">
                                                <i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart
                                            </button>
                                            <a href="view_product.php?id=<?php echo $productRow['pid']; ?>" class="btn border border-secondary rounded-pill px-3 text-primary">
                                                <i class="fa fa-eye me-2 text-primary"></i> View
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Products Shop End -->

    <?php include('includes/footer.php'); ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            } 
            else if (urlParams.has('update') && urlParams.get('update') === 'true') {
            showAlert('success', 'Update Successfully!');
            }
            else if (urlParams.has('success') && urlParams.get('success') === 'false') {
                showAlert('error', 'Something went wrong!');
            }
        }
        window.onload = checkURLParams;
    </script>
    <script>
    $(document).ready(function(){
        $('.add-to-cart').click(function(){
            var pid = $(this).data('pid');

            Swal.fire({
                title: "Do you want to add this product to your cart?",
                text: " ",
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: "Add to cart",
                denyButtonText: `Customized`
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'add_to_cart.php',
                        type: 'POST',
                        data: {
                            pid: pid,
                        },
                        success: function(response){
                            Swal.fire({
                                icon: 'success',
                                text: 'Product added to cart.',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        },
                        error: function(xhr, status, error){
                            Swal.fire({
                                icon: 'error',
                                text: 'An error occurred. Please try again.',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            console.error('AJAX Error: ' + status + ' ' + error);
                        }
                    });
                } else if(result.isDenied) {
                    window.location.href="customize.php?pid=" + pid;
                }
            });
        });
    });
    </script>
</body>
</html>
