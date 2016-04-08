<?php 
////////////// select current date vehicle///////////////////////

$query_vehicle = "select TableName from `table`";

$result_vehicle = mysql_query($query_vehicle,$DbConnection);
$num12 = mysql_num_rows($result_vehicle);

$num_table_ids=0;
while($row1 = mysql_fetch_object($result_vehicle))
{
	$tablename_1[$num_table_ids]=$row1->TableName;	
	$num_table_ids++;
}

if($access==1 || $access=="Zone")
{
$query_vehicle = "select vehicle.VehicleID,vehicle.VehicleName,vehicletable.TableID,`table`.TableName from vehicle,vehicletable,`table` where vehicle.VehicleID=vehicletable.VehicleID and `table`.TableID=vehicletable.tableid and UserID='$login' ORDER BY `TableID` ASC";
}

if($access==-2)
{
	for($i=0;$i<$size_suid;$i++)
	{				
	if($i==0)
	$query_vehicle="select vehicle.VehicleID,vehicle.VehicleName,vehicletable.TableID,`table`.TableName from vehicle,vehicletable,`table` where vehicle.VehicleID=vehicletable.VehicleID and `table`.TableID=vehicletable.tableid and UserID='$suid[$i]'";
	else
	$query_vehicle=$query_vehicle." UNION select vehicle.VehicleID,vehicle.VehicleName,vehicletable.TableID,`table`.TableName from vehicle,vehicletable,`table` where vehicle.VehicleID=vehicletable.VehicleID and `table`.TableID=vehicletable.tableid and UserID='$suid[$i]'";
	}
	$query_vehicle=$query_vehicle."  ORDER BY `TableID` ASC"; 
}

$result_vehicle = mysql_query($query_vehicle,$DbConnection);
$num12 = mysql_num_rows($result_vehicle);

$default_size=0;
while($row1 = mysql_fetch_object($result_vehicle))
{
	$vehicleids_def[$default_size]=$row1->VehicleID;	
	$vehiclename_def[$default_size]=$row1->VehicleName;		
	$default_size++;
}

mysql_free_result($result_vehicle);

$vehicles="";
for($j=0;$j<=$default_size-1;$j++)
{				
	if($j!=$default_size-1)
	{
		$vehicles = $vehicles.$vehicleids_def[$j];	
		$vehicles=$vehicles.",";
	}
	else
	{		
		$vehicles = $vehicles.$vehicleids_def[$j];
	}
}

//date_default_timezone_set('Asia/Calcutta');
$current_time = date('Y/m/d H:i:s');					
$time_today = explode(" ",$current_time);

$start_dt = $time_today[0]." 00:00:00";
$end_dt = $current_time;

$start_dt = str_replace("/","-",$start_dt);
$end_dt = str_replace("/","-",$end_dt);

$current_status = 0;

$k = 0;

for($i=0;$i<$num_table_ids;$i++)       // for selecting current vehicle
{		
	if($i==0)
	{
		$Query3="Select Max(DateTime) as DateTime, vehicle.VehicleID from $tablename_1[$i],vehicle WHERE $tablename_1[$i].VehicleID=vehicle.VehicleID and  $tablename_1[$i].VehicleID IN($vehicles) and DateTime between '$start_dt' and '$end_dt' GROUP BY vehicle.VehicleID";
	}
	else
	{
		$Query3 = $Query3." UNION Select Max(DateTime) as DateTime, vehicle.VehicleID from $tablename_1[$i],vehicle WHERE $tablename_1[$i].VehicleID=vehicle.VehicleID and  $tablename_1[$i].VehicleID IN($vehicles) and DateTime between '$start_dt' and '$end_dt' GROUP BY vehicle.VehicleID";						
	}
}								


$QueryResult = mysql_query($Query3, $DbConnection);	

$c=0;
while($row=mysql_fetch_object($QueryResult))
{
	$current_vehicle[$c] = $row->VehicleID;
	$c++;
}

////////////////////////////////////////////////////////////
if($default_size)
{
	echo'<select name="vehicleid">';

	$v=1;
	for($i=0;$i<$default_size;$i++)
	{		
		$flag=0;
		for($k=0;$k<$c;$k++)
		{
			if($vehicleids_def[$i]==$current_vehicle[$k])
			{
				$flag=1;
			}
		}

			if($flag==1)
			{
				$current_status = 1;
			}
			elseif($flag==0)
			{
				$current_status = 0;
			}


		
			echo'<option value="'.$vehicleids_def[$i];echo'">';
					if($current_status)
					{								
					echo'
					<font color="#FF0000" face="Verdana" size="2">'.$vehiclename_def[$i].'</FONT>
					';
					}
					else
					{							
					echo'
					<font color="#000000" face="Verdana" size="2">'.$vehiclename_def[$i].'</FONT>
					';
					}
				echo'
				</option>';
				
	}	//for closed

	echo'</select>';
}

else
{
	print"<center><FONT color=\"Red\"><strong>No Vehicle Exists</strong></font></center>";
	//echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=Admin.php\">";
}
?>																							
