<?php 
	//include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	//$root=$_SESSION['root'];  
	$DEBUG=0;
	$parameter_type="student";
	echo "edit##";
	$common_id1=$_POST['common_id'];	
	$group_id="";	
	$group_id=getGroupID($common_id1,1,$DbConnection);		
	
	echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';
	echo'<input type="hidden" id="group_id_hidden" value='.$group_id.'>';
	echo'<input type="hidden" id="selected_account_id_hidden" value='.$common_id1.'>';
	//include_once('tree_hierarchy_information.php');
	//include_once('manage_radio_account.php');   
 ?>  
	<br>
	<table border="0" align="center" class="manage_interface" cellspacing="2" cellpadding="2">
  <tr>
  <td>&nbsp;DriverName&nbsp:&nbsp;
    
    <select name="drivername_id" id="drivername_id" onchange="show_busdriver_record(manage1);">
      <option value="select">Select</option>
      <?php
		    $data=DidDnameDnoBusDriver($common_id1,$DbConnection);            							
			foreach($data as $dt)
			{
				
				$driverid=$dt['driverid'];
				$drivername=$dt['drivername']; 
				$dlnumber=$dt['dlnumber'];;              								 
				echo '<option value='.$driverid.'>'.$drivername.'['.$dlnumber.']</option>';
			}
			?>
    </select>
  </td>
</tr>
<tr> 		         
                          
  <td>
   <div id="display_area" style="display:none">
  <fieldset class="manage_fieldset1">
	<legend><strong>Driver Record</strong></legend>
	<table border="0" class="manage_interface">
		
		<tr>
			<td>Driver Name</td>
			<td> :</td>
			<td><input type="text" name="edit_driver_name" id="edit_driver_name" ></td>
		</tr>
		<tr>
			<td>Driving Lincence</td>
			<td> :</td>
			<td><textarea style="width:350px;height:60px" name="edit_dlnumber" id="edit_dlnumber"></textarea></td>
		</tr>
		<tr>
			<td>dob</td>
			<td> :</td>
			<td><input type="text" name="edit_dob" id="edit_dob" ></td>
		</tr>
		<tr>
			<td>Address</td>
			<td> :</td>
			<td><input type="text" name="edit_address" id="edit_address" ></td>
		</tr>
   
	</table>
	</fieldset>
</div>
</td>                                
</tr>        
<tr>
	<td colspan="3" align="center">
		<input type="button" id="enter_button" value="Update" onclick="javascript:return action_manage_busdriver('edit')"/>&nbsp;
		<input type="button" value="Delete" onclick="javascript:action_manage_busdriver('delete')"/>
	</td>
</tr>
</table>
	<?php
		include_once('availability_message_div.php');
	?>

  