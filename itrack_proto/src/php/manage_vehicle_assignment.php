<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include_once('manage_hierarchy_header.php');
	include("user_type_setting.php");
	
	$account_id_local1 = $_POST['account_id_local'];	$vehicle_display_option1 = $_POST['vehicle_display_option'];	
	$options_value1 = $_POST['options_value'];
	
	if($options_value1!="all")
	{	
		$options_value2=explode(",",$options_value1);		$option_size=sizeof($options_value2);		
	}
	echo "assign##";
	echo'<input type="hidden" id="local_account_id" value='.$account_id_local1.'>';	
	echo"<br>
			<center>
				<table border=0>
					<tr>
						<td>
							<fieldset class='assignment_manage_fieldset'>
								<legend>";
								
                	if($report_type=='Person')
                    echo"<strong>Person Assignment</strong>";                
                	else
                    echo"<strong>Vehicle Assignment</strong>";
								
                echo"</legend>
								<form name='manage1' method='post'>
									<table border=0 cellspacing=0 cellpadding=0 class='module_left_menu'>";
								echo'<tr>
										<td><input type="checkbox" name="all" onclick="javascript:all_check(this.form);">Select All</td>
									</tr>';
									if($options_value1!="all")
									{
										for($i=0;$i<$option_size;$i++)
										{
									echo'<tr>
											<td>'.$options_value2[$i].'</td>
										</tr>';	
											$query="SELECT vehicle_id,vehicle_name from vehicle WHERE $vehicle_display_option1='$options_value2[$i]' AND status='1' AND vehicle_id IN(SELECT vehicle_id FROM vehicle_grouping where status=1 AND account_id='$account_id_local1') AND vehicle_id NOT IN (SELECT vehicle_id FROM vehicle_assignment where status=1)";
											//echo "query=".$query;
											$result=mysql_query($query,$DbConnection);
											$flag=0;
											$vehicle_cnt=0;
											while($row=mysql_fetch_object($result))
											{
													//echo "vehicle_ids=".$row->vehicle_id;
											echo'<tr>
													<td><input type="checkbox" name="manage_id[]" value="'.$row->vehicle_id.'">'.$row->vehicle_name.'</td>
												</tr>';									
											}									
										}
									}
									else if($options_value1=="all")
									{
										$query="SELECT vehicle_id,vehicle_name from vehicle WHERE account_id='$account_id_local1' AND status='1' AND vehicle_id NOT IN(SELECT vehicle_id FROM vehicle_grouping where status=1) AND vehicle_id IN (SELECT vehicle_id FROM vehicle_assignment where status=1)";
										//echo "query=".$query;
										$result=mysql_query($query,$DbConnection);
										$flag=0;
										$vehicle_cnt=0;
										while($row=mysql_fetch_object($result))
										{
										//echo "vehicle_ids=".$row->vehicle_id;
										echo'<tr>
												<td><input type="checkbox" name="manage_id[]" value="'.$row->vehicle_id.'">'.$row->vehicle_name.'</td>
											</tr>';									
										}																	
									}					
						echo '</table>
							</fieldset>
						</td>
					</tr>
				</table>
			<br>				
		<center><input type="button" name="enter_button" id="enter_button" Onclick="javascript:return action_manage_vehicle(manage1,\'assign\')" value="Assign">&nbsp;
	</form>';
	include_once('manage_loading_message.php');
?>