<?php 
	include_once('Hierarchy.php');		include_once('util_session_variable.php');		include_once('util_php_mysql_connectivity.php'); include_once('user_type_setting.php');
	$root=$_SESSION['root'];  
	$js_function_name="report_select_by_entity";
	$file_name="src/php/report_assigned_vehicle_option.php";
	$filename1=$_POST['filename']; 
	$title1=$_POST['title']; 	
	echo"<input type='hidden' id='report_type' value='".$title1."'>";
	$acc_str="";
	function GetMiningGroup($AccountNode)
	{
		global $DbConnection;
		global $acc_str;
		$account_id_local=$AccountNode->data->AccountID;
		$acc_str=$acc_str.$account_id_local.",";
		$ChildCount=$AccountNode->ChildCnt;
		for($i=0;$i<$ChildCount;$i++)
		{
			GetMiningGroup($AccountNode->child[$i]);
		}
		return $acc_str; 		
	}
	
	$accounts_str=GetMiningGroup($root);
	$accounts_str1=substr($accounts_str,0,-1);
	//echo "filename1=".$filename1." title=".$title1; 
  echo'<form name="report1">';
			//echo "mining_user_type=".$mining_user_type;
			echo'<div class="report_div_height"></div>
					<center><div class="report_title"><b>'.$title1.'</b></div></center>'; 		
				echo"<table align='center'>
						<tr>
							<td>Group</td>
							<td>
								<select id='group_id_local' onchange='javascript:show_report_vehicles(this.value)'>
									<option value='select'>Select</option>";
                  	$query="SELECT DISTINCT group_id,group_name from `group` where group_id IN (SELECT group_id from account WHERE account_id in ($accounts_str1) AND status=1 and group_id IN (SELECT group_id from milestone_assignment WHERE status=1)) and status=1";
                    $result=mysql_query($query);                	
                		while($row=mysql_fetch_object($result))
                		{  	
                  		echo"<option value=".$row->group_id.">".$row->group_name."</option>";
                		}								
							echo"</select></td>
						</tr>
					</table>
					<div align='center' id='mining_vehicle_display' style='display:none;'></div>";						
			
	echo'</form>';
?>