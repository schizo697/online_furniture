<?php 
session_start();
include('../conn.php'); // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $user_id = $_SESSION['uid'];
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    $update_sql = "UPDATE userinfo SET firstname='$firstname', lastname='$lastname', gender='$gender', contact='$contact', address='$address' WHERE infoid='$user_id'";
    
    if (mysqli_query($conn, $update_sql)) {
        echo "<script>alert('Profile updated successfully.');</script>";
    } else {
        echo "<script>alert('Error updating profile.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('includes/topbar.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</head>
<body>
    <?php include('includes/sidebar.php')?>

    <!-- Header -->
    <?php include('includes/header.php'); ?>

    <!-- Main Content -->
    <div class="container-xl px-4 mt-4">
        <!-- Account page navigation-->
        <nav class="nav nav-borders">
            <a class="nav-link active ms-0" href="profile.php">Profile</a>
            <a class="nav-link" href="purchase.php">My Purchase</a>
        </nav>
        <hr class="mt-0 mb-4">
        <div class="row">
            <div class="col-xl-4">
                <!-- Profile picture card-->
                <div class="card mb-4 mb-xl-0">
                    <div class="card-header">Profile Picture</div>
                    <div class="card-body text-center">
                        <!-- Profile picture image-->
                        <img class="img-account-profile rounded-circle mb-2" src="http://bootdey.com/img/Content/avatar/avatar1.png" alt="">
                        <!-- Profile picture help block-->
                        <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>
                        <!-- Profile picture upload button-->
                        <button class="btn btn-primary" type="button">Upload new image</button>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <!-- Account details card-->
                <div class="card mb-4">
                    <div class="card-header">Account Details</div>
                    <div class="card-body">
                        <?php 
                        if(isset($_SESSION['uid'])){
                            $user_id = $_SESSION['uid'];
                            
                            $sql = "SELECT userinfo.firstname, userinfo.lastname, userinfo.gender, userinfo.contact, userinfo.address, useraccount.username FROM userinfo
                            JOIN useraccount ON userinfo.infoid = useraccount.infoid
                            WHERE userinfo.infoid = '$user_id'";
                            $result = mysqli_query($conn, $sql);

                            if($result && mysqli_num_rows($result) > 0){
                                $row = mysqli_fetch_assoc($result);
                        ?> 
                                <form>
                                    <div class="mb-3">
                                        <label class="small mb-1" for="inputUsername">Username:</label>
                                        <input class="form-control" name="username" type="text" placeholder="Enter your username" value="<?php echo $row['username'] ?>" readonly>
                                    </div>
                                    <div class="row gx-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="small mb-1" for="inputFirstName">First name</label>
                                            <input class="form-control" name="firstname" type="text" placeholder="Enter your first name" value="<?php echo $row['firstname'] ?>" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="small mb-1" for="inputLastName">Last name</label>
                                            <input class="form-control" name="lastname" type="text" placeholder="Enter your last name" value="<?php echo $row['lastname'] ?>" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="small mb-1" for="inputGender">Gender</label>
                                            <input class="form-control" name="gender" type="text" placeholder="Enter your gender" value="<?php echo $row['gender'] ?>" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="small mb-1" for="inputPhone">Phone number</label>
                                            <input class="form-control" name="contact" type="tel" placeholder="Enter your phone number" value="<?php echo $row['contact'] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="small mb-1" for="inputAddress">Address</label>
                                        <input class="form-control" name="address" type="text" placeholder="Enter your address" value="<?php echo $row['address'] ?>" readonly>
                                    </div>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editAccount">Edit</button>

                                    <button type="submit" class="btn btn-danger" formaction="logout.php">Logout</button>
                                </form>
                        <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Account Modal -->
    <div class="modal fade" id="editAccount" tabindex="-1" aria-labelledby="editAccountLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editAccountLabel">Edit Account Information</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="small mb-1" for="editFirstName">First name</label>
                            <input class="form-control" name="firstname" type="text" placeholder="Enter your first name" value="<?php echo $row['firstname'] ?>">
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="editLastName">Last name</label>
                            <input class="form-control" name="lastname" type="text" placeholder="Enter your last name" value="<?php echo $row['lastname'] ?>">
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="editGender">Gender</label>
                            <input class="form-control" name="gender" type="text" placeholder="Enter your gender" value="<?php echo $row['gender'] ?>">
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="editPhone">Phone number</label>
                            <input class="form-control" name="contact" type="tel" placeholder="Enter your phone number" value="<?php echo $row['contact'] ?>">
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="editAddress">Address</label>
                            <input class="form-control" name="address" type="text" placeholder="Enter your address" value="<?php echo $row['address'] ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="update_profile" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <!-- <?php include('includes/footer.php'); ?> -->
</body>
</html>
