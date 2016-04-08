<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 	
	$DEBUG = 0;
	$action_type1 = $_POST['action_type'];
	$local_account_ids = $_POST['local_account_ids'];
	$local_account_ids=explode(",",$local_account_ids);
	$account_size=sizeof($local_account_ids); 
  				
  
	$old_value= Array();	$new_value=Array();		$field_name=Array(); 	$table_name="schedule_visit"; 
  
	include_once('track_log.php');
  
	if($action_type1=="add") 
	{     
		$date1=trim($_POST['date1']);
		$person_id1=trim($_POST['person_id']);		
    $route_coord1=base64_encode(trim($_POST['route_coord'])); 
		

		$query2 ="select * from schedule_visit where person_id='$person_id1'  and visit_date='$date1' and status='1'";  // 
    //echo $query2;  
		$result2=mysql_query($query2,$DbConnection);
		$num_rows=mysql_num_rows($result2);
    if(!$num_rows)
		{
    
		    $query_string1="INSERT INTO schedule_visit(user_account_id,visit_date,person_id,location_coord,status,create_id,create_date) VALUES";

    		for($i=0;$i<$account_size;$i++)
    		{
    			//echo "accout_id=".$local_account_ids[$i]."<br>";
    			
    			if($i==$account_size-1)
    			{
    				$query_string2.="('$local_account_ids[$i]','$date1','$person_id1','$route_coord1','1','$account_id','$date');";
    			}
    			else
    			{
    				$query_string2.="('$local_account_ids[$i]','$date1','$person_id1','$route_coord1','1','$account_id','$date'),";
    			}
    		}
  		$query=$query_string1.$query_string2; 
  		//echo $query;
  		if($DEBUG ==1 )print_query($query);     
  		$result=mysql_query($query,$DbConnection);          	  
  		if($result){$flag=1;$action_perform="Added";} 
		 }// if num_rows closed 
		 else{
        $flag=2;
        $msg="Visit Already Scheduled For This Date";
     }
	}
  
	else if($action_type1=="edit")
	{
		
    /*$route_name1 =trim($_POST['route_name']);
		$new_value[]=$route_name1;
	
		$route_coord1 =base64_encode(trim($_POST['route_coord']));     
		$new_value[]=$route_coord1;
    */
       
    $date1=trim($_POST['date1']);
    $new_value[]=$date1;
		$person_id1=trim($_POST['person_id']);
		$new_value[]=$person_id1;
		$route_coord1=base64_encode(trim($_POST['route_coord'])); 
		$new_value[]=$route_coord1;
		
		          

		$query="SELECT * FROM schedule_visit where person_id='$person_id1' and visit_date='$date1' and status='1'";
		if($DEBUG ==1 )
		print_query($query);
		$result=mysql_query($query,$DbConnection);
		$row=mysql_fetch_object($result);
		$visit_date2=$row->visit_date;
		$old_value[] =$visit_date2;
		$field_name[]="visit_date";
		$person_id2=$row1->person_id;         
		$old_value[] = $person_id2;
		$field_name[]="person_id";
    $location_coord2=$row->location_coord;
		$old_value[] =$location_coord2;
		$field_name[]="location_coord";
		    		   
        
		$query="UPDATE schedule_visit SET location_coord='$route_coord1',edit_id='$account_id',edit_date='$date' WHERE person_id='$person_id1' and visit_date='$date1' ";
    
		if($DEBUG ==1 )
		print_query($query);
		$result=mysql_query($query,$DbConnection); 

		$ret_result=track_table($person_id1,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
		if($ret_result=="success" && $result){$flag=1;$action_perform="Updated";} 
	    
	} 
  
	else if ($action_type1=="delete")
	{
		$person_id1 = $_POST['person_id'];
    $date1=trim($_POST['date1']);    
		
    $query="UPDATE schedule_visit SET edit_id='$account_id',edit_date='$date',status='0' WHERE person_id='$person_id1' AND visit_date='$date1' AND status='1'"; 
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
	
	else if($action_type1=="getlocations")
	{
	  $local_account_id = $_POST['account_id'];    
    //$local_classname = $_POST['classname'];
    $date1=trim($_POST['date1']);
		$person_id1=trim($_POST['person_id']);		
    //$route_coord1 ;   
     echo "getlocations##";
   
    $result_string="";
    $i=0;
    $msg="";
    //$query="SELECT distinct busroute.busroute_id,busroute.busroute_name from busstop_assignment,busroute where busstop_assignment.busroute_id= busroute.busroute_id AND busstop_assignment.busstop_id='$local_busstop_id' AND busstop_assignment.status='1' AND busroute.status='1' AND busroute.user_account_id='$local_account_id'";
		$query="select location_coord from schedule_visit where person_id='$person_id1' and visit_date='$date1' and status='1'";
    			//echo "query=".$query."<br>";
					$result=mysql_query($query,$DbConnection);
					$row_result=mysql_num_rows($result);		
					if($row_result!=null)
					{
						while($row=mysql_fetch_object($result))
						{									
							$route_coord1=$row->location_coord;
							$msg=base64_decode($route_coord1);
						/*	if($i==0){
							     $msg=$msg.$section.":".$section;
              }
              else{
                  $msg=$msg.",".$section.":".$section;
              }
              $i++;
              */
					   }    
	        }
	    $flag=3;
	    
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
	
   
	if($flag==1)
	{
		$msg = "Visit ".$action_perform." Successfully";
		$msg_color = "green";				
	}
  else if($flag==2)
	{
		$msg_color = "red";
    echo "<center><br><br><FONT color=\"".$msg_color."\" size=\"2\">".$msg."<br><br></strong></font></center>";
    echo'<center><a href="javascript:show_option(\'manage\',\'visit\');" class="back_css">&nbsp;<b>Back</b></a></center>';			
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
 //echo $query;
 if($flag!=2 && $flag!=3){
   if($action_type1=="parent_edit"){
   echo "<center><br><br><FONT color=\"".$msg_color."\" size=\"2\">".$msg."<br><br></strong></font></center>";
    echo'<center><a href="javascript:show_option(\'setting\',\'person\');" class="back_css">&nbsp;<b>Back</b></a></center>';
   }
   else{
   echo "<center><br><br><FONT color=\"".$msg_color."\" size=\"2\">".$msg."<br><br></strong></font></center>";
    echo'<center><a href="javascript:show_option(\'manage\',\'visit\');" class="back_css">&nbsp;<b>Back</b></a></center>';
    } 
 }
  
?>   