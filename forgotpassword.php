<?php
include 'conn.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

	if(isset($_REQUEST['pwdrst'])) {
        $mail = new PHPMailer(true);
		$email = $_REQUEST['email'];
		$check_email = mysqli_query($conn, "SELECT email FROM userinfo WHERE email = '$email'");
			if ($check_email) {
				$message = '<div>
				<p><b> Hello! </b> </p>
				<p> You are receiving this email because we received a password reset
				request for your account. </p>
				<br>
				<p><button class = "btn btn-primary"> 
				<a href="https://localhost/online_furniture/passswordreset.php?secret='.base64_encode($email).'"> 
				Reset Password </a> </button> </p>
				<br>
				<If you did not request a password reset, no further action is required. </p>
				</div>';

				 $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = "mpmfurnitureshop@gmail.com";
        $mail->Password = "ekmzoucpklddccim";
        $mail->SMTPSecure = 'ssl';
        $mail->Port= 465;

        $mail->setFrom('mpmfurnitureshop@gmail.com');

        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = "Password Reset";
        $mail->Body = $message;

        $mail->send();

				if ($mail->send()) {
					$msg = "We have e-mailed your password reset link!";
				}

			}else {
				$msg = "We cant find a user with that email address";
			}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="css/style.css">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet">

	<script src="https://kit.fontawesome.com/a81368914c.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
	<img class="wave" src="photos/wave.png">
	<div class="container">
		<div class="img">
			
	    </div>
		<div class="login-container box">
			<form id = "validate" action="#" method = "POST">
				<h3>Forgot Password</h3>
			
           		<div class="input-div one ">
           		   <div class="i">
           		   		<i class="fas fa-user"></i>
           		   </div>
           		   <div class="div">
           		   		<h5>Enter Email</h5>
           		   		<input class="input form-control" type="text" name = "email" id = "email" required
						data-parsley-type = "email" data-parsley-trigger = "keyup">
           		   </div>
           		</div>
            	<input type="submit" id= "login" class="btn btn-success" value="Send Reset Link" name = "pwdrst">
				<p class = "error">
					<?php
						if(!empty($msg)) {
							echo $msg;
						}
					?>
				</p>
            </form>
        </div>

		
    </div>
    
    <script src="js/index.js"></script>
</body>
</html>