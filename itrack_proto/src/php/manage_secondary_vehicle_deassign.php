<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include_once('manage_secondary_vehicle.php');
 echo'                  
  <center>
  	<form  method="post" name="manage1">';
	global $pageAction;
	$pageAction='deAssign';
		
			echo'<br>
				<table align="center">
					<tr>
						<td  class="text">	
							<input type="radio" name="shifTime" value="ZPMM" onclick="showSiftVehicleDeAssign(this.value);">
						</td>
						<td class="text">
						Morning Shift
						</TD>
						<td  class="text">	
							<input type="radio" name="shifTime" value="ZPME" onclick="showSiftVehicleDeAssign(this.value);">
						</td>
						<td class="text">
						Evening Shift
						</TD>
					</tr>
				</table>
				<div id="shitVehicleBlock" style="display:none"></div>
				<br>
				<center><input type="button" value="De-Assign" onclick="actionManageSecondaryVehicle(\'deassign\')">
		</form>
    <center>
    ';
?>						
					 
