<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
	$DEBUG = 0;  
	$route_name = $_POST['ls_1'];    
	$vehicle_name = $_POST['rs_1'];
	$query = "SELECT account_id from account WHERE superuser='$superuser' and user='$user' and grp='admin'"; 
	$result = @mysql_query($query, $DbConnection);
	$row = @mysql_fetch_object($result);     	
	$user_account_id = $row->account_id;  

	$query1="SELECT route_id FROM route WHERE user_account_id='$user_account_id' AND route_name='$route_name'"; 
	$result1=mysql_query($query1,$DbConnection);        
	if($row1 = mysql_fetch_object($result1))
	{
		$route_id = $row1->route_id;
	} 
	$query2 = "SELECT VehicleID FROM vehicle WHERE "."VehicleID IN(SELECT vehicle_id FROM vehicle_grouping WHERE "."vehicle_group_id =(SELECT vehicle_group_id FROM "."account_detail WHERE account_id='$account_id') AND VehicleName='$vname'";      
	$result2=mysql_query($query2,$DbConnection);        
	if($row = mysql_fetch_object($result1))
	{
		$vid = $row->VehicleID;
	}
  
	$query3="INSERT INTO route_assignment('route_id','vehicle_id','create_id','create_date') VALUES('$route_id','$vid','$account_id','$date')";
	$result3=mysql_query($query3,$DbConnection);  
	if($result3)
	{
		$message="Route Assigned To Vehicle Successfully";
	}
	else
	{
		$message="Unable to Assign Route to Vehicle";
	} 
  
	echo'<br><br><table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2"><tr><td colspan="3" align="center"><b>'.$message.'</b></td></tr></table>';
	  
	if($DEBUG){print $query;}
	if($DEBUG==1){print_query($query1);}
	if($DEBUG==1){print_query($query2);}
	if($DEBUG==1 ){echo "route_name=".$route_name." vehicle_name=".$vehicle_name;} 
	
    include_once('manage_route.php');
?> 
	

