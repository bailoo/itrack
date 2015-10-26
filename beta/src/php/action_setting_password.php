<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
        include_once("util_account_detail.php");

	$local_account_id=$_POST['setting_account_id'];		$old_pass1=md5($_POST['old_pass']);	$new_pass1=md5($_POST['new_pass']);
		
	$old_value[]=$old_pass1;	$new_value=$new_pass1;	$field_name='password';  $table='account';
	//date_default_timezone_set('Asia/Calcutta'); 		$date=date("Y-m-d H:i:s");

	//$query = "SELECT password from account WHERE password='$old_pass1' AND account_id=$local_account_id";
	if($user_type=="raw_milk" || $user_type=='substation' || $user_type=="plant_raw_milk" || $user_type=="hindalco_invoice" ){
        
            $query = "SELECT password from account WHERE password='$old_pass1' AND account_id=$account_id";
        }
        else
        {
            $query = "SELECT password from account WHERE password='$old_pass1' AND account_id=$local_account_id";
        }
        //echo "query=".$query;
	$result = mysql_query($query);	$numrows = mysql_num_rows($result);	 
  
	if($numrows>0)
	{  
		$msg = track_table($local_account_id,$table,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);	
		if($msg=="success")
		{
			//date_default_timezone_set('Asia/Calcutta'); // add conversion based on the time zone of user
			$date=date("Y-m-d H:i:s");	
			//$query="UPDATE account SET password='$new_pass1',edit_id='$account_id',edit_date='$date' WHERE password='$old_pass1' and account_id=$local_account_id";
			if($user_type=="raw_milk" || $user_type=='substation' || $user_type=="plant_raw_milk" || $user_type=="hindalco_invoice" ){
			$query="UPDATE account SET password='$new_pass1',edit_id='$account_id',edit_date='$date' WHERE password='$old_pass1' and account_id=$account_id";
			//echo$query;
                        }
                        else
                        {
                            $query="UPDATE account SET password='$new_pass1',edit_id='$account_id',edit_date='$date' WHERE password='$old_pass1' and account_id=$local_account_id";
                        }
                        $result2=mysql_query($query,$DbConnection);	
			if($result2)
			{
				$message="<center><br><br><FONT color=\"blue\"><strong>Password Changed Successfully</strong></font></center>";$result_status="success";
			}
			else
			{
				$message="<center><br><br><FONT color=\"blue\"><strong>Unable to Change Password due to some server problem!</strong></font></center>";
			}
		}
		else
		{
			$message=$msg;
		}
	}
	else
	{
		$message="<center>Sorry! Unable to process request.</center>";
	}
	
	echo $message;

	if($result_status=="success")
	{
		unset($root);
		unset($final_group_array);
		include_once("get_group.php");
		include_once('tree_hierarchy.php');
		$group_cnt=0;		
		$final_group_array=GetGroup_3($root,$DbConnection);
		$_SESSION['final_group_array'] = $final_group_array;
	}					
	//echo'<center><a href="#" onclick="javascript:setting_account_detail(\'src/php/setting_password_choose_account.php\',\'first_stage\');" class="menuitem">&nbsp;<b>Back</b></a></center>';
        if($user_type=="raw_milk" || $user_type=='substation' || $user_type=="plant_raw_milk" || $user_type=="hindalco_invoice" )
            {
                echo'<center><a href="#" onclick="javascript:setting_account_detail(\'src/php/main_usertype_setting.php\',\'first_stage\');" class="menuitem">&nbsp;<b>Back</b></a></center>'; 
            }
            else
            {
           echo'<center><a href="#" onclick="javascript:setting_account_detail(\'src/php/setting_password_choose_account.php\',\'first_stage\');" class="menuitem">&nbsp;<b>Back</b></a></center>'; 
            }

?>
        
