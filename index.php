<?php
include("config.php");
if(isset($_SESSION["slno"])){
	header("location:adminProfile.php");
}
$error="";
if(isset($_POST['SysLogin'])){
$user_email = trim(strip_tags($_POST['emailaddress']));
$user_password = trim(strip_tags($_POST['password']));
$encrypted_md5_password = md5($user_password);
$validate_user_information = mysqli_query($con,"select * from `admin_login` where `Username` = '".$user_email."' and `Password` = '".$encrypted_md5_password."' AND Status='1'");
if($user_email == "" || $user_password == "")
{
$error = 'Sorry, all fields are required to log into your account.';
}
elseif(mysqli_num_rows($validate_user_information) == 1)
{
$get_user_information = mysqli_fetch_array($validate_user_information);
$_SESSION["slno"] = $get_user_information['AL'];
$_SESSION["name"] = $get_user_information['Name'];
header("location:adminProfile.php");
}
elseif(mysqli_num_rows($validate_user_information) == 0)
{
$error = 'Sorry, your user-name or password is wrong.';
}
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Admin Panel Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
  </head>
  <body class="login-bg">
	<div class="page-content container">
		<div class="row">
			<div class="col-md-4 col-md-offset-4 marginTop80">
				<div class="login-wrapper">
			        <div class="box">
			            <div class="content-wrap">
							<form action="index.php" method="post">
								<h6>Sign In</h6>
								<input class="form-control" type="text" name="emailaddress" required placeholder="E-mail address">
								<input class="form-control" type="password" name="password" required placeholder="Password">
								<span style="color:red;"><?php echo $error;?></span>
								<div class="action">
									<input type="submit" class="btn btn-primary signup"  name="SysLogin" value="Login">
								</div>  
							</form>
			            </div>
			        </div>

			        <div class="already">
			            <p>Don't have access your account?</p>
			            <a href="#">Retrieve Password</a>
			        </div>
			    </div>
			</div>
		</div>
	</div>

	<script src="js/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="js/custom.js"></script>
  </body>
</html>