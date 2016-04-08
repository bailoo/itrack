<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$str = strtolower($_POST['content']);
	$local_account_id1 = $_POST['local_account_id'];
	//echo "str=".$str;
	echo "search_vehicle##";
	if(strlen($str))
	{
			//$query="select vehicle.vehicle_id,vehicle.vehicle_name,vehicle_assignment.device_imei_no from vehicle,vehicle_assignment where vehicle.vehicle_name like '".($str)."%' AND vehicle.vehicle_id=vehicle_assignment.vehicle_id AND vehicle.vehicle_id IN(SELECT vehicle_id from vehicle_grouping WHERE account_id=$local_account_id1 AND status=1)";
	
			//echo "query=".$query;
			$sel = mysql_query("select vehicle.vehicle_id,vehicle.vehicle_name,vehicle_assignment.device_imei_no from vehicle,vehicle_assignment where vehicle.vehicle_name like '".($str)."%' AND vehicle.vehicle_id=vehicle_assignment.vehicle_id AND vehicle.vehicle_id IN(SELECT vehicle_id from vehicle_grouping WHERE account_id=$local_account_id1 AND status=1)");
	
		
		if(mysql_num_rows($sel))
		{
			//echo "mysql_num_rows=".mysql_num_rows($sel);
			echo"<table border =\"0\" width=\"100%\">\n";
			if(mysql_num_rows($sel))
			{
			echo"<script language=\"javascript\">box('1');</script>";
				while($row = mysql_fetch_object($sel))
				{
				  $veh_id=$row->vehicle_id;
					$veh_name=$row->vehicle_name;
					$device_imei_no=$row->device_imei_no;
					$common_value=$veh_name.','.$device_imei_no;
					//echo "vehicle_id=".$veh_id."vehicle_name=".$veh_name."device_imei_no=".$device_imei_no;
					$country = str_ireplace($str,"<b>".$str."</b>",($veh_name));
					echo "<tr id=\"word".$veh_id."\" onmouseover=\"highlight(1,'".$veh_id."');\" onmouseout=\"highlight(0,'".$veh_id."');\" onClick=\"display('".$common_value."');\">
							\n						
								<td style='font-size:11px;'>
									$country
								</td>
							\n
						  </tr>\n";
				}
			}
			echo"</table>";
		}
		
		else
		{
			
			echo'<table border ="0" width="100%">					
					<tr>
						<td style="font-size:11px;color:red;">
							No match found
						</td>
					</tr>			
				 </table>
				';
		
		}
	}	
	else
	{
			echo "<script language=\"javascript\">box('0');</script>";
	}
?>