<?php
	include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');	

	$DEBUG = 0;
	
  $post_action_type = $_POST['action_type'];
  
  $imei_no_1=$_POST['imei_no'];
	$superuser_1=$_POST['super_user'];
	$user_1 = $_POST['user'];
	//$qos_1 = $_POST['qos'];
	//$qos_1 = "1";
      
  if($post_action_type =="add")
  {	    	
  	$query="SELECT account_id FROM account WHERE superuser='$superuser_1' AND user='$user_1' AND grp='admin'";
    $result=mysql_query($query,$DbConnection);
    $row=mysql_fetch_object($result);
    $user_account_id=$row->account_id;

         
    $query = "SELECT device_imei_no FROM device_sales_info WHERE device_imei_no='$imei_no_1' AND user_account_id='$user_account_id' AND create_id='$account_id' AND status='1'";
    $result = mysql_query($query, $DbConnection);
    $numrows = mysql_num_rows($result);
    
    if($numrows)
    {
      $message ="<center><FONT color=\"blue\"><strong>This Device has Already been Sold</strong></font></center>";
    }
    else
    {
      $query="INSERT INTO device_sales_info(device_imei_no, user_account_id, create_id, create_date, status)VALUES('$imei_no_1','$user_account_id','$account_id','$date','1')";
      $result=mysql_query($query,$DbConnection);  
        
      // PRINT MESSAGE
      if($result)
    	{
    	  $message="<center><FONT color=\"green\"><strong>Device Sold Successfully</strong></font></center>";
      }
      else
      {
        $message="<center><FONT color=\"red\"><strong>Unable to sell</strong></font></center>";
      }  
    }
  }   
  /*else if($post_action_type == "edit")
  {   	
    if($DEBUG == 1) print_query($query);
    
    if($result && $msg=="success")
      $message = "<center><br><br><FONT color=\"green\"><strong>Device Sale Detail Updated Successfully</strong></font></center>";
    else 
      $message = "<center><br><br><FONT color=\"green\"><strong>Unable to Update Device Sale Detail</strong></font></center>";  
  }*/
  
  else if($post_action_type == "delete")
  {       
    $query = "SELECT serial FROM device_sales_info WHERE device_imei_no='$imei_no_1'";
    $result = mysql_query($query, $DbConnection);
    $row = mysql_fetch_object($result);
    $serial = $row->serial;
    
    $table = "device_sales_info";
    $old_value[]="1";$new_value[]="0";$field_name[]="status";
    
    $msg = track_table($serial,$table,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);

    if($DEBUG == 1) echo "msg=".$msg;

    $query = "UPDATE device_sales_info SET status='0',edit_id='$account_id',edit_date='$date' WHERE device_imei_no='$imei_no_1'";
    
    if($DEBUG == 1) print_query($query);
    $result = mysql_query($query, $DbConnection);   
    if($result)
      $message = "<center><FONT color=\"red\"><strong>Selected Device Sale Detail Deleted Successfully</strong></font></center>";
    else
      $message = "<center><FONT color=\"red\"><strong>Unable to Delete Device Sale Detail Detail</strong></font></center>";    
  }
	
  echo' <br> 
  <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
  <tr>
    <td colspan="3" align="center"><b>'.$message.'</b></td>    
  </tr>
  </table>';
  
  include_once("manage_device_sale.php");
        	
?> 
	

