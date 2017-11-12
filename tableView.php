<?php
include("config.php");
$name = str_replace('_', ' ', $_REQUEST['tb']);
$name = str_replace('-', ' ', $name);
$tableName=ucwords($name);
if(isset($_POST['Submitcoulmn'])){
	mysqli_query($con,"DELETE FROM `column_settings` where TableName='".$_POST['tb']."'") or die(mysql_error());
	$checkbox=$_POST['Removecolumn'];
	for($i=0;$i< sizeof($checkbox);$i++){
		$query="INSERT INTO `column_settings`(`TableName`, `ColumName`, `Status`, `Txnuser`, `Txndate`) VALUES ('".$_POST['tb']."','".$checkbox[$i]."',1,1,sysdate())";
		mysqli_query($con,$query) or die(mysql_error());
	}
	header("location: tableView.php?tb=".$_POST['tb']."&activate=".$_POST['activate']);
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title><?php echo $tableName;?> Data</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="css/jquery-ui.css" rel="stylesheet" media="screen">
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
								<li><a class="<?php echo $active;?>" href="tableView.php?tb=<?php echo $value['table_name'];?>&activate=<?php echo $i;?>"><?php echo ucwords($name);?></a></li>
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
				<div class="col-md-9">
					<div class="content-box-large">
						<div class="panel-heading">
							<div class="panel-title"><?php echo $tableName;?>  → <a href="tableFrom.php?tb=<?php echo $_REQUEST['tb'];?>&activate=<?php echo $_REQUEST['activate'];?>"><button class="btn btn-success">Add </button></a></div>
						</div>
						<div class="panel-body" style="overflow-x: auto;">
							<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example">
								<thead>
									<tr>
									<th>Slno.</th>
									<?php
									if(columnNames($_REQUEST['tb'])){
										if(foreignkey($_REQUEST['tb'])){
											foreach(foreignkey($_REQUEST['tb']) as $foreignkey){
												?>
												<th><?php echo ucwords($foreignkey['ReferencedTableName'])." Name";?></th>
												<?php
											}
										}
										foreach(columnNames($_REQUEST['tb']) as $key=>$value){
											if(column_user_settings($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName'] !=$value['COLUMN_NAME'] AND $value['COLUMN_NAME'] !=primarykey($_REQUEST['tb'])['COLUMN_NAME']){
												if($value['COLUMN_NAME'] !='Txndate'){
													$name = str_replace('_', ' ', $value['COLUMN_NAME']);
													$name = str_replace('-', ' ', $name);
													?>
													<th><?php echo ucwords($name)?></th>
													<?php
												}
											}
										}
									}
									?>
									<th>Actions</th>
									</tr>
								</thead>
								<tbody>
									<?php
									if(tableValues($_REQUEST['tb'])){
										$j=1;
										foreach(tableValues($_REQUEST['tb']) as $key=>$value){
										?>
											<tr class="odd gradeX">
											<td><?php echo $j;?></td>
											<?php
											foreach(columnNamesView($_REQUEST['tb']) as $key1=>$value1){
												if(column_user_settings($_REQUEST['tb'],$value1['COLUMN_NAME'])['ColumName'] !=$value1['COLUMN_NAME'] AND $value1['COLUMN_NAME'] !=primarykey($_REQUEST['tb'])['COLUMN_NAME']){
													if($value1['COLUMN_NAME'] !='Txndate'){
														//$value1['COLUMN_NAME'];
														if(foreignkeynamefind($value1['COLUMN_NAME'])){
															$tableName=foreignkeynamefind($value1['COLUMN_NAME'])['TableName'];
															$colName=foreignkeynamefind($value1['COLUMN_NAME'])['ColumnName'];
															$wherecolName=foreignkeynamefind($value1['COLUMN_NAME'])['ColumnID'];
															$colValue=$value[$value1['COLUMN_NAME']];
															
															$data1=foreignkeynameview($tableName,$colName,$wherecolName,$colValue);
															$data=$data1[$colName];
														}else{
															$data=$value[$value1['COLUMN_NAME']];
														}
														
														if($value1['COLUMN_NAME']=='Status'){
															if($value[$value1['COLUMN_NAME']]==1){
																$data="Active";
															}else{
																$data="In-Active";
															}
														}elseif($value1['COLUMN_NAME']=='CreatedBy'){
																$data=admin($value[$value1['COLUMN_NAME']])['Name'];
														}
														?>
														<td title="<?php echo $value[$value1['COLUMN_NAME']];?>"><?php echo custom_echo($data,23);?></td>
														<?php
													}
												}
											}
											?>
											<td>
												<a class="btn btn-default btn-xs" href="dataview.php?tb=<?php echo $_REQUEST['tb'];?>&activate=<?php echo $_REQUEST['activate'];?>&id=<?php echo $value[primarykey($_REQUEST['tb'])['COLUMN_NAME']]?>"><i class="glyphicon glyphicon-eye-open" title="View"></i></a>
												<a class="btn btn-primary btn-xs" style="color:#fff;" href="dataedit.php?tb=<?php echo $_REQUEST['tb'];?>&activate=<?php echo $_REQUEST['activate'];?>&id=<?php echo $value[primarykey($_REQUEST['tb'])['COLUMN_NAME']]?>"><i class="glyphicon glyphicon-pencil" title="Edit"></i></a>
												<a class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove" onclick="deleterecordclick('<?php echo $_REQUEST['tb'];?>','<?php echo $value[primarykey($_REQUEST['tb'])['COLUMN_NAME']]?>');" title="Delete"></i></a></td>
											</tr>
										<?php  
										$j++;
										}
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="content-box-large">
						<div class="panel-heading">
							<div class="panel-title">Column Name Views</div><br/><br/>
							<span style="color:red; text-align:justify;display:block">Not required Columns Select and delete for table view only.It wont effect to your database</span>
						</div>
						<div class="panel-body">
							<form action="tableView.php" method="post">
								<fieldset>
									<div class="col-sm-offset-2 col-sm-10">
									  <?php
										if(columnNames($_REQUEST['tb'])){
											foreach(columnNames($_REQUEST['tb']) as $key=>$value){
												if(column_user_settings($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName'] !=$value['COLUMN_NAME'] AND $value['COLUMN_NAME'] !=primarykey($_REQUEST['tb'])['COLUMN_NAME']){
													if($value['COLUMN_NAME'] !='Txndate'){
														?>
														 <div class="checkbox">
															<label><input type="checkbox" name="Removecolumn[]" value="<?php echo $value['COLUMN_NAME'];?>"> <?php echo $value['COLUMN_NAME'];?> </label>
														  </div>
														<?php
													}
												}if(column_user_settings($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName'] ==$value['COLUMN_NAME']){
												?>
												 <div class="checkbox">
													<label><input type="checkbox" name="Removecolumn[]" checked value="<?php echo $value['COLUMN_NAME'];?>"> <del><?php echo $value['COLUMN_NAME'];?> </del></label>
												  </div>
												<?php
												}
											}
										}
									  ?>
									</div>
									<input type="hidden" name="tb" value="<?php echo $_REQUEST['tb'];?>">
									<input type="hidden" name="activate" value="<?php echo $_REQUEST['activate'];?>">
								</fieldset>
								<br/>
								<div>
									<button class="btn btn-danger" type="submit" name="Submitcoulmn" style="float:right;"><i class="glyphicon glyphicon-remove"></i> Delete</button>
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

    <link href="vendors/datatables/dataTables.bootstrap.css" rel="stylesheet" media="screen">
    <script src="js/jquery.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="vendors/datatables/js/jquery.dataTables.min.js"></script>
    <script src="vendors/datatables/dataTables.bootstrap.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/tables.js"></script>
	<script>
	function deleterecordclick(tbs,idslno) {
	  var r = confirm("Are you sure ! you want delete..!");
	  if (r == true) {
		var xhttp = new XMLHttpRequest();
		  xhttp.onreadystatechange = function() {	
			if (xhttp.readyState == 4 && xhttp.status == 200) {
			  alert(xhttp.responseText);
			  alert("Successfully deleted record..!");
			  location.reload();
			}
		  };
		  xhttp.open("GET", "deleterecords.php?tbs="+tbs+"&idslno="+idslno, true);
		  xhttp.send();
	  }
	  
	}
	</script>
  </body>
</html>