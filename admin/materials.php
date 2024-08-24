<?php
include '../conn.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    header("Location: ../login.php");
    exit();
}

// Handle Remove Product
if (isset($_POST['remove_product'])) {
    $remove_pid = $_POST['remove_pid'];
    $update_query = "UPDATE furniture SET status='Inactive' WHERE pid=?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("i", $remove_pid);
    if ($stmt->execute()) {
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
                    <h3 class="fw-bold mb-3">Customize</h3>
                </div>
                <div class="ms-md-auto py-2 py-md-0"></div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List of Customize Product</h4>
                            <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addRowModal">
                                <i class="fa fa-plus"></i> Add Product Customize
                            </button>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <!-- Add Product Modal -->
                         <?php 
                         if(isset($_POST['btnAdd'])){
                            $fid = $_POST['fid'];
                            $material = $_POST['material'];
                            $color = $_POST['color'];
                            $fp = $_POST['fp'];

                            $mats = "INSERT INTO mats (ftype, material, color, fpart) VALUES ('$fid', '$material', '$color', '$fp')";
                            $mats_res = mysqli_query($conn, $mats);

                            if($mats){
                                $url = "materials.php?success=true";
                                echo '<script>window.location.href= "' . $url . '";</script>';
                            }
                         }
                         ?>
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title">
                                                <span class="fw-mediumbold"> New</span>
                                                <span class="fw-light"> Material </span>
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
                                                        <label>Material</label>
                                                        <input name="material" type="text" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Color</label>
                                                        <input name="color" type="text" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group form-group-default">
                                                        <label>Foot Part</label>
                                                        <input name="fp" type="text" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="submit" name="btnAdd" class="btn btn-primary">Add</button>
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Edit Product Modal -->
                         <?php
                         if(isset($_POST['btnUpdate'])){
                            $mid = $_POST['editMid'];
                            $editFtype = $_POST['editFtype'];
                            $editmats = $_POST['editMats'];
                            $editcolor = $_POST['editColor'];
                            $editcolor = $_POST['editColor'];
                            $editfp = $_POST['editFootPart'];

                            $updatemats = "UPDATE mats SET ftype = '$editFtype', material = '$editmats', color = '$editcolor', fpart = '$editfp' WHERE mid = '$mid'";
                            $updatematsres = mysqli_query($conn, $updatemats);

                            if($updatematsres){
                                $url = "materials.php?update=true";
                                echo '<script>window.location.href= "' . $url . '";</script>';
                            }
                         }
                         ?>
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
                                            <input type="hidden" name="editMid" id="editMid">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group form-group-default">
                                                        <label>Furniture Type</label>
                                                        <select name="editFtype" id="editFid" class="form-control" required>                                   
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
                                                        <label>Material</label>
                                                        <input name="editMats" id="editMats" type="text" class="form-control" placeholder="" />                                 
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Color</label>
                                                        <input name="editColor" id="editColor" type="textarea" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group form-group-default">
                                                        <label>Foot Part</label>
                                                        <input name="editFootPart" id="editFootPart" type="text" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="submit" name="btnUpdate" class="btn btn-primary">Update</button>
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
                                        <th>Furniture Type</th>
                                        <th>Material</th>
                                        <th>Color</th>
                                        <th>Foot Part</th>
                                        <th style="width: 10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    $sql = "SELECT * FROM mats 
                                    JOIN furniture_type ON mats.ftype = furniture_type.fid";
                                    $result = mysqli_query($conn, $sql);

                                    if ($result && mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['type'] ?></td>
                                        <td><?php echo $row['material']; ?></td>
                                        <td><?php echo $row['color']; ?></td>
                                        <td><?php echo $row['fpart']; ?></td>
                                        <td>
                                            <div class="form-button-action">
                                            <button type="button" class="btn btn-link btn-primary btn-lg btn-edit" data-bs-toggle="modal" data-bs-target="#editProductModal" data-mid=<?php echo $row['mid']; ?> data-ftype="<?php echo $row['ftype']; ?>" 
                                                data-material="<?php echo $row['material']; ?>" data-color="<?php echo $row['color']; ?>" data-fpart="<?php echo $row['fpart']; ?>">
                                                <i class="fa fa-edit"></i>
                                            </button>

                                                <form action="" method="POST" style="display: inline;">
                                                    <input type="hidden" name="remove_pid" value="<?php echo $pid; ?>">
                                                    <button type="submit" name="remove_product" data-bs-toggle="tooltip" title="Remove" class="btn btn-link btn-danger">
                                                        <i class="fa fa-trash"></i>
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

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add event listener to all buttons with class 'btn-edit'
        document.querySelectorAll('.btn-edit').forEach(button => {
            button.addEventListener('click', function() {
                // Get data attributes from the button
                const mid = this.getAttribute('data-mid');
                const ftype = this.getAttribute('data-ftype');
                const material = this.getAttribute('data-material');
                const color = this.getAttribute('data-color');
                const fpart = this.getAttribute('data-fpart');

                // Set the values in the modal fields
                document.getElementById('editMid').value = mid;
                document.getElementById('editFid').value = ftype;
                document.getElementById('editPname').value = material;
                document.getElementById('editPrice').value = color;
                document.getElementById('editFootPart').value = fpart;

                // Optionally, if you need to set the hidden input (editPid), you can add a data attribute to the button for it
                const editPid = this.getAttribute('data-pid');
                if (editPid) {
                    document.getElementById('editPid').value = editPid;
                }
            });
        });
    });
    </script>



    <!-- Footer -->
    <?php include('includes/footer.php'); ?> 
    <?php include('includes/tables.php'); ?>
    
    <script>
    function showModal(message) {
        Swal.fire({
            position: 'center',
            icon: 'success',
            text: message,
            showConfirmButton: false
        });
    }

    function checkExistParam() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('success') && urlParams.get('success') === 'true') {
            showModal('Material added successfully');
        } else if (urlParams.has('update') && urlParams.get('update') === 'true') {
            showModal('Material updated successfully');
        }
    }

    window.onload = checkExistParam;
    </script>

</body>
</html>
