<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('includes/topbar.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>

<body>

    <!-- Single Page Header start -->
    <div class="container-fluid page-header py-5" style="background-color: #4CAF50;">
        <h1 class="text-center text-white display-6">View Product</h1>
    </div>
    <!-- Single Page Header End -->

    <?php
    include "../conn.php";

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $stmt = $conn->prepare("SELECT furniture.*, furniture_type.type FROM furniture JOIN furniture_type ON furniture.fid = furniture_type.fid WHERE furniture.pid = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $color = $row['color'];
            $colors[$color] = $color;
            ?>
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
                                            <img src="<?php echo "../admin/assets/img/".$row['image']; ?>" class="img-fluid rounded" alt="Image">
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <h4 class="fw-bold mb-3"><?php echo $row['pname']; ?></h4>
                                    <p class="mb-3">Category: <?php echo $row['type']; ?></p>
                                    <h5 class="fw-bold mb-3" style="font-size: 2rem; color: #FF5722;">₱<?php echo $row['price']; ?></h5>
                                    <p class="mb-4"><?php echo $row['description']; ?></p>

                                    <!-- Color Options Start -->
                                    <div class="mb-4">
                                    <label for="colorOptions" class="form-label">Color Options:</label>
                                    <select class="form-select" id="colorOptions">
                                        <option selected>Select Color</option>
                                        <?php foreach ($colors as $color) : ?>
                                        <option value="<?php echo $color; ?>"><?php echo $color; ?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>

                                <div class="row mb-4">
                                    <div class="col">
                                        <label for="widthInput" class="form-label">Width (cm):</label>
                                        <input type="text" class="form-control" id="widthInput" name="width" placeholder="<?php echo $row['width'] ?>" readonly>
                                    </div>
                                    <div class="col">
                                        <label for="heightInput" class="form-label">Height (cm):</label>
                                        <input type="text" class="form-control" id="heightInput" name="height" placeholder="<?php echo $row['height'] ?>" readonly>
                                    </div>
                                </div>

                                <div class="input-group quantity mb-5" style="width: 100px;">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-minus rounded-circle bg-light border">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                    <input type="text" class="form-control form-control-sm text-center border-0" id="quantityInput" value="1">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-plus rounded-circle bg-light border">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>

                                <a href="customize.php?pid=<?php echo $id; ?>">
                                    <button type="button" class="btn border border-secondary rounded-pill px-3 text-primary">
                                        <i class="fa fa-shopping-bag me-2 text-primary"></i> Customize
                                    </button>
                                </a>
                                <button type="button" class="btn border border-secondary rounded-pill px-3 text-primary" onclick="addToCart()">
                                    <i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart
                                </button>

                                <script>
                                    function addToCart() {
                                        // Get selected color
                                        var colorSelect = document.getElementById('colorOptions');
                                        var selectedColor = colorSelect.options[colorSelect.selectedIndex].text;
                                        
                                        // Get width and height
                                        var width = document.getElementById('widthInput').value;
                                        var height = document.getElementById('heightInput').value;
                                        
                                        // Get quantity
                                        var quantity = document.getElementById('quantityInput').value;
                                        
                                        // Set values to hidden fields in the form
                                        document.getElementById('color').value = selectedColor;
                                        document.getElementById('width').value = width;
                                        document.getElementById('height').value = height;
                                        document.getElementById('quantity').value = quantity;
                                        
                                        // Submit the form
                                        document.getElementById('addToCartForm').submit();
                                    }
                                </script>
                                </div>
                            </div>
                            <form id="addToCartForm" action="vadd_to_cart.php" method="POST">
                                <input type="hidden" name="pid" value="<?php echo $id ?>">
                                <input type="hidden" name="color" id="color" value="">
                                <input type="hidden" name="width" id="width" value="">
                                <input type="hidden" name="height" id="height" value="">
                                <input type="hidden" name="quantity" id="quantity" value="">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Single Product End -->
            <?php
        } else {
            echo "Product not found.";
        }

        $stmt->close();
    } else {
        echo "No product ID provided.";
    }

    $conn->close();
    ?>

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
            showAlert('success', 'Product added to cart!');
        } else if (urlParams.has('success') && urlParams.get('success') === 'false') {
            showAlert('error', 'Something went wrong!');
        } 
    }
    window.onload = checkURLParams;
</script>


    <?php include('includes/footer.php'); ?>

    <!-- Back to Top -->
    <a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
