<?php
include '../conn.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    header("Location: ../login.php");
    exit();
}


// Handle Add Product
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

                $allowed_ex = array("jpg", "jpeg", "png", "pdf");

                if (in_array($img_ex_loc, $allowed_ex)) {
                    $new_img_name = uniqid("FR-", true).'.'.$img_ex_loc;
                    $img_upload_path = 'assets/img/'.$new_img_name;
                    move_uploaded_file($tmp_name, $img_upload_path);

                    // Insert into the database
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

// Handle Remove Product
if(isset($_POST['remove_product'])) {
    $remove_pid = $_POST['remove_pid'];
    $update_query = "UPDATE furniture SET status='Inactive' WHERE pid=?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("i", $remove_pid);
    if($stmt->execute()) {
        $url = "product_listing.php?remove_success=true";
        echo '<script>window.location.href= "' . $url . '";</script>';
        
    } else {
        echo "<script>Swal.fire({
                icon: 'error',
                text: 'Something went wrong!',
            });
        </script>";
    }
}


// Handle Update Product
if(isset($_POST['updateproduct'])) {
    $pid = $_POST['editPid'];
    $pname = $_POST['editPname'];
    $price = $_POST['editPrice'];
    $description = $_POST['editDescription'];
    $quantity = $_POST['editQuantity'];
    $color = $_POST['editColor'];
    $height = $_POST['editHeight'];
    $width = $_POST['editWidth'];
    $length = $_POST['editLength'];
    $fid = $_POST['editFid'];

    // Check if a new image is uploaded
    if (isset($_FILES['editImage']) && $_FILES['editImage']['error'] === 0) {
        $img_name = $_FILES['editImage']['name'];
        $img_size = $_FILES['editImage']['size'];
        $tmp_name = $_FILES['editImage']['tmp_name'];

        if ($img_size <= 125000000) {
            $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
            $img_ex_loc = strtolower($img_ex);

            $allowed_ex = array("jpg", "jpeg", "png", "pdf");

            if (in_array($img_ex_loc, $allowed_ex)) {
                $new_img_name = uniqid("FR-", true) . '.' . $img_ex_loc;
                $img_upload_path = 'assets/img/' . $new_img_name;
                move_uploaded_file($tmp_name, $img_upload_path);

                // Update query including image
                $update_query = "UPDATE furniture SET pname=?, price=?, description=?, quantity=?, color=?, height=?, width=?, length=?, fid=?, image=? WHERE pid=?";
                $stmt = $conn->prepare($update_query);
                $stmt->bind_param("ssssssssssi", $pname, $price, $description, $quantity, $color, $height, $width, $length, $fid, $new_img_name, $pid);
            } else {
                $message = "You cannot upload files of this type";
                header("Location: product_listing.php?error=$message");
                exit();
            }
        } else {
            $message = "Sorry, your file is too large";
            header("Location: product_listing.php?error=$message");
            exit();
        }
    } else {
        // Update query without image
        $update_query = "UPDATE furniture SET pname=?, price=?, description=?, quantity=?, color=?, height=?, width=?, length=?, fid=? WHERE pid=?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sssssssssi", $pname, $price, $description, $quantity, $color, $height, $width, $length, $fid, $pid);
    }

    if($stmt->execute()) {
        
        $url = "product_listing.php?update_success=true";
        echo '<script>window.location.href= "' . $url . '";</script>';
    } else {
        echo "<script>Swal.fire({
                icon: 'error',
                text: 'Something went wrong!',
                });
            </script>";
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
                        <!-- Add Product Modal -->
                        <form action="" method="POST" enctype="multipart/form-data">
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
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group form-group-default">
                                                        <label>Furniture Type</label>
                                                        <select name="fid" class="form-control" required>
                                                            <option selected disabled>Select...</option>
                                                            <?php
                                                                $name_query = "SELECT * FROM furniture_type";
                                                                $r = mysqli_query($conn, $name_query);
                                                                while ($row = mysqli_fetch_array($r)) {
                                                                    echo "<option value='{$row['fid']}'>{$row['type']}</option>";
                                                                }
                                                            ?>
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
                                                        <input name="price" type="text" class="form-control" placeholder="" />
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
                                                        <input name="color" type="text" class="form-control" placeholder="" />
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
                                                        <input type="file" name="image" id="image" style="border: solid gray 1px; padding: 6px; width: 80%; border-radius: 4px">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="submit" name="addproduct" class="btn btn-primary">Add</button>
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Edit Product Modal -->
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title">
                                                <span class="fw-mediumbold"> Edit</span>
                                                <span class="fw-light"> Product </span>
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="editPid" id="editPid">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group form-group-default">
                                                        <label>Furniture Type</label>
                                                        <select name="editFid" id="editFid" class="form-control" required>                                   
                                                            <?php
                                                                $name_query = "SELECT * FROM furniture_type";
                                                                $r = mysqli_query($conn, $name_query);
                                                                while ($row = mysqli_fetch_array($r)) {
                                                                    echo "<option value='{$row['fid']}'>{$row['type']}</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 pe-0">
                                                    <div class="form-group form-group-default">
                                                        <label>Product Name</label>
                                                        <input name="editPname" id="editPname" type="text" class="form-control" placeholder="" />                                 
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Price</label>
                                                        <input name="editPrice" id="editPrice" type="textarea" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-12 pe-0">
                                                    <div class="form-group form-group-default">
                                                        <label>Description</label>
                                                        <input name="editDescription" id="editDescription" type="textarea" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 pe-0">
                                                    <div class="form-group form-group-default">
                                                        <label>Height</label>
                                                        <input name="editHeight" id="editHeight" type="number" class="form-control" placeholder="in cm" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Width</label>
                                                        <input name="editWidth" id="editWidth" type="number" class="form-control" placeholder="in cm" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Length</label>
                                                        <input name="editLength" id="editLength" type="number" class="form-control" placeholder="in cm" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Color</label>
                                                        <input name="editColor" id="editColor" type="text" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Quantity</label>
                                                        <input name="editQuantity" id="editQuantity" type="number" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-12 pe-0">
                                                    <div class="form-group form-group-default">
                                                        <label>Image</label>
                                                        <input type="file" name="editImage" id="editImage" style="border: solid gray 1px; padding: 6px; width: 80%; border-radius: 4px">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="submit" name="updateproduct" class="btn btn-primary">Update</button>
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>


                        <!-- Table -->
                        <div class="table-responsive">
                            <table id="add-row" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Type</th>
                                        <th>Height</th>
                                        <th>Width</th>
                                        <th>Length</th>
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
                                            $type = $row['type'];
                                            $height = $row['height'];
                                            $width = $row['width'];
                                            $length = $row['length'];
                                            $status = $row['status'];
                                ?>
                                    <tr>
                                        <td><img src="assets/img/<?php echo $row['image']; ?>" alt="Product Image" style="max-width: 100px;"></td> 
                                        <td><?php echo $pname ?></td>
                                        <td><?php echo $quantity ?></td>
                                        <td>₱<?php echo $price ?></td>
                                        <td><?php echo $type ?></td>
                                        <td><?php echo $height ?>cm</td>
                                        <td><?php echo $width ?>cm</td>
                                        <td><?php echo $length ?>cm</td>
                                        <td><?php echo $status ?></td>
                                        <td>
                                        <div class="form-button-action">
                                            <button type="button" class="btn btn-link btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#editProductModal" onclick="populateEditModal('<?php echo $pid ?>', '<?php echo $row['fid'] ?>', '<?php echo $pname ?>', '<?php echo $price ?>', '<?php echo $description ?>', '<?php echo $quantity ?>', '<?php echo $row['color'] ?>', '<?php echo $height ?>', '<?php echo $width ?>', '<?php echo $length ?>')">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button type="button" data-bs-toggle="tooltip" title="View" class="btn btn-link btn-primary btn-lg">
                                                <i class="far fa-eye"></i>
                                            </button>
                                            <form action="" method="POST" style="display: inline;">
                                                <input type="hidden" name="remove_pid" value="<?php echo $pid; ?>">
                                                <button type="submit" name="remove_product" data-bs-toggle="tooltip" title="Remove" class="btn btn-link btn-danger">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </form>
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
    <?php include('includes/footer.php'); ?> 
    <?php include('includes/tables.php'); ?>
    
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

    function populateEditModal(pid, fid, pname, price, description, quantity, color, height, width, length) {
        document.getElementById('editPid').value = pid;
        document.getElementById('editFid').value = fid;
        document.getElementById('editPname').value = pname;
        document.getElementById('editPrice').value = price;
        document.getElementById('editDescription').value = description;
        document.getElementById('editQuantity').value = quantity;
        document.getElementById('editColor').value = color;
        document.getElementById('editHeight').value = height;
        document.getElementById('editWidth').value = width;
        document.getElementById('editLength').value = length;
    }

    window.onload = checkExistParam;
    </script>
</body>
</html>
