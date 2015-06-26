<?php
$sheet1_row = 2;
$sheet2_row = 2;

/*if($DEBUG_OFFLINE)
{
	$userdates = array();
}*/
function get_halt_xml_data($startdate, $enddate, $read_excel_path, $time1_ev, $time2_ev)
{
	//echo "\nEnddate	=".$enddate." ,time1_ev=".$time1_ev." ,time2_ev=".$time2_ev;	
	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
	global $old_xml_date;
	global $DEBUG_OFFLINE;
	echo "\nSD=".$startdate." ,ED=".$enddate." ,Time1=".$time1_ev;
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
	//echo "\nDebug1";
	global $Lat;
	global $Lng;
	global $DistVar;
	global $IMEI;
	global $RouteType;	
	global $NO_GPS;
	//global $PlantIn;
	
	/*global $PlantLat;
	global $PlantLng;*/
	global $PlantCoord;
	global $PlantDistVar;
	global $Status;
	global $SecondaryVehicle;
	
	global $PlantInDate;
	global $PlantInTime;
	global $PlantOutDate;
	global $PlantOutTime;	

	global $PlantOutScheduleTime;
	global $PlantOutDelay;
	
	global $objPHPExcel_1;
	
	global $last_vehicle_name;
	global $last_halt_time;
	global $last_halt_time_new;
	
	//######## SORTED ROUTES
	global $Vehicle_CI;
	global $StationNo_CI;
	global $RouteNo_CI;
	global $RouteType_CI;
	global $ArrivalTime_CI;
	global $TransporterI_CI;

	$current_halt_time =0;
	$objPHPExcel_1 = null;	
	//$objPHPExcel_1 = new PHPExcel();
	
	$objPHPExcel_1 = PHPExcel_IOFactory::load($read_excel_path);
	//echo "\nDebug2:".$read_excel_path;
	//##### REMOVE EXTRA SHEETS
	$objPHPExcel_1->removeSheetByIndex(2);
	$objPHPExcel_1->removeSheetByIndex(1);
	
	//echo date('H:i:s') , " Write to Excel2007 format" , EOL;
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel_1, 'Excel2007');
	$objWriter->save($read_excel_path);
	echo date('H:i:s') , " File written to " , $read_excel_path , EOL;
		
	//###### RELOAD SHEET
	$objPHPExcel_1 = null;
	$objPHPExcel_1 = PHPExcel_IOFactory::load($read_excel_path);
	
	//####### RECREATE EXTRA SHEETS #####################
	//###################################################	
	$header_font = array(
	'font'  => array(
	'bold'  => true,
	'color' => array('rgb' => '000000'), //RED
	'size'  => 10
	//'name'  => 'Verdana'
	));
	
	$objPHPExcel_1->createSheet();
	$objPHPExcel_1->setActiveSheetIndex(1)->setTitle('Route Completed');
		
	$objPHPExcel_1->createSheet();
	$objPHPExcel_1->setActiveSheetIndex(2)->setTitle('Route Pending');		
	echo "\nSecond tab";
	$row =1;
	//###### HEADER
	$col_tmp = 'A'.$row;
	$objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp , "Vehicle");
	$objPHPExcel_1->getActiveSheet(1)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'B'.$row;					
	$objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp , "Route"); 					
	$objPHPExcel_1->getActiveSheet(1)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'C'.$row;
	$objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp , "CustomerCompleted(All)");
	$objPHPExcel_1->getActiveSheet(1)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'D'.$row;
	$objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp , "Transporter(I)");
	$objPHPExcel_1->getActiveSheet(1)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'E'.$row;
	$objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp , "RouteType");
	$objPHPExcel_1->getActiveSheet(1)->getStyle($col_tmp)->applyFromArray($header_font);	
	$row++;						
	//#### SECOND TAB CLOSED ##################################################################	
	
	//############################### THIRD TAB ###############################################
	echo "\nThird tab";
	$row =1;
	//####### DEFINE HEADER
	$col_tmp = 'A'.$row;
	$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , "Vehicle");
	$objPHPExcel_1->getActiveSheet(2)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'B'.$row;					
	$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , "Route"); 			
	$objPHPExcel_1->getActiveSheet(2)->getStyle($col_tmp)->applyFromArray($header_font);	
	$col_tmp = 'C'.$row;
	$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , "Customer Completed");
	$objPHPExcel_1->getActiveSheet(2)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'D'.$row;
	$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , "Customer Incompleted");					
	$objPHPExcel_1->getActiveSheet(2)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'E'.$row;
	$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , "Transporter(I)");					
	$objPHPExcel_1->getActiveSheet(2)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'F'.$row;
	$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , "RouteType");					
	$objPHPExcel_1->getActiveSheet(2)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'G'.$row;
	$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , "Remark");
	$objPHPExcel_1->getActiveSheet(2)->getStyle($col_tmp)->applyFromArray($header_font);
 
	$row++;	
	//######## EXTRA SHEET CLOSED
		
	//echo "\nSD=".$startdate." ,ED=".$enddate." ,read_excel_path=".$read_excel_path." ,VehicleSize=".sizeof($Vehicle);
	echo "\nSizeVehicle=".sizeof($Vehicle);	

}	

?>
