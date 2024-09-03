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
                            $material = $_POST['mats'];
                            $materialprice = $_POST['matsprice'];
                            $color = $_POST['color'];
                            $colorprice = $_POST['colorprice'];
                            $fp = $_POST['fp'];
                            $fpprice = $_POST['fpprice'];
                            $sp = $_POST['spring'];
                            $spprice = $_POST['springprice'];
                            $foam = $_POST['foam'];
                            $foamprice = $_POST['foamprice'];
                            $fabric = $_POST['fabric'];
                            $fabricprice = $_POST['fabricprice'];

                            $icolor = "INSERT INTO mats_color (color, price) VALUES ('$color', '$colorprice')";
                            $icolorres = mysqli_query($conn, $icolor);

                            if($icolorres){
                                $mcid = $conn->insert_id; //last insert id
                                $ifp = "INSERT INTO mats_fp (fp, price) VALUES ('$fp', '$fpprice')";
                                $ifpres = mysqli_query($conn, $ifp);

                                if($ifpres){
                                    $mfpid = $conn->insert_id;
                                    $isp = "INSERT INTO mats_spring (spring, price) VALUES ('$sp', '$spprice')";
                                    $ispres = mysqli_query($conn, $isp);

                                    if($ispres){
                                        $msid = $conn->insert_id;
                                        $ifb = "INSERT INTO mats_fabric (fabric, price) VALUES ('$fabric', '$fabricprice')";
                                        $ifbres = mysqli_query($conn, $ifb);

                                        if($ifbres){
                                            $mfid = $conn->insert_id;
                                            $ifm = "INSERT INTO mats_foam (foam, price) VALUES ('$foam', '$foamprice')";
                                            $ifmres = mysqli_query($conn, $ifm);

                                            if($ifmres){
                                                $mfmid = $conn->insert_id; 
                                                $imats = "INSERT INTO mats (ftype, material, price, mcid, mfpid, mfid, mfmid, msid) VALUES ('$fid', '$material', '$materialprice', '$mcid', '$mfpid', '$mfid', '$mfmid', '$msid')";
                                                $imatsres = mysqli_query($conn, $imats);

                                                if($imatsres){
                                                    $url = "materials.php?success=true";
                                                    echo '<script>window.location.href= "' . $url . '";</script>';
                                                } else {
                                                    echo "Error inserting into mats table.";
                                                }
                                            } else {
                                                echo "Error inserting into mats_foam table.";
                                            }
                                        } else {
                                            echo "Error inserting into mats_fabric table.";
                                        }
                                    } else {
                                        echo "Error inserting into mats_spring table.";
                                    }
                                } else {
                                    echo "Error inserting into mats_fp table.";
                                }
                            } else {
                                echo "Error inserting into mats_color table.";
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
                                                        <input name="mats" id="mats" type="text" class="form-control" placeholder="" />                                 
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Material Price</label>
                                                        <input name="matsprice" id="matsprice" type="text" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 pe-0">
                                                    <div class="form-group form-group-default">
                                                        <label>Color</label>
                                                        <input name="color" id="color" type="text" class="form-control" placeholder="" />                                 
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Color Price</label>
                                                        <input name="colorprice" id="colorprice" type="text" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 pe-0">
                                                    <div class="form-group form-group-default">
                                                        <label>Foot Part</label>
                                                        <input name="fp" id="fp" type="text" class="form-control" placeholder="" />                                 
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Foot Part Price</label>
                                                        <input name="fpprice" id="fpprice" type="text" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 pe-0">
                                                    <div class="form-group form-group-default">
                                                        <label>Fabric</label>
                                                        <input name="fabric" id="fabric" type="text" class="form-control" placeholder="" />                                 
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Fabric Price</label>
                                                        <input name="fabricprice" id="fabricprice" type="text" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 pe-0">
                                                    <div class="form-group form-group-default">
                                                        <label>Foam</label>
                                                        <input name="foam" id="foam" type="text" class="form-control" placeholder="" />                                 
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Foam Price</label>
                                                        <input name="foamprice" id="foamprice" type="text" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 pe-0">
                                                    <div class="form-group form-group-default">
                                                        <label>Spring</label>
                                                        <input name="spring" id="spring" type="text" class="form-control" placeholder="" />                                 
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Spring Price</label>
                                                        <input name="springprice" id="springprice" type="text" class="form-control" placeholder="" />
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
                            $mcid = $_POST['editMcid'];
                            $mfpid = $_POST['editMfpid'];
                            $fid = $_POST['editFtype'];
                            $material = $_POST['editMaterial'];
                            $materialprice = $_POST['editMprice'];
                            $color = $_POST['editColor'];
                            $colorprice = $_POST['editMcprice'];
                            $fp = $_POST['editFootPart'];
                            $fpprice = $_POST['editMfpprice'];
                            $sp = $_POST['editspring'];
                            $spprice = $_POST['editspringprice'];
                            $foam = $_POST['editfoam'];
                            $foamprice = $_POST['editfoamprice'];
                            $fabric = $_POST['editfabric'];
                            $fabricprice = $_POST['editfabricprice'];

                            $updateColor = "UPDATE mats_color SET color = '$color', price = '$colorprice' WHERE mcid = '$mcid'";
                            $updateColorRes = mysqli_query($conn, $updateColor);

                            if($updateColorRes){
                                $updateFp = "UPDATE mats_fp SET fp = '$fp', price = '$fpprice' WHERE mfpid = '$mfpid'";
                                $updateFpRes = mysqli_query($conn, $updateFp);

                                if($updateFpRes){
                                    $updateSpring = "UPDATE mats_spring SET spring = '$sp', price = '$spprice' WHERE msid = (SELECT msid FROM mats WHERE mid = '$mid')";
                                    $updateSpringRes = mysqli_query($conn, $updateSpring);

                                    if($updateSpringRes){
                                        $updateFabric = "UPDATE mats_fabric SET fabric = '$fabric', price = '$fabricprice' WHERE mfid = (SELECT mfid FROM mats WHERE mid = '$mid')";
                                        $updateFabricRes = mysqli_query($conn, $updateFabric);

                                        if($updateFabricRes){
                                            $updateFoam = "UPDATE mats_foam SET foam = '$foam', price = '$foamprice' WHERE mfmid = (SELECT mfmid FROM mats WHERE mid = '$mid')";
                                            $updateFoamRes = mysqli_query($conn, $updateFoam);

                                            if($updateFoamRes){
                                                $updateMats = "UPDATE mats SET ftype = '$fid', material = '$material', price = '$materialprice' WHERE mid = '$mid'";
                                                $updateMatsRes = mysqli_query($conn, $updateMats);

                                                if($updateMatsRes){
                                                    $url = "materials.php?update=true";
                                                    echo '<script>window.location.href= "' . $url . '";</script>';
                                                } else {
                                                    echo "Error updating mats table.";
                                                }
                                            } else {
                                                echo "Error updating mats_foam table.";
                                            }
                                        } else {
                                            echo "Error updating mats_fabric table.";
                                        }
                                    } else {
                                        echo "Error updating mats_spring table.";
                                    }
                                } else {
                                    echo "Error updating mats_fp table.";
                                }
                            } else {
                                echo "Error updating mats_color table.";
                            }
                        }
                        ?>
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title">
                                                <span class="fw-mediumbold">Edit</span>
                                                <span class="fw-light">Product</span>
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="editMid" id="editMid">
                                            <input type="hidden" name="editMcid" id="editMcid">
                                            <input type="hidden" name="editMfpid" id="editMfpid">
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
                                                        <input name="editMaterial" id="editMaterial" type="text" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Material Price</label>
                                                        <input name="editMprice" id="editMprice" type="text" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 pe-0">
                                                    <div class="form-group form-group-default">
                                                        <label>Color</label>
                                                        <input name="editColor" id="editColor" type="text" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Color Price</label>
                                                        <input name="editMcprice" id="editMcprice" type="text" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 pe-0">
                                                    <div class="form-group form-group-default">
                                                        <label>Foot Part</label>
                                                        <input name="editFootPart" id="editFootPart" type="text" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Foot Part Price</label>
                                                        <input name="editMfpprice" id="editMfpprice" type="text" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 pe-0">
                                                    <div class="form-group form-group-default">
                                                        <label>Fabric</label>
                                                        <input name="editfabric" id="editfabric" type="text" class="form-control" placeholder="" />                                 
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Fabric Price</label>
                                                        <input name="editfabricprice" id="editfabricprice" type="text" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 pe-0">
                                                    <div class="form-group form-group-default">
                                                        <label>Foam</label>
                                                        <input name="editfoam" id="editfoam" type="text" class="form-control" placeholder="" />                                 
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Foam Price</label>
                                                        <input name="editfoamprice" id="editfoamprice" type="text" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 pe-0">
                                                    <div class="form-group form-group-default">
                                                        <label>Spring</label>
                                                        <input name="editspring" id="editspring" type="text" class="form-control" placeholder="" />                                 
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Spring Price</label>
                                                        <input name="editspringprice" id="editspringprice" type="text" class="form-control" placeholder="" />
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
                                        <th>Material/Price</th>
                                        <th>Color/Price</th>
                                        <th>Spring</th>
                                        <th>Foam</th>
                                        <th>Fabric</th>
                                        <th>Foot Part/Price</th>
                                        <th style="width: 10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $sql = "SELECT furniture_type.type,mats.mid, mats.material, mats.price AS mprice,mats_color.mcid, mats_color.color, mats_color.price AS mcprice,mats_fp.mfpid, mats_fp.fp, mats_fp.price AS mfpprice, mats_spring.spring, mats_spring.price AS springprice, mats_foam.foam, mats_foam.price AS foamprice, mats_fabric.fabric, mats_fabric.price AS fabricprice FROM mats 
                                    JOIN mats_color ON mats.mcid = mats_color.mcid
                                    JOIN mats_fp ON mats.mfpid = mats_fp.mfpid
                                    JOIN mats_spring ON mats.msid = mats_spring.msid
                                    JOIN mats_fabric ON mats.mfid = mats_fabric.mfid
                                    JOIN mats_foam ON mats.mfmid = mats_foam.mfmid
                                    JOIN furniture_type ON mats.ftype = furniture_type.fid";
                                    $result = mysqli_query($conn, $sql);

                                    if ($result && mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['type']?></td>
                                        <td><?php echo $row['material']  . ', ₱' . $row['mprice']; ?></td>
                                        <td><?php echo $row['color']  . ', ₱' . $row['mcprice']; ?></td>
                                        <td><?php echo $row['fp']  . ', ₱' . $row['mfpprice']; ?></td>
                                        <td><?php echo $row['spring']  . ', ₱' . $row['springprice']; ?></td>
                                        <td><?php echo $row['foam']  . ', ₱' . $row['foamprice']; ?></td>
                                        <td><?php echo $row['fabric']  . ', ₱' . $row['fabricprice']; ?></td>
                                        <td>
                                            <div class="form-button-action">
                                            <button type="button" class="btn btn-link btn-primary btn-lg btn-edit" data-bs-toggle="modal" data-bs-target="#editProductModal" 
                                                data-mid="<?php echo $row['mid']; ?>" data-mcid="<?php echo $row['mcid']; ?>" data-mfpid="<?php echo $row['mfpid']; ?>"
                                                data-mprice="<?php echo $row['mprice']; ?>" data-mcprice="<?php echo $row['mcprice']; ?>" data-mfpprice="<?php echo $row['mfpprice']; ?>"
                                                data-ftype="<?php echo $row['type']; ?>" data-material="<?php echo $row['material']; ?>" data-color="<?php echo $row['color']; ?>" 
                                                data-fpart="<?php echo $row['fp']; ?>" data-fabric="<?php echo $row['fabric']; ?>" data-fabricprice="<?php echo $row['fabricprice']; ?>"
                                                data-foam="<?php echo $row['foam']; ?>" data-foamprice="<?php echo $row['foamprice']; ?>" data-spring="<?php echo $row['spring']; ?>"
                                                data-springprice="<?php echo $row['springprice']; ?>">
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
        document.querySelectorAll('.btn-edit').forEach(button => {
            button.addEventListener('click', function() {
                const mid = this.getAttribute('data-mid');
                const mcid = this.getAttribute('data-mcid');
                const mfpid = this.getAttribute('data-mfpid');
                const ftype = this.getAttribute('data-ftype');
                const material = this.getAttribute('data-material');
                const mprice = this.getAttribute('data-mprice');
                const color = this.getAttribute('data-color');
                const mcprice = this.getAttribute('data-mcprice');
                const fpart = this.getAttribute('data-fpart');
                const mfpprice = this.getAttribute('data-mfpprice');
                const fabric = this.getAttribute('data-fabric');
                const fabricprice = this.getAttribute('data-fabricprice');
                const foam = this.getAttribute('data-foam');
                const foamprice = this.getAttribute('data-foamprice');
                const spring = this.getAttribute('data-spring');
                const springprice = this.getAttribute('data-springprice');

                document.getElementById('editMid').value = mid;
                document.getElementById('editMcid').value = mcid;
                document.getElementById('editMfpid').value = mfpid;
                document.getElementById('editFid').value = ftype;
                document.getElementById('editMaterial').value = material;
                document.getElementById('editMprice').value = mprice;
                document.getElementById('editColor').value = color;
                document.getElementById('editMcprice').value = mcprice;
                document.getElementById('editFootPart').value = fpart;
                document.getElementById('editMfpprice').value = mfpprice;
                document.getElementById('editfabric').value = fabric;
                document.getElementById('editfabricprice').value = fabricprice;
                document.getElementById('editfoam').value = foam;
                document.getElementById('editfoamprice').value = foamprice;
                document.getElementById('editspring').value = spring;
                document.getElementById('editspringprice').value = springprice;

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
