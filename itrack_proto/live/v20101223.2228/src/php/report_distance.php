<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');	
		
		echo '		
  	<center>
    <table border=0 width = 100% cellspacing=2 cellpadding=0>
  		<tr>
  			<td height=10 class="text" align="center"><strong>Distance Report</strong></td>
  		</tr>
  	</table>    
    
    <form  method="post" name="thisform">
		
		<br>	
		
	<fieldset class="report_fieldset">
		<legend>Select Vehicle</legend>		
		
		<table border=1 rules=rows bordercolor="#e5ecf5" cellspacing=0 cellpadding=3 align="center">		
		
			<tr>
				<td class="text" align="center">
					<input type="checkbox" name="all" value="1" onClick="javascript:SelectAll(this.form);">&nbsp;Select All
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
							
								include('module_show_vehicle_chk.php');

								echo'<input type="hidden" name="vehicleid[]">
						</table>
					</div>
				</td>
			</tr>
		</table>
		</fieldset>
		<br>';							

	 echo'
   <fieldset class="report_fieldset">
		<legend>Select display Option</legend>';		
		
    echo'<SPAN STYLE="font-size: xx-small">Select Interval </SPAN>
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

		echo'</select>&nbsp;<SPAN STYLE="font-size: xx-small"> hr/hrs</SPAN><br>';												
		
		
		date_default_timezone_set('Asia/Calcutta');
		$start_date=date("Y/m/d 00:00:00");	
		$end_date=date("Y/m/d H:i:s");	
		
		echo'
		<br><table border=0 cellspacing=0 cellpadding=3 align="center">	
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
										<img src="Images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
									</a>
										&nbsp;&nbsp;&nbsp;End Date

						<input type="text" id="date2" name="end_date"value="'.$end_date.'" size="10" maxlength="19">
				
									<a href=javascript:NewCal("date2","yyyymmdd",true,24)>
										<img src="Images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
									</a>													
							</TD>
																						
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
					<input type="button" onclick="javascript:report_distance_res(this.form);" value="Enter">
						&nbsp;
					<input type="reset" value="Clear">
				</td>
			</tr>
		</table>
	</form>
  </center>
  ';

?>