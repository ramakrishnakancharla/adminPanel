<?php
include("config.php");
if(isset($_POST['Submittable'])){
	mysqli_query($con,"DELETE FROM `table_settings`") or die(mysql_error());
	$checkbox=$_POST['RemoveTable'];
	for($i=0;$i< sizeof($checkbox);$i++){
		$query="INSERT INTO `table_settings`(`TableName`, `Status`, `Txnuser`, `Txndate`) VALUES ('".$checkbox[$i]."',1,1,sysdate())";
		mysqli_query($con,$query) or die(mysql_error());
	}
	header("location: adminProfile.php");
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
  </head>
  <body>
  	<div class="header">
	     <?php include("header.php") ?>
	</div>

    <div class="page-content">
    	<div class="row">
		  <div class="col-md-3">
		  	<div class="sidebar content-box" style="display: block;">
                <ul class="nav">
					<?php
					if(tableNames()){
						$i=1;
						foreach(tableNames() as $key=>$value){
							if(table_user_settings($value['table_name'])['TableName'] !=$value['table_name']){
								$name = str_replace('_', ' ', $value['table_name']);
								$name = str_replace('-', ' ', $name);
							?>
								<li><a href="tableView.php?tb=<?php echo $value['table_name'];?>&activate=<?php echo $i;?>"><?php echo ucwords($name);?></a></li>
							<?php
							$i++;
							}
						}
					}
					?>
                </ul>
             </div>
		  </div>
		  <div class="col-md-9">
			<div class="row">
				<div class="col-md-8">
					<div class="content-box-large">
						<div class="panel-heading">
							<div class="panel-title">Admin Form</div>
						  
							<div class="panel-options">
							  <a href="#" data-rel="collapse"><i class="glyphicon glyphicon-refresh"></i></a>
							  <a href="#" data-rel="reload"><i class="glyphicon glyphicon-cog"></i></a>
							</div>
						</div>
						<div class="panel-body">
							<form action="adminProfile.php" method="post">
								<fieldset>
									<div class="form-group">
										<label>Name</label>
										<input class="form-control" value="<?php echo admin($_SESSION['slno'])['Name']?>"  type="text">
									</div>
									<div class="form-group">
										<label>Username</label>
										<input class="form-control" readonly value="<?php echo admin($_SESSION['slno'])['Username']?>"  type="email">
									</div>
									<div class="form-group">
										<label>Phone No.</label>
										<input class="form-control"  type="text" value="<?php echo admin($_SESSION['slno'])['Phone']?>">
									</div>
									<div class="form-group">
										<label>Address</label>
										<textarea class="form-control" placeholder="Textarea" rows="3"><?php echo admin($_SESSION['slno'])['Address']?></textarea>
									</div>
								</fieldset>
								<div>
									<input class="btn btn-primary" name="updateprofile" value="Submit"  type="submit">
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="content-box-large">
						<div class="panel-heading">
							<div class="panel-title">Table Names</div><br/><br/>
							<span style="color:red; text-align:justify;display:block">Not required table Select and delete for form only.It wont effect to your database</span>
						</div>
						<div class="panel-body">
							<form action="adminProfile.php" method="post">
								<fieldset>
								<?php
									if(tableNames()){
									$i=1;
									foreach(tableNames() as $key=>$value){
										if(table_user_settings($value['table_name'])['TableName'] !=$value['table_name']){
										?>
										 <div class="checkbox">
											<label><input type="checkbox" name="RemoveTable[]" value="<?php echo $value['table_name'];?>"> <?php echo $value['table_name'];?> </label>
										  </div>
										<?php
										$i++;
										}
										if(table_user_settings($value['table_name'])['TableName'] ==$value['table_name']){
											?>
										<div class="checkbox">
											<label><input type="checkbox" name="RemoveTable[]" checked value="<?php echo $value['table_name'];?>"> <del><?php echo $value['table_name'];?></del> </label>
										  </div>
										<?php
										}
									}
								}
								?>
								</fieldset>
								<div>
									<button class="btn btn-danger" type="submit" name="Submittable" style="float:right;"><i class="glyphicon glyphicon-remove"></i> Delete</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		  </div>
		</div>
    </div>

    <footer style="display:none;">
         <div class="container">
         
            <div class="copy text-center">
               Copyright 2014 <a href='#'>Website</a>
            </div>
            
         </div>
      </footer>

    <script src="js/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="js/custom.js"></script>
  </body>
</html>