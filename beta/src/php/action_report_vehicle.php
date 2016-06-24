<?php
	include_once('main_vehicle_information_1.php');
	include_once('Hierarchy.php');	
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
        include_once('user_type_setting.php');
	$root = $_SESSION['root'];
	$DEBUG = 0;	
	$option_str = $_POST['option']; 
	$option1 = explode(':',$option_str);
	$size_option = sizeof($option1);	
	for($i=0;$i<$size_option;$i++)
	{
		if($option1[$i] == "1")
		$name = "1"; 
		
		if($option1[$i] == "2")
		$imei = "1";  
		
		if($option1[$i] == "3")
		$number = "1";
		
		if($option1[$i] == "4")
		$max_speed = "1";  
		
		if($option1[$i] == "5")
		$type = "1"; 
		
		if($option1[$i] == "6")
		$tag = "1";    
		/* if($option1[$i] == "7")
		$geo_name = "1";     
		if($option1[$i] == "8") 
		$route_name = "1";*/   
	}	
	
	$device_str = $_POST['vehicleserial'];
	$device = explode(':',$device_str);
	//echo "device_str=".$device_str."<br>";
	$vsize = sizeof($device);
	echo'<form method="post" target="_blank">';
	 $title=$report_type.' Details';
	 echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title\">";
				
        $csv_string = "";
		$csv_string = $csv_string." ".$report_type." Report\n";
		$csv_string = $csv_string."SNo,";
		if($imei) 
		{
			$csv_string = $csv_string."IMEI No,";
		}		
        if($name)
		{
			$csv_string = $csv_string."Vehicle Name,";
		}       
        if($number) 
		{
			$csv_string = $csv_string."Vehicle Number,";	
		}
        if($max_speed)
		{
			$csv_string = $csv_string."Max Speed,";
		}
        if($type) 
		{
			$csv_string = $csv_string."Type,";
		}
        if($tag) 
		{
			$csv_string = $csv_string."Tag,";
		}
		$csv_string=$csv_string." \n";    
        /*if($geo_name)
		{
			$str7 = "Geofence,";
		}
        if($route_name) 
		{
			$str8 = "Route";
		}*/
		/*for($i=0;$i<$vsize;$i++)
		{
			$vehicle_info=get_vehicle_info($root,$device[$i]);
			echo "vehicle_info=".$vehicle_info."<br>";
			$vehicle_detail_local=explode(",",$vehicle_info);
			$vehicle_detail_cnt=count($vehicle_detail_local);
		}*/
	echo'<br><br>
		<table border=0 width = 100% cellspacing=2 cellpadding=0>
			<tr>
				<td height=10 class="report_heading" align="center">
					'.$report_type.' Report
				</td>
			</tr>
		</table>
		<br>';
	//echo'<table border=1 rules=all width="95%" align="center" cellspacing=0 cellpadding=3>';
        echo'<center><table cellspacing=0 cellpadding=0 class="table-bordered table-hover" style="width:90%">';	
	$j=0;
	for($i=0;$i<$vsize;$i++)
  	{
		$j++;
		if($i==0)
		{
		echo'<tr><thead class="alert-warning">
				<th class="text" align="left">
					<b>&nbsp;SNo</b>
				</th>';
				if($imei)
				{
					echo'<th class="text" align="left">
							<b>&nbsp;IMEI No</b>
						</th>';
				}	
				if($name)
				{
				echo'<th class="text" align="left">
						<b>&nbsp;Vehicle Name</b>
					</th>';
				}
				if($number)
				{
					echo'<th class="text" align="left">
							<b>&nbsp;Vehicle Number</b>
						</th>';
				}

				if($max_speed)
				{					
					echo'<th class="text" align="left">
							<b>&nbsp;Max Speed</b>
						</th>';
				}

				if($type)
				{
					echo'<th class="text" align="left">
							<b>&nbsp;Type</b>
						</th>';
				}

				if($tag)
				{					
					echo'<th class="text" align="left">
							<b>&nbsp;Tag</b>
						</th>';
				}

				/*if($geo_name)
				{
					echo'<td class="text" align="left">
							<b>Geofence Name</b>
						</td>';	
				}

				if($route_name)
				{
					echo'<td class="text" align="left">
							<b>Route Name</b>
						</td>';
				}*/
		echo'</tr></thead>';
		}
		echo"<input TYPE=\"hidden\" VALUE=\"$j\" NAME=\"temp[$i][SNo]\">";
		$vehicle_info=get_vehicle_info($root,$device[$i]);
		$vehicle_detail_local=explode(",",$vehicle_info);
		echo'<tr>
				<td class="text" align="left">
					&nbsp;'.$j.'
				</td>';
				$csv_string=$csv_string.$j.",";
				if($imei)
				{
				echo'<td class="text" align="left">
						&nbsp;'.$device[$i].'
					</td>';	
			echo"<input TYPE=\"hidden\" VALUE=\"$device[$i]\" NAME=\"temp[$i][IMEI No]\">";	
				$csv_string=$csv_string.$device[$i].",";
				}
				if($name)
				{
				echo'<td class="text" align="left">
						&nbsp;'.$vehicle_detail_local[0].'
					</td>';
				echo"<input TYPE=\"hidden\" VALUE=\"$vehicle_detail_local[0]\" NAME=\"temp[$i][Vehicle Name]\">";  
				$csv_string=$csv_string.$vehicle_detail_local[0].",";
				}				
				if($number)
				{
				echo'<td class="text" align="left">
						&nbsp;'.$vehicle_detail_local[2].'
					</td>';	
				echo"<input TYPE=\"hidden\" VALUE=\"$vehicle_detail_local[2]\" NAME=\"temp[$i][Vehicle Number]\">";
				$csv_string=$csv_string.$vehicle_detail_local[2].",";
				}
				if($max_speed)
				{
				echo'<td class="text" align="left">
						&nbsp;'.$vehicle_detail_local[4].'
					</td>';
			echo"<input TYPE=\"hidden\" VALUE=\"$vehicle_detail_local[4]\" NAME=\"temp[$i][Max Speed]\">";
				$csv_string=$csv_string.$vehicle_detail_local[4].",";
				}
				if($type)
				{
				echo'<td class="text" align="left">
						&nbsp;'.$vehicle_detail_local[1].'
					</td>';
				echo"<input TYPE=\"hidden\" VALUE=\"$vehicle_detail_local[1]\" NAME=\"temp[$i][Type]\">";	
				$csv_string=$csv_string.$vehicle_detail_local[1].",";
				}
				if($tag)
				{
				echo'<td class="text" align="left">
						&nbsp;'.$vehicle_detail_local[3].'
					</td>';
				echo"<input TYPE=\"hidden\" VALUE=\"$vehicle_detail_local[3]\" NAME=\"temp[$i][Tag]\">";
				$csv_string=$csv_string.$vehicle_detail_local[3].",";
				}
				$csv_string=$csv_string." \n";
		echo'</tr>';
	}
	echo'
	</table></center>
	<center>
	<input TYPE="hidden" VALUE="vehicle" NAME="csv_type">
	<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string"><br>			
	<input type="button" class="btn btn-default btn-sm" onclick="javascript:report_csv(\'src/php/report_getpdf_type3.php?size='.$vsize.'\');" value="Get PDF" class="noprint">
	&nbsp;
	<input type="button" class="btn btn-default btn-sm" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">
	&nbsp;
	</center>
</form>';
?>
					