<?php 
    include "conn.php";
    
    if(isset($_POST['check-otp'])) {
        $otp_code = $_POST['otp'];
        $check_code = "SELECT * FROM useraccount WHERE otp = $otp_code";
        $code_res = mysqli_query($conn, $check_code);
        
        if (mysqli_num_rows($code_res) > 0) {
            $row = mysqli_fetch_assoc($code_res);
            $fetch_code = $row['otp'];
            $update_status = mysqli_query($conn, "UPDATE useraccount SET is_verified = 'Yes' WHERE otp = '$fetch_code'");
            
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    text: 'Email verification successfully!',
                    showConfirmButton: false
                });
                setTimeout(function() {
                    window.location.href = 'login.php';
                }, 2000); // 2 seconds delay
            });
            </script>";
        } 
        else {
            echo " <script> alert ('Incorrect Code'); document.location.href = 'verifyEmail.php'; </script>";  
        }

    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Code Verification</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    
    <style>
            <?php
                include "includes/verify.css"
            ?>
        </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form">
                <form action="" method="POST">
                    <h2 class="text-center">Email Verification</h2>
                    
                    <div class="form-group">
                        <input class="form-control" type="text" name="otp" placeholder="Enter otp code" required pattern="\d*" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                    </div>
                    <div class="form-group">
                        <input class="form-control button" type="submit" name="check-otp" value="Submit">
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</body>

    <!-- Sweet Alert-->
    <script src="main/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</html>