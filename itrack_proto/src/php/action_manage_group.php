<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include_once('coreDb.php');
	
	$root = $_SESSION['root'];
	$DEBUG=0; 
	$final_group_array=array(array());
	$post_action_type = $_POST['action_type'];
	$post_account_id = $_POST['manage_account_id'];
	
	//echo "post_account_id=".$post_account_id;
	$post_group_name=$_POST['group_name']; ////////not for delete
    $post_remark = trim($_POST['remark']);  ////////not for delete
	$group_id_local1 = trim($_POST['group_id_local']);  ///////not for add group action	
	
	if($post_action_type =="add")                     ///////// ADD
	{ 	
		$result=getGroupName($post_account_id,$post_group_name,$DbConnection);
		echo "result=".$result."<br>";	
		if($result!="")
		{
			$message = "Group Name already exist!";
		}
		else
		{
			if($DEBUG) 
			{
				echo "post_action_type=".$post_action_type;
				echo "Group Name = ".$post_group_name." (Length: ".strlen($post_group_name).") <br>";
				echo "Remark = ".$post_remark." (Length: ".strlen($post_remark).") <br>";  
			}
			
			$flag=0;  
			$result_response=1;			
			  
			if (strlen($post_group_name)==0 || strlen($post_remark)==0)
			{
				$ERROR = "Empty Input!";  $flag = -1;
			}
			else
			{ 
				$max_no1=getGroupMaxSerial($DbConnection);
				echo "max_no1=".$max_no1."<br>";

				if($max_no1=="")
				{
					$group_id_local="0001";
				}
				else
				{ 
					$max_no1=$max_no1+1;   	  
					if($max_no1<9)
					{$grp_str="000";}
					else if($max_no1>=10 && $max_no1<=99)
					{$grp_str="00";}
					else if($max_no1>=100 && $max_no1<=999)
					{$grp_str="0";}
					else
					{$grp_str="";}       
					$group_id_local =$grp_str.$max_no1;
					// echo "group_id_local=".$group_id_local."grp_str=".$grp_str;
				}
				 
				$result2=insertGroup($group_id_local,$post_group_name,$post_account_id,$account_id,$date,1,$post_remark,$DbConnection);
				echo "result=".$result2."<br>";
				if($result2)
				{
					$flag=1;
				}

				if($flag==1)
				{
					$message = "<center>
									<br>
										<FONT color=\"green\">
											<strong>
												Group Added Successfully And Your GroupID is 
											</strong>
										</font>
										<font color='red'>
											<strong>
												".$group_id_local."
											</strong>
										</font>
									</center>";
									$result_status='success';
				}   
				else
				{
					$message = "<center>
									<br>
									<FONT color=\"red\">
										<strong>
											Sorry! Unable to process request.
										</strong>
									</font>
								</center>";
				}      
			}  
		}
	}		
	
	else if($post_action_type == "edit")                 ////////// EDIT
	{
		$result=getGroupName($post_account_id,$post_group_name,$DbConnection);	
		//echo "result=".$result;
		if($result!="")
		{
			$message = "Group Name already exist!";
		}
		else
		{		
			/*$new_value[]=$post_group_name;     
			$new_value[]=$post_remark;
			getGroupSingleRowResult($group_id_local1,$DbConnection);			
			$query = "SELECT group_name,remark FROM `group` WHERE group_id='$group_id_local1'";     
			$result = mysql_query($query, $DbConnection);			     
			$row = mysql_fetch_object($result); 
			//$old_group_name=$row->group_name;    
			$old_value[] = $row->group_name;       
			$old_value[] = $row->remark;    
      
			$field_name[] = "group_name";      
			$field_name[] = "remark";    
			$table = 'group';

			$msg = track_table($group_id_local1,$table,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
			if($msg=="success")
			{  */ 
				
				$result = updateGroup($group_name,$remark,$group_id_local1,$DbConnection);
				if($result)
				{
					$message = "<center>
								<br><br>
									<FONT color=\"green\">
										<strong>
											Group Detail Updated Successfully
										</strong>
									</font>
								</center>";
					$result_status='success';
				}
				else 
				{
					$message = "<center>
									<br><br>
									<FONT color=\"green\">
										<strong>
											Unable to Update Group Detail
										</strong>
									</font>
								</center>";
				} 
			//}
			/*else
			{
				$message=$msg;
			}*/
		}
	}
	
	else if($post_action_type == "delete")              //////////// DELETE
	{
		//echo "group_id=".$group_id_local1;
		$result=getAccountIDByGroupId($group_id_local1,1,$DbConnection);	
		if($result!="")
		{
			$message = "<center>
							<br><br>
								<FONT color=\"red\">
									<strong>
										This Group has been Assigned to Account! Deassign First!
									</strong>
								</font>
						</center>";  
		}
		else
		{
			/*$table = "group";
			$old_value[]="1"; 
			$new_value[]="0"; 
			$field_name[]="status";
			$msg = track_table($group_id_local1,$table,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
			//echo "msg=".$msg;
			if($DEBUG) echo "msg=".$msg;			
			if($msg=="success")
			{*/
				$result=deleteGroup($account_id,$date,$group_id_local1,$DbConnection);				 
				if($result)
				{
					$message="<center>
								<br><br>
									<FONT color=\"red\">
										<strong>
											Selected Group Deleted Successfully
										</strong>
									</font>
								</center>";
					$result_status='success';
				}
				else
				{
					/*$table = "group";
					$old_value[]="0"; 
					$new_value[]="1"; 
					$field_name[]="status";
					$msg = track_table($group_id_local1,$table,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);*/
					$message="<center>
								<br><br>
									<FONT color=\"red\">
										<strong>
											Unable to Delete This Group
										</strong>
									</font>
							</center>";
				}
			/*}
			else
			{
				$message=$msg;
			}*/ 		
		}
	}

	if($result_status=='success')
	{	
		unset($root);
		unset($final_group_array);
		include_once("get_group.php");
		include_once('tree_hierarchy.php');
		$group_cnt=0;		
		$final_group_array=GetGroup_3($root,$DbConnection);
		$_SESSION['final_group_array'] = $final_group_array;  
	}

	echo'<table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
			<tr>
				<td colspan="3" align="center"><b>'.$message.'</b></td>    
			</tr>
		</table><br>'; 
	echo'<center><a href="javascript:show_option(\'manage\',\'group\');" class="menuitem">&nbsp;<b>Back</b></a></center>';
  //include_once("manage_action_message.php");
?>
        