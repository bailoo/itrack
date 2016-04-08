<?php
	include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');	

	$DEBUG = 0;
  $device1 = $_POST['device'];
  $device = explode(',',$device1);
   
  if($DEBUG == 1)
    echo "device=".$device." device=".$device." size=".sizeof($device);
   
   
  date_default_timezone_set('Asia/Calcutta');
	$date=date("Y-m-d h:i:s");
	
  for($i=0;$i<sizeof($device);$i++)
  {
    $query="UPDATE vehicle SET VehicleSerial=NULL WHERE VehicleSerial='$device[$i]'";
    $result1=mysql_query($query,$DbConnection);  
  
    $query="UPDATE vehicletable SET status='0',edit_id='$account_id',edit_date='$date' WHERE device_imei_no='$device[$i]'";
    $result2=mysql_query($query,$DbConnection);      
  
    $query = "SELECT VehicleID,TableID FROM vehicletable WHERE device_imei_no='$device[$i]'";
    $result = mysql_query($query,$DbConnection);
    $row = mysql_fetch_object($result);
    $vid = $row->VehicleID;
    $tableid = $row->TableID;
    
    // UPDATE vehicletable_track
    if($result1 && $result2)
    {
      $query="INSERT INTO vehicletable_track(VehicleID, TableID, device_imei_no, edit_id, edit_date, status)VALUES('$vid','$tableid','$device[$i]','$account_id','$date','1')";
      $result2=mysql_query($query,$DbConnection);      
    }
    
    if($DEBUG ==1 )
      print_query($query);
  } 
              
  // PRINT MESSAGE
  if($result1 && $result2)
	{
	  $message="<center><br><br><FONT color=\"green\"><strong>Device DeAssigned Successfully</strong></font></center>";
  }
  else
  {
    $message="<center><br><br><FONT color=\"red\"><strong>Unable to De Assign Device</strong></font></center>";
  }  

  echo' <br>
      <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
        <tr>
          <td colspan="3" align="center"><b>'.$message.'</b></td>    
        </tr>
      </table>';	
      
   include_once("manage_device_vehicle_deassignment.php");
?> 
	

