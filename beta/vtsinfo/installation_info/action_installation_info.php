<?php
set_time_limit(360000);
//$HOST = "111.118.181.156";
//include_once("../database_ip.php");
$HOST = "itrackdb.c4pqfsdaiccz.us-east-1.rds.amazonaws.com";
$DBASE = "iespl_vts_beta";
$USER = "bailoo";
$PASSWD = 'neon04$VTS';

//echo "\nDBASE=".$DBASE." ,USER=".$USER." ,PASS=".$PASSWD;
$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("Connection to server is down. Please try after few minutes.");
mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");

$post_vehicle_string = $_POST['vehicle_string'];
$post_device_string = $_POST['device_string'];

echo '<html>';
echo '<head>';
echo '
<style type="text/css">
table.gridtable {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
	color:#333333;
	border-width: 1px;
	border-color: #666666;
	border-collapse: collapse;
}
table.gridtable th {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #666666;
	background-color: #dedede;
}
table.gridtable td {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #666666;
	background-color: #ffffff;
}
</style>';
echo '</head>';

echo "<body>";
echo '<div align="right"><a href="../home.php" style="text-decoration:none;"><font color=blue size=3><strong>Home</strong></font></a>&nbsp;&nbsp;<a href="../logout.php" style="text-decoration:none;"><font color=green size=3><strong>Logout</strong></font></a></div>';
echo '<br>';
//echo "<br>PostVehicle=".$post_vehicle_string;
//echo "<br>PostIMEI=".$post_device_string;
$vehicle_string_final = "";
$device_string_final = "";

if($post_vehicle_string!="")
{
	$vehicle_string_tmp = explode(',',$post_vehicle_string);
	for($i=0;$i<sizeof($vehicle_string_tmp);$i++)
	{
		$vehicle_string_final.= "'".trim($vehicle_string_tmp[$i])."',";
	}
	$vehicle_string_final = trim(substr($vehicle_string_final, 0, -1));	
	//###### DISPLAY ON WEB
	echo '
	<center><br><strong>Vehicle Information</strong><br>
	<table rules="all" border="1">
	<tr bgcolor="grey">
		<th>Vehicle Name</th><th>UserID</th><th>Create Date</th><th>Edit Date</th>
	</tr>';
	
	$query_vehicle = "SELECT vehicle.vehicle_name,vehicle.create_date,vehicle.edit_date,account.user_id FROM vehicle,account,vehicle_grouping WHERE vehicle.vehicle_id = vehicle_grouping.vehicle_id AND vehicle_grouping.account_id = account.account_id AND vehicle.status=1 AND vehicle_grouping.status=1 AND NOT(account.account_id=1) AND vehicle.vehicle_name IN($vehicle_string_final)";
	//echo "<br>query=".$query_vehicle;
	$result = mysql_query($query_vehicle,$DbConnection);
	while($row = mysql_fetch_object($result))
	{
		echo'
		<tr>
			<td>'.$row->vehicle_name.'</td><td>'.$row->user_id.'</td><td>'.$row->create_date.'</td><td>'.$row->edit_date.'</td>
		</tr>
		';
	}
	echo '</center></table>';
}

echo "<br>";

if($post_device_string!="")
{
	$device_string_tmp = explode(',',$post_device_string);
	for($i=0;$i<sizeof($device_string_tmp);$i++)
	{
		$device_string_final.= "'".trim($device_string_tmp[$i])."',";		
	}
	$device_string_final = trim(substr($device_string_final, 0, -1));
	//###### DISPLAY ON WEB
	echo '
	<center><br><strong>Device Information</strong><br>
	<table rules="all" border="1">
	<tr>
		<th>Device IMEI No</th><th>UserID</th><th>Create Date</th><th>Edit Date</th><th>Status</th>		
	</tr>';
	
	$query_device = "SELECT device_assignment.device_imei_no,device_assignment.status,account.user_id,device_assignment.create_date,device_assignment.edit_date FROM device_assignment,account WHERE device_assignment.account_id=account.account_id AND NOT(account.account_id=1) AND device_assignment.device_imei_no IN($device_string_final);";
	//echo "<br>query2=".$query_device;
	$result = mysql_query($query_device,$DbConnection);
	while($row = mysql_fetch_object($result))
	{
		$status = $row->status;
		if($status =="1")
		{
			echo'
			<tr bgcolor="#CEECF5">
				<td>'.$row->device_imei_no.'</td><td>'.$row->user_id.'</td><td>'.$row->create_date.'</td><td>'.$row->edit_date.'</td><td>'.$status.'</td>
			</tr>
			';
		}
		else if($status =="0")
		{
			echo'
			<tr bgcolor="#A4A4A4">
				<td>'.$row->device_imei_no.'</td><td>'.$row->user_id.'</td><td>'.$row->create_date.'</td><td>'.$row->edit_date.'</td><td>'.$status.'</td>
			</tr>
			';		
		}
	}	
	echo '</center></table>';
}
echo '</body></html>';
?>
