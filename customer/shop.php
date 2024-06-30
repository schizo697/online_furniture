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

<!-- Page Header -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Shop</h1>
</div>
<!-- End Page Header -->

<!-- Main Content -->
<div class="container-fluid product py-5">
    <div class="container py-5">
        <h1 class="mb-4">Furniture Shop</h1>
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="row g-4">
                    <div class="col-xl-3">
                        <div class="input-group w-100 mx-auto d-flex">
                            <input type="search" class="form-control p-3" placeholder="keywords" aria-describedby="search-icon-1">
                            <span id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
                    <div class="col-6"></div>
                    <div class="col-xl-3">
                        <div class="bg-light ps-3 py-3 rounded d-flex justify-content-between mb-4">
                            <label for="fruits">Default Sorting:</label>
                            <select id="fruits" name="fruitlist" class="border-0 form-select-sm bg-light me-3" form="fruitform">
                                <option value="volvo">Nothing</option>
                                <option value="saab">Popularity</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-lg-3">
                        <div class="row g-4">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <h4>Categories</h4>
                                    <ul class="list-unstyled product-categorie">
                                        <li>
                                            <div class="d-flex justify-content-between product-name">
                                                <a href="#">Couch</a>
                                                <span>(2)</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex justify-content-between product-name">
                                                <a href="#">Bed</a>
                                                <span>(5)</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex justify-content-between product-name">
                                                <a href="#">Chair</a>
                                                <span>(2)</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex justify-content-between product-name">
                                                <a href="#">Table</a>
                                                <span>(8)</span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="row g-4 justify-content-center">
                            <!-- Product Items -->
                            <div class="col-md-6 col-lg-6 col-xl-4">
                                <div class="rounded border border-secondary position-relative product-item text-center">
                                    <div class="product-img">
                                        <img src="img/couch.jpg" class="img-fluid w-100 rounded-top" alt="">
                                    </div>
                                    <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;">Couch</div>
                                    <div class="p-4 border-top-0 rounded-bottom">
                                        <h4>Customize Couch 1</h4>
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit sed do eiusmod te incididunt</p>
                                        <p class="text-dark fs-5 fw-bold mb-2">₱4.99</p>
                                        <div class="button-group">
                                            <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                                            <a href="view_product.php" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-eye me-2 text-primary"></i> View</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 col-lg-6 col-xl-4">
                                <div class="rounded border border-secondary position-relative product-item text-center">
                                    <div class="product-img">
                                        <img src="img/couch.jpg" class="img-fluid w-100 rounded-top" alt="">
                                    </div>
                                    <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;">Couch</div>
                                    <div class="p-4 border-top-0 rounded-bottom">
                                        <h4>Customize Couch 2</h4>
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit sed do eiusmod te incididunt</p>
                                        <p class="text-dark fs-5 fw-bold mb-2">₱4.99</p>
                                        <div class="button-group">
                                            <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                                            <a href="view_product.php" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-eye me-2 text-primary"></i> View</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-6 col-xl-4">
                                <div class="rounded border border-secondary position-relative product-item text-center">
                                    <div class="product-img">
                                        <img src="img/bed.jpg" class="img-fluid w-100 rounded-top" alt="">
                                    </div>
                                    <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;">Couch</div>
                                    <div class="p-4 border-top-0 rounded-bottom">
                                        <h4>Customize Bed 1</h4>
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit sed do eiusmod te incididunt</p>
                                        <p class="text-dark fs-5 fw-bold mb-2">₱4.99</p>
                                        <div class="button-group">
                                            <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                                            <a href="view_product.php" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-eye me-2 text-primary"></i> View</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-6 col-xl-4">
                                <div class="rounded border border-secondary position-relative product-item text-center">
                                    <div class="product-img">
                                        <img src="img/bed.jpg" class="img-fluid w-100 rounded-top" alt="">
                                    </div>
                                    <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;">Couch</div>
                                    <div class="p-4 border-top-0 rounded-bottom">
                                        <h4>Customize Bed 2</h4>
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit sed do eiusmod te incididunt</p>
                                        <p class="text-dark fs-5 fw-bold mb-2">₱4.99</p>
                                        <div class="button-group">
                                            <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                                            <a href="view_product.php" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-eye me-2 text-primary"></i> View</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-6 col-xl-4">
                                <div class="rounded border border-secondary position-relative product-item text-center">
                                    <div class="product-img">
                                        <img src="img/chair.jpg" class="img-fluid w-100 rounded-top" alt="">
                                    </div>
                                    <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;">Couch</div>
                                    <div class="p-4 border-top-0 rounded-bottom">
                                        <h4>Customize Chair 1</h4>
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit sed do eiusmod te incididunt</p>
                                        <p class="text-dark fs-5 fw-bold mb-2">₱4.99</p>
                                        <div class="button-group">
                                            <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                                            <a href="view_product.php" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-eye me-2 text-primary"></i> View</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-6 col-xl-4">
                                <div class="rounded border border-secondary position-relative product-item text-center">
                                    <div class="product-img">
                                        <img src="img/table.png" class="img-fluid w-100 rounded-top" alt="">
                                    </div>
                                    <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;">Couch</div>
                                    <div class="p-4 border-top-0 rounded-bottom">
                                        <h4>Customize Table 1</h4>
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit sed do eiusmod te incididunt</p>
                                        <p class="text-dark fs-5 fw-bold mb-2">₱4.99</p>
                                        <div class="button-group">
                                            <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                                            <a href="view_product.php" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-eye me-2 text-primary"></i> View</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-6 col-xl-4">
                                <div class="rounded border border-secondary position-relative product-item text-center">
                                    <div class="product-img">
                                        <img src="img/chair.jpg" class="img-fluid w-100 rounded-top" alt="">
                                    </div>
                                    <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;">Couch</div>
                                    <div class="p-4 border-top-0 rounded-bottom">
                                        <h4>Customize Chair 2</h4>
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit sed do eiusmod te incididunt</p>
                                        <p class="text-dark fs-5 fw-bold mb-2">₱4.99</p>
                                        <div class="button-group">
                                            <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                                            <a href="view_product.php" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-eye me-2 text-primary"></i> View</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-xl-4">
                                <div class="rounded border border-secondary position-relative product-item text-center">
                                    <div class="product-img">
                                        <img src="img/table.png" class="img-fluid w-100 rounded-top" alt="">
                                    </div>
                                    <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;">Couch</div>
                                    <div class="p-4 border-top-0 rounded-bottom">
                                        <h4>Customize Table 1</h4>
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit sed do eiusmod te incididunt</p>
                                        <p class="text-dark fs-5 fw-bold mb-2">₱4.99</p>
                                        <div class="button-group">
                                            <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                                            <a href="view_product.php" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-eye me-2 text-primary"></i> View</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- Pagination -->
                        <div class="col-12">
                            <div class="pagination d-flex justify-content-center mt-5">
                                <a href="#" class="rounded">&laquo;</a>
                                <a href="#" class="active rounded">1</a>
                                <a href="#" class="rounded">2</a>
                                <a href="#" class="rounded">3</a>
                                <a href="#" class="rounded">4</a>
                                <a href="#" class="rounded">5</a>
                                <a href="#" class="rounded">6</a>
                                <a href="#" class="rounded">&raquo;</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

<!-- Template Javascript -->
<script src="js/main.js"></script>

</body>
</html>
