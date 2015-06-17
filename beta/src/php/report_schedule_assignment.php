<?php
	include_once("report_hierarchy_header.php");
	$account_id_local1 = $_POST['account_id_local'];	
  $vehicle_display_option1 = $_POST['vehicle_display_option'];	
  $options_value1 = $_POST['options_value'];
  
  $options_value2=explode(",",$options_value1);			
  $option_size=sizeof($options_value2);
	$option_string="";  
  
	$function_string='get_'.$vehicle_display_option1.'_vehicle';   
  
 echo'                  
  <center>
			<table border=0 width = 100% cellspacing=2 cellpadding=0>
				<tr>
					<td height=10 class="report_heading" align="center">Schedule Assignment Report</td>
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
			
			echo'<fieldset class="report_fieldset">
            <legend>Select display Option</legend>
             <center><br>';   														
			date_default_timezone_set('Asia/Calcutta');
			$start_date=date("Y/m/d");	
			$end_date=date("Y/m/d");	
			
			echo'
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
					
										<a href=javascript:NewCal_SD("date1","yyyymmdd",false,24)>
										<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
									</a>
											&nbsp;&nbsp;&nbsp;End Date

							<input type="text" id="date2" name="end_date" value="'.$end_date.'" size="10" maxlength="19">
					
										<a href=javascript:NewCal_SD("date2","yyyymmdd",false,24)>
										<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
									</a>
														
								</TD>

							<input type="hidden" name="rep_uid" value="'.$uid.'">																	
								</td>
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
						<input type="button" onclick="javascript:action_report_schedule_assignment(this.form);" value="Enter">
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
					 