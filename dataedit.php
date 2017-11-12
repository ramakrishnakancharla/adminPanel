<?php
include("config.php");
$name = str_replace('_', ' ', $_REQUEST['tb']);
$name = str_replace('-', ' ', $name);
$tableName=ucwords($name);
$randkey=rand(1, 99);
if(isset($_POST['formupdate'])){
	$keys = array();

	foreach(columnNamesView($_POST['tb']) as $key=>$value){
		if(form_user_settings($_POST['tb'],$value['COLUMN_NAME'])['ColumName'] !=$value['COLUMN_NAME'] AND $value['COLUMN_NAME'] !=primarykey($_REQUEST['tb'])['COLUMN_NAME']){
			
			if($value['COLUMN_NAME']=='FileName'){
				$val=$randkey.$_FILES[$value['COLUMN_NAME']]['name'];
				$keys[] = "`{$value['COLUMN_NAME']}`='{$val}'";	
				$target_dir = "uploadfiles/";
				$target_file = $target_dir . basename($randkey.$_FILES[$value['COLUMN_NAME']]["name"]);
				move_uploaded_file($_FILES[$value['COLUMN_NAME']]["tmp_name"], $target_file);
			}else{
				$value2 = trim($_POST[$value['COLUMN_NAME']]);
				$value2 = mysqli_real_escape_string($con,$value2);
				$keys[] = "`{$value['COLUMN_NAME']}`='{$value2}'";	
			}

		}
	}
	//print_r($keys);
	//die();
	$query = "UPDATE ".$_POST['tb']."  SET " . implode(",", $keys) . " WHERE ".primarykey($_POST['tb'])['COLUMN_NAME']."='".$_POST['id']."'";
	//echo $query;
	mysqli_query($con,$query) or die(mysql_error());
	header("location: tableView.php?tb=".$_POST['tb']."&activate=".$_POST['activate']);
}
	
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
							<div class="panel-title"><?php echo $tableName;?> Edit</div>
						</div>
						<div class="panel-body">
							<form action="dataedit.php" method="post"  enctype="multipart/form-data">
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
																<option <?php if (dataview($_REQUEST['tb'],primarykey($_REQUEST['tb'])['COLUMN_NAME'],$_REQUEST['id'])[$foreignkey['Tableforeignkey']]==$foreignkeyValue[$foreignkey['ReferencedColumnName']]) { ?>selected="selected"<?php } ?> value="<?php echo $foreignkeyValue[$foreignkey['ReferencedColumnName']]?>"><?php echo $keyname;?></option>
																<?php
															}
															?>
														</select>
													</div>
													<?php
												}
											}
											foreach(columnNames($_REQUEST['tb']) as $key=>$value){
												if(form_user_settings($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName'] !=$value['COLUMN_NAME'] AND $value['COLUMN_NAME'] !=primarykey($_REQUEST['tb'])['COLUMN_NAME'] AND $value['COLUMN_NAME'] !='Path'){
														$name = str_replace('_', ' ', $value['COLUMN_NAME']);
														$name = str_replace('-', ' ', $name);
														if(datatype($_REQUEST['tb'],$value['COLUMN_NAME'])['DATA_TYPE'] =='int'){
															if($value['COLUMN_NAME'] =='Status'){
																?>
																  <div class="form-group">
																	<label><?php echo ucwords($name);?>  <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? '*' : '');?></label>
																	<select class="form-control" name="<?php echo $value['COLUMN_NAME'];?>"  id="<?php echo $value['COLUMN_NAME'];?>">
																		<option <?php if (dataview($_REQUEST['tb'],primarykey($_REQUEST['tb'])['COLUMN_NAME'],$_REQUEST['id'])[$value['COLUMN_NAME']]==1) { ?>selected="selected"<?php } ?> value="1">Active</option>
																		<option <?php if (dataview($_REQUEST['tb'],primarykey($_REQUEST['tb'])['COLUMN_NAME'],$_REQUEST['id'])[$value['COLUMN_NAME']]==0) { ?>selected="selected"<?php } ?> value="0">In-Active</option>
																	</select>
																  </div>
																<?php
															}elseif($value['COLUMN_NAME'] ==primarykey($_REQUEST['tb'])['COLUMN_NAME']){
																?>
																  <div class="form-group">
																	<input class="form-control" name="<?php echo $value['COLUMN_NAME'];?>"  id="<?php echo $value['COLUMN_NAME'];?>" type="hidden" <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? 'required' : '');?> value="<?php echo dataview($_REQUEST['tb'],primarykey($_REQUEST['tb'])['COLUMN_NAME'],$_REQUEST['id'])[$value['COLUMN_NAME']]?>" />
																  </div>
																<?php
															}elseif($value['COLUMN_NAME'] =='CreatedBy'){
																?>
																  <div class="form-group">
																	<input class="form-control" name="<?php echo $value['COLUMN_NAME'];?>"  id="<?php echo $value['COLUMN_NAME'];?>" type="hidden" <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? 'required' : '');?> value="<?php echo dataview($_REQUEST['tb'],primarykey($_REQUEST['tb'])['COLUMN_NAME'],$_REQUEST['id'])[$value['COLUMN_NAME']]?>" />
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
																				<label><input type="radio" name="<?php echo $value['COLUMN_NAME'];?>"  id="<?php echo $value['COLUMN_NAME'];?>" type="<?php echo $type;?>" <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? 'required' : '');?>  value="<?php echo $radio['Value']?>" <?php echo (dataview($_REQUEST['tb'],primarykey($_REQUEST['tb'])['COLUMN_NAME'],$_REQUEST['id'])[$value['COLUMN_NAME']]==$radio['Value'])?'checked':'' ?>><?php echo $radio['Name']?></label>
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
																				<label><input type="checkbox" name="<?php echo $value['COLUMN_NAME'];?>"  id="<?php echo $value['COLUMN_NAME'];?>" type="<?php echo $type;?>" <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? 'required' : '');?> value="<?php echo $checkbox['Value']?>" <?php echo (dataview($_REQUEST['tb'],primarykey($_REQUEST['tb'])['COLUMN_NAME'],$_REQUEST['id'])[$value['COLUMN_NAME']]==$checkbox['Value'] ? 'checked' : '');?>><?php echo $checkbox['Name']?></label>
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
																			<option value="<?php echo $DropDown['Value']?>" <?php if(dataview($_REQUEST['tb'],primarykey($_REQUEST['tb'])['COLUMN_NAME'],$_REQUEST['id'])[$value['COLUMN_NAME']]== $DropDown['Value']) echo "selected"; ?>><?php echo $DropDown['Name']?></option>
																			<?php
																		}
																	}
																	?>
																	</select>
																  </div>
																<?php
																}elseif($type=='file'){
																	if(dataview($_REQUEST['tb'],primarykey($_REQUEST['tb'])['COLUMN_NAME'],$_REQUEST['id'])[$value['COLUMN_NAME']] !=''){
																		$imagedisplay="uploadfiles/".dataview($_REQUEST['tb'],primarykey($_REQUEST['tb'])['COLUMN_NAME'],$_REQUEST['id'])[$value['COLUMN_NAME']];
																	}else{
																		$imagedisplay="images/noimage.png";
																	}
																	
																?>
																 <div class="form-group row">
																	<div class="col-md-6">
																		<label><?php echo ucwords($name);?>  <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? '*' : '');?></label>
																		<input class="form-control" name="<?php echo $value['COLUMN_NAME'];?>"  id="<?php echo $value['COLUMN_NAME'];?>" type="<?php echo $type;?>" <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? 'required' : '');?> <?php echo $CountOf;?>>
																	</div>
																	<div class="col-md-6">
																	  <img src="<?php echo $imagedisplay;?>" style="width:100px; height:80px;">
																	</div>
																  </div>
																<?php
																}
															}else{
																?>
																  <div class="form-group">
																	<label><?php echo ucwords($name);?>  <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? '*' : '');?></label>
																	<input class="form-control" name="<?php echo $value['COLUMN_NAME'];?>"  id="<?php echo $value['COLUMN_NAME'];?>" type="number"  <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? 'required' : '');?> value="<?php echo dataview($_REQUEST['tb'],primarykey($_REQUEST['tb'])['COLUMN_NAME'],$_REQUEST['id'])[$value['COLUMN_NAME']]?>" />
																  </div>
																<?php
															}
														}elseif(datatype($_REQUEST['tb'],$value['COLUMN_NAME'])['DATA_TYPE'] =='text'){
														?>
														  <div class="form-group">
															<label><?php echo ucwords($name);?>  <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? '*' : '');?></label>
															<textarea class="form-control" name="<?php echo $value['COLUMN_NAME'];?>" id="<?php echo $value['COLUMN_NAME'];?>"  <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? 'required' : '');?>><?php echo dataview($_REQUEST['tb'],primarykey($_REQUEST['tb'])['COLUMN_NAME'],$_REQUEST['id'])[$value['COLUMN_NAME']]?></textarea>
														  </div>
														<?php
														}elseif(datatype($_REQUEST['tb'],$value['COLUMN_NAME'])['DATA_TYPE'] =='datetime' OR datatype($_REQUEST['tb'],$value['COLUMN_NAME'])['DATA_TYPE'] =='timestamp'){
															if($value['COLUMN_NAME'] =='Txndate'){
															?>
															  <div class="form-group">
																<label><?php echo ucwords($name);?>  <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? '*' : '');?></label><br/>
																<input type="text" readonly class="form-control"  name="<?php echo $value['COLUMN_NAME'];?>"  id="<?php echo $value['COLUMN_NAME'];?>"  value="<?php echo dataview($_REQUEST['tb'],primarykey($_REQUEST['tb'])['COLUMN_NAME'],$_REQUEST['id'])[$value['COLUMN_NAME']]?>">
															  </div>
															<?php
															}else{
																?>
															  <div class="form-group">
																<label><?php echo ucwords($name);?>  <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? '*' : '');?></label><br/>
																<input class="form-control" name="<?php echo $value['COLUMN_NAME'];?>"  id="<?php echo $value['COLUMN_NAME'];?>" type="datetime-local" style="width:45%;float:left;" <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? 'required' : '');?> value="<?php echo dataview($_REQUEST['tb'],primarykey($_REQUEST['tb'])['COLUMN_NAME'],$_REQUEST['id'])[$value['COLUMN_NAME']]?>" /><input type="text" readonly class="form-control" style="width:45%;float:right;" value="<?php echo dataview($_REQUEST['tb'],primarykey($_REQUEST['tb'])['COLUMN_NAME'],$_REQUEST['id'])[$value['COLUMN_NAME']]?>">
															  </div>
															<?php
															}
														}elseif(datatype($_REQUEST['tb'],$value['COLUMN_NAME'])['DATA_TYPE'] =='date'){
														?>
														  <div class="form-group">
															<label><?php echo ucwords($name);?>  <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? '*' : '');?></label>
															<input class="form-control" name="<?php echo $value['COLUMN_NAME'];?>"  id="<?php echo $value['COLUMN_NAME'];?>" type="date"  <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? 'required' : '');?> value="<?php echo dataview($_REQUEST['tb'],primarykey($_REQUEST['tb'])['COLUMN_NAME'],$_REQUEST['id'])[$value['COLUMN_NAME']]?>" />
														  </div>
														<?php
														}elseif(datatype($_REQUEST['tb'],$value['COLUMN_NAME'])['DATA_TYPE'] =='time'){
														?>
														  <div class="form-group">
															<label><?php echo ucwords($name);?>  <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? '*' : '');?></label>
															<input class="form-control" name="<?php echo $value['COLUMN_NAME'];?>"  id="<?php echo $value['COLUMN_NAME'];?>" type="time"  <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? 'required' : '');?> value="<?php echo dataview($_REQUEST['tb'],primarykey($_REQUEST['tb'])['COLUMN_NAME'],$_REQUEST['id'])[$value['COLUMN_NAME']]?>" />
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
																				<label><input type="radio" name="<?php echo $value['COLUMN_NAME'];?>"  id="<?php echo $value['COLUMN_NAME'];?>" type="<?php echo $type;?>" <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? 'required' : '');?>  value="<?php echo $radio['Value']?>" <?php echo (dataview($_REQUEST['tb'],primarykey($_REQUEST['tb'])['COLUMN_NAME'],$_REQUEST['id'])[$value['COLUMN_NAME']]==$radio['Value'])?'checked':'' ?>><?php echo $radio['Name']?></label>
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
																				<label><input type="checkbox" name="<?php echo $value['COLUMN_NAME'];?>"  id="<?php echo $value['COLUMN_NAME'];?>" type="<?php echo $type;?>" <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? 'required' : '');?> value="<?php echo $checkbox['Value']?>" <?php echo (dataview($_REQUEST['tb'],primarykey($_REQUEST['tb'])['COLUMN_NAME'],$_REQUEST['id'])[$value['COLUMN_NAME']]==$checkbox['Value'] ? 'checked' : '');?>><?php echo $checkbox['Name']?></label>
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
																			<option value="<?php echo $DropDown['Value']?>" <?php if(dataview($_REQUEST['tb'],primarykey($_REQUEST['tb'])['COLUMN_NAME'],$_REQUEST['id'])[$value['COLUMN_NAME']]== $DropDown['Value']) echo "selected"; ?>><?php echo $DropDown['Name']?></option>
																			<?php
																		}
																	}
																	?>
																	</select>
																  </div>
																<?php
																}elseif($type=='file'){
																	if(dataview($_REQUEST['tb'],primarykey($_REQUEST['tb'])['COLUMN_NAME'],$_REQUEST['id'])[$value['COLUMN_NAME']] !=''){
																		$imagedisplay="uploadfiles/".dataview($_REQUEST['tb'],primarykey($_REQUEST['tb'])['COLUMN_NAME'],$_REQUEST['id'])[$value['COLUMN_NAME']];
																	}else{
																		$imagedisplay="images/noimage.png";
																	}
																	
																?>
																 <div class="form-group row">
																	<div class="col-md-6">
																		<label><?php echo ucwords($name);?>  <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? '*' : '');?></label>
																		<input class="form-control" name="<?php echo $value['COLUMN_NAME'];?>"  id="<?php echo $value['COLUMN_NAME'];?>" type="<?php echo $type;?>" <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? 'required' : '');?> <?php echo $CountOf;?>>
																	</div>
																	<div class="col-md-6">
																	  <img src="<?php echo $imagedisplay;?>" style="width:100px; height:80px;">
																	</div>
																  </div>
																<?php
																}
															}else{
																?>
																<div class="form-group">
																	<label><?php echo ucwords($name);?>  <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? '*' : '');?></label>
																	<input class="form-control" name="<?php echo $value['COLUMN_NAME'];?>"  id="<?php echo $value['COLUMN_NAME'];?>" type="text" <?php echo (validations($_REQUEST['tb'],$value['COLUMN_NAME'])['ColumName']==$value['COLUMN_NAME'] ? 'required' : '');?> value="<?php echo dataview($_REQUEST['tb'],primarykey($_REQUEST['tb'])['COLUMN_NAME'],$_REQUEST['id'])[$value['COLUMN_NAME']]?>">
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
								<input type="hidden" name="id" value="<?php echo $_REQUEST['id']?>">
								<br/>
								<div>
									<button class="btn btn-primary" type="submit" name="formupdate" style="float:right;">Update</button>
								</div>
							</form>
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