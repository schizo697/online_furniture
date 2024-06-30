<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('includes/topbar.php'); ?>
</head>

<body>

    <!-- Single Page Header start -->
    <div class="container-fluid page-header py-5" style="background-color: #4CAF50;">
        <h1 class="text-center text-white display-6">View Product</h1>
    </div>
    <!-- Single Page Header End -->

    <!-- Main -->
    <!-- Single Product Start -->
    <div class="container-fluid py-5 mt-5">
        <div class="container py-5">
            <div class="row g-4 mb-5">
                <div class="col-lg-8 col-xl-9">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="border rounded">
                                <a href="#">
                                    <img src="img/couch.jpg" class="img-fluid rounded" alt="Image">
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <h4 class="fw-bold mb-3">Customize Couch</h4>
                            <p class="mb-3">Category: Couch</p>
                            <h5 class="fw-bold mb-3" style="font-size: 2rem; color: #FF5722;">₱4.99</h5>
                         
                            <p class="mb-4">The generated Lorem Ipsum is therefore always free from repetition injected humour, or non-characteristic words etc.</p>

                            <!-- Color Options Start -->
                            <div class="mb-4">
                                <label for="colorOptions" class="form-label">Color Options:</label>
                                <select class="form-select" id="colorOptions">
                                    <option selected>Select Color</option>
                                    <option value="1">Red</option>
                                    <option value="2">Blue</option>
                                    <option value="3">Green</option>
                                    <option value="4">Yellow</option>
                                </select>
                            </div>
                            <!-- Color Options End -->

                            <!-- Size Options Start -->
                            <div class="row mb-4">
                                <div class="col">
                                    <label for="widthInput" class="form-label">Width (cm):</label>
                                    <input type="text" class="form-control" id="widthInput" placeholder="Enter width">
                                </div>
                                <div class="col">
                                    <label for="heightInput" class="form-label">Height (cm):</label>
                                    <input type="text" class="form-control" id="heightInput" placeholder="Enter height">
                                </div>
                            </div>
                            <!-- Size Options End -->

                            <div class="input-group quantity mb-5" style="width: 100px;">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-minus rounded-circle bg-light border">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                </div>
                                <input type="text" class="form-control form-control-sm text-center border-0" value="1">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-plus rounded-circle bg-light border">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Single Product End -->

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
