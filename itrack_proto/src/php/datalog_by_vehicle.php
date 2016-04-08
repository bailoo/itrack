<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include("user_type_setting.php");
	
	$account_id_local1 = $_POST['account_id_local'];	
	$vehicle_display_option1 = $_POST['vehicle_display_option'];
	$options_value1 = $_POST['options_value'];
	$function_name="display_option_vehicle";
	$file_name="src/php/report_show_option_vehicle_datalog.php";
	//echo "account_id_local1=".$account_id_local1."	vehicle_display_option1=".$vehicle_display_option1."options_value1".$options_value1;
	echo"<form name='report'>";
	echo'<input type="hidden" id="account_id_local1" value="'.$account_id_local1.'">';
	echo'<input type="hidden" id="vehicle_display_option" value="'.$vehicle_display_option1.'">';
	echo'<input type="hidden" id="options_value" value="'.$options_value1.'">';
	echo'<center>
			<br>
            <input type="hidden" name="id" value="3"/>					
				<table border=0 cellpadding=3 align="center">
					<tr valign="top">
						<td align="right" class="text">
							<input type="radio" name="option1" value="1" onClick="javascript:'.$function_name.'(\''.$file_name.'\',\'vehicle_name\')">&nbsp;Select by '.$report_type.' Name
						</td>							
						<td align="right" class="text">
							<input type="radio" name="option1" value="2" onClick="javascript:'.$function_name.'(\''.$file_name.'\',\'device_serial\')">&nbsp;Select by Device Serial
						</td>
						<!--<td align="right" valign="top" class="text">
							<input type="radio" name="option1" value="4" onClick="javascript:'.$function_name.'(\''.$file_name.'\',\'sim_no\')">&nbsp;Select by PhoneNo
						</td>-->	
					</tr>													
				</table>
				<br>
				<div id="portal_vehicle_information"></div>
				<br>
				<a href="javascript:show_option(\'report\',\'common_prev\');" class="back_css">&nbsp;<b>Back</b></a>
		</center>
	</form>'; 							

