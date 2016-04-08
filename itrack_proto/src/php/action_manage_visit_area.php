<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$DEBUG=0;
	
	$action_type1 = $_POST['action_type'];
	$local_account_ids = $_POST['local_account_ids'];
	$local_account_ids=explode(",",$local_account_ids);
	$account_size=sizeof($local_account_ids);
	
	$assign_flag = 0;

	$old_value= Array();		$new_value=Array();			$field_name=Array();		$table_name="geofence"; 
  
	if($action_type1=="add") 
	{ 
		$geo_name1 = trim($_POST['visit_area_name']);
		// $type="add";    
		/*$geo_name1 = trim($_POST['geo_name']);     
		$query1="SELECT geo_name FROM geofence WHERE create_id='$account_id' AND geo_name='$geo_name1' AND status='1'";
		if($DEBUG ==1 )print_query($query1);
		$result1=mysql_query($query1,$DbConnection);
		$num_rows1=mysql_num_rows($result1);      
		if($num_rows1>0)*/
		/// {$flag=1;}
		// else
		// { 
		$geo_coord1 = $_POST['visit_area_coord'];
		$geo_coord1=trim($geo_coord1);    
		$geo_coord1 = base64_encode($geo_coord1); 

		$query ="select Max(sno)+1 as serial_no from visit_area";  ///// for auto increament of geo_id ///////////   
		$result=mysql_query($query,$DbConnection);
		$row=mysql_fetch_object($result);
		$max_no= $row->serial_no;
		if($max_no==""){$max_no=1;}

		$query_string1="INSERT INTO visit_area(user_account_id,visit_area_id,visit_area_name,visit_area_coord,status,create_id,create_date) VALUES";

		for($i=0;$i<$account_size;$i++)
		{
			//echo "accout_id=".$local_account_ids[$i]."<br>";
			if($i==$account_size-1)
			{
				$query_string2.="('$local_account_ids[$i]','$max_no','$geo_name1','$geo_coord1','1','$account_id','$date');";
			}
			else
			{
				$query_string2.="('$local_account_ids[$i]','$max_no','$geo_name1','$geo_coord1','1','$account_id','$date'),";
			}
		}
		$query=$query_string1.$query_string2; 
		//echo "query=".$query;
		//}
		if($DEBUG ==1 )print_query($query);     
		$result=mysql_query($query,$DbConnection);          	  
		if($result){$flag=1;$action_perform="Added";}    
	//  }   
	}
  
	else if($action_type1=="edit")
	{
		//$type="edit_delete";
		$geo_id1 = $_POST['visit_area_id'];    
		$geo_name1 =trim($_POST['visit_area_name']);
		$new_value[]=$geo_name1;
		/* $query="SELECT geo_name FROM geofence WHERE create_id='$account_id' AND geo_name='$geo_name1' AND user_account_id='$local_account_ids' AND status='1'";
		if($DEBUG ==1 )print_query($query);
		$result=mysql_query($query,$DbConnection);
		$num_rows=mysql_num_rows($result);      
		if($num_rows1>0)
		{$flag=1;}
		else
		{  */    
		$geo_coord1 =base64_encode(trim($_POST['visit_area_coord']));     
		$new_value[]=$geo_coord1;           

		$query="SELECT * FROM visit_area where visit_area_id='$geo_id1' AND status='1'";
		$result=mysql_query($query,$DbConnection);
		$row=mysql_fetch_object($result);
		$geo_name2=$row->geo_name;
		$old_value[] =$geo_name2;
		$field_name[]="geo_name";
		$geo_coord2=$row->geo_coord;         
		$old_value[] = $geo_coord2;
		$field_name[]="geo_coord"; 

		$query="UPDATE visit_area SET visit_area_name='$geo_name1',visit_area_coord='$geo_coord1',edit_id='$account_id',edit_date='$date' WHERE visit_area_id='$geo_id1'";
		//echo "query=".$query;
		$result=mysql_query($query,$DbConnection); 

		$ret_result=track_table($geo_id1,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
		if($ret_result=="success" && $result){$flag=1;$action_perform="Updated";} 
		//}     
	}
	else if ($action_type1=="delete")
	{
		// $type="edit_delete";
		$geo_id1 = $_POST['visit_area_id'];    
		$query="UPDATE visit_area SET edit_id='$account_id',edit_date='$date',status='0' WHERE visit_area_id='$geo_id1' AND status='1'"; 
		$result=mysql_query($query,$DbConnection);    
		//$old_value[]="1";$new_value[]="0";$field_name[]="status";     
		//$ret_result=track_table($geo_id1,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);    
		//if($ret_result=="success"  && $result){
    $flag=2;$action_perform="Deleted";
    //;}
	}
	else if($action_type1=="assign")
	{
		echo "assign_visit_area##";
		
    $local_vehicle_id = $_POST['vehicle_id'];
    $local_visit_area_ids = $_POST['visit_area_ids'];
		//echo "local_vehicle_ids=".$local_vehicle_ids."<br>";
		$local_visit_area_ids=explode(",",$local_visit_area_ids);
		$visit_area_size=sizeof($local_visit_area_ids);
		$schedule_date1 = $_POST['schedule_date'];
		//echo "vehicle_size=".$vehicle_size."<br>";
				
    $flag_insert = 0;
    for($i=0;$i<$visit_area_size;$i++)
		{
			//echo "local_geofenc_id=".$local_geofenc_id[$i]."<br>";			
      $query_select = "SELECT * FROM visit_area_assignment WHERE visit_area_id ='$local_visit_area_ids[$i]' AND vehicle_id='$local_vehicle_id' AND schedule_date='$schedule_date1'";      
      $result_select = mysql_query($query_select,$DbConnection);
      $numrows = mysql_num_rows($result_select);
      
      if(!$numrows)
      {              
        $query_string1="INSERT INTO visit_area_assignment(visit_area_id,vehicle_id,schedule_date,create_id,create_date,status) VALUES".
                       "($local_visit_area_ids[$i],$local_vehicle_id,'$schedule_date1',$account_id,'$date',1)";
        $result=mysql_query($query_string1,$DbConnection);
        if($DEBUG ==1 )print_query($query_string1); 
        
        //GET VISIT AREA NAME
        $query3 = "SELECT visit_area_name FROM visit_area WHERE visit_area_id='$local_visit_area_ids[$i]'";
        $result3 = mysql_query($query3,$DbConnection);
        $row3 = mysql_fetch_object($result3);
        if($i==0)
          $visit_area_str = $visit_area_str.$row3->visit_area_name;
        else
          $visit_area_str = $visit_area_str."<font color=red>&nbsp;,&nbsp;</font>".$row3->visit_area_name;
        
        $flag_insert = 1;                     
			}
		}
	
    //QUERY ASSIGNED VISIT AREAS

    if($flag_insert)
    {
      $query2 = "SELECT vehicle_name FROM vehicle WHERE vehicle_id='$local_vehicle_id'";
      $result2 = mysql_query($query2,$DbConnection);
      $row2 = mysql_fetch_object($result2);
      $vehicle_name = $row2->vehicle_name;  
      
      $assign_flag = 1;    
      echo "<center><strong><font color=green>ADDED=></font><font color=blue>".$vehicle_name." </font>&nbsp;<font color=red>: ".$schedule_date1." </font>&nbsp;<font color=red>:</font><font color=green>(".$visit_area_str.")</font></strong></center>";    
    } 
    else
    {
      $assign_flag =1;
      echo "<center><font color=red><strong>Failed=>Assignment Already Exists</strong></font></center>";
    }         	  
		//if($result){$flag=1;$action_perform="Assigned";} 		
	}
	else if($action_type1=="deassign")
	{
		$local_vehicle_ids = $_POST['vid_string'];
		//echo "local_vehicle_ids=".$local_vehicle_ids."<br>";
		$local_vehicle_ids=explode(",",$local_vehicle_ids);
		$vehicle_size=sizeof($local_vehicle_ids);	
		
		$old_value= Array();   $new_value=Array();   $field_name=Array(); $table_name="visit_area_assignment";

    for($i=0;$i<$vehicle_size;$i++)
		{	
      $local_all_ids=explode(":",$local_vehicle_ids[$i]);
      
      $visit_area_id = $local_all_ids[0];
      $vehicle_id = $local_all_ids[1];
      $schedult_date = $local_all_ids[2];
              			
      $query="UPDATE visit_area_assignment SET status=0,edit_id='$account_id',edit_date='$date' WHERE vehicle_id='$vehicle_id' AND visit_area_id='$visit_area_id' AND schedule_date='$schedult_date' AND status=1";
			//echo $query;
      $result=mysql_query($query,$DbConnection); 
			//$station_id1= $veh_geo_id2[1];
			//$old_value[]= "1";
			//$new_value[]="0";
			//$field_name[]= "status";         
			//$ret_result=track_table($local_vehicle_ids[$i],$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
		}
		
		//if($ret_result=="success" && $result){
    $flag=1;$action_perform="De-Assigned";
    //} 	
	}
 
	if($flag==1)
	{
		$msg = "Visit Area ".$action_perform." Successfully";
		$msg_color = "green";				
	}
  else if($flag==2)
  {
    $msg = "Visit Area ".$action_perform." Successfully";
		$msg_color = "blue";	
  }	
	else
	{
		if(!$assign_flag)
		{
      $msg = "Sorry! Unable to process request.";
		  $msg_color = "red";
    }		
	}
  
	if(!$assign_flag)
	{
      echo "<center><br><br><FONT color=\"".$msg_color."\" size=\"2\">".$msg."<br><br></strong></font></center>";
      echo'<center><a href="javascript:show_option(\'manage\',\'visit_area\');" class="back_css">&nbsp;<b>Back</b></a></center>';
  }                 
  
?>
        