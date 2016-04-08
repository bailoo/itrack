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
			<center>
					<div class="report_title">
						<b>'.$title1.'</b>
					</div>
			</center>';  
			include_once('tree_hierarchy_information.php');	
			include_once('report_radio_account.php');				 
			$StartDate=date("Y/m/d");	
			$EndDate=date("Y/m/d");	
echo'
		<table border=0 cellspacing=0 cellpadding=0 align="center">	
			<tr>			
				<td>
					<table>
						<tr>
							<td  class="text">	</td>
							<td class="text">
								Date&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;												
									<input type="text" id="date1" name="start_date" value="'.$StartDate.'" size="10" maxlength="19">		
									<a href=javascript:NewCal_SD("date1","yyyymmdd",false,24)>
										<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
									</a>																			
							</td>
						</tr>
					</table>
				<td>
			</tr>										
		</table>
		<br>
		<center>	
			<input type="button" value="Enter" onclick="javascript:action_report_moto_dispatch_master(\''.$filename1.'\',\''.$title1.'\');">&nbsp;
		</center>
				
	</form>
  </center>';
?>