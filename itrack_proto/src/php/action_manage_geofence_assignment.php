<?php
	include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
	$DEBUG = 0;
	
	$query = "SELECT account_id from account WHERE superuser='$superuser' and user='$user' and grp='admin'";	
	$result = @mysql_query($query, $DbConnection);
	$row = @mysql_fetch_object($result);     	
	$user_account_id = $row->account_id; 
	
  $geo_name=trim($_POST['ls_1']);  /////// ls_1=geofence Name
  $vname=trim($_POST['rs_1']);   ///////// rs_1=vehicle Name

  $query="SELECT geo_id FROM geofence WHERE user_account_id='$user_account_id' AND geo_name='$geo_name'";
  $result=mysql_query($query,$DbConnection);        
  if($row = mysql_fetch_object($result))
  {$geo_id = $row->geo_id;}  
 	
  $query1 = "SELECT VehicleID FROM vehicle WHERE "."VehicleID IN(SELECT vehicle_id FROM vehicle_grouping WHERE "."vehicle_group_id =(SELECT vehicle_group_id FROM "."account_detail WHERE account_id='$account_id') AND VehicleName='$vname'";
  $result1=mysql_query($query1,$DbConnection);        
  if($row = mysql_fetch_object($result1))
  {$vid = $row->VehicleID;}  
  
  $query2="INSERT INTO geo_assignment('geo_id','vehicle_id','create_id','create_date') VALUES('$geo_id','$vid','$account_id','$date')";
  $result2=mysql_query($query2,$DbConnection);  

  if($result2){$message="Geofence Assigned To Vehicle Successfully";}
  else{$message="Unable to Assign Geofence to Vehicle";} 
  
 echo'<br><br><table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2"><tr><td colspan="3" align="center"><b>'.$message.'</b></td></tr></table>';
      
  if($DEBUG ==1)print_query($query);
  if($DEBUG ==1)print_query($query1);
  if($DEBUG ==1)print_query($query2);
  if($DEBUG==1){echo "geo name=".$geo_name." vname=".$vname." geo_id=".$geo_id;} 
    	
  include_once('manage_geofence.php');
?> 
	

