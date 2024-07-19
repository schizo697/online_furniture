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

        .map-container {
            width: 100%;
            height: 400px; /* Adjust height as needed */
            margin-top: 20px;
        }
    </style>
</head>
<body>
 <!-- Featurs Section Start -->
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
                        <p class="mb-0">Free within General Santos City</p>
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
        </div>
    </div>
</div>
<!-- Featurs Section End -->

<!-- Map Section Start -->
<div class="container-fluid">
    <div class="map-container">
        <iframe 
            src="https://www.google.com/maps/embed?pb=!1m13!1m8!1m3!1d7933.722984693499!2d125.21389199999999!3d6.149297!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zNsKwMDknMDEuNiJOIDEyNcKwMTMnMDAuMSJF!5e0!3m2!1sen!2sph!4v1721317745067!5m2!1sen!2sph" 
            width="100%" 
            height="100%" 
            frameborder="0" 
            style="border:0;" 
            allowfullscreen="" 
            aria-hidden="false" 
            tabindex="0">
        </iframe>
    </div>
</div>
<!-- Map Section End -->

<?php include('includes/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
