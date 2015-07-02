<?php
include_once('main_report_vehicle_information.php');
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');

set_time_limit(300);
$DEBUG = 0;
$device_str = $_POST['vehicleserial']; 
$vserial = explode(':',$device_str);
$vsize=count($vserial);

$date1 = str_replace("/","-",$start_date);
$date2 = str_replace("/","-",$end_date);




if($vsize>0)
{
  $vid_local="";
  for($i=0;$i<$vsize;$i++)
  {
    $vid_local=$vid_local.get_vehicle_id($root,$vserial[$i]).",";  
  }  
}
$vid_local=substr($vid_local,0,-1);
	
$getSAArr=getScheduleAssignmentData($vid_local,$date1,$date2,1,$DbConnection);
    
    
    if(count($getSAArr)>0)
    {
        echo'<form name="schedule_assignment" method="POST" target="_blank">';
        $csv_string="";
        $title1="Schedule Assignment Report From ".$date1." To ".$date2." ";
        echo"<input TYPE=\"hidden\" VALUE=\"$title1\" NAME=\"title\">";
        $csv_string = $csv_string.$title1."\n";
        $csv_string = $csv_string."SNo,Vehicle Name,Location Name,Date From,Date To,By Day,Min OT,Max OT,Rest Time,Min HT,Max HT,Min DT,Max DT,Intermediate HT\n";

		echo '	
			<div style="height:10px"></div>
			<table border=0 width = 100% cellspacing=2 cellpadding=0>
				<tr>
					<td height=10 class="report_heading" align="center">Schedule Assignment Report</td>
				</tr>
			</table>
			<div style="height:10px"></div>';
			
			echo'<div style="height:400px;overflow:auto;">';
		
			 echo'<table border=1 width="95%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
				  <tr>
						<td class="text" align="left"><b>SNo</b></td>
						<td class="text" align="left"><b>Vehicle Name</b></td>
						<td class="text" align="left"><b>Location Name</b></td>
						<td class="text" align="left"><b>Date From</b></td>
						<td class="text" align="left"><b>Date To</b></td>
						<td class="text" align="left"><b>By Day</b></td>
						<td class="text" align="left"><b>Min OT</b></td>
						<td class="text" align="left"><b>Max OT</b></td>
						<td class="text" align="left"><b>Rest Time</b></td>
						<td class="text" align="left"><b>Min HT</b></td>
						<td class="text" align="left"><b>Max HT</b></td>
						<td class="text" align="left"><b>Min DT</b></td>
						<td class="text" align="left"><b>Max DT</b></td>
						<td class="text" align="left"><b>Intermediate HT</b></td>        							
				  </tr>';
				  $sno=0;
				  $data_cnt=0;
				  foreach($getSAArr as $sAValue)
				  {
                                        $locationId=$sAValue['location_id'];
                                        $dateFrom=$sAValue['date_from'];
                                        $dateTo=$sAValue['date_to'];
                                        $byDay=$sAValue['by_day'];
                                        $minOperationTime=$sAValue['min_operation_time'];
                                        $maxOperationTime=$sAValue['max_operation_time'];
                                        $restTime=$sAValue['rest_time'];
                                        $minHaltTime=$sAValue['min_halt_time'];
                                        $maxHaltTime=$sAValue['max_halt_time'];
                                        $minDistanceTravelled=$sAValue['min_distance_travelled'];
                                        $maxDistanceTravelled=$sAValue['max_distance_travelled'];
                                        $intermediateHaltTime=$sAValue['Intermediate_halt_time'];
					$sno++;
					$vehicle_name_local=get_vehicle_name($root,$Row->vehicle_id);
				   echo '<tr>
							<td>'.$sno.'</td>
							<td>'.$vehicle_name_local.'</td> ';
							if($locationId!="")
							{
                                                          $sLArr=getScheduleLocationDetail($locationId,1,$DbConnection);                                                         
							
							  $localtion_str_name="";
							  $localtion_str_name_1="";
							  foreach($sLArr as $sLValue)
							  {
								 $localtion_str_name=$localtion_str_name.$sLValue['location_name'].',';					 
							  }				  
							  $localtion_str_name_1=substr($localtion_str_name,0,-1);   
							}
							else
							{
								$localtion_str_name_1="";
							}
									   
							echo'<td>'.$localtion_str_name_1.'</td>
							<td>'.$dateFrom.'</td>
							<td>'.$dateTo.'</td>
							<td>'.$byDay.'</td>
							<td>'.$minOperationTime.'</td>
							<td>'.$maxOperationTime.'</td>
							<td>'.$restTime.'</td>
							<td>'.$minHaltTime.'</td>
							<td>'.$maxHaltTime.'</td>
							<td>'.$minDistanceTravelled.'</td>
							<td>'.$maxDistanceTravelled.'</td>
							<td>'.$intermediateHaltTime.'</td>
						  </tr>'; 
						echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$data_cnt][Seral]\">";
						$csv_string = $csv_string.$sno;
						echo"<input TYPE=\"hidden\" VALUE=\"$vehicle_name_local\" NAME=\"temp[$data_cnt][Vehicle Name]\">";
						$csv_string = $csv_string.','.$vehicle_name_local;
						echo"<input TYPE=\"hidden\" VALUE=\"$localtion_str_name_1\" NAME=\"temp[$data_cnt][Location Name]\">";
						$csv_string = $csv_string.','.$localtion_str_name_1;
						echo"<input TYPE=\"hidden\" VALUE=\"$dateFrom\" NAME=\"temp[$data_cnt][Date From]\">";
						$csv_string = $csv_string.','.$dateFrom;
						echo"<input TYPE=\"hidden\" VALUE=\"$dateTo\" NAME=\"temp[$data_cnt][Date To]\">";
						$csv_string = $csv_string.','.$dateTo;
						echo"<input TYPE=\"hidden\" VALUE=\"$byDay\" NAME=\"temp[$data_cnt][By Day]\">";
						$csv_string = $csv_string.','.$byDay;
						echo"<input TYPE=\"hidden\" VALUE=\"$minOperationTime\" NAME=\"temp[$data_cnt][Min OT]\">";
						$csv_string = $csv_string.','.$minOperationTime;
						echo"<input TYPE=\"hidden\" VALUE=\"$maxOperationTime\" NAME=\"temp[$data_cnt][Max OT]\">";
						$csv_string = $csv_string.','.$maxOperationTime;
						echo"<input TYPE=\"hidden\" VALUE=\"$restTime\" NAME=\"temp[$data_cnt][Rest Time]\">";
						$csv_string = $csv_string.','.$restTime;
						echo"<input TYPE=\"hidden\" VALUE=\"$minHaltTime\" NAME=\"temp[$data_cnt][Min HT]\">";
						$csv_string = $csv_string.','.$minHaltTime;
						echo"<input TYPE=\"hidden\" VALUE=\"$maxHaltTime\" NAME=\"temp[$data_cnt][Max HT]\">";
						$csv_string = $csv_string.','.$maxHaltTime;
						echo"<input TYPE=\"hidden\" VALUE=\"$minDistanceTravelled\" NAME=\"temp[$data_cnt][Min DT]\">";
						$csv_string = $csv_string.','.$minDistanceTravelled;
						echo"<input TYPE=\"hidden\" VALUE=\"$maxDistanceTravelled\" NAME=\"temp[$data_cnt][Max DT]\">";
						$csv_string = $csv_string.','.$maxDistanceTravelled;
						echo"<input TYPE=\"hidden\" VALUE=\"$intermediateHaltTime\" NAME=\"temp[$data_cnt][Intermediate HT]\">";
						$csv_string = $csv_string.','.$intermediateHaltTime;
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
							No schedule assignment found during this date time
						</b>
					</font>
				</center>';
		}

?>								