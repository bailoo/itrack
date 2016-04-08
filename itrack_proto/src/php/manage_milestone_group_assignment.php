<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	//include_once('manage_hierarchy_header1.php');
	$root=$_SESSION['root'];
	$common_id1=$_POST['common_id'];
	echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';
	echo"<br>			
			<form name='manage1' method='post'>
				<center>
				<table border=0 cellspacing=0 cellpadding=0 class='module_left_menu'>
					<tr>
						<td colspan='3'>
							&nbsp;&nbsp;<INPUT TYPE='checkbox' name='all_vehicle' onclick='javascript:select_all_assigned_vehicle(this.form);'>
							<font size='2'>Select All</font>"."												
						</td>																														
					</tr>";
					get_user_group($root,$common_id1);
			echo"</table>

				<br>
				<table border=0 cellspacing=0 cellpadding=0 class='module_left_menu' align='center'>
					";
												
					$query="SELECT * from milestone where user_account_id='$common_id1' AND status='1'";
					//echo "query=".$query."<br>";
					$result=mysql_query($query,$DbConnection);
					$row_result=mysql_num_rows($result);		
					if($row_result!=null)
					{
						while($row=mysql_fetch_object($result))
						{									
							$ms_id=$row->milestone_serial;
							$ms_name=$row->milestone_name;
						echo"<tr>
								<td>
									&nbsp<INPUT TYPE='radio' name='geo_id' VALUE='$ms_id'>
									<font color='blue' size='2'>".$ms_name."&nbsp;&nbsp;&nbsp;</font>"."												
								</td>																														
							</tr>";
						}
					}
					else
					{
						echo"<font color='blue' size='2'>NO MILESTONE FOUND FOR THIS ACCOUNT</font>";
					}
						echo"</td>";
					echo"</tr>";
			   echo'</table>
				<br>
					<input type="button" id="enter_button" name="enter_button" Onclick="javascript:return action_manage_milestone(\'assign\')" value="Assign">&nbsp;<input type="reset" value="Cancel">
				<br><a href="javascript:show_option(\'manage\',\'milestone\');" class="back_css">&nbsp;<b>Back</b></a>
				</center>
			</form>';	

			function common_function_for_group($group_id,$group_name,$option_name)
			{	
				//$td_cnt++;
				global $td_cnt;
				if($td_cnt==1)
				{
					echo'<tr>';
				}

				echo'<td align="left">&nbsp;<INPUT TYPE="checkbox"  name="group_id[]" VALUE="'.$group_id.'"></td>
					<td class=\'text\'>&nbsp;<font color="darkgreen">'.$group_name.'</font>&nbsp;('.$option_name.')
					</td>';
			
				if($td_cnt==3)
				{ 
					echo'</tr>';
				}

			}

			function get_user_group($AccountNode,$account_id)
			{
				global $groupid;
				global $group_cnt;
				global $td_cnt;
				global $DbConnection;
				if($AccountNode->data->AccountID==$account_id)
				{
					$td_cnt =0;
					for($j=0;$j<$AccountNode->data->GroupCnt;$j++)
					{			    
						$group_id = $AccountNode->data->GroupID[$j];
						$group_name = $AccountNode->data->GroupName[$j];

						if($group_id!=null)
						{
							for($i=0;$i<$group_cnt;$i++)
							{
								if($groupid[$i]==$group_id)
								{
									break;
								}
							}			
							if($i>=$group_cnt)
							{
								$vehicleid[$group_cnt]=$group_id;
								$group_cnt++;
								$td_cnt++;
								$query="SELECT group_id FROM milestone_assignment WHERE group_id='$group_id' AND status=1";
								$result=mysql_query($query,$DbConnection);
								$num_rows=mysql_num_rows($result);
								if($num_rows==0)
								{							
									common_function_for_group($group_id,$group_name,$AccountNode->data->AccountGroupName);
								}
								if($td_cnt==3)
								{
									$td_cnt=0;
								}
							}
						}
					}
				}
				$ChildCount=$AccountNode->ChildCnt;
				for($i=0;$i<$ChildCount;$i++)
				{ 
					get_user_group($AccountNode->child[$i],$account_id);
				}
			}

			
?>  