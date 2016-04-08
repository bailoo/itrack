<?php 
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION['root']; 

	$common_id1=$_POST['common_id'];
	echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';
	//echo "add##";
	echo '
  <center>
	<form name="setting1">
	
  ';
  include_once('setting_alert_hierarchy_header.php');
	//include_once('tree_hierarchy_information.php');
	//include_once('manage_checkbox_account.php');   
 ?>

	<br>
	<fieldset class="manage_fieldset">
	<legend><strong>Configure Alert</strong></legend>
	<table border="0" class="manage_interface">
	
		<tr>
		  <td>
      <input type="checkbox" name="alert[]" value="1" /> Alert before bus reached bus stop  <b>Distance Before :<b> 
                  <select name="time_before_min" id="time_before_min">
											<option value="select">KM</option>
                      <option value="0.5">0.5</option> 
											<option value="1.0">1.0</option> 
											<option value="1.5">1.5</option> 
											<option value="2.0">2.0</option> 
											<option value="3.0">3.0</option> 
											<option value="4.0">4.0</option> 
											<option value="5.0">5.0</option> 
											<option value="6.0">6.0</option> 
											<option value="7.0">7.0</option> 
											<option value="8.0">8.0</option> 
											<option value="9.0">9.0</option> 
											<option value="10.0">10.0</option>
										</select> KM
		  </td>
		</tr>
		<tr>
		  <td>
      <input type="checkbox" name="alert[]" value="2" /> Alert when bus reached bus stop 
		  </td>
		</tr>
		<tr>
		  <td>
      <input type="checkbox" name="alert[]" value="3" /> Alert when student entered in bus 
		  </td>
		</tr>
		<tr>
		  <td>
      <input type="checkbox" name="alert[]" value="4" /> Alert when student exit from bus 
		  </td>
		</tr>
    <!--
    <tr>
			<td>Mobile Number</td>
			<td> :</td>
			<td><input type="text" name="mobile_number" id="mobile_number" onkeyup="manage_availability(this.value, 'busroute')" onmouseup="manage_availability(this.value, 'busroute')" onchange="manage_availability(this.value, 'busroute')"></td>
		</tr>   		
		--> 		
		<tr>
			<td colspan="3" align="center">
				<input type="button" id="enter_button" value="Configure" onclick="javascript:return action_setting_alert('configure')"/>&nbsp;<input type="reset"" value="Clear" />
			</td>
		</tr>
	</table>
	</fieldset>
	</form>
	</center>
	<?php
		include_once('availability_message_div.php');
	?>

  