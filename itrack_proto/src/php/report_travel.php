<?php
	include_once("report_hierarchy_header.php");
	include_once("user_type_setting.php");
	
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
  			<td height=10 class="report_heading" align="center">Travel Report</td>
  		</tr>
  	</table>

	
<form  method="post" name="thisform" action="src/php/action_report_travel.php" target="_blank">	
			<input type="hidden" name="vehicleserial_prev">			
			<br>
	<fieldset class="report_fieldset">';
	
		if($report_type=='Person')
      echo'<legend>Select Person</legend>';
    else
      echo'<legend>Select Vehicle</legend>';	

			echo'<table border=0  cellspacing=0 cellpadding=0  width="100%">
				<tr>
					<td align="center">							
						<div style="overflow: auto;height: 150px; width: 650px;" align="center">
							<table border=0 cellspacing=0 cellpadding=0 align="center" width="100%">';																							

 						  echo'<tr><td height="5px" align="center" colspan="6" class=\'text\'>&nbsp;<input type=\'checkbox\' name=\'all\' value=\'1\' onClick=\'javascript:select_all_vehicle(this.form);\'>&nbsp;&nbsp;Select All</td></tr>';                 
              $function_string($account_id_local1,$options_value1);							

							echo'</table>
						</div>
					</td>
				</tr>
			</table>
			 </fieldset>
     <fieldset class="report_fieldset">
  		<legend>Select display Option</legend>';								
			
	echo'<br><center><SPAN STYLE="font-size: xx-small"><strong>Threshold : </strong></SPAN><select name="threshold" id="threshold">';
	echo '<option value="5">0</option>';

	echo '<option value="5">5</option>';

	echo '<option value="10">10</option>';
								
	echo '<option value="15">15</option>';
	
	echo '<option value="20">20</option>';
	
	echo '<option value="25">25</option>';
	
	echo '<option value="30">30</option>';
	
	echo '<option value="35">35</option>';
								
	echo '<option value="40">40</option>';
	
	echo '<option value="45">45</option>';

	echo '<option value="50">50</option>';

	echo '<option value="55">55</option>';

	echo '<option value="60">60</option>';

	echo '<option value="90">90</option>';

	echo '<option value="120">120</option>';

	echo '<option value="150">150</option>';

	echo '<option value="180">180</option>';

	echo '<option value="210">210</option>';

	echo '<option value="240">240</option>';

	echo '<option value="270">270</option>';

	echo '<option value="300">300</option>';

	echo '<option value="330">330</option>';

	echo '<option value="360" selected>360</option>';

echo'</select>&nbsp;<SPAN STYLE="font-size: xx-small"> mins</SPAN></center><br>';


//date_default_timezone_set('Asia/Calcutta');
$StartDate=date("Y/m/d");	
$EndDate=date("Y/m/d");	
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
		
							<a href=javascript:NewCal_SD("date1","yyyymmdd",false,24)>
								<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
							</a>
								&nbsp;&nbsp;&nbsp;End Date

				<input type="text" id="date2" name="end_date" value="'.$EndDate.'" size="10" maxlength="19">
		
							<a href=javascript:NewCal("date2","yyyymmdd",false,24)>
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
<table border=0  cellspacing=0 cellpadding=0  width="100%">
	<tr>
		<td align="center">
			<table>
				<tr>
					<td>			
						<input type="button" onclick="javascript:action_report_travel(this.form)" value="Enter">&nbsp;<input type="reset" value="Clear">
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<!--<div align="center" id="loading_msg" style="display:none;"><br><font color="green">loading...</font></div>-->
</form>
</center>
';
