<?php 
	include_once('Hierarchy.php');		
	include_once('util_session_variable.php');		
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION['root']; 
	$filename1=$_POST['filename']; 
	$title1=$_POST['title'];	
  //echo "filename1=".$filename1." title=".$title1; 

  echo'<form name="report1" method="post">
  <input type="hidden" name="title" value="'.$title1.'">
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
		<br>
		<center>	
			<input type="button" value="Enter" onclick="javascript:action_report_moto_stop_on_trip(\''.$filename1.'\',\''.$title1.'\');">&nbsp;
		</center>
				
	</form>
  </center>';
?>