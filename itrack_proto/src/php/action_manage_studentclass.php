<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 	
	$DEBUG = 0;
	$action_type1 = $_POST['action_type'];
	$local_account_ids = $_POST['local_account_ids'];
	$local_account_ids=explode(",",$local_account_ids);
	$account_size=sizeof($local_account_ids);  
  
	$old_value= Array();	$new_value=Array();		$field_name=Array(); 	$table_name="studentclass"; 
  
	include_once('track_log.php');
  
	if($action_type1=="add") 
	{     
		$studentclass_name1=trim($_POST['studentclass_name']);
		$studentclass_section1=trim($_POST['studentclass_section']);
    $studentclass_lat1=trim($_POST['studentclass_lat']);
    $studentclass_lng1=trim($_POST['studentclass_lng']);		    
		//$route_coord1=base64_encode(trim($_POST['route_coord']));

		$query2 ="select Max(sno)+1 as seiral_no from studentclass";  ///// for auto increament of school_id ///////////   
		$result2=mysql_query($query2,$DbConnection);
		$row1=mysql_fetch_object($result2);
		$max_no= $row1->seiral_no;
		if($max_no==""){$max_no=1;}
		
		$query_string1="INSERT INTO studentclass(user_account_id,studentclass_id,studentclass_name,studentclass_section,class_lat,class_lng,status,create_id,create_date,group_id) VALUES";
     
		for($i=0;$i<$account_size;$i++)
		{
			//echo "accout_id=".$local_account_ids[$i]."<br>";
			if($i==$account_size-1)
			{
				$query_string2.="('$local_account_ids[$i]','$max_no','$studentclass_name1','$studentclass_section1','$studentclass_lat1','$studentclass_lng1','1','$account_id','$date','$group_id');";
			}
			else
			{
				$query_string2.="('$local_account_ids[$i]','$max_no','$studentclass_name1','$studentclass_section1','$studentclass_lat1','$studentclass_lng1','1','$account_id','$date','$group_id'),";
			}
		}
		$query=$query_string1.$query_string2; 
		//echo $query;
		if($DEBUG ==1 )print_query($query);     
		$result=mysql_query($query,$DbConnection);          	  
		if($result){$flag=1;$action_perform="Added";} 
		   
	}
  //to be updated
	else if($action_type1=="edit")
	{
		$studentclass_id1 = $_POST['studentclass_id'];    
		$edit_studentclass_name1 =$_POST['edit_studentclass_name'];
		$new_value[]=$edit_studentclass_name1;
		$edit_studentclass_section1 = $_POST['edit_studentclass_section'];
		$new_value[]=$edit_studentclass_section1;
    $edit_class_lat1 =$_POST['edit_class_lat'];
    $new_value[]=$edit_class_lat1;
    $edit_class_lng1 =$_POST['edit_class_lng'];
		$new_value[]=$edit_class_lng1;
		
		  

		$query="SELECT * FROM studentclass where studentclass_id='$studentclass_id1' AND status='1'";
		if($DEBUG ==1 )
		print_query($query);
		$result=mysql_query($query,$DbConnection);
		$row=mysql_fetch_object($result);
		$studentclass_name2=$row->studentclass_name;
		$old_value[] =$studentclass_name2;
		$field_name[]="studentclass_name";
		$studentclass_section2=$row->studentclass_section;
		$old_value[] =$studentclass_section2;
		$field_name[]="studentclass_section";
		$class_lat2 =$row->class_lat ;
		$old_value[] =$class_lat2;
		$field_name[]="class_lat";
		$class_lng2 =$row->class_lng ;
		$old_value[] =$class_lng2;
		$field_name[]="class_lng";
		
		
		
		$query="UPDATE studentclass SET studentclass_name='$edit_studentclass_name1',edit_id='$account_id',edit_date='$date',studentclass_section ='$edit_studentclass_section1',class_lat ='$edit_class_lat1',class_lng='$edit_class_lng1'  WHERE studentclass_id='$studentclass_id1'";
		if($DEBUG ==1 )
		print_query($query);
		$result=mysql_query($query,$DbConnection); 

		$ret_result=track_table($studentclass_id1,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
		if($ret_result=="success" && $result){$flag=1;$action_perform="Updated";} 
	    
	}
  
	else if ($action_type1=="delete")
	{
		$studentclass_id1 = $_POST['studentclass_id'];  
    //==checking if any student is enroll in the class===//
    $query1="select COUNT(*) as cnt from student where class='$studentclass_id1' and status='1'";
    $result1=mysql_query($query1,$DbConnection);
		$row1=mysql_fetch_object($result1);
		$totalstudent=$row1->cnt;
		if($totalstudent >= 1 )
		{$flag=4;$action_perform="Deleted";}
		else
		{
   		$query="UPDATE studentclass SET edit_id='$account_id',edit_date='$date',status='0' WHERE studentclass_id='$studentclass_id1' AND status='1'"; 
  		$result=mysql_query($query,$DbConnection);    
  		$old_value[]="1";
  		$new_value[]="0";
  		$field_name[]="status";     
  		$ret_result=track_table($studentcard_id1,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);    
  		if($ret_result=="success"  && $result){$flag=1;$action_perform="Deleted";}
		}
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
		$msg = "Student Class ".$action_perform." Successfully";
		$msg_color = "green";				
	}	
  else if($flag==4)
	{
    $msg = "Sorry! Unable to process request beacuse there are ".$totalstudent." student in this class..!";
		$msg_color = "red";	
  } 
	else
	{
		$msg = "Sorry! Unable to process request.";
		$msg_color = "red";		
	} 

 echo "<center><br><br><FONT color=\"".$msg_color."\" size=\"2\">".$msg."<br><br></strong></font></center>";
  echo'<center><a href="javascript:show_option(\'manage\',\'studentclass\');" class="back_css">&nbsp;<b>Back</b></a></center>'; 

  
?>   