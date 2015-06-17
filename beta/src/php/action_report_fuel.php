<?php
	include_once('lib/BUG.php');
	include_once('lib/Util.php');
	include_once('lib/VTSFuel.php');
	include_once("report_title.php");
	include_once('lib/VTSMySQL.php'); 
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	 
	$imeis = explode(":",$_POST['vehicleserial']);  ////vehicleserial is actually vehicle_id for this report
	//echo "veh=".$_POST['vehicleserial']."<br>";
	$startDateTime = str_replace("/","-",$_POST['start_date']);
	$endDateTime = str_replace("/","-",$_POST['end_date']);
	$timeInterval = $_POST['user_interval'];
	
	$startDate = date('Y-m-d', strtotime($startDateTime));
	$endDate = date('Y-m-d', strtotime($endDateTime));
	$endDate1 = date('Y-m-d', strtotime($endDate)+(1*24*60*60));
	$timeIntervalTS = $timeInterval*60;
	
	
	/*if($_SERVER["REMOTE_ADDR"]== "202.3.77.11")
	{
	 echo "<br>imeis:".$imeis[0]." startDateTime:".$startDateTime." ,startDate:".$startDate." ,endDateTime:".$endDateTime." ,enddate:".$endDate." enddate1:".$endDate1." ,timeIntervalTS:".$timeIntervalTS;
  }*/

	$time_list = UTIL::getAllTimes($startDateTime, $endDateTime, $timeIntervalTS);
	$datetime_now = date("Y:m:d H:i:s", time()); 	
	$vsize =0; 	
  foreach($imeis as $imei)
	{ 	
		//echo "imie=".$imei."<br>";
	  $fuel_data = VTSFuel::getFuelData($DbConnection, $imei, $startDate, $endDate1);		
		if(sizeof($time_list)>0)
		{
			foreach($time_list as $datetime)
			{
				if(strtotime($datetime) <= strtotime($datetime_now))
				{
					$fuel_datetime =  VTSFuel::interpolateFuelData($fuel_data, $datetime);
					if($fuel_datetime >= 0)
					{
						$fuel[$imei][$datetime] = $fuel_datetime;
					}
				}
			}
		}		
	 $vsize++;	
	}
?>

<?php
  echo'<center><br>
        <form method = "post" target="_blank">';  
        $csv_string = ""; 
        $cnt=0;  
        $x=0;                         
        $imei_size =0; 
        
        $fuel_io_global1 = VTSFuel::$fuel_io_global;
        $max_fuel_io1 = VTSFuel::$max_fuel_io;
        $max_fuel_value1 = VTSFuel::$max_fuel_value;
                              
        /*$fuel_tmp = UTIL::getXMLData('/'.$fuel_io_global1.'="[^"]+"/', $line);
                                        
        if($fuel_tmp > $max_fuel_io1)
        {
          $fuel_imei = $max_fuel_value1;
        }
        
        if(($_SERVER['HTTP_X_FORWARDED_FOR']) == "172.26.48.189")
        {
          echo "<br>fuel_io_global1:".$fuel_io_global1." ,max_fuel_io1:".$max_fuel_io1." ,max_fuel_value1:".$max_fuel_value1." ,fuel_tmp:".$fuel_tmp;
        } */               
         
  
		$display_count=0;
        foreach($imeis as $imei)
        {		    
			$fuel_size =0;
			foreach($fuel[$imei] as $datetime => $fuel_imei)
			{
				$fuel_size=1;
			}
			if($fuel_size)
			{
				if($display_count==0)
				{
					echo'<div style="overflow: auto;height: 350px; width: 620px;" align="center">'; 
				}
				$display_count=1;
				$imei_size =1;
				$vname = VTSMySQL::getVehicleNameOfVehicle($DbConnection, $imei);
				$imei1 = VTSMySQL::getIMEIOfVehicle($DbConnection, $imei);            
				report_title("Fuel Report for " . $vname."&nbsp; <font color=red>(".$imei1.")</font><br>&nbsp;&nbsp;", $startDateTime, $endDateTime);        		
				$title = $vname."(".$imei1.") Fuel Report For : ".$startDateTime."-".$endDateTime;
				$csv_string = $csv_string.$title."\n";
				$csv_string = $csv_string."SNo,Date Time,Fuel level\n";
				echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[]\">";            
				echo '<center>           
						<table border=1 width="60%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3 align="center">	
							<tr>							
								<td class="text" align="left" width="50px;">
									<b>SNo</b>
								</td>
								<td class="text" align="left">
									<b>Date Time </b>
								</td>
								<td class="text" align="left">
									<b>Fuel</b>
								</td>          						
							</tr>';
							$sno=1;  
							$y=0;         
							foreach($fuel[$imei] as $datetime => $fuel_imei)
							{                                                                                         
								$cnt++;
								if($fuel_imei<1000)
								{
									/*if(($_SERVER['HTTP_X_FORWARDED_FOR']) == "172.26.48.189")
									{
									echo "<br>fuel_io_global1:".$fuel_io_global1." ,max_fuel_io1:".$max_fuel_io1." ,max_fuel_value1:".$max_fuel_value1." ,fuel_imei:".$fuel_imei;
									}*/                         
									$fuel_imei_1 = $fuel_imei;
									if($fuel_imei_1 > $max_fuel_value1)
									{
										//if(($_SERVER['HTTP_X_FORWARDED_FOR']) == "172.26.48.189") echo "<br>IN";
										$fuel_imei_1 = $max_fuel_value1;
									}                      
								echo'<tr>
										<td class="text" align="left">
											<b>'.$cnt.'</b>
										</td>
										<td class="text" align="left">
											'.$datetime.'
										</td>
										<td class="text" align="left">
											'.$fuel_imei_1.'
										</td>
									</tr>';                
									echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$x][$y][SNo]\">
										<input TYPE=\"hidden\" VALUE=\"$datetime\" NAME=\"temp[$x][$y][DateTime]\">
										<input TYPE=\"hidden\" VALUE=\"$fuel_imei_1\" NAME=\"temp[$x][$y][Fuel level(litres)]\">";      			
										$csv_string = $csv_string.$sno.','.$datetime.','.$fuel_imei_1."\n";
									$y++;
									$sno++;
								}             		
							}
						echo "</table>";
						$cnt=0;    		
						echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][SNo]\">";
						echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][DateTime]\">";                									
						echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][Fuel level(%)]\">";
						$x++;
			} // if fuel_size closed
		}
		if($imei_size==1) /////// if no fuel record found this will not work
		{		
			echo'</div>';
		}
  
	echo'<table align="center">
			<tr>
				<td>';
				if($imei_size==0)
				{						
					print"<br><br>
							<center>
								<FONT color=\"Red\" size=2>
									<strong>
										No Fuel Record found
									</strong>
								</font>
							</center>";              
				}	
				else
				{
					echo'<input TYPE="hidden" VALUE="fuel" NAME="csv_type">';
					echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';                 
					echo'<br><center><input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type4.php?size='.$vsize.'\');" value="Get PDF" class="noprint">&nbsp;<input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
					<!--<input type="button" value="Print it" onclick="window.print()" class="noprint">-->&nbsp;';      
				}                  
			echo'</td>		
			</tr>
		</table>
    </form>
  </center>'; 
   
?>
