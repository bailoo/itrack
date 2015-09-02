<?php
function read_sent_file($read_excel_path)
{
	global $Vehicle;			//SENT FILE
	global $SNo;
	global $StationNo;
	global $Type;
	global $RouteNo;
	global $ReportShift;
	global $HourBand;
	global $ArrivalDate;
	global $ArrivalTime;
	global $DepartureDate;
	global $DepartureTime;
	global $ScheduleDate;
	global $ScheduleTime;
	global $Delay;
	global $HaltDuration;
	global $Remark;
	global $ReportDate1;
	global $ReportTime1;
	global $ReportDate2;
	global $ReportTime2;
	global $TransporterM;
	global $TransporterI;
	global $Plant;
	//global $Km;
	global $Lat;
	global $Lng;
	global $DistVar;
	global $IMEI;
	global $RouteType;
	global $NO_GPS;
	global $VisitStatus;

	//global $PlantLat;
	//global $PlantLng;
	global $PlantCoord;
	global $PlantDistVar;
	global $Status;
	global $PlantInDate;
	global $PlantInTime;
	global $PlantOutDate;
	global $PlantOutTime;
	
	global $PlantOutScheduleDate;
	global $PlantOutScheduleTime;
	global $PlantOutDelay;
	/*//####################
	global $Vehicle_CI;
	global $StationNo_CI;
	global $RouteNo_CI;
	global $ArrivalTime_CI;
	//####################*/	

	global $RedRoute;
	global $RedCustomer;
	global $unmapped_customers;
	
	echo "\nREAD_SENT_FILE";
	echo "\nPath=".$read_excel_path;
	//######### SENT FILE NAME CLOSED 			
	$objPHPExcel_1 = null;
	$objPHPExcel_1 = PHPExcel_IOFactory::load($read_excel_path);	

	$cellIterator = null;
	$column = null;
	$row = null;

	//################ FIRST TAB ############################################
	$read_completed = false;
	$read_red = false;
	foreach ($objPHPExcel_1->setActiveSheetIndex(0)->getRowIterator() as $row) 
	{
		$cellIterator = $row->getCellIterator();
		$cellIterator->setIterateOnlyExistingCells(false);
		$i=0;
		foreach ($cellIterator as $cell) 
		{
			//if (!is_null($cell)) 
			//{
				$column = $cell->getColumn();
				$row = $cell->getRow();
				//if($row > $sheet2_row_count)				
				
				$tmp_val="A".$row;
				$vehicle_tmp = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();

				if($vehicle_tmp=="")
				{
					$read_completed = true;
					break;
				}
				if($vehicle_tmp==":")
				{
					//echo "\nREAD RED ROUTE";
					$row = $cell->getRow();
					$read_red=true;
				}				
				if(($read_red) && ($vehicle_tmp!=":"))
				{					
					$tmp_val="A".$row;
					$red_route_tmp = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();					
					$RedRoute[] = $red_route_tmp;
					$tmp_val="B".$row;
					$red_customer_tmp = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					$RedCustomer[] = $red_customer_tmp;	
					break;					
				}	
				
				if((!$read_red) && ($row>1))
				{
					//echo "\nRecord:".$row;
					$Vehicle[] = $vehicle_tmp;
					//echo "\nRow=".$row." ,Val=".$objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					
					$tmp_val="B".$row;
					$SNo[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="C".$row;
					$station_tmp = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					$StationNo[] = $station_tmp;

					$tmp_val="D".$row;
					$Type[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="E".$row;
					$route_tmp = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					$RouteNo[] = $route_tmp;

					$tmp_val="F".$row;
					$ReportShift[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="G".$row;
					$Plant[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="H".$row;
					$HourBand[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="I".$row;
					$ArrivalDate[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd');					

					$tmp_val="J".$row;
					$arrival_tmp = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');					

					$ArrivalTime[] = $arrival_tmp;
					
					$tmp_val="K".$row;
					$DepartureDate[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd');					

					$tmp_val="L".$row;
					$DepartureTime[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');					

					$tmp_val="M".$row;
					$ScheduleDate[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd');					

					$tmp_val="N".$row;
					$ScheduleTime[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');					

					$tmp_val="O".$row;
					$Delay[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="P".$row;
					$HaltDuration[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');					

					$tmp_val="Q".$row;
					$Remark[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="R".$row;
					$PlantInDate[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd');					

					$tmp_val="S".$row;
					$PlantInTime[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');					

					$tmp_val="T".$row;
					$PlantOutDate[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd');					

					$tmp_val="U".$row;
					$PlantOutTime[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');

					$tmp_val="V".$row;
					$PlantOutScheduleDate[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd');					
					
					$tmp_val="W".$row;
					$PlantOutScheduleTime[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');					

					$tmp_val="X".$row;
					$PlantOutDelay[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="Y".$row;
					$TransporterM[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="Z".$row;
					$TransporterI[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();					

					$tmp_val="AA".$row;
					$RouteType[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="AB".$row;
					$NO_GPS[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="AC".$row;
					$Lat[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="AD".$row;
					$Lng[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="AE".$row;
					$DistVar[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="AF".$row;
					$imei_tmp = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					$imei_tmp = number_format($imei_tmp,0,'','');
					$IMEI[] = $imei_tmp;					

					$tmp_val="AG".$row;
					$PlantCoord[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();					

					$tmp_val="AH".$row;
					$PlantDistVar[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();					

					$tmp_val="AI".$row;
					$Status[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();					
										
					$tmp_val="AJ".$row;
					$ReportDate1[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd');					

					$tmp_val="AK".$row;
					$ReportTime1[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');					
					
					$tmp_val="AL".$row;
					$ReportDate2[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd');					

					$tmp_val="AM".$row;
					$ReportTime2[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');					
	
					$pos_c = strpos($station_tmp, "@");
					if($pos_c !== false)
					{
						//echo "\nNegative Found";
						$customer_at_the_rate1 = explode("@", $station_tmp);											
					}
					else
					{
						$customer_at_the_rate1[0] = $station_tmp;
					}
					//echo "<br>CustomerRead=".$customer_at_the_rate1[0];
					$tmp_val="AN".$row;
					//$VisitStatus[trim($vehicle_tmp)][trim($customer_at_the_rate1[0])] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					//$VisitStatus[trim($route_tmp)][trim($customer_at_the_rate1[0])] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					$status_pink = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					if($status_pink=="1")
					{
						$VisitStatus[trim($route_tmp)][trim($customer_at_the_rate1[0])] = $status_pink;
					}
					break;
				}				
			//}		
		}
		if($read_completed)
		{
			break;
		}
	}	
	//#### READ FIRST TAB CLOSED ################################################	


	//################  READ THIRD SHEET ###################################
	$column = null;
	$row = null;
	foreach ($objPHPExcel_1->setActiveSheetIndex(2)->getRowIterator() as $row) 
	{
		$cellIterator = $row->getCellIterator();
		$cellIterator->setIterateOnlyExistingCells(false);
		$i=0;
		foreach ($cellIterator as $cell) 
		{
			$column = $cell->getColumn();
			$row = $cell->getRow();
			//if($row > $sheet2_row_count)				
			
			$tmp_val="A".$row;
			$route_tmp = $objPHPExcel_1->getActiveSheet(2)->getCell($tmp_val)->getValue();
			
			$tmp_val="E".$row;
			$unmapped_customers[$route_tmp] = $objPHPExcel_1->getActiveSheet(2)->getCell($tmp_val)->getValue();
		}
	}
	//######### SORT WITH RESPECT TO ROUTES ###########################
	/*$Vehicle_CI = $Vehicle;
	$StationNo_CI = $StationNo;
	$RouteNo_CI = $RouteNo;
	$ArrivalTime_CI = $ArrivalTime;
	
	for($x = 1; $x < sizeof($RouteNo_CI); $x++) 
	{
		$tmp_vehicle_ci = $Vehicle_CI[$x];
		$tmp_station_ci = $StationNo_CI[$x];
		$tmp_route_ci = $RouteNo_CI[$x];
		$tmp_arrival_ci = $ArrivalTime_CI[$x];		
		///////////      				

		$z = $x - 1;
		$done = false;
		while($done == false)
		{
			$route_tmp1 = $RouteNo_CI[$z];			
			
			if ($route_tmp1 >$tmp_route_ci)
			{
				$Vehicle_CI[$z + 1] = $Vehicle_CI[$z];
				$StationNo_CI[$z + 1] = $StationNo_CI[$z];				
				$RouteNo_CI[$z + 1] = $RouteNo_CI[$z];
				$ArrivalTime_CI[$z + 1] = $ArrivalTime_CI[$z];				
				//////////////////
				$z = $z - 1;
				if ($z < 0)
				{
					$done = true;
				}
			}
			else
			{
				$done = true;
			}
		} //WHILE CLOSED

		$Vehicle_CI[$z + 1] = $tmp_vehicle_ci;
		$StationNo_CI[$z + 1] = $tmp_station_ci;		
		$RouteNo_CI[$z + 1] = $tmp_route_ci;
		$ArrivalTime_CI[$z + 1] = $ArrivalTime_CI[$z];				
	}*/	
	//#################################################################
}

?>
