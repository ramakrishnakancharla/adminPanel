<?php
include("config.php");
$name = str_replace('_', ' ', $_REQUEST['tb']);
$name = str_replace('-', ' ', $name);
$tableName=ucwords($name);
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Bootstrap Admin Theme v3</title>
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
		  <div class="col-md-2">
		  	<div class="sidebar content-box" style="display: block;">
                <ul class="nav">
					<?php
					if(tableNames()){
						$i=1;
						foreach(tableNames() as $key=>$value){
							if(table_user_settings($value['table_name'])['TableName'] !=$value['table_name']){
								$name = str_replace('_', ' ', $value['table_name']);
								$name = str_replace('-', ' ', $name);
								if(!empty($_REQUEST['activate']) AND $i==$_REQUEST['activate'] AND $value['table_name']==$_REQUEST['tb']){
									$active="active";
								}else{
									$active="";
								}
							?>
								<li><a class="<?php echo $active;?>"  href="tableView.php?tb=<?php echo $value['table_name'];?>&activate=<?php echo $i;?>"><?php echo ucwords($name);?></a></li>
							<?php
							$i++;
							}
						}
					}
					?>
                </ul>
             </div>
		  </div>
		  <div class="col-md-10">
			<div class="row">
				<div class="col-md-8">
					<div class="content-box-large">
						<div class="panel-heading">
							<div class="panel-title"><?php echo $tableName;?> View</div>
						</div>
						<div class="panel-body">
							<table class="table table-bordered">
								<?php
								if(columnNames($_REQUEST['tb'])){
									foreach(columnNames($_REQUEST['tb']) as $key=>$value){
										$name = str_replace('_', ' ', $value['COLUMN_NAME']);
										$name = str_replace('-', ' ', $name);
										
										$data=dataview($_REQUEST['tb'],primarykey($_REQUEST['tb'])['COLUMN_NAME'],$_REQUEST['id'])[$value['COLUMN_NAME']];
										if($value['COLUMN_NAME']=='Status'){
											if(dataview($_REQUEST['tb'],primarykey($_REQUEST['tb'])['COLUMN_NAME'],$_REQUEST['id'])[$value['COLUMN_NAME']]==1){
												$data="Active";
											}else{
												$data="In-Active";
											}
										}elseif($value['COLUMN_NAME']=='CreatedBy'){
												$data=admin(dataview($_REQUEST['tb'],primarykey($_REQUEST['tb'])['COLUMN_NAME'],$_REQUEST['id'])[$value['COLUMN_NAME']])['Name'];
										}elseif($value['COLUMN_NAME']=='FileName'){
												if(dataview($_REQUEST['tb'],primarykey($_REQUEST['tb'])['COLUMN_NAME'],$_REQUEST['id'])[$value['COLUMN_NAME']] !=''){
													$imagedisplay="uploadfiles/".dataview($_REQUEST['tb'],primarykey($_REQUEST['tb'])['COLUMN_NAME'],$_REQUEST['id'])[$value['COLUMN_NAME']];
												}else{
													$imagedisplay="images/noimage.png";
												}
												$data='<img src="'.$imagedisplay.'" style="width:100px; height:90px; margin-left:10px;">';
										}
										?>
										<tr><th style="background-color: #f9f9f9;"><?php echo ucwords($name)?></th><td><?php echo $data?></td></tr>
										<?php
									}
								}
								?>
							</table>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="content-box-large">
						<div class="panel-body">
							
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