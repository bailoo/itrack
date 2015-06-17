<?php 
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
$root=$_SESSION['root'];

echo "add##"; 
include_once('tree_hierarchy_information.php');
include_once('manage_radio_account.php'); 
	
echo '<div style="height:5px;" align="center">

<input type="radio" Onclick="javascript:show_invoice_upload_div();">&nbsp;Upload File &nbsp;&nbsp;
<!--<input type="radio" name="mode" id="mode" value="2" Onclick="javascript:show_station_mode(this.value);">&nbsp;Enter Manually--></div><br><br>';
				
//include_once('availability_message_div.php');
?>
  
