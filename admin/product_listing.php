<!-- handle add product -->
<?php
    include '../conn.php';

    if(isset($_POST['addproduct'])) {
        $pname = $_POST['pname'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $quantity = $_POST['quantity'];
        $color = $_POST['color'];
        $height = $_POST['height'];
        $width = $_POST['width'];
        $length = $_POST['length'];
        $fid = $_POST['fid'];

        if (isset($_FILES['image'])) {
            $img_name = $_FILES['image']['name'];
            $img_size = $_FILES['image']['size'];
            $tmp_name = $_FILES['image']['tmp_name'];
            $error = $_FILES['image']['error'];
            
            if ($error === 0) {
                if ($img_size > 125000000) {
                        $message = "Sorry, your file is too large";
                        header("Location: product_listing.php?error=$message");
                } else {
                    $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                    $img_ex_loc = strtolower($img_ex);
            
                    $allowed_ex = array ("jpg", "jpeg", "png", "pdf");
            
                    if (in_array($img_ex_loc, $allowed_ex)) {
                        $new_img_name = uniqid("FR-", true).'.'.$img_ex_loc;
                        $img_upload_path = 'assets/img/'.$new_img_name;
                        move_uploaded_file($tmp_name, $img_upload_path);
            
                        //into the database
                        $sql = "INSERT INTO furniture (pname, price, description, quantity, color, height, width, length, fid, image, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Active')";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ssssssssss", $pname, $price, $description, $quantity, $color, $height, $width, $length, $fid, $new_img_name);
                        if($stmt->execute()) {
                            $url = "product_listing.php?success=true";
                            echo '<script>window.location.href= "' . $url . '";</script>'; 
                        } else {
                            echo "<script>Swal.fire({
                                    icon: 'error',
                                    text: 'Something went wrong!',
                                    });
                                </script>";
                        }
                    } else {
                        $message = "You cannot upload files of this type";
                        header("Location: product_listing.php?error=$message");
                    }
                }
            } else {
                $message = "Please upload the required images";
                header("Location: product_listing.php?error=$message");
            }
        }  
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('includes/topbar.php'); ?>

</head>
<body>
    <?php include('includes/sidebar.php')?>

    <!-- Header -->
    <?php include('includes/header.php'); ?>

    <!-- Main Content -->
    <div class="container">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Product Management</h3>
                </div>
                <div class="ms-md-auto py-2 py-md-0"></div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List of Product</h4>
                            <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addRowModal">
                                <i class="fa fa-plus"></i> Add Product
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Modal -->
                        <form action="" method="POST" enctype = "multipart/form-data">
                        <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title">
                                            <span class="fw-mediumbold"> New</span>
                                            <span class="fw-light"> Product </span>
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="small">Fill all the necessary information.</p>
                                        <form>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group form-group-default">
                                                    <label>Furniture Type</label>
                                                        <select name="fid" class="form-control" required>
                                                            <option selected disabled>Select...</option>
                                                            <option value="1">Table</option>
                                                            <option value="2">Chair</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 pe-0">
                                                    <div class="form-group form-group-default">
                                                        <label>Product Name</label>
                                                        <input name="pname" type="text" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Price</label>
                                                        <input name="price" type="number" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group form-group-default">
                                                        <label>Description</label>
                                                        <input name="description" type="textarea" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Height</label>
                                                        <input name="height" type="number" class="form-control" placeholder="in cm" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Width</label>
                                                        <input name="width" type="number" class="form-control" placeholder="in cm" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Length</label>
                                                        <input name="length" type="number" class="form-control" placeholder="in cm" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Color</label>
                                                        <input name="color" type="" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Quantity</label>
                                                        <input name="quantity" type="number" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-12 pe-0">
                                                    <div class="form-group form-group-default">
                                                        <label>Image</label>
                                                        <input type = "file" name="image" id="image' style="border: solid gray 1px; padding: 6px; width: 80%; border-radius: 4px">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="submit" name="addproduct" class="btn btn-primary">Add</button>
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>

                        <div class="table-responsive">
                            <table id="add-row" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Product Name</th>
                                        <th>Description</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th style="width: 10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    $sql = "SELECT * FROM furniture 
                                            JOIN furniture_type ON furniture.fid = furniture_type.fid WHERE furniture.status = 'Active'";
                                    $result = mysqli_query($conn, $sql);

                                    if ($result && mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $pid = $row['pid'];
                                            $image = $row['image'];
                                            $pname = $row['pname'];
                                            $description = $row['description'];
                                            $quantity = $row['quantity'];
                                            $price = $row['price'];
                                            $status = $row['status'];
                                ?>
                                    <tr>
                                        <td><img src = "<?php echo "assets/img/".$image; ?>" alt="Image" onclick="window.open(this.src,'_blank');" style = "width: 80px; height: 80px;"></td>
                                        <td><?php echo $pname ?></td>
                                        <td><?php echo $description ?></td>
                                        <td><?php echo $quantity ?></td>
                                        <td>₱<?php echo $price ?></td>
                                        <td><?php echo $status ?></td>
                                        <td>
                                            <div class="form-button-action">
                                                <button type="button" data-bs-toggle="tooltip" title="Edit Task" class="btn btn-link btn-primary btn-lg">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button type="button" data-bs-toggle="tooltip" title="Remove" class="btn btn-link btn-danger">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "No records found";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <!-- <?php include('includes/footer.php'); ?> -->

    <!-- Scripts -->
    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

    <!-- Chart JS -->
    <script src="assets/js/plugin/chart.js/chart.min.js"></script>

    <!-- jQuery Sparkline -->
    <script src="assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Chart Circle -->
    <script src="assets/js/plugin/chart-circle/circles.min.js"></script>

    <!-- Datatables -->
    <script src="assets/js/plugin/datatables/datatables.min.js"></script>

    <!-- Sweet Alert -->
    <script src="admin/assets/js/plugin/sweetalert/sweetalert.min.js"></script>

    <!-- Kaiadmin JS -->
    <script src="assets/js/kaiadmin.min.js"></script>

    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="assets/js/setting-demo.js"></script>
    <script src="assets/js/demo.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    function showModal(){
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Product Added Successfully',
            showConfirmButton: false
        });
    }

    function checkExistParam() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('success') && urlParams.get('success') === 'true') {
            showModal();
        }
    }

    window.onload = checkExistParam; 
</script>

    <!-- <script>
        $(document).ready(function () {
            $("#add-row").DataTable({
                pageLength: 5,
            });

            var action = 
                '<td><div class="form-button-action">' +
                '<button type="button" data-bs-toggle="tooltip" title="Edit Task" class="btn btn-link btn-primary btn-lg">' +
                '<i class="fa fa-edit"></i></button> ' +
                '<button type="button" data-bs-toggle="tooltip" title="Remove" class="btn btn-link btn-danger">' +
                '<i class="fa fa-times"></i></button></div></td>';

            $("#addRowButton").click(function () {
                $("#add-row").dataTable().fnAddData([
                    $("#addName").val(),
                    $("#addPosition").val(),
                    $("#addOffice").val(),
                    action,
                ]);
                $("#addRowModal").modal("hide");
            });
        });
    </script> -->
</body>
</html>
