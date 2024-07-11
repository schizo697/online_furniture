<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('includes/topbar.php');
    if(!isset($_SESSION['uid'])){
        header("Location: ../login.php");
        exit();
    }
    ?>
 <style>
        body {
            background-color: #f8f9fa;
        }
        .page-header {
            background-color: #343a40;
        }
        .form-container {
            background-color: #ffffff;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            font-weight: bold;
        }
        .btn-primary {
            background-color: #81c408;
            border-color: #81c408;
        }
        .btn-primary:hover {
            background-color: #19875;
            border-color: #19875;
        }
    </style>
</head>
<body>

<!-- Page Header -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Customize your Furniture</h1>
</div>
<!-- End Page Header -->

<!-- Main Content -->
<div class="container mt-5">
    <div class="form-container mx-auto col-md-8 col-lg-6">
        <form action="save_furniture_customization.php" method="POST">
        <div class="mb-3">
                <label for="type" class="form-label">Type of Furniture</label>
                <input type="text" class="form-control" id="color" name="type" required>
            </div>
            <div class="mb-3">
                <label for="color" class="form-label">Color</label>
                <input type="text" class="form-control" id="color" name="color" required>
            </div>
            <div class="mb-3">
                <label for="design" class="form-label">Design</label>
                <input type="text" class="form-control" id="design" name="design" required>
            </div>
            <div class="mb-3">
                <label for="material" class="form-label">Material</label>
                <input type="text" class="form-control" id="material" name="material" required>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="height" class="form-label">Height (cm)</label>
                    <input type="number" class="form-control" id="height" name="height" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="width" class="form-label">Width (cm)</label>
                    <input type="number" class="form-control" id="width" name="width" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="length" class="form-label">Length (cm)</label>
                    <input type="number" class="form-control" id="length" name="length" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Submit</button>
        </form>
    </div>
</div>
<br><br>
<!-- End Main Content -->





<?php include('includes/footer.php'); ?>

<!-- Back to Top Button -->
<a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
