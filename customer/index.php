<!DOCTYPE html>
<html lang="en">
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


        <!-- Featurs Section Start 
        <div class="container-fluid featurs py-5">
            <div class="container py-5">
                <div class="row g-4">
                    <div class="col-md-6 col-lg-3">
                        <div class="featurs-item text-center rounded bg-light p-4">
                            <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                                <i class="fas fa-car-side fa-3x text-white"></i>
                            </div>
                            <div class="featurs-content text-center">
                                <h5>Free Shipping</h5>
                                <p class="mb-0">Free on order over $300</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="featurs-item text-center rounded bg-light p-4">
                            <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                                <i class="fas fa-user-shield fa-3x text-white"></i>
                            </div>
                            <div class="featurs-content text-center">
                                <h5>Security Payment</h5>
                                <p class="mb-0">100% security payment</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="featurs-item text-center rounded bg-light p-4">
                            <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                                <i class="fas fa-exchange-alt fa-3x text-white"></i>
                            </div>
                            <div class="featurs-content text-center">
                                <h5>30 Day Return</h5>
                                <p class="mb-0">30 day money guarantee</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="featurs-item text-center rounded bg-light p-4">
                            <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                                <i class="fa fa-phone-alt fa-3x text-white"></i>
                            </div>
                            <div class="featurs-content text-center">
                                <h5>24/7 Support</h5>
                                <p class="mb-0">Support every time fast</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         Featurs Section End -->


      <!-- Products Shop Start -->
<div class="container-fluid product py-5">
    <div class="container py-5">
        <div class="tab-class text-center">
            <div class="row g-4">
                <div class="col-lg-4 text-start">
                    <h1>Our Products</h1>
                </div>
                <div class="col-lg-8 text-end">
                    <ul class="nav nav-pills d-inline-flex text-center mb-5">
                        <li class="nav-item">
                            <a class="d-flex m-2 py-2 bg-light rounded-pill active" data-bs-toggle="pill" href="#tab-1">
                                <span class="text-dark" style="width: 130px;">All Products</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="d-flex py-2 m-2 bg-light rounded-pill" data-bs-toggle="pill" href="#tab-2">
                                <span class="text-dark" style="width: 130px;">Bed</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="d-flex m-2 py-2 bg-light rounded-pill" data-bs-toggle="pill" href="#tab-3">
                                <span class="text-dark" style="width: 130px;">Chair</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="d-flex m-2 py-2 bg-light rounded-pill" data-bs-toggle="pill" href="#tab-4">
                                <span class="text-dark" style="width: 130px;">Cabinet</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="d-flex m-2 py-2 bg-light rounded-pill" data-bs-toggle="pill" href="#tab-5">
                                <span class="text-dark" style="width: 130px;">Couch</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="tab-content">
                <div id="tab-1" class="tab-pane fade show p-0 active">
                    <div class="row g-4">
                    <?php 
                                include "../conn.php";
                                
                                $getlisting = "SELECT * FROM product 
                                                JOIN furniture_type ON furniture_type.fid = product.fid 
                                                WHERE product.status = 'Active'";
                                $fetch = $conn->query($getlisting);
                            ?>               
                            <?php 
                                while($row = mysqli_fetch_assoc($fetch)){ 
                            ?>
                        <div class="col-md-6 col-lg-4 col-xl-3">  
                            <div class="rounded border border-secondary position-relative product-item text-center">
                                <div class="product-img">
                                    <img src="<?php echo "../admin/assets/img/".$row['image']; ?>" class="img-fluid w-100 rounded-top" alt="">
                                </div>
                                <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;">Couch</div>
                                <div class="p-4 border-top-0 rounded-bottom">
                                    <h4><?php echo $row['pname']; ?></h4>
                                    <p><?php echo $row['description']; ?></p>
                                    <p class="text-dark fs-5 fw-bold mb-2">₱<?php echo $row['price']; ?></p>
                                    <div class="button-group">
                                            <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                                            <a href="view_product.php" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-eye me-2 text-primary"></i> View</a>
                                        </div>
                                </div>
                            </div>
                        </div>
                         <?php } ?>
                    </div>
                </div>
                <!-- Additional tabs (e.g., tab-2, tab-3) would follow the same structure -->
            </div>
        </div>
    </div>
</div>
<!-- Products Shop End -->


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
