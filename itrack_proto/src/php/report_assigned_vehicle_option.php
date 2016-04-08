<?php
	include_once('util_session_variable.php');	include_once('util_php_mysql_connectivity.php'); 
	$display_type = $_POST['display_type1'];	// it is either vehicle type or vehicle tag	
	$account_id_local1 = $_POST['account_id_local'];		
	echo"portal_vehicle_information##";	//////////using this as div id in which this page is display from ajax	
	
	if($display_type=="vehicle_type")	{$vehicle_option="Vehicle Type";}
	else if($display_type=="vehicle_tag")	{$vehicle_option="Vehicle Tag";}

  $query="SELECT DISTINCT $display_type FROM vehicle INNER JOIN vehicle_grouping WHERE vehicle.vehicle_id=vehicle_grouping.vehicle_id AND".
" vehicle_grouping.account_id=$account_id_local1 AND vehicle_grouping.status=1 AND vehicle.status=1";
	
	//echo"query=".$query."<br>";
	$result=mysql_query($query,$DbConnection);	
	$num_result=mysql_num_rows($result);
	if($num_result==0)
	{	echo"<td class='text'><div style='height:7px'></div><font color='red'>No ".$display_type." Found</td>";	}
	else
	{
		$select_all_td="<td class='text'>&nbsp;<input type='checkbox' name='all' value='1' onClick='javascript:select_all_option(this.form);'>&nbsp;&nbsp;Select All</td>";			
		$vehicletag_cnt=0;
	echo "<br><div style='height:7px'></div>
						<table border=1 cellspacing=0 cellpadding=0 class='module_left_menu' align='center' width='70%'>
							<tr>
								<td class='text'>&nbsp;SNo </td>
								<td class='text'>&nbsp;".$vehicle_option."</td>
								".$select_all_td."
							</tr>";
						for($i=0;$i<$num_result;$i++)
						{
								$row=mysql_fetch_object($result);
								$vehicle_type_tag=$row->$display_type;
						echo'<tr> 
								<td>&nbsp;'.$i.'</td>
								<td>&nbsp;'.$vehicle_type_tag.'</td>
								<td align="left">&nbsp;<INPUT TYPE="checkbox"  name="manage_option[]" VALUE="'.$vehicle_type_tag.'"></td>
							</tr>';
						}
					echo"</table>"; 
	}
?>