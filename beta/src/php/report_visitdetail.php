<?php
    echo"reportPrevPage##";
    include_once("report_hierarchy_header.php"); 
    $account_id_local1 = $_POST['account_id_local'];
    $vehicle_display_option1 = $_POST['vehicle_display_option'];
    $options_value1 = $_POST['options_value'];  
    $options_value2=explode(",",$options_value1);			
    $option_size=sizeof($options_value2);
    $option_string="";  
  
    $function_string='get_'.$vehicle_display_option1.'_vehicle'; 
  //$function_string='get_all_persons';   
  
 echo'                  
  <center>
			<table border=0 width = 100% cellspacing=2 cellpadding=0>
				<tr>
					<td height=10 class="report_heading" align="center">Visit Report</font></td>
				</tr>
			</table>			
														
			<form  method="post" name="thisform">							
			<br>								

	<fieldset class="report_fieldset">
		<legend>Select Person</legend>	
					
			<table border=0  cellspacing=0 cellpadding=0  width="100%">
				<tr>
					<td align="center">							
						<div style="overflow: auto;height: 150px; width: 650px;" align="center">
							<table border=0 cellspacing=0 cellpadding=0 align="center" width="100%">';						
                
								 echo'<tr><td height="10px" align="center" colspan="6" class=\'text\'>&nbsp;<input type=\'checkbox\' name=\'all\' value=\'1\' onClick=\'javascript:select_all_vehicle(this.form);\'>&nbsp;&nbsp;Select All</td></tr>';                 
                 $function_string($account_id_local1,$options_value1);
                // echo $function_string;
               // $function_string($account_id_local1);														
								echo'
							</table>
						</div>
					</td>
				</tr>
			</table>
      </fieldset> <br>
      ';						
			
			echo'<fieldset class="report_fieldset">';
			echo'<legend>Select display Option</legend>';	
			
			echo'<br>
      <!--
      <br><center>
       
      <SPAN STYLE="font-size: xx-small">Select Interval </SPAN>
      <select name="user_interval" id="user_interval">';
			echo '<option value="1">1</option>';

			echo '<option value="2">2</option>';

			echo '<option value="3">3</option>';																					
			
			echo '<option value="4">4</option>';
			
			echo '<option value="5">5</option>';
			
			echo '<option value="6">6</option>';
			
			echo '<option value="7">7</option>';
										
			echo '<option value="8">8</option>';
			
			echo '<option value="9">9</option>';

			echo '<option value="10">10</option>';

			echo '<option value="11">11</option>';

			echo '<option value="12">12</option>';							

			echo'</select>&nbsp;<SPAN STYLE="font-size: xx-small"> hr/hrs</SPAN></center><br> -->';
														
			//date_default_timezone_set('Asia/Calcutta');
			if($start_date=="" && $end_date=="")
			{
				$StartDate=date("Y/m/d 00:00:00");	
				$EndDate=date("Y/m/d H:i:s");
			}
			else
			{
				$StartDate=$start_date;	
				$EndDate=$end_date;
			}	
			
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
															
							<input type="text" id="date1" name="start_date" value="'.$StartDate.'" size="10" maxlength="19">
					
										<a href=javascript:NewCal_SD("date1","yyyymmdd",true,24)>
											<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
										</a>
											&nbsp;&nbsp;&nbsp;End Date

							<input type="text" id="date2" name="end_date" value="'.$EndDate.'" size="10" maxlength="19">
					
										<a href=javascript:NewCal("date2","yyyymmdd",true,24)>
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
						<input type="button" onclick="javascript:action_report_visitDetail(this.form);" value="Enter">
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
					 
