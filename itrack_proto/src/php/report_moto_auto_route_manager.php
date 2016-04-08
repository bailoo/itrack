<?php
	include_once('util_session_variable.php');		
	include_once('util_php_mysql_connectivity.php');
	/*include_once("report_moto_hierarchy_vehicle.php");
	$account_id_local1 = $_POST['account_id_local'];	
	$vehicle_display_option1 = $_POST['vehicle_display_option'];	
	$options_value1 = $_POST['options_value'];
	$options_value2=explode(",",$options_value1);			
	$option_size=sizeof($options_value2);
	$option_string="";  
	$function_string='get_'.$vehicle_display_option1.'_vehicle'; */
	echo'<center>
			<br>
			<table border=0 width = 100% cellspacing=2 cellpadding=0>
				<tr>
					<td height=10 class="report_heading" align="center">'.$title.'</td>
				</tr>
			</table>													
			<form  method="post" name="thisform">							
				<br>			
				<table border=0 cellspacing=0 cellpadding=3 align="center">											
					<tr>
						<td align="left">
							Source Location Name
						</td>
						<td align="center">
							:
						</td>
						<td align="center">
							<input type="text" name="source_location_name">
						</td>
					</tr>
					<tr>
						<td align="left">
							Destination Location Name
						</td>
						<td align="center">
							:
						</td>
						<td align="left">
							<input type="text" name="destination_location_name">
						</td>
					</tr>
				</table>
				<br>      
				<table border=0 cellspacing=0 cellpadding=3 align="center">											
					<tr>
						<td align="center" colspan=2>
							<input type="button" onclick="javascript:action_report_moto_route_manager(this.form);" value="Enter">
								&nbsp;
							<input type="reset" value="Clear">
						</td>
					</tr>
				</table>		
			</form>       
			<div align="center" id="loading_msg" style="display:none;">			
				<font color="green">loading...</font>
			</div>		
    <center>';
?>						
					 
