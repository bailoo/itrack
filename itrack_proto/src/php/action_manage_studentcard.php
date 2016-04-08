<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 	
	$DEBUG = 0;
	$action_type1 = $_POST['action_type'];
	$local_account_ids = $_POST['local_account_ids'];
	$local_account_ids=explode(",",$local_account_ids);
	$account_size=sizeof($local_account_ids);  
  
	$old_value= Array();	$new_value=Array();		$field_name=Array(); 	$table_name="studentcard"; 
  
	include_once('track_log.php');
  
	if($action_type1=="add") 
	{     
		$studentcard_name1=trim($_POST['studentcard_name']);
				    
		//$route_coord1=base64_encode(trim($_POST['route_coord']));

		$query2 ="select Max(sno)+1 as seiral_no from studentcard";  ///// for auto increament of school_id ///////////   
		$result2=mysql_query($query2,$DbConnection);
		$row1=mysql_fetch_object($result2);
		$max_no= $row1->seiral_no;
		if($max_no==""){$max_no=1;}
		
		$query_string1="INSERT INTO studentcard(user_account_id,studentcard_id,studentcard_number,status,create_id,create_date) VALUES";

		for($i=0;$i<$account_size;$i++)
		{
			//echo "accout_id=".$local_account_ids[$i]."<br>";
			if($i==$account_size-1)
			{
				$query_string2.="('$local_account_ids[$i]','$max_no','$studentcard_name1','1','$account_id','$date');";
			}
			else
			{
				$query_string2.="('$local_account_ids[$i]','$max_no','$studentcard_name1','1','$account_id','$date'),";
			}
		}
		$query=$query_string1.$query_string2; 
		//echo $query;
		if($DEBUG ==1 )print_query($query);     
		$result=mysql_query($query,$DbConnection);          	  
		if($result){$flag=1;$action_perform="Added";} 
		   
	}
  
	else if($action_type1=="edit")
	{
		$studentcard_id1 = $_POST['studentcard_id'];    
		$edit_studentcard_number1 =trim($_POST['edit_studentcard_number']);
		$new_value[]=$edit_studentcard_number1;
		  

		$query="SELECT * FROM studentcard where studentcard_id='$studentcard_id1' AND status='1'";
		if($DEBUG ==1 )
		print_query($query);
		$result=mysql_query($query,$DbConnection);
		$row=mysql_fetch_object($result);
		$studentcard_number2=$row->studentcard_number;
		$old_value[] =$studentcard_number2;
		$field_name[]="studentcard_number";
		
		$query="UPDATE studentcard SET studentcard_number='$edit_studentcard_number1',edit_id='$account_id',edit_date='$date' WHERE studentcard_id='$studentcard_id1'";
		if($DEBUG ==1 )
		print_query($query);
		$result=mysql_query($query,$DbConnection); 

		$ret_result=track_table($studentcard_id1,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
		if($ret_result=="success" && $result){$flag=1;$action_perform="Updated";} 
	    
	}
  
	else if ($action_type1=="delete")
	{
		$studentcard_id1 = $_POST['studentcard_id'];    
		$query="UPDATE studentcard SET edit_id='$account_id',edit_date='$date',status='0' WHERE studentcard_id='$studentcard_id1' AND status='1'"; 
		$result=mysql_query($query,$DbConnection);    
		$old_value[]="1";
		$new_value[]="0";
		$field_name[]="status";     
		$ret_result=track_table($studentcard_id1,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);    
		if($ret_result=="success"  && $result){$flag=1;$action_perform="Deleted";}
	}
	else if($action_type1=="assign")
	{
	/*	$local_vehicle_ids = $_POST['vehicle_ids'];		
		$local_vehicle_ids=explode(",",$local_vehicle_ids);
		$vehicle_size=sizeof($local_vehicle_ids);	
		$local_route_id = $_POST['route_id'];
		
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
		//echo "query=".$query;
	   */
	  $table_name="student"; 
    $local_studentcard_id = $_POST['studentcard_id'];
    $local_student_id = $_POST['student_id'];
    
    $query="UPDATE student SET studentcard_id='$local_studentcard_id',edit_id='$account_id',edit_date='$date' WHERE student_id='$local_student_id' AND status=1";
		//print_query($query);
    $result=mysql_query($query,$DbConnection); 
		$geo_id1= $veh_geo_id2[1];
		$old_value[]= "0";
		$new_value[]=$local_studentcard_id;
		$field_name[]= "studentcard_id";         
		$ret_result=track_table($local_student_id,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
		
		if($ret_result=="success" && $result){$flag=1;$action_perform="Assigned";}
    
	/*	if($DEBUG ==1 )print_query($query);     
		$result=mysql_query($query,$DbConnection);          	  
		if($result){$flag=1;$action_perform="Assigned";}
    */ 		
	}
	else if($action_type1=="deassign")
	{
		$local_vehicle_ids = $_POST['vehicle_ids'];	
		$local_vehicle_ids=explode(",",$local_vehicle_ids);
		$vehicle_size=sizeof($local_vehicle_ids);
		
		$old_value= Array();   $new_value=Array();   $field_name=Array(); $table_name="geo_assignment";

		for($i=0;$i<$vehicle_size;$i++)
		{	
			$query="UPDATE route_assignment SET status=0,edit_id='$account_id',edit_date='$date' WHERE vehicle_id='$local_vehicle_ids[$i]' AND status=1";
			$result=mysql_query($query,$DbConnection); 
			$geo_id1= $veh_geo_id2[1];
			$old_value[]= "1";
			$new_value[]="0";
			$field_name[]= "status";         
			$ret_result=track_table($local_vehicle_ids[$i],$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
		}
		
		if($ret_result=="success" && $result){$flag=1;$action_perform="De-Assigned";} 	
	}
 
	if($flag==1)
	{
		$msg = "Student Card ".$action_perform." Successfully";
		$msg_color = "green";				
	}	
	else
	{
		$msg = "Sorry! Unable to process request.";
		$msg_color = "red";		
	} 

 echo "<center><br><br><FONT color=\"".$msg_color."\" size=\"2\">".$msg."<br><br></strong></font></center>";
  echo'<center><a href="javascript:show_option(\'manage\',\'studentcard\');" class="back_css">&nbsp;<b>Back</b></a></center>'; 

  
?>   