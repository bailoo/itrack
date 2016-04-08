<?php
	include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');	

	$DEBUG = 0;
  $geofence_option=$_POST['geofence_option'];
  $vehicle_option=$_POST['vehicle_option'];
  
  $geo_name = $_POST['geo_name'];  
  $geo_id2 = $_POST['geo_id2'];
  
  $vname = $_POST['vname'];  
  $vid2 = $_POST['vid2']; 
  
  $old_value= Array();
  $new_value= Array();
  $field_name=Array();   
  $table_name="vehicle"; 
  
  $query = "SELECT account_id from account WHERE superuser=$superuser' and user='$user' and grp='admin'";
	$result = @mysql_query($query, $DbConnection);
	$row = @mysql_fetch_object($result);     	
	$user_account_id = $row->account_id; 
  
  //if($DEBUG ==1 )
    //echo "Rizwan:Action:Deviceoption=".$device_option.", vehicle_option=".$vehicle_option.", imei_no=".$imei_no.", imei_no2=".$imei_no2.", vid=".$vid.", vid2=".$vid2;
  ////////////////////////////////////////
  
  $geo_id = "";
  $vid = "";
  
  if($geofence_option=="1")
  {
    $query="SELECT geo_id FROM geofence WHERE user_account_id='$user_account_id' AND geo_name='$geo_name'";
    $result=mysql_query($query,$DbConnection);        
    if($row = mysql_fetch_object($result))
    {
      $geo_id = $row->geo_id;
    }  
  }
  else if($geofence_option=="2")
  {
    $geo_id = $geo_id2; 
  }  
  
  if($vehicle_option=="1")  // IF VEHICLE NAME IS PASSED
  {   	
      $query = "SELECT VehicleID FROM vehicle WHERE ".
      "VehicleID IN(SELECT vehicle_id FROM vehicle_grouping WHERE ".
      "vehicle_group_id =(SELECT vehicle_group_id FROM ".
      "account_detail WHERE account_id='$account_id') AND ".
      "vehicle_id IN(SELECT VehicleID from vehicle WHERE VehicleSerial IS NULL)) AND ".
      "VehicleName='$vname'";  
      
      $result=mysql_query($query,$DbConnection);        
      if($row = mysql_fetch_object($result))
      {
        $vid = $row->VehicleID;
      }       
  }  
  else if($vehicle_option=="2")
  {
     $vid = $vid2;
  }  
 
	$new_value[]=$geo_id;
	$query1="SELECT geo_id from vehicle WHERE VehicleID='$vid'";
	if($DEBUG ==1 )
    print_query($query1);
  $result1=mysql_query($query1,$DbConnection); 
  $row1=mysql_fetch_object($result1);
  $geo_id_old=$row1->geo_id;
  $old_value[]= $geo_id_old;
  $field_name[]= "geo_id";
  
  $query2="UPDATE vehicle SET geo_id='$geo_id' WHERE VehicleID='$vid'";
  if($DEBUG ==1 )
    print_query($query2);
  $result2=mysql_query($query2,$DbConnection); 
  
  
  if($result2)
  {
    $ret_result=track_table($vid,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
    //echo "return_result=".$ret_result;     
  }
  
  if($DEBUG ==1 )
    print_query($query);
              
  // PRINT MESSAGE
  if($result1 && $ret_result=="success")
	{
	  $message="Geofence Assigned To Vehicle Successfully";
  }
  else
  {
    $message="Unable to Assign Geofence to Vehicle";
  }  

  echo'<br><br>
      <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
        <tr>
          <td colspan="3" align="center"><b>'.$message.'</b></td>    
        </tr>       
      </table>';	
      include_once('manage_geofence_assignment.php');
?> 
	

