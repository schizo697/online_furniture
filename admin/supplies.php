<?php 
session_start();
include('../conn.php'); // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    header("Location: ../login.php");
    exit();
}
?>

<!-- handle add user -->
<?php
    include '../conn.php';

    if(isset($_POST['addsupplies'])) {
        $supplier = $_POST['supplier'];
        $pname = $_POST['pname'];
        $quantity = $_POST['quantity'];
        $delivery = $_POST['delivery'];
        $user = $_POST['user'];
        $unit = $_POST['unit'];


        $sql = "INSERT INTO supplies (sid, item, quantity, unit, deliverydate, approvedby, status) 
                    VALUES ('$supplier', '$pname', '$quantity', '$unit', '$delivery', '$user', 1)";
        $result = mysqli_query($conn, $sql);

        if($result) {
            $url = "supplies.php?success=true";
            echo '<script>window.location.href= "' . $url . '";</script>';
            exit(); 
        } else {
            $url = "supplies.php?error=true";
            echo '<script>window.location.href="' . $url . '";</script';
            exit();
        }  
    }
    // edit
    if (isset($_POST['editSupply'])) {
        $supid = $_POST['supid'];
        $supplier = $_POST['supplier'];
        $pname = $_POST['pname'];
        $quantity = $_POST['quantity'];
        $delivery = $_POST['delivery'];
        $user = $_POST['user'];
        $unit = $_POST['unit'];
    
        $sql = "UPDATE supplies 
                SET sid = '$supplier', item = '$pname', quantity = '$quantity', unit = '$unit', deliverydate = '$delivery', approvedby = '$user'
                WHERE supid = '$supid'";
    
        $result = mysqli_query($conn, $sql);
    
        if ($result) {
            header("Location: supplies.php?update=true");
        } else {
            header("Location: supplies.php?error=true");
        }
        exit();
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
                    <h3 class="fw-bold mb-3">Supplies</h3>
                </div>
                <div class="ms-md-auto py-2 py-md-0"></div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Supplies List</h4>
                            <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addRowModal">
                                <i class="fa fa-plus"></i> Add Supplies
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Modal -->
                        <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title">
                                            <span class="fw-mediumbold"> Add</span>
                                            <span class="fw-light"> New </span>
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="" method="POST">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group form-group-default">
                                                    <label>Supplier</label>
                                                    <select id="supplier" name="supplier" class="form-control" required>
                                                        <option value="" selected disabled>Select...</option>
                                                        <?php
                                                        $sql = "SELECT * FROM supplier WHERE status = 1";
                                                        $result = mysqli_query($conn, $sql);
                                                        if (mysqli_num_rows($result) > 0) {
                                                            while ($row = mysqli_fetch_assoc($result)) {
                                                                echo '<option value="' . $row['sid'] . '">' . $row['firstname'] . ' ' . $row['lastname'] . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group form-group-default">
                                                        <label>Product Name</label>
                                                        <input id="pname" name="pname" type="text" class="form-control" placeholder="" required/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Quantity</label>
                                                        <input id="quantity" name="quantity" type="number" class="form-control" placeholder="" required />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Measurement</label>
                                                        <select name="unit" class="form-control" required>
                                                            <option selected disabled>Select...</option>
                                                            <option value="pcs">pcs</option>
                                                            <option value="kilo">kilo</option>
                                                            <option value="cm">cm</option>
                                                            <option value="box">box</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group form-group-default">
                                                        <label>Date & Time of Delivery</label>
                                                        <input id="delivery" name="delivery" type="datetime-local" class="form-control"  required/>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group form-group-default">
                                                    <label>Approved/Received by:</label>
                                                    <select id="user" name="user" class="form-control" required>
                                                        <option value="" selected disabled>Select...</option>
                                                        <?php
                                                        $sql = "SELECT * FROM useraccount
                                                                    JOIN userinfo ON userinfo.infoid = useraccount.infoid
                                                                    WHERE useraccount.levelid != 3 AND useraccount.status = 1";
                                                        $result = mysqli_query($conn, $sql);
                                                        if (mysqli_num_rows($result) > 0) {
                                                            while ($row = mysqli_fetch_assoc($result)) {
                                                                echo '<option value="' . $row['uid'] . '">' . $row['firstname'] . ' ' . $row['lastname'] . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    </div>
                                                </div>
                                            </div>
                                        
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="submit" id="addsupplies" name="addsupplies" class="btn btn-primary">Add</button>
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                            <!-- Edit Modal -->
                            <div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title">
                                                <span class="fw-mediumbold"> Edit</span>
                                                <span class="fw-light"> Supply </span>
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="editForm" action="editsupply.php" method="POST">
                                                <input type="hidden" id="editSupId" name="supid">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default">
                                                            <label>Supplier</label>
                                                            <select id="editSupplier" name="supplier" class="form-control" required>
                                                                <option value="" selected disabled>Select...</option>
                                                                <?php
                                                                $sql = "SELECT * FROM supplier WHERE status = 1";
                                                                $result = mysqli_query($conn, $sql);
                                                                if (mysqli_num_rows($result) > 0) {
                                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                                        echo '<option value="' . $row['sid'] . '">' . $row['firstname'] . ' ' . $row['lastname'] . '</option>';
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default">
                                                            <label>Product Name</label>
                                                            <input id="editPname" name="pname" type="text" class="form-control" placeholder="" required />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group form-group-default">
                                                            <label>Quantity</label>
                                                            <input id="editQuantity" name="quantity" type="number" class="form-control" placeholder="" required />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group form-group-default">
                                                            <label>Measurement</label>
                                                            <select id="editUnit" name="unit" class="form-control" required>
                                                                <option value="pcs">pcs</option>
                                                                <option value="kilo">kilo</option>
                                                                <option value="cm">cm</option>
                                                                <option value="box">box</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default">
                                                            <label>Date & Time of Delivery</label>
                                                            <input id="editDelivery" name="delivery" type="datetime-local" class="form-control" required />
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default">
                                                            <label>Approved/Received by:</label>
                                                            <select id="editUser" name="user" class="form-control" required>
                                                                <option value="" selected disabled>Select...</option>
                                                                <?php
                                                                $sql = "SELECT * FROM useraccount
                                                                        JOIN userinfo ON userinfo.infoid = useraccount.infoid
                                                                        WHERE useraccount.levelid != 3 AND useraccount.status = 1";
                                                                $result = mysqli_query($conn, $sql);
                                                                if (mysqli_num_rows($result) > 0) {
                                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                                        echo '<option value="' . $row['uid'] . '">' . $row['firstname'] . ' ' . $row['lastname'] . '</option>';
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="submit" id="editSupply" name="editSupply" class="btn btn-primary">Save Changes</button>
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Archive Modal -->
                                    <div class="modal fade" id="archivemodal" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header border-0">
                                                    <h5 class="modal-title">
                                                        <span class="fw-mediumbold"> Archive</span>
                                                        <span class="fw-light"> Supply </span>
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="archiveForm" action="archivesupply.php" method="POST">
                                                        <input type="hidden" id="archiveSupId" name="supid">
                                                        <p>Are you sure you want to archive this supply?</p>
                                                </div>
                                                <div class="modal-footer border-0">
                                                    <button type="submit" id="archiveSupply" name="archiveSupply" class="btn btn-primary">Yes, Archive</button>
                                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>


                        <div class="table-responsive">
                            <table id="add-row" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                       <th>Company</th>
                                        <th>Supplier</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Date & Time of Delivery</th>
                                        <th>Approved By:</th>
                                        <th style="width: 10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    $sql = "SELECT *, supplier.cbname,  CONCAT(supplier.firstname, ' ', supplier.lastname) AS supplier, CONCAT(userinfo.firstname, ' ', userinfo.lastname) AS user FROM supplies
                                                JOIN useraccount ON useraccount.uid = supplies.approvedby
                                                JOIN userinfo ON userinfo.infoid = useraccount.infoid
                                                JOIN supplier ON supplier.sid = supplies.sid
                                                WHERE supplies.status = 1";
                                    $result = mysqli_query($conn, $sql);

                                    if ($result && mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $supid = $row['supid'];
                                            $sid = $row['sid'];
                                            $item = $row['item'];
                                            $date = $row['deliverydate'];
                                            $quantity = $row['quantity'];
                                            $unit = $row['unit'];
                                            $name = $row['user'];
                                            $supplier = $row['supplier'];
                                            $cbname = $row['cbname'];
                                        ?>
                                    <tr>
                                        <td><?php echo $cbname ?></td>
                                        <td><?php echo $supplier ?></td>
                                        <td><?php echo $item ?></td>
                                        <td><?php echo $quantity , ' ', $unit?></td>
                                        <td><?php echo $date ?></td>
                                        <td><?php echo $name ?></td>
                                        <td>
                                            <div class="form-button-action">
                                            <a href="#" class="btn btn-link btn-success edit-button" data-bs-toggle="modal" data-bs-target="#editmodal"
                                                data-sup-id="<?php echo $supid ?>" data-supplier="<?php echo $sid ?>" data-pname="<?php echo $item ?>"
                                                data-quantity="<?php echo $quantity ?>" data-unit="<?php echo $unit ?>" data-delivery="<?php echo $date ?>"
                                                data-user="<?php echo $uid ?>">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <a href="#" class="btn btn-link btn-primary archive-button" data-sup-id="<?php echo $supid ?>">
                                                <i class="fas fa-trash"></i>
                                            </a>


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

    <script>
        $(document).ready(function () {
            $("#add-row").DataTable({
                pageLength: 5,
            });
        });
    </script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('.archive-button').click(function() {
        var supid = $(this).data('sup-id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to restore this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, archive it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: 'archivesupply.php',
                    data: { archiveSupply: true, supid: supid },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire(
                                'Archived!',
                                response.message,
                                'success'
                            ).then(() => {
                                window.location.href = "supplies.php?archive=true";
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'Error!',
                            'Something went wrong!',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
</script>

<script>
$(document).ready(function() {
    $('.edit-button').click(function() {
        var supid = $(this).data('sup-id');
        var supplier = $(this).data('supplier');
        var pname = $(this).data('pname');
        var quantity = $(this).data('quantity');
        var unit = $(this).data('unit');
        var delivery = $(this).data('delivery');
        var user = $(this).data('user');

        $('#editSupId').val(supid);
        $('#editSupplier').val(supplier);
        $('#editPname').val(pname);
        $('#editQuantity').val(quantity);
        $('#editUnit').val(unit);
        $('#editDelivery').val(delivery);
        $('#editUser').val(user);
    });
});
</script>

<script>
$(document).ready(function() {
    $('.archive-button').click(function() {
        var userid = $(this).data('account-id');
        $('#userid').val(userid);
    });
});
</script>

<script>
$(document).ready(function() {
    $('.edit-button').click(function() {
        var userID = $(this).data('account-id');
        var fname = $(this).data('account-fname');
        var lname = $(this).data('account-lname'); 
        var gender = $(this).data('account-gender');
        var contact = $(this).data('account-contact');
        var address = $(this).data('account-address');
        var email = $(this).data('account-email');
        var cbname = $(this).data('account-cbname');

        $('#userID').val(userID);
        $('#editFirstName').val(fname);
        $('#editLastName').val(lname);
        $('#editContact').val(contact);
        $('#editAddress').val(address);
        $('#editEmail').val(email);
        $('#editCbName').val(cbname);
    });
});
</script>

<script>
    function showAlert(type, message) {
        Swal.fire({
            icon: type,
            text: message,
        });
    }

    function checkURLParams() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('exist') && urlParams.get('exist') === 'true') {
            showAlert('warning', 'Username Already Exists');
        } else if (urlParams.has('success') && urlParams.get('success') === 'true') {
            showAlert('success', 'Supplies added successfully');
        } else if (urlParams.has('error') && urlParams.get('error') === 'true') {
            showAlert('error', 'Something went wrong!');
        } else if (urlParams.has('update') && urlParams.get('update') === 'true') {
            showAlert('success', 'Supplies updated successfully');
        } else if (urlParams.has('errorpassword') && urlParams.get('errorpassword') === 'true') {
            showAlert('error', 'Password do not match');
        } else if (urlParams.has('archive') && urlParams.get('archive') === 'true') {
            showAlert('success', 'Supplies has been archived');
        }
    }

    window.onload = checkURLParams;
</script>
</body>
</html>
