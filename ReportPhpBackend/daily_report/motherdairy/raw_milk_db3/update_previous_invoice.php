<?php

function read_update_records($read_excel_path)
{
	global $objPHPExcel_1;
	global $LRNo_DB;
	global $VehicleNo_DB;
	global $FAT_DB;
	global $SNF_DB;
	global $QTY_DB;
	global $DriverName_DB;
	global $TransporterName_DB;
	global $InitialMilkAge_DB;
	global $ChillingPlant_DB;
	global $TargetPlant_DB;
	global $ManualDispatchTime_DB;
	global $GPRSDispatchTime_DB;
	global $PlantInDate_DB;
	global $PlantInTime_DB;
	global $ManualCloseTime_DB;
	global $GPRSCloseTime_DB;
	global $EstUnloadTime_DB;
	global $DiffinCloseTime_DB;
	global $PlantOutDate_DB;
	global $PlantOutTime_DB;
	global $PlantHaltTime_DB;
	global $ServerCloseTime_DB;
	global $TransportationAge_DB;
	global $FinalMilkAge_DB;
	global $TargetArrivalTime_DB;
	global $DelayInArrival_DB;
	global $IMEI_DB;
	global $Latitude_DB;
	global $Longitude_DB;
	global $DistVar_DB;
	global $C_Lat_DB;
	global $C_Lng_DB;
	global $C_DistVar_DB;
	global $Door1OpenTime_1_DB;
	global $Door1CloseTime_2_DB;
	global $Door1OpenTime_3_DB;
	global $Door1CloseTime_4_DB;
	global $Door1OpenTime_5_DB;
	global $Door1CloseTime_6_DB;
	global $Door1OpenTime_7_DB;
	global $Door1CloseTime_8_DB;
	global $Door1OpenTime_9_DB;
	global $Door1CloseTime_10_DB;
	global $Door1OpenTime_11_DB;
	global $Door1CloseTime_12_DB;
	global $Door1OpenTime_13_DB;
	global $Door1CloseTime_14_DB;
	global $Door1OpenTime_15_DB;
	global $Door1CloseTime_16_DB;
	global $Door1OpenTime_17_DB;
	global $Door1CloseTime_18_DB;
	global $Door2OpenTime_19_DB;
	global $Door2CloseTime_20_DB;
	global $Door2OpenTime_21_DB;
	global $Door2CloseTime_22_DB;
	global $Door2OpenTime_23_DB;
	global $Door2CloseTime_24_DB;
	global $Door2OpenTime_25_DB;
	global $Door2CloseTime_26_DB;
	global $Door2OpenTime_27_DB;
	global $Door2CloseTime_28_DB;
	global $Door2OpenTime_29_DB;
	global $Door2CloseTime_30_DB;
	global $Door2OpenTime_31_DB;
	global $Door2CloseTime_32_DB;
	global $Door2OpenTime_33_DB;
	global $Door2CloseTime_34_DB;
	global $Door2OpenTime_35_DB;
	global $Door2CloseTime_36_DB;
	global $InvoiceCreateTime_DB;
		
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
					//$SNo[] = $sno_tmp;
					//echo "\nRow=".$row." ,Val=".$objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					
					$tmp_val="B".$row;
					$LRNo_DB[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="C".$row;
					$VehicleNo_DB[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="D".$row;
					$FAT_DB[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="E".$row;
					$SNF_DB[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();					

					$tmp_val="F".$row;
					$QTY_DB[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="G".$row;
					$DriverName_DB[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="H".$row;
					$TransporterName_DB[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="I".$row;
					$InitialMilkAge_DB[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="J".$row;
					$ChillingPlant_DB[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="K".$row;
					$TargetPlant_DB[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();					
					
					$tmp_val="L".$row;
					$ManualDispatchTime_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="M".$row;
					$GPRSDispatchTime_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');

					$tmp_val="N".$row;
					$PlantInDate_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd');					

					$tmp_val="O".$row;
					$PlantInTime_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');					
					
					$tmp_val="P".$row;
					$ManualCloseTime_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="Q".$row;
					$GPRSCloseTime_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="R".$row;
					$EstUnloadTime_DB[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="S".$row;
					$DiffinCloseTime_DB[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();					
					
					$tmp_val="T".$row;
					$PlantOutDate_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd');					

					$tmp_val="U".$row;
					$PlantOutTime_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');					
					
					$tmp_val="V".$row;
					$PlantHaltTime_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');					

					$tmp_val="W".$row;
					$ServerCloseTime_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="X".$row;
					$TransportationAge_DB[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="Y".$row;
					$FinalMilkAge_DB[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="Z".$row;
					$TargetArrivalTime_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="AA".$row;
					$DelayInArrival_DB[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="AB".$row;
					$imei_tmp = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					$imei_tmp = number_format($imei_tmp,0,'','');
					$IMEI_DB[] = $imei_tmp;
					
					$tmp_val="AC".$row;
					$Latitude_DB[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="AD".$row;
					$Longitude_DB[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="AE".$row;
					$DistVar_DB[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
										
					$tmp_val="AF".$row;
					$C_Lat_DB[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="AG".$row;
					$C_Lng_DB[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="AH".$row;
					$C_DistVar_DB[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
						
					//##### DOOR OPEN
					$tmp_val="AI".$row;
					$Door1OpenTime_1_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="AJ".$row;
					$Door1CloseTime_2_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="AK".$row;
					$Door1OpenTime_3_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="AL".$row;
					$Door1CloseTime_4_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="AM".$row;
					$Door1OpenTime_5_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="AN".$row;
					$Door1CloseTime_6_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="AO".$row;
					$Door1OpenTime_7_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="AP".$row;
					$Door1CloseTime_8_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="AQ".$row;
					$Door1OpenTime_9_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="AR".$row;
					$Door1CloseTime_10_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="AS".$row;
					$Door1OpenTime_11_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="AT".$row;
					$Door1CloseTime_12_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="AU".$row;
					$Door1OpenTime_13_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="AV".$row;
					$Door1CloseTime_14_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="AW".$row;
					$Door1OpenTime_15_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="AX".$row;
					$Door1CloseTime_16_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="AY".$row;
					$Door1OpenTime_17_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="AZ".$row;
					$Door1CloseTime_18_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					
					
					$tmp_val="BA".$row;
					$Door2OpenTime_19_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="BB".$row;
					$Door2CloseTime_20_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="BC".$row;
					$Door2OpenTime_21_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="BD".$row;
					$Door2CloseTime_22_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="BE".$row;
					$Door2OpenTime_23_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="BF".$row;
					$Door2CloseTime_24_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="BG".$row;
					$Door2OpenTime_25_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="BH".$row;
					$Door2CloseTime_26_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="BI".$row;
					$Door2OpenTime_27_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="BJ".$row;
					$Door2CloseTime_28_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="BK".$row;
					$Door2OpenTime_29_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="BL".$row;
					$Door2CloseTime_30_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="BM".$row;
					$Door2OpenTime_31_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="BN".$row;
					$Door2CloseTime_32_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					
					
					$tmp_val="BO".$row;
					$Door2OpenTime_33_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="BP".$row;
					$Door2CloseTime_34_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="BQ".$row;
					$Door2OpenTime_35_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="BR".$row;
					$Door2CloseTime_36_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					$tmp_val="BS".$row;
					$InvoiceCreateTime_DB[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					break;
				}				
			//}		
		}
		if($read_completed)
		{
			break;
		}
	}
	
	//####### UPDATE RECORDS
	update_records_db();
	//######################
}

function update_records_db()
{
	global $objPHPExcel_1;
	global $DbConnection;
	global $LRNo_DB;
	global $VehicleNo_DB;
	global $FAT_DB;
	global $SNF_DB;
	global $QTY_DB;
	global $DriverName_DB;
	global $TransporterName_DB;
	global $InitialMilkAge_DB;
	global $ChillingPlant_DB;
	global $TargetPlant_DB;
	global $ManualDispatchTime_DB;
	global $GPRSDispatchTime_DB;
	global $PlantInDate_DB;
	global $PlantInTime_DB;
	global $ManualCloseTime_DB;
	global $GPRSCloseTime_DB;
	global $EstUnloadTime_DB;
	global $DiffinCloseTime_DB;
	global $PlantOutDate_DB;
	global $PlantOutTime_DB;
	global $PlantHaltTime_DB;
	global $ServerCloseTime_DB;
	global $TransportationAge_DB;
	global $FinalMilkAge_DB;
	global $TargetArrivalTime_DB;
	global $DelayInArrival_DB;
	global $IMEI_DB;
	global $Latitude_DB;
	global $Longitude_DB;
	global $DistVar_DB;
	global $C_Lat_DB;
	global $C_Lng_DB;
	global $C_DistVar_DB;
	global $Door1OpenTime_1_DB;
	global $Door1CloseTime_2_DB;
	global $Door1OpenTime_3_DB;
	global $Door1CloseTime_4_DB;
	global $Door1OpenTime_5_DB;
	global $Door1CloseTime_6_DB;
	global $Door1OpenTime_7_DB;
	global $Door1CloseTime_8_DB;
	global $Door1OpenTime_9_DB;
	global $Door1CloseTime_10_DB;
	global $Door1OpenTime_11_DB;
	global $Door1CloseTime_12_DB;
	global $Door1OpenTime_13_DB;
	global $Door1CloseTime_14_DB;
	global $Door1OpenTime_15_DB;
	global $Door1CloseTime_16_DB;
	global $Door1OpenTime_17_DB;
	global $Door1CloseTime_18_DB;
	global $Door2OpenTime_19_DB;
	global $Door2CloseTime_20_DB;
	global $Door2OpenTime_21_DB;
	global $Door2CloseTime_22_DB;
	global $Door2OpenTime_23_DB;
	global $Door2CloseTime_24_DB;
	global $Door2OpenTime_25_DB;
	global $Door2CloseTime_26_DB;
	global $Door2OpenTime_27_DB;
	global $Door2CloseTime_28_DB;
	global $Door2OpenTime_29_DB;
	global $Door2CloseTime_30_DB;
	global $Door2OpenTime_31_DB;
	global $Door2CloseTime_32_DB;
	global $Door2OpenTime_33_DB;
	global $Door2CloseTime_34_DB;
	global $Door2OpenTime_35_DB;
	global $Door2CloseTime_36_DB;
	global $InvoiceCreateTime_DB;
	
	$update_time = date('Y-m-d H:i:s');
	
	for($i=0;$i<sizeof($LRNo_DB);$i++)
	{
		$query = "SELECT LRNo,ManualDispatchTime FROM invoice_previous WHERE LRNo='$LRNo_DB[$i]'";
		$result = mysql_query($query,$DbConnection);
		
		$numrows = mysql_num_rows($result);
		
		if($numrows==0)
		{
			$query_insert = "INSERT INTO invoice_previous(LRNo,VehicleNo,FAT,SNF,QTY,DriverName,TransporterName,InitialMilkAge,ChillingPlant,TargetPlant,ManualDispatchTime,GPRSDispatchTime,PlantInDate,PlantInTime,ManualCloseTime,GPRSCloseTime,EstUnloadTime,DiffinCloseTime,PlantOutDate,PlantOutTime,PlantHaltTime,ServerCloseTime,TransportationAge,FinalMilkAge,TargetArrivalTime,DelayInArrival,IMEI,Latitude,Longitude,DistVar,C_Lat,C_Lng,C_DistVar,Door1OpenTime_1,Door1CloseTime_2,Door1OpenTime_3,Door1CloseTime_4,Door1OpenTime_5,Door1CloseTime_6,Door1OpenTime_7,Door1CloseTime_8,Door1OpenTime_9,Door1CloseTime_10,Door1OpenTime_11,Door1CloseTime_12,Door1OpenTime_13,Door1CloseTime_14,Door1OpenTime_15,Door1CloseTime_16,Door1OpenTime_17,Door1CloseTime_18,Door2OpenTime_19,Door2CloseTime_20,Door2OpenTime_21,Door2CloseTime_22,Door2OpenTime_23,Door2CloseTime_24,Door2OpenTime_25,Door2CloseTime_26,Door2OpenTime_27,Door2CloseTime_28,Door2OpenTime_29,Door2CloseTime_30,Door2OpenTime_31,Door2CloseTime_32,Door2OpenTime_33,Door2CloseTime_34,Door2OpenTime_35,Door2CloseTime_36,InvoiceCreateTime,update_time) VALUES('$LRNo_DB[$i]','$VehicleNo_DB[$i]','$FAT_DB[$i]','$SNF_DB[$i]','$QTY_DB[$i]','$DriverName_DB[$i]','$TransporterName_DB[$i]','$InitialMilkAge_DB[$i]','$ChillingPlant_DB[$i]','$TargetPlant_DB[$i]','$ManualDispatchTime_DB[$i]','$GPRSDispatchTime_DB[$i]','$PlantInDate_DB[$i]','$PlantInTime_DB[$i]','$ManualCloseTime_DB[$i]','$GPRSCloseTime_DB[$i]','$EstUnloadTime_DB[$i]','$DiffinCloseTime_DB[$i]','$PlantOutDate_DB[$i]','$PlantOutTime_DB[$i]','$PlantHaltTime_DB[$i]','$ServerCloseTime_DB[$i]','$TransportationAge_DB[$i]','$FinalMilkAge_DB[$i]','$TargetArrivalTime_DB[$i]','$DelayInArrival_DB[$i]','$IMEI_DB[$i]','$Latitude_DB[$i]','$Longitude_DB[$i]','$DistVar_DB[$i]','$C_Lat_DB[$i]','$C_Lng_DB[$i]','$C_DistVar_DB[$i]','$Door1OpenTime_1_DB[$i]','$Door1CloseTime_2_DB[$i]','$Door1OpenTime_3_DB[$i]','$Door1CloseTime_4_DB[$i]','$Door1OpenTime_5_DB[$i]','$Door1CloseTime_6_DB[$i]','$Door1OpenTime_7_DB[$i]','$Door1CloseTime_8_DB[$i]','$Door1OpenTime_9_DB[$i]','$Door1CloseTime_10_DB[$i]','$Door1OpenTime_11_DB[$i]','$Door1CloseTime_12_DB[$i]','$Door1OpenTime_13_DB[$i]','$Door1CloseTime_14_DB[$i]','$Door1OpenTime_15_DB[$i]','$Door1CloseTime_16_DB[$i]','$Door1OpenTime_17_DB[$i]','$Door1CloseTime_18_DB[$i]','$Door2OpenTime_19_DB[$i]','$Door2CloseTime_20_DB[$i]','$Door2OpenTime_21_DB[$i]','$Door2CloseTime_22_DB[$i]','$Door2OpenTime_23_DB[$i]','$Door2CloseTime_24_DB[$i]','$Door2OpenTime_25_DB[$i]','$Door2CloseTime_26_DB[$i]','$Door2OpenTime_27_DB[$i]','$Door2CloseTime_28_DB[$i]','$Door2OpenTime_29_DB[$i]','$Door2CloseTime_30_DB[$i]','$Door2OpenTime_31_DB[$i]','$Door2CloseTime_32_DB[$i]','$Door2OpenTime_33_DB[$i]','$Door2CloseTime_34_DB[$i]','$Door2OpenTime_35_DB[$i]','$Door2CloseTime_36_DB[$i]','$InvoiceCreateTime_DB[$i]','$update_time')";
			$result_insert = mysql_query($query_insert,$DbConnection);
		}
		else
		{
			$query_update = "UPDATE invoice_previous SET LRNo='$LRNo_DB[$i]',VehicleNo='$VehicleNo_DB[$i]',FAT='$FAT_DB[$i]',SNF='$SNF_DB[$i]',QTY='$QTY_DB[$i]',DriverName='$DriverName_DB[$i]',TransporterName='$TransporterName_DB[$i]',InitialMilkAge='$InitialMilkAge_DB[$i]',ChillingPlant='$ChillingPlant_DB[$i]',TargetPlant='$TargetPlant_DB[$i]',ManualDispatchTime='$ManualDispatchTime_DB[$i]',GPRSDispatchTime='$GPRSDispatchTime_DB[$i]',PlantInDate='$PlantInDate_DB[$i]',PlantInTime='$PlantInTime_DB[$i]',ManualCloseTime='$ManualCloseTime_DB[$i]',GPRSCloseTime='$GPRSCloseTime_DB[$i]',EstUnloadTime='$EstUnloadTime_DB[$i]',DiffinCloseTime='$DiffinCloseTime_DB[$i]',PlantOutDate='$PlantOutDate_DB[$i]',PlantOutTime='$PlantOutTime_DB[$i]',PlantHaltTime='$PlantHaltTime_DB[$i]',ServerCloseTime='$ServerCloseTime_DB[$i]',TransportationAge='$TransportationAge_DB[$i]',FinalMilkAge='$FinalMilkAge_DB[$i]',TargetArrivalTime='$TargetArrivalTime_DB[$i]',DelayInArrival='$DelayInArrival_DB[$i]',IMEI='$IMEI_DB[$i]',Latitude='$Latitude_DB[$i]',Longitude='$Longitude_DB[$i]',DistVar='$DistVar_DB[$i]',C_Lat='$C_Lat_DB[$i]',C_Lng='$C_Lng_DB[$i]',C_DistVar='$C_DistVar_DB[$i]',Door1OpenTime_1='$Door1OpenTime_1_DB[$i]',Door1CloseTime_2='$Door1CloseTime_2_DB[$i]',Door1OpenTime_3='$Door1OpenTime_3_DB[$i]',Door1CloseTime_4='$Door1CloseTime_4_DB[$i]',Door1OpenTime_5='$Door1OpenTime_5_DB[$i]',Door1CloseTime_6='$Door1CloseTime_6_DB[$i]',Door1OpenTime_7='$Door1OpenTime_7_DB[$i]',Door1CloseTime_8='$Door1CloseTime_8_DB[$i]',Door1OpenTime_9='$Door1OpenTime_9_DB[$i]',Door1CloseTime_10='$Door1CloseTime_10_DB[$i]',Door1OpenTime_11='$Door1OpenTime_11_DB[$i]',Door1CloseTime_12='$Door1CloseTime_12_DB[$i]',Door1OpenTime_13='$Door1OpenTime_13_DB[$i]',Door1CloseTime_14='$Door1CloseTime_14_DB[$i]',Door1OpenTime_15='$Door1OpenTime_15_DB[$i]',Door1CloseTime_16='$Door1CloseTime_16_DB[$i]',Door1OpenTime_17='$Door1OpenTime_17_DB[$i]',Door1CloseTime_18='$Door1CloseTime_18_DB[$i]',Door2OpenTime_19='$Door2OpenTime_19_DB[$i]',Door2CloseTime_20='$Door2CloseTime_20_DB[$i]',Door2OpenTime_21='$Door2OpenTime_21_DB[$i]',Door2CloseTime_22='$Door2CloseTime_22_DB[$i]',Door2OpenTime_23='$Door2OpenTime_23_DB[$i]',Door2CloseTime_24='$Door2CloseTime_24_DB[$i]',Door2OpenTime_25='$Door2OpenTime_25_DB[$i]',Door2CloseTime_26='$Door2CloseTime_26_DB[$i]',Door2OpenTime_27='$Door2OpenTime_27_DB[$i]',Door2CloseTime_28='$Door2CloseTime_28_DB[$i]',Door2OpenTime_29='$Door2OpenTime_29_DB[$i]',Door2CloseTime_30='$Door2CloseTime_30_DB[$i]',Door2OpenTime_31='$Door2OpenTime_31_DB[$i]',Door2CloseTime_32='$Door2CloseTime_32_DB[$i]',Door2OpenTime_33='$Door2OpenTime_33_DB[$i]',Door2CloseTime_34='$Door2CloseTime_34_DB[$i]',Door2OpenTime_35='$Door2OpenTime_35_DB[$i]',Door2CloseTime_36='$Door2CloseTime_36_DB[$i]',InvoiceCreateTime='$InvoiceCreateTime_DB[$i]',update_time='$update_time' WHERE LRNo='$LRNo_DB[$i]'";
			$result_update = mysql_query($query_update,$DbConnection);
		}
	}
}

?>
