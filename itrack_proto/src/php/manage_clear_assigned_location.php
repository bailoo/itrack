<?php	
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');


	echo "clear_assigned_location##";	
	echo "<input type='hidden' value=".$no_of_halt_1." id='total_halt_no'>";
	$query="SELECT * FROM schedule_assignment WHERE vehicle_id='$vehicleid_to_location' AND status='1'";
	//echo "query=".$query."<br>";
	$result=mysql_query($query,$DbConnection);
	$row_result=mysql_num_rows($result);
	
	if($row_result>0)
	{
		echo "<table border=1 class='menu' rules=all bordercolor='gray' cellspacing=2 cellpadding=2>";
			echo"<tr>
					<td><b>Serial</b></td>
					<!--<td><b>Vehicle Name</b></td>-->
					<td><b>Day From</b></td>
					<td><b>Day to</b></td>
					<td><b>Day</b></td>					
					<td><b>Location</b></td>
					<td><b>Min H. T.</b></td>
					<td><b>Max H. T.</b></td>
					<td><b>Base Station</b></td>
					<td><b>Intermediate H. T.</b></td>
					<td><b>Min O. T.</b></td>	
					<td><b>Max O. T.</b></td>
					<td><b>Rest Time</b></td>
					<td><b>Min D. T.</b></td>
					<td><b>Max D. T.</b></td>
					<td><b>Delete</b></td>
					<td><b>Edit</b></td>
				</tr>";
				$cnt=1;
		while($row=mysql_fetch_object($result))
		{
			echo"<tr>
					<td>".$cnt."</td>
					<!--<td>".$vehicle_name."</td>-->
					<td>".$row->date_from."</td>
					<td>".$row->date_to."</td>";
					if($row->day!="")
					{
						$dayName=explode(",",$row->day);
						$dayNameStr="";
						for($i=0;$i<sizeof($dayName);$i++)
						{
							if($dayName[$i]=="1")
							{
								$dayNameStr=$dayNameStr."Sunday,";
							}
							if($dayName[$i]=="2")
							{
								$dayNameStr=$dayNameStr."Monday,";
							}
							if($dayName[$i]=="3")
							{
								$dayNameStr=$dayNameStr."Tuesday,";
							}
							if($dayName[$i]=="4")
							{
								$dayNameStr=$dayNameStr."Wednesday,";
							}
							if($dayName[$i]=="5")
							{
								$dayNameStr=$dayNameStr."Thursday,";
							}
							if($dayName[$i]=="6")
							{
								$dayNameStr=$dayNameStr."Friday,";
							}
							if($dayName[$i]=="7")
							{
								$dayNameStr=$dayNameStr."Saturday,";
							}							
						}
						$dayNameStr=substr($dayNameStr,0,-1);
						echo"<td>".$dayNameStr."</td>";
					}
					else
					{
						echo"<td>-</td>";
					}									
					if($row->location_id!="")
					{
						$location_id=explode(",",$row->location_id);
						$lqc=""; //lqc=location query condition
						for($i=0;$i<sizeof($location_id);$i++)
						{
							$lqc=$lqc."location_id=".$location_id[$i]." OR ";
						}				
						$lqc=substr($lqc,0,-5);
						//echo "SELECT * FROM schedule_location WHERE $lqc"."<br>";
						$Query_Location="SELECT * FROM schedule_location WHERE $lqc";					
						$Result_Location=mysql_query($Query_Location,$DbConnection);
						$LocationName="";
						while($Row_Location=mysql_fetch_object($Result_Location))
						{
							$LocationName=$LocationName.$Row_Location->location_name.",";
						}
						$LocationName=substr($LocationName,0,-1);
						echo"<td>".$LocationName."</td>";
					}
					else
					{
						echo"<td>-</td>";
					}
					if($row->min_halt_time!="")
					{
						echo"<td>".$row->min_halt_time."</td>";
					}
					else
					{
						echo"<td>-</td>";
					}
					if($row->max_halt_time!="")
					{
						echo"<td>".$row->max_halt_time."</td>";
					}
					else
					{
						echo"<td>-</td>";
					}
					$QueryBS="SELECT landmark_name FROM landmark WHERE landmark_id=$row->base_station_id";		
					//echo "dbconnection".$DbConnection."<br>";
					$ResultBS=mysql_query($QueryBS,$DbConnection);
					$RowBS=mysql_fetch_row($ResultBS);
					//echo "RowBS=".$RowBS[0]."<br>";
					if($RowBS[0]!="")
					{
						echo"<td>".$RowBS[0]."</td>";
					}
					else
					{
						echo"<td>-</td>";
					}
					if($row->Intermediate_halt_time!="")
					{
						echo"<td>".$row->Intermediate_halt_time."</td>";
					}
					else
					{
						echo"<td>-</td>";
					}
					if($row->min_operation_time!="")
					{
						echo"<td>".$row->min_operation_time."</td>";
					}
					else
					{
						echo"<td>-</td>";
					}
					if($row->max_operation_time!="")
					{
						echo"<td>".$row->max_operation_time."</td>";
					}
					else
					{
						echo"<td>-</td>";
					}
					if($row->rest_time!="")
					{
						echo"<td>".$row->rest_time."</td>";
					}
					else
					{
						echo"<td>-</td>";
					}
					if($row->min_distance_travelled!="")
					{
						echo"<td>".$row->min_distance_travelled."</td>";
					}
					else
					{
						echo"<td>-</td>";
					}
					if($row->max_distance_travelled!="")
					{
						echo"<td>".$row->max_distance_travelled."</td>";
					}
					else
					{
						echo"<td>-</td>";
					}
					echo"<td>
							<input type='button' Value='Delete' onclick=javascript:manage_delete_schedule_assignment(".$row->serial.",".$vehicleid_to_location.",'".$vehicle_name."')>
						</td>
						<td>
							<input type='button' Value='Edit' onclick=javascript:manage_edit_schedule_assignment(".$row->serial.",".$vehicleid_to_location.",'".$vehicle_name."','".$row->date_to."')>
						</td>
				</tr>";
			$cnt++;
		}
	echo "</table>";
	echo"<input type='hidden' id='edit_sa_serial'>
	<input type='hidden' id='cmp_date1'>
	<input type='hidden' id='edit_sa_vehicle_id'>
	<input type='hidden' id='edit_sa_vehicle_name'>";
	echo'<div id="blackout_edit_sa"> </div>
			<div id="divpopup_edit_sa">
						<table cellspacing="5" cellpadding="5" border=0>
							<tr>
								<td>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
								
								<td>
									Edit Date
								</td>
								<td>
								:
								</td>
								<td>
									<input type="text" id="date1" value="" size="10" maxlength="19">
									<a href=javascript:NewCal_SD("date1","yyyymmdd",false,24)>
										<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
									</a>
								</td>
								<td>
									<input type="button" Value="Edit Date" onclick="javascript:manage_edit_schedule_assignment_1()">
								</td>
								<td>
									
								</td>
								<td>
									
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>								
								<td class="manage_interfarce" align="right">
									<a href="#" onclick="javascript:close_edit_sa_popup()" class="hs3">
										<img src="images/close.png" type="image" style="border-style:none;">
									</a>
								</td>
							</tr>
						</table>									
			</div>';
			//echo "in if";	
			echo"##".$cnt;
	
	}
	else
	{
		echo "<b>No Assignment Found For This Vehicle<b>##";
	}
?>
