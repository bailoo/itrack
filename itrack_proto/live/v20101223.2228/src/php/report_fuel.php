<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
	$size_suid=sizeof($suid);
  echo'
  	<table border=0 width = 100% cellspacing=2 cellpadding=0>
  		<tr>
  			<td height=10 class="text" align="center"><strong>Fuel Report</strong></td>
  		</tr>
  	</table>

		<form  method="post" name="thisform">							
		<br>								
		<table border=1 rules=rows bordercolor="#e5ecf5" cellspacing=0 cellpadding=3 align="center">		
			<tr>
				<td class="text">
					<b>
						&nbsp;Select
					</b>
				</td>
				<td class="text">
					<b>
						&nbsp;Vehicle
					</b>
				</td>
			</tr>

			<tr>
				<td class="text" align="center">
					<input type="checkbox" name="all" value="1" onClick="javascript:All(this.form)">
				</td>
				<td class="text">
					Select All
				</td>
			</tr>
		</table>

		<br>
		
		<table border=0  cellspacing=0 cellpadding=0  width="100%">
			<tr>
				<td align="center">							
					<div style="overflow: auto;height: 150px; width: 650px;" align="center">
						<table border=1 rules=rows bordercolor="lightblue" cellspacing=0 cellpadding=0 align="center" width="100%">	';						
							
								include('module_manage_show_vehicle.php');
											
								echo'<input type="hidden" name="vehicleid[]">
						</table>
					</div>
				</td>
			</tr>
		</table>							
		<br>';		
		
		echo'<br><center><SPAN STYLE="font-size: xx-small">Select Interval </SPAN>
    <select name="user_interval" id="user_interval">';														
		
		echo '<option value="all">All</option>';
		
		echo '<option value="10m">10 mins</option>';
		
		echo '<option value="30m" selected>30 mins</option>';
		
		echo '<option value="1h">1 hr</option>';

		echo '<option value="2h">2 hrs</option>';

		echo '<option value="3h">3 hrs</option>';																					
		
		echo '<option value="4h">4 hrs</option>';
		
		echo '<option value="5h">5 hrs</option>';
		
		echo '<option value="6h">6 hrs</option>';
		
		echo '<option value="7h">7 hrs</option>';
									
		echo '<option value="8h">8 hrs</option>';
		
		echo '<option value="9h">9 hrs</option>';

		echo '<option value="10h">10 hrs</option>';

		echo '<option value="11h">11 hrs</option>';

		echo '<option value="12h">12 hrs</option>';							

		echo'</select>&nbsp;<SPAN STYLE="font-size: xx-small"> mins/hrs</SPAN></center><br>';
		
		date_default_timezone_set('Asia/Calcutta');
		$StartDate=date("Y/m/d 00:00:00");	
		$EndDate=date("Y/m/d H:i:s");	
		
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
														
						<input type="text" id="date1" name="StartDate" id="StartDate" value="'.$StartDate.'" size="10" maxlength="19">
				
									<a href=javascript:NewCal_SD("date1","yyyymmdd",true,24)>
										<img src="Images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
									</a>
										&nbsp;&nbsp;&nbsp;End Date

						<input type="text" id="date2" name="EndDate" id="EndDate" value="'.$EndDate.'" size="10" maxlength="19">
				
									<a href=javascript:NewCal("date2","yyyymmdd",true,24)>
										<img src="Images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
									</a>
													
							</TD>

						<input type="hidden" name="rep_uid" value="'.$uid.'">																	
							</td>
						</tr>
					</table>
				<td>
			</tr>										
		</table>
		
		<br>
		<table border=0 cellspacing=0 cellpadding=3 align="center">						
										
			<tr>
				<td align="center" colspan=2>
					<input type="button" onclick="javascript:action_report_fuel(this.form)" value="Enter">
						&nbsp;
					<input type="reset" value="Clear">
				</td>
			</tr>
		</table>
	</form>
';
?>				