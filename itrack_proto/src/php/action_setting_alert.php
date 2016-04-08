<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 	
	$DEBUG = 0;
	$action_type1 = $_POST['action_type'];
	$local_account_ids = $_POST['local_account_ids'];
	$local_account_ids=explode(",",$local_account_ids);
	$account_size=sizeof($local_account_ids); 
	$local_card_ids = $_POST['card_ids'];
	$local_card_ids=explode(",",$local_card_ids);
	$card_size=sizeof($local_card_ids);
  
	
	$old_value= Array();	$new_value=Array();		$field_name=Array(); 	$table_name="configure_alert"; 
  
	include_once('track_log.php');
	  if($action_type1=="configure") 
		{     
		$selected_alerts = $_POST['selected_alerts'];
		//echo "selected_alerts :". $selected_alerts; 
		$selected_alerts = explode(",",$selected_alerts);
		$alerts_size=sizeof($selected_alerts);
		$time_before_min = $_POST['time_before_min'];
		
		$before="0";
		$arrived="0";
		$pick="0";
		$drop="0";
    
    // echo "alerts_size :". $alerts_size; 
	
    for($j=0;$j<$alerts_size;$j++)
		{
			$value =  $selected_alerts[$j];
			if($value=="1"){
			$before="1";
			}
			else if($value=="2"){
			$arrived="1";
			}
			else if($value=="3"){
			$pick="1";
			}
			else if($value=="4"){
			$drop="1";
			}
		
		}
    
    for($i=0;$i<$card_size;$i++)
		{
			$query2 ="select * from configure_alert where studentcard_id='$local_card_ids[$i]'";
			if($DEBUG ==1 )print_query($query2);  
			$result2=mysql_query($query2,$DbConnection);
			$row1=mysql_fetch_object($result2);
			$num_rows=mysql_num_rows($result2);
			
  			if($num_rows > 0){
  			   
			$new_value[]=$local_account_ids[0];
			$new_value[]=$before;
            $new_value[]=$arrived;
            $new_value[]=$pick;
            $new_value[]=$drop;
            $new_value[]=$time_before_min;
        		             
        	
            $local_account_ids2=$row->user_account_id;
        		$old_value[] =$local_account_ids2;
        		$field_name[]="user_account_id";
			$before2=$row->before;
        		$old_value[] =$before2;
        		$field_name[]="before";
			$arrived2=$row1->arrived;         
        		$old_value[] = $arrived2;
        		$field_name[]="arrived";
            $pick2=$row1->pick;         
        		$old_value[] = $pick2;
        		$field_name[]="pick";
            $drop2=$row1->drop;         
        		$old_value[] = $drop2;
        		$field_name[]="drop";
            $time_before2=$row1->time_before;         
        		$old_value[] = $time_before2;
        		$field_name[]="time_before"; 
        
        		$query="UPDATE configure_alert SET `user_account_id`='$local_account_ids[0]',`before`='$before',arrived='$arrived',pick='$pick',`drop`='$drop',time_before='$time_before_min',edit_id='$account_id',edit_date='$date' WHERE studentcard_id='$local_card_ids[$i]'";  
        		if($DEBUG ==1 )
        		print_query($query);
        		$result=mysql_query($query,$DbConnection); 
        
        		$ret_result=track_table($local_card_ids[$i],$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
        		if($ret_result=="success" && $result){$flag=1;$action_perform="Configured";} 
                
        }
        else{
        
          $query_string1="INSERT INTO configure_alert(user_account_id,`before`,arrived,pick,`drop`,time_before,studentcard_id,status,create_id,create_date) VALUES";
          $query_string2.="($local_account_ids[0],$before,$arrived,$pick,$drop,'$time_before_min',$local_card_ids[$i],'1','$account_id','$date');";
          $query=$query_string1.$query_string2;
                        
          if($DEBUG ==1 )print_query($query);     
      		$result=mysql_query($query,$DbConnection);          	  
      		if($result){$flag=1;$action_perform="Configured";} 
        }
      
     } 
      
	}
	
 
	if($flag==1)
	{
		$msg = "Alert ".$action_perform." Successfully";
		$msg_color = "green";				
	}	
	else
	{
		$msg = "Sorry! Unable to process request.";
		$msg_color = "red";		
	} 

 echo "<center><br><br><FONT color=\"".$msg_color."\" size=\"2\">".$msg."<br><br></strong></font></center>";
  echo'<center><a href="javascript:show_option(\'setting\',\'configure_alert_prev\');" class="back_css">&nbsp;<b>Back</b></a></center>'; 

  
?>   