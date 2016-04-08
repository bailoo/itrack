<?php
	include_once("report_moto_hierarchy_vehicle.php");
	$account_id_local1 = $_POST['account_id_local'];	
	$vehicle_display_option1 = $_POST['vehicle_display_option'];	
	$options_value1 = $_POST['options_value'];
	$options_value2=explode(",",$options_value1);			
	$option_size=sizeof($options_value2);
	$option_string="";  
	$function_string='get_'.$vehicle_display_option1.'_vehicle';   
	echo'<center>
			<table border=0 width = 100% cellspacing=2 cellpadding=0>
				<tr>
					<td height=10 class="report_heading" align="center">'.$title.'</td>
				</tr>
			</table>													
			<form  method="post" name="thisform">							
				<br>
				<fieldset class="report_fieldset">
					<legend>
						Select Vehicle
					</legend>
					<table border=0  cellspacing=0 cellpadding=0  width="100%">
						<tr>
							<td align="center">							
								<div style="overflow: auto;height: 150px; width: 650px;" align="center">
									<table border=0 cellspacing=0 cellpadding=0 align="center" width="100%">';											            
											$function_string($account_id_local1,$options_value1);																				
										echo'
									</table>
								</div>
							</td>
						</tr>
					</table>
				</fieldset>
				<br>      
				<table border=0 cellspacing=0 cellpadding=3 align="center">											
					<tr>
						<td align="center" colspan=2>
							<input type="button" onclick="javascript:action_report_moto_geocode_management(this.form);" value="Enter">
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
					 
