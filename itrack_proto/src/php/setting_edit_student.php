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
  <td>&nbsp;Student Name&nbsp:&nbsp;
    <?php
	$query="select * from student where student_id IN(select distinct student_id from student_grouping where account_id='$common_id1' and status='1') and status='1'";
	//echo'query='.$query;
	?>
    <select name="student_id" id="student_id" onchange="show_student_record_parent(setting);">
      <option value="select">Select</option>
      <?php
			
			$result=mysql_query($query,$DbConnection);            							
			while($row=mysql_fetch_object($result))
			{
			  $student_id=$row->student_id; $student_name=$row->student_name;                								 
			  echo '<option value='.$student_id.'>'.$student_name.'</option>';
			}
			?>
    </select>
  </td>
</tr>
<tr> 		         
                          
  <td>
   <div id="display_area" style="display:none">
  <fieldset class="manage_fieldset1">
	<legend><strong>Student Record</strong></legend>
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
			<td><input type="text" name="edit_student_name" id="edit_student_name" readonly="true"></td>
		</tr>
		<tr>
			<td>Address</td>
			<td> :</td>
			<td><textarea style="width:350px;height:60px" name="edit_student_address" id="edit_student_address"></textarea></td>
		</tr>
		<tr>
			<td>Father Name</td>
			<td> :</td>
			<td><input type="text" name="edit_student_father_name" id="edit_student_father_name" readonly="true"></td>
		</tr>
		<tr>
			<td>Mother Name</td>
			<td> :</td>
			<td><input type="text" name="edit_student_mother_name" id="edit_student_mother_name" readonly="true"></td>
		</tr>
		<tr>
			<td>Roll Number</td>
			<td> :</td>
			<td><input type="text" name="edit_student_roll_no" id="edit_student_roll_no" readonly="true"></td>
		</tr>
		<tr>
			<td>Class</td>
			<td> :</td>
			<td><input type="text" name="edit_student_class" id="edit_student_class" readonly="true"></td>
		</tr>
		<tr>
			<td>Section</td>
			<td> :</td>
			<td><input type="text" name="edit_student_section" id="edit_student_section" readonly="true"></td>
		</tr>
		<tr>
			<td>Student Mobile No</td>
			<td> :</td>
			<td><input type="text" name="edit_student_student_mobile_no" id="edit_student_student_mobile_no" ></td>
		</tr>
		<tr>
			<td>Parent Mobile No</td>
			<td> :</td>
			<td><input type="text" name="edit_student_parent_mobile_no" id="edit_student_parent_mobile_no" ></td>
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
		<input type="button" id="enter_button" value="Update" onclick="javascript:return action_setting_student('parent_edit')"/>&nbsp;
		<!-- <input type="button" value="Delete" onclick="javascript:action_manage_student('delete')"/>   -->
	</td>
</tr>
</table>
	<?php
		include_once('availability_message_div.php');
	?>

  