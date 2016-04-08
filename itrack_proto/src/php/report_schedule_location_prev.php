<?php 
	include_once('Hierarchy.php');		
	include_once('util_session_variable.php');		
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION['root']; 
	$filename1=$_POST['filename']; 
	$title1=$_POST['title'];
  //echo "filename1=".$filename1." title=".$title1; 
  echo'<form name="report1">
			<div class="report_div_height"></div>
			<center><div class="report_title"><b>'.$title1.'</b></div></center>';  
  include_once('tree_hierarchy_information.php');	
  include_once('report_location_account.php');
	echo'<center>';
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
								</td>
							</tr>
						</table>
					<td>
				</tr>										
			</table>
		<input type="button" value="Enter" onclick="javascript:report_display_schedule_location(\''.$filename1.'\',\''.$title1.'\');">&nbsp;			
	</form>
  </center>';
?>