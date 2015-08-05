<?php
function read_sent_file($read_excel_path)
{
	global $Vehicle;			//SENT FILE
	global $SNo;
	global $StationNo;
	global $Type;
	global $RouteNo;
	global $ReportShift;
	global $ArrivalDate;
	global $ArrivalTime;
	global $DepartureDate;
	global $DepartureTime;
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
	/*//####################
	global $Vehicle_CI;
	global $StationNo_CI;
	global $RouteNo_CI;
	global $ArrivalTime_CI;
	//####################*/	

	global $RedRoute;
	global $RedCustomer;	
	
	echo "\nREAD_SENT_FILE";
	//echo "\nPath=".$path;
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
					$ArrivalDate[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd');					

					$tmp_val="H".$row;
					$arrival_tmp = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');					

					$ArrivalTime[] = $arrival_tmp;
					
					$tmp_val="I".$row;
					$DepartureDate[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd');					

					$tmp_val="J".$row;
					$DepartureTime[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');					

					$tmp_val="K".$row;
					$ScheduleTime[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');					

					$tmp_val="L".$row;
					$Delay[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="M".$row;
					$HaltDuration[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');					

					$tmp_val="N".$row;
					$Remark[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="O".$row;
					$ReportDate1[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd');					

					$tmp_val="P".$row;
					$ReportTime1[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');					
					
					$tmp_val="Q".$row;
					$ReportDate2[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd');					

					$tmp_val="R".$row;
					$ReportTime2[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');					

					$tmp_val="S".$row;
					$TransporterM[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="T".$row;
					$TransporterI[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();					

					$tmp_val="U".$row;
					$Plant[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					//$tmp_val="U".$row;
					//$Km[] = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					
					$tmp_val="V".$row;
					$Lat[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="W".$row;
					$Lng[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="X".$row;
					$DistVar[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="Y".$row;
					$imei_tmp = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					$imei_tmp = number_format($imei_tmp,0,'','');
					$IMEI[] = $imei_tmp;
					
					$tmp_val="Z".$row;
					$RouteType[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="AA".$row;
					$NO_GPS[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();					

					//echo "\nRow=".$row." read";
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
