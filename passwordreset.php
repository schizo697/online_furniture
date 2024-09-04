<?php
include "dbconn.php";

    if(isset($_REQUEST['pwdrst'])) {
        $email = $_REQUEST['email'];
        $pwd = md5($_REQUEST['pwd']);
        $cpwd = md5($_REQUEST['cpwd']);
            if ($pwd == $cpwd) {

                $sql = "UPDATE user SET password = '$pwd' WHERE email = '$email'";
                $reset_pwd = mysqli_query($conn, $sql);

                    if ($reset_pwd > 0) {
                        $msg = 'Your password updated successfully;
                        <a href = "index.php"> Click Here </a>
                        to login ';
                    } else {
                        $msg = "Error while updating password.";
                    }
            }
    } else {
        $msg = "Password and Confirm Password do not match";
    }

    if($_GET['secret']) {
        $email = base64_decode($_GET['secret']);
        $check_details = mysqli_query($conn, "SELECT email FROM user WHERE email = '$email'");
        $res = mysqli_num_rows($check_details);
            if($res > 0) { ?>

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
		<div class="login-container">
			<form id = "validate_form" action="#" method = "POST">
			<input type = "hidden" name = "email" value = "<?php echo $email; ?>" />
				<img class="avatar" src="photos/avatar.svg">
				<h2>Reset Password</h2>
           		<div class="input-div one ">
           		   <div class="i">
           		   		<i class="fas fa-lock"></i>
           		   </div>
           		   <div class="div">
           		   		<h5>Password</h5>
           		   		<input id = "pwd" class="input form-control" type="password" name = "pwd" required
                        data-parsley-type = "pwd" data-parsley-trigger = "keyup" >
           		   </div>
           		</div>
           		<div class="input-div two ">
           		   <div class="i"> 
           		    	<i class="fas fa-lock"></i>
           		   </div>
           		   <div class="div">
           		    	<h5>Password</h5>
           		    	<input id = "cpwd" class="input form-control" type="password" name = "cpwd" required
                        data-parsley-type = "cpwd" data-parsley-trigger = "keyup" >
            	   </div>
            	</div>
            	<input type="submit" id = "login" class="btn btn-success" value="Submit" name = "pwdrst">
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
<?php } } ?>
    <script src="js/index.js"></script>
</body>
</html>