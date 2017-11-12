<?php
include("config.php");
?>
<fieldset>
	<div class="col-sm-12">
	  <?php
		if(columnNamesView($_POST['name'])){
			foreach(columnNamesView($_POST['name']) as $value){
				if(datatype($_POST['name'],$value['COLUMN_NAME'])['DATA_TYPE'] =='varchar' OR datatype($_POST['name'],$value['COLUMN_NAME'])['DATA_TYPE'] =='text'){
				?>
				 <div class="radio">
					<label><input type="radio" name="mappname" id="mappname"  value="<?php echo $value['COLUMN_NAME'];?>"><label><?php echo $value['COLUMN_NAME'] ?></label> </label>
				  </div>
				<?php
				}
			}
		}
	
	  ?>
	</div>
</fieldset>
<input type="hidden" name="tablekey" value="<?php echo $_POST['tablekey'];?>">
<input type="hidden" name="tablename" value="<?php echo $_POST['name'];?>">
<input type="hidden" name="tb" value="<?php echo $_POST['tablename'];?>">
<input type="hidden" name="activate" value="<?php echo $_POST['key'];?>">
<div>
	<button class="btn btn-success" type="submit" id="mappingkey" name="mappingkey" style="float:right;">Mapping</button>
</div>
