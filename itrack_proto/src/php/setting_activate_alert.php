<?php 
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION['root'];  
	//echo "add##";
	//include_once('tree_hierarchy_information.php');
	//include_once('manage_checkbox_account.php');   
 ?>
 <center>
	<form name="manage1">  
	<br>
	<fieldset class="manage_fieldset">
	<legend><strong>Activate Alert</strong></legend>
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
		  <td>
      <input type="checkbox" name="alert" value="1" /> Alert before bus reached bus stop 
		  </td>
		</tr>
		<tr>
		  <td>
      <input type="checkbox" name="alert" value="2" /> Alert when bus reached bus stop 
		  </td>
		</tr>
		<tr>
		  <td>
      <input type="checkbox" name="alert" value="3" /> Alert when student entered in bus 
		  </td>
		</tr>
		<tr>
		  <td>
      <input type="checkbox" name="alert" value="4" /> Alert when student exit from bus 
		  </td>
		</tr>  		
		 		
		<tr>
			<td colspan="3" align="center">
				<input type="button" id="enter_button" value="Activate" onclick="javascript:return action_manage_busroute('add')"/>&nbsp;<input type="reset"" value="Clear" />
			</td>
		</tr>
	</table>
	</fieldset>
	</form>
	</center>
	<?php
		include_once('availability_message_div.php');
	?>

  