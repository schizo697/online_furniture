<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>MPM: Furniture Shop</title>
    <style>
        <?php include 'main/css/login.css'; ?>
        .required-asterisk {
            color: red;
        }

        body {
            background-image: url('img/background1.jpg');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            position: relative;
        }

        .btn-custom {
            background-color: #007bff;
            color: #fff;
            border-radius: 25px;
            padding: 10px 20px;
            box-shadow: 0 4px 6px rgba(0, 123, 255, 0.4);
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background-color: #0056b3;
            box-shadow: 0 6px 8px rgba(0, 123, 255, 0.6);
            transform: translateY(-2px);
        }
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
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['gender']) && isset($_POST['contact']) && isset($_POST['address']) && isset($_POST['username']) && isset($_POST['password'])) {
                        $first_name = $_POST['first_name'];
                        $last_name = $_POST['last_name'];
                        $gender = $_POST['gender'];
                        $contact = $_POST['contact'];
                        $email = $_POST['email'];
                        $address = $_POST['address'];
                        $city = $_POST['city'];
                        $postal = $_POST['postal'];
                        $username = $_POST['username'];
                        $password = $_POST['password'];
                        $encrypted = password_hash($password, PASSWORD_DEFAULT);

                        $check_username = "SELECT username FROM useraccount WHERE username = '$username'";
                        $check_result = mysqli_query($conn, $check_username);

                        if ($check_result && mysqli_num_rows($check_result) > 0) {
                            // SweetAlert exist
                            echo "<script>
                            document.addEventListener('DOMContentLoaded', function() {
                                Swal.fire({
                                    text: 'Username Already Exist',
                                    icon: 'warning',
                                    confirmButtonColor: '#3085d6',
                                });
                            });
                            </script>";
                        } else {
                            $sql = "INSERT INTO userinfo (firstname, lastname, gender, contact, address, email, city, postal) VALUES ('$first_name', '$last_name', '$gender', '$contact', '$address', '$email', '$city', '$postal')";

                            if ($conn->query($sql) === TRUE) {
                                $info_id = $conn->insert_id;
                                $sql = "INSERT INTO useraccount (username, password, levelid, infoid, status) VALUES ('$username', '$encrypted', 3, '$info_id', 1)";

                                if ($conn->query($sql) === TRUE) {
                                    // SweetAlert success
                                    echo "<script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        Swal.fire({
                                            position: 'center',
                                            icon: 'success',
                                            text: 'Account Created Successfully',
                                            showConfirmButton: false
                                        });
                                        setTimeout(function() {
                                            window.location.href = 'login.php';
                                        }, 2000); // 2 seconds delay
                                    });
                                    </script>";
                                } else {
                                    // SweetAlert error
                                    echo "<script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        Swal.fire({
                                            icon: 'error',
                                            text: 'Something went wrong!',
                                        });
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
                                        <div class="mb-2">
                                            <label for="first_name" class="form-label">First Name <span
                                                    class="required-asterisk">*</span></label>
                                            <input type="text" name="first_name" id="first_name"
                                                class="form-control form-control-lg bg-light fs-6"
                                                placeholder="First Name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label for="last_name" class="form-label">Last Name <span
                                                    class="required-asterisk">*</span></label>
                                            <input type="text" name="last_name" id="last_name"
                                                class="form-control form-control-lg bg-light fs-6"
                                                placeholder="Last Name" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label for="email" class="form-label">Email <span
                                                    class="required-asterisk">*</span></label>
                                            <input type="text" name="email" id="email"
                                                class="form-control form-control-lg bg-light fs-6" placeholder="Email"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label for="contact" class="form-label">Contact <span
                                                    class="required-asterisk">*</span></label>
                                            <input type="text" name="contact" id="contact"
                                                class="form-control form-control-lg bg-light fs-6" placeholder="Contact"
                                                required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label for="otp" class="form-label">OTP <span
                                                    class="required-asterisk">*</span></label>
                                            <input type="text" name="otp" id="otp"
                                                class="form-control form-control-lg bg-light fs-6" placeholder="OTP"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 d-flex align-items-end">
                                        <button type="button" class="btn btn-lg btn-custom">SEND NOW</button>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <fieldset class="form-group row">
                                        <legend class="col-form-label col-sm-2 float-sm-left pt-0">Gender</legend>
                                        <div class="col-sm-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="gender"
                                                    id="gridRadios1" value="Male" checked>
                                                <label class="form-check-label" for="gridRadios1">
                                                    Male
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="gender"
                                                    id="gridRadios2" value="Female">
                                                <label class="form-check-label" for="gridRadios2">
                                                    Female
                                                </label>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="mb-2">
                                        <label for="address" class="form-label">Address (street, barangay) <span
                                                class="required-asterisk">*</span></label>
                                        <input type="text" name="address" id="address"
                                            class="form-control form-control-lg bg-light fs-6" placeholder="Address"
                                            required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label for="city" class="form-label">City/Municipality<span
                                                    class="required-asterisk">*</span></label>
                                            <select name="city" id="city"
                                                class="form-control form-control-lg bg-light fs-6" required>
                                                <option selected disabled>Select...</option>
                                                <option value="Banga">Banga</option>
                                                <option value="General Santos">General Santos</option>
                                                <option value="Glan">Glan</option>
                                                <option value="Kiamba">Kiamba</option>
                                                <option value="Koronadal">Koronadal</option>
                                                <option value="Maitum">Maitum</option>
                                                <option value="Norala">Norala</option>
                                                <option value="Polomolok">Polomolok</option>
                                                <option value="Surallah">Surallah</option>
                                                <option value="Tantangan">Tantangan</option>
                                                <option value="Tupi">Tupi</option>
                                            </select>
                                            <!-- <input type="text" name="city" id="city" class="form-control form-control-lg bg-light fs-6" placeholder="City" required> -->
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label for="postal_code" class="form-label">Postal Code <span
                                                    class="required-asterisk">*</span></label>
                                            <input type="text" name="postal" id="postal_code"
                                                class="form-control form-control-lg bg-light fs-6"
                                                placeholder="Postal Code" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label for="username" class="form-label">Username <span
                                                    class="required-asterisk">*</span></label>
                                            <input type="text" name="username" id="username"
                                                class="form-control form-control-lg bg-light fs-6"
                                                placeholder="Username" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label for="password" class="form-label">Password <span
                                                    class="required-asterisk">*</span></label>
                                            <input type="password" name="password" id="password"
                                                class="form-control form-control-lg bg-light fs-6"
                                                placeholder="Password" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <button type="submit" name="submit"
                                                class="btn btn-lg btn-primary w-100 fs-6">Signup
                                                <span class="loading-text" style="display: none;">
                                                    <span class="spinner-border spinner-border-sm" role="status"
                                                        aria-hidden="true"></span>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="main/js/main.js"></script>

</html>