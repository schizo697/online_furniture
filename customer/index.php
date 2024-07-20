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

        .faq-section {
            padding: 50px 0;
            background-color: #f9f9f9;
        }

        .faq-section h2 {
            text-align: center;
            margin-bottom: 40px;
            font-size: 2.5rem;
            color: #333;
        }

        .accordion-button {
            font-size: 1.5rem;
            color: #204a6d ;
        }

        .accordion-button:not(.collapsed) {
            color: #fff;
            background-color: #204a6d ;
        }

        .accordion-body {
            font-size: 1.2rem;
            color: #555;
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

<!-- FAQ Section Start -->
<div class="container faq-section">
    <h2>Frequently Asked Questions</h2>
    <div class="accordion" id="faqAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    1. How do I place an order?
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    You can place an order online through our website by adding items to your cart and proceeding to checkout. You can also place an order by contacting our customer service.
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    2. What payment methods are accepted?
                </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    We accept COD and Gcash.
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    3. Can I track my order?
                </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Yes, once your order is shipped, you will receive a tracking number via email to monitor the delivery status.
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFour">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                    4. What is the return policy?
                </button>
            </h2>
            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    We offer a 30-day return policy on all items. Please ensure the items are in original condition and packaging.
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFive">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                    5. Do you offer custom furniture?
                </button>
            </h2>
            <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Yes, we offer custom furniture options. Please contact our customer service for more details and to discuss your requirements.
                </div>
            </div>
        </div>
    </div>
</div>
<!-- FAQ Section End -->

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
