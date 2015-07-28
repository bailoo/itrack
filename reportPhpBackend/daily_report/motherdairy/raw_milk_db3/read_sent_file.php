<?php
function read_sent_file($read_excel_path)
{
	global $SNo;						//SENT FILE
	global $LRNo;
	global $Vehicle;
	global $TankerType;
	global $FAT_kg;
	global $SNF_kg;
	global $QTY;
	global $DriverName;
	global $TranspoterName;
	global $Initial_MilkAge;
	global $Chilling_Plant;
	//global $Chilling_PlantName;
	global $Target_Plant;				//$StationNo
	//global $Target_PlantName;
	global $Manual_DisptachTime;	
	global $Manual_CloseTime;
	global $GPRS_CloseTime;
	global $Est_UnloadTime;
	global $Diff_inCloseTime;
	global $Plant_OutDate;				//$DepartureDate
	global $Plant_OutTime;				//$DepartureTime
	global $Plant_InDate;				//$ArrivalDate
	global $Plant_InTime;				//$ArrivalTime	
	global $GPRS_DispatchTime;
	global $Plant_HaltTime;				//$HaltDuration
	global $Server_CloseTime;
	global $Transportation_Age;
	global $Final_MilkAge;
	global $Target_ArrivalTime;			//$ScheduleDateTime
	global $Delay_InArrival;			//$Delay
	global $IMEI;
	
	global $DBSNO;
	/*global $Lat;
	global $Lng;
	global $DistVar;
	global $C_Lat;
	global $C_Lng;
	global $C_DistVar;
	global $Status;*/
	
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
				$sno_tmp = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();

				if($sno_tmp=="")
				{
					$read_completed = true;
					break;
				}
							
				if($row>1)
				{
					//echo "\nRecord:".$row;
					$SNo[] = $sno_tmp;
					//echo "\nRow=".$row." ,Val=".$objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					
					$tmp_val="B".$row;
					$LRNo[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="C".$row;
					$Vehicle[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="D".$row;
					$TankerType[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();					

					$tmp_val="E".$row;
					$FAT_kg[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="F".$row;
					$SNF_kg[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();					

					$tmp_val="G".$row;
					$QTY[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="H".$row;
					$DriverName[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="I".$row;
					$TranspoterName[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="J".$row;
					$Initial_MilkAge[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="K".$row;
					$Chilling_Plant[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="L".$row;
					$Target_Plant[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();					
					
					$tmp_val="O".$row;
					$Manual_DisptachTime[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					//$tmp_val="M".$row;
					//$GPRS_DispatchTime[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');

					/*$tmp_val="N".$row;
					$Plant_InDate[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd');					

					$tmp_val="P".$row;
					$Plant_InTime[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');					
					*/
					$tmp_val="U".$row;
					$Manual_CloseTime[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					//$tmp_val="Q".$row;
					//$GPRS_CloseTime[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="V".$row;
					$Est_UnloadTime[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="W".$row;
					$Diff_inCloseTime[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();					
					
					$tmp_val="P".$row;
					$Plant_OutDate[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd');					

					$tmp_val="Q".$row;
					$Plant_OutTime[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');					
					
					$tmp_val="X".$row;
					$Plant_HaltTime[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');					

					$tmp_val="Y".$row;
					$Server_CloseTime[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="Z".$row;
					$Transportation_Age[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="AA".$row;
					$Final_MilkAge[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="AB".$row;
					$Target_ArrivalTime[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="AC".$row;
					$Delay_InArrival[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="AD".$row;
					$imei_tmp = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					$imei_tmp = number_format($imei_tmp,0,'','');
					$IMEI[] = $imei_tmp;
					
					$tmp_val = 'BU'.$row;					
					$DBSNO[] = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();					
					
					/*$tmp_val="AC".$row;
					$Lat[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="AD".$row;
					$Lng[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="AE".$row;
					$DistVar[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
										
					$tmp_val="AF".$row;
					$C_Lat[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="AG".$row;
					$C_Lng[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="AH".$row;
					$C_DistVar[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
						
					$tmp_val="BT".$row;
					$Status[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();*/
					
					break;
				}				
			//}		
		}
		if($read_completed)
		{
			break;
		}
	}
}

?>
