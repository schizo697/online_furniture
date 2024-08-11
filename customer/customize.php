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
        /* Existing Styles */
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

        .tabs {
            width: 25%;
            background-color: #FFF;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px; /* Add padding for dropdown */
        }

        /* Dropdown Button Style */
        .dropdown {
            margin-bottom: 20px;
        }

        .dropdown select {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 4px;
            border: 1px solid #CCC;
        }

        .tab-links {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        .tab-links button {
            flex-grow: 1;
            padding: 10px;
            border: none;
            background-color: #FFA07A;
            color: white;
            cursor: pointer;
            font-size: 16px;
            border-radius: 8px 8px 0 0;
        }

        .tab-links button.active {
            background-color: #FF7F50;
        }

        .tab-content {
            padding: 20px;
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .preview {
            width: 40%;
            text-align: center;
            background-color: #FFF;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .preview img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }

        .summary {
            width: 35%;
            background-color: #FFF;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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

        .tab-content label,
        .tab-content input,
        .tab-content select {
            display: block;
            width: 100%;
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #CCC;
            border-radius: 4px;
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
        <section class="tabs">
            <!-- Dropdown for Type of Furniture -->
            <div class="dropdown">
                <label for="furnitureType">Type of Furniture:</label>
                <select id="furnitureType" name="furnitureType">
                    <option value="cabinet">Cabinet</option>
                    <option value="table">Table</option>
                    <option value="chair">Chair</option>
                    <option value="bed">Bed</option>
                </select>
            </div>

            <!-- Tab Links -->
            <div class="tab-links">
                <button class="active" onclick="openTab(event, 'Properties')">Properties</button>
                <button onclick="openTab(event, 'Design')">Design</button>
                <button onclick="openTab(event, 'FootPart')">Foot Part</button>
            </div>
            <div id="Properties" class="tab-content active">
                <h2>Properties</h2>
                <label for="color">Color:</label>
                <input type="text" id="color" name="color" required>
                <label for="qty">Quantity:</label>
                <input type="number" id="qty" value="1">
                <label for="height">Height:</label>
                <input type="number" id="height" value="345">
                <label for="width">Width:</label>
                <input type="number" id="width" value="285">
                <label for="length">Length:</label>
                <input type="number" id="length" value="345">
            </div>
            <div id="Design" class="tab-content">
                <h2>Design</h2>
                <label for="material">Material:</label>
                <input type="text" id="material" name="material" required>
            </div>
            <div id="FootPart" class="tab-content">
                <h2>Foot Part</h2>
                <label for="foot">Foot Part:</label>
                <select id="foot" name="foot" required>
                    <option value="wheel">Wheel</option>
                    <option value="plain">Plain</option>
                </select>
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

<script>
    function openTab(event, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
            tabcontent[i].classList.remove("active");
        }
        tablinks = document.getElementsByTagName("button");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].classList.remove("active");
        }
        document.getElementById(tabName).style.display = "block";
        document.getElementById(tabName).classList.add("active");
        event.currentTarget.classList.add("active");
    }
</script>

</body>
</html>
