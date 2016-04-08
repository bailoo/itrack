<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 	
	$DEBUG = 0;
	$action_type1 = $_POST['action_type'];
	$local_account_ids = $_POST['local_account_ids'];
	$local_account_ids=explode(",",$local_account_ids);
	$account_size=sizeof($local_account_ids);  
  
	$old_value= Array();	$new_value=Array();		$field_name=Array(); 	$table_name="shift"; 
  
	include_once('track_log.php');
  
	if($action_type1=="add") 
	{     
		$shift_name1=trim($_POST['shift_name']);
		$shift_starttime1=trim($_POST['shift_starttime']);
		$shift_stoptime1=trim($_POST['shift_stoptime']);
		    
		//$route_coord1=base64_encode(trim($_POST['route_coord']));
		
		$max_no= getMaxSerialShift($DbConnection);
		if($max_no=="")
		{
			$max_no=1;
		}
		    
		$result=insertShift($account_size,$local_account_ids,$max_no,$shift_name1,$shift_starttime1,$shift_stoptime1,$account_id,$date,$DbConnection);          	  
		if($result)
		{
			$flag=1;
			$action_perform="Added";
		} 
		   
	}
  
	else if($action_type1=="edit")
	{
		$shift_id1 = $_POST['shift_id'];    
		
		$shift_name1=trim($_POST['shift_name']);
		$new_value[]=$shift_name1;
		
		$shift_starttime1=trim($_POST['shift_starttime']);
		$new_value[]=$shift_starttime1;
		
		$shift_stoptime1=trim($_POST['shift_stoptime']);
		$new_value[]=$shift_stoptime1;
		             

		/*$query="SELECT * FROM shift where shift_id='$shift_id1' AND status='1'";
		if($DEBUG ==1 )
		print_query($query);
		$result=mysql_query($query,$DbConnection);
		$row=mysql_fetch_object($result);
		$shift_name2=$row->shift_name;
		$old_value[] =$shift_name2;
		$field_name[]="shift_name";
		$shift_starttime2=$row1->shift_starttime;         
		$old_value[] = $shift_starttime2;
		$field_name[]="shift_starttime";
		$shift_stoptime2=$row1->shift_stoptime;         
		$old_value[] = $shift_stoptime2;
		$field_name[]="shift_stoptime";*/ 
		
		$result=editShift($shift_name1,$shift_starttime1,$shift_stoptime1,$account_id,$date,$shift_id1,$DbConnection); 
		//$ret_result=track_table($shift_id1,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
		if($result)
		{
			$flag=1;
			$action_perform="Updated";
		} 
	    
	}
  
	else if ($action_type1=="delete")
	{
		$shift_id1 = $_POST['shift_id'];    
		//$query="UPDATE shift SET edit_id='$account_id',edit_date='$date',status='0' WHERE shift_id='$shift_id1' AND status='1'"; 
		$result=deleteShift($account_id,$date,$shift_id1);    
		//$old_value[]="1";
		//$new_value[]="0";
		//$field_name[]="status";     
		//$ret_result=track_table($shift_id1,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);    
		if($result)
		{
			$flag=1;
			$action_perform="Deleted";
		}
	}
	else if($action_type1=="assign")
	{
		$local_vehicle_ids = $_POST['vehicle_ids'];		
		$local_vehicle_ids=explode(",",$local_vehicle_ids);
		$vehicle_size=sizeof($local_vehicle_ids);	
		$local_route_id = $_POST['route_id'];
		    
		$result=insertRouteAssign($vehicle_size,$local_route_id,$local_vehicle_ids,$account_id,$date,$DbConnection);          	  
		if($result)
		{
			$flag=1;
			$action_perform="Assigned";
		} 		
	}
	else if($action_type1=="deassign")
	{
		$local_vehicle_ids = $_POST['vehicle_ids'];	
		$local_vehicle_ids=explode(",",$local_vehicle_ids);
		$vehicle_size=sizeof($local_vehicle_ids);
		
		
		$result=deleteRouteAssign($vehicle_size,$account_id,$date,$local_vehicle_ids,$DbConnection);
		if($result)
		{
			$flag=1;
			$action_perform="De-Assigned";
		} 	
	}
 
	if($flag==1)
	{
		$msg = "Shift ".$action_perform." Successfully";
		$msg_color = "green";				
	}	
	else
	{
		$msg = "Sorry! Unable to process request.";
		$msg_color = "red";		
	} 

 echo "<center><br><br><FONT color=\"".$msg_color."\" size=\"2\">".$msg."<br><br></strong></font></center>";
  echo'<center><a href="javascript:show_option(\'manage\',\'shift\');" class="back_css">&nbsp;<b>Back</b></a></center>'; 

  
?>   