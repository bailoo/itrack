<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$DEBUG=0;
	
	$action_type1 = $_POST['action_type'];
	$local_account_ids = $_POST['local_account_ids'];
	$local_account_ids=explode(",",$local_account_ids);
	$account_size=sizeof($local_account_ids);

	$old_value= Array();		$new_value=Array();			$field_name=Array();		$table_name="sector"; 
  
	if($action_type1=="add") 
	{ 
		$route_name1 = trim($_POST['route_name']);

		$sector_ids1 = $_POST['sector_ids'];
		$sector_ids1 = trim($sector_ids1);  

		$query ="select Max(sno)+1 as serial_no from route";  ///// for auto increament of geo_id ///////////   
		$result=mysql_query($query,$DbConnection);
		$row=mysql_fetch_object($result);
		$max_no= $row->serial_no;
		if($max_no==""){$max_no=1;}

		$query_string1="INSERT INTO route(user_account_id,route_id,route_name,route_sector_ids,status,create_id,create_date) VALUES";

		for($i=0;$i<$account_size;$i++)
		{
			//echo "accout_id=".$local_account_ids[$i]."<br>";
			if($i==$account_size-1)
			{
				$query_string2.="('$local_account_ids[$i]','$max_no','$route_name1','$sector_ids1','1','$account_id','$date');";
			}
			else
			{
				$query_string2.="('$local_account_ids[$i]','$max_no','$route_name1','$sector_ids1','1','$account_id','$date'),";
			}
		}
    
    $query=$query_string1.$query_string2; 
		//echo "query=".$query;

		if($DEBUG ==1 )print_query($query);     
		$result=mysql_query($query,$DbConnection);          	  
		if($result){$flag=1;$action_perform="Added";}      
	}
  
	else if($action_type1=="edit")
	{
		//$type="edit_delete";
		$route_id1 = $_POST['route_id'];
		$route_name1 = $_POST['route_name'];
		$sector_ids1 = $_POST['sector_ids'];
		$sector_ids1 = trim($sector_ids1); 
		$new_value[]=$sector_ids1;        

		$query="SELECT * FROM route where route_sector_ids='$sector_id1' AND status='1' WHERE route_id='$route_id1'";
		$result=mysql_query($query,$DbConnection);
		$row=mysql_fetch_object($result);
		$table_name = "route";
		$field_name[]="route_sector_ids";
		$old_sector_ids=$row->route_sector_ids;
		$old_value[] =$old_sector_ids;		

		$query="UPDATE route SET route_sector_ids='$sector_ids1',route_name='$route_name1',edit_id='$account_id',edit_date='$date' WHERE route_id='$route_id1'";
		//echo "query=".$query;
		$result=mysql_query($query,$DbConnection); 

		$ret_result=track_table($old_sector_ids,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
		//echo "<br>ret_res=".$ret_result;
    if($ret_result=="success" && $result){$flag=1;$action_perform="Updated";}     
	}
	else if ($action_type1=="delete")
	{
		// $type="edit_delete";
		$route_id1 = $_POST['route_id'];    
		$query="UPDATE route SET edit_id='$account_id',edit_date='$date',status=0 WHERE route_id='$route_id1' AND status='1'"; 
		$result=mysql_query($query,$DbConnection);    
		$old_value[]="1";$new_value[]="0";$field_name[]="status";     
		$ret_result=track_table($route_id1,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);    
		//if($ret_result=="success"  && $result){$flag=1;$action_perform="Deleted";}
		if($result){$flag=1;$action_perform="Deleted";}
	}
	
	else if($action_type1=="assign")
	{
		$local_vehicle_ids = $_POST['vehicle_ids'];
		//echo "local_vehicle_ids=".$local_vehicle_ids."<br>";
		$local_vehicle_ids=explode(",",$local_vehicle_ids);
		$vehicle_size=sizeof($local_vehicle_ids);
		//echo "vehicle_size=".$vehicle_size."<br>";
		$local_route_id = $_POST['route_id'];
		
		for($i=0;$i<$vehicle_size;$i++)
		{
      $query="SELECT route_id FROM route_assignment WHERE vehicle_id='$local_vehicle_ids[$i]' AND route_id='$local_route_id' and status=1";
      //echo $query."<br>";
      $result=mysql_query($query,$DbConnection);
      $numrows = mysql_num_rows($result);
      if($numrows)
      {
        $row = mysql_fetch_object($result);
        $route_id2 = $row->route_id;     
        $query2 = "UPDATE route_assignment SET status=0 WHERE vehicle_id='$local_vehicle_ids[$i]' AND route_id='$route_id2'";
        //echo $query2."<br>";
        $result2=mysql_query($query2,$DbConnection);
      } 
    }        	
  		
    $query_string1="INSERT INTO route_assignment(route_id,vehicle_id,create_id,create_date,status) VALUES";

		for($i=0;$i<$vehicle_size;$i++)
		{
			if($i==$vehicle_size-1)
			{
				$query_string2.="($local_route_id,$local_vehicle_ids[$i],$account_id,'$date',1);";
			}
			else
			{
				$query_string2.="($local_route_id,$local_vehicle_ids[$i],$account_id,'$date',1),";
			}
		}
		$query=$query_string1.$query_string2; 
		//echo "querystr=".$query;
	
		if($DEBUG ==1 )print_query($query);     
		$result=mysql_query($query,$DbConnection);          	  
		if($result){$flag=1;$action_perform="Assigned";} 		
	}
	else if($action_type1=="deassign")
	{
		$local_vehicle_ids = $_POST['vehicle_ids'];
		//echo "local_vehicle_ids=".$local_vehicle_ids."<br>";
		$local_vehicle_ids=explode(",",$local_vehicle_ids);
		$vehicle_size=sizeof($local_vehicle_ids);
		
		$old_value= Array();   $new_value=Array();   $field_name=Array(); $table_name="route_assignment";
		for($i=0;$i<$vehicle_size;$i++)
		{	
			$query="UPDATE route_assignment SET status=0,edit_id='$account_id',edit_date='$date' WHERE vehicle_id='$local_vehicle_ids[$i]' AND status=1";
			$result=mysql_query($query,$DbConnection); 
			$route_id1= $veh_route_id2[1];
      $old_value[]= "1";
			$new_value[]="0";
			$field_name[]= "status";         
			$ret_result=track_table($local_vehicle_ids[$i],$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
		}
		
		if($ret_result=="success" && $result){$flag=1;$action_perform="De-Assigned";} 	
	}
 
	if($flag==1)
	{
		$msg = "Route ".$action_perform." Successfully";
		$msg_color = "green";				
	}	
	else
	{
		$msg = "Sorry! Unable to process request.";
		$msg_color = "red";		
	}
  
  echo "<center><br><br><FONT color=\"".$msg_color."\" size=\"2\">".$msg."<br><br></strong></font></center>";
  echo'<center><a href="javascript:show_option(\'manage\',\'route\');" class="back_css">&nbsp;<b>Back</b></a></center>';                 
  
?>
        