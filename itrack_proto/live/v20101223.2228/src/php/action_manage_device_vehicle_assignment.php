<?php
	include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');	

	$DEBUG = 0;
  $device_option=$_POST['device_option'];
  $vehicle_option=$_POST['vehicle_option'];
  
  $imei_no = $_POST['imei_no'];  
  $imei_no2 = $_POST['imei_no2'];
  
  $vname = $_POST['vname'];  
  $vid2 = $_POST['vid2'];  
  
  //if($DEBUG ==1 )
    //echo "Rizwan:Action:Deviceoption=".$device_option.", vehicle_option=".$vehicle_option.", imei_no=".$imei_no.", imei_no2=".$imei_no2.", vid=".$vid.", vid2=".$vid2;
  ////////////////////////////////////////
  
  $device = "";
  $vid = "";
  
  if($device_option=="1")
  {
    $device = $imei_no; 
  }
  else if($device_option=="2")
  {
    $device = $imei_no2; 
  }  
  
  if($vehicle_option=="1")  // IF VEHICLE NAME IS PASSED
  {   
      $query = "SELECT superuser, user FROM account WHERE account_id='$account_id'";   		
      $result = @mysql_query($query, $DbConnection);      	
      $row = mysql_fetch_object($result);
      
      $user = $row->user;
      $superuser = $row->superuser;    	
      
      $query = "SELECT account_id from account WHERE superuser=$superuser' and user='$user' and grp='admin'";
    	$result = @mysql_query($query, $DbConnection);
    	$row = @mysql_fetch_object($result);     	
    	$user_account_id = $row->account_id;
    	
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
   
  date_default_timezone_set('Asia/Calcutta');
	$date=date("Y-m-d h:i:s");
	
  $query="UPDATE vehicle SET VehicleSerial='$device' WHERE VehicleID='$vid'";
  $result1=mysql_query($query,$DbConnection);  
    
  if($DEBUG ==1 )
    print_query($query);
      
  $query = "SELECT track_table_id from device_lookup WHERE device_imei_no='$device'";
  $result = mysql_query($query,$DbConnection);
  $row = mysql_fetch_object($result);
  $tableid = $row->track_table_id;
  
  $query = "SELECT device_imei_no FROM vehicletable WHERE device_imei_no='$device' AND Status='0'";
  $result = mysql_query($query,$DbConnection);  
  $numrows = mysql_num_rows($result);

  if($result1 && $numrows)
  {
    $query="UPDATE vehicletable SET status='1',edit_id='$account_id',edit_date='$date' WHERE device_imei_no='$device'";
    $result2=mysql_query($query,$DbConnection);      
  }
  else
  {
    $query="INSERT INTO vehicletable(VehicleID, TableID, device_imei_no, create_id, create_date, status)VALUES('$vid','$tableid','$device','$account_id','$date','1')";
    $result2=mysql_query($query,$DbConnection);    
  }
  
  // UPDATE vehicletable_track
  if($result2)
  {
    $query="INSERT INTO vehicletable_track(VehicleID, TableID, device_imei_no, edit_id, edit_date, status)VALUES('$vid','$tableid','$device','$account_id','$date','1')";
    $result2=mysql_query($query,$DbConnection);      
  }
  
  if($DEBUG ==1 )
    print_query($query);
              
  // PRINT MESSAGE
  if($result1 && $result2)
	{
	  $message="<center><br><br><FONT color=\"green\"><strong>Device Vehicle Assigned Successfully</strong></font></center>";
  }
  else
  {
    $message="<center><br><br><FONT color=\"red\"><strong>Unable to Assign Device to Vehicle</strong></font></center>";
  }  

  echo' <br>
      <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
        <tr>
          <td colspan="3" align="center"><b>'.$message.'</b></td>    
        </tr>
      </table>';
      
  include_once("manage_device_vehicle_assignment.php");	
?> 
	

