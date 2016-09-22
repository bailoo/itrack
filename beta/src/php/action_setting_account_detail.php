<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');

	$flag=0;
	$local_account_id=$_POST['setting_account_id'];
	
	$post_user_name1=$_POST['user_name'];	$post_address_1=$_POST['address1'];		$post_address_2=$_POST['address2'];
	$post_city1=$_POST['city'];				$post_state1=$_POST['state'];			$post_country1=$_POST['country'];
	$post_zip1=$_POST['zip'];				$post_phoneno1=$_POST['phoneno'];		$post_email_id_1=$_POST['email'];	

	$query="SELECT * from account_detail WHERE account_id=$local_account_id";
	//echo "query=".$query;
	$result=mysql_query($query,$DbConnection);
	$row=mysql_fetch_object($result);
	$user_name=$row->name;
	$email=$row->email;			$address1=$row->address1;		$address2=$row->address2;
	$city=$row->city;			$state=$row->state;				$country=$row->country;
	$zip=$row->zip;				$phone=$row->phone;				
	
	if($user_name!=$post_user_name)
	{
		$old_value[]=$user_name;	$new_value[]=$post_user_name;   $field_name[]="name";
	}
	if($email!=$post_email_id_1)
	{
		$old_value[]=$email;		$new_value[]=$post_email_id_1;	$field_name[]="email";
	}
	if($address1!=$post_address_1)
	{
		$old_value[]=$address1;		$new_value[]=$post_address_1;	$field_name[]="address1";
	}
	if($address2!=$post_address_2)
	{
		$old_value[]=$address2;		$new_value[]=$post_address_2;	$field_name[]="address2";
	}
	if($city!=$post_city1)
	{
		$old_value[]=$city;			$new_value[]=$post_city1;		$field_name[]="city";
	}	
	if($state!=$post_state1)
	{
		$old_value[]=$state;		$new_value[]=$post_state1;		$field_name[]="state";
	}	
	if($country!=$post_country1)
	{
		$old_value[]=$country;		$new_value[]=$post_country1;	$field_name[]="country";
	}
	if($zip!=$post_zip1)
	{
		$old_value[]=$zip;			$new_value[]=$post_zip1;		$field_name[]="zip";
	}	
	if($phone!=$post_phone1)
	{
		$old_value[]=$email;		$new_value[]=$post_phone1;		$field_name[]="phone";
	}
	
	$table="account_detail";
	$msg = track_table($local_account_id,$table,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
	
	if($msg=="success")
	{
		//date_default_timezone_set('Asia/Calcutta'); // add conversion based on the time zone of user
		$date=date("Y-m-d H:i:s");	
		$query="UPDATE account_detail set name='$post_user_name1',email='$post_email_id_1',address1='$post_address_1',address2='$post_address_2',city='$post_city1',state='$post_state1',country='$post_country1',zip='$post_zip1',phone='$post_phoneno1',edit_id='$account_id',edit_date='$date' where account_id=$local_account_id";
		$result2=mysql_query($query,$DbConnection);	
		if($result2)
		{
			$message="<center><br><br><FONT color=\"blue\"><strong>Account Detail Updated Successfully</strong></font></center>";$result_status="success";
		}
		else
		{
			$message="<center><br><br><FONT color=\"blue\"><strong>Unable to Update Account Detail due to some server problem!</strong></font></center>";
		}
	}
	else
	{
		$message=$msg;
	}
	//print_query($query);

	//echo "result1=".$result1." result2=".$result2." result3=".$result3." result4=".$result4." result5=".$result5;
	

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
		
		//echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"2; URL=add_account.php\">";
	}include_once("util_account_detail.php");
        if($user_type=="raw_milk" || $user_type=='substation' || $user_type=="plant_raw_milk" || $user_type=="hindalco_invoice" )
        {
            
        }
        else
        {
	 echo'<center><a href="javascript:show_option(\'setting\',\'update_account_prev\');" class="back_css">&nbsp;<b>Back</b></a></center>';
        }
?>
        
