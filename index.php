<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('includes/topbar.php'); ?>
    <?php include('includes/header.php'); ?>
    <style>
        .product-item {
            position: relative;
            border: 1px solid #ced4da;
            border-radius: 10px;
            overflow: hidden;
        }

        .product-item .product-img img {
            width: 100%;
            height: 200px; /* Adjust height as needed */
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
                            include "conn.php";
                            
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
                                            <img src="<?php echo "admin/assets/img/".$row['image']; ?>" class="img-fluid w-100 rounded-top" alt="">
                                        </div>
                                        <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;"><?php echo $row['type']; ?></div>
                                        <div class="p-4 border-top-0 rounded-bottom">
                                            <h4><?php echo $row['pname']; ?></h4>
                                            <!-- <p><?php echo $row['description']; ?></p> -->
                                            <p class="text-dark fs-5 fw-bold mb-2">₱<?php echo $row['price']; ?></p>
                                            <div class="button-group">
                                                <a href = "login.php">
                                                <button class="btn border border-secondary rounded-pill px-3 text-primary add-to-cart">
                                                    <i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart
                                                </button>
                                                </a>
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
                        while($productRow = mysqli_fetch_assoc($fetchProductsByType)) {
                            echo '<div class="col-md-6 col-lg-4 col-xl-3">';  
                            echo '<div class="rounded border border-secondary position-relative product-item text-center">';
                            echo '<div class="product-img">';
                            echo '<img src="../admin/assets/img/'.$productRow['image'].'" class="img-fluid w-100 rounded-top" alt="">';
                            echo '</div>';
                            echo '<div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;">'.$productRow['type'].'</div>';
                            echo '<div class="p-4 border-top-0 rounded-bottom">';
                            echo '<h4>'.$productRow['pname'].'</h4>';
                            // Add description if needed
                            echo '<p class="text-dark fs-5 fw-bold mb-2">₱'.$productRow['price'].'</p>';
                            echo '<div class="button-group">';
                            echo '<button class="btn border border-secondary rounded-pill px-3 text-primary add-to-cart" data-pid="'.$productRow['pid'].'">';
                            echo '<i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart';
                            echo '</button>';
                            echo '<a href="view_product.php?id='.$productRow['pid'].'" class="btn border border-secondary rounded-pill px-3 text-primary">';
                            echo '<i class="fa fa-eye me-2 text-primary"></i> View';
                            echo '</a>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.add-to-cart').click(function(){
                var pid = $(this).data('pid');

                $.ajax({
                    url: 'customer/add_to_cart.php',
                    type: 'POST',
                    data: {
                        pid: pid,
                    },
                    success: function(response){
                        var result = JSON.parse(response);
                        if (result.status === 'not_logged_in') {
                            window.location.href = 'login.php';
                        } else if (result.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                text: result.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                text: result.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    },
                    error: function(xhr, status, error){
                        console.error('AJAX Error: ' + status + ' ' + error);
                        console.error(xhr);
                    }
                });
            });
        });
    </script>
</body>
</html>
