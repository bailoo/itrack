<?php
	include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  include_once('track_log.php'); 
	$DEBUG =0;
  $veh_geo_id1 = $_POST['veh_geo_id'];
  $veh_geo_id = explode(',',$veh_geo_id1);
  
  $old_value= Array();   $new_value=Array();   $field_name=Array(); $table_name="geo_assignment";
	
  for($i=0;$i<sizeof($veh_geo_id);$i++)
  {
    $veh_geo_id2=explode("#",$veh_geo_id[$i]); 
    $query="UPDATE geo_assignment SET status=0 WHERE vehicle_id='$veh_geo_id2[0]' AND geo_id='$veh_geo_id2[1]'";
    $result1=mysql_query($query,$DbConnection); 
    $geo_id1= $veh_geo_id2[1];
    $old_value[]= "1";
    $new_value[]="0";
    $field_name[]= "status";         
    $ret_result=track_table($geo_id1,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
  } 
                
  if($result1 && $ret_result=="success"){$message="Geofence DeAssigned Successfully";}
  else{$message="Unable to De Assign Geofence";}
  echo'<br><br><table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2"><tr><td colspan="3" align="center"><b>'.$message.'</b></td></tr></table>';
  
  if($DEBUG == 1){echo "geo_id=".$geo_id." geo_id=".$geo_id." size=".sizeof($veh_geo_id);}    
  if($DEBUG==1)print_query($query);
  
  include_once('manage_geofence.php');
?> 
	

