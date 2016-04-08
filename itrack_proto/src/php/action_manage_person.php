<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 	
	$DEBUG = 0;
	$action_type1 = $_POST['action_type'];
	$local_account_ids = $_POST['local_account_ids'];
	$local_account_ids=explode(",",$local_account_ids);
	$account_size=sizeof($local_account_ids); 
  				
  
	$old_value= Array();	$new_value=Array();		$field_name=Array(); 	$table_name="person"; 
  
	include_once('track_log.php');
  
	if($action_type1=="add") 
	{     
		$person_name1=trim($_POST['person_name']);
		$person_address1=trim($_POST['person_address']);		
		$person_mobile_no1=trim($_POST['person_mobile_no']);
		$person_imei_no1=trim($_POST['person_imei_no']); 
				
		//$route_coord1=base64_encode(trim($_POST['route_coord']));

		$query2 ="select Max(sno)+1 as seiral_no from person";  ///// for auto increament of person_id ///////////   
		$result2=mysql_query($query2,$DbConnection);
		$row1=mysql_fetch_object($result2);
		$max_no= $row1->seiral_no;
		if($max_no==""){$max_no=1;}
		
		$query_string1="INSERT INTO person(user_account_id,person_id,person_name,address,mobile_no,imei_no,status,create_id,create_date) VALUES";

		for($i=0;$i<$account_size;$i++)
		{
			//echo "accout_id=".$local_account_ids[$i]."<br>";
			
			if($i==$account_size-1)
			{
				$query_string2.="('$local_account_ids[$i]','$max_no','$person_name1','$person_address1','$person_mobile_no1','$person_imei_no1','1','$account_id','$date');";
			}
			else
			{
				$query_string2.="('$local_account_ids[$i]','$max_no','$person_name1','$person_address1','$person_mobile_no1','$person_imei_no1','1','$account_id','$date'),";
			}
		}
		$query=$query_string1.$query_string2; 
		//echo $query;
		if($DEBUG ==1 )print_query($query);     
		$result=mysql_query($query,$DbConnection);          	  
		if($result){$flag=1;$action_perform="Added";	
    //$result_status="success";
    } 
		   
	}
  
	else if($action_type1=="edit")
	{
		$person_id1 = $_POST['person_id'];    
		
    /*$route_name1 =trim($_POST['route_name']);
		$new_value[]=$route_name1;
	
		$route_coord1 =base64_encode(trim($_POST['route_coord']));     
		$new_value[]=$route_coord1;
    */
     
    
    $person_name1=trim($_POST['person_name']);
    $new_value[]=$person_name1;
		$person_address1=trim($_POST['person_address']);
		$new_value[]=$person_address1;
		$person_mobile_no1=trim($_POST['person_mobile_no']);
		$new_value[]=$person_mobile_no1;
		$person_imei_no1=trim($_POST['person_imei_no']);
		$new_value[]=$person_imei_no1;
		          

		$query="SELECT * FROM person where person_id='$person_id1' AND status='1'";
		if($DEBUG ==1 )
		print_query($query);
		$result=mysql_query($query,$DbConnection);
		$row=mysql_fetch_object($result);
		$person_name2=$row->person_name;
		$old_value[] =$person_name2;
		$field_name[]="person_name";
		$address2=$row1->address;         
		$old_value[] = $address2;
		$field_name[]="address";
    $mobile_no2=$row->mobile_no;
		$old_value[] =$mobile_no2;
		$field_name[]="mobile_no";
		$imei_no2=$row1->imei_no;         
		$old_value[] = $imei_no2;
		$field_name[]="imei_no";
    		   
        
		$query="UPDATE person SET person_name='$person_name1',address='$person_address1',mobile_no='$person_mobile_no1'".
    ",imei_no='$person_imei_no1',edit_id='$account_id',edit_date='$date' WHERE person_id='$person_id1'";
    
		if($DEBUG ==1 )
		print_query($query);
		$result=mysql_query($query,$DbConnection); 

		$ret_result=track_table($person_id1,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
		if($ret_result=="success" && $result){$flag=1;$action_perform="Updated";} 
	    
	} 
  
	else if ($action_type1=="delete")
	{
		$person_id1 = $_POST['person_id'];    
		$query="UPDATE person SET edit_id='$account_id',edit_date='$date',status='0' WHERE person_id='$person_id1' AND status='1'"; 
		$result=mysql_query($query,$DbConnection);    
		$old_value[]="1";
		$new_value[]="0";
		$field_name[]="status";     
		$ret_result=track_table($person_id1,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);    
		if($ret_result=="success"  && $result){$flag=1;$action_perform="Deleted";}
	}
	else if($action_type1=="assign")
	{
		$local_vehicle_ids = $_POST['vehicle_ids'];		
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
	
		if($DEBUG ==1 )print_query($query);     
		$result=mysql_query($query,$DbConnection);          	  
		if($result){$flag=1;$action_perform="Assigned";} 		
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
		$query="select distinct section from student where school_id='$local_account_id' and `class`='$local_classname' and status='1'";
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
    $local_busstop_id = $_POST['busstop_id'];
    $local_shift_id = $_POST['shift_id'];
    $local_pick_drop = $_POST['pick_drop'];
     echo "get_busroute##".$local_pick_drop."##";
     
     //echo $local_account_id .":" ;
     //echo $local_busroute_id;
    $result_string="";
    $i=0;
    $msg="";
    $query="SELECT distinct busroute.busroute_id,busroute.busroute_name from busstop_assignment,busroute where busstop_assignment.busroute_id= busroute.busroute_id AND busstop_assignment.busstop_id='$local_busstop_id' AND busstop_assignment.status='1' AND busroute.status='1' AND busroute.user_account_id='$local_account_id'";
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
    $query="SELECT distinct vehicle.vehicle_id,vehicle.vehicle_name from bus_assignment,vehicle where bus_assignment.bus_serial= vehicle.vehicle_id AND bus_assignment.shift_id='$local_shift_id' AND bus_assignment.busroute_id='$local_busroute_id' AND bus_assignment.status='1' AND vehicle.status='1' AND bus_assignment.school_id='$local_account_id'";
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
		$msg = "Person ".$action_perform." Successfully";	
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
	
	if($result_status=="success")
	{
		unset($root);
		unset($final_group_array);
		include_once("get_group.php");
		include_once('tree_hierarchy.php');
		$group_cnt=0;		
		$final_group_array=GetGroup_3($root,$DbConnection);
		$_SESSION['final_group_array'] = $final_group_array; 
	}
 //echo $query;
 if($flag!=2){
   if($action_type1=="parent_edit"){
   echo "<center><br><br><FONT color=\"".$msg_color."\" size=\"2\">".$msg."<br><br></strong></font></center>";
    echo'<center><a href="javascript:show_option(\'setting\',\'person\');" class="back_css">&nbsp;<b>Back</b></a></center>';
   }
   else{
   echo "<center><br><br><FONT color=\"".$msg_color."\" size=\"2\">".$msg."<br><br></strong></font></center>";
    echo'<center><a href="javascript:show_option(\'manage\',\'person\');" class="back_css">&nbsp;<b>Back</b></a></center>';
    } 
 }
  
?>   