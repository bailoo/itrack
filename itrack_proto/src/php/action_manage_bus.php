<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 	
	$DEBUG = 0;
	$action_type1 = $_POST['action_type'];
	$local_account_ids = $_POST['local_account_ids'];
	$local_account_ids=explode(",",$local_account_ids);
	$account_size=sizeof($local_account_ids);  
  
	$old_value= Array();	$new_value=Array();		$field_name=Array(); 	$table_name="busstop"; 
  
	include_once('track_log.php');
  
	if($action_type1=="add") 
	{     
		$busstop_name1=trim($_POST['busstop_name']);
		    
		//$route_coord1=base64_encode(trim($_POST['route_coord']));

		$query2 ="select Max(sno)+1 as seiral_no from busstop";  ///// for auto increament of school_id ///////////   
		$result2=mysql_query($query2,$DbConnection);
		$row1=mysql_fetch_object($result2);
		$max_no= $row1->seiral_no;
		if($max_no==""){$max_no=1;}
		
		$query_string1="INSERT INTO busstop(user_account_id,busstop_id,busstop_name,status,create_id,create_date) VALUES";

		for($i=0;$i<$account_size;$i++)
		{
			//echo "accout_id=".$local_account_ids[$i]."<br>";
			if($i==$account_size-1)
			{
				$query_string2.="('$local_account_ids[$i]','$max_no','$busstop_name1','1','$account_id','$date');";
			}
			else
			{
				$query_string2.="('$local_account_ids[$i]','$max_no','$busstop_name1','1','$account_id','$date'),";
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
		$route_id1 = $_POST['route_id'];    
		$route_name1 =trim($_POST['route_name']);
		$new_value[]=$route_name1;
	
		$route_coord1 =base64_encode(trim($_POST['route_coord']));     
		$new_value[]=$route_coord1;            

		$query="SELECT * FROM route where route_id='$route_id1' AND status='1'";
		if($DEBUG ==1 )
		print_query($query);
		$result=mysql_query($query,$DbConnection);
		$row=mysql_fetch_object($result);
		$route_name2=$row->route_name;
		$old_value[] =$route_name2;
		$field_name[]="route_name";
		$route_coord2=$row1->route_coord;         
		$old_value[] = $route_coord2;
		$field_name[]="route_coord"; 

		$query="UPDATE route SET route_name='$route_name1',route_coord='$route_coord1',edit_id='$account_id',edit_date='$date' WHERE route_id='$route_id1'";
		if($DEBUG ==1 )
		print_query($query);
		$result=mysql_query($query,$DbConnection); 

		$ret_result=track_table($route_id1,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
		if($ret_result=="success" && $result){$flag=1;$action_perform="Updated";} 
	    
	}
  
	else if ($action_type1=="delete")
	{
		$route_id1 = $_POST['route_id'];    
		$query="UPDATE route SET edit_id='$account_id',edit_date='$date',status='0' WHERE route_id='$route_id1' AND status='1'"; 
		$result=mysql_query($query,$DbConnection);    
		$old_value[]="1";
		$new_value[]="0";
		$field_name[]="status";     
		$ret_result=track_table($route_id1,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);    
		if($ret_result=="success"  && $result){$flag=1;$action_perform="Deleted";}
	}
	else if($action_type1=="get_shift")
	{
	  $local_account_id = $_POST['account_id'];    
    $local_bus_id = $_POST['bus_id'];
    $local_pick_drop = $_POST['pick_drop'];
     echo "get_shift##".$local_pick_drop."##";
     
     //echo $local_account_id .":" ;
     //echo $local_busroute_id;
	 $group_id="";
	 $query="SELECT group_id from account where account_id='$local_account_id' AND status='1'";
		//echo "query=".$query."<br>";
		$result=mysql_query($query,$DbConnection);
		$row_result=mysql_num_rows($result);		
		if($row_result!=null)
		{
		$row=mysql_fetch_object($result);
		$group_id=$row->group_id;
		}
	 
	 
    $result_string="";
    $i=0;
    $msg="";
    $query="SELECT distinct shift.shift_id,shift.shift_name from shift,bus_assignment where bus_assignment.shift_id= shift.shift_id AND bus_assignment.bus_serial='$local_bus_id' AND bus_assignment.status='1' AND shift.status='1' AND shift.group_id='$group_id'";
					//echo "query=".$query."<br>";
					$result=mysql_query($query,$DbConnection);
					$row_result=mysql_num_rows($result);		
					if($row_result!=null)
					{
						while($row=mysql_fetch_object($result))
						{									
							$shift_id=$row->shift_id;
							$shift_name=$row->shift_name;							
							
							if($i==0){
							     $msg=$msg.$shift_id.":".$shift_name;
              }
              else{
                  $msg=$msg."#".$shift_id.":".$shift_name;
              }
              $i++;
              
					   }    
	        }
	    $flag=3;
	}
	else if($action_type1=="assign")
	{
		/*$local_busstop_ids = $_POST['busstop_ids'];		
		$local_busstop_ids=explode(",",$local_busstop_ids);
		$busstop_size=sizeof($local_busstop_ids);
		*/
    $local_shift_id = $_POST['shift_id'];    
    $local_busroute_id = $_POST['busroute_id'];
    $local_bus_id = $_POST['bus_id'];
    $school_id = $_POST['school_id'];
    $driver_id=$_POST['driver_id'];
		 $j=0;
		
		$query="SELECT busroute_id FROM bus_assignment WHERE school_id='$school_id' AND shift_id='$local_shift_id' and bus_serial='$local_bus_id'  AND status='1'";
		echo $query;
		$result_1=mysql_query($query,$DbConnection);
		$row_result=mysql_num_rows($result_1);								
		if($row_result!=null)
		{
			  $flag=2;
		}
		else{
    		$query_string1="INSERT INTO bus_assignment(bus_serial,driverid,school_id,shift_id,busroute_id,create_id,create_date,status) VALUES";
    		$query_string2.="($local_bus_id,$driver_id,$school_id,$local_shift_id,$local_busroute_id,$account_id,'$date',1);";
    		
    		$query=$query_string1.$query_string2; 
    		//echo "query=".$query;
    	
    		if($DEBUG ==1 )print_query($query);     
    		$result=mysql_query($query,$DbConnection);          	  
    		if($result){$flag=1;$action_perform="Assigned";}     
    }
		      		
	}
	else if($action_type1=="deassign")
	{
		/*$local_vehicle_ids = $_POST['vehicle_ids'];	
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
		 */
		$local_shift_id = $_POST['shift_id'];    
    $local_bus_id = $_POST['bus_id'];
    $school_id = $_POST['school_id'];
     
     $query="UPDATE bus_assignment SET status=0,edit_id='$account_id',edit_date='$date' WHERE bus_serial='$local_bus_id' AND shift_id='$local_shift_id' AND school_id='$school_id' AND status=1";
			$result=mysql_query($query,$DbConnection); 
			$geo_id1= $veh_geo_id2[1];
			$old_value[]= "1";
			$new_value[]="0";
			$field_name[]= "status";         
			$ret_result=track_table($local_bus_id,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);    
    
		if($ret_result=="success" && $result){$flag=1;$action_perform="De-Assigned";} 	
	}
 
	if($flag==1)
	{
		$msg = "Bus ".$action_perform." Successfully";
		$msg_color = "green";				
	}
  else if($flag==2)
	{
		$msg = "Bus Already Assigned to Different Route in Same Shift";
		$msg_color = "red";				
	}
  else if($flag==3)
	{
		echo $msg;			
	}	
	else
	{
		$msg = "Sorry! Unable to process request.";
		$msg_color = "red";		
	} 

  if($flag!=3){
 echo "<center><br><br><FONT color=\"".$msg_color."\" size=\"2\">".$msg."<br><br></strong></font></center>";
  echo'<center><a href="javascript:show_option(\'manage\',\'bus\');" class="back_css">&nbsp;<b>Back</b></a></center>'; 
   }
  
?>   