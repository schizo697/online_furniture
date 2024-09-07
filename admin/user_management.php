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
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $gender = $_POST['gender'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $contact = $_POST['contact'];
        $usertype = $_POST['usertype'];
        $encrypted_password = password_hash($password, PASSWORD_DEFAULT);

        $check_username = "SELECT username FROM useraccount WHERE username = '$username'";
        $check_result = mysqli_query($conn, $check_username);

        if($check_result && mysqli_num_rows($check_result) > 0) {
            // Redirect to user_management.php with exist=true parameter
            $url = "user_management.php?exist=true";
            echo '<script>window.location.href = "' . $url . '";</script>';
            exit(); // Exiting to prevent further execution
        } else {
            $sql = "INSERT INTO userinfo (firstname, lastname, contact, address, gender, email) VALUES ('$firstname', '$lastname', '$contact', '$address', '$gender', '$email')";
            if(mysqli_query($conn, $sql)) {
                $info_id = mysqli_insert_id($conn);
                $sql = "INSERT INTO useraccount (username, password, levelid, infoid, is_verified, status) VALUES ('$username', '$encrypted_password', '$usertype', '$info_id', 'Yes', 1)";
            
                    if(mysqli_query($conn, $sql)) {
                        $url = "user_management.php?success=true";
                        echo '<script>window.location.href= "' . $url . '";</script>';
                        exit(); 
                    } else {
                        $url = "user_management.php?error=true";
                        echo '<script>window.location.href="' . $url . '";</script';
                        exit();
                    }
                
            }
        }
    }
?>

<!-- handle edit user -->
    <?php
      if(isset($_POST['btnSave'])){
        $user_id = $_POST['userid'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $contact = $_POST['contact'];
        $address = $_POST['address'];
        $type = $_POST['usertype'];

            $infoupdate = "UPDATE userinfo SET firstname = '$firstname', lastname = '$lastname', contact = '$contact', address = '$address', email = '$email' WHERE infoid = '$user_id'";
            $inforesult = mysqli_query($conn, $infoupdate);

                if($inforesult){

                    $userupdate = "UPDATE useraccount SET levelid = '$type' WHERE uid = '$user_id'";
                    $updateres = mysqli_query($conn, $userupdate);

                    if($updateres){
                        $url = 'user_management.php?update=true';
                        echo '<script>window.location.href= "' . $url . '"</script>';
                        exit();
                    } else {
                        $url = "user_management.php?error=true";
                        echo '<script>window.location.href="' . $url . '";</script';
                        exit();
                    }
                } else {
                    $url = "user_management.php?error=true";
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
                    <h3 class="fw-bold mb-3">User Management</h3>
                </div>
                <div class="ms-md-auto py-2 py-md-0"></div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List of User</h4>
                            <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addRowModal">
                                <i class="fa fa-plus"></i> Add User
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

                                    $statusupdate = "UPDATE useraccount SET status = 0 WHERE uid = '$user_id'";
                                    $statusresult = mysqli_query($conn, $statusupdate);

                                    if($statusresult){
                                        $url = "user_management.php?archive=true";
                                        echo '<script>window.location.href="' . $url . '"</script>';
                                        exit();
                                    } else {
                                        $url = "user_management.php?error=true";
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
                                            <span class="fw-light"> User Information </span>
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="small">Fill all the necessary information</p>
                                        <form>
                                            <div class="row">
                                            <div class="col-md-6 pe-0">
                                                    <input type="hidden" name="userid" id="userID" type="text" class="form-control" placeholder="" readonly/>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group form-group-default">
                                                    <label>User Type</label>
                                                        <select name="usertype" class="form-control" required>
                                                            <option selected disabled>Select...</option>
                                                            <option value="2">Staff</option>
                                                            <option value="3">Customer</option>
                                                        </select>
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
                                                <div class="col-md-12">
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
                                            <span class="fw-light"> User </span>
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="small">Fill all the necessary information</p>
                                        <form>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group form-group-default">
                                                    <label>User Type</label>
                                                        <select name="usertype" class="form-control" required>
                                                            <option selected disabled>Select...</option>
                                                            <option value="2">Staff</option>
                                                            <option value="3">Customer</option>
                                                        </select>
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
                                                <div class="col-md-12">
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
                                                        <input name="contact" type="text" class="form-control" placeholder="09*********" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 pe-0">
                                                    <div class="form-group form-group-default">
                                                        <label>User Name</label>
                                                        <input name="username" type="text" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Password</label>
                                                        <input name="password" type="text" class="form-control" placeholder="********" />
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
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
                                        <th>Full Name</th>
                                        <th>Address</th>
                                        <th>Phone Number</th>
                                        <th>Type of User</th>
                                        <th style="width: 10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    $sql = "SELECT uid, username, email, firstname, lastname, gender, contact, address,city, levelid, status
                                    FROM useraccount 
                                    JOIN userinfo ON useraccount.infoid = userinfo.infoid 
                                    WHERE useraccount.status = 1 AND useraccount.levelid IN (1, 2)";
                                    $result = mysqli_query($conn, $sql);

                                    if ($result && mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $uid = $row['uid'];
                                            $firstname = $row['firstname'];
                                            $lastname = $row['lastname'];
                                            $name = $firstname . ' ' . $lastname;
                                            $username = $row['username'];
                                            $email = $row['email'];
                                            $gender = $row['gender'];
                                            $contact = $row['contact'];
                                            $address = $row['address'];
                                            $city = $row['city'];
                                            $address1 = $address . ' ' . $city;
                                            $level = $row['levelid'];
                                            $type = '';
                                            switch ($level) {
                                                case 1:
                                                    $type = 'Admin';
                                                    break;
                                                case 2:
                                                    $type = 'Staff';
                                                    break;
                                               
                                                default:
                                                    $type = 'Unknown';
                                                    break;
                                            }
                                        ?> 
                                    <tr>                                     
                                        <td><?php echo $name ?></td>
                                        <td><?php echo $address1 ?></td>                              
                                        <td><?php echo $contact ?></td>
                                        <td><?php echo $type ?></td>
                                        <td>
                                            <div class="form-button-action">
                                                <a href="#" class="btn btn-link btn-success edit-button" data-bs-toggle="modal" data-bs-target="#editmodal" data-account-id="<?php echo $uid?>" data-account-fname="<?php echo $firstname?>" data-account-lname="<?php echo $lastname?>"
                                                    data-account-gender="<?php echo $gender?>" data-account-contact="<?php echo $contact?>" data-account-address="<?php echo $address?>" data-account-type="<?php echo $type?>" data-account-email="<?php echo $email?>">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="#" class="btn btn-link btn-danger archive-button" data-bs-toggle="modal" data-bs-target="#archivemodal" data-account-id="<?php echo $uid?>">
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

        $('#userID').val(userID);
        $('#editFirstName').val(fname);
        $('#editLastName').val(lname);
        $('#editContact').val(contact);
        $('#editAddress').val(address);
        $('#editEmail').val(email);
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
