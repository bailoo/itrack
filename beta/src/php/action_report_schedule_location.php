<?php
include_once('main_report_vehicle_information.php');
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
set_time_limit(300);
$date1 = str_replace("/","-",$start_date);
$date2 = str_replace("/","-",$end_date);
     
      $Query="SELECT * FROM schedule_location WHERE account_id='$account_id_local' AND create_date BETWEEN '$date1' AND '$date2' AND status=1";
      //echo "query=".$Query."<br>";
      $Result=mysql_query($Query,$DbConnection);
	   $NumRows=mysql_num_rows($Result);
	   if($NumRows>0)
	  {
	  echo'<form name="schedule_location_report" method="POST" target="_blank">';
		$csv_string="";
		$title1="Schedule Location Report From ".$date1." To ".$date2." ";
		echo"<input TYPE=\"hidden\" VALUE=\"$title1\" NAME=\"title\">";
		$csv_string = $csv_string.$title1."\n";
		$csv_string = $csv_string."SNo,Latitude,Longitude,Location Point,Create Date\n";
	
			echo '	
			<div style="height:10px"></div>
			<table border=0 width = 100% cellspacing=2 cellpadding=0>
				<tr>
					<td height=10 class="report_heading" align="center">'.$title1.'</td>
				</tr>
			</table>
			<div style="height:10px"></div>';
			echo'<div style="height:400px;overflow:auto;">';
			echo'<table border=1 width="95%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
				  <tr>
						<td class="text" align="left"><b>SNo</b></td>
						<td class="text" align="left"><b>location Name</b></td>
						<td class="text" align="left"><b>Location Point</b></td>
						<td class="text" align="left"><b>Create Date</b></td>           							
				  </tr>';			
		  $sno=0;
		  $data_cnt=0;
			while($Row=mysql_fetch_object($Result))
			{
				$sno++;
				echo '<tr>
						<td>'.$sno.'</td>
						<td>'.$Row->location_name.'</td> 
						<td>'.$Row->geo_point.'</td>
						<td>'.$Row->create_date.'</td>             
					</tr>'; 
					echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$data_cnt][Seral]\">";
						$csv_string = $csv_string.$sno;
						echo"<input TYPE=\"hidden\" VALUE=\"$Row->location_name\" NAME=\"temp[$data_cnt][Location Name]\">";
						$csv_string = $csv_string.','.$Row->location_name;
						echo"<input TYPE=\"hidden\" VALUE=\"$Row->geo_point\" NAME=\"temp[$data_cnt][Location Point]\">";
						$csv_string = $csv_string.','.$Row->geo_point;
						echo"<input TYPE=\"hidden\" VALUE=\"$Row->create_date\" NAME=\"temp[$data_cnt][Location Point]\">";
						$csv_string = $csv_string.','.$Row->create_date;						
						$csv_string=$csv_string."\n";	
						$data_cnt++;
			}		
		  echo'</table>
				  </div>';
				 
						echo'<br>
						<center>
							<input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type3.php?size='.($data_cnt-1).'\');" value="Get PDF" class="noprint">&nbsp;</td>
							<input TYPE="hidden" VALUE="dist_data" NAME="csv_type">
							<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">
							<input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
						</center>';
			echo'</form>';	 
		}
		else
		{
			echo '
				<br>
				<center> 
					<font color="red">
						<b>
							No location report found during this date time
						</b>
					</font>
				</center>';
		}

?>								