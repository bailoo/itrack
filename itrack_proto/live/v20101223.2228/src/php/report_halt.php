<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
	$size_suid=sizeof($suid);

  echo'
  	<table border=0 width = 100% cellspacing=2 cellpadding=0>
  		<tr>
  			<td height=10 class="text" align="center"><strong>Halt Report</strong></td>
  		</tr>
  	</table>

			<form  method="post" name="thisform">
			
			<br>
			<table border=1 rules=all bordercolor="#e5ecf5" cellspacing=0 cellpadding=0 align="center">		
				<tr>
					<td class="text"><b>&nbsp;Select&nbsp;Vehicle</b></td>					
				</tr>

				<tr>
					<td class="text" align="left">
						<input type="checkbox" name="all" value="1" onClick="javascript:All(this.form)">&nbsp;Select All
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
			
			';						
			
			echo'<br><center><SPAN STYLE="font-size: xx-small">Select Intervals </SPAN>
      <select name="user_interval" id="user_interval">';
			echo '<option value="5">0</option>';

			echo '<option value="5">5</option>';

			echo '<option value="10">10</option>';
										
			echo '<option value="15" selected>15</option>';
			
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

			echo '<option value="360">360</option>';

		echo'</select>&nbsp;<SPAN STYLE="font-size: xx-small"> mins</SPAN></center><br>';


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
<table border=0  cellspacing=0 cellpadding=0  width="100%">
	<tr>
		<td align="center">
			<table>
				<tr>
					<td>			
						<input type="button" onclick="javascript:action_report_halt(this.form)" value="Enter">&nbsp;<input type="reset" value="Clear">
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>';