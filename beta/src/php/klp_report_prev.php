<?php 
	include_once('Hierarchy.php');		
	include_once('util_session_variable.php');		
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION['root'];  	
	$filename1="src/php/action_report_klp_input.php"; 
	$title1=$_POST['title'];
  //echo "filename1=".$filename1." title=".$title1; 
  echo'<form name="report1">
			<div class="report_div_height"></div>
			<center><div class="report_title"><b>'.$title1.'</b></div></center>';  
  include_once('tree_hierarchy_information.php');	include_once('report_radio_account.php');
	echo'<center>
			<table border=0 cellspacing=0 cellpadding=0 class="module_left_menu">
				<tr>
					<td>
						Date
					</td>
					<td>
						&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;
					</td>
					<td>
						&nbsp;<input type="text" id="date1" name="start_date" size="10" maxlength="19">
						<a href=javascript:NewCal_SD("date1","yyyymmdd",false,24)>
									<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
								</a>
					</td>					
				</tr>
			</table>			
		<br>
		<input type="button" value="Enter" onclick="javascript:action_report_klp_input(\''.$filename1.'\',\''.$title1.'\');">&nbsp;			
	</form>
  </center>';
?>