<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$DEBUG=0;
	
	$action_type1 = $_POST['action_type'];
	$action_type1 = trim($action_type1);
  //echo $action_type1;
	$local_account_ids = $_POST['local_account_ids'];
	$local_account_ids=explode(",",$local_account_ids);
	$account_size=sizeof($local_account_ids);

	$old_value= Array();		$new_value=Array();			$field_name=Array();		$table_name="geofence"; 
  
	if($action_type1=="add") 
	{ 
		$transporter_name1 = trim($_POST['transporter_name']);
		$state1 = $_POST['state'];
		$city1 = $_POST['city'];
		$address1 = $_POST['address1'];
		$address2 = $_POST['address2'];

		$query ="select Max(sno)+1 as seiral_no from transporters";  ///// for auto increament of geo_id ///////////   
		$result=mysql_query($query,$DbConnection);
		$row=mysql_fetch_object($result);
		$max_no= $row->seiral_no;
		if($max_no==""){$max_no=1;}

		$query_string1="INSERT INTO transporters(user_account_id,transporter_id,name,state,city,address1,address2,status,create_id,create_date) VALUES";

		for($i=0;$i<$account_size;$i++)
		{
			//echo "accout_id=".$local_account_ids[$i]."<br>";
			if($i==$account_size-1)
			{
				$query_string2.="('$local_account_ids[$i]','$max_no','$transporter_name1','$state1','$city1','$address1','$address2','1','$account_id','$date');";
			}
			else
			{
				$query_string2.="('$local_account_ids[$i]','$max_no','$transporter_name1','$state1','$city1','$address1','$address2','1','$account_id','$date'),";
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
		//$type="edit_delete";
		$transporter_id1 = $_POST['transporter_id'];    
		$state1 = $_POST['state'];
		$city1 = $_POST['city'];
		$address1 = $_POST['address1'];
		$address2 = $_POST['address2'];

		$new_value[]=$transporter_id1;
		$new_value[]=$state1;
		$new_value[]=$city1;
		$new_value[]=$address1;
		$new_value[]=$address2;

		$query="SELECT * FROM transporter where transporter_id='$transporter_id1' AND status='1'";
		$result=mysql_query($query,$DbConnection);
		$row=mysql_fetch_object($result);
		
		$old_value[] = $row->transporter_id;
		$old_value[] = $row->state;
		$old_value[] = $row->city;
		$old_value[] = $row->address1;
		$old_value[] = $row->address2;
		
		$field_name[]="transporter_id";
		$field_name[]="state"; 
		$field_name[]="city";
    $field_name[]="address1";
	  $field_name[]="address2";       

		$table_name = "transporters";
    $query="UPDATE transporters SET state='$state1',city='$city1',address1='$address1',address2='$address2',edit_id='$account_id',edit_date='$date' WHERE transporter_id='$transporter_id1'";
		//echo "query=".$query;
		$result=mysql_query($query,$DbConnection); 

		$ret_result=track_table($transporter_id1,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
		if($ret_result=="success" && $result){$flag=1;$action_perform="Updated";} 
	}
	else if ($action_type1=="delete")
	{
		// $type="edit_delete";
		$transporter_id1 = $_POST['transporter_id'];    
		$query="UPDATE transporters SET edit_id='$account_id',edit_date='$date',status='0' WHERE transporter_id='$transporter_id1' AND status='1'"; 
		$result=mysql_query($query,$DbConnection);    
		$old_value[]="1";$new_value[]="0";$field_name[]="status";     
		$ret_result=track_table($transporter_id1,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);    
		if($ret_result=="success"  && $result){$flag=2;$action_perform="Deleted";}
	}
  
  else if ($action_type1=="vehicle_booking")
  {    
      //echo "in v booking";
      $user_name1 = $_POST['user_name']; 
      $email_id1 = $_POST['email_id']; 
      $address1 = $_POST['address']; 
      $dobooking1 = $_POST['dobooking']; 
      $placefrom1 = $_POST['placefrom']; 
      $placeto1 = $_POST['placeto']; 
      $remark1 = $_POST['remark'];  
      $transporter_id1 = $_POST['transporter_id']; 
      
    	date_default_timezone_set('Asia/Calcutta');    	
    	$add_date = date("Y/m/d H:i:s");	      
      
      $query = "INSERT INTO vehicle_booking(user_name,email_id,address,booking_date,add_date,place_from,place_to,".
      "transporter_id,status,remark) VALUES ('$user_name1','$email_id1','$address1','$dobooking1','$add_date','$placefrom1','$placeto1',".
      "'$transporter_id1',1,'$remark1')"; 
      
      //echo $query; 
      $result = mysql_query($query,$DbConnection); 
      
      if($result)
      {
        $action_perform = "vehicle booking detail added successfully";
        $flag =1;
      }
  }
		
	if($flag==1)
	{
		$msg = "Transporter ".$action_perform." Successfully";
		$msg_color = "green";				
	}	
	else
	{
		$msg = "Sorry! Unable to process request.";
		$msg_color = "red";		
	}
  
  echo "<center><br><br><FONT color=\"".$msg_color."\" size=\"2\">".$msg."<br><br></strong></font></center>";
  
  if($action_type1=="vehicle_booking")
  {
    echo'<center><a href="javascript:show_transporter_info()" class="back_css">&nbsp;<b>Back</b></a></center>';
  }
  else
  {
    echo'<center><a href="javascript:manage_show_file(\'src/php/manage_transporters.php\');" class="back_css">&nbsp;<b>Back</b></a></center>';                 
  }
?>
        