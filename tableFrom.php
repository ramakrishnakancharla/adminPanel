<?php
include("config.php");
$name = str_replace('_', ' ', $_REQUEST['tb']);
$name = str_replace('-', ' ', $name);
$randkey=rand(1, 99);
$tableName=ucwords($name);
if(isset($_POST['Submitform'])){
	mysqli_query($con,"DELETE FROM `form_settings` where TableName='".$_POST['tb']."'") or die(mysql_error());
	$checkbox=$_POST['Removecolumn'];
	for($i=0;$i< sizeof($checkbox);$i++){
		$query="INSERT INTO `form_settings`(`TableName`, `ColumName`, `Status`, `Txnuser`, `Txndate`) VALUES ('".$_POST['tb']."','".$checkbox[$i]."',1,1,sysdate())";
		mysqli_query($con,$query) or die(mysql_error());
	}
	header("location: tableFrom.php?tb=".$_POST['tb']."&activate=".$_POST['activate']);
}
if(isset($_POST['Validationform'])){
	mysqli_query($con,"DELETE FROM `validations` where TableName='".$_POST['tb']."'") or die(mysql_error());
	$checkbox=$_POST['Validation'];
	for($i=0;$i< sizeof($checkbox);$i++){
		$query="INSERT INTO `validations`(`TableName`, `ColumName`, `Status`) VALUES ('".$_POST['tb']."','".$checkbox[$i]."',1)";
		mysqli_query($con,$query) or die(mysql_error());
	}
	header("location: tableFrom.php?tb=".$_POST['tb']."&activate=".$_POST['activate']);
}
if(isset($_POST['formsubmit'])){
	$keys = array();
	foreach(columnNamesView($_POST['tb']) as $key=>$value){
		if(form_user_settings($_POST['tb'],$value['COLUMN_NAME'])['ColumName'] !=$value['COLUMN_NAME'] AND $value['COLUMN_NAME'] !=primarykey($_REQUEST['tb'])['COLUMN_NAME']){
			$keys["key"][] = "`{$value['COLUMN_NAME']}`";
			if($value['COLUMN_NAME']=='FileName'){
				$total = count($_FILES[$value['COLUMN_NAME']]['name']);
				for($i=0; $i<$total; $i++) {
					if($_FILES[$value['COLUMN_NAME']]['name'][$i] !=''){
						$keys["values"]["images"][] = "'".$randkey.$_FILES[$value['COLUMN_NAME']]['name'][$i]."'";
						$target_dir = "uploadfiles/";
						$target_file = $target_dir . basename($randkey.$_FILES[$value['COLUMN_NAME']]["name"][$i]);
						move_uploaded_file($_FILES[$value['COLUMN_NAME']]["tmp_name"][$i], $target_file);
					}else{
						$keys["values"]["images"][] = "''";
					}
					
				}
			}else{
				$value = trim($_POST[$value['COLUMN_NAME']]);
				$value = mysqli_real_escape_string($con,$value);
				$keys["values"][] = "'{$value}'";	
			} 
		}
	}

	if(isset($keys["values"]["images"]) AND is_array($keys["values"]["images"])){
		$query = "INSERT INTO ".$_POST['tb']." (" . implode(",",$keys["key"]). ") VALUES ";
		$maincontact = "";
		foreach($keys["values"]["images"] as $imgkey => $imgval){
			$stringvalues = "(";
			foreach($keys["values"] as $key => $values){
				if(is_array($values)){
					$stringvalues .= "".$values[$imgkey].",";
				}else{
					$stringvalues .= "".$values.",";	
				}
			}
			$stringvalues = rtrim($stringvalues,',');
			$stringvalues .= ")";
			$maincontact .= $stringvalues.",";
		}
		//echo $query.rtrim($maincontact,',');
		//die();
		mysqli_query($con,$query.rtrim($maincontact,',')) or die(mysql_error());	
	}else{
		$query = "INSERT INTO ".$_POST['tb']." (" . implode(",",$keys["key"]). ") VALUES (" . implode(",",$keys["values"]). ")";
		mysqli_query($con,$query) or die(mysql_error());
	}
	
	header("location: tableView.php?tb=".$_POST['tb']."&activate=".$_POST['activate']);
}
if(isset($_POST['mappingkey'])){
	if(isset($_POST['mappname'])){
		mysqli_query($con,"DELETE FROM `mappingforeignkey` WHERE `TableName`='".$_POST['tablename']."'")or die(mysql_error());
		$query = "INSERT INTO mappingforeignkey ( `TableName`, `ColumnID`, `ColumnName`) VALUES ('".$_POST['tablename']."','".$_POST['tablekey']."','".$_POST['mappname']."');";
		mysqli_query($con,$query) or die(mysql_error());
	}
	header("location: tableFrom.php?tb=".$_POST['tb']."&activate=".$_POST['activate']);
}  
if(isset($_POST['changeType'])){
	$find=mysqli_query($con,"SELECT * FROM `changeinput` WHERE `TableName`='".$_POST['tb']."' AND `ColumName`='".$_POST['columnname']."'")or die(mysql_error());
	$findci=mysql_fetch_assoc($find);
	
	mysqli_query($con,"DELETE FROM `changeinputnamevalue` WHERE `CI`='".$findci['CI']."'")or die(mysql_error());
	mysqli_query($con,"DELETE FROM `changeinput` WHERE `CI`='".$findci['CI']."'")or die(mysql_error());
	
	if($_POST['countof'] !=''){
		$query = "INSERT INTO changeinput ( `TableName`, `ColumName`, `ChangeTo`, `CountOf`) VALUES ('".$_POST['tb']."','".$_POST['columnname']."','".$_POST['changeto']."','".$_POST['countof']."');";
		mysqli_query($con,$query) or die(mysql_error());
		$fetch=mysql_fetch_assoc(mysqli_query($con,"SELECT max(CI) as CI FROM `changeinput`"));
		
		for($i=1; $i<=$_POST['countof'];$i++){
			$query1 = "INSERT INTO changeinputnamevalue ( `CI`, `Name`, `Value`) VALUES ('".$fetch['CI']."','".$_POST['nametoshow'.$i]."','".$_POST['valueforsave'.$i]."');";
			mysqli_query($con,$query1) or die(mysql_error());
		}
		
	}
	if($_POST['filetype'] !=''){
		$query = "INSERT INTO changeinput ( `TableName`, `ColumName`, `ChangeTo`, `CountOf`) VALUES ('".$_POST['tb']."','".$_POST['columnname']."','".$_POST['changeto']."','".$_POST['filetype']."');";
		mysqli_query($con,$query) or die(mysql_error());
	}	
	
	header("location: tableFrom.php?tb=".$_POST['tb']."&activate=".$_POST['activate']);
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
				<div class="col-md-7">
					<div class="content-box-large">
						<div class="panel-heading">
							<div class="panel-title"><?php echo $tableName;?> Form</div>
						</div>
						<div class="panel-body">
							<form action="tableFrom.php" method="post" enctype="multipart/form-data">
								<fieldset>
									<?php
										if(columnNames($_REQUEST['tb'])){
											if(foreignkey($_REQUEST['tb'])){
												foreach(foreignkey($_REQUEST['tb']) as $foreignkey){
														?>
														<div class="form-group">
															<label><?php echo ucwords($foreignkey['ReferencedTableName'])." Name";?> *</label>
															<select class="form-control" name="<?php echo $foreignkey['Tableforeignkey'];?>" id="<?php echo $foreignkey['Tableforeignkey'];?>" required>
																<option value="">Select</option>
																<?php
																foreach(foreignkeyValue($foreignkey['ReferencedTableName']) as $foreignkeyValue){
																	if(foreignkeynamemapping($foreignkey['ReferencedTableName'])['ColumnName'] !=''){
																		$keyname=$foreignkeyValue[foreignkeynamemapping($foreignkey['ReferencedTableName'])['ColumnName']];
																	}else{
																		$keyname=$foreignkeyValue[$foreignkey['ReferencedColumnName']];
																	}
																	?>
																	<option value="<?php echo $foreignkeyValue[$foreignkey['ReferencedColumnName']]?>"><?php echo $keyname;?></option>
																	<?php
																}
																?>
															</select>
														</div>
														<?php
													
												}
											}
											foreach(columnNames($_REQUEST['tb']) as $key=>$value){
												if(form_user_settings($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName'] !=$value['COLUMN_NAME'] AND $value['COLUMN_NAME'] !=primarykey($_REQUEST['tb'])['COLUMN_NAME']){
													$name = str_replace('_', ' ', $value['COLUMN_NAME']);
													$name = str_replace('-', ' ', $name);
													if(datatype($_REQUEST['tb'],$value['COLUMN_NAME'])['DATA_TYPE'] =='int' OR datatype($_REQUEST['tb'],$value['COLUMN_NAME'])['DATA_TYPE'] =='decimal' OR datatype($_REQUEST['tb'],$value['COLUMN_NAME'])['DATA_TYPE'] =='float' OR datatype($_REQUEST['tb'],$value['COLUMN_NAME'])['DATA_TYPE'] =='double'){
														if($value['COLUMN_NAME'] =='Status'){
															?>
															  <div class="form-group">
																<input class="form-control" name="<?php echo $value['COLUMN_NAME'];?>"  id="<?php echo $value['COLUMN_NAME'];?>" type="hidden" value="1"  <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? 'required' : '');?>>
															  </div>
															<?php
														}elseif($value['COLUMN_NAME'] =='CreatedBy'){
															?>
															  <div class="form-group">
																<input class="form-control" name="<?php echo $value['COLUMN_NAME'];?>"  id="<?php echo $value['COLUMN_NAME'];?>" type="hidden" value="<?php echo $_SESSION['slno'];?>"  <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? 'required' : '');?>>
															  </div>
															<?php
														}elseif(changeinputtype($_REQUEST['tb'],$value['COLUMN_NAME'])){
															$type=changeinputtype($_REQUEST['tb'],$value['COLUMN_NAME'])['ChangeTo'];
															$CI=changeinputtype($_REQUEST['tb'],$value['COLUMN_NAME'])['CI'];
															$CountOf=changeinputtype($_REQUEST['tb'],$value['COLUMN_NAME'])['CountOf'];
															if($type=='radio'){
																?>
																<div class="form-group">
																<label><?php echo ucwords($name);?>  <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? '*' : '');?></label>
																<?php
																if(changeinputname($CI)){
																	foreach(changeinputname($CI) as $key=>$radio){
																		?>
																		<div class="radio">
																			<label><input type="radio" name="<?php echo $value['COLUMN_NAME'];?>"  id="<?php echo $value['COLUMN_NAME'];?>" type="<?php echo $type;?>" <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? 'required' : '');?>  value="<?php echo $radio['Value']?>"><?php echo $radio['Name']?></label>
																		</div>
																		<?php
																	}
																}
																?>
															  </div>
																<?php
															}elseif($type=='checkbox'){
															?>
															 <div class="form-group">
																<label><?php echo ucwords($name);?>  <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? '*' : '');?></label>
																<?php
																if(changeinputname($CI)){
																	foreach(changeinputname($CI) as $key=>$checkbox){
																		?>
																		<div class="checkbox">
																			<label><input type="checkbox" name="<?php echo $value['COLUMN_NAME'];?>"  id="<?php echo $value['COLUMN_NAME'];?>" type="<?php echo $type;?>" <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? 'required' : '');?> value="<?php echo $checkbox['Value']?>"><?php echo $checkbox['Name']?></label>
																		</div>
																		<?php
																	}
																}
																?>
															  </div>
															<?php
															}elseif($type=='DropDown'){
															?>
															 <div class="form-group">
																<label><?php echo ucwords($name);?>  <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? '*' : '');?></label>
																<select class="form-control" name="<?php echo $value['COLUMN_NAME'];?>"  id="<?php echo $value['COLUMN_NAME'];?>" type="<?php echo $type;?>" <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? 'required' : '');?>>
																<option value="">Select</option>
																<?php
																if(changeinputname($CI)){
																	foreach(changeinputname($CI) as $key=>$DropDown){
																		?>
																		<option value="<?php echo $DropDown['Value']?>"><?php echo $DropDown['Name']?></option>
																		<?php
																	}
																}
																?>
																</select>
															  </div>
															<?php
															}elseif($type=='file'){
															?>
															 <div class="form-group">
																<label><?php echo ucwords($name);?>  <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? '*' : '');?></label>
																<input class="form-control" name="<?php echo $value['COLUMN_NAME'];?>[]"  id="<?php echo $value['COLUMN_NAME'];?>" type="<?php echo $type;?>" <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? 'required' : '');?> <?php echo $CountOf;?>>
															  </div>
															<?php
															}
														}else{
															?>
															<div class="form-group">
																<label><?php echo ucwords($name);?>  <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? '*' : '');?></label>
																<input class="form-control" name="<?php echo $value['COLUMN_NAME'];?>"  id="<?php echo $value['COLUMN_NAME'];?>" type="number" min="1" <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? 'required' : '');?>>
															</div>
															<?php
														}
													}elseif(datatype($_REQUEST['tb'],$value['COLUMN_NAME'])['DATA_TYPE'] =='text'){
													?>
													  <div class="form-group">
														<label><?php echo ucwords($name);?>  <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? '*' : '');?></label>
														<textarea class="form-control"  name="<?php echo $value['COLUMN_NAME'];?>" id="<?php echo $value['COLUMN_NAME'];?>"  <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? 'required' : '');?>></textarea>
													  </div>
													<?php
													}elseif(datatype($_REQUEST['tb'],$value['COLUMN_NAME'])['DATA_TYPE'] =='datetime' OR datatype($_REQUEST['tb'],$value['COLUMN_NAME'])['DATA_TYPE'] =='timestamp'){
														if($value['COLUMN_NAME'] =='Txndate'){
															?>
															 <div class="form-group">
																<label><?php echo ucwords($name);?>  <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? '*' : '');?></label>
																<input class="form-control" name="<?php echo $value['COLUMN_NAME'];?>"  id="<?php echo $value['COLUMN_NAME'];?>" readonly type="datetime-local" value="<?php echo date("Y-m-d")."T".date("H:i:s") ;?>" <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? 'required' : '');?>>
															  </div>
															<?php
														}else{
													?>
													  <div class="form-group">
														<label><?php echo ucwords($name);?>  <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? '*' : '');?></label>
														<input class="form-control" name="<?php echo $value['COLUMN_NAME'];?>"  id="<?php echo $value['COLUMN_NAME'];?>" type="datetime-local" value="<?php echo date("Y-m-d")."T".date("H:i:s") ;?>" <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? 'required' : '');?>>
													  </div>
													<?php
														}
													}elseif(datatype($_REQUEST['tb'],$value['COLUMN_NAME'])['DATA_TYPE'] =='date'){
													?>
													  <div class="form-group">
														<label><?php echo ucwords($name);?>  <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? '*' : '');?></label>
														<input class="form-control" name="<?php echo $value['COLUMN_NAME'];?>"  id="<?php echo $value['COLUMN_NAME'];?>" type="date"  <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? 'required' : '');?>>
													  </div>
													<?php
													}elseif(datatype($_REQUEST['tb'],$value['COLUMN_NAME'])['DATA_TYPE'] =='time'){
													?>
													  <div class="form-group">
														<label><?php echo ucwords($name);?>  <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? '*' : '');?></label>
														<input class="form-control" name="<?php echo $value['COLUMN_NAME'];?>"  id="<?php echo $value['COLUMN_NAME'];?>" type="time"  <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? 'required' : '');?>>
													  </div>
													<?php
													}elseif(datatype($_REQUEST['tb'],$value['COLUMN_NAME'])['DATA_TYPE'] =='year'){
													?>
													  <div class="form-group">
														<label><?php echo ucwords($name);?>  <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? '*' : '');?></label>
														<input class="form-control" name="<?php echo $value['COLUMN_NAME'];?>"  id="<?php echo $value['COLUMN_NAME'];?>" type="year"  <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? 'required' : '');?>>
													  </div>
													<?php
													}else{
														if(changeinputtype($_REQUEST['tb'],$value['COLUMN_NAME'])){
															$type=changeinputtype($_REQUEST['tb'],$value['COLUMN_NAME'])['ChangeTo'];
															$CI=changeinputtype($_REQUEST['tb'],$value['COLUMN_NAME'])['CI'];
															$CountOf=changeinputtype($_REQUEST['tb'],$value['COLUMN_NAME'])['CountOf'];
															if($type=='radio'){
																?>
																<div class="form-group">
																<label><?php echo ucwords($name);?>  <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? '*' : '');?></label>
																<?php
																if(changeinputname($CI)){
																	foreach(changeinputname($CI) as $key=>$radio){
																		?>
																		<div class="radio">
																			<label><input type="radio" name="<?php echo $value['COLUMN_NAME'];?>"  id="<?php echo $value['COLUMN_NAME'];?>" type="<?php echo $type;?>" <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? 'required' : '');?>  value="<?php echo $radio['Value']?>"><?php echo $radio['Name']?></label>
																		</div>
																		<?php
																	}
																}
																?>
															  </div>
																<?php
															}elseif($type=='checkbox'){
															?>
															 <div class="form-group">
																<label><?php echo ucwords($name);?>  <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? '*' : '');?></label>
																<?php
																if(changeinputname($CI)){
																	foreach(changeinputname($CI) as $key=>$checkbox){
																		?>
																		<div class="checkbox">
																			<label><input type="checkbox" name="<?php echo $value['COLUMN_NAME'];?>"  id="<?php echo $value['COLUMN_NAME'];?>" type="<?php echo $type;?>" <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? 'required' : '');?> value="<?php echo $checkbox['Value']?>"><?php echo $checkbox['Name']?></label>
																		</div>
																		<?php
																	}
																}
																?>
															  </div>
															<?php
															}elseif($type=='DropDown'){
															?>
															 <div class="form-group">
																<label><?php echo ucwords($name);?>  <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? '*' : '');?></label>
																<select class="form-control" name="<?php echo $value['COLUMN_NAME'];?>"  id="<?php echo $value['COLUMN_NAME'];?>" type="<?php echo $type;?>" <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? 'required' : '');?>>
																<option value="">Select</option>
																<?php
																if(changeinputname($CI)){
																	foreach(changeinputname($CI) as $key=>$DropDown){
																		?>
																		<option value="<?php echo $DropDown['Value']?>"><?php echo $DropDown['Name']?></option>
																		<?php
																	}
																}
																?>
																</select>
															  </div>
															<?php
															}elseif($type=='file'){
															?>
															 <div class="form-group">
																<label><?php echo ucwords($name);?>  <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? '*' : '');?></label>
																<input class="form-control" name="<?php echo $value['COLUMN_NAME'];?>[]"  id="<?php echo $value['COLUMN_NAME'];?>" type="<?php echo $type;?>" <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? 'required' : '');?> <?php echo $CountOf;?>>
															  </div>
															<?php
															}
														}else{
															?>
															<div class="form-group">
																<label><?php echo ucwords($name);?>  <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? '*' : '');?></label>
																<input class="form-control" name="<?php echo $value['COLUMN_NAME'];?>"  id="<?php echo $value['COLUMN_NAME'];?>" type="text" <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? 'required' : '');?>>
															</div>
															<?php
														}
													}
												}
											}
										}
									?>
								</fieldset>
								<input type="hidden" name="tb" value="<?php echo $_REQUEST['tb']?>">
								<input type="hidden" name="activate" value="<?php echo $_REQUEST['activate']?>">
								<div>
									<button class="btn btn-primary" type="submit" name="formsubmit" style="float:right;">Submit</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="col-md-5">
					<div class="content-box-large">
						<div class="panel-heading">
							<div class="panel-title">Column Names -- User Settings</div><br/><br/>
							<ul>
							<li style="color:teal; text-align:justify;">Not required Columns Select and delete for FORM view only. It wont effect to your database.</li>
							<li style="color:teal; text-align:justify;">Not required Columns Select and submit for FORM validation purpose. It wont effect to your database.</li>
							</ul>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="panel-body">
									<form action="tableFrom.php" method="post">
										<fieldset>
											<div class="col-sm-12">
											  <?php
												if(columnNames($_REQUEST['tb'])){
													foreach(columnNames($_REQUEST['tb']) as $key=>$value){
														if(form_user_settings($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName'] !=$value['COLUMN_NAME'] AND $value['COLUMN_NAME'] !=primarykey($_REQUEST['tb'])['COLUMN_NAME']){
															if($value['COLUMN_NAME'] !='Status' AND $value['COLUMN_NAME'] !='CreatedBy' AND $value['COLUMN_NAME'] !='Txndate'){
																?>
																 <div class="checkbox">
																	<label><input type="checkbox" name="Removecolumn[]" value="<?php echo $value['COLUMN_NAME'];?>"> <?php echo $value['COLUMN_NAME'];?> </label>
																  </div>
																<?php
															}
														}if(form_user_settings($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName'] ==$value['COLUMN_NAME'] AND $value['COLUMN_NAME'] !=primarykey($_REQUEST['tb'])['COLUMN_NAME'] AND $value['COLUMN_NAME'] !='Path'){
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
										<div>
											<button class="btn btn-danger" type="submit" name="Submitform" style="float:right;"><i class="glyphicon glyphicon-remove"></i> Delete</button>
										</div>
									</form>
								</div>
							</div>
							<div class="col-md-8">
								<div class="panel-body">
									<form action="tableFrom.php" method="post">
										<fieldset>
											<div class="col-sm-12">
											  <?php
												if(columnNames($_REQUEST['tb'])){
													foreach(columnNames($_REQUEST['tb']) as $key=>$value){
														if(validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName'] ==$value['COLUMN_NAME'] AND $value['COLUMN_NAME'] !=primarykey($_REQUEST['tb'])['COLUMN_NAME']){
															if($value['COLUMN_NAME'] !='Status' AND $value['COLUMN_NAME'] !='CreatedBy' AND $value['COLUMN_NAME'] !='Txndate'){
																?>
																 <div class="checkbox">
																	<label><input type="checkbox" name="Validation[]" checked value="<?php echo $value['COLUMN_NAME'];?>"> <?php echo $value['COLUMN_NAME'];?> - <span style="color:red;">Validation required </span> </label>
																  </div>
																<?php
															}
														}if(validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName'] !=$value['COLUMN_NAME'] AND $value['COLUMN_NAME'] !=primarykey($_REQUEST['tb'])['COLUMN_NAME'] AND $value['COLUMN_NAME'] !='Path'){
															if($value['COLUMN_NAME'] !='Status' AND $value['COLUMN_NAME'] !='CreatedBy' AND $value['COLUMN_NAME'] !='Txndate'){
																?>
																 <div class="checkbox">
																	<label><input type="checkbox" name="Validation[]" value="<?php echo $value['COLUMN_NAME'];?>"> <?php echo $value['COLUMN_NAME'];?> - <span style="color:red;">Validation required </span> </label>
																  </div>
																<?php
															}
														}
													}
												}
											  ?>
											</div>
											<input type="hidden" name="tb" value="<?php echo $_REQUEST['tb'];?>">
											<input type="hidden" name="activate" value="<?php echo $_REQUEST['activate'];?>">
										</fieldset>
										<div>
											<button class="btn btn-primary" type="submit" name="Validationform" style="float:right;">Submit</button>
										</div>
									</form>
								</div>
							</div>
						</div>
						<div class="row">
							<?php
								if(foreignkey($_REQUEST['tb'])){
							?>
							<ul>
							<li style="color:teal; text-align:justify;">Foreign Key Relationship mapping with columnNames</li>
							</ul>
							<?php } ?>
							<div class="col-md-6">
								<div class="panel-body">
									<fieldset>
										<div class="col-sm-12">
										  <?php
											if(foreignkey($_REQUEST['tb'])){
												foreach(foreignkey($_REQUEST['tb']) as $foreignkey){
													?>
													 <div class="radio">
														<label><input type="radio" name="addkey" id="addkey" onclick="loadDoc('<?php echo $foreignkey['ReferencedTableName'];?>','<?php echo $_REQUEST['activate'];?>','<?php echo $_REQUEST['tb'];?>','<?php echo primarykey($foreignkey['ReferencedTableName'])['COLUMN_NAME'];?>')" value="<?php echo $foreignkey['ReferencedTableName'];?>"><label><?php echo $foreignkey['ReferencedTableName'] ?></label> </label>
													  </div>
													<?php
												}
											}
										
										  ?>
										</div>
									</fieldset>
								</div>
							</div>
							<div class="col-md-6">
								<div class="panel-body">
									<form action="tableFrom.php" method="post" id="result">
										
									</form>
								</div>
							</div>
							<div style="clear:both;"></div>
							<ul>
							<li style="color:teal; text-align:justify;">Change Data Types.</li>
							</ul>
							<div class="col-md-12">
								<div class="panel-body">
									<form action="tableFrom.php" method="post" enctype="multipart/form-data">
										<fieldset>
										<?php
										if(columnNames($_REQUEST['tb'])){
										?>
											<div class="col-sm-6">
												<label>Column Names</label>
												<select class="form-control" name="columnname" required>
													<option value="">Select Type</option>
													<?php
													foreach(columnNames($_REQUEST['tb']) as $key=>$value){
														if($value['COLUMN_NAME'] !=primarykey($_REQUEST['tb'])['COLUMN_NAME'] AND $value['COLUMN_NAME'] !='Path'){
															if($value['COLUMN_NAME'] !='Status' AND $value['COLUMN_NAME'] !='CreatedBy' AND $value['COLUMN_NAME'] !='Txndate'){
																?>
																	<option value="<?php echo $value['COLUMN_NAME'];?>"><?php echo $value['COLUMN_NAME'];?></option>
																<?php
															}
														}
													}
													?>
												</select><br/>
												<div id="textlabel">
												<label>Count Of Values</label>
												<input type="number" min="1" id="countof" name="countof"  onchange="functioncount()" class="form-control"/>
												<select class="form-control" id="filetype" name="filetype">
													<option value="">Select</option>
													<option value="Single">Single</option>
													<option value="Multiple">Multiple</option>
												</select>
												</div>
											</div>
											<div class="col-sm-6">
												<label>Input Data Type</label>
												<select class="form-control"  onchange="myFunction()" id="changeto" name="changeto" required>
													<option value="">Select</option>
													<option value="file">ChooseFile</option>
													<option value="radio">RadioButton</option>
													<option value="checkbox">CheckBox</option>
													<option value="DropDown">DropDown</option>
													<option value="text">TextBox</option>
													<option value="TextArea">TextArea</option>
													<option value="datetime-local">DateTime</option>
													<option value="date">Date</option>
													<option value="time">Time</option>
													<option value="int">Integer</option>
												</select><br/>
											</div>
											<div style="clear:both;"></div>
											<div id="namevalue">
												
											</div>
											<input type="hidden" name="tb" value="<?php echo $_REQUEST['tb'];?>">
											<input type="hidden" name="activate" value="<?php echo $_REQUEST['activate'];?>">
											<?php
										}
									  ?>
										</fieldset>
										<br/>
										<div>
											<button class="btn btn-primary" type="submit" name="changeType" style="float:right;">Submit</button>
										</div>
									</form>
								</div>
							</div>
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
	<script>
	function loadDoc(namevalue, key, tablename,tablekey) {
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (xhttp.readyState == 4) {
				if (xhttp.status==200 || window.location.href.indexOf("http")==-1){
					document.getElementById("result").innerHTML = xhttp.responseText;
				}
				else{
					alert("An error has occured making the request");
				}
			}
		};
		var parameters="name="+namevalue+"&key="+key+"&tablename="+tablename+"&tablekey="+tablekey;
		xhttp.open("POST", "mapping.php", true);
		xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhttp.send(parameters);
	}
	$("#textlabel").hide();
	$("#namevalue").hide();
	function myFunction() {
		var x = document.getElementById("changeto").value;
		if(x=='file'){
			$("#textlabel").show();
			$("#countof").hide();
			$("#filetype").show();
			$("#filetype").prop('required',true);
			$("#namevalue").hide();
			$("#namevalue").html("");
		}else if(x=='radio' || x=='checkbox' || x=='DropDown'){
			$("#textlabel").show();
			$("#countof").val("");
			$("#countof").show();
			$("#filetype").hide();
			$("#countof").prop('required',true);
			$("#namevalue").hide();
			$("#namevalue").html("");
		}else{
			$("#textlabel").hide();
			$("#filetype").prop('required',false);
			$("#countof").prop('required',false);
			$("#namevalue").hide();
			$("#namevalue").html("");
		}
	}
	function functioncount() {
		var x = document.getElementById("countof").value;
		$("#namevalue").show();
		$("#namevalue").html("");
		for(i=1;i<=x;i++){
			$("#namevalue").append('<div class="col-sm-6"><label>Name To Show</label><input type="text" name="nametoshow'+i+'" required class="form-control"/></div><div class="col-sm-6"><label>Value For Save</label><input type="text" name="valueforsave'+i+'" required class="form-control"/></div>');
		}
		
	}
	</script>
  </body>
</html>