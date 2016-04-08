<?php 
	//include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	//$root=$_SESSION['root'];  
	$DEBUG=0;
	$parameter_type="student";
	echo "edit##";
	$common_id1=$_POST['common_id'];
	echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';
	//include_once('tree_hierarchy_information.php');
	//include_once('manage_radio_account.php');   
 ?>  
	<br>
	<table border="0" align="center" class="manage_interface" cellspacing="2" cellpadding="2">
  <tr>
  <td>&nbsp;Person Name&nbsp:&nbsp;
    
    <select name="person_id" id="person_id" onchange="show_person_record(manage1);">
      <option value="select">Select</option>
      <?php
			
			$data=getDetailAllPerson($common_id1,$DbConnection);            							
			foreach($data as $dt)
			{
			  $person_id=$dt['person_id'];
			  $person_name=$dt['person_name'];			               								 
			  echo '<option value='.$person_id.'>'.$person_name.'</option>';
			}
			?>
    </select>
  </td>
</tr>
<tr> 		         
                          
  <td>
   <div id="display_area" style="display:none">
  <fieldset class="manage_fieldset1">
	<legend><strong>Person Record</strong></legend>
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
			<td><input type="text" name="edit_person_name" id="edit_person_name" ></td>
		</tr>
		<tr>
			<td>Address</td>
			<td> :</td>
			<td><textarea style="width:350px;height:60px" name="edit_person_address" id="edit_person_address"></textarea></td>
		</tr>		
		<tr>
			<td>Mobile No</td>
			<td> :</td>
			<td><input type="text" name="edit_mobile_no" id="edit_mobile_no" ></td>
		</tr>
		<tr>
			<td>IMEI No</td>
			<td> :</td>
			<td><input type="text" name="edit_imei_no" id="edit_imei_no" ></td>
		</tr>
		
	  <!--
		<tr>
			<td colspan="3" align="center">
				<input type="button" id="enter_button" value="Save" onclick="javascript:return action_manage_student('add')"/>&nbsp;<input type="reset"" value="Clear" />
			</td>
		</tr>
		-->
	</table>
	</fieldset>
</div>
</td>                                
</tr>        
<tr>
	<td colspan="3" align="center">
		<input type="button" id="enter_button" value="Update" onclick="javascript:return action_manage_person('edit')"/>&nbsp;
		<input type="button" value="Delete" onclick="javascript:action_manage_person('delete')"/>
	</td>
</tr>
</table>
	<?php
		include_once('availability_message_div.php');
	?>

  