<?php
if($_SESSION["slno"] ==''){
	header("location:logout.php");
}
?>
<div class="container">
	<div class="row">
	   <div class="col-md-10">
		  <!-- Logo -->
		  <div class="logo">
			 <h1><a href="adminProfile.php">Admin Panel</a></h1>
		  </div>
	   </div>
	   <div class="col-md-2">
		  <div class="navbar navbar-inverse" role="banner">
			  <nav class="collapse navbar-collapse bs-navbar-collapse navbar-right" role="navigation">
				<ul class="nav navbar-nav">
				  <li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $_SESSION["name"];?><b class="caret"></b></a>
					<ul class="dropdown-menu animated fadeInUp">
					  <li><a href="adminProfile.php">Settings</a></li>
					  <li><a href="logout.php">Logout</a></li>
					</ul>
				  </li>
				</ul>
			  </nav>
		  </div>
	   </div>
	</div>
 </div>