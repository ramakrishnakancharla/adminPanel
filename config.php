<?php
session_start();
$Hostname="localhost";
$DBName="admin_panel";
$DBUserName="root";
$DBPassword="";

//$connect = mysql_connect($Hostname,$DBUserName,$DBPassword) or die(mysql_error());
//mysql_select_db($DBName,$connect) or die(mysql_error());

$con = mysqli_connect($Hostname,$DBUserName,$DBPassword,$DBName);

// Check connection
if (mysqli_connect_errno())
{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
  
$currency			= '&#8377; '; //currency symbol

function randomerror($length = 66) {
	$str = "";
	$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
	$max = count($characters) - 1;
	for ($i = 0; $i < $length; $i++) {
	$rand = mt_rand(0, $max);
	$str .= $characters[$rand];
	}
	return $str;
}
function custom_echo($x, $length)
{
  if(strlen($x)<=$length)
  {
    echo $x;
  }
  else
  {
    $y=substr($x,0,$length) . '...';
    echo $y;
  }
}
function tableNames(){
	global $DBName;
	global $con;
	$query=mysqli_query($con,"select table_name from information_schema.tables where table_schema='".$DBName."'")or die(mysql_error());
	if(mysqli_num_rows($query)>0){
		while($row = mysqli_fetch_assoc($query)) { 
			$resArr[] = $row;
		}
		return $resArr;
	}else{
		return false;
	} 
	return $DBName;
}
function columnNamesView($TableName){
	global $DBName;
	global $con;
	$query=mysqli_query($con,"SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '".$DBName."' AND TABLE_NAME = '".$TableName."'")or die(mysql_error());
	if(mysqli_num_rows($query)>0){
		while($row = mysqli_fetch_assoc($query)) { 
			$resArr[] = $row;
		}
		return $resArr;
	}else{
		return false;
	} 
	return $DBName;
}

function columnNames($TableName){
	global $DBName;
	global $con;
	$query=mysqli_query($con,"SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '".$DBName."' AND TABLE_NAME = '".$TableName."' AND COLUMN_NAME NOT IN (SELECT COLUMN_NAME FROM information_schema.key_column_usage WHERE referenced_table_name is not null AND table_schema = '".$DBName."' AND  TABLE_NAME='".$TableName."')")or die(mysql_error());
	if(mysqli_num_rows($query)>0){
		while($row = mysqli_fetch_assoc($query)) { 
			$resArr[] = $row;
		}
		return $resArr;
	}else{
		return false;
	} 
	return $DBName;
}
function datatype($table,$col)
{
	global $con;
	$query = mysqli_query($con,"SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '".$table."' AND COLUMN_NAME = '".$col."'")or die(mysql_error());
	return mysqli_fetch_assoc($query);
}
function primarykey($table)
{
	global $DBName;
	global $con;
	//$query = mysqli_query($con,"SELECT `COLUMN_NAME` FROM `information_schema`.`COLUMNS` WHERE (`TABLE_SCHEMA` = '".$DBName."') AND (`TABLE_NAME` = '".$table."') AND (`COLUMN_KEY` = 'PRI');")or die(mysql_error());
	
	$query = mysqli_query($con,"SELECT `COLUMN_NAME` FROM `information_schema`.`COLUMNS` WHERE (`TABLE_SCHEMA` = '".$DBName."') AND (`TABLE_NAME` = '".$table."') AND (`extra` = 'auto_increment')")or die(mysql_error());
	
	return mysqli_fetch_assoc($query);
}


function tableValues($tableName)
{
	global $con;
	$query = mysqli_query($con,"SELECT * FROM ".$tableName."")or die(mysql_error());
	if(mysqli_num_rows($query)>0){
		while($row = mysqli_fetch_assoc($query)) { 
			$resArr[] = $row;
		}
		return $resArr;
	}else{
		return false;
	} 
}
function table_user_settings($id)
{
	global $con;
	$query = mysqli_query($con,"SELECT * FROM table_settings where TableName='".$id."'")or die(mysql_error());
	return mysqli_fetch_assoc($query);
}
function column_user_settings($id,$col)
{
	global $con;
	$query = mysqli_query($con,"SELECT * FROM column_settings where TableName='".$id."' AND ColumName='".$col."'")or die(mysql_error());
	return mysqli_fetch_assoc($query);
}
function form_user_settings($id,$col)
{
	global $con;
	$query = mysqli_query($con,"SELECT * FROM form_settings where TableName='".$id."' AND ColumName='".$col."'")or die(mysql_error());
	return mysqli_fetch_assoc($query);
}
function validations($id,$col)
{
	global $con;
	$query = mysqli_query($con,"SELECT * FROM validations where TableName='".$id."' AND ColumName='".$col."'")or die(mysql_error());
	return mysqli_fetch_assoc($query);
}
function dataview($tablename,$colname,$colval)
{
	global $con;
	$query = mysqli_query($con,"SELECT * FROM ".$tablename." where ".$colname."='".$colval."'")or die(mysql_error());
	return mysqli_fetch_assoc($query);
}
function admin($id)
{
	global $con;
	$query = mysqli_query($con,"SELECT * FROM admin_login where AL='".$id."'")or die(mysql_error());
	return mysqli_fetch_assoc($query);
}

function foreignkey($tableName)
{
	global $DBName;
	global $con;
	$query=mysqli_query($con,"select table_name as 'TableName', column_name as 'Tableforeignkey', referenced_table_name as 'ReferencedTableName' , referenced_column_name as 'ReferencedColumnName' from information_schema.key_column_usage where referenced_table_name is not null and table_schema = '".$DBName."' AND  TABLE_NAME='".$tableName."'")or die(mysql_error());
	if(mysqli_num_rows($query)>0){
		while($row = mysqli_fetch_assoc($query)) { 
			$resArr[] = $row;
		}
		return $resArr;
	}else{
		return false;
	}
}
function foreignkeyValue($tableName)
{
	echo "SELECT * FROM ".$tableName." where Status='1'";
	global $con;
	$query=mysqli_query($con,"SELECT * FROM ".$tableName." where Status='1'")or die(mysql_error());
	if(mysqli_num_rows($query)>0){
		while($row = mysqli_fetch_assoc($query)) { 
			$resArr[] = $row;
		}
		return $resArr;
	}else{
		return false;
	}
}
function foreignkeynamemapping($id)
{
	global $con;
	$query = mysqli_query($con,"SELECT * FROM mappingforeignkey where TableName='".$id."'")or die(mysql_error());
	return mysqli_fetch_assoc($query);
}
function foreignkeynamefind($id)
{
	global $con;
	$query = mysqli_query($con,"SELECT * FROM mappingforeignkey where ColumnID='".$id."'")or die(mysql_error());
	return mysqli_fetch_assoc($query);
}
function foreignkeynameview($tableName, $colName, $wherecolName, $colValue)
{
	global $con;
	$query = mysqli_query($con,"SELECT ".$colName." FROM ".$tableName." where ".$wherecolName."='".$colValue."'")or die(mysql_error());
	return mysqli_fetch_assoc($query);
}
function changeinputtype($tableName, $colName)
{
	global $con;
	$query = mysqli_query($con,"SELECT *  FROM changeinput where TableName='".$tableName."' AND ColumName='".$colName."'")or die(mysql_error());
	return mysqli_fetch_assoc($query);
}
function changeinputname($colName)
{
	global $con;
	$query = mysqli_query($con,"SELECT *  FROM changeinputnamevalue where CI='".$colName."'")or die(mysql_error());
	if(mysqli_num_rows($query)>0){
		while($row = mysqli_fetch_assoc($query)) { 
			$resArr[] = $row;
		}
		return $resArr;
	}else{
		return false;
	}
}
function onchange($tableName)
{
	global $con;
	$query = mysqli_query($con,"SELECT * FROM onchange where ParentTable='".$tableName."'")or die(mysql_error());
	return mysqli_fetch_assoc($query);
}
?>