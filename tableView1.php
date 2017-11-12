<?php
include("config.php");
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Bootstrap Admin Theme v3</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <link href="css/jquery.dataTables.min.css" rel="stylesheet">
	<style>
	div.dataTables_wrapper {
        width: 800px;
        margin: 0 auto;
    }
	</style>
  </head>
  <body>
  	<div class="header">
	     <div class="container">
	        <div class="row">
	           <div class="col-md-5">
	              <!-- Logo -->
	              <div class="logo">
	                 <h1><a href="index.html">Bootstrap Admin Theme</a></h1>
	              </div>
	           </div>
	           <div class="col-md-5">
	              <div class="row">
	                <div class="col-lg-12">
	                  <div class="input-group form">
	                       <input type="text" class="form-control" placeholder="Search...">
	                       <span class="input-group-btn">
	                         <button class="btn btn-primary" type="button">Search</button>
	                       </span>
	                  </div>
	                </div>
	              </div>
	           </div>
	           <div class="col-md-2">
	              <div class="navbar navbar-inverse" role="banner">
	                  <nav class="collapse navbar-collapse bs-navbar-collapse navbar-right" role="navigation">
	                    <ul class="nav navbar-nav">
	                      <li class="dropdown">
	                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">My Account <b class="caret"></b></a>
	                        <ul class="dropdown-menu animated fadeInUp">
	                          <li><a href="profile.html">Profile</a></li>
	                          <li><a href="login.html">Logout</a></li>
	                        </ul>
	                      </li>
	                    </ul>
	                  </nav>
	              </div>
	           </div>
	        </div>
	     </div>
	</div>

    <div class="page-content">
    	<div class="row">
		  <div class="col-md-2">
		  	<div class="sidebar content-box" style="display: block;">
                <ul class="nav">
					<?php
					if(tableNames()){
						$i=1;
						foreach(tableNames() as $key=>$value){
							$name = str_replace('_', ' ', $value['table_name']);
							$name = str_replace('-', ' ', $name);
							if(!empty($_REQUEST['activate']) AND $i==$_REQUEST['activate'] AND $value['table_name']==$_REQUEST['tb']){
								$active="active";
							}else{
								$active="";
							}
						?>
							<li><a class="<?php echo $active;?>" href="tableView.php?tb=<?php echo $value['table_name'];?>&activate=<?php echo $i;?>"><?php echo ucwords($name);?></a></li>
						<?php
						$i++;
						}
					}
					?>
                </ul>
             </div>
		  </div>
		  <div class="col-md-10">
			<div class="content-box-large">
  				<div class="panel-heading">
					<div class="panel-title">Bootstrap dataTables</div>
				</div>
  				<div class="panel-body">
  					<table id="example" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
						<thead>
							<tr>
							<?php
							if(columnNames($_REQUEST['tb'])){
								foreach(columnNames($_REQUEST['tb']) as $key=>$value){
									$name = str_replace('_', ' ', $value['COLUMN_NAME']);
									$name = str_replace('-', ' ', $name);
								?>
								<th><?php echo ucwords($name)?></th>
								<?php
								}
							}
							?>
							</tr>
						</thead>
						<tbody>
							<?php
							if(tableValues($_REQUEST['tb'])){
								foreach(tableValues($_REQUEST['tb']) as $key=>$value){
								?>
									<tr class="odd gradeX">
									<?php
									foreach(columnNames($_REQUEST['tb']) as $key1=>$value1){
									?>
										<th><?php echo custom_echo($value[$value1['COLUMN_NAME']],30);?></th>
									<?php
									}
									?>
									</tr>
								<?php
								}
							}
							?>
						</tbody>
					</table>
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
    <script src="js/jquery-1.12.0.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
	<script>
	$(document).ready(function() {
    $('#example').DataTable( {
        "scrollX": true
    } );
} );
	</script>
  </body>
</html>