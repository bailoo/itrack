<?php  
	include_once('Hierarchy.php');		
	include_once('util_session_variable.php');	
	include_once('util_php_mysql_connectivity.php');	
	include_once('user_type_setting.php');
	$root=$_SESSION['root'];	
	$DEBUG=0;	
	$post_action_type = $_POST['action_type'];		
	if($DEBUG) 
	{
		echo "post_action_type=".$post_action_type;
	}
	$parent_account_ids=array();

	$action_type_local;
	//echo "action_type=".$post_action_type."<br>";
	if($post_action_type=="add" || $post_action_type=="add_register")
	{	
		$local_account_ids=$_POST['account_ids'];	
		$local_account_ids1=explode(",",$local_account_ids);
		$account_size=sizeof($local_account_ids1);		
		$post_vehicle_name=$_POST['vehicle_name'];	
		$post_vehicle_number=$_POST['vehicle_number'];
		$post_sim_number=$_POST['sim_number'];	
		$post_mobile_number=$_POST['mobile_number'];			
		$post_max_speed=$_POST['max_speed'];
		$post_vehicle_tag=$_POST['vehicle_tag'];	
		$post_vehicle_type=$_POST['vehicle_type'];	
		$post_category=$_POST['category'];
		//echo "post_cat=".$post_category;	
		if($DEBUG)
		{
			echo "Vehicle Name = ".$post_vehicle_name." (Length: ".strlen($post_vehicle_name).") <br>";	echo "Vehicle Number = ".$post_vehicle_number." (Length: ".strlen($post_vehicle_number).") <br>";
			echo "MAX Speed = ".$post_max_speed." (Length: ".strlen($post_max_speed).") <br>";	echo "Vehicle Tag = ".$post_vehicle_tag." (Length: ".strlen($post_vehicle_tag).") <br>";
			echo "Vehicle Type = ".$post_vehicle_type." (Length: ".strlen($post_vehicle_type).") <br>";
		}
		$query = "SELECT vehicle_name FROM vehicle WHERE vehicle_name='$post_vehicle_name' and status='1'";
		//echo "query=".$query;
		if($DEBUG) print_query($query);
		$result=mysql_query($query,$DbConnection);
		$count = mysql_num_rows($result);
		if($count > 0)
		{
			$message = $report_type." Name already exist!";
		}
		else
		{
			$query="INSERT INTO vehicle(vehicle_name,vehicle_type,vehicle_tag,max_speed,category,vehicle_number,sim_number,mobile_number,status,create_id,create_date) VALUES".
				   "('$post_vehicle_name','$post_vehicle_type','$post_vehicle_tag','$post_max_speed','$post_category','$post_vehicle_number','$post_sim_number','$post_mobile_number','1','$account_id','$date')";
			//echo "query=".$query."<br>";
			$result=mysql_query($query,$DbConnection);
			if($result)
			{
				$query="SELECT vehicle_id from vehicle where vehicle_name='$post_vehicle_name' and status=1";
				$result=mysql_query($query,$DbConnection);
				$row=mysql_fetch_row($result);
				$vehicle_id=$row[0];
				$query_string1="INSERT INTO vehicle_grouping(vehicle_id,account_id,create_id,create_date,status) VALUES";				
				for($i=0;$i<$account_size;$i++)
				{
					if($i==$account_size-1)
					{
						$query_string2.="($vehicle_id,$local_account_ids1[$i],$account_id,'$date',1);";
					}
					else
					{
						$query_string2.="($vehicle_id,$local_account_ids1[$i],$account_id,'$date',1),";
					}
				}				
				$query=$query_string1.$query_string2; 
				$result=mysql_query($query,$DbConnection);	
				if($DEBUG){echo $query;}
				//if($post_action_type=="add")
				{
					if($result)
					{
						for($i=0;$i<($account_size-1);$i++)
						{
							if($i==0)
							{
								$account_string2=explode(",",$account_string1[$i]);
								$account_id_local1=$account_string2[0];
								$account_status1=$account_string2[1];
								//echo"account_id_local=".$account_id_local1."<br>";			
								break;
							}
						}					
						$acc=assign_to_till_root($account_id);
						for($i=0;$i<sizeof($acc);$i++)
						{
							$query = "INSERT INTO vehicle_grouping (vehicle_id,account_id,status,create_id,create_date) VALUES('$vehicle_id',$acc[$i],1,$account_id,'$date')";
							$result = mysql_query($query, $DbConnection);
						}
						$message = "<center><br><FONT color=\"green\"><strong>".$report_type." Detail Added Successfully!</strong></font></center>";$result_status="success";
					}					
					else
					{
						$message = "<center><br><FONT color=\"red\"><strong>Sorry! Unable to process request.</strong></font></center>";
					}
				}
			}
		}		
	}
	
	if($post_action_type=="register" || $post_action_type=="add_register")            ////////// ASSIGN
	{ 
		$device = $_POST['device_imei_no'];
		$vname = $_POST['vehicle_name']; 	
		$local_account_id = $_POST['local_account_id1'];		
		
		$vehicle_display_option = $_POST['vehicle_display_option1']; 	
		$options_value = $_POST['options_value1'];
		
		echo'<input type="hidden" id="vehicle_display_option" value='.$vehicle_display_option.'>
			<input type="hidden" id="options_value" value='.$options_value.'>
			<input type="hidden" id="local_account_id" value='.$local_account_id.'>';
		//$query ="SELECT vehicle_id FROM vehicle WHERE vehicle_name='$vname' AND vehicle_id IN (SELECT vehicle_id from vehicle_grouping WHERE account_id=$local_account_id AND status=1) AND status=1";     
		$query ="SELECT vehicle_id FROM vehicle WHERE vehicle_name='$vname' AND vehicle_id IN (SELECT vehicle_id from vehicle_grouping WHERE status=1) AND status=1";     
		//echo "query=".$query."<br>";
		$query ="SELECT vehicle.vehicle_id FROM vehicle INNER JOIN vehicle_grouping ON vehicle.vehicle_id=vehicle_grouping.vehicle_id AND".
				" vehicle_grouping.status=1 AND vehicle.vehicle_name='$vname' AND vehicle.status=1";     
		//echo "query=".$query."<br>";
		$result=mysql_query($query,$DbConnection);        
		if($row = mysql_fetch_object($result))
		{   
			$vid = $row->vehicle_id;//echo "vehicle_id=".$vid."<br>";
		}
		if($vid)
		{
			$query="INSERT INTO vehicle_assignment(vehicle_id, device_imei_no, create_id, create_date, status)VALUES('$vid','$device','$account_id','$date','1')";
			$result=mysql_query($query,$DbConnection);   
			// PRINT MESSAGE
			if($result)
			{
				$message="<center><br><br><FONT color=\"green\"><strong>Device And ".$report_type." Assigned Successfully</strong></font></center>";
				$result_status="success";
			}
			else
			{
				$message="<center><br><br><FONT color=\"red\"><strong>Unable to Assign Device to ".$report_type."</strong></font></center>";
			} 
		} 
	}
  
	else if($post_action_type=="deregister")           ///////// DE ASSIGN
	{
		$device1 = $_POST['device']; 	$device = explode(',',$device1);     
		if($DEBUG) 
		{echo "device=".$device." device=".$device." size=".sizeof($device);}

		for($i=0;$i<sizeof($device);$i++)
		{   
		$query="UPDATE vehicle_assignment SET status='0',edit_id='$account_id',edit_date='$date' WHERE device_imei_no='$device[$i]'";
		$result=mysql_query($query,$DbConnection); 
		if($DEBUG) print_query($query);
		}               

		if($result)
		{$message="<center><br><br><FONT color=\"green\"><strong>Device and ".$report_type." De-Registered Successfully</strong></font></center>";
		$result_status="success";
		}
		else
		{$message="<center><br><br><FONT color=\"red\"><strong>Unable to De De-Registered Device and ".$report_type."</strong></font></center>";}    
	}

	
	else if($post_action_type == "edit_action")                 ////////// EDIT
	{
		//$edit_account_id = $_POST['edit_account_id'];
		$vehicle_id_edit = $_POST['vehicle_id'];
		$vehicle_name_edit = $_POST['vehicle_name'];
		$vehicle_number_edit = $_POST['vehicle_number'];
		$max_speed_edit = $_POST['max_speed'];
		$vehicle_tag_edit = $_POST['vehicle_tag']; 
		$vehicle_type_edit = $_POST['vehicle_type']; 
		$category = $_POST['category1'];  
		$post_sim_number=$_POST['sim_number'];	
		$post_mobile_number=$_POST['mobile_number'];

		$new_value[] = $vehicle_name_edit;
		$new_value[] = $vehicle_number_edit;  
		$new_value[] = $max_speed_edit;
		$new_value[] = $vehicle_tag_edit;  
		$new_value[] = $vehicle_type_edit;
		$new_value[] = $category;          

		// UPDATE TRACK LOG
		$query = "SELECT vehicle_id,vehicle_name,vehicle_number,max_speed,vehicle_tag,vehicle_type,category FROM vehicle WHERE vehicle_id='$vehicle_id_edit'";
		//echo "query=".$query;
		$result = mysql_query($query, $DbConnection);
		if($DEBUG == 1) print_query($query);    

		$row = mysql_fetch_object($result);
		$id = $row->VehicleID;
		$old_value[] = $row->vehicle_name;
		$old_value[] = $row->vehicle_number;
		$old_value[] = $row->max_speed;
		$old_value[] = $row->vehicle_tag;
		$old_value[] = $row->vehicle_type;
		$old_value[] = $row->category;

		$table = 'vehicle';
		$field_name[] = "vehicle_name";
		$field_name[] = "vehicle_number";
		$field_name[] = "max_speed";
		$field_name[] = "vehicle_tag";
		$field_name[] = "vehicle_type";
		$field_name[] = "category";

		$msg = track_table($id,$table,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);

		// UPDATE DEVICE
		$query="UPDATE vehicle SET vehicle_name='$vehicle_name_edit',vehicle_number='$vehicle_number_edit',sim_number='$post_sim_number',mobile_number='$post_mobile_number', ".
		"max_speed='$max_speed_edit',vehicle_tag='$vehicle_tag_edit',vehicle_type='$vehicle_type_edit',category='$category', ".
		"edit_id='$account_id', edit_date='$date' WHERE vehicle_id='$vehicle_id_edit'"; 
    //echo "query=".$query; 
		$result = mysql_query($query, $DbConnection);    

		if($DEBUG) print_query($query);
		if($result)
		{
			$message = "<center><br><br><FONT color=\"green\"><strong>".$report_type." Detail Updated Successfully</strong></font></center>";
      $result_status="success";		  
    }
    else 
    {
			$message = "<center><br><br><FONT color=\"green\"><strong>Unable to Update ".$report_type." Detail</strong></font></center>";  
	}
  }
	
  
  else if($post_action_type == "delete")              //////////// DELETE
  {   
    $vehicle_id_local = $_POST['manage_vehicle_id'];
	//echo"vehicle_id_local=".$vehicle_id_local;
        
    $query = "SELECT vehicle_id FROM vehicle_assignment WHERE vehicle_id='$vehicle_id_local' AND status='1'";
	//echo"query1=".$query."<br>";
    $result = mysql_query($query, $DbConnection);
    $numrows = mysql_num_rows($result);
    if($numrows>0)
    {
      $message = "<center><br><br><FONT color=\"red\"><strong>This ".$report_type." has been Assigned to Device! Deassign First!</strong></font></center>";  
    }
    else
    {
      $query = "UPDATE vehicle SET status='0',edit_id='$account_id',edit_date='$date' WHERE vehicle_id='$vehicle_id_local'";
      //echo "query=".$query;
      if($DEBUG) print_query($query);
      $result = mysql_query($query, $DbConnection);   
      if($result)
      {	
      $table = "vehicle";	$old_value[]="1";$new_value[]="0";$field_name[]="status";	
		$msg = track_table($vehicle_id_local,$table,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
		if($DEBUG) echo "msg=".$msg;
        $message = "<center><br><br><FONT color=\"red\"><strong>Selected ".$report_type." Deleted Successfully</strong></font></center>";
        $result_status="success";
      }
      else
      {
        $message = "<center><br><br><FONT color=\"red\"><strong>Unable to Delete ".$report_type." Detail</strong></font></center>";
      }
      $query = "UPDATE vehicle_grouping SET status='0',edit_id='$account_id',edit_date='$date' WHERE vehicle_id='$vehicle_id_local'";
      //echo "query=".$query;
      if($DEBUG) print_query($query);
      $result = mysql_query($query, $DbConnection);   
      if($result)
      {	
        $table = "vehicle_grouping";	$old_value[]="1";$new_value[]="0";$field_name[]="status";	
		$msg = track_table($vehicle_id_local,$table,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
		if($DEBUG) echo "msg=".$msg;
        $message = "<center><br><br><FONT color=\"red\"><strong>Selected ".$report_type." Deleted Successfully</strong></font></center>";
        $result_status="success";
      }
      else
      {
        $message = "<center><br><br><FONT color=\"red\"><strong>Unable to Delete ".$report_type." Detail</strong></font></center>";
      }
    }    
  }
  
  
	else if($post_action_type == "assign")
	{		
		$account_string = $_POST['account_string1'];
		//echo "account_string=".$account_string."<br>";
		$account_string1=explode(":",$account_string);
		$account_size=sizeof($account_string1);		
		$vehicle_ids = $_POST['vehicle_ids1'];		
		//echo"vehicle_ids=".$vehicle_ids."<br>";
		for($i=0;$i<($account_size-1);$i++)
		{
			$account_string2=explode(",",$account_string1[$i]);
			$account_id_local=$account_string2[0];			
			$account_status=$account_string2[1];		
			//echo "account_status=".$account_status."<br>"."account_id=".$account_id_local."<br>";
			$query ="SELECT	* FROM vehicle_grouping WHERE vehicle_id='$vehicle_ids' AND account_id='$account_id_local'";
			// echo "query=".$query."<br>";
			$result = mysql_query($query, $DbConnection);
			$numrows = mysql_num_rows($result);
			if($numrows!=0)
			{
				$row=mysql_fetch_object($result);	$status_local=$row->status;
			}
			
			//($num_rows==0 && $account_status==1)  it's found no entry
			// $account_status==0 && $status_local==1) it is for deassignment
			// ($account_status==1 && $status_local==0) it is for assignment 
			
		
			if(($numrows==0 && $account_status==1) || ($account_status==0 && $status_local==1) || ($account_status==1 && $status_local==0))
			{
				if($account_status==1)
				{
					if($numrows==0)
					{
						$query = "INSERT INTO vehicle_grouping (vehicle_id,account_id,status,create_id,create_date) VALUES('$vehicle_ids',$account_id_local,1,$account_id,'$date')";
						$result = mysql_query($query, $DbConnection);
						if($result)
						{$message="Assigned/Deassigned action performed successfully";}
						else
						{$message="Unable to process this request";}
					}
					else
					{
						$query = "UPDATE vehicle_grouping SET status=1 where vehicle_id = '$vehicle_ids',edit_date='$date' AND account_id=$account_id_local";
						$result = mysql_query($query, $DbConnection);
						if($result)
						{$message="Assigned/Deassigned action performed successfully";}
						else
						{$message="Unable to process this request";}
					}
					
					$query = "SELECT * FROM vehicle_assignment WHERE vehicle_id =$vehicle_ids AND status=1";
					//echo" query=".$query."<br>";
					$result = mysql_query($query, $DbConnection);
					$numrows = mysql_num_rows($result);
					if($numrows!=0)
					{
						$row=mysql_fetch_object($result);
						$device_imeino_local = $row->device_imei_no;
						$query = "SELECT * FROM device_assignment WHERE device_imei_no = $device_imeino_local AND account_id=$account_id_local";
						//echo "query3=".$query."<br>";
						$result = mysql_query($query, $DbConnection);
						$numrows = mysql_num_rows($result);
						if($numrows==0)
						{
							$query = "INSERT INTO device_assignment(device_imei_no,account_id,status,create_id,create_date) VALUES($device_imeino_local,$account_id_local,1,$account_id,'$date')";
							$result = mysql_query($query, $DbConnection);
							if($result)
							{$message="Assigned/Deassigned action performed successfully";}
							else
							{$message="Unable to process this request";}
						}
						else
						{
							$query ="UPDATE device_assignment SET status=1 WHERE device_imei_no = $device_imeino_local AND account_id=$account_id_local";
							$result = mysql_query($query, $DbConnection);
							if($result)
							{$message="Assigned/Deassigned action performed successfully";}
							else
							{$message="Unable to process this request";}
						}
					}	
				}
				else if($account_status==0)
				{
					$query = "UPDATE vehicle_grouping SET status=0 WHERE vehicle_id ='$vehicle_ids',edit_date='$date' AND account_id=$account_id_local";
					$result = mysql_query($query, $DbConnection);
					if($result)
					{$message="Assigned/Deassigned action performed successfully";}
					else
					{$message="Unable to process this request";}
					$query1 = "SELECT * FROM vehicle_assignment WHERE vehicle_id  = '$vehicle_ids' AND status=1";
					//echo "query=".$query1;
					$result1 = mysql_query($query1, $DbConnection);
					$numrows1 = mysql_num_rows($result1);			
					if($numrows1!=0)
					{
						$row1=mysql_fetch_object($result1);
						$device_imei_no_local = $row1->device_imei_no;
						$query = "SELECT * FROM device_assignment where device_imei_no = '$device_imei_no_local' AND account_id=$account_id_local";
						$result2 = mysql_query($query, $DbConnection);
						$numrows2 = mysql_num_rows($result2);
						if($numrows2!=0)
						{
							$query = "UPDATE device_assignment SET status=0 WHERE device_imei_no ='$device_imei_no_local' AND account_id=$account_id_local";
							$result = mysql_query($query, $DbConnection);							
						}
					}
				}
			}		
		}  
		$account_string = $_POST['account_string1'];
		//echo "account_string=".$account_string."<br>";
		$account_string1=explode(":",$account_string);
		
		for($i=0;$i<($account_size-1);$i++)
		{
			if($i==0)
			{
				$account_string2=explode(",",$account_string1[$i]);
				$account_id_local1=$account_string2[0];
				$account_status1=$account_string2[1];
				//echo"account_id_local=".$account_id_local1."<br>";			
				break;
			}
		}
		
		$acc=assign_to_till_root($account_id_local1);	
		//echo "acc=".sizeof($acc)."<br>";
		
		for($i=0;$i<sizeof($acc);$i++)
		{
			$account_id_local=$acc[$i];		
			$query ="SELECT	* FROM vehicle_grouping WHERE vehicle_id='$vehicle_ids' AND account_id='$account_id_local'";
			$result = mysql_query($query, $DbConnection);
			$numrows = mysql_num_rows($result);
			if($numrows!=0)
			{
				$row=mysql_fetch_object($result);	$status_local=$row->status;
			}
			if(($numrows==0 && $account_status==1) || ($account_status==0 && $status_local==1) || ($account_status==1 && $status_local==0))
			{
				if($account_status==1)
				{
					if($numrows==0)
					{
						$query = "INSERT INTO vehicle_grouping (vehicle_id,account_id,status,create_id,create_date) VALUES('$vehicle_ids',$account_id_local,1,$account_id,'$date')";
						$result = mysql_query($query, $DbConnection);
						if($result)
						{$message="Assigned/Deassigned action performed successfully";}
						else
						{$message="Unable to process this request";}
					}
					else
					{
						$query = "UPDATE vehicle_grouping SET status=1 where vehicle_id ='$vehicle_ids',edit_date='$date' AND account_id=$account_id_local";
						$result = mysql_query($query, $DbConnection);
						if($result)
						{$message="Assigned/Deassigned action performed successfully";}
						else
						{$message="Unable to process this request";}
					}
					
					$query = "SELECT * FROM vehicle_assignment WHERE vehicle_id =$vehicle_ids AND status=1";
					//echo" query=".$query."<br>";
					$result = mysql_query($query, $DbConnection);
					$numrows = mysql_num_rows($result);
					if($numrows!=0)
					{
						$row=mysql_fetch_object($result);
						$device_imeino_local = $row->device_imei_no;
						$query = "SELECT * FROM device_assignment WHERE device_imei_no = $device_imeino_local AND account_id=$account_id_local";
						//echo "query3=".$query."<br>";
						$result = mysql_query($query, $DbConnection);
						$numrows = mysql_num_rows($result);
						if($numrows==0)
						{
							$query = "INSERT INTO device_assignment(device_imei_no,account_id,status,create_id,create_date) VALUES($device_imeino_local,$account_id_local,1,$account_id,'$date')";
							$result = mysql_query($query, $DbConnection);
							if($result)
							{$message="Assigned/Deassigned action performed successfully";}
							else
							{$message="Unable to process this request";}
						}
						else
						{
							$query ="UPDATE device_assignment SET status=1 WHERE device_imei_no = $device_imeino_local AND account_id=$account_id_local";
							$result = mysql_query($query, $DbConnection);
							if($result)
							{$message="Assigned/Deassigned action performed successfully";}
							else
							{$message="Unable to process this request";}
						}
					}	
				}
			}
		}		
	}  
  
    if($result_status=="success")
	{
		/*unset($root);
		unset($final_group_array);
		include_once("get_group.php");
		include_once('tree_hierarchy.php');
		$group_cnt=0;		
		$final_group_array=GetGroup_3($root,$DbConnection);
		$_SESSION['final_group_array'] = $final_group_array; */

	}
	
	function assign_to_till_root($account_id_local1)
	{
		global $DbConnection;	global $parent_account_ids;	 global $acc_size;
		//echo "id=".$account_id_local1."vehicle_id=".$vehicle_ids."<br>";
		$query = "SELECT account_admin_id FROM account_detail WHERE account_id='$account_id_local1'";
		//echo"query=".$query.'<BR>';
		$result=mysql_query($query,$DbConnection);
		$row=mysql_fetch_row($result);
		$admin_id=$row[0];
		$query = "SELECT account_id FROM account_detail WHERE admin_id='$admin_id'";
		//echo"query=".$query.'<BR>';
		$result=mysql_query($query,$DbConnection);
		$row1=mysql_fetch_row($result);
		$function_account_id=$row1[0];
		//echo "function_account_id=".$function_account_id."<br>";
		if($function_account_id==0)
		{
			return $parent_account_ids;
		}
		else
		{			
			$final_account_id=assign_to_till_root($function_account_id,$vehicle_ids);			
			$parent_account_ids[]=$function_account_id;
			//echo"acc1=".$function_account_id."<br>"."acc1=".$function_account_id."<br>"."acc1=".$parent_account_ids."<br>";				
			return $parent_account_ids;					
		}
	}

	echo' <br>
			<table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
				<tr>
					<td colspan="3" align="center"><b>'.$message.'</b></td>    
				</tr>
			</table>';
	if($post_action_type=="register")
	{		
		echo'<center><a href="javascript:show_vehicle_register(\'manage\',\'device_vehicle_register\');" class="back_css">&nbsp;<b>Back</b></a></center>'; 		
	}
	else if($post_action_type=="add_register" || $post_action_type=="add")
	{
		echo'<center><a href="javascript:show_vehicle_register(\'manage\',\'add_vehicle\');" class="back_css">&nbsp;<b>Back</b></a></center>'; 
	}
	else
	{
		echo'<center><a href="javascript:show_option(\'manage\',\'vehicle\');" class="back_css">&nbsp;<b>Back</b></a></center>'; 
	}
	//include_once("manage_vehicle.php");
?>
        
