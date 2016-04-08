<?php
	include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
 
  $postPars = array('escalationschool_id' ,'escalation_id', 'action_type' , 'local_account_ids' , 'person_name', 'person_mob', 'person_email' , 'other_detail', 'alert_ids', 'vehicle_id', 'escalation_serial_number', 'duration', 'sms_status', 'mail_status','student_id','studentcard_id','student_father_name','student_parent_mobile_no');
  include_once('action_post_data.php');
  $pd = new PostData();   
  $DEBUG=0;   
 // $setgetpostvalue_obj=new setgetpostvalue();    		
  $local_account_ids=$setgetpostvalue_obj->localAccoutIDS;
	$account_size=sizeof($local_account_ids);
  $old_value= Array();
  $new_value=Array();
  $field_name=Array();
  $table_name="escalation_school"; 
  //echo "hello";
	if($pd->data[action_type]=="add") 
	{
    $action_type="add";  
    $studentcard_id1=$pd->data[studentcard_id];
    $student_id1=$pd->data[student_id];
    $person_name1=$pd->data[student_father_name];
    $person_mob1=$pd->data[student_parent_mobile_no];
	  $person_email1=$pd->data[person_email];
    //$other_detail1=$pd->data[other_detail]; 
     
    $local_account_ids=explode(",",$pd->data[local_account_ids]);
    $account_size=sizeof($local_account_ids); 
    $selected_account_id=$local_account_ids[$account_size-1];
    //echo  "person_name1=".$person_name1;
    
  	$query ="select Max(serial)+1 as seiral_no from escalation_school";  ///// for auto increament of geo_id ///////////   
		//echo "1".  $query;
    $result=mysql_query($query,$DbConnection);
		$row=mysql_fetch_object($result);
		$max_no= $row->seiral_no;
		if($max_no==""){$max_no=1;} 
		
		$query_select = "SELECT person_name,person_mobile FROM escalation_school WHERE person_mobile='$person_mob1'  AND status=1";
    //echo  "2".$query_select;
    $result_select = mysql_query($query_select,$DbConnection);
    $numrows_select = mysql_num_rows($result_select);
    
    if($numrows_select>0)
    {
      $action_perform="Sorry! the Person:".$person_name1." ,Mob:".$person_mob1." has already been added";
      $flag=2;
    } 
    else
    {
      	$query="INSERT INTO escalation_school(escalation_school_id,person_name,person_mobile,person_email,student_id,studentcard_id,status,create_id,create_date) 
        VALUES ('$max_no','$person_name1','$person_mob1','$person_email1','$student_id1','$studentcard_id1','1','$account_id','$date');";
    	  //echo $query."<br>";
        $result=mysql_query($query,$DbConnection);
    	  if($result)
    	  {
        	$query_string1="INSERT INTO escalation_school_grouping(escalation_school_id,account_id,status,edit_id,edit_date) VALUES";
           
      		for($i=0;$i<$account_size;$i++)
      		{
      			if($i==$account_size-1)
      			{
      				$query_string2.="('$max_no','$account_id','1','$account_id','$date');";
      			}
      			else
      			{
      				$query_string2.="('$max_no','$account_id','1','$account_id','$date'),";
      			}
      		}
      		$query=$query_string1.$query_string2;
          //echo "query=".$query;       		
      		if($DEBUG ==1)print_query($query);     
      		$result=mysql_query($query,$DbConnection);          	  
      		if($result){$flag=1;$action_perform="Added";} 
      	}  
    }

  }
  /*else
  {
    $flag=2;
  } */
 
  if($pd->data[action_type]=="edit")
	{	
    $file_name="src/php/manage_edit_delete_escalation_school.php"; ///////for previous page
    $action_type="edit_delete";
    $escalation_id=$pd->data[escalationschool_id]; 
    $person_name=$pd->data[person_name];
    $person_mob=$pd->data[person_mob];
	  $person_email=$pd->data[person_email];

		//$new_value[]=$calibration_name;		   
		//$new_value[]=$final_calibration_data; 
    $query="SELECT * FROM escalation_school where escalation_school_id='$escalation_id' AND status='1'";
		//echo $query;
    $result=mysql_query($query,$DbConnection);
		$row=mysql_fetch_object($result);
		$person_name2=$row->person_name;
		$old_value[] =$person_name2;
		$field_name[]="person_name";
		
		$person_mob2=$row->person_mobile;
		$old_value[] =$person_mob2;
		$field_name[]="person_mobile";
		
		$person_email2=$row->person_email;
		$old_value[] =$person_email2;
		$field_name[]="person_email";		
    
		$other_detail2=$row->other_detail;
		$old_value[] =$other_detail2;
		$field_name[]="other_detail";    		
		
		$query="UPDATE escalation_school SET person_name='$person_name',person_mobile='$person_mob',person_email='$person_email',edit_id='$account_id',edit_date='$date' WHERE escalation_school_id='$escalation_id'";
		//echo "query=".$query;
		$result=mysql_query($query,$DbConnection);
		//$ret_result=track_table($calibration_id,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
		//if($ret_result=="success" && $result){$flag=1;$action_perform="Updated";}
		if($result)
    {$flag=1;$action_perform="Updated";} 
    else
    {
      $flag=2;
    }    
	}
	else if ($pd->data[action_type]=="delete")
	{
	  $file_name="src/php/manage_edit_delete_escalation_school.php"; ///////for previous page
    $action_type="edit_delete"; 
    $escalation_id=$pd->data[escalationschool_id];
    $query="SELECT escalation_school_id from alert_assignment_school where escalation_school_id='$escalation_id' and status=1";
    //echo $query;
    $result=mysql_query($query,$DbConnection); 
    $numrows = mysql_num_rows($result);
    if($numrows>0)
    {
      //echo "aaa";
      $delete_flag=1; 
    }
    else
    {
     //echo"<br>else" ;
      $table_name="escalation_school"; 
      $file_name="src/php/manage_edit_delete_escalation_school.php"; ///////for previous page   
  		$query="UPDATE escalation_school SET edit_id='$account_id',edit_date='$date',status='0' WHERE escalation_school_id ='$escalation_id' AND status='1'"; 
  		//echo $query;
      $result=mysql_query($query,$DbConnection); 
      $numrows = mysql_num_rows($result); 
      if($numrows>0)
      {
       $old_value[]="1";$new_value[]="0";$field_name[]="status";     
    		$ret_result=track_table($escalation_id,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);    
    		if($ret_result=="success" && $result){$flag=1;$action_perform="Deleted";}
      } 
      else
      {
           $delete_flag=0; 
      } 
  		
  	}
	}
	else if($pd->data[action_type]=="assign")
	{
    $file_name="src/php/manage_escalation_school_alert_assignment.php"; ///////for previous page
    $action_type="assign";
		//$local_vehicle_ids=explode(",",$pd->data[vehicle_ids]);
		$local_escalation_id = $pd->data[escalation_id];
    $local_alert_ids_tmp=explode(",",$pd->data[alert_ids]);
		$alert_size_tmp=sizeof($local_alert_ids_tmp);
		$local_vehicle_id = $pd->data[vehicle_id];
		
    $local_duration_tmp=explode(",",$pd->data[duration]);
		$duration_size_tmp=sizeof($local_duration_tmp);	
    
    $local_sms_tmp=explode(",",$pd->data[sms_status]);
    $local_mail_tmp=explode(",",$pd->data[mail_status]);	
		//$local_duration = $pd->data[duration];
				
  	$firststr = 1;
    $alert_id_tmp2 ="";
    $duration_tmp2 ="";
    $msgtmp ="";
    $flag3 =0;
    for($i=0;$i<$alert_size_tmp;$i++)
  	{
      /*$query ="SELECT alert_id,vehicle_id,escalation_id,alert_duration FROM alert_assignment WHERE alert_id='$local_alert_ids_tmp[$i]' ".
      "AND vehicle_id='$local_vehicle_id' AND escalation_id = '$local_escalation_id' AND alert_duration = '$local_duration_tmp[$i]' ".
      "AND status=1";*/      
      $query ="SELECT alert_school_id,vehicle_id,escalation_school_id,alert_duration FROM alert_assignment_school WHERE alert_school_id='$local_alert_ids_tmp[$i]' ".
      " AND vehicle_id='$local_vehicle_id' AND escalation_school_id = '$local_escalation_id'".
      " AND status=1";      
      //echo "1".$query; 
  		$result=mysql_query($query,$DbConnection);
  		
      $row_db = mysql_fetch_object($result);
      $alert_dur_db = $row_db->alert_duration;
      $numrows = mysql_num_rows($result);  	
      
      if($numrows)
      {
        $query1 ="SELECT alert_school_name FROM alert_school WHERE alert_school_id='$local_alert_ids_tmp[$i]' and status=1";
        $query2 ="SELECT person_name FROM escalation_school WHERE escalation_school_id='$local_escalation_id' and status=1";
        $query3 ="SELECT vehicle_name FROM vehicle WHERE vehicle_id='$local_vehicle_id'and status=1";
        $result1=mysql_query($query1,$DbConnection);
        $result2=mysql_query($query2,$DbConnection);
        $result3=mysql_query($query3,$DbConnection);
        
        $row1=mysql_fetch_object($result1);
		    $alert_name= $row1->alert_school_name;
        $row2=mysql_fetch_object($result2);
		    $person_name= $row2->person_name;
        $row3=mysql_fetch_object($result3);
		    $vehicle_name= $row3->vehicle_name;        
        
        //echo $query1."<br>".$query2."<br>".$query3."<br>";         
        $msgtmp = $msgtmp."<font color='red'>Alert already been assigned :".$vehicle_name." ->".$alert_name."->(".$alert_dur_db." &nbsp;mins) [".$person_name."]</font><br>";
        $flag3 =1;
      }
      else
      {
        if($firststr == 1)
        {
          $alert_id_tmp2 = $alert_id_tmp2.$local_alert_ids_tmp[$i];
          $duration_tmp2 = $duration_tmp2.$local_duration_tmp[$i];
          
          $sms_tmp2 = $sms_tmp2.$local_sms_tmp[$i];
          $mail_tmp2 = $mail_tmp2.$local_mail_tmp[$i];	
          
          $firststr =0;
        }
        else
        {
          $alert_id_tmp2 = $alert_id_tmp2.",".$local_alert_ids_tmp[$i];
          $duration_tmp2 = $duration_tmp2.",".$local_duration_tmp[$i];
          
          $sms_tmp2 = $sms_tmp2.",".$local_sms_tmp[$i];
          $mail_tmp2 = $mail_tmp2.",".$local_mail_tmp[$i];	          
        }
      }
    }
    
    $local_alert_ids=explode(",",$alert_id_tmp2);
    $local_duration=explode(",",$duration_tmp2);
    $local_sms=explode(",",$sms_tmp2);
    $local_mail=explode(",",$mail_tmp2);
    //$local_duration=explode(",",$duration_tmp2);
		$alert_size=sizeof($local_alert_ids);

		
    $query_string1="INSERT INTO alert_assignment_school (escalation_school_id,vehicle_id,alert_school_id,alert_duration,sms_status,mail_status,edit_id,edit_date,status) VALUES";
		//echo $query_string1;
    for($i=0;$i<$alert_size;$i++)
		{					
      if($i==$alert_size-1)
			{
			   if($local_duration[$i]=='')
			   {
            $local_duration[$i]="NULL" ;
         }
				$query_string2.="($local_escalation_id,$local_vehicle_id,$local_alert_ids[$i],$local_duration[$i],$local_sms[$i],$local_mail[$i],$account_id,'$date',1);";
			}
			else
			{
			   if($local_duration[$i]=='')
			   {
            $local_duration[$i]="NULL";
         }
				$query_string2.="($local_escalation_id,$local_vehicle_id,$local_alert_ids[$i],$local_duration[$i],$local_sms[$i],$local_mail[$i],$account_id,'$date',1),";
			}
		}
		
    $query=$query_string1.$query_string2;
    //echo $query;
    
    for($i=0;$i<$alert_size;$i++)
    {
      $query_write_status = "SELECT alert_id,vehicle_id,escalation_id FROM alert_write_status_acc WHERE alert_id='$local_alert_ids[$i]' ".
      "AND vehicle_id='$local_vehicle_id' AND escalation_id='$local_escalation_id'";
      $result_write_status = mysql_query($query_write_status,$DbConnection);
      $numrows = mysql_num_rows($result_write_status);
      
      if($numrows ==0)
      {
        $insert_write_status = "INSERT INTO alert_write_status_acc(alert_id,vehicle_id,escalation_id,status) VALUES('$local_alert_ids[$i]','$local_vehicle_id','$local_escalation_id','0')";
        $result_insert_status = mysql_query($insert_write_status,$DbConnection);
      }
    } 
		//echo $query;
		
    if($DEBUG ==1 )print_query($query);     
		$result=mysql_query($query,$DbConnection);          	  
		if($result){$flag=1;$action_perform="Assigned";} 		
	}
	else if($pd->data[action_type]=="deassign")
	{
	  $file_name="src/php/manage_escalation_school_alert_deassignment.php"; ///////for previous page
    $action_type="de-assign";
		$escalation_serial_number_1=explode(",",$pd->data[escalation_serial_number]);
		$escalation_size=sizeof($escalation_serial_number_1); 
    	 		
		$old_value= Array();   $new_value=Array();   $field_name=Array(); $table_name="alert_assignment_school";  
		for($i=0;$i<$escalation_size;$i++)
		{	
			$query="UPDATE alert_assignment_school SET status=0,edit_id='$account_id',edit_date='$date' WHERE serial='$escalation_serial_number_1[$i]' AND status=1";
			echo "query=".$query;
      $result=mysql_query($query,$DbConnection); 
			//$geo_id1= $veh_geo_id2[1];
			$old_value[]= "1";
			$new_value[]="0";
			$field_name[]= "status";         
			$ret_result=track_table($escalation_serial_number_1[$i],$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
		}		
		if($ret_result=="success" && $result){$flag=1;$action_perform="De-Assigned";} 	
	}
     
	if($flag==1)
	{
		$msg = "Escalation for School ".$action_perform." Successfully";
		$msg_color = "green";				
	}	
	else if($flag==2)
	{	  
		$msg = "Escalation input is not correct.Please enter correct input.";
		$msg_color = "green";				
	}
	else if($delete_flag==1)
	{
    $msg = "This escalation has already been Assigned to Vehicle! Deassign First!</strong></font></center>";
    $msg_color = "red";	
  }
	else
	{ 	 
		$msg = "Sorry! Unable to process request.";
		$msg_color = "red";		
	}
	
	
	if($flag3)
	{
	 $msg = $msg."<br>".$msgtmp."<br>";
  }
  
  if($flag==2)
	{	      
    $common_str="escalation_id=".$pd->data[escalation_id]."&action_type=".$action_type."&local_account_ids=".$pd->data[local_account_ids]."&common_id=".$pd->data[local_account_ids]."&person_name=".$pd->data[person_name]."&person_mob=".$pd->data[person_mob]."&person_email=".$pd->data[person_email]."&other_detail=".$pd->data[other_detail];
	  //echo"common_str=".$common_str;
    echo"<input type='hidden' id='common_id' value='".$common_str."'>";
  }
    
  echo "<center><br><br><FONT color=\"".$msg_color."\" size=\"2\">".$msg."<br><br></strong></font></center>";
  if($pd->data[action_type]=="add"  )
  {
    $common_str="action_type=".$pd->data[action_type]; 
    echo"<input type='hidden' id='common_id' value='".$common_str."'>";
    echo'<center><a href="javascript:show_option_with_value(\'manage\',\'add_escalation_school\');" class="back_css">&nbsp;<b>Back</b></a></center>';
  } 
  else
  {
    $backform="src/php/manage_escalation_school.php";
    $common_str="action_type=".$action_type."&common_id=".$pd->data[local_account_ids]; 
    echo"<input type='hidden' id='common_id' value='".$common_str."'>";
    echo'<center><a href="javascript:manage_action_edit_prev(\''.$backform.'\');" class="back_css">&nbsp;<b>Back</b></a></center>';
  }
   
?>
        
