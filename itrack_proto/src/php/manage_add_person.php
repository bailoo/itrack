<?php 
	//include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	//$root=$_SESSION['root'];  
	$DEBUG=0;
	$parameter_type="student";
	echo "add##";
	$common_id1=$_POST['common_id'];
	echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';
	//include_once('tree_hierarchy_information.php');
	//include_once('manage_radio_account.php');   
 ?>  
	<br>
	<table border="0" class="manage_interface">
			<!--
    <tr>
			<td>Name</td>
			<td> :</td>
			<td><input type="text" name="add_school_name" id="add_school_name" onkeyup="manage_availability(this.value, 'school')" onmouseup="manage_availability(this.value, 'school')" onchange="manage_availability(this.value, 'school')"></td>
		</tr>
	
		<tr>
			<td>Coord</td>
			<td> :</td>
			<td><textarea readonly="readonly" style="width:350px;height:60px" name="route_coord" id="route_coord" onclick="javascript:showCoordinateInterface('route');"></textarea></td>	
		</tr>
		-->
		<tr>
			<td>Name</td>
			<td> :</td>
			<td><input type="text" name="add_person_name" id="add_person_name" ></td>
		</tr>
		<tr>
			<td>Address</td>
			<td> :</td>
			<td><textarea style="width:350px;height:60px" name="add_person_address" id="add_person_address"></textarea></td>
		</tr> 		
		<tr>
			<td>Mobile No</td>
			<td> :</td>
			<td><input type="text" name="add_mobile_no" id="add_mobile_no" ></td>
		</tr>
		
    <tr>
			<td>IMEI No</td>
			<td> :</td>
			<!--<td><input type="text" name="add_imei_no" id="add_imei_no" onkeyup="manage_availability(this.value, 'person')" onmouseup="manage_availability(this.value, 'person')" onchange="manage_availability(this.value, 'person')"></td>-->
			<td><input type="text" name="add_imei_no" id="add_imei_no"></td>
		</tr>
		
	 
		<tr>
			<td colspan="3" align="center">
				<input type="button" id="enter_button" value="Save" onclick="javascript:return action_manage_person('add')"/>&nbsp;<input type="reset"" value="Clear" />
			</td>
		</tr>
	</table>
	<?php
		include_once('availability_message_div.php');
	?>

  