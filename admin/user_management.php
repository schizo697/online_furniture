<!-- handle add user -->
<?php
    include '../conn.php';

    if(isset($_POST['adduser'])) {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $gender = $_POST['gender'];
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
            $sql = "INSERT INTO userinfo (firstname, lastname, contact, address, gender) VALUES ('$firstname', '$lastname', '$contact', '$address', '$gender')";
            if(mysqli_query($conn, $sql)) {
                $info_id = mysqli_insert_id($conn);
                $sql = "INSERT INTO useraccount (username, password, levelid, infoid, status) VALUES ('$username', '$encrypted_password', $usertype, $info_id, 1)";
            
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
                        <form action="" method="POST" id="createAccountForm">
                        <!--Add User Modal -->
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
                                                            <option value="1">Admin</option>
                                                            <option value="2">Staff</option>
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
                                        <th>ID</th>
                                        <th>Full Name</th>
                                        <th>Address</th>
                                        <th>Phone Number</th>
                                        <th>Type of User</th>
                                        <th style="width: 10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>                                     
                                        <td>1</td>
                                        <td>Mary Loi Yves Ricalde</td>
                                        <td>STI Gensan</td>                              
                                        <td>09486711308</td>
                                        <td>Staff</td>
                                        <td>
                                            <div class="form-button-action">
                                                <button type="button" data-bs-toggle="tooltip" title="View Details" class="btn btn-link btn-primary btn-lg">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>                                     
                                        <td>2</td>
                                        <td>Mikhaela Janna Lim</td>
                                        <td>Lagao</td>                              
                                        <td>0948816564</td>
                                        <td>Customer</td>
                                        <td>
                                            <div class="form-button-action">
                                                <button type="button" data-bs-toggle="tooltip" title="View Details" class="btn btn-link btn-primary btn-lg">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
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
    <?php include ('includes/tables.php');?>
</body>

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
