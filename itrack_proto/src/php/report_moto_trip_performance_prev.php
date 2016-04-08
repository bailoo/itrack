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
			<br>
			<center>
					<div class="report_title">
						<b>'.$title1.'</b>
					</div>
			</center>';  
			//include_once('tree_hierarchy_information.php');	
			//include_once('report_radio_account.php');				 
			$StartDate=date("Y/m/d");	
			$EndDate=date("Y/m/d");	
			
			echo'<br>
				<table border=0 cellspacing=0 cellpadding=3 align="center">	
					<tr>					
						<td class="text">
							Start Date
						</td>
						<td>
						:
						</td>
						<td>													
							<input type="text" id="date1" name="start_date" value="'.$start_date.'" size="10" maxlength="19">			
							<a href=javascript:NewCal_SD("date1","yyyymmdd",true,24)>
								<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
							</a>
						</td>
					</tr>
					<tr>
						<td class="text">
							End Date
						</td>
						<td>
							:
						</td>
						<td>
							<input type="text" id="date2" name="end_date" value="'.$end_date.'" size="10" maxlength="19">			
							<a href=javascript:NewCal("date2","yyyymmdd",true,24)>
								<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
							</a>												
						</td>
					</tr>
				</table>
				<br>
			<center>	
				<input type="button" value="Enter" onclick="javascript:report_moto_trip_display(\''.$filename1.'\',\''.$title1.'\');">&nbsp;
			</center>
				
	</form>
  </center>';
?>