<?php
function create_pending_invoice($read_excel_path)
{	
	global $objPHPExcel_1;
	global $transporter_name;
	global $vehicle_imei;
	
	global $customer_no;
	global $station_name;
	global $station_coord;
	global $distance_variable;
	
	global $sno_db;
	global $lorry_no;
	global $vehicle_no;
	global $tanker_type;
	global $email;
	global $mobile;
	global $qty_kg;
	global $fat_percentage;
	global $snf_percentage;
	global $fat_kg;
	global $snf_kg;
	global $milk_age;
	global $docket_no;
	global $dispatch_time;
	global $target_time;
	global $validity_time;
	global $plant_acceptance_time;
	global $plant;
	global $chilling_plant;
	global $chilling_plant_coord;
	global $chilling_plant_distvar;
	global $driver_name;
	global $driver_mobile;
	global $unload_estimated_time;
	global $unload_accept_time;
	global $parent_account_id;
	global $create_id;
	global $create_date;
	global $close_type;
	global $close_time;
	global $system_time;
	global $plant_outtime_db;
	global $plant_intime_db;
	global $chilling_plant_outtime_db;
	global $gprs_chilling_plant_db;
	global $gprs_plant_db;	
	
	global $SNODB;
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
	/*global $Latitude_DB;
	global $Longitude_DB;
	global $DistVar_DB;
	global $C_Lat_DB;
	global $C_Lng_DB;
	global $C_DistVar_DB;*/
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
	
	//######### SENT FILE NAME CLOSED 			
	$objPHPExcel_1 = null;
	$objPHPExcel_1 = new PHPExcel();
	$objPHPExcel_1->setActiveSheetIndex(0)->setTitle('Invoice Report (Mother RawMilk)');
	$cellIterator = null;
	$column = null;
	$row = null;
	//echo "\n1";
	//######### FIRST TAB ##########
	$row=1;
	

	$header_font = array(			
	'fill' 	=> array(
		'type'		=> PHPExcel_Style_Fill::FILL_SOLID,								
		'color'		=> array('argb' => 'd3d3d3')		//grey
		//'text' => array('argb' => 'FFFC64')
	),
	'borders' => array(
		'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
		'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
	)
	);

	//###### COLOR STYLES
	/*$header_font = array(
		'font'  => array(
		'bold'  => true,
		'color' => array('rgb' => '000000'), //RED
		'size'  => 10
		//'name'  => 'Verdana'
	));*/
	$styleFontRed = array(
	'font'  => array(
		'bold'  => true,
		'color' => array('rgb' => 'FF0000'), //RED
		'size'  => 10
		//'name'  => 'Verdana'
	));
	
	//###### FILL THE HEADER
	$col_tmp = 'A'.$row;					
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "SNo");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);	
	$col_tmp = 'B'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "LR No");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'C'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Vehicle No");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'D'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Tanker Type");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'E'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "FAT");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'F'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "SNF");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'G'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "QTY");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'H'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Driver Name");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'I'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Transporter Name");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'J'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Initial MilkAge");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'K'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "DEF-Chilling Plant");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'L'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "DEF-Target Plant");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	
	$col_tmp = 'M'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "GPRS-Chilling Plant");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'N'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "GPRS-Target Plant");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	
	$col_tmp = 'O'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Manual Dispatch Time");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	
	$col_tmp = 'P'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Plant Out Date");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'Q'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Plant OutTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	
	$col_tmp = 'R'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Plant InDate");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'S'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Plant InTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	
	$col_tmp = 'T'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "GPRS Dispatch Time");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	
	$col_tmp = 'U'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Manual Close Time");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);	
	/*$col_tmp = 'S'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "GPRS Close Time");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);*/
	$col_tmp = 'V'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Est Unload Time");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);	
	$col_tmp = 'W'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Diff in Close Time");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'X'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Plant HaltTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'Y'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Server CloseTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'Z'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Transportation Age (Mins)");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'AA'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Final Milk Age");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);	
	$col_tmp = 'AB'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Target Arrival Time");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);	
	$col_tmp = 'AC'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Delay In Arrival");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'AD'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "IMEI");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'AE'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Remark");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'AF'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Invoice CreateTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	
	$col_tmp = 'AG'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door1 OpenTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'AH'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door1 CloseTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'AI'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door1 OpenTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'AJ'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door1 CloseTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'AK'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door1 OpenTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'AL'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door1 CloseTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'AM'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door1 OpenTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'AN'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door1 CloseTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'AO'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door1 OpenTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'AP'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door1 CloseTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'AQ'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door1 OpenTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'AR'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door1 CloseTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'AS'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door1 OpenTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'AT'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door1 CloseTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'AU'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door1 OpenTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'AV'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door1 CloseTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'AW'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door1 OpenTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'AX'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door1 CloseTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'AY'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door1 OpenTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'AZ'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door1 CloseTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	
	//###### DOOR2
	$col_tmp = 'BA'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door2 OpenTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'BB'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door2 CloseTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'BC'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door2 OpenTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'BD'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door2 CloseTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'BE'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door2 OpenTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'BF'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door2 CloseTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'BG'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door2 OpenTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'BH'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door2 CloseTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'BI'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door2 OpenTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'BJ'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door2 CloseTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'BK'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door2 OpenTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'BL'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door2 CloseTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'BM'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door2 OpenTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'BN'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door2 CloseTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'BO'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door2 OpenTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'BP'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door2 CloseTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'BQ'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door2 OpenTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'BR'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door2 CloseTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'BS'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door2 OpenTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'BT'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Door2 CloseTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);	
	$col_tmp = 'BU'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "DB_SNO");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	
	$row++;		
	echo "\nSizeIMEI=".sizeof($vehicle_imei);

	//########### READ PREVIOUS INVOICES FROM DATABASE 
	global $DbConnection;
	/*$query = "SELECT * FROM invoice_previous";
	$result = mysql_query($query,$DbConnection);
	while($row_db = mysql_fetch_object($result))
	{
		$LRNo = $row_db->LRNo;
		$LRNo_DB[$LRNo] = $LRNo;
		$VehicleNo_DB[$LRNo] = $row_db->VehicleNo;
		$FAT_DB[$LRNo] = $row_db->FAT;
		$SNF_DB[$LRNo] = $row_db->SNF;
		$QTY_DB[$LRNo] = $row_db->QTY;
		$DriverName_DB[$LRNo] = $row_db->DriverName;
		$TransporterName_DB[$LRNo] = $row_db->TransporterName;
		$InitialMilkAge_DB[$LRNo] = $row_db->InitialMilkAge;
		$ChillingPlant_DB[$LRNo] = $row_db->ChillingPlant;
		$TargetPlant_DB[$LRNo] = $row_db->TargetPlant;
		$ManualDispatchTime_DB[$LRNo] = $row_db->ManualDispatchTime;
		$GPRSDispatchTime_DB[$LRNo] = $row_db->GPRSDispatchTime;
		$PlantInDate_DB[$LRNo] = $row_db->PlantInDate;
		$PlantInTime_DB[$LRNo] = $row_db->PlantInTime;
		$ManualCloseTime_DB[$LRNo] = $row_db->ManualCloseTime;
		$GPRSCloseTime_DB[$LRNo] = $row_db->GPRSCloseTime;
		$EstUnloadTime_DB[$LRNo] = $row_db->EstUnloadTime;
		$DiffinCloseTime_DB[$LRNo] = $row_db->DiffinCloseTime;
		$PlantOutDate_DB[$LRNo] = $row_db->PlantOutDate;
		$PlantOutTime_DB[$LRNo] = $row_db->PlantOutTime;
		$PlantHaltTime_DB[$LRNo] = $row_db->PlantHaltTime;
		$ServerCloseTime_DB[$LRNo] = $row_db->ServerCloseTime;
		$TransportationAge_DB[$LRNo] = $row_db->TransportationAge;
		$FinalMilkAge_DB[$LRNo] = $row_db->FinalMilkAge;
		$TargetArrivalTime_DB[$LRNo] = $row_db->TargetArrivalTime;
		$DelayInArrival_DB[$LRNo] = $row_db->DelayInArrival;
		$IMEI_DB[$LRNo] = $row_db->IMEI;
		$Latitude_DB[$LRNo] = $row_db->Latitude;
		$Longitude_DB[$LRNo] = $row_db->Longitude;
		$DistVar_DB[$LRNo] = $row_db->DistVar;
		$C_Lat_DB[$LRNo] = $row_db->C_Lat;
		$C_Lng_DB[$LRNo] = $row_db->C_Lng;
		$C_DistVar_DB[$LRNo] = $row_db->C_DistVar;
		$Door1OpenTime_1_DB[$LRNo] = $row_db->Door1OpenTime_1;
		$Door1CloseTime_2_DB[$LRNo] = $row_db->Door1CloseTime_2;
		$Door1OpenTime_3_DB[$LRNo] = $row_db->Door1OpenTime_3;
		$Door1CloseTime_4_DB[$LRNo] = $row_db->Door1CloseTime_4;
		$Door1OpenTime_5_DB[$LRNo] = $row_db->Door1OpenTime_5;
		$Door1CloseTime_6_DB[$LRNo] = $row_db->Door1CloseTime_6;
		$Door1OpenTime_7_DB[$LRNo] = $row_db->Door1OpenTime_7;
		$Door1CloseTime_8_DB[$LRNo] = $row_db->Door1CloseTime_8;
		$Door1OpenTime_9_DB[$LRNo] = $row_db->Door1OpenTime_9;
		$Door1CloseTime_10_DB[$LRNo] = $row_db->Door1CloseTime_10;
		$Door1OpenTime_11_DB[$LRNo] = $row_db->Door1OpenTime_11;
		$Door1CloseTime_12_DB[$LRNo] = $row_db->Door1CloseTime_12;
		$Door1OpenTime_13_DB[$LRNo] = $row_db->Door1OpenTime_13;
		$Door1CloseTime_14_DB[$LRNo] = $row_db->Door1CloseTime_14;
		$Door1OpenTime_15_DB[$LRNo] = $row_db->Door1OpenTime_15;
		$Door1CloseTime_16_DB[$LRNo] = $row_db->Door1CloseTime_16;
		$Door1OpenTime_17_DB[$LRNo] = $row_db->Door1OpenTime_17;
		$Door1CloseTime_18_DB[$LRNo] = $row_db->Door1CloseTime_18;
		$Door2OpenTime_19_DB[$LRNo] = $row_db->Door2OpenTime_19;
		$Door2CloseTime_20_DB[$LRNo] = $row_db->Door2CloseTime_20;
		$Door2OpenTime_21_DB[$LRNo] = $row_db->Door2OpenTime_21;
		$Door2CloseTime_22_DB[$LRNo] = $row_db->Door2CloseTime_22;
		$Door2OpenTime_23_DB[$LRNo] = $row_db->Door2OpenTime_23;
		$Door2CloseTime_24_DB[$LRNo] = $row_db->Door2CloseTime_24;
		$Door2OpenTime_25_DB[$LRNo] = $row_db->Door2OpenTime_25;
		$Door2CloseTime_26_DB[$LRNo] = $row_db->Door2CloseTime_26;
		$Door2OpenTime_27_DB[$LRNo] = $row_db->Door2OpenTime_27;
		$Door2CloseTime_28_DB[$LRNo] = $row_db->Door2CloseTime_28;
		$Door2OpenTime_29_DB[$LRNo] = $row_db->Door2OpenTime_29;
		$Door2CloseTime_30_DB[$LRNo] = $row_db->Door2CloseTime_30;
		$Door2OpenTime_31_DB[$LRNo] = $row_db->Door2OpenTime_31;
		$Door2CloseTime_32_DB[$LRNo] = $row_db->Door2CloseTime_32;
		$Door2OpenTime_33_DB[$LRNo] = $row_db->Door2OpenTime_33;
		$Door2CloseTime_34_DB[$LRNo] = $row_db->Door2CloseTime_34;
		$Door2OpenTime_35_DB[$LRNo] = $row_db->Door2OpenTime_35;
		$Door2CloseTime_36_DB[$LRNo] = $row_db->Door2CloseTime_36;
		$InvoiceCreateTime_DB[$LRNo] = $row_db->InvoiceCreateTime;
		$update_time_DB[$LRNo] = $row_db->update_time;		
	}*/
	
	$sno=1;
	//####### 1. OPEN INVOICES
	for($i=0;$i<sizeof($vehicle_imei);$i++)
	{		
		//echo "\nInWrite";
		//if(trim($close_time[$i]) == "")
		//{
			$col_tmp = 'A'.$row;					
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $sno);
				
			/*if( (sizeof($LRNo_DB)>0) && (trim($lorry_no[$i]) == trim($LRNo_DB[$lorry_no[$i]])) && ( trim($dispatch_time[$i]) == trim($ManualDispatchTime_DB[$lorry_no[$i]]) ) && (trim($system_time[$i])==trim($ServerCloseTime_DB[$lorry_no[$i]])))
			{
				//echo "\nIn Previous";
				if( ($GPRSDispatchTime_DB[$lorry_no[$i]]=="") || ($PlantInDate_DB[$lorry_no[$i]]=="") || ($PlantOutDate_DB[$lorry_no[$i]]=="") )
				{
					fill_previous_data(trim($lorry_no[$i]),$row,"1");
				}
				else
				{
					fill_previous_data(trim($lorry_no[$i]),$row,"2");
				}
			}
			else*/
			//{
				//echo "\nFresh Entry";
				$col_tmp = 'B'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $lorry_no[$i]);
				
				$col_tmp = 'C'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $vehicle_no[$i]);
				
				$col_tmp = 'D'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $tanker_type[$i]);				
				
				$col_tmp = 'E'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $fat_kg[$i]);
				
				$col_tmp = 'F'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $snf_kg[$i]);
				
				$col_tmp = 'G'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $qty_kg[$i]);
				
				$col_tmp = 'H'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $driver_name[$i]);
				
				$col_tmp = 'I'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $transporter_name[$i]);
				
				$col_tmp = 'J'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $milk_age[$i]);
				
				$col_tmp = 'K'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $chilling_plant[$i]);	//DEF
				
				$col_tmp = 'L'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $customer_no[$i]);		//DEF
				
				$col_tmp = 'M'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $gprs_chilling_plant_db[$i]);	//GPRS

				$col_tmp = 'N'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $gprs_plant_db[$i]);	//GPRS
				
								
				//###### L,M =>REAL CUSTOMER AND PLANT IN ACTION  
				
				$col_tmp = 'O'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $dispatch_time[$i]);

				//############## PREVIOUS RECORDS				
				if($plant_outtime_db[$i]!="")
				{
					$plant_outtime_tmp = explode(" ",$plant_outtime_db[$i]);
					$col_tmp = 'P'.$row;
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $plant_outtime_tmp[0]);

					$col_tmp = 'Q'.$row;
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $plant_outtime_tmp[1]);
				}

				if($plant_intime_db[$i]!="")
				{
					$plant_intime_tmp = explode(" ",$plant_intime_db[$i]);
					$col_tmp = 'R'.$row;
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $plant_intime_tmp[0]);
					
					$col_tmp = 'S'.$row;
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $plant_intime_tmp[1]);
				}
				
				if($chilling_plant_outtime_db[$i]!="")
				{
					$col_tmp = 'T'.$row;
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $chilling_plant_outtime_db[$i]);
				}
				//##################
				//$col_tmp = 'M'.$row;
				//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "GPRS Dispatch Time");
				
				//$col_tmp = 'N'.$row;
				//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Plant InDate");
				
				//$col_tmp = 'O'.$row;
				//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Plant InTime");
				
				$col_tmp = 'U'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $close_time[$i]);
					
				//$col_tmp = 'Q'.$row;
				//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "GPRS Close Time");
				
				$col_tmp = 'V'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $unload_estimated_time[$i]);
					
				//$col_tmp = 'S'.$row;
				//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Diff in Close Time");
				
				//$col_tmp = 'T'.$row;
				//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Plant Out Date");
				
				//$col_tmp = 'U'.$row;
				//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Plant OutTime");
				
				//$col_tmp = 'V'.$row;
				//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Plant HaltTime");
				
				//########## PLANT HATL TIME - PREVIOUS RECORD
				if(($plant_outtime_db[$i]!="") && ($plant_intime_db[$i]!=""))
				{
					$deptime = strtotime($plant_outtime_db[$i]);
					$arrtime = strtotime($plant_intime_db[$i]);
					$hms_2 = secondsToTime($deptime - $arrtime);
					$hrs_min = $hms_2[h].":".$hms_2[m].":".$hms_2[s];
					$col_tmp = 'X'.$row;
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $hrs_min);			
				}
				//############################################
				
				$col_tmp = 'Y'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $system_time[$i]);
				
				if( ($plant_intime_db[$i]!="") && ($chilling_plant_outtime_db[$i]!="") )
				{
					//##### TRANSPORTATION AGE -PREVIOUS RECORDS
					$transportation_age = (strtotime($plant_intime_db[$i]) - strtotime($chilling_plant_outtime_db[$i]))/60;	//## TRANSPORTATION AGE
					$col_tmp = 'Z'.$row;
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , round($transportation_age,1));		
					//######################
				}
				
				$final_milk_age = "";

				if(($close_time[$i]!="") && ($dispatch_time[$i]!=""))

				{
					$final_milk_age = round(($milk_age[$i]+((strtotime($close_time[$i]) - strtotime($dispatch_time[$i]))/3600)),2);		//### FINAL MILK AGE- hrs
				}
				
				$col_tmp = 'AA'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $final_milk_age);
					
				$col_tmp = 'AB'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $target_time[$i]);				//TARGET ARRIVAL TIME
					
				//$col_tmp = 'AA'.$row;
				//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Delay In Arrival");
				
				
				$col_tmp = 'AD'.$row;
				//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $vehicle_imei[$i]);	//Imei
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValueExplicit($col_tmp, $vehicle_imei[$i], PHPExcel_Cell_DataType::TYPE_STRING);

				/*$coord = explode(',',$station_coord[$i]);
				$col_tmp = 'AC'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , trim($coord[0]));	//Lat
				
				$col_tmp = 'AD'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , trim($coord[1]));	//Lng
				
				$col_tmp = 'AE'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $distance_variable[$i]);
				
				$c_coord = explode(',',$chilling_plant_coord[$i]);
				$col_tmp = 'AF'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , trim($c_coord[0]));	//C_Lat
				
				$col_tmp = 'AG'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , trim($c_coord[1]));	//C_Lng
						
				$col_tmp = 'AH'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $chilling_plant_distvar[$i]);*/	

				$col_tmp = 'AF'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $create_date[$i]);
								
				$col_tmp = 'BU'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $sno_db[$i]);			
			//}
					
			$row++;
			$sno++;
			
			if($vehicle_imei[$i]!=$vehicle_imei[$i+1])
			{
				$sno=1;
			}
		//} //###### IF CLOSED
	}
	
	/*
	//########## 2. MANUAL CLOSED INVOICES
	for($i=0;$i<sizeof($vehicle_imei);$i++)
	{		
		if(trim($close_time[$i]!=""))
		{
			//echo "\nInWrite";
			$col_tmp = 'A'.$row;					
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $sno);
				
			if( (sizeof($LRNo_DB)>0) && (trim($lorry_no[$i]) == trim($LRNo_DB[$lorry_no[$i]])) && ( trim($dispatch_time[$i]) == trim($ManualDispatchTime_DB[$lorry_no[$i]]) ) && (trim($ServerCloseTime_DB[$lorry_no[$i]])==trim($system_time[$i]) ) )
			{
				//echo "\nIn Previous";
				fill_previous_data(trim($lorry_no[$i]),$row,"2");
			}
			else
			{
				//echo "\nFresh Entry";
				$col_tmp = 'B'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $lorry_no[$i]);
				
				$col_tmp = 'C'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $vehicle_no[$i]);
				
				$col_tmp = 'D'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $fat_kg[$i]);
				
				$col_tmp = 'E'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $snf_kg[$i]);
				
				$col_tmp = 'F'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $qty_kg[$i]);
				
				$col_tmp = 'G'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $driver_name[$i]);
				
				$col_tmp = 'H'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $transporter_name[$i]);
				
				$col_tmp = 'I'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $milk_age[$i]);
				
				$col_tmp = 'J'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $chilling_plant[$i]);
				
				$col_tmp = 'K'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $customer_no[$i]);	//Target plant
				
				$col_tmp = 'L'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $dispatch_time[$i]);
				
				//$col_tmp = 'M'.$row;
				//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "GPRS Dispatch Time");
				
				//$col_tmp = 'N'.$row;
				//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Plant InDate");
				
				//$col_tmp = 'O'.$row;
				//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Plant InTime");
				
				//if($close_type[$i]=='m')
				if($unload_estimated_time[$i]!="")
				{
					$col_tmp = 'P'.$row;
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $close_time[$i]);
				}
					
				//$col_tmp = 'Q'.$row;
				//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "GPRS Close Time");
				
				$col_tmp = 'R'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $unload_estimated_time[$i]);
					
				//$col_tmp = 'S'.$row;
				//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Diff in Close Time");
				
				//$col_tmp = 'T'.$row;
				//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Plant Out Date");
				
				//$col_tmp = 'U'.$row;
				//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Plant OutTime");
				
				//$col_tmp = 'V'.$row;
				//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Plant HaltTime");
				
				$col_tmp = 'W'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $system_time[$i]);
				
				//$col_tmp = 'X'.$row;
				//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Transportation Age");
				
				$final_milk_age = "";

				if(($close_time[$i]!="") && ($dispatch_time[$i]!=""))

				{
					$final_milk_age = round(($milk_age[$i]+((strtotime($close_time[$i]) - strtotime($dispatch_time[$i]))/3600)),2);		//### FINAL MILK AGE- hrs
				}
				
				$col_tmp = 'Y'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $final_milk_age);
					
				$col_tmp = 'Z'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $target_time[$i]);				//TARGET ARRIVAL TIME
					
				//$col_tmp = 'AA'.$row;
				//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Delay In Arrival");
				
				$col_tmp = 'AB'.$row;
				//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $vehicle_imei[$i]);	//Imei
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValueExplicit($col_tmp, $vehicle_imei[$i], PHPExcel_Cell_DataType::TYPE_STRING);

				$coord = explode(',',$station_coord[$i]);
				$col_tmp = 'AC'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , trim($coord[0]));	//Lat
				
				$col_tmp = 'AD'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , trim($coord[1]));	//Lng
				
				$col_tmp = 'AE'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $distance_variable[$i]);
				
				$c_coord = explode(',',$chilling_plant_coord[$i]);
				$col_tmp = 'AF'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , trim($c_coord[0]));	//C_Lat
				
				$col_tmp = 'AG'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , trim($c_coord[1]));	//C_Lng
						
				$col_tmp = 'AH'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $chilling_plant_distvar[$i]);	

				$col_tmp = 'BS'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $create_date[$i]);
				
				$col_tmp = 'BT'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "1");			
			}
					
			$row++;
			$sno++;
			
			if($vehicle_imei[$i]!=$vehicle_imei[$i+1])
			{
				$sno=1;
			}
		}
	}*/	
	
	//#### WRITE FINAL XLSX
	echo date('H:i:s') , " Write to Excel2007 format" , EOL;
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel_1, 'Excel2007');
	$objWriter->save($read_excel_path);
	echo date('H:i:s') , " File written to " , $read_excel_path , EOL;	
}


/*function fill_previous_data($LRNo,$row,$status)
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
	
	$col_tmp = 'B'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $LRNo_DB[$LRNo]);
	
	$col_tmp = 'C'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $VehicleNo_DB[$LRNo]);

	$col_tmp = 'D'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $FAT_DB[$LRNo]);
	
	$col_tmp = 'E'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $SNF_DB[$LRNo]);
	
	$col_tmp = 'F'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $QTY_DB[$LRNo]);
	
	$col_tmp = 'G'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $DriverName_DB[$LRNo]);
	
	$col_tmp = 'H'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $TransporterName_DB[$LRNo]);
	
	$col_tmp = 'I'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $InitialMilkAge_DB[$LRNo]);
		 
	$col_tmp = 'J'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $ChillingPlant_DB[$LRNo]);
	
	$col_tmp = 'K'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $TargetPlant_DB[$LRNo]);
	
	$col_tmp = 'L'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $ManualDispatchTime_DB[$LRNo]);
	
	$col_tmp = 'M'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $GPRSDispatchTime_DB[$LRNo]);
		
	$col_tmp = 'N'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $PlantInDate_DB[$LRNo]);
		 
	$col_tmp = 'O'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $PlantInTime_DB[$LRNo]);
	
	$col_tmp = 'P'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $ManualCloseTime_DB[$LRNo]);
	
	$col_tmp = 'Q'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $GPRSCloseTime_DB[$LRNo]);
	
	$col_tmp = 'R'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $EstUnloadTime_DB[$LRNo]);
	
	$col_tmp = 'S'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $DiffinCloseTime_DB[$LRNo]);
	
	$col_tmp = 'T'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $PlantOutDate_DB[$LRNo]);
	
	$col_tmp = 'U'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $PlantOutTime_DB[$LRNo]);
	
	$col_tmp = 'V'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $PlantHaltTime_DB[$LRNo]);
	
	$col_tmp = 'W'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $ServerCloseTime_DB[$LRNo]);
	
	$col_tmp = 'X'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $TransportationAge_DB[$LRNo]);
	
	$col_tmp = 'Y'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $FinalMilkAge_DB[$LRNo]);
	
	$col_tmp = 'Z'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $TargetArrivalTime_DB[$LRNo]);
	
	$col_tmp = 'AA'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $DelayInArrival_DB[$LRNo]);
	
	$col_tmp = 'AB'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValueExplicit($col_tmp, $IMEI_DB[$LRNo], PHPExcel_Cell_DataType::TYPE_STRING);
	
	$col_tmp = 'AC'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Latitude_DB[$LRNo]);
	
	$col_tmp = 'AD'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Longitude_DB[$LRNo]);

	$col_tmp = 'AE'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $DistVar_DB[$LRNo]);
	
	$col_tmp = 'AF'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $C_Lat_DB[$LRNo]);
	
	$col_tmp = 'AG'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $C_Lng_DB[$LRNo]);
	
	$col_tmp = 'AH'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $C_DistVar_DB[$LRNo]);
	
	$col_tmp = 'AI'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Door1OpenTime_1_DB[$LRNo]);
	
	$col_tmp = 'AJ'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Door1CloseTime_2_DB[$LRNo]);
	
	$col_tmp = 'AK'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Door1OpenTime_3_DB[$LRNo]);
	
	$col_tmp = 'AL'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Door1CloseTime_4_DB[$LRNo]);
	
	$col_tmp = 'AM'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Door1OpenTime_5_DB[$LRNo]);
	
	$col_tmp = 'AN'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Door1CloseTime_6_DB[$LRNo]);
	
	$col_tmp = 'AO'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Door1OpenTime_7_DB[$LRNo]);
	
	$col_tmp = 'AP'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Door1CloseTime_8_DB[$LRNo]);
	
	$col_tmp = 'AQ'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Door1OpenTime_9_DB[$LRNo]);
	
	$col_tmp = 'AR'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Door1CloseTime_10_DB[$LRNo]);
	
	$col_tmp = 'AS'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp ,$Door1OpenTime_11_DB[$LRNo]);
	
	$col_tmp = 'AT'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Door1CloseTime_12_DB[$LRNo]);
	
	$col_tmp = 'AU'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Door1OpenTime_13_DB[$LRNo]);
	
	$col_tmp = 'AV'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Door1CloseTime_14_DB[$LRNo]);
	
	$col_tmp = 'AW'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Door1OpenTime_15_DB[$LRNo]);

	$col_tmp = 'AW'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Door1CloseTime_16_DB[$LRNo]);
	
	$col_tmp = 'AY'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Door1OpenTime_17_DB[$LRNo]);
	
	$col_tmp = 'AZ'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Door1CloseTime_18_DB[$LRNo]);
	
	$col_tmp = 'BA'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Door2OpenTime_19_DB[$LRNo]);
	
	$col_tmp = 'BB'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Door2CloseTime_20_DB[$LRNo]);
	
	$col_tmp = 'BC'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Door2OpenTime_21_DB[$LRNo]);
	
	$col_tmp = 'BD'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Door2CloseTime_22_DB[$LRNo]);
	
	$col_tmp = 'BE'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Door2OpenTime_23_DB[$LRNo]);
	
	$col_tmp = 'BF'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Door2CloseTime_24_DB[$LRNo]);
	
	$col_tmp = 'BG'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Door2OpenTime_25_DB[$LRNo]);
	
	$col_tmp = 'BH'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Door2CloseTime_26_DB[$LRNo]);
	
	$col_tmp = 'BI'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Door2OpenTime_27_DB[$LRNo]);
	
	$col_tmp = 'BJ'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Door2CloseTime_28_DB[$LRNo]);
	
	$col_tmp = 'BK'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Door2OpenTime_29_DB[$LRNo]);
	
	$col_tmp = 'BL'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Door2CloseTime_30_DB[$LRNo]);
	
	$col_tmp = 'BM'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Door2OpenTime_31_DB[$LRNo]);

	$col_tmp = 'BN'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Door2CloseTime_32_DB[$LRNo]);
	
	$col_tmp = 'BO'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Door2OpenTime_33_DB[$LRNo]);
	
	$col_tmp = 'BP'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Door2CloseTime_34_DB[$LRNo]);
	
	$col_tmp = 'BQ'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Door2OpenTime_35_DB[$LRNo]);
	
	$col_tmp = 'BR'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Door2CloseTime_36_DB[$LRNo]);
	
	$col_tmp = 'BS'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $InvoiceCreateTime_DB[$LRNo]);
	
	$col_tmp = 'BT'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $status);			
}*/
?>
