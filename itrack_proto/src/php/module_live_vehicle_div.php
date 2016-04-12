<?php 
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');

for($k=0;$k<$size_feature_session;$k++)
{
	//$feature_id_session[$k];
	if($feature_name_session[$k] == "station")
	{
	  $flag_station = 1;		  
	  break;
	}
	  //echo "<br>feature_name=".$feature_name_session[$k];
}
?>

<div id="blackout_4"> </div>
	<div id='divpopup_4'>
		<?php
		if($flag_station)
		{			
			echo '<center>'
                    . '<input type="radio" name="live_opt" value="1" checked onclick="javascript:show_live_div();">'
                                . '<strong>Vehicle</strong>'
                                . '&nbsp;&nbsp;'
                                . '<!--<input type="radio" name="live_opt" value="2" onclick="javascript:show_live_div();">'
                                . '<strong>Route</strong>--></center>';
			//echo '<center><div id="show_route_opt" style="display:none;"><table rules=all style="font-size:10px;" border=1><tr><td><input type="radio" name="route_opt" value="1" onclick="javascript:show_all_routes();"><strong>Evening</strong>&nbsp;&nbsp;<input type="radio" name="route_opt" value="2" onclick="javascript:show_all_routes();"><strong>Morning</strong></td></tr></table></div></center>';
		}
		?>
		<table width="100%" cellspacing=0 cellpadding=0>
			<tr>
				<td>
					<table align='center' border="0" style='font-family: arial, sans-serif; font-size:11px;font-weight:bold' width="100%" cellspacing=2 cellpadding=2>
						 <tr>
							<td>
								&nbsp;&nbsp;Vehicle List
							</td>
							<td align='right'>  
								<a href='#' onclick='javascript:close_popup()'>
									<img src="images/close.png" style="border:none;">&nbsp;
								</a>
							</td>
						</tr> 						
					</table> 
				</td>
			</tr>
			<?php 
				include('module_refresh.php'); 
			?> 
			<tr id="sel_all_vehicle">
				<td>					
					<table align="center">
						<tr>
							<td valign="top">
								<input type='checkbox' name='all' id ='live_all_vehicle' value='1' onClick='javascript:select_all_vehicles();'>
							</td>
							<td valign="top">
								<table style='font-family: arial, sans-serif; font-size:10px;font-weight:bold' cellspacing=0 cellpadding=0>
									<tr>
										<td height="5px">
										</td>
										<td>
											Select All
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr id="sel_all_route" style="display:none;">
				<td>					
					<table align="center">
						<tr>
							<td valign="top">
								<input type='checkbox' name='all_route' id ='live_all_route' value='1' onClick='javascript:select_all_routes();'>
							</td>
							<td valign="top">
								<table style='font-family: arial, sans-serif; font-size:10px;font-weight:bold' cellspacing=0 cellpadding=0>
									<tr>
										<td height="5px">
										</td>
										<td>
											Select All
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>						
			<tr valign="top">
				<td align="center">
					<div id="show_vehicle" style="height:200px;overflow:auto;">
						<br>
						<font color="#034A24">
							Vehicle filtering may take few minutes depending on number of vehicles.<br>processing please wait....
						</font>
					</div>  
					<?php
					if($flag_station)
					{
						echo '<div id="show_route_ev" style="width:300px;height:170px;overflow:auto;display:none;">						
							<font color="#034A24">';
								echo '<table>';
								
								$query_assigned = "SELECT DISTINCT (route_assignment2.route_name_ev),vehicle_assignment.device_imei_no,route_assignment2.vehicle_name FROM vehicle_assignment,route_assignment2,vehicle WHERE route_assignment2.user_account_id='$account_id' AND vehicle.vehicle_name=route_assignment2.vehicle_name AND vehicle.vehicle_id = vehicle_assignment.vehicle_id AND route_assignment2.status=1 AND NOT(route_assignment2.route_name_ev='') AND vehicle_assignment.status=1 ORDER BY route_assignment2.route_name_ev ASC";
								//echo $query_assigned;
								/*$query_assigned = "SELECT vehicle_assignment.device_imei_no route_assignment2.route_name ".
								 "FROM vehicle_assignment,route_assignment2 WHERE route_assignment2.user_account_id='$account_id' AND ".
								 "route_assignment2.status=1 ORDER BY route_assignment2.route_name ASC";*/
								//echo $query_assigned;
								$result_assigned = mysql_query($query_assigned,$DbConnection);

								$route_str = "";
								while($row=mysql_fetch_object($result_assigned))
								{
									//$route = $row->vehicle_name;
									$imei = $row->device_imei_no;																	
									$route = $row->route_name_ev;
									$vname = $row->vehicle_name;									
									$route_str = $route.":".$imei;	
									//echo "route_str=".$route_str."<br>";
								
									echo '<tr>
										<td style="font-size:10px;"><input type="checkbox" name="route_ev[]" value="'.$route_str.'"/>&nbsp;'.$route.'&nbsp:&nbsp;<font color="green">'.$vname.'</font></td>';																		
									echo'</tr>';									
								}		
								echo'</table>
							</font>
						</div>'; 	
						
						echo '<div id="show_route_mor" style="height:170px;overflow:auto;display:none;">						
							<font color="#034A24">';
								echo '<table>';
								
								$query_assigned = "SELECT DISTINCT vehicle_assignment.device_imei_no,route_assignment2.route_name_mor,route_assignment2.vehicle_name FROM vehicle_assignment,route_assignment2,vehicle WHERE route_assignment2.user_account_id='$account_id' AND vehicle.vehicle_name=route_assignment2.vehicle_name AND vehicle.vehicle_id = vehicle_assignment.vehicle_id AND route_assignment2.status=1 AND NOT(route_assignment2.route_name_mor ='') AND vehicle_assignment.status=1 ORDER BY route_assignment2.route_name_mor ASC";
								//echo $query_assigned;
								/*$query_assigned = "SELECT vehicle_assignment.device_imei_no route_assignment2.route_name ".
								 "FROM vehicle_assignment,route_assignment2 WHERE route_assignment2.user_account_id='$account_id' AND ".
								 "route_assignment2.status=1 ORDER BY route_assignment2.route_name ASC";*/
								//echo $query_assigned;
								$result_assigned = mysql_query($query_assigned,$DbConnection);

								$route_str = "";
								while($row=mysql_fetch_object($result_assigned))
								{
									//$route = $row->vehicle_name;
									$imei = $row->device_imei_no;																	
									$route = $row->route_name_mor;
									$vname = $row->vehicle_name;
									$route_str = $route.":".$imei;							
								
									echo '<tr>
										<td style="font-size:10px;"><input type="checkbox" name="route_mor[]" value="'.$route_str.'"/>&nbsp;'.$route.'&nbsp:&nbsp;<font color="green">'.$vname.'</font></td>
									</tr>';								
								}		
								echo'</table>
							</font>
						</div>'; 							
					}
					?>	
					
				</td>
			</tr>
			<TR>
				<TD align="center" style='font-family: arial, sans-serif; font-size:10px;font-weight:bold'><BR><BR><input type="radio" name="mode" value="map" CHECKED>Map Mode&nbsp;<input type="radio" name="mode" value="text">Text Mode</TD>
			</TR>
			<TR>
				<TD><BR></TD>
			</TR>
			<TR>			
				<TD align="center">
					<input type="button" Onclick="javascript:filter_live_vehicle(this.form,'js1')" value="Enter"> &nbsp;
					<input type="reset" value="Clear">		
				</td>
			</tr>
			<TR>
				<TD><BR></TD>
			</TR>			
		</table>
</div>
  
