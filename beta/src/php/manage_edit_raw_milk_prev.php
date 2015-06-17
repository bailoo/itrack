<?php 
	//echo "TEST";
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include_once("util_account_detail.php");
	if($user_type=='plant_raw_milk'){
echo'
	<center>	
	<table>
	<tr>
		<td align="center">';
	echo"&nbsp;&nbsp; 	<a href='src/php/manage_add_raw_milk_usertype_approve_1.php?pending=2' class='hs2' target='_blank'><font color=red><b>Show Pending Invoice</b></a>";
	echo'</td>
	
	<td align="center">';
	echo"|&nbsp;<a href='#' onclick='javascript:open_pending_tanker_view();' class='hs2'><font color=brown><b>Show Pending Tanker</b></a>";
	echo'</td>
	</tr>
	</table>
	</center>';
	}
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
		<option value="1">Open with Date</option>
		<option value="6">OpenAll</option>
		<option value="2">Closed</option>
		<option value="0">Cancelled</option>
	</select></td>
	<td><input type="button" value="Enter" onclick="javascript:action_manage_invoice_update_prev(\'src/php/manage_edit_raw_milk_admin.php\');"></td>		 
	</tr>
</table>
				
</fieldset>

<div id="edit_div" style="display:none;"></div>
</center>';


//include_once('manage_loading_message.php');
?>
<link rel="StyleSheet" href="src/css/pager/bootstrap.css">	
	<link rel="StyleSheet" href="src/css/pager/dataTables.bootstrap.css">
	<script type="text/javascript" src="src/js/pager/jquery.dataTables.js"></script>
	<script type="text/javascript" src="src/js/pager/ZeroClipboard.js"></script>
	<script type="text/javascript" src="src/js/pager/TableTools.js"></script>
	<script type="text/javascript" src="src/js/pager/dataTables.bootstrap.js"></script>
	
	

		<div id="blackout_pending_tanker"> </div>	
		<div id="divpopup_pending_tanker" style="border: red 1px solid;">
	   
		
		<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="skyblue" >							
			<tr bgcolor=silver>
				<td class="manage_interfarce" align="right"><a href="#" onclick="javascript:return close_pending_tanker_view()" class="hs3"><b>[X]</b></a></td> 													
			</tr> 
			<tr bgcolor=silver>
				<td colspan="5" valign="top" align="CENTER">MANAGE PENDING TANKER <div id="loading_pending_tanker"></div></td>
			</tr>							
		</table>
		
		<table width="100%" height="90%" border="0" cellpadding="0" cellspacing="0"  style="background-color:ghostwhite;" align="center" >							
			
		
			<tr>
				<td colspan="2" valign="top" >
				<div id="manage_pending_tanker_show">
				
					
				
				</div>
					
				</td>
			</tr>
		</table>
		
		<?php  ?>
		
	</div>
	
  
