<?php
	include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');	

	$DEBUG =0;
	
  $post_action_type = $_POST['action_type'];
  
  if($DEBUG == 1)
    echo "post_action_type=".$post_action_type;
  
  if($post_action_type =="add")
  {	
    $imei_no_1=$_POST['imei_no'];
  	$manufacturing_date_1=$_POST['manufacturing_date'];
  	$make_1=$_POST['make'];
  
    date_default_timezone_set('Asia/Calcutta');
  	$date=date("Y-m-d h:i:s");
  	
    $query="INSERT INTO device_manufacturing_info(device_IMEI_no,manufacture_date,make,create_id,create_date,status)VALUES('$imei_no_1','$manufacturing_date_1','$make_1','$account_id','$date','1')";
    
    // echo"query=".$query;
    $result=mysql_query($query,$DbConnection);
  	
    if($result)
  	{
  	  $message="<center><br><br><FONT color=\"green\"><strong>Device Added Successfully</strong></font></center>";
    }
    else
    {
      $message="<center><br><br><FONT color=\"red\"><strong>Device Not Added Successfully</strong></font></center>";
    }  
  }
  
  else if($post_action_type =="edit")
  {
    $imei_no_edit = $_POST['imei_no_edit'];
  	$manufacturing_date_edit = trim($_POST['manufacturing_date_edit']);
  	$make_edit = trim($_POST['make_edit']);
    
    $new_value[] = $manufacturing_date_edit;
    $new_value[] = $make_edit;  
    
    // UPDATE TRACK LOG
    $query = "SELECT device_id,device_imei_no,manufacture_date,make FROM device_manufacturing_info WHERE device_imei_no='$imei_no_edit'";
    $result = mysql_query($query, $DbConnection);
    
    if ($DEBUG == 1)
      print_query($query);
      
    $row = mysql_fetch_object($result);
    $id = $row->device_id;
    $old_value[] = trim($row->manufacture_date);
    $old_value[] = trim($row->make);
    $table = "device_manufacturing_info";
    $field_name[] = "manufacture_date";
    $field_name[] = "make";
    
    //echo "id=".$id."old=".sizeof($old_value)." ,new=".sizeof($new_value)." field=".sizeof($field_name);
    $msg = track_table($id,$table,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
        
    if ($DEBUG == 1)
      echo "msg=".$msg;
          
    // UPDATE DEVICE
    $query="UPDATE device_manufacturing_info SET manufacture_date='$manufacturing_date_edit', ".
    "make='$make_edit', edit_id='$account_id', edit_date='$date' WHERE device_imei_no='$imei_no_edit'";
    
    if ($DEBUG == 1)
      print_query($query);
          
    $result = mysql_query($query, $DbConnection);           
    
    if($result)
      $message = "<center><br><br><FONT color=\"green\"><strong>Device Detail Updated Successfully</strong></font></center>";
    else 
      $message = "<center><br><br><FONT color=\"red\"><strong>Unable to Update Device Detail- Nothing to Update</strong></font></center>";    
  }
	
  else if($post_action_type == "delete")
  {
    $imei_no_edit = $_POST['imei_no_edit'];
    
    $new_value[] = "1";
    $query = "SELECT device_id,status FROM device_manufacturing_info WHERE device_imei_no='$imei_no_edit'";
    $result = mysql_query($query, $DbConnection);
 
    $id = $row->device_id;
    $table = "device_manufacturing_info";
    $old_value[]="1";$new_value[]="0";$field_name[]="status";
    
    $msg = track_table($id,$table,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
        
    if ($DEBUG == 1)
      echo "msg=".$msg;
          
    $query = "UPDATE device_manufacturing_info SET status='0',edit_id='$account_id',edit_date='$date' WHERE device_imei_no='$imei_no_edit'";
    if($DEBUG == 1) print_query($query);
    $result = mysql_query($query, $DbConnection);   
    if($result)
      $message = "<center><br><br><FONT color=\"red\"><strong>Selected Device Deleted Successfully</strong></font></center>";
    else 
      $message = "<center><br><br><FONT color=\"red\"><strong>Unable to Delete Device</strong></font></center>";  
  }
    
  echo' <br>
  <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
    <tr>
      <td colspan="3" align="center">'.$message.'</td>    
    </tr>
  </table>';
        	
  include_once("manage_device.php");
	
?> 
	

