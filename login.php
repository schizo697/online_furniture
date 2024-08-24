<?php
session_start();

if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    session_destroy();
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>MPM: Furniture Shop</title>
    <style>
        body {
            background-image: url('img/background1.jpg');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            position: relative;
        }
    </style>
</head>

<body>

    <!----------------------- Main Container -------------------------->

    <div class="container d-flex justify-content-center align-items-center min-vh-100">

        <!----------------------- Login Container -------------------------->

        <div class="row border rounded-5 p-3 bg-white shadow box-area">

            <!--------------------------- Left Box ----------------------------->

            <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box"
                style="background-image: url('img/login.jpg'); background-size:cover;">
                <div class="featured-image mb-3">
                    <!-- <img src="img/background1.jpg" class="img-fluid" style="width: 250px;"> -->
                </div>
                <p class="text-white fs-2" style="font-family: 'Courier New', Courier, monospace; font-weight: 600;">MPM
                    Furniture</p>
                <small class="text-white text-wrap text-center"
                    style="width: 17rem; font-family: 'Courier New', Courier, monospace;">Find the best furniture on
                    this platform.</small>
            </div>

            <!-------------------- ------ Right Box ---------------------------->

            <div class="col-md-6 right-box">
                <div class="row align-items-center">
                    <div class="header-text mb-4">
                        <h2>Hello!</h2>
                        <p>We are happy to have you back.</p>
                    </div>
                    <?php
                    include 'conn.php'; // Include database connection
                    
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        if (isset($_POST['username'], $_POST['password'])) {
                            $username = $_POST['username'];
                            $password = $_POST['password'];

                            $escaped_username = mysqli_real_escape_string($conn, $username);

                            $sql = "SELECT password, levelid, uid FROM useraccount WHERE username = '$escaped_username'";
                            $result = mysqli_query($conn, $sql);

                            if ($result && mysqli_num_rows($result) == 1) {
                                $row = mysqli_fetch_assoc($result);
                                $hashed_password = $row['password'];

                                if (password_verify($password, $hashed_password)) {
                                    $_SESSION['username'] = $username;
                                    $_SESSION['uid'] = $row['uid'];

                                    $user_level = $row['levelid'];

                                    if ($user_level == 1) {
                                        header("Location: admin/index.php");
                                    } elseif ($user_level == 2) {
                                        header("Location: staff/index.php");
                                    } elseif ($user_level == 3) {
                                        header("Location: customer/index.php");
                                    }
                                    exit();
                                } else {
                                    $error = "Username or password is incorrect";
                                }
                            } else {
                                $error = "Incorrect Username or Password";
                            }
                        }
                    }
                    if (isset($error)) {
                        echo "<script>Swal.fire({ icon: 'error', text: '$error' });</script>";
                    }
                    ?>
                    <form action="" method="POST">
                        <div class="input-group mb-3">
                            <input name="username" type="text" class="form-control form-control-lg bg-light fs-6"
                                placeholder="Username">
                        </div>
                        <div class="input-group mb-1">
                            <input name="password" type="password" class="form-control form-control-lg bg-light fs-6"
                                placeholder="Password">
                        </div>
                        <div class="input-group mb-5 d-flex justify-content-between">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="formCheck">
                                <label for="formCheck" class="form-check-label text-secondary"><small>Remember
                                        Me</small></label>
                            </div>
                            <div class="forgot">
                                <small><a href="#">Forgot Password?</a></small>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <button type="submit" name="submit" class="btn btn-lg btn-primary w-100 fs-6">
                                Login
                                <span class="loading-text" style="display: none;">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                    Loading...
                                </span>
                            </button>
                        </div>
                        <div class="row">
                            <small>Don't have an account? <a href="signup.php">Sign Up</a></small>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</html>