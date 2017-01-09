<?php
	include_once('Hierarchy.php');	
	include_once('util_session_variable.php');	
	include_once('util_php_mysql_connectivity.php');
	include_once('coreDb.php');
  
	$DEBUG=0;	
	$root = $_SESSION['root'];
	$post_action_type = $_POST['action_type']; 
  
	if($post_action_type == "add")
	{
		$flag=0;
		$result_response=1;	
		$post_login=trim($_POST['login']);			
		$post_password=md5(trim($_POST['password']));				
		$post_user_name = trim($_POST['user_name']);  	
		$post_group_id=trim($_POST['group_id']);	
		$post_ac_type=trim($_POST['ac_type']);					
		$post_user_type=trim($_POST['user_type']);
		$len=strlen($post_user_type);  
		$post_user_type=substr($post_user_type,0,($len-1));
		$post_perm_type = trim($_POST['perm_type']);	
		$post_add_account_id =trim($_POST['add_account_id']);	
		$post_company_type=trim($_POST['company_type']);		 
		$post_account_feature1=trim($_POST['account_feature1']); 
		$tmp_post_account_feature1 = explode(',',$post_account_feature1);
		//echo "<br>utype=".$usertype." post_feature=".$post_account_feature1."<br>"; 
		$list_fname="";		
		$list_fvalue="";  
		$usertype=trim($_POST['route_substation']);	  
		
		//$coreDbObject=new coreDb();
		for($fi=0; $fi<sizeof($tmp_post_account_feature1)-1; $fi++)
		{
			$objFM=getFieldName($tmp_post_account_feature1[$fi],1,$DbConnection);	
			//echo "objFM=".$objFM."<br>";
			$field_name=$objFM;
			
			$list_fname.=",".$field_name; // Used in query for insertion
			$list_fvalue.=",1"; // Used in query for insertion
		} 
		//echo "fieldName=".$field_name."<br>";
  
		if($DEBUG)
		{
			echo $list_fname.":".$list_fvalue.":";
			echo "Group ID= ".$post_group_id."<br>";
			echo "User Name= ".$post_user_name."<br>";
			echo "Login = ".$post_group_id."<br>";
			echo "Login = ".$post_login."<br>";
			echo "Password = ".$post_password."<br>";
			echo "A/C Type = ".$post_ac_type."<br>";
			echo "Company Type = ".$post_company_type."<br>";
			echo "Permission Type = ".$post_perm_type."<br>";
			echo "Admin Permission = ".$post_admin_perm."<br>";
			echo"post_account_feature1=".$post_account_feature1."size=".sizeof($post_account_feature1)."<br>";
			for ($fi=1; $fi<=$feature_count; $fi++) 
			{
				echo ucfirst(strtolower($fname[$fi]))." = ".$post_fvalue[$fi]."<br>";
			}
		}
		
		$accountIdLocal=getAccountID($post_group_id,$post_login,1,$DbConnection);	
		//echo "accountIdLocal=".$accountIdLocal."<br>";
		if($accountIdLocal!="")
		{
			$flag = -1;
		}
		else
		{
			if($post_perm_type=="Current")
			{				
				$rowResult=getCurrentVGIDAdminId($post_add_account_id,$DbConnection);
			}
			else
			{				
				$rowResult=getPreviousVGIDAdminId($DbConnection);
			}
			//print_r($rowResult);
			$vehicle_group_id1=$rowResult[0];
			$admin_id1=$rowResult[1];
			//echo"vehicleGroupingId=".$vehicle_group_id1."admin1=".$admin_id1."<br>";
			// Admin of new account
			if($post_perm_type=="Current") // condition of self and not a user
			{
				$admin_id2=getAccountAdminId($post_add_account_id,$DbConnection);
			
			}
			else // condition of new or non-self
			{
				$admin_id2=getAdminId($post_add_account_id,$DbConnection);				
			}			
			//echo"admin_id2=".$admin_id2."<br>";
			$company_id=getCompanyId($account_id,$DbConnection);
			$time_zone1=getTimeZoneId($account_id,$DbConnection);			
			$latlng1 = "1"; // Default Value
			$refresh_rate1 = "1"; // Default Value  

			// Date of account creation
			date_default_timezone_set('Asia/Calcutta'); // add conversion based on the time zone of user
			$date=date("Y-m-d H:i:s");
     
			mysql_query("BEGIN"); 			
		
			$result1=insertAccount($post_login,$post_group_id,$post_password,$usertype,1,$account_id,$date,$DbConnection);		
			//echo "result=".$result1."<br>";
			$account_id1=getAccountID($post_group_id,$post_login,1,$DbConnection);			
			//echo "account_id1=".$account_id1."<br>";
			$company_id1=$company_id;
			$company_id1 = "1";	
			
			$result2=insertAccountDetail($account_id1,$post_user_name,$distance_variable,$vehicle_group_id1,$company_id1,$admin_id1,$admin_id2,$post_ac_type,$account_id,$date,$DbConnection);
			//echo "result2=".$result2."<br>";
			$result3=insertAccountFeature($list_fname,$account_id1,$list_fvalue,$post_user_type,$account_id,$date,$DbConnection);		
			//echo "result3=".$result3."<br>";
			$result4=insertAccountPreference($account_id1,$time_zone1,$latlng1,$refresh_rate1,$account_id,$date,$DbConnection);
			//echo "result4=".$result4."<br>";
			
			if ($result1 && $result2 && $result3 && $result4) 
			{
				//echo "in if";
				mysql_query("COMMIT");
				$flag=1;
				$result_status="success";
			}
			else 
			{  				
				mysql_query("ROLLBACK");
				//echo "in else";
			}		
		}
		//echo "flag=".$flag;
		if ($flag==-1)
		{
			$message="<br>
						<font color=\"Red\" size=4>
							<strong>
								Login ID Already Exist! Please try another Login ID ...
							</strong>
						</font>";
		}			
		else if ($flag==1)
		{ 		
			$message="<br>
					<font color=\"Green\" size=4>
						<strong>
							Account Created Successfully!
						</strong>
					</font>";
		}
		else if($flag==0)
		{
			$message="<br>
					<font color=\"Red\" size=4>
						<strong>
							Unable to create Account due to some Network problem!
						</strong>
					</font>";
		}			
	} // IF POST_ACTION TYPE CLOSED

	else if($post_action_type =="edit")
	{
		$post_edit_account_id = trim($_POST['edit_account_id']);
		$post_edit_account_id=explode(",",$post_edit_account_id);   

		$post_user_name = trim($_POST['user_name']);
		$post_perm_type=trim($_POST['perm_type']);
		$post_user_type=trim($_POST['user_type']);

		$len=strlen($post_user_type); 
		$post_user_type=substr($post_user_type,0,($len-1));  ////////for detect the last four character;    		
		$post_account_feature1=trim($_POST['account_feature1']);  
		$tmp_post_account_feature1 = explode(',',$post_account_feature1); 
		for($i=1;$i<=$feature_count;$i++)  /////featrue count coming from session ///////
		{
			$flag=0; 
			for($fi=0; $fi<sizeof($tmp_post_account_feature1)-1; $fi++)
			{
				$field_name=getFieldName($tmp_post_account_feature1[$fi],1,$DbConnection);			
				if($fname[$i]== $field_name)      ///////////feature name coming from session ////////////
				{    	 
					$update_string.=$field_name."=1,"; $flag=1;
				}
			}
			if($flag==0)
			{ 
				$update_string.=$fname[$i]."=0,"; 
			}
		}    
		$len=strlen($update_string);
		$update_string=substr($update_string,0,($len-1));  ////////for detect the last four character; 	

		
		$result=updateAccountDetail($post_edit_account_id[0],$post_user_name,$distance_variable,$post_perm_type,$DbConnection);
		//echo "result=".$result."<br>";
		
		$result2=updateAccountFeature($update_string,$post_user_type,$post_edit_account_id[0],$DbConnection);			
		echo "result=".$result2."<br>";
		if($result && $result2)
		{
			$message = "<center>
						<br><br>
							<FONT color=\"green\">
								<strong>
									Account Detail Updated Successfully
								</strong>
							</font>
						</center>";
			$result_status='success';
		} 
	}
	
	else if($post_action_type =="delete")
	{
		$tmp_child;
		$post_manage_account_id=$_POST['manage_account_id'];
		$post_manage_account_id1=explode(",",$post_manage_account_id);
		//echo"post_manage_account_id".$post_manage_account_id." post_manage_account_id1".$post_manage_account_id1[0];		
		$account_id_local=$post_manage_account_id1[0];		

		include_once('tree_hierarchy_information.php');
		$result=find_child($root,$account_id_local);	
		if($result=="Child Found") 
		{
		$message="<center>
					<br><br>
					<FONT color=\"red\">
						<strong>
							Some account or vehicle assigned to this account. First deassigned it and then 
							Delete this account
						</strong>
					</font>
				</center>";
		}
		else
		{
			$table = "account";		
			$groupId=getGroupID($account_id_local,1,$DbConection);			
			if($groupId!="")
			{
				$message="<center>
						<br><br>
							<FONT color=\"red\">
								<strong>
									A Group is assigned to this account.
									First de assigned it and then delete this account
								</strong></font></center>";
			}
			else
			{			
				/*$old_value[]="1"; 
				$new_value[]="0"; 
				$field_name[]="status";	  
				//$msg = track_table($account_id_local,$table,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection); 
				if($DEBUG) 
				{
					echo "msg=".$msg;
				}*/
				
				//if($msg=="success")
				{
					
					$result=updateAccount($account_id_local,$account_id,$date,0,$DbConnection);
			
					if($result)
					{
						$message="<center>
									<br><br>
									<FONT color=\"red\">
										<strong>Account Deleted Successfully</strong>
									</font>
								</center>";
						$result_status="success";
					}
					else
					{
						/*$table = "account";
						$old_value[]="0"; 
						$new_value[]="1"; 
						$field_name[]="status";	  
						//$msg = track_table($group_id_local,$table,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);*/ 
						$message="<center>
									<br><br>
									<FONT color=\"red\">
										<strong>Unable to Delete This Account</strong>
									</font>
								</center>";
					}
				}
			}
		}
	}	
	echo "<center>".$message."</center>";
	//echo"result_status=".$result_status."<br>";
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
echo'<center>
		<a href="javascript:show_option(\'manage\',\'account\');" class="back_css">
			<b>
				&nbsp;<b>Back
			</b>
		</a>
	</center>'; 
?>
        