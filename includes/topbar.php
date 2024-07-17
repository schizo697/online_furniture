<!-- topbar -->
<?php
session_start();
include 'conn.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>MPM: Furniture Shop</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <link rel="icon" href="img/Logo1.png" type="image/x-icon" />

    <style>
        .logo {
            max-width: 8%;
            height: auto;
        }
    </style>
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->

    <!-- Navbar start -->
    <div class="container-fluid fixed-top">
        <div class="container topbar bg-primary d-none d-lg-block">
            <div class="d-flex justify-content-between">
                <div class="top-info ps-2">
                    <small class="me-3"><i class="fas fa-map-marker-alt me-2 text-secondary"></i> <a href="#" class="text-white">123 Street, New York</a></small>
                    <small class="me-3"><i class="fas fa-envelope me-2 text-secondary"></i><a href="#" class="text-white">Email@Example.com</a></small>
                </div>
                <img src="img/Logo1.png" alt="Logo" class="logo">
            </div>
        </div>

        <div class="container px-0">
            <nav class="navbar navbar-light bg-white navbar-expand-xl">
                <a href="index.php" class="navbar-brand"><h1 class="text-primary display-6">MPM: Furniture Shop</h1></a>
                <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars text-primary"></span>
                </button>
                <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                    <div class="navbar-nav mx-auto">
                        <a href="index.php" class="nav-item nav-link active">Home</a>
                        <a href="shop.php" class="nav-item nav-link">Shop</a>
                        <a href="customize.php" class="nav-item nav-link">Customized</a>
                        <a href="purchase.php" class="nav-item nav-link">My Purchase</a>
                    </div>
                    <div class="d-flex m-3 me-0">
                       <?php
                       if (isset($_SESSION['uid'])) {
                           $stmt = $conn->prepare("SELECT COUNT(DISTINCT pid) AS product_count FROM cart WHERE uid = ?");
                           $stmt->bind_param("i", $_SESSION['uid']);
                           $stmt->execute();
                           $stmt->bind_result($product_count);
                           $stmt->fetch();
                           $stmt->close();
                       }
                       ?>
                        <a href="cart.php" class="position-relative me-4 my-auto">
                            <i class="fa fa-shopping-bag fa-2x"></i>
                            <?php if (isset($product_count)) { ?>
                            <span class="position-absolute bg-secondary rounded-circle d-flex align-items-center justify-content-center text-dark px-1" 
                            style="top: -5px; left: 15px; height: 20px; min-width: 20px;"><?php echo $product_count; ?></span>
                            <?php } ?>
                        </a>
                        <a href="profile.php" class="my-auto me-4">
                            <i class="fas fa-user fa-2x"></i>
                        </a>
                        <?php 
                        if(!isset($_SESSION['uid'])){
                            ?> 
                            <a href="../login.php" class="btn btn-outline-primary me-4">Login</a>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar End -->

    <!-- Modal Search Start -->
    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Search by keyword</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex align-items-center">
                    <div class="input-group w-75 mx-auto d-flex">
                        <input type="search" class="form-control p-3" placeholder="keywords" aria-describedby="search-icon-1">
                        <span id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Search End -->

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>
