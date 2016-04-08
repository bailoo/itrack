<?php
	include_once("report_hierarchy_header.php");
	$account_id_local1 = $_POST['account_id_local'];	
	echo "<input type='hidden' id='selected_account_id' value=".$account_id_local1.">";
	$vehicle_display_option1 = $_POST['vehicle_display_option'];	
	echo "<input type='hidden' id='s_vehicle_display_option' value=".$vehicle_display_option1.">";
	$options_value1 = $_POST['options_value'];
	echo "<input type='hidden' id='selected_options_value' value='".$options_value1."'>";  

	$options_value2=explode(",",$options_value1);			
	$option_size=sizeof($options_value2);
	$option_string="";  
  
	$function_string='get_'.$vehicle_display_option1.'_vehicle';   
  
 echo'                  
  <center>
			<table border=0 width = 100% cellspacing=2 cellpadding=0>
				<tr>
					<td height=10 class="report_heading" align="center">Consignment Report</td>
				</tr>
			</table>			
														
			<form  method="post" name="thisform">							
			<br>								

	<fieldset class="report_fieldset">
		<legend>Select Vehicle</legend>	
					
			<table border=0  cellspacing=0 cellpadding=0  width="100%">
				<tr>
					<td align="center">							
						<div style="overflow: auto;height: 150px; width: 650px;" align="center">
							<table border=0 cellspacing=0 cellpadding=0 align="center" width="100%">';						
                
								 echo'<tr><td height="10px" align="center" colspan="6" class=\'text\'>&nbsp;<input type=\'checkbox\' name=\'all\' value=\'1\' onClick=\'javascript:select_all_vehicle(this.form);\'>&nbsp;&nbsp;Select All</td></tr>';                 
                 $function_string($account_id_local1,$options_value1);
                														
								echo'
							</table>
						</div>
					</td>
				</tr>
			</table>
      </fieldset> <br>
      ';						
			
			
														
			//date_default_timezone_set('Asia/Calcutta');
			$start_date=date("Y/m/d 00:00:00");	
			$end_date=date("Y/m/d H:i:s");	
			
			echo'
                          <table border=0 cellspacing=0 cellpadding=3 align="center">	
				<tr>
					<td  class="text"><b>Consignment Type </b></td>
                                        </td>
                                        <td>
						<input type="checkbox" id="consignment_type_all" onclick="common_select_all(this.form,\'consignment_type_all\',\'consignment_type[]\');">
                                        </td>
                                        <td>
						All
                                        </td>
					<td>
						<input type="checkbox" name="consignment_type[]" value="live">
                                        </td>
                                        <td>
						Live
                                        </td>
                                        <td>
						<input type="checkbox" name="consignment_type[]" value="expired">
                                        </td>
                                        <td>
						Expired
                                        </td>							
				</tr>										
			</table>
			<table border=0 cellspacing=0 cellpadding=3 align="center">	
				<tr>
					<td  class="text"><b>Select Duration : </b></td>
					<td>
						<table>
							<tr>
								<td  class="text">	</td>
								<td class="text">
									Start Date
															
							<input type="text" id="date1" name="start_date" value="'.$start_date.'" size="10" maxlength="19">
					
										<a href=javascript:NewCal_SD("date1","yyyymmdd",true,24)>
											<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
										</a>
											&nbsp;&nbsp;&nbsp;End Date

							<input type="text" id="date2" name="end_date" value="'.$end_date.'" size="10" maxlength="19">
					
										<a href=javascript:NewCal("date2","yyyymmdd",true,24)>
											<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
										</a>
													
								</TD>
							</tr>
						</table>
					<td>
				</tr>										
			</table>			
			</fieldset>
			
      <br>
			<table border=0 cellspacing=0 cellpadding=3 align="center">						
											
				<tr>
					<td align="center" colspan=2>
						<input type="button" onclick="javascript:action_report_consignment_info(this.form);" value="Enter">
							&nbsp;
						<input type="reset" value="Clear">
					</td>
				</tr>
			</table>		
		</form>       
    <div align="center" id="loading_msg" style="display:none;"><br><font color="green">loading...</font></div>		
    <center>
    ';
?>						
					 
