<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$root = $_SESSION['root'];
	$DEBUG=0; 
	$final_group_array=array(array());
	$post_action_type = $_POST['action_type'];
	$post_account_id_local = $_POST['account_id_local'];
	
	//echo "post_account_id=".$post_account_id;
	$post_date=$_POST['date'];
	$post_imei=$_POST['imei'];
	$post_load=$_POST['load'];
  $post_remark = trim($_POST['remark']);
	$load_cell_id_local1 = trim($_POST['load_cell_id_local']);  ///////not for add group action
	
	
	if($post_action_type =="add")                     ///////// ADD
	{ 	
			$flag=0;  $result_response=1;
 
			$query1="SELECT MAX(sno) as max_count from `load_cell`";
			//echo"query=".$query1;
			if($DEBUG) print_query($query1);
			$result1=mysql_query($query1,$DbConnection);
			$max_no = mysql_fetch_object($result1);
			$max_no1=$max_no->max_count;			

			if($max_no1=="")
			{
				$load_cell_id_local="1";
			}
			else
			{ 
				$max_no1=$max_no1+1;   	  				
			}
			$query2="INSERT INTO `load_cell`(load_cell_id,datetime,imei,load,user_account_id,create_id,create_date,status,remark) VALUES('$load_cell_id_local','$post_date','$post_imei','$post_load','$post_account_id_local','$account_id','$date','1','$post_remark')";  
			echo $query;
      if($DEBUG) print_query($query2);
			$result2=mysql_query($query2,$DbConnection);
			if($result2){$flag=1;}

			if($flag==1)
			{$message = "<center><br><FONT color=\"green\"><strong>Load Cell Data Added Successfully </font></strong></font></center>";$result_status='success';}   
			else
			{$message = "<center><br><FONT color=\"red\"><strong>Sorry! Unable to process request.</strong></font></center>";}      
	}		
	
	else if($post_action_type == "edit")                 ////////// EDIT
	{	
			$query="UPDATE `load_cell` SET datetime='$post_date',datetime='$post_date',imei='$post_imei',load='$post_load',edit_id='$account_id',edit_date='$date',remark='$remark' WHERE load_cell_id='$load_cell_id_local1'";  
			echo $query;
      $result = mysql_query($query, $DbConnection);    

			if($DEBUG) print_query($query);

			if($result)
			{$message = "<center><br><br><FONT color=\"green\"><strong>Load Cell Detail Updated Successfully</strong></font></center>";$result_status='success';}
			else 
			{$message = "<center><br><br><FONT color=\"green\"><strong>Unable to Update Load Cell Detail</strong></font></center>";} 
	}
	
	else if($post_action_type == "delete")              //////////// DELETE
	{
			$query = "UPDATE `load_cell` SET status='0',edit_id='$account_id',edit_date='$date' WHERE load_cell_id='$group_id_local1'";
			if($DEBUG) print_query($query);
			$result=mysql_query($query, $DbConnection);   
			if($result)
			{$message="<center><br><br><FONT color=\"red\"><strong>Selected Load Cell Data Deleted Successfully</strong></font></center>";$result_status='success';}
			else
			{
				$message="<center><br><br><FONT color=\"red\"><strong>Unable to Delete This Group</strong></font></center>";
			}
	}

	echo'<table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
			<tr>
				<td colspan="3" align="center"><b>'.$message.'</b></td>    
			</tr>
		</table><br>'; 
	echo'<center><a href="javascript:show_option(\'manage\',\'load_cell\');" class="menuitem">&nbsp;<b>Back</b></a></center>';
  //include_once("manage_action_message.php");
?>
        