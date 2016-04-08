<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');

	$size_suid=sizeof($suid);
							
  echo '
		
 	<table border=0 width = 100% cellspacing=2 cellpadding=0>
		<tr>
			<td height=10 class="text" align="center"><strong>Speed violation</strong></td>
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
							
                include('module_report_show_vehicle.php');
					
								echo'<input type="hidden" name="vehicleid[]">
						</table>
					</div>
				</td>
			</tr>
		</table>';

echo'<br>';													
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
												
				<input type="text" id="date1" name="StartDate" value="'.$StartDate.'" size="10" maxlength="19">
		
							<a href=javascript:NewCal_SD("date1","yyyymmdd",true,24)>
								<img src="Images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
							</a>
								&nbsp;&nbsp;&nbsp;End Date

				<input type="text" id="date2" name="EndDate" value="'.$EndDate.'" size="10" maxlength="19">
		
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
	<table align="center" BORDER="0" CELLPADDING="0" CELLSPACING="0">	
		<TR>
			<td valign="top" width="36%">
				&nbsp;
			</td>
			<td valign="top" width="1%">
				<input type="radio" name="rec" id="rec" value="ist"  checked>
			</td>
			<td width="8%">
					 <font STYLE="font-size:8pt" color="#0000FF" face="Verdana">
						&nbsp;IST
					 </font>							
			</td>                                                   
			<td valign="top" width="1%">
				<input type="radio" name="rec" id="rec" value="gmt">	
			</td>
			<td>
					 <font STYLE="font-size:8pt" color="#0000FF" face="Verdana">
						&nbsp;GMT+4
					 </font>
			</td>
		</TR>
	</table>
<br>
<table border=0  cellspacing=0 cellpadding=0  width="100%">
	<tr>
		<td align="center">
			<table>
				<tr>
					<td>			
						<input type="button" onclick="javascript:action_alert_speed_violation(this.form);" value="Enter">&nbsp;<input type="reset" value="Clear">
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

</form>';

?>					
				
