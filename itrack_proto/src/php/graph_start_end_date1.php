<?php
  //date_default_timezone_set('Asia/Calcutta');
	$start_date=date("Y/m/d");	
	$end_date=date("Y/m/d");
	echo'<fieldset class="report_fieldset">
	<legend>Date</legend> 
		<table border=0 width = 87% cellpadding="3" cellspacing="2">
		<tr>
			<td width=22%></td>
			<td  class="text">
				Select Date
			</td>
			</td>
			<td><input type="text" id="date1" name="start_date" value="'.$start_date.'" size="12" maxlength="19">
					
										<a href=javascript:NewCal_SD("date1","yyyymmdd",false,24)>
											<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
										</a>
											&nbsp;&nbsp;&nbsp;End Date  
							         <input type="text" id="date2" name="end_date" value="'.$end_date.'" size="12" maxlength="19">					
										<a href=javascript:NewCal("date2","yyyymmdd",false,24)>
											<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
										</a>
        </td>
      </tr>
      </table>
      </fieldset>';

?>
