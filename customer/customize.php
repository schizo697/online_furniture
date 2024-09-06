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
            <!-- Tab Links -->
            <div class="tab-links">
                <button class="active" onclick="openTab(event, 'Properties')">Properties</button>
                <button onclick="openTab(event, 'Materials')">Materials</button>
                <button onclick="openTab(event, 'FootPart')">Foot Part</button>
            </div>
            <?php
            // Fetch the data from the database
            // Note: Use prepared statements for security reasons in a real application
            $pid = $_GET['pid'];
            $furniture = "SELECT furniture_type.type, furniture.pname, furniture.image, furniture.price AS fprice, furniture.height, furniture.width, furniture.length, mats.mid, 
            mats.material, mats.price AS mprice,mats_color.mcid, mats_color.color, mats_color.price AS mcprice,mats_fp.mfpid, mats_fp.fp, mats_fp.price AS mfpprice, mats_spring.msid, mats_spring.spring, mats_spring.price AS springprice, mats_foam.mfmid, mats_foam.foam, mats_foam.price AS foamprice, mats_fabric.mfid, mats_fabric.fabric, mats_fabric.price AS fabricprice FROM mats 
            JOIN mats_color ON mats.mcid = mats_color.mcid
            JOIN mats_fp ON mats.mfpid = mats_fp.mfpid
            JOIN mats_spring ON mats.msid = mats_spring.msid
            JOIN mats_foam ON mats.mfmid = mats_foam.mfmid
            JOIN mats_fabric ON mats.mfid = mats_fabric.mfid
            JOIN furniture_type ON mats.ftype = furniture_type.fid
            JOIN furniture ON furniture_type.fid = furniture.fid 
            WHERE furniture.pid = '$pid'";
            $furnitureres = mysqli_query($conn, $furniture);
            if($furnitureres && mysqli_num_rows($furnitureres) > 0){
                $colors = [];
                $materials = [];
                $foots = []; 
                while($furniturerow = mysqli_fetch_assoc($furnitureres)){
                    $pname = $furniturerow['pname'];
                    $img = $furniturerow['image'];
                    $fprice = $furniturerow['fprice'];
                    $height = $furniturerow['height'];
                    $width = $furniturerow['width'];
                    $length = $furniturerow['length'];
                    $material = $furniturerow['material'];
                    $mprice = $furniturerow['mprice'];
                    $color = $furniturerow['color'];
                    $mcprice = $furniturerow['mcprice'];
                    $fp = $furniturerow['fp'];
                    $mfpprice = $furniturerow['mfpprice'];
                    $cotton = $furniturerow['foam'];
                    $cprice = $furniturerow['foamprice'];
                    $fabric = $furniturerow['fabric'];
                    $fbprice = $furniturerow['fabricprice'];
                    $spring = $furniturerow['spring'];
                    $spprice = $furniturerow['springprice'];

                    $colors[$color] = $mcprice;
                    $materials[$material] = $mprice;
                    $foots[$fp] = $mfpprice;
                    $cottons[$cotton] = $cprice;
                    $fabrics[$fabric] = $fbprice;
                    $springs[$spring] = $spprice;
                }
            } else {
                $url = "shop.php";
                echo '<script>window.location.href= "' . $url . '";</script>';
                exit();
            }
            ?>
            <form action="customize_add_to_cart.php" method="POST">
            <div id="Properties" class="tab-content active">
                <h2>Properties</h2>
                <h6>Quantity:</span></h6>
                <input type="text" id="qty" value="1" placeholder="Quantity" pattern="\d*" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                <h6>Height:<span style="color: red; font-size: 12px;">(inches)</h6>
                <input type="text" id="height" placeholder="Height" pattern="\d*" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                <h6>Width:<span style="color: red; font-size: 12px;">(inches)</h6>
                <input type="text" id="width" placeholder="Width" pattern="\d*" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                <h6>Length:<span style="color: red; font-size: 12px;">(inches)</h6>
                <input type="text" id="length" placeholder="Length" pattern="\d*" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                <h6>Color:</h6>
                <select id="color" name="color" required>
                    <option value="" disabled selected>Select a color</option>
                    <?php foreach ($colors as $color => $mcprice) : ?>
                    <option value="<?php echo $color ?>" data-price="<?php echo $mcprice ?>"><?php echo $color ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div id="Materials" class="tab-content">
                <h2>Materials</h2>
                <h6>Materials:</h6>
                <select id="mats" name="mats" required>
                    <option value="" disabled selected>Select material</option>
                    <?php foreach ($materials as $material => $mprice) : ?>       
                    <option value="<?php echo $material ?>" data-price="<?php echo $mprice ?>"><?php echo $material ?></option>
                    <?php endforeach ?>
                </select>
                <h6>Foam:</h6>
                <select id="foam" name="foam" required>
                    <option value="" disabled selected>Select cotton</option>
                    <?php foreach ($cottons as $cotton => $cprice) : ?>       
                    <option value="<?php echo $cotton ?>" data-price="<?php echo $cprice ?>"><?php echo $cotton ?></option>
                    <?php endforeach ?>
                </select>
                <h6>Fabric:</h6>
                <select id="fabric" name="fabric" required>
                    <option value="" disabled selected>Select fabric</option>
                    <?php foreach ($fabrics as $fabric => $fbprice) : ?>       
                    <option value="<?php echo $fabric ?>" data-price="<?php echo $fbprice ?>"><?php echo $fabric ?></option>
                    <?php endforeach ?>
                </select>
                <h6>Spring:</h6>
                <select id="spring" name="spring" required>
                    <option value="" disabled selected>Select spring</option>
                    <?php foreach ($springs as $spring => $spprice) : ?>       
                    <option value="<?php echo $spring ?>" data-price="<?php echo $spprice ?>"><?php echo $spring ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div id="FootPart" class="tab-content">
                <h2>Foot Part</h2>
                <h6>Foot Part:</h6>
                <select id="foot" name="foot" required>
                    <option value="" disabled selected>Select foot part</option>
                    <?php foreach ($foots as $fp => $mfpprice) : ?>
                    <option value="<?php echo $fp ?>" data-price="<?php echo $mfpprice ?>"><?php echo $fp ?></option>
                    <?php endforeach ?>
                </select>
            </div>
        </section>

        <section class="preview">
            <img src="../admin/assets/img/<?php echo $img ?>" alt="<?php echo $img ?>">
        </section>
        <section class="summary">
            <div class="cost">
                <span><h3>Total</h3></span>
                <span class="amount">₱<span id="totalPrice">0.00</span></span>
            </div>
            <ul>
                <li style="display: flex; justify-content: space-between;">
                    <span><?php echo $pname ?></span>
                    <span>₱<?php echo $fprice ?></span>
                </li>
                <span><strong>Material</strong></span>
                <li style="display: flex; justify-content: space-between;">
                    <span id="materialName"></span>
                    <span id="materialPrice"></span>
                </li>
                <li style="display: flex; justify-content: space-between;">
                    <span id="foamName"></span>
                    <span id="foamPrice"></span>
                </li>
                <li style="display: flex; justify-content: space-between;">
                    <span id="fabricName"></span>
                    <span id="fabricPrice"></span>
                </li>
                <li style="display: flex; justify-content: space-between;">
                    <span id="springName"></span>
                    <span id="springPrice"></span>
                </li>
                <span><strong>Size</strong></span>
                <li style="display: flex; justify-content: space-between;">
                    <span id="sizeName"></span>
                    <span id="sizePrice"></span>
                </li>
                <span><strong>Color</strong></span>
                <li style="display: flex; justify-content: space-between;">
                    <span id="colorName"></span>
                    <span id="colorPrice"></span>
                </li>
                <span><strong>Foot Part</strong></span>
                <li style="display: flex; justify-content: space-between;">
                    <span id="footName"></span>
                    <span id="footPrice"></span>
                </li>
                <br>
                <br>
                <br>
                <br>
                <br>
            </ul>
       
                <input type="hidden" name="pid" value="<?php echo $pid ?>">
                <input type="hidden" id="hiddenMaterial" name="material" value="">
                <input type="hidden" id="hiddenColor" name="color" value="">
                <input type="hidden" id="hiddenFoot" name="footPart" value="">
                <input type="hidden" id="hiddenFoam" name="foam" value="">
                <input type="hidden" id="hiddenFabric" name="fabric" value="">
                <input type="hidden" id="hiddenSpring" name="spring" value="">
                <input type="hidden" id="hiddenWidth" name="width" value="">
                <input type="hidden" id="hiddenLength" name="length" value="">
                <input type="hidden" id="hiddenHeight" name="height" value="">
                <input type="hidden" id="hiddenTotalPrice" name="totalPrice" value="0.00">
                <div class="actions">
                    <button class="add-cart">Add Cart</button>
                    <button class="place-order">Place Order</button>
                </div>
            </form>
        </section>
    </main>
</div>
<br><br>
<!-- End Main Content -->

<script>
document.addEventListener('DOMContentLoaded', () => {
    const furniturePrice = <?php echo $fprice ?>;
    let materialPrice = 0;
    let colorPrice = 0;
    let footPrice = 0;
    let sizePrice = 0;
    let foamPrice = 0;
    let fabricPrice = 0;
    let springPrice = 0;

    function updateSizePrice() {
        const width = parseFloat(document.getElementById('width').value) || 0;
        const length = parseFloat(document.getElementById('length').value) || 0;
        const height = parseFloat(document.getElementById('height').value) || 0;
        
        const dimension = width + length + height;
        sizePrice = dimension * 50;

        document.getElementById('sizeName').innerText = `Height: ${height} inches, Width: ${width} inches, Length: ${length} inches`;
        document.getElementById('sizePrice').innerText = '₱' + sizePrice.toFixed(2);

        document.getElementById('hiddenWidth').value = width.toFixed(2);
        document.getElementById('hiddenLength').value = length.toFixed(2);
        document.getElementById('hiddenHeight').value = height.toFixed(2);
    }

    function updatePrice() {
        const quantity = parseInt(document.getElementById('qty').value) || 1;
        const totalPrice = (furniturePrice + materialPrice + foamPrice + fabricPrice + springPrice + colorPrice + footPrice + sizePrice) * quantity;
        console.log('Updating total price to: ₱' + totalPrice.toFixed(2));

        document.getElementById('totalPrice').innerText = totalPrice.toFixed(2);

        document.getElementById('hiddenMaterial').value = document.getElementById('mats').value;
        document.getElementById('hiddenColor').value = document.getElementById('color').value;
        document.getElementById('hiddenFoot').value = document.getElementById('foot').value;
        document.getElementById('hiddenFoam').value = document.getElementById('foam').value;
        document.getElementById('hiddenFabric').value = document.getElementById('fabric').value;
        document.getElementById('hiddenSpring').value = document.getElementById('spring').value;
        document.getElementById('hiddenTotalPrice').value = totalPrice.toFixed(2);
    }

    document.getElementById('color').addEventListener('change', (event) => {
        const selectedOption = event.target.options[event.target.selectedIndex];
        colorPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        document.getElementById('colorName').innerText = event.target.value;
        document.getElementById('colorPrice').innerText = '₱' + colorPrice.toFixed(2);
        updatePrice();
    });

    document.getElementById('mats').addEventListener('change', (event) => {
        const selectedOption = event.target.options[event.target.selectedIndex];
        materialPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        document.getElementById('materialName').innerText = event.target.value;
        document.getElementById('materialPrice').innerText = '₱' + materialPrice.toFixed(2);
        updatePrice();
    });

    document.getElementById('foam').addEventListener('change', (event) => {
        const selectedOption = event.target.options[event.target.selectedIndex];
        foamPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        document.getElementById('foamName').innerText = event.target.value;
        document.getElementById('foamPrice').innerText = '₱' + foamPrice.toFixed(2);
        updatePrice();
    });

    document.getElementById('fabric').addEventListener('change', (event) => {
        const selectedOption = event.target.options[event.target.selectedIndex];
        fabricPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        document.getElementById('fabricName').innerText = event.target.value;
        document.getElementById('fabricPrice').innerText = '₱' + fabricPrice.toFixed(2);
        updatePrice();
    });

    document.getElementById('spring').addEventListener('change', (event) => {
        const selectedOption = event.target.options[event.target.selectedIndex];
        springPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        document.getElementById('springName').innerText = event.target.value;
        document.getElementById('springPrice').innerText = '₱' + springPrice.toFixed(2);
        updatePrice();
    });

    document.getElementById('foot').addEventListener('change', (event) => {
        const selectedOption = event.target.options[event.target.selectedIndex];
        footPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        document.getElementById('footName').innerText = event.target.value;
        document.getElementById('footPrice').innerText = '₱' + footPrice.toFixed(2);
        updatePrice();
    });

    document.getElementById('width').addEventListener('input', () => {
        updateSizePrice();
        updatePrice();
    });

    document.getElementById('length').addEventListener('input', () => {
        updateSizePrice();
        updatePrice();
    });

    document.getElementById('height').addEventListener('input', () => {
        updateSizePrice();
        updatePrice();
    });

    document.getElementById('qty').addEventListener('input', updatePrice);

    updatePrice();
});
</script>

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
