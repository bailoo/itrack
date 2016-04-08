<?php
	include_once('util_session_variable.php');		include_once('util_php_mysql_connectivity.php');
 
	$display_type = $_POST['display_type1'];		$account_id_local1 = $_POST['account_id_local'];		echo"portal_vehicle_information##";		
	
	if($display_type=="vehicle_type")
	{$vehicle_option="Vehicle Type";}
	else if($display_type=="vehicle_tag")
	{$vehicle_option="Vehicle Tag";}	

	$select_all_td="<td class='text'>&nbsp;<input type='checkbox' name='all_1' value='1' onClick='javascript:select_manage_all_portal_option(this.form);'>&nbsp;&nbsp;Select All</td>";			
		$vehicletag_cnt=0;
		echo "<br>
		<table border=1 cellspacing=0 cellpadding=0 class='module_left_menu' align='center' width='70%'>
			<tr>
				<td class='text'>&nbsp;SNo </td>
				<td class='text'>&nbsp;".$vehicle_option."</td>
				".$select_all_td."
			</tr>";
			//$query="SELECT distinct $display_type from vehicle WHERE vehicle_id IN (SELECT vehicle_id from vehicle_grouping where account_id='$account_id_local' AND status=1) AND status='1'";
			$query="SELECT distinct vehicle.$display_type FROM vehicle,vehicle_grouping WHERE vehicle.vehicle_id=vehicle_grouping.vehicle_id".
					" AND vehicle_grouping.account_id='$account_id_local' AND vehicle_grouping.status=1 AND vehicle.status='1'";
			$result=mysql_query($query,$DbConnection);	
			$i=0;		
			while($row=mysql_fetch_object($result))
			{
				$vehicle_type_tag=$row->$display_type;
			echo'<tr> 
					<td>&nbsp;'.++$i.'</td>
					<td>&nbsp;'.$vehicle_type_tag.'</td>
					<td align="left">&nbsp;<INPUT TYPE="checkbox"  name="manage_option[]" VALUE="'.$vehicle_type_tag.'"></td>
				</tr>';
			}
			echo"</table>"; 
?>