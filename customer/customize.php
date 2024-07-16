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
        * {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
}

body {
    background-color: #FFFFFF;
}



header {
    display: flex;
    align-items: center;
    background-color: #FF7F50;
    padding: 10px;
}

.back-button {
    font-size: 24px;
    background: none;
    border: none;
    cursor: pointer;
    color: #FFF;
}

h1 {
    flex-grow: 1;
    text-align: center;
    color: #FFF;
}

main {
    display: flex;
    gap: 20px;
    margin-top: 20px;
}

.properties, .preview, .summary {
    background-color: #FFF;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.properties {
    width: 25%;
}

.properties h2 {
    margin-bottom: 10px;
}

.color-picker,
.adjustment,
.design {
    margin-bottom: 20px;
}

.color-picker label,
.adjustment label,
.design label {
    display: block;
    margin-bottom: 5px;
}

.color-picker input[type="color"],
.color-picker input[type="text"],
.adjustment input[type="number"],
.design select {
    width: 100%;
    padding: 8px;
    border: 1px solid #CCC;
    border-radius: 4px;
}

.adjust-buttons {
    display: flex;
    align-items: center;
    gap: 5px;
}

.adjust-buttons button {
    width: 30px;
    height: 30px;
    background-color: #1E90FF;
    border: none;
    border-radius: 50%;
    color: #FFF;
    font-size: 18px;
    cursor: pointer;
}

.preview {
    width: 40%;
    text-align: center;
}

.preview img {
    max-width: 100%;
    height: auto;
    margin-bottom: 10px;
}

.tools {
    display: flex;
    justify-content: center;
    gap: 10px;
}

.tool-button {
    font-size: 24px;
    background: none;
    border: none;
    cursor: pointer;
    color: #1E90FF;
}

.summary {
    width: 35%;
}

.summary table {
    width: 100%;
    margin-bottom: 10px;
    border-collapse: collapse;
}

.summary th,
.summary td {
    border: 1px solid #CCC;
    padding: 10px;
    text-align: left;
}

.summary th {
    background-color: #FFA07A;
    color: #FFF;
}

.cost {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 24px;
    margin: 20px 0;
}

.actions {
    display: flex;
    gap: 10px;
}

.add-cart,
.place-order {
    flex-grow: 1;
    padding: 15px;
    font-size: 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.add-cart {
    background-color: #1E90FF;
    color: #FFF;
}

.place-order {
    background-color: #FFA07A;
    color: #FFF;
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
<div class="container">
  
        <main>
            <section class="properties">
                <h2>Properties</h2>
                <div class="color-picker">
                    <label for="color">Color:</label>
                    <input type="text" class="form-control" id="color" name="color" required>
                </div>
                <div class="adjustment">
                    <label for="qty">Qty:</label>
                    <div class="adjust-buttons">
                       
                        <input type="number" id="qty" value="1">
                       
                    </div>
                </div>
                <div class="adjustment">
                    <label for="height">Height:</label>
                    <div class="adjust-buttons">
                       
                        <input type="number" id="height" value="345">
                       
                    </div>
                </div>
                <div class="adjustment">
                    <label for="width">Width:</label>
                    <div class="adjust-buttons">
                   
                        <input type="number" id="width" value="285">
                     
                    </div>
                </div>
                <div class="adjustment">
                    <label for="length">Length:</label>
                    <div class="adjust-buttons">
                       
                        <input type="number" id="length" value="345">
                       
                    </div>
                </div>
                <div class="design">
                    <label for="design">Design:</label>
                    <input type="text" class="form-control" id="design" name="design" required>
                </div>
                <div class="material">
                    <label for="material">Material:</label>
                    <input type="text" class="form-control" id="material" name="material" required>
                </div>
                <div class="foot">
                    <label for="material">Foot Parts:</label>
                    <input type="text" class="form-control" id="foot" name="foot" required>
                </div>
            </section>
            <section class="preview">
                <img src="cabinet.jpg" alt="Cabinet Diagram">
             
            </section>
            <section class="summary">
              
                <div class="cost">
                    <span>Cost:</span>
                    <span class="amount">₱11,000</span>
                </div>
                <div class="actions">
                    <button class="add-cart">Add Cart</button>
                    <button class="place-order">Place Order</button>
                </div>
            </section>
        </main>
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
