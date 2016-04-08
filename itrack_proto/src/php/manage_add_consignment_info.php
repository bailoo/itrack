<?php
	include_once('Hierarchy.php');		
	include_once('util_session_variable.php');	
	include_once('util_php_mysql_connectivity.php'); 
	include_once('user_type_setting.php');
	$root=$_SESSION['root'];	
	$DEBUG=0;
	echo'<div style="height:2px"> </div>';
	$check_status='checked';
	include_once('manage_consignment_info.php');
	echo"<form name='manage'>";
	echo'<div id="portal_vehicle_information">';
        include_once('consignment_tree_information.php');	
	include_once('manage_consignment_radio_account.php');
	$start_date=date("Y/m/d 00:00:00");	
	$end_date=date("Y/m/d H:i:s");	
	echo "</div>";
	echo'<div style="height:2px"> </div>
				<table border="0" class="manage_interface" align="center">
					<tr>
						<td valign="top">
							Vehicle Name
						</td>
						<td>:</td>
						<td colspan=2>';						
						echo'<select id="vehicle_imei_name">
								<option value="select">
									Select
								</option>									
							</select>';						
					echo'</td>
					</tr>
					<tr>
						<td valign="top">
							From Place
						</td>
						<td>:</td>
						<td colspan=2>';						
						echo'<input type="text" id="from_place">';
					echo'</td>
					</tr>					
					<tr>
						<td valign="top">
							To Place
						</td>
						<td>:</td>
                                               
						<td colspan=2>
							<input type="text" id="to_place">
						</td>
					</tr>
					<tr>
						<td valign="top">
							Consignee Name
						</td>
						<td>:</td>
						<td colspan=2>
							<input type="text" id="consignee_name">
						</td>
					</tr>                       
                                      <tr>
						<td valign="top">
							Start Date 
						</td>
						<td>:</td>
						<td colspan=2>
							<input type="text" id="date1" name="end_date" value="'.$start_date.'" size="10" maxlength="19">					
							<a href=javascript:NewCal("date1","yyyymmdd",true,24)>
								<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
							</a>
						</td>
					</tr>
					
					<tr>
						<td valign="top">
							End Date
						</td>
						<td>:</td>
						<td colspan=2>
							<input type="text" id="date2" name="end_date" value="'.$end_date.'" size="10" maxlength="19">					
							<a href=javascript:NewCal("date2","yyyymmdd",true,24)>
								<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
							</a>
						</td>
					</tr>
                    
					<tr>
						<td valign="top">
							Email
						</td>
						<td>:</td>
						<td colspan=2>
                           <input type="text" id="email" name="email" onchange="javascript:check_email();">							
						</td>
					</tr>
					
					<tr>
						<td valign="top">
							Remark
						</td>
						<td>:</td>
						<td colspan=2>
                           <input type="text" id="remark">							
						</td>
					</tr>
						
				</table>			
				<center>
					<input type="button" Onclick="javascript:action_manage_consignment_info(\'add\',this.form)" value="Add Consignment" id="enter_button">
				</center>		
			</form>';
		include_once('manage_loading_message.php');
		
?>
