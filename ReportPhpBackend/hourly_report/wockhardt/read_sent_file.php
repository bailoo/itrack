<?php
function read_sent_file($read_excel_path)
{
	global $VehicleName;
	global $SNo;
	global $VehicleID;
	global $BaseStation;	
	global $BSCoordinate;	
	global $BSExpectedDeptTime;
	global $BSExpectedArrTime;	
	global $VillageName;	
	global $VLCoordinate;	
	global $VLExpectedMinHaltDuration;	
	global $VLExpectedMaxHaltDuration;
	global $ActualBSDeptTime;	
	global $ActualBSArrTime;	
	global $DelayBSDept;	
	global $DelayBSArr;
	global $ActualVLArrTime;
	global $ActualVLDeptTime;	
	global $DelayVLArr;	
	global $DelayVLDept;	
	global $VLHaltDuration;
	global $VLHaltViolation;	
	global $TotalDistanceTravelled;	
	global $IMEI;	
	global $Remark;
	
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
					$VehicleName[] = $vehicle_tmp;
					//echo "\nRow=".$row." ,Val=".$objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					
					$tmp_val="B".$row;
					$SNo[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="C".$row;
					$VehicleID[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="D".$row;
					$BaseStation[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="E".$row;
					$BSCoordinate[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="F".$row;
					$BSExpectedDeptTime[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm');
					
					$tmp_val="G".$row;
					$BSExpectedArrTime[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm');					

					$tmp_val="H".$row;
					$VillageName[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="I".$row;
					$VLCoordinate[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="J".$row;
					$VLExpectedMinHaltDuration[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm');					

					$tmp_val="K".$row;
					$VLExpectedMaxHaltDuration[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm');					

					$tmp_val="L".$row;
					$ActualBSDeptTime[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm');

					$tmp_val="M".$row;
					$ActualBSArrTime[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm');					

					$tmp_val="N".$row;
					$DelayBSDept[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="O".$row;
					$DelayBSArr[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="P".$row;
					$ActualVLArrTime[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm');					
					
					$tmp_val="Q".$row;
					$ActualVLDeptTime[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm');

					//$tmp_val="R".$row;
					//$DelayVLArr[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm');					

					//$tmp_val="S".$row;
					//$DelayVLDept[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm');

					//$tmp_val="T".$row;
					//$VLHaltDuration[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm');
				
					//$tmp_val="U".$row;
					//$TotalDistanceTravelled[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="U".$row;
					$imei_tmp = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();										
					$imei_tmp = number_format($imei_tmp,0,'','');
					$IMEI[] = $imei_tmp;
					//$tmp_val="Y".$row;
					//$Remark[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();					
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
}


//###########
function read_sub_vehicles($read_excel_path)
{
	global $SubVehicles;
	
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
	foreach ($objPHPExcel_1->setActiveSheetIndex(0)->getRowIterator() as $row) 
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
			$vehicleNameSub=$objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
			$SubVehicles[$vehicleNameSub] =$vehicleNameSub;
			break;	
		}
	}
	//print_r($SubVehicles);
	//#### READ FIRST TAB CLOSED ################################################		
}

?>