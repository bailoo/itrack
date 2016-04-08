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
		$sector_name1 = trim($_POST['sector_name']);
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
		$sector_coord1 = $_POST['sector_coord'];
		$sector_coord1=trim($sector_coord1);    
		$sector_coord1 = base64_encode($sector_coord1); 

		$query ="select Max(sno)+1 as serial_no from sector";  ///// for auto increament of geo_id ///////////   
		$result=mysql_query($query,$DbConnection);
		$row=mysql_fetch_object($result);
		$max_no= $row->serial_no;
		if($max_no==""){$max_no=1;}

		$query_string1="INSERT INTO sector(user_account_id,sector_id,sector_name,sector_coord,status,create_id,create_date) VALUES";

		for($i=0;$i<$account_size;$i++)
		{
			//echo "accout_id=".$local_account_ids[$i]."<br>";
			if($i==$account_size-1)
			{
				$query_string2.="('$local_account_ids[$i]','$max_no','$sector_name1','$sector_coord1','1','$account_id','$date');";
			}
			else
			{
				$query_string2.="('$local_account_ids[$i]','$max_no','$sector_name1','$sector_coord1','1','$account_id','$date'),";
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
		$sector_id1 = $_POST['sector_id'];    
		$sector_name1 =trim($_POST['sector_name']);
		$new_value[]=$sector_name1;
		/* $query="SELECT geo_name FROM geofence WHERE create_id='$account_id' AND geo_name='$geo_name1' AND user_account_id='$local_account_ids' AND status='1'";
		if($DEBUG ==1 )print_query($query);
		$result=mysql_query($query,$DbConnection);
		$num_rows=mysql_num_rows($result);      
		if($num_rows1>0)
		{$flag=1;}
		else
		{  */    
		$sector_coord1 =base64_encode(trim($_POST['sector_coord']));     
		$new_value[]=$sector_coord1;           

		$query="SELECT * FROM sector where sector_id='$sector_id1' AND status='1'";
		$result=mysql_query($query,$DbConnection);
		$row=mysql_fetch_object($result);
		$sector_name2=$row->sector_name;
		$old_value[] =$sector_name2;
		$field_name[]="sector_name";
		$sector_coord2=$row->sector_coord;         
		$old_value[] = $sector_coord2;
		$field_name[]="sector_coord"; 

		$query="UPDATE sector SET sector_name='$sector_name1',sector_coord='$sector_coord1',edit_id='$account_id',edit_date='$date' WHERE sector_id='$sector_id1'";
		//echo "query=".$query;
		$result=mysql_query($query,$DbConnection); 

		$ret_result=track_table($sector_id1,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
		if($ret_result=="success" && $result){$flag=1;$action_perform="Updated";} 
		//}     
	}	
	else if ($action_type1=="delete")
	{
		// $type="edit_delete";
		$sector_id1 = $_POST['sector_id'];    
		$query="UPDATE sector SET edit_id='$account_id',edit_date='$date',status=0 WHERE sector_id='$sector_id1' AND status='1'"; 
		$result=mysql_query($query,$DbConnection);
        
		$table_name = "sector"; $old_value[]="1";$new_value[]="0";$field_name[]="status";     
		$ret_result=track_table($sector_id1,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);    
		//if($ret_result=="success"  && $result){$flag=1;$action_perform="Deleted";}
		if($result){$flag=1;$action_perform="Deleted";}
	}
	else if($action_type1 == "assign")
	{
    $postPars = array('action_type' , 'local_account_ids', 'alert_ids', 'sector_id', 'duration', 'common_id');    
    
    $file_name="src/php/manage_sector_alert_assignment.php"; ///////for previous page
    $action_type="assign";
		//$local_vehicle_ids=explode(",",$pd->data[vehicle_ids]);

    $local_account_ids1 = $_POST['$local_account_ids'];
     					       
		$escalation_id_str = $_POST['escalation_id'];
    $local_escalation_ids = explode(",",$_POST['escalation_id']);
		$escalation_size_tmp=sizeof($local_escalation_ids);
		
    $local_alert_ids_tmp=explode(",",$_POST['alert_ids']);
		$alert_size_tmp=sizeof($local_alert_ids_tmp);
		$local_sector_id = $_POST['sector_id'];
		
    $local_duration_tmp=explode(",",$_POST['duration']);
		$duration_size_tmp=sizeof($local_duration_tmp);		
		//$local_duration = $pd->data[duration];
				
  	$firststr = 1;
    $alert_id_tmp2 ="";
    $duration_tmp2 ="";
    $msgtmp ="";
    $flag3 =0;
    
    //echo "alert_size_tmp=".$alert_size_tmp."<br>";
    
    for($i=0;$i<$alert_size_tmp;$i++)
  	{
      /*$query ="SELECT alert_id,vehicle_id,escalation_id,alert_duration FROM alert_assignment WHERE alert_id='$local_alert_ids_tmp[$i]' ".
      "AND vehicle_id='$local_vehicle_id' AND escalation_id = '$local_escalation_id' AND alert_duration = '$local_duration_tmp[$i]' ".
      "AND status=1";*/      
      $query ="SELECT alert_id,sector_id,escalation_id,alert_duration FROM sector_alert_assignment WHERE alert_id='$local_alert_ids_tmp[$i]' ".
      "AND sector_id='$local_sector_id' AND escalation_id IN('$escalation_id_str')".
      "AND status=1";      
      //echo $query;  
  		
      $result=mysql_query($query,$DbConnection);
  		
      $row_db = mysql_fetch_object($result);
      $alert_dur_db = $row_db->alert_duration;
      $numrows = mysql_num_rows($result);  	
      
      if($numrows)
      {
        $query1 ="SELECT alert_name FROM alert WHERE alert_id='$local_alert_ids_tmp[$i]'and status=1";
        $query2 ="SELECT person_name FROM escalation WHERE escalation_id='$local_escalation_ids[$k]'and status=1";
        $query3 ="SELECT sector_name FROM sector WHERE sector_id='$local_sector_id'and status=1";
        $result1=mysql_query($query1,$DbConnection);
        $result2=mysql_query($query2,$DbConnection);
        $result3=mysql_query($query3,$DbConnection);
        
        $row1=mysql_fetch_object($result1);
		    $alert_name= $row1->alert_name;
        $row2=mysql_fetch_object($result2);
		    $person_name= $row2->person_name;
        $row3=mysql_fetch_object($result3);
		    $sector_name= $row3->sector_name;        
        
        //echo $query1."<br>".$query2."<br>".$query3."<br>";         
        $msgtmp = $msgtmp."<font color='red'>Alert already been assigned :".$sector_name." ->".$alert_name."->(".$alert_dur_db." &nbsp;mins) [".$person_name."]</font><br>";
        $flag3 =1;
      }
      else
      {
        if($firststr == 1)
        {
          $alert_id_tmp2 = $alert_id_tmp2.$local_alert_ids_tmp[$i];
          $duration_tmp2 = $duration_tmp2.$local_duration_tmp[$i];
          $firststr =0;
        }
        else
        {
          $alert_id_tmp2 = $alert_id_tmp2.",".$local_alert_ids_tmp[$i];
          $duration_tmp2 = $duration_tmp2.",".$local_duration_tmp[$i];
        }                   
      }      
      //echo "<br>alert_id_tmp=".$alert_id_tmp2." ,k=".$k." ,i=".$i;
    }
    
    $local_alert_ids=explode(",",$alert_id_tmp2);
    $local_duration=explode(",",$duration_tmp2);
		$alert_size=sizeof($local_alert_ids);
		
    
    $query_string1="INSERT INTO sector_alert_assignment(escalation_id,sector_id,alert_id,alert_duration,edit_id,edit_date,status) VALUES";
		
    //echo "escalation size=".$escalation_size_tmp."<br>";
    //echo "alert size=".$alert_size."<br>";
    
    if($alert_id_tmp2!="")
    {
      for($k=0;$k<$escalation_size_tmp;$k++)
      {
        for($i=0;$i<$alert_size;$i++)
    		{
    			if( ($k==($escalation_size_tmp-1)) && ($i == ($alert_size-1)) )
    			{
    				$query_string2.="($local_escalation_ids[$k],$local_sector_id,$local_alert_ids[$i],$local_duration[$i],$account_id,'$date',1);";
    			}
    			else
    			{
    				$query_string2.="($local_escalation_ids[$k],$local_sector_id,$local_alert_ids[$i],$local_duration[$i],$account_id,'$date',1),";
    			}
    		}
    	}   // second k closed        
      
      $query=$query_string1.$query_string2;
    }
    		
    //echo $query."<br>";    

    if($alert_id_tmp2!="")
    {
      for($k=0;$k<$escalation_size_tmp;$k++)
      {
        for($i=0;$i<$alert_size;$i++)
        {
          $query_write_status = "SELECT alert_id,sector_id,escalation_id FROM alert_write_status_acc WHERE alert_id='$local_alert_ids[$i]' ".
          "AND vehicle_id='$local_vehicle_id' AND escalation_id='$local_escalation_ids[$k]'";
          $result_write_status = mysql_query($query_write_status,$DbConnection);
          $numrows = mysql_num_rows($result_write_status);
          
          if($numrows ==0)
          {
            $insert_write_status = "INSERT INTO sector_alert_write_status(alert_id,sector_id,escalation_id,status) VALUES('$local_alert_ids[$i]','$local_sector_id','$local_escalation_ids[$k]','0')";        
            $result_insert_status = mysql_query($insert_write_status,$DbConnection);
          }
        }
      } // third k closed
    }      
		//echo $query;
		
    if($DEBUG ==1 )print_query($query); 
        
		if($alert_id_tmp2!="") 
    { 
      $result=mysql_query($query,$DbConnection);      
      
      if($result){$flag=1;$action_perform="Assigned";} 
    }          	  			
  }
  
	else if($action_type1=="deassign")
	{
	  $file_name="src/php/manage_sectorn_alert_deassignment.php"; ///////for previous page
    $action_type="de-assign";
		$escalation_serial_number_1 = explode(",",$_POST['escalation_serial_number']);
		$escalation_size = sizeof($escalation_serial_number_1); 	 		
		$old_value= Array();   $new_value=Array();   $field_name=Array(); $table_name="alert_assignment";  
		for($i=0;$i<$escalation_size;$i++)
		{	
			$query="UPDATE sector_alert_assignment SET status=0,edit_id='$account_id',edit_date='$date' WHERE serial='$escalation_serial_number_1[$i]' AND status=1";
			//echo "query=".$query;
      $result=mysql_query($query,$DbConnection); 
			//$geo_id1= $veh_geo_id2[1];
			$table_name = "sector_alert_assignment";
      $old_value[]= "1";
			$new_value[]="0";
			$field_name[]= "status";         
			$ret_result=track_table($escalation_serial_number_1[$i],$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
		}		
		if($ret_result=="success" && $result){$flag=1;$action_perform="De-Assigned";} 	
	}  
 
	if($flag==1)
	{
		$msg = "Sector ".$action_perform." Successfully";
		$msg_color = "green";				
	}	
	else
	{
		if($flag3==1)
		{
		  $msg = $msgtmp;
		}
		else
    {		
      $msg = "Sorry! Unable to process request.";
    }
    
		$msg_color = "red";		
	}
  
  echo "<center><br><br><FONT color=\"".$msg_color."\" size=\"2\">".$msg."<br><br></strong></font></center>";
  echo'<center><a href="javascript:show_option(\'manage\',\'route\');" class="back_css">&nbsp;<b>Back</b></a></center>';                 
  
?>
        