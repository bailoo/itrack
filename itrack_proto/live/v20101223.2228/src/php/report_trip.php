<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  echo'
	<style style type="text/css">
	<!--
	div.scroll {
	height: 200px;
	width: 650px;
	overflow: auto;
	border: 1px solid #666;
	padding: 8px;
	-->
	</style> ';
	

$case = $_GET['case'];
echo' 
		<table border=0 width = 100% cellspacing=2 cellpadding=0>
			<tr>';				
				
				if($case=="trip")
					echo'<td height=10 class="text" align="center"><strong>Trip Report</strong></td>';
				
				if($case=="movement")
					echo'<td height=10 class="text" align="center"><strong>Vehicle Movement</strong></td>';										
				
			echo'</tr>
		</table>
																				
			<form  method="post" name="thisform">
			
			<input type="hidden" name="case" value="'.$case.'">
			
			<br>								

			<table border=1 rules=all bordercolor="#e5ecf5" cellspacing=0 cellpadding=0 align="center">		
				<tr>
					<td class="text"><b>&nbsp;Select&nbsp;Vehicle</b></td>					
				</tr>

				<tr>
					<td class="text" align="center">
						<input type="checkbox" name="all" value="1" onClick="javascript:All(this.form)">&nbsp;Select All
					</td>
				
				</tr>
			</table>

			<br>

			<table border=0  align="center" cellspacing=0 cellpadding=0  width="100%">
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
			
			<br>
						
			 <table width="90%" align="center" border="0" cellspacing="0" cellpadding="0">
				<TR valign="top" cellspacing="0" cellpadding="0" align="center"> 
					
					<td width="100">
						&nbsp;
					</td>
					
					<TD  height="24" width="100" class="text">
						<font size="2">
							Date From
						</font>
					</td>
						
					<td width="140" align="center">
						<input type="text" id="date1" name="StartDate" id="StartDate" size="20" maxlength="19">
					</td>
					
					<td>
						<a href=javascript:NewCal_SD("date1","yyyymmdd",true,24)>
							<img src="Images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
						</a>
					</TD>

					<td width="25"></td>
					
					<TD height="24" width="100" class="text">
						<font size="2">
							Date To
						</font>
					</td>
						
					<td width="140" align="center">
						<input type="text" id="date2" name="EndDate" id="EndDate" size="20" maxlength="19">
					</td>
					
					<td>
						<a href=javascript:NewCal("date2","yyyymmdd",true,24)>
							<img src="Images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
						</a>
					</TD>

					<input type="hidden" name="date_id" value="1">							
					<td width="250"></td>
				</TR>
																				
			</table>			
		</div>
			<br><br>						
					
			<table border=0 align="center">						
				<tr>
					<td class="text" align="center"><input type="button" onclick="javascript:action_report_trip();" value="Show Trip Report"></td>
				</tr>
			</table>			
	</form> ';
				
?>			