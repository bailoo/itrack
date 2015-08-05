<?php
function read_sent_file($read_excel_path, $min_max_temp_path)
{
	global $Vehicle;			//SENT FILE
	global $SNo;
	global $StationNo;
	global $StationName;
	global $Type;
	//global $RouteNo;
	global $ReportShift;
	global $ArrivalDate;
	global $ArrivalTime;
	global $DepartureDate;
	global $DepartureTime;
	global $ScheduleTime;
	global $Delay;
	global $HaltDuration;
	
	global $MinTemp;
	global $MinTempDate;
	global $MinTempTime;
	global $MaxTemp;
	global $MaxTempDate;
	global $MaxTempTime;
	
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
				
				if($row>1)
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
					$station_name_tmp = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					$StationName[] = $station_name_tmp;
					
					$tmp_val="E".$row;
					$Type[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					/*$tmp_val="E".$row;
					$route_tmp = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					$RouteNo[] = $route_tmp;

					$tmp_val="F".$row;
					$ReportShift[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();*/
					
					$tmp_val="F".$row;
					$ArrivalDate[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd');					

					$tmp_val="G".$row;
					$arrival_tmp = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');					

					$ArrivalTime[] = $arrival_tmp;
					
					$tmp_val="H".$row;
					$DepartureDate[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd');					

					$tmp_val="I".$row;
					$DepartureTime[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');					

					/*$tmp_val="H".$row;
					$ScheduleTime[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');					

					$tmp_val="L".$row;
					$Delay[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();*/

					$tmp_val="J".$row;
					$HaltDuration[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');					

					$tmp_val="K".$row;
					$InTemp[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="L".$row;
					$OutTemp[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="M".$row;
					$MinTemp[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="N".$row;
					$MinTempDate[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd');					

					$tmp_val="O".$row;
					$MinTempTime[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');	
					
					$tmp_val="P".$row;
					$MaxTemp[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="Q".$row;
					$MaxTempDate[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd');					

					$tmp_val="R".$row;
					$MaxTempTime[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');						

					
					$tmp_val="S".$row;
					$Remark[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="T".$row;
					$ReportDate1[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd');					

					$tmp_val="U".$row;
					$ReportTime1[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');					
					
					$tmp_val="V".$row;
					$ReportDate2[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd');					

					$tmp_val="W".$row;
					$ReportTime2[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');					

					$tmp_val="X".$row;
					$TransporterM[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="Y".$row;
					$TransporterI[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();					

					//$tmp_val="".$row;
					//$Plant[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					//$tmp_val="U".$row;
					//$Km[] = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					
					$tmp_val="Z".$row;
					$Lat[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="AA".$row;
					$Lng[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="AB".$row;
					$DistVar[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="AC".$row;
					$imei_tmp = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					$imei_tmp = number_format($imei_tmp,0,'','');
					$IMEI[] = $imei_tmp;
					
					//$tmp_val="Z".$row;
					//$RouteType[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="AD".$row;
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
	
	//######## READ MIN MAX TEMPERATURE	
	//##################################
	global $LastTempVehicle;
	
	global $LastMinTemp;
	global $LastMinDate;
	global $LastMinTime;
	global $LastMaxTemp;
	global $LastMaxDate;
	global $LastMaxTime;
	global $objPHPExcel_2;	
	//######### EVENING FILE NAME CLOSED	
	$objPHPExcel_2 = PHPExcel_IOFactory::load($min_max_temp_path);	

	$cellIterator = null;
	$column = null;
	$row = null;

	foreach ($objPHPExcel_2->setActiveSheetIndex(0)->getRowIterator() as $row) 
	{
		$cellIterator = $row->getCellIterator();
		$cellIterator->setIterateOnlyExistingCells(false);

		foreach ($cellIterator as $cell) 
		{
			if (!is_null($cell)) 
			{
				$column = $cell->getColumn();
				$row = $cell->getRow();
				//if($row > $sheet2_row_count)
				if($row>1)
				{										
					$tmp_val="A".$row;
					//echo "<br>VehicleRead=".$objPHPExcel_2->getActiveSheet(0)->getCell($tmp_val)->getValue();
					$LastTempVehicle[] = $objPHPExcel_2->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="B".$row;
					//echo "<br>MinRead=".$objPHPExcel_2->getActiveSheet(0)->getCell($tmp_val)->getValue();
					$LastMinTemp[] = $objPHPExcel_2->getActiveSheet(0)->getCell($tmp_val)->getValue();					
					
					$tmp_val="C".$row;
					$LastMinDate[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_2->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd');					

					$tmp_val="D".$row;
					$LastMinTime[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_2->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');

					$tmp_val="E".$row;
					$LastMaxTemp[] = $objPHPExcel_2->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="F".$row;
					$LastMaxDate[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_2->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd');					

					$tmp_val="G".$row;
					$LastMaxTime[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_2->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');										
							
					break;				
				}			
			}		
		}
	}
	//#############	MIN MAX TEMPERATURE CLOSED
}

?>
