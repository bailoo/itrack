<?php
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
$query = "SELECT * FROM landmark WHERE account_id='$account_id' AND status=1";
$result = mysql_query($query,$DbConnection);
$size=0;
$final_landmark_str="";
while($row=mysql_fetch_object($result))
{	
	$coord1 = explode(',',$row->landmark_coord);
	$final_landmark_str=$final_landmark_str.$row->landmark_name."@".$row->zoom_level."@".$coord1[0]."@".$coord1[1]."#";
}
$final_landmark_str=substr($final_landmark_str,0,-1);
echo $final_landmark_str;
?>
