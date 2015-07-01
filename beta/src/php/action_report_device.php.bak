<?php
	include_once('main_vehicle_information_1.php');
	include_once('Hierarchy.php');	
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include_once('coreDb.php');
	$account_id_local1 = $_POST['account_id_local'];
	//echo $account_id_local1;
	
	$root = $_SESSION['root'];
	$DEBUG = 0;	
	$option_str = $_POST['option']; 
	$option1 = explode(':',$option_str);
	$size_option = sizeof($option1);	
	$deviceDataArr=getDeviceImeiNoAr($account_id_local1,$DbConnection);
	$query = "SELECT DISTINCT device_imei_no FROM device_assignment WHERE account_id='$account_id_local1' AND status=1";
	$result = mysql_query($query,$DbConnection);
	foreach($deviceDataArr as $dValye)
	{
		$device[] = $dValye['device_imei_no'];
	}
		 
	$dsize = sizeof($device);
	echo'<form method="post" target="_blank">';
	 $title='Device Details';
	 echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title\">";
				
        $csv_string = "";
		$csv_string = $csv_string." Device Report\n";
		$csv_string = $csv_string."SNo,";

		$csv_string = $csv_string."IMEI No,";

		$csv_string = $csv_string."Vehicle Name,";
       
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
		<table border=0 width ="50%" cellspacing=2 cellpadding=0 align="center">
			<tr>
				<td height=10 class="report_heading" align="center">
					Device Report
				</td>
			</tr>
		</table>
		<br>';
	echo '<div style="height:400px;overflow:auto;">';
	echo'<table border=1 rules=all width="60%" align="center" cellspacing=0 cellpadding=3>';	
	$j=0;
	for($i=0;$i<$dsize;$i++)
  	{
		$j++;
		if($i==0)
		{
			echo'<tr>
			<td class="text" align="left" width="12%">
			<b>&nbsp;SNo</b>
			</td>';

			echo'<td class="text" align="left" width="40%">
			<b>&nbsp;Device IMEI No</b>
			</td>';

			echo'<td class="text" align="left">
			<b>&nbsp;Vehicle Name</b>
			</td>';

			echo'</tr>';
		}
		echo"<input TYPE=\"hidden\" VALUE=\"$j\" NAME=\"temp[$i][SNo]\">";
		$vehicle_info=get_vehicle_info($root,$device[$i]);
		$vehicle_detail_local=explode(",",$vehicle_info);
		echo'<tr>
				<td class="text" align="left">
					&nbsp;'.$j.'
				</td>';
				$csv_string=$csv_string.$j.",";
				echo'<td class="text" align="left">
						&nbsp;'.$device[$i].'
					</td>';	
				echo"<input TYPE=\"hidden\" VALUE=\"$device[$i]\" NAME=\"temp[$i][IMEI No]\">";	
				$csv_string=$csv_string.$device[$i].",";

				echo'<td class="text" align="left">
						&nbsp;'.$vehicle_detail_local[0].'
					</td>';
				echo"<input TYPE=\"hidden\" VALUE=\"$vehicle_detail_local[0]\" NAME=\"temp[$i][Vehicle Name]\">";  
				$csv_string=$csv_string.$vehicle_detail_local[0].",";
								
				$csv_string=$csv_string." \n";
		echo'</tr>';
	}
	
	echo'
	</table>
	</div>
	<center>
	<input TYPE="hidden" VALUE="device" NAME="csv_type">
	<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">			
	<input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type3.php?size='.$dsize.'\');" value="Get PDF" class="noprint">
	&nbsp;
	<input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">
	&nbsp;
	</center>
</form>';
//echo "fileName=".$filename."title=".$title1."<br>";
echo'<center>
		<a href="javascript:showCommonPrevPage(\'report_common_prev.htm\',\''.$filename.'\',\''.$title1.'\');" class="back_css">
			&nbsp;<b>Back</b>
		</a>
	</center>';
?>
					
