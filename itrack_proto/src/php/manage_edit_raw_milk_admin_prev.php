<?php 
	//echo "TEST";
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');

	echo'
	<center>	
	<fieldset class="manage_fieldset_invoice">
		<legend><strong>Select Date</strong></legend>
	<table>
	<tr>
		<td>StartDate :</td>
		<td>
			<input type="text" name="startdate" id="startdate" value="" placeholder="StartDate"  onclick="javascript:NewCal_SD(this.id,\'yyyymmdd\',true,24);" /></td>	
		
		<td>EndDate :</td>		
		<td>
			<input type="text" name="enddate" id="enddate" placeholder="EndDate" onclick="javascript:NewCal(this.id,\'yyyymmdd\',true,24);"/>	
			
		 </td>
	<td>Select Order:</td>
	<td><select id="order">
		<option value="3">All</option>
		<option value="1">Open</option>
		<option value="2">Closed</option>
		<option value="0">Cancelled</option>
		<option value="5">Pending</option>
	</select></td>
	<td><input type="button" value="Enter" onclick="javascript:action_manage_invoice_update_prev(\'src/php/manage_edit_raw_milk_admin.php\');"></td>		 
	</tr>
</table>
				
</fieldset>

<div id="edit_div" style="display:none;"></div>
</center>';


//include_once('manage_loading_message.php');
?>
  
