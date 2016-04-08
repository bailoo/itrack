<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$DEBUG=0;
	
	$action_type1 = $_POST['action_type'];
	$local_account_ids = $_POST['local_account_ids'];
	$local_account_ids=explode(",",$local_account_ids);
	$account_size=sizeof($local_account_ids);

	//$old_value= Array();		$new_value=Array();			$field_name=Array();		$table_name="load_cell"; 
  
	if($action_type1=="add") 
	{ 
      $datetime1 = $_POST['date'];
      $datetime1 = str_replace("/","-",$datetime1);  		
  		//$time1 = $_POST['time'];
  		//$date1 = $_POST['date'];
  		$imei1 = $_POST['imei'];
  		$load_status1 = $_POST['load_status1'];
  		$location1 = $_POST['location'];
  		$load1 = $_POST['load'];
  		$load_status2 = $_POST['load_status2'];
  
  		$query ="select Max(sno)+1 as serial_no from load_cell_2";  ///// for auto increament of geo_id ///////////   
  		$result=mysql_query($query,$DbConnection);
  		$row=mysql_fetch_object($result);
  		$max_no= $row->serial_no;
  		if($max_no==""){$max_no=1;}
  
  		//$query_string1="INSERT INTO load_cell(user_account_id,load_cell_id,`datetime`,imei,`before_load`,`after_load`,status,create_id,create_date) VALUES";
      $query_string1="INSERT INTO load_cell_2(user_account_id,load_cell_id,`datetime_1`,`load_status1`,`location`,`load_1`,`load_status2`,imei,status,create_id,create_date) VALUES";
      
  		for($i=0;$i<$account_size;$i++)
  		{
  			//echo "accout_id=".$local_account_ids[$i]."<br>";
  			if($i==$account_size-1)
  			{
  				//$query_string2.="('$local_account_ids[$i]','$max_no','$datetime1','$imei1','$before_load1','$after_load1','1','$account_id','$date');";
  				$query_string2.="('$local_account_ids[$i]','$max_no','$datetime1','$load_status1','$location1','$load1','$load_status2','$imei1','1','$account_id','$date');";
  			}
  			else
  			{
  				//$query_string2.="('$local_account_ids[$i]','$max_no','$datetime1','$imei1','$before_load1','$after_load1','1','$account_id','$date'),";
  				$query_string2.="('$local_account_ids[$i]','$max_no','$datetime1','$load_status1','$location1','$load1','$load_status2','$imei1','1','$account_id','$date'),";
  			}
  		}
  		$query=$query_string1.$query_string2; 
  		//echo "query=".$query;
  	 
  		$result=mysql_query($query,$DbConnection);          	  
  		$flag=1;$action_perform="Added";   
	} 
	else if($action_type1=="edit")
	{
		//$type="edit_delete";
		$load_cell_id1 = $_POST['load_cell_id'];    
		$datetime1 = $_POST['date'];
		$imei1 = $_POST['imei'];
		$load1 = $_POST['load'];        

		$query="UPDATE load_cell SET datetime='$datetime1',imei='$imei1',load='$load1',edit_id='$account_id',edit_date='$date' WHERE load_cell_id='$load_cell_id1'";
		//echo "query=".$query;
		$result=mysql_query($query,$DbConnection); 
		
		if($ret_result=="success" && $result){$flag=1;$action_perform="Updated";} 
	}
	else if ($action_type1=="delete")
	{
		// $type="edit_delete";
		$load_cell_id1 = $_POST['load_cell_id'];    

    $query="UPDATE load_cell SET edit_id='$account_id',edit_date='$date',status='0' WHERE load_cell_id='$load_cell_id1' AND status='1'"; 
		$result=mysql_query($query,$DbConnection);    				
		$flag=2;
    $action_perform="Deleted";
	}
 
	if($flag==1)
	{
		$msg = "Load Cell Detail ".$action_perform." Successfully";
		$msg_color = "green";				
	}	
	else if($flag==2)
	{
		$msg = "Load Cell Detail Deleted Successfully";
		$msg_color = "red";		
	}	
  else if($flag==3)
	{
		$msg = $action_perform;
		$msg_color = "red";		
	}
	else
	{
		$msg = "Sorry! Unable to process request.";
		$msg_color = "red";		
	}
  
  echo "<center><br><br><FONT color=\"".$msg_color."\" size=\"2\">".$msg."<br><br></strong></font></center>";
  echo'<center><a href="javascript:show_option(\'manage\',\'load_cell\');" class="back_css">&nbsp;<b>Back</b></a></center>';                 
  
?>
        