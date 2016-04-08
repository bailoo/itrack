<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');

  $DEBUG=0;	
  
  $post_action_type = $_POST['action_type'];
  
  if($post_action_type =="add")
  {  
  	$flag=0;
  	$result_response=1;
  	
    $post_vehicle_name=$_POST['vehicle_name'];
    $post_vehicle_number=$_POST['vehicle_number'];
    $post_max_speed=$_POST['max_speed'];
    $post_vehicle_tag=$_POST['vehicle_tag'];
    $post_vehicle_type=$_POST['vehicle_type'];
    
    if($DEBUG)
    {  
      echo "Vehicle Name = ".$post_vehicle_name." (Length: ".strlen($post_vehicle_name).") <br>";
      echo "Vehicle Number = ".$post_vehicle_number." (Length: ".strlen($post_vehicle_number).") <br>";
      echo "MAX Speed = ".$post_max_speed." (Length: ".strlen($post_max_speed).") <br>";
      echo "Vehicle Tag = ".$post_vehicle_tag." (Length: ".strlen($post_vehicle_tag).") <br>";
      echo "Vehicle Type = ".$post_vehicle_type." (Length: ".strlen($post_vehicle_type).") <br>";
    }
    
    if (strlen($post_vehicle_name)==0 || strlen($post_vehicle_number)==0 || strlen($post_max_speed)==0 || strlen($post_vehicle_tag)==0)
    {
      $ERROR = "Empty Input!";
      $flag = -1;
    }
    else
    {
      $query="SELECT VehicleName FROM vehicle WHERE VehicleID IN (SELECT DISTINCT vehicle_id FROM vehicle_grouping WHERE vehicle_group_id IN (SELECT DISTINCT vehicle_group_id FROM account_detail WHERE account_id IN (SELECT account_id FROM account WHERE superuser='$superuser' AND user='$user'))) AND VehicleName='$post_vehicle_name'";
      if($DEBUG) print_query($query);
    	$result=mysql_query($query,$DbConnection);
    	$count = mysql_num_rows($result);
      if($count > 0)
      {
        $ERROR = "Vehicle Name already exist!";
      	$flag = -1;
      }
      else
      {
        $account_id_i = $account_id;
        $vehicle_gids = Array();
        $i = 1;
        // if($DEBUG) echo "<center><table border=2 cellpadding=4>";
        $vid_lookup_detail = "<center><table border=2 cellpadding=4>";
        // if($DEBUG) echo "<tr><th>grp</th><th>vehicle_group_id</th><th>account_admin_id</th><th>account_id</th></tr>";
        $vid_lookup_detail .= "<tr><th>grp</th><th>vehicle_group_id</th><th>account_admin_id</th><th>account_id</th></tr>";
        do{
          $query="SELECT grp,vehicle_group_id,account_admin_id FROM account,account_detail WHERE account.account_id=account_detail.account_id AND account_detail.account_id='$account_id_i'";
          if($DEBUG) print_query($query);
        	$result=mysql_query($query,$DbConnection);
        	$row=mysql_fetch_object($result);
        	$grp_i=$row->grp;
        	$vehicle_group_id_i=$row->vehicle_group_id;
        	$account_admin_id_i=$row->account_admin_id;
        	
        	$query="SELECT account_id FROM account_detail WHERE admin_id='$account_admin_id_i' LIMIT 1";
          if($DEBUG) print_query($query);
        	$result=mysql_query($query,$DbConnection);
        	$row=mysql_fetch_object($result);
        	$account_id_i=$row->account_id;
      
          $vehicle_gids[$i++]=$vehicle_group_id_i;
          // if($DEBUG) echo "<tr><td>$grp_i</td><td>$vehicle_group_id_i</td><td>$account_admin_id_i</td><td>$account_id_i</td></tr>";
          $vid_lookup_detail .= "<tr><td>$grp_i</td><td>$vehicle_group_id_i</td><td>$account_admin_id_i</td><td>$account_id_i</td></tr>";
        } while ($grp_i != "admin");
        // if($DEBUG) echo "</table></center>";
        $vid_lookup_detail .= "</table></center>";
        if($DEBUG) echo $vid_lookup_detail;
      
      	date_default_timezone_set('Asia/Calcutta'); // add conversion based on the time zone of user
      	$date=date("Y-m-d H:i:s");
      
        // adding vehicle into vehicle info table
        $query="INSERT INTO vehicle (VehicleName,VehicleType,tag,vehicle_number,max_speed,status,create_id,create_date) VALUES ('$post_vehicle_name','$post_vehicle_type','$post_vehicle_tag','$post_vehicle_number','$post_max_speed','1','$account_id','$date')";
        $result1=mysql_query($query,$DbConnection);
        $result_response = $result_response && $result1;
        if($DEBUG) print_message("Result = ".$result1."/".$result_response, $query);
      
        // get vehicle id of new vehicle
        $query="SELECT VehicleID FROM vehicle WHERE VehicleName='$post_vehicle_name' AND create_id='$account_id' AND create_date='$date'";
        if($DEBUG) print_query($query);
      	$result=mysql_query($query,$DbConnection);
      	$row=mysql_fetch_object($result);
      	$VehicleID=$row->VehicleID;
      
        $query="INSERT INTO vehicle_grouping (vehicle_group_id,vehicle_id,status,create_id,create_date) VALUES";  
        for ($i=1; $i<sizeof($vehicle_gids); $i++)
        {
          $query.=" ('".$vehicle_gids[$i]."','$VehicleID','1','$account_id','$date') ,";
        }
        $query.=" ('".$vehicle_gids[sizeof($vehicle_gids)]."','$VehicleID','1','$account_id','$date')";
        $result1=mysql_query($query,$DbConnection);
        $result_response = $result_response && $result1;
        if($DEBUG) print_message("Result = ".$result1."/".$result_response, $query);
      
      	if($result_response)
      	{
      	 $flag=1;
        }
      }
    }
    
    if($flag==1)
  	{
  		$message = "<center><br><br><FONT color=\"green\"><strong>Vehicle Detail Added Successfully!</strong></font></center>";
  	}
    else if($flag==-1)
    {
      $message = "<center><br><br><FONT color=\"red\"><strong>".$ERROR."</strong></font></center>";
    }					
  	else if($flag==0)
  	{
  		$message = "<center><br><br><FONT color=\"red\"><strong>Unable to add Vehicle Detail Due to Some Server Problem!</strong></font></center>";
  	}
  	else
  	{
      $message = "<center><br><br><FONT color=\"red\"><strong>Sorry! Unable to process request.</strong></font></center>";
    }
  }
  else if($post_action_type == "edit")
  {   
    $vehicle_id_edit = $_POST['vehicle_id_edit'];
    
    $vehicle_name_edit = $_POST['vehicle_name_edit'];
  	$vehicle_number_edit = $_POST['vehicle_number_edit'];
  	$max_speed_edit = $_POST['max_speed_edit'];
    $vehicle_tag_edit = $_POST['vehicle_tag_edit']; 
    $vehicle_type_edit = $_POST['vehicle_type_edit'];     	
    
    $new_value[] = $vehicle_name_edit;
    $new_value[] = $vehicle_number_edit;  
    $new_value[] = $max_speed_edit;
    $new_value[] = $vehicle_tag_edit;  
    $new_value[] = $vehicle_type_edit;          
    
    // UPDATE TRACK LOG
    $query = "SELECT VehicleID,VehicleName,vehicle_number,max_speed,tag,VehicleType FROM vehicle WHERE VehicleID='$vehicle_id_edit'";
    $result = mysql_query($query, $DbConnection);
    if($DEBUG == 1) print_query($query);    
    
    $row = mysql_fetch_object($result);
    $id = $row->VehicleID;
    $old_value[] = $row->VehicleName;
    $old_value[] = $row->vehicle_number;
    $old_value[] = $row->max_speed;
    $old_value[] = $row->tag;
    $old_value[] = $row->VehicleType;
    
    $table = 'vehicle';
    $field_name[] = "VehicleName";
    $field_name[] = "vehicle_number";
    $field_name[] = "max_speed";
    $field_name[] = "tag";
    $field_name[] = "VehicleType";
    
    $msg = track_table($id,$table,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
        
    // UPDATE DEVICE
    $query="UPDATE vehicle SET VehicleName='$vehicle_name_edit',vehicle_number='$vehicle_number_edit', ".
    "max_speed='$max_speed_edit',tag='$vehicle_tag_edit',VehicleType='$vehicle_type_edit', ".
    "edit_id='$account_id', edit_date='$date' WHERE VehicleID='$vehicle_id_edit'";  
    $result = mysql_query($query, $DbConnection);    
           
    if($DEBUG == 1) print_query($query);
    
    if($result)
      $message = "<center><br><br><FONT color=\"green\"><strong>Vehicle Detail Updated Successfully</strong></font></center>";
    else 
      $message = "<center><br><br><FONT color=\"green\"><strong>Unable to Update Vehicle Detail</strong></font></center>";  
  }
  
  else if($post_action_type == "delete")
  {   
    $vehicle_id_edit = $_POST['vehicle_id_edit'];
    $table = "vehicle";
    $old_value[]="1";$new_value[]="0";$field_name[]="status";
    
    $msg = track_table($vehicle_id_edit,$table,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);

    if($DEBUG == 1) echo "msg=".$msg;
    
    $query = "SELECT VehicleSerial FROM vehicle WHERE VehicleID='$vehicle_id_edit' AND VehicleSerial IS NOT NULL";
    $result = mysql_query($query, $DbConnection);
    $numrows = mysql_num_rows($result);
    if($numrows>0)
    {
      $message = "<center><br><br><FONT color=\"red\"><strong>This Vehicle has been Assigned to Device! Deassign First!</strong></font></center>";  
    }
    else
    {
      $query = "UPDATE vehicle SET status='0',edit_id='$account_id',edit_date='$date' WHERE VehicleID='$vehicle_id_edit'";
      
      if($DEBUG == 1) print_query($query);
      $result = mysql_query($query, $DbConnection);   
      if($result)
        $message = "<center><br><br><FONT color=\"red\"><strong>Selected Vehicle Deleted Successfully</strong></font></center>";
      else
        $message = "<center><br><br><FONT color=\"red\"><strong>Unable to Delete Vehicle Detail</strong></font></center>";
    }    
  }

	echo' <br>
  <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
    <tr>
      <td colspan="3" align="center"><b>'.$message.'</b></td>    
    </tr>
  </table>';
        	
	include_once("manage_vehicle.php");

?>
        