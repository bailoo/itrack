<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	//include_once('manage_hierarchy_header1.php');
	$root=$_SESSION['root'];
	$account_id_local1=$_POST['account_id_local'];
	$no_of_halt_1=$_POST['no_of_halt'];
	//get_user_vehicle($root,$common_id1);
	echo "min_max_halt##";
	echo "<input type='hidden' value=".$no_of_halt_1." id='total_halt_no'>";
	$query="SELECT * FROM schedule_location WHERE account_id='$account_id_local1' AND status='1'";
	//echo "query=".$query."<br>";
	$result=mysql_query($query,$DbConnection);
	$row_result=mysql_num_rows($result);
	while($row=mysql_fetch_object($result))
	{
		$location_id[]=$row->location_id;
		$location_name[]=$row->location_name;				
	}	
	echo "<table class='menu'>";			
	for($i=1;$i<=$no_of_halt_1;$i++)
	{
		echo"<tr>
				<td><b>Min Halt Time</b></td>
				<td><b>Max Halt Time</b></td>
				<td><b>Location</b></td>
			</tr>
			<tr>
			<td>
				<input type='text' id='min_halt_time_hr_".$i."' size='3' value='00'>&nbsp;hr&nbsp;
				<input type='text' id='min_halt_time_min_".$i."' size='3' value='00'>&nbsp;min&nbsp;			
			</td>
				<td>
					<input type='text' id='max_halt_time_hr_".$i."' size='3' value='00'>&nbsp;hr&nbsp;
					<input type='text' id='max_halt_time_min_".$i."' size='3' value='00'>&nbsp;min&nbsp;
				</td>";
				if($row_result<=0)
				{
				echo"<td>
						No Location Found
					</td>";
				}
				else
				{
				echo"<td>
						<select id='min_max_halt_location_".$i."'>
							<option value='select'>Select</option>";
						$row=$row."_".$i;
						for($j=0;$j<sizeof($location_id);$j++)
						{
						echo"<option value=".$location_id[$j].">".$location_name[$j]."</option>";			
						}									
				echo"</select>
				</td>";
				}
			echo"</tr>";	
	}
	echo"</table>";
?>
