<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
	
	$size_suid=sizeof($suid);
	
	echo'
		<table border=0 width = 100% cellspacing=2 cellpadding=0>
			<tr>
				<td height=10 class="text" align="center"><strong>Summary Report</strong></td>
			</tr>
		</table>
		
			<form method="post">
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
			</table>';

    echo'<br>								
				
				  <table width="427" align="center">
					<TR valign="top" cellspacing="0" cellpadding="0" align="left"> 
						<TD  height="24" width="80">
							<font size="2">Date From</font></td>
							<td width="140" align="center"><div align="left"><input type="text" id="date1" name="StartDate" value="'.$StartDate.'" size="10" maxlength="19">
							<a href=javascript:NewCal_SD("date1","yyyymmdd",true,24)><img src="Images/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a></div>
						</TD>

						<TD  height="24" width="60">
							<font size="2">Date To</font></td>
							<td width="140" align="center"><div align="left"><input type="text" id="date2" name="EndDate" value="'.$EndDate.'" size="10" maxlength="19">
							<a href=javascript:NewCal("date2","yyyymmdd",true,24)><img src="Images/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a></div>
						</TD>
					
					</TR>																						
					</table>
											
  				<br>
  				<table border=0 align="center">						
				  <tr>
					<td class="text" align="left"><input type="button" onclick="javascript:action_report_summary(this.form);" value="Enter"></td>
				</tr>
				</table>
			</form>';   
  ?>
					