<?php
include("config.php");
mysqli_query($con,"DELETE FROM `".$_GET['tbs']."` WHERE ".primarykey($_GET['tbs'])['COLUMN_NAME']." ='".$_GET['idslno']."'") or die(mysql_error());
//echo "DELETE FROM `".$_GET['tbs']."` WHERE ".primarykey($_GET['tbs'])['COLUMN_NAME']." ='".$_GET['idslno']."'";
?>