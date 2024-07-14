<?php 
session_start();
include('../conn.php'); // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    header("Location: ../login.php");
    exit();
}
?>

<!-- restored -->

<!-- handle add payment -->
<?php
    if(isset($_POST['addPayment'])) {
        $accname = $_POST['accname'];
        $accnumber = $_POST['accnumber'];
        $type = $_POST['type'];
        $addedby = $_SESSION['uid'];

        $sql = "INSERT INTO paymentoption (accountname, accountnumber, type, addedby, status) VALUES ('$accname', '$accnumber', '$type', '$addedby', 'Active')";
        $result = mysqli_query($conn, $sql);

        if($result) {
            $url = "paymentoption.php?success=true";
            echo '<script>window.location.href= "' . $url . '";</script>';
            exit(); 
        } else {
            $url = "paymentoption.php?error=true";
            echo '<script>window.location.href="' . $url . '";</script';
            exit();
        }  
    }
?>

<!-- handle edit user -->
<?php
      if(isset($_POST['btnSave'])){
        $poid = $_POST['poid'];
        $accname = $_POST['accname'];
        $accnumber = $_POST['accnumber'];
        $type = $_POST['type'];

        $poupdate = "UPDATE paymentoption SET accountname = '$accname', accountnumber = '$accnumber', type = '$type' WHERE poid = '$poid'";
        $inforesult = mysqli_query($conn, $poupdate);
    
        if($inforesult) {
            $url = 'paymentoption.php?update=true';
            echo '<script>window.location.href= "' . $url . '"</script>';
            exit();
        } else {
            $url = "paymentoption.php?error=true";
            echo '<script>window.location.href="' . $url . '";</script';
            exit();
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
                    <h3 class="fw-bold mb-3">Payment Option</h3>
                </div>
                <div class="ms-md-auto py-2 py-md-0"></div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Available Payment Option</h4>
                            <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addRowModal">
                                <i class="fa fa-plus"></i> Add Payment Method
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Add Modal -->
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
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Account Name</label>
                                                        <input name="accname" type="text" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Account Number</label>
                                                        <input name="accnumber" type="number" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 pe-0">
                                                    <div class="form-group form-group-default">
                                                    <label>Payment Method</label>
                                                        <select name="type" class="form-control" required>
                                                            <option selected disabled>Select...</option>
                                                            <option value="Gcash">Gcash</option>
                                                            <option value="Maya">Maya</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="submit" name="addPayment" class="btn btn-primary">Add</button>
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
                                            <span class="fw-light"> Payment Information </span>
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="" method="POST">
                                            <div class="row">
                                                <div class="col-md-6 pe-0">
                                                    <div class="form-group form-group-default">
                                                        <label>User ID</label>
                                                        <input name="poid" id="userID" type="text" class="form-control" placeholder="" readonly/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Account Name</label>
                                                        <input name="accname" id="editAccName" type="text" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Account Number</label>
                                                        <input name="accnumber" id="editAccNumber" type="text" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 pe-0">
                                                    <div class="form-group form-group-default">
                                                    <label>Payment Method</label>
                                                        <select name="type" class="form-control" required>
                                                            <option selected disabled>Select...</option>
                                                            <option value="Gcash">Gcash</option>
                                                            <option value="Maya">Maya</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="submit" name="btnSave" class="btn btn-primary">Save Changes</button>
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
                                        <th>Account Name</th>
                                        <th>Account Number</th>
                                        <th>Type</th>
                                        <th>Date Added</th>
                                        <th>Status</th>
                                        <th style="width: 10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $sql = "SELECT * FROM paymentoption";
                                        $result = mysqli_query($conn, $sql);

                                        if ($result && mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $poid = $row['poid'];
                                                $accountname = $row['accountname'];
                                                $accountnumber = $row['accountnumber'];
                                                $type = $row['type'];
                                                $date = $row['dateadded'];
                                                $status = $row['status'];
                                    ?>
                                    <tr>
                                        <td><?php echo $accountname ?></td>
                                        <td><?php echo $accountnumber ?></td>
                                        <td><?php echo $type ?></td>
                                        <td><?php echo $date ?></td>
                                        <td><?php echo $status ?></td>
                                        <td>
                                            <div class="form-button-action">
                                                <a href="#" class="btn btn-link btn-success edit-button" data-bs-toggle="modal" data-bs-target="#editmodal" data-account-id="<?php echo $poid?>" data-account-accname="<?php echo $accountname?>" data-account-accnumber="<?php echo $accountnumber?>"
                                                    data-account-type="<?php echo $type?>">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="#" class="btn btn-link btn-primary archive-button" data-bs-toggle="modal" data-bs-target="#archivemodal" data-account-id="<?php echo $uid?>">
                                                    <i class="fa fa-times"></i>
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

    
</body>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
        var accname = $(this).data('account-accname');
        var accnumber = $(this).data('account-accnumber'); 

        $('#userID').val(userID);
        $('#editAccName').val(accname);
        $('#editAccNumber').val(accnumber);
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
            showAlert('success', 'Account added successfully');
        } else if (urlParams.has('error') && urlParams.get('error') === 'true') {
            showAlert('error', 'Something went wrong!');
        } else if (urlParams.has('update') && urlParams.get('update') === 'true') {
            showAlert('success', 'Account updated successfully');
        } else if (urlParams.has('errorpassword') && urlParams.get('errorpassword') === 'true') {
            showAlert('error', 'Password do not match');
        } else if (urlParams.has('archive') && urlParams.get('archive') === 'true') {
            showAlert('success', 'Account archived successfully');
        }
    }

    window.onload = checkURLParams;
</script>
</html>
