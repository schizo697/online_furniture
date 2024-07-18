<?php 
session_start();

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    header("Location: ../login.php");
    exit();
}
?>

<!-- handle add user -->
<?php
    include '../conn.php';

    if(isset($_POST['adduser'])) {
        $cbname = $_POST['cbname'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $gender = $_POST['gender'];
        $address = $_POST['address'];
        $contact = $_POST['contact'];

        $sql = "INSERT INTO supplier (cbname, firstname, lastname, contact, address, gender, email, levelid, status) 
                    VALUES ('$cbname', '$firstname', '$lastname', '$contact', '$address', '$gender', '$email', 4, 1)";
        $result = mysqli_query($conn, $sql);

        if($result) {
            $url = "supplier.php?success=true";
            echo '<script>window.location.href= "' . $url . '";</script>';
            exit(); 
        } else {
            $url = "supplier.php?error=true";
            echo '<script>window.location.href="' . $url . '";</script';
            exit();
        }  
    }
?>

<!-- handle edit user -->
    <?php
      if(isset($_POST['btnSave'])){
        $cbname = $_POST['cbname'];
        $user_id = $_POST['userid'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $contact = $_POST['contact'];
        $address = $_POST['address'];
        $email = $_POST['email'];

        $infoupdate = "UPDATE supplier SET cbname = '$cbname', firstname = '$firstname', lastname = '$lastname', contact = '$contact', address = '$address', email = '$email' WHERE sid = '$user_id'";
        $inforesult = mysqli_query($conn, $infoupdate);
    
        if($inforesult) {
            $url = 'supplier.php?update=true';
            echo '<script>window.location.href= "' . $url . '"</script>';
            exit();
        } else {
            $url = "supplier.php?error=true";
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
                    <h3 class="fw-bold mb-3">Supplier</h3>
                </div>
                <div class="ms-md-auto py-2 py-md-0"></div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List of Supplier</h4>
                            <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addRowModal">
                                <i class="fa fa-plus"></i> Add New
                            </button>
                        </div>
                    </div>
                    <div class="card-body">

                        <!-- archive modal -->
                        <div class="modal fade" id="archivemodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Archive</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                                <?php 
                                if(isset($_POST['btnArchive'])){
                                    $user_id = $_POST['userid'];

                                    $statusupdate = "UPDATE supplier SET status = 0 WHERE sid = '$user_id'";
                                    $statusresult = mysqli_query($conn, $statusupdate);

                                    if($statusresult){
                                        $url = "supplier.php?archive=true";
                                        echo '<script>window.location.href="' . $url . '"</script>';
                                        exit();
                                    } else {
                                        $url = "supplier.php?error=true";
                                        echo '<script>window.location.href="' . $url . '";</script';
                                        exit();
                                    }
                                }
                                ?>
                                <form action="" method="POST">
                                    <div class="modal-body">
                                        Are you sure you want to archive this account?
                                    </div>
                                    <div class="form-group" style="display: none;">
                                        <label for="userid">User ID</label>
                                        <input type="text" class="form-control" id="userid" name="userid" required readonly>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" name="btnArchive" class="btn btn-primary">Archive</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        </div>
                    
                        <!--Edit User Modal -->
                        <form action="" method="POST">
                        <div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title">
                                            <span class="fw-mediumbold"> Edit</span>
                                            <span class="fw-light"> Supplier Information </span>
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="small">Fill all the necessary information</p>
                                        <form>
                                            <div class="row">
                                            <div class="col-md-6 pe-0">
                                                    <div class="form-group form-group-default">
                                                        <label>User ID</label>
                                                        <input name="userid" id="userID" type="text" class="form-control" placeholder="" readonly/>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group form-group-default">
                                                        <label>Company/Business Name</label>
                                                        <input name="cbname" id="editCbName" type="text" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 pe-0">
                                                    <div class="form-group form-group-default">
                                                        <label>First Name</label>
                                                        <input name="firstname" id="editFirstName" type="text" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Last Name</label>
                                                        <input name="lastname" id="editLastName" type="text" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group form-group-default">
                                                        <label>Email</label>
                                                        <input name="email" id="editEmail" type="text" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group form-group-default">
                                                        <label>Address</label>
                                                        <input name="address" id="editAddress" type="text" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Contact</label>
                                                        <input name="contact" id="editContact" type="text" class="form-control" placeholder="09*********" />
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" name="btnSave" id="validateButton" class="btn btn-primary">Save changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>

                        <!--Add User Modal -->
                        <form action="" method="POST" id="createAccountForm">
                        <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title">
                                            <span class="fw-mediumbold"> Add</span>
                                            <span class="fw-light"> Supplier </span>
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="small">Fill all the necessary information</p>
                                        <form>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group form-group-default">
                                                        <label>Company/Business Name</label>
                                                        <input name="cbname" type="text" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 pe-0">
                                                    <div class="form-group form-group-default">
                                                        <label>First Name</label>
                                                        <input name="firstname" type="text" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Last Name</label>
                                                        <input name="lastname" type="text" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group form-group-default">
                                                        <label>Email</label>
                                                        <input name="email" type="text" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group form-group-default">
                                                        <label>Address</label>
                                                        <input name="address" type="text" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 pe-0">
                                                    <div class="form-group form-group-default">
                                                    <label>Gender</label>
                                                        <select name="gender" class="form-control" required>
                                                            <option selected disabled>Select...</option>
                                                            <option value="male">Male</option>
                                                            <option value="female">Female</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Contact</label>
                                                        <input name="contact" type="number" class="form-control" placeholder="09*********" />
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="submit" name="adduser" class="btn btn-primary">Add</button>
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
                                        <th>Business Name</th>
                                        <th>Representative Name</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Address</th>
                                        <th style="width: 10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    $sql = "SELECT * FROM supplier WHERE status = 1";
                                    $result = mysqli_query($conn, $sql);

                                    if ($result && mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $uid = $row['sid'];
                                            $cbname = $row['cbname'];
                                            $firstname = $row['firstname'];
                                            $lastname = $row['lastname'];
                                            $email = $row['email'];
                                            $name = $firstname . ' ' . $lastname;
                                            $gender = $row['gender'];
                                            $contact = $row['contact'];
                                            $address = $row['address'];
                                            $level = $row['levelid'];
                                        ?>
                                    <tr>                                    
                                        <td><?php echo $cbname ?></td>
                                        <td><?php echo $name ?></td>
                                        <td><?php echo $email ?></td>
                                        <td><?php echo $contact ?></td>
                                        <td><?php echo $address ?></td>                              
                                        <td>
                                            <div class="form-button-action">
                                                <a href="#" class="btn btn-link btn-success edit-button" data-bs-toggle="modal" data-bs-target="#editmodal" data-account-id="<?php echo $uid?>" data-account-fname="<?php echo $firstname?>" data-account-lname="<?php echo $lastname?>"
                                                    data-account-gender="<?php echo $gender?>" data-account-contact="<?php echo $contact?>" data-account-address="<?php echo $address?>" data-account-type="<?php echo $type?>" data-account-email="<?php echo $email?>" data-account-cbname="<?php echo $cbname?>">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="#" class="btn btn-link btn-primary archive-button" data-bs-toggle="modal" data-bs-target="#archivemodal" data-account-id="<?php echo $uid?>">
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
    <?php include('includes/footer.php'); ?>
    <?php include ('includes/tables.php');?>
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
