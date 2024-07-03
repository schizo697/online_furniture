<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>MPM: Furniture Shop</title>
    <style>
        <?php include 'main/css/login.css'; ?>
    </style>
</head>
<body>

    <!----------------------- Main Container -------------------------->

     <div class="container d-flex justify-content-center align-items-center min-vh-100">

    <!----------------------- Login Container -------------------------->

       <div class="row border rounded-5 p-3 bg-white shadow box-area">


    <!-------------------- ------ Right Box ---------------------------->
        
       <div class="col-md-12 right-box">
          <div class="row align-items-center">
                <div class="header-text mb-4">
                     <h2>Register</h2>
                     <p>Find the perfect furniture today!</p>
                </div>
                <?php
                    include 'conn.php';
                    if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['gender']) && isset($_POST['contact']) && isset($_POST['address']) && isset($_POST['username']) && isset($_POST['password'])) {
                        $first_name = $_POST['first_name'];
                        $last_name = $_POST['last_name'];
                        $gender = $_POST['gender'];
                        $contact = $_POST['contact'];
                        $email = $_POST['email'];
                        $address = $_POST['address'];
                        $username = $_POST['username'];
                        $password = $_POST['password'];
                        $encrypted = password_hash($password, PASSWORD_DEFAULT);

                        $check_username = "SELECT username FROM useraccount WHERE username = '$username'";
                        $check_result = mysqli_query($conn, $check_username);

                        if ($check_result && mysqli_num_rows($check_result) > 0) {
                            // sweetalert exist
                            echo "<script>
                            Swal.fire({
                                text: 'Username Already Exist',
                                icon: 'warning',
                                confirmButtonColor: '#3085d6',
                            });
                            </script>";
                        } else {
                            $sql = "INSERT INTO userinfo (firstname, lastname, gender, contact, address, email) VALUES ('$first_name', '$last_name', '$gender', '$contact', '$address', '$email')";

                            if ($conn->query($sql) === TRUE) {
                                $info_id = $conn->insert_id;
                                $sql = "INSERT INTO useraccount (username, password, levelid, infoid, status) VALUES ('$username', '$encrypted', 3, '$info_id', 1)";

                                    if ($conn->query($sql) === TRUE) {
                                    // sweetalert success
                                    echo "<script>
                                        Swal.fire({
                                            position: 'center',
                                            icon: 'success',
                                            title: 'Account Created Successfully',
                                            showConfirmButton: false
                                        });
                                        setTimeout(function() {
                                            window.location.href = 'login.php';
                                        }, 2000); // 2 seconds delay
                                    </script>";
                                } else {
                                    // sweetalert error
                                    echo "<script>
                                        Swal.fire({
                                            icon: 'error',
                                            text: 'Something went wrong!',
                                        });
                                    </script>";
                                    }
                                }
                        }
                    }
                    ?>
                <div class="col-md-12">
                    <div class="row justify-content-center align-items-center">
                        <form action="" method="POST" class="w-100">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" name="first_name" id="first_name" class="form-control form-control-lg bg-light fs-6" placeholder="First Name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" name="last_name" id="last_name" class="form-control form-control-lg bg-light fs-6" placeholder="Last Name" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" name="email" id="email" class="form-control form-control-lg bg-light fs-6" placeholder="Email" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" name="contact" id="contact" class="form-control form-control-lg bg-light fs-6" placeholder="Contact" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <fieldset class="form-group row">
                                    <legend class="col-form-label col-sm-2 float-sm-left pt-0">Gender</legend>
                                    <div class="col-sm-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender" id="gridRadios1" value="Male" checked>
                                            <label class="form-check-label" for="gridRadios1">
                                                Male
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender" id="gridRadios2" value="Female">
                                            <label class="form-check-label" for="gridRadios2">
                                                Female
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input type="text" name="address" id="address" class="form-control form-control-lg bg-light fs-6" placeholder="Address" required>
                                </div>
                            </div><br>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" name="username" id="username" class="form-control form-control-lg bg-light fs-6" placeholder="Username" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="password" name="password" id="password" class="form-control form-control-lg bg-light fs-6" placeholder="Password" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <button type="submit" name="submit" class="btn btn-lg btn-primary w-100 fs-6">Signup
                                            <span class="loading-text" style="display: none;">
                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                Loading...
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <small>Already have an account? <a href="login.php">Login</a></small>
                            </div>
                        </form>
                    </div>
                </div>
          </div>
       </div> 

      </div>
    </div>

</body>
<script src="main/js/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</html>