<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');

	$local_account_id=$_POST['setting_account_id'];
	$feature1=$_POST['feature'];	
	$color_str1=$_POST['color_str'];
			
	$query = "SELECT * from color_setting WHERE feature_name='$feature1' AND user_account_id=$local_account_id AND status=1";
	//echo "query=".$query;
	$result = mysql_query($query);	$numrows = mysql_num_rows($result);	 
  
	if($numrows>0)
	{  
		//date_default_timezone_set('Asia/Calcutta'); // add conversion based on the time zone of user
		$date=date("Y-m-d H:i:s");	
		$query="UPDATE color_setting SET color_code='$color_str1',edit_id='$account_id',edit_date='$date' WHERE feature_name='$feature1' AND user_account_id=$local_account_id AND status=1";
		$result2=mysql_query($query,$DbConnection);	
	}
	else
	{
		$date=date("Y-m-d H:i:s");	
		$query="INSERT INTO color_setting(feature_name,color_code,user_account_id,create_id,create_date,status) values('$feature1','$color_str1','$local_account_id','$account_id','$date',1)";
		$result2=mysql_query($query,$DbConnection);					
	}
	//echo "Q=".$query;
	
	if($result2)
	{
		$message="<center><br><br><FONT color=\"green\"><strong>Live Color Setting Changed Successfully</strong></font></center>";$result_status="success";
	}
	else
	{
		$message="<center><br><br><FONT color=\"blue\"><strong>Unable to Change Color Setting due to some server problem!</strong></font></center>";
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
	echo'<center><a href="#" onclick="javascript:setting_account_detail(\'src/php/setting_color_prev.php\',\'first_stage\');" class="menuitem">&nbsp;<b>Back</b></a></center>';


?>
        
