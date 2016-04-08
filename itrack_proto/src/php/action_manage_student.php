<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 	
	$DEBUG = 0;
	$action_type1 = $_POST['action_type'];
	$local_account_ids = $_POST['local_account_ids'];
	$local_account_ids=explode(",",$local_account_ids);
	$account_size=sizeof($local_account_ids); 
  				
  
	$old_value= Array();	$new_value=Array();		$field_name=Array(); 	$table_name="student"; 
  
	include_once('track_log.php');
  
	if($action_type1=="add") 
	{     
		$student_name1=trim($_POST['student_name']);
		$student_address1=trim($_POST['student_address']);
		$student_father_name1=trim($_POST['student_father_name']);
		$student_mother_name1=trim($_POST['student_mother_name']);
		$student_roll_no1=trim($_POST['student_roll_no']);
		$student_class1=trim($_POST['student_class']);
		$student_section1=trim($_POST['student_section']);
		$student_student_mobile_no1=trim($_POST['student_student_mobile_no']);
		$student_parent_mobile_no1=trim($_POST['student_parent_mobile_no']); 
		$studentcard_id1=trim($_POST['studentcard_id']);
		$bus_id_pick1=trim($_POST['bus_id_pick']);
		$bus_id_drop1=trim($_POST['bus_id_drop']);
		$busroute_id_pick1=trim($_POST['busroute_id_pick']);
		$busroute_id_drop1=trim($_POST['busroute_id_drop']);
		$shift_id_pick1=trim($_POST['shift_id_pick']);
		$shift_id_drop1=trim($_POST['shift_id_drop']);
		$busstop_id_pick1=trim($_POST['busstop_id_pick']);
		$busstop_id_drop1=trim($_POST['busstop_id_drop']); 
		
		if($studentcard_id1=="select"){
		    $studentcard_id1="0";}
		if($bus_id_pick1=="select"){
		    $bus_id_pick1="0";}
		if($bus_id_drop1=="select"){
		    $bus_id_drop1="0";}
		if($busroute_id_pick1=="select"){
		    $busroute_id_pick1="0";}
		if($busroute_id_drop1=="select"){
		    $busroute_id_drop1="0";}
		if($shift_id_pick1=="select"){
		    $shift_id_pick1="0";}
		if($shift_id_drop1=="select"){
		    $shift_id_drop1="0";}
		if($busstop_id_pick1=="select"){
		    $busstop_id_pick1="0";}
		if($busstop_id_drop1=="select"){
		    $busstop_id_drop1="0";}
		  
		
										    
		//$route_coord1=base64_encode(trim($_POST['route_coord']));

		$query2 ="select Max(sno)+1 as seiral_no from student";  ///// for auto increament of student_id ///////////   
		$result2=mysql_query($query2,$DbConnection);
		$row1=mysql_fetch_object($result2);
		$max_no= $row1->seiral_no;
		if($max_no==""){$max_no=1;}
		
		if($studentcard_id1 !="0")
		{
			$query_alert="INSERT INTO alert_write_status(studentcard_id,datetime_written,alert_type,status) VALUES('$studentcard_id1','$date','before','0'),('$studentcard_id1','$date','arrived','0'),('$studentcard_id1','$date','student','0')";
			
			if($DEBUG ==1 )print_query($query_alert);     
			$result1=mysql_query($query_alert,$DbConnection);          	  
			if($result1){$flag=1;$action_perform="Added";}
		}
		//------------ for entry to student table
		$query_string1="INSERT INTO student(student_id,student_name,address,father_name,mother_name,roll_no,class,section,student_mobile_no,parent_mobile_no,studentcard_id,bus_id_pick,bus_id_drop,route_id_pick,route_id_drop,shift_id_pick,shift_id_drop,busstop_id_pick,busstop_id_drop,status,create_id,create_date) VALUES('$max_no','$student_name1','$student_address1','$student_father_name1','$student_mother_name1','$student_roll_no1','$student_class1','$student_section1','$student_student_mobile_no1','$student_parent_mobile_no1','$studentcard_id1','$bus_id_pick1','$bus_id_drop1','$busroute_id_pick1','$busroute_id_drop1','$shift_id_pick1','$shift_id_drop1','$busstop_id_pick1','$busstop_id_drop1','1','$account_id','$date');";
		
		if($DEBUG ==1 )print_query($query_string1);     
		$result=mysql_query($query_string1,$DbConnection);          	  
		if($result){$flag=1;$action_perform="Added";}
		
		//------------ for entry to student_grouping table
		
		$query_string1="INSERT INTO student_grouping(student_id,account_id,status,create_id,create_date) VALUES";

		for($i=0;$i<$account_size;$i++)
		{
			//echo "accout_id=".$local_account_ids[$i]."<br>";
			
			if($i==$account_size-1)
			{
				$query_string2.="('$max_no','$local_account_ids[$i]','1','$account_id','$date');";
			}
			else
			{
				$query_string2.="('$max_no','$local_account_ids[$i]','1','$account_id','$date'),";
			}
		}
		$query=$query_string1.$query_string2;
		
		
		if($DEBUG ==1 )print_query($query);     
		$result=mysql_query($query,$DbConnection);          	  
		if($result){$flag=1;$action_perform="Added";}

		
		   
	}
  
	else if($action_type1=="edit")
	{
		$student_id1 = $_POST['student_id'];    
		
    /*$route_name1 =trim($_POST['route_name']);
		$new_value[]=$route_name1;
	
		$route_coord1 =base64_encode(trim($_POST['route_coord']));     
		$new_value[]=$route_coord1;
    */
    
    $student_name1=trim($_POST['student_name']);
    $new_value[]=$student_name1;
		$student_address1=trim($_POST['student_address']);
		$new_value[]=$student_address1;
		$student_father_name1=trim($_POST['student_father_name']);
		$new_value[]=$student_father_name1;
		$student_mother_name1=trim($_POST['student_mother_name']);
		$new_value[]=$student_mother_name1;
		$student_roll_no1=trim($_POST['student_roll_no']);
		$new_value[]=$student_roll_no1;
		$student_class1=trim($_POST['student_class']);
		$new_value[]=$student_class1;
		$student_section1=trim($_POST['student_section']);
		$new_value[]=$student_section1;
		$student_student_mobile_no1=trim($_POST['student_student_mobile_no']);
		$new_value[]=$student_student_mobile_no1;
		$student_parent_mobile_no1=trim($_POST['student_parent_mobile_no']);
		$new_value[]=$student_parent_mobile_no1; 
		$studentcard_id1=trim($_POST['studentcard_id']);
		$bus_id_pick1=trim($_POST['bus_id_pick']);
		$bus_id_drop1=trim($_POST['bus_id_drop']);
		$busroute_id_pick1=trim($_POST['busroute_id_pick']);
		$busroute_id_drop1=trim($_POST['busroute_id_drop']);
		$shift_id_pick1=trim($_POST['shift_id_pick']);
		$shift_id_drop1=trim($_POST['shift_id_drop']);
		$busstop_id_pick1=trim($_POST['busstop_id_pick']);
		$busstop_id_drop1=trim($_POST['busstop_id_drop']); 
		
		if($studentcard_id1=="select"){
		    $studentcard_id1="0";}
		if($bus_id_pick1=="select"){
		    $bus_id_pick1="0";}
		if($bus_id_drop1=="select"){
		    $bus_id_drop1="0";}
		if($busroute_id_pick1=="select"){
		    $busroute_id_pick1="0";}
		if($busroute_id_drop1=="select"){
		    $busroute_id_drop1="0";}
		if($shift_id_pick1=="select"){
		    $shift_id_pick1="0";}
		if($shift_id_drop1=="select"){
		    $shift_id_drop1="0";}
		if($busstop_id_pick1=="select"){
		    $busstop_id_pick1="0";}
		if($busstop_id_drop1=="select"){
		    $busstop_id_drop1="0";} 
        
    $new_value[]=$studentcard_id1;
    $new_value[]=$bus_id_pick1;
    $new_value[]=$bus_id_drop1;
    $new_value[]=$busroute_id_pick1;
    $new_value[]=$busroute_id_drop1;
    $new_value[]=$shift_id_pick1;
    $new_value[]=$shift_id_drop1;
    $new_value[]=$busstop_id_pick1;
    $new_value[]=$busstop_id_drop1;           

		$query="SELECT * FROM student where student_id='$student_id1' AND status='1'";
		if($DEBUG ==1 )
		print_query($query);
		$result=mysql_query($query,$DbConnection);
		$row=mysql_fetch_object($result);
		$student_name2=$row->student_name;
		$old_value[] =$student_name2;
		$field_name[]="student_name";
		$address2=$row1->address;         
		$old_value[] = $address2;
		$field_name[]="address";
		$father_name2=$row->father_name;
		$old_value[] =$father_name2;
		$field_name[]="father_name";
		$mother_name2=$row1->mother_name;         
		$old_value[] = $mother_name2;
		$field_name[]="mother_name";
		$roll_no2=$row->roll_no;
		$old_value[] =$roll_no2;
		$field_name[]="roll_no";
		$class2=$row1->class;         
		$old_value[] = $class2;
		$field_name[]="class";
		$section2=$row->section;
		$old_value[] =$section2;
		$field_name[]="section";
		$student_mobile_no2=$row1->student_mobile_no;         
		$old_value[] = $student_mobile_no2;
		$field_name[]="student_mobile_no";
		$parent_mobile_no2=$row->parent_mobile_no;
		$old_value[] =$parent_mobile_no2;
		$field_name[]="parent_mobile_no";
		/*$school_id2=$row1->school_id;         
		$old_value[] = $school_id2;
		$field_name[]="school_id";*/
		$studentcard_id2=$row->studentcard_id;
		$old_value[] =$studentcard_id2;
		$field_name[]="studentcard_id";
		$bus_id_pick2=$row1->bus_id_pick;         
		$old_value[] = $bus_id_pick2;
		$field_name[]="bus_id_pick";
		$bus_id_drop2=$row->bus_id_drop;
		$old_value[] =$bus_id_drop2;
		$field_name[]="bus_id_drop";
		$route_id_pick2=$row1->route_id_pick;         
		$old_value[] = $route_id_pick2;
		$field_name[]="route_id_pick";
		$route_id_drop2=$row->route_id_drop;
		$old_value[] =$route_id_drop2;
		$field_name[]="route_id_drop";
		$shift_id_pick2=$row1->shift_id_pick;         
		$old_value[] = $shift_id_pick2;
		$field_name[]="shift_id_pick";
		$shift_id_drop2=$row->shift_id_drop;
		$old_value[] =$shift_id_drop2;
		$field_name[]="shift_id_drop";
		$busstop_id_pick2=$row1->busstop_id_pick;         
		$old_value[] = $busstop_id_pick2;
		$field_name[]="busstop_id_pick";
		$busstop_id_drop2=$row->busstop_id_drop;
		$old_value[] =$busstop_id_drop2;
		$field_name[]="busstop_id_drop";
		   
        if($studentcard_id1 !="0")
		{
			$query="SELECT * FROM alert_write_status where studentcard_id='$studentcard_id1'";
			if($DEBUG ==1 )
			print_query($query);
			$result_1=mysql_query($query,$DbConnection);
			$row_result=mysql_num_rows($result_1);		
				if($row_result==null)
				{
				$query_alert="INSERT INTO alert_write_status(studentcard_id,datetime_written,alert_type,status) VALUES('$studentcard_id1','$date','before','0'),('$studentcard_id1','$date','arrived','0'),('$studentcard_id1','$date','student','0')";
				if($DEBUG ==1 )print_query($query_alert);     
				$result1=mysql_query($query_alert,$DbConnection);          	  
				if($result1){$flag=1;$action_perform="Added";}
				}
			
		}
		
		$query="UPDATE student SET student_name='$student_name1',address='$student_address1',father_name='$student_father_name1'".
		",mother_name='$student_mother_name1',roll_no='$student_roll_no1',class='$student_class1',section='$student_section1',".
		"student_mobile_no='$student_student_mobile_no1',parent_mobile_no='$student_parent_mobile_no1',studentcard_id='$studentcard_id1',".
		"bus_id_pick='$bus_id_pick1',bus_id_drop='$bus_id_drop1',route_id_pick='$busroute_id_pick1',route_id_drop='$busroute_id_drop1',".
		"shift_id_pick='$shift_id_pick1',shift_id_drop='$shift_id_drop1',busstop_id_pick='$busstop_id_pick1',busstop_id_drop='$busstop_id_drop1',".
		"edit_id='$account_id',edit_date='$date' WHERE student_id='$student_id1'";
    
		if($DEBUG ==1 )
		print_query($query);
		$result=mysql_query($query,$DbConnection); 

		$ret_result=track_table($student_id1,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
		if($ret_result=="success" && $result){$flag=1;$action_perform="Updated";} 
	    
	} 
	
	else if($action_type1=="parent_edit")
	{
		$student_id1 = $_POST['student_id'];    
		         
		$student_address1=trim($_POST['student_address']);
		$new_value[]=$student_address1;		
		$student_student_mobile_no1=trim($_POST['student_student_mobile_no']);
		$new_value[]=$student_student_mobile_no1;
		$student_parent_mobile_no1=trim($_POST['student_parent_mobile_no']);
		$new_value[]=$student_parent_mobile_no1; 
		
              

		$query="SELECT * FROM student where student_id='$student_id1' AND status='1'";
		if($DEBUG ==1 )
		print_query($query);
		$result=mysql_query($query,$DbConnection);
		$row=mysql_fetch_object($result);
		
		$address2=$row1->address;         
		$old_value[] = $address2;
		$field_name[]="address";    
		$student_mobile_no2=$row1->student_mobile_no;         
		$old_value[] = $student_mobile_no2;
		$field_name[]="student_mobile_no";
		$parent_mobile_no2=$row->parent_mobile_no;
		$old_value[] =$parent_mobile_no2;
		$field_name[]="parent_mobile_no";
			   
        
		$query="UPDATE student SET address='$student_address1',student_mobile_no='$student_student_mobile_no1',parent_mobile_no='$student_parent_mobile_no1',".
		"edit_id='$account_id',edit_date='$date' WHERE student_id='$student_id1'";
    
		if($DEBUG ==1 )
		print_query($query);
		$result=mysql_query($query,$DbConnection); 

		$ret_result=track_table($student_id1,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
		if($ret_result=="success" && $result){$flag=1;$action_perform="Updated";} 
	    
	}
  
	else if ($action_type1=="delete")
	{
		$student_id1 = $_POST['student_id'];    
		$query="UPDATE student SET edit_id='$account_id',edit_date='$date',status='0' WHERE student_id='$student_id1' AND status='1'"; 
		echo'query'.$query;
		$result=mysql_query($query,$DbConnection);    
		$old_value[]="1";
		$new_value[]="0";
		$field_name[]="status";     
		$ret_result=track_table($student_id1,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);    
		if($ret_result=="success"  && $result){$flag=1;$action_perform="Deleted";}
	}
	else if($action_type1=="assign")
	{
		$action_perform="Assigned";
		$account_string = $_POST['account_string1'];
		
		$account_string1=explode(":",$account_string);
		$account_size=sizeof($account_string1);		
		$student_ids = $_POST['student_ids1'];
		//echo "account_string=".$account_string."<br>";
		//echo "student_string=".$student_ids."<br>";
		
		for($i=0;$i<($account_size-1);$i++)
		{
			$account_string2=explode(",",$account_string1[$i]);
			$account_id_local=$account_string2[0];			
			$account_status=$account_string2[1];		
			//echo "account_status=".$account_status."<br>"."account_id=".$account_id_local."<br>";
			$query ="SELECT	* FROM student_grouping WHERE student_id='$student_ids' AND account_id='$account_id_local'";
			//echo "query=".$query."<br>";
			$result = mysql_query($query, $DbConnection);
			$numrows = mysql_num_rows($result);
			if($numrows!=0)
			{
				$row=mysql_fetch_object($result);	$status_local=$row->status;
			}
			//echo "numrows=".$numrows."<br>";
			//($num_rows==0 && $account_status==1)  it's found no entry
			// $account_status==0 && $status_local==1) it is for deassignment
			// ($account_status==1 && $status_local==0) it is for assignment 
			
		
			if(($numrows==0 && $account_status==1) || ($account_status==0 && $status_local==1) || ($account_status==1 && $status_local==0) || ($account_status==0 && $numrows>0))
			{
				if($account_status==1)
				{
					if($numrows==0)
					{
						$query = "INSERT INTO student_grouping (student_id,account_id,status,create_id,create_date) VALUES('$student_ids',$account_id_local,1,$account_id,'$date')";
						//echo "query=".$query."<br>";
						$result = mysql_query($query, $DbConnection);
						if($result)
						{$flag=1;$message="Assigned/Deassigned action performed successfully";}
						else
						{$message="Unable to process this request";}
					}
					else
					{
						$query = "UPDATE student_grouping SET status=1 where student_id = $student_ids AND account_id=$account_id_local";
						//echo "query=".$query."<br>";
						$result = mysql_query($query, $DbConnection);
						if($result)
						{$flag=1;$message="Assigned/Deassigned action performed successfully";}
						else
						{$message="Unable to process this request";}
					}
				}
				else if($account_status==0)
				{
				if($numrows==1)
					{
						$query = "UPDATE student_grouping SET status=0 where student_id = $student_ids AND account_id=$account_id_local";
						//echo "query=".$query."<br>";
						$result = mysql_query($query, $DbConnection);
						if($result)
						{$flag=1;$message="Assigned/Deassigned action performed successfully";}
						else
						{$message="Unable to process this request";}
					}
				}
			}
		}// for loop closed
				
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
	
	else if($action_type1=="get_section")
	{
	  $local_account_id = $_POST['account_id'];    
    $local_classname = $_POST['classname'];    
     echo "get_section##";
     
     //echo $local_account_id .":" ;
     //echo $local_busroute_id;
    $result_string="";
    $i=0;
    $msg="";
    //$query="SELECT distinct busroute.busroute_id,busroute.busroute_name from busstop_assignment,busroute where busstop_assignment.busroute_id= busroute.busroute_id AND busstop_assignment.busstop_id='$local_busstop_id' AND busstop_assignment.status='1' AND busroute.status='1' AND busroute.user_account_id='$local_account_id'";
		$query="select distinct section from student where student_id IN(select student_id from student_grouping where account_id='$local_account_id' and status='1') and `class`='$local_classname' and status='1'";
    			//echo "query=".$query."<br>";
					$result=mysql_query($query,$DbConnection);
					$row_result=mysql_num_rows($result);		
					if($row_result!=null)
					{
						while($row=mysql_fetch_object($result))
						{									
							$section=$row->section;
							
							if($i==0){
							     $msg=$msg.$section.":".$section;
              }
              else{
                  $msg=$msg.",".$section.":".$section;
              }
              $i++;
              
					   }    
	        }
	    $flag=2;
	}
	else if($action_type1=="get_busroute")
	{
	  $local_account_id = $_POST['account_id'];
	$local_group_id = $_POST['group_id'];
	$local_selected_ac_id = $_POST['selected_ac_id'];
    $local_busstop_id = $_POST['busstop_id'];
    $local_shift_id = $_POST['shift_id'];
    $local_pick_drop = $_POST['pick_drop'];
     echo "get_busroute##".$local_pick_drop."##";
     
     //echo $local_account_id .":" ;
     //echo $local_busroute_id;
    $result_string="";
    $i=0;
    $msg="";
    $query="SELECT distinct busroute.busroute_id,busroute.busroute_name from busstop_assignment,busroute where busstop_assignment.busroute_id= busroute.busroute_id AND busstop_assignment.busstop_id='$local_busstop_id' AND busstop_assignment.status='1' AND busroute.status='1' AND busroute.group_id='$local_group_id'";
					//echo "query=".$query."<br>";
					$result=mysql_query($query,$DbConnection);
					$row_result=mysql_num_rows($result);		
					if($row_result!=null)
					{
						while($row=mysql_fetch_object($result))
						{									
							$busroute_id=$row->busroute_id;
							$busroute_name=$row->busroute_name;							
							
							if($i==0){
							     $msg=$msg.$busroute_id.":".$busroute_name;
              }
              else{
                  $msg=$msg.",".$busroute_id.":".$busroute_name;
              }
              $i++;
              
					   }    
	        }
	    $flag=2;
	}
	
 else if($action_type1=="get_bus")
	{
	
	$local_account_id = $_POST['account_id'];
	$local_group_id = $_POST['group_id'];
	$local_selected_ac_id = $_POST['selected_ac_id'];
    $local_busstop_id = $_POST['busstop_id'];
    $local_shift_id = $_POST['shift_id'];
    $local_busroute_id = $_POST['busroute_id'];
    $local_pick_drop = $_POST['pick_drop'];
    echo "get_bus##".$local_pick_drop."##";
     
     //echo $local_account_id .":" ;
     //echo $local_busroute_id;
    $result_string="";
    $i=0;
    $msg="";
    $query="SELECT distinct vehicle.vehicle_id,vehicle.vehicle_name from bus_assignment,vehicle where bus_assignment.bus_serial= vehicle.vehicle_id AND bus_assignment.shift_id='$local_shift_id' AND bus_assignment.busroute_id='$local_busroute_id' AND bus_assignment.status='1' AND vehicle.status='1' AND bus_assignment.school_id='$local_selected_ac_id'";
					//echo "query=".$query."<br>";
					$result=mysql_query($query,$DbConnection);
					$row_result=mysql_num_rows($result);		
					if($row_result!=null)
					{
						while($row=mysql_fetch_object($result))
						{									
							$vehicle_id=$row->vehicle_id;
							$vehicle_name=$row->vehicle_name;							
							
							if($i==0){
							     $msg=$msg.$vehicle_id.":".$vehicle_name;
              }
              else{
                  $msg=$msg.",".$vehicle_id.":".$vehicle_name;
              }
              $i++;
              
					   }    
	        }
	    $flag=2;
	}
  
  else if($action_type1=="change")
	{
		$mobile_number1 = $_POST['mobile_number']; 
		
	}
   
	if($flag==1)
	{
		$msg = "Student ".$action_perform." Successfully";
		$msg_color = "green";				
	}
  else if($flag==2)
	{
		echo $msg;			
	}		
	else
	{
		$msg = "Sorry! Unable to process request.";
		$msg_color = "red";		
	} 
 //echo $query;
 if($flag!=2){
   if($action_type1=="parent_edit"){
   echo "<center><br><br><FONT color=\"".$msg_color."\" size=\"2\">".$msg."<br><br></strong></font></center>";
    echo'<center><a href="javascript:show_option(\'setting\',\'student\');" class="back_css">&nbsp;<b>Back</b></a></center>';
   }
   else{
   echo "<center><br><br><FONT color=\"".$msg_color."\" size=\"2\">".$msg."<br><br></strong></font></center>";
    echo'<center><a href="javascript:show_option(\'manage\',\'student\');" class="back_css">&nbsp;<b>Back</b></a></center>';
    } 
 }
  
?>   