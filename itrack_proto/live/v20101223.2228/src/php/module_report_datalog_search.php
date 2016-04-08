<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');

	$str = $_GET['content'];
	if(strlen($str))			
	{	
		if($login=="demouser")
		{
			include("../custom_users.php");
			$Query="select vehicle.VehicleID,vehicle.VehicleName,vehicletable.TableID,`table`.TableName from vehicle,vehicletable,`table` where vehicle.VehicleID=vehicletable.VehicleID and vehicle.VehicleName like trim('".($str)."%') and `table`.TableID=vehicletable.tableid and (UserID='$user1' OR UserID='$user2') ORDER BY `TableID` ASC";
		}
		else
		{
			$Query="select vehicle.VehicleID,vehicle.VehicleName,vehicletable.TableID,`table`.TableName from vehicle,vehicletable,`table` where vehicle.VehicleID=vehicletable.VehicleID and vehicle.VehicleName like trim('".($str)."%') and `table`.TableID=vehicletable.tableid and UserID='$login' ORDER BY `TableID` ASC";
		}

		$sel = mysql_query($Query,$DbConnection);
		
		if(mysql_num_rows($sel))
		{
			echo"<table border =\"0\" width=\"100%\">\n";
			if(mysql_num_rows($sel))
			{
			echo"<script language=\"javascript\">box('1');</script>";
				while($row = mysql_fetch_array($sel))
				{
					$veh_name=$row->VehicleName;
					$country = str_ireplace($str,"<b>".$str."</b>",($row['VehicleName']));
					echo "<tr id=\"word".$row['VehicleID']."\" onmouseover=\"highlight(1,'".$row['VehicleID']."');\" onmouseout=\"highlight(0,'".$row['VehicleID']."');\" onClick=\"display('".$row['VehicleName']."');\">
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