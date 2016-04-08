<?php
function create_hrly_excel($read_excel_path, $shift)
{
	echo "\nInCreateHrly";
	global $customer_sel;
	global $plant_sel;
	global $transporter_sel;
	global $expected_time_sel;
	global $station_id;
	global $type;
	global $station_coord;
	global $distance_variable;	
	global $vehicle_name_rdb;				//##### VEHICLE ROUTE -DB DETAIL
	global $vehicle_imei_rdb;
	global $route_name_rdb;
	global $remark_rdb;
	global $all_routes;
	
	global $relative_customer_input;		//##### VEHICLE CUSTOMER -MASTER DETAIL
	global $relative_route_input;
	
	//######### SENT FILE NAME CLOSED 			
	$objPHPExcel_1 = null;
	$objPHPExcel_1 = new PHPExcel();
	/*if (file_exists($read_excel_path))
	{		
		$objPHPExcel_1 = new PHPExcel();  //write new file
	}
	else
	{
		$objPHPExcel_1 = PHPExcel_IOFactory::load($read_excel_path);
	}*/	

	$objPHPExcel_1->setActiveSheetIndex(0)->setTitle('Halt Report');

	$objPHPExcel_1->createSheet();
	$objPHPExcel_1->setActiveSheetIndex(1)->setTitle('Route Completed');
	
	$objPHPExcel_1->createSheet();
	$objPHPExcel_1->setActiveSheetIndex(2)->setTitle('Route Pending');	

	$cellIterator = null;
	$column = null;
	$row = null;
	echo "\n1";
	//################ FIRST TAB ############################################
	//#######################################################################
	$row=1;
	
	//###### COLOR STYLES
	$header_font = array(
		'font'  => array(
		'bold'  => true,
		'color' => array('rgb' => '000000'), //RED
		'size'  => 10
		//'name'  => 'Verdana'
	));
	$styleFontRed = array(
	'font'  => array(
		'bold'  => true,
		'color' => array('rgb' => 'FF0000'), //RED
		'size'  => 10
		//'name'  => 'Verdana'
	));	
	
	//###### FILL THE HEADER
	$col_tmp = 'A'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Vehicle");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'B'.$row;					
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "SNo");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);	
	$col_tmp = 'C'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "StationNo");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'D'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Type");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'E'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "RouteNo");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'F'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "ReportShift");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'G'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "ArrivalDate");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'H'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "ArrivalTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'I'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "DepartureDate");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'J'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "DepartureTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'K'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "ScheduleTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'L'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Delay (Mins)");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'M'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "HaltDuration");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'N'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Remark");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);	
	$col_tmp = 'O'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "ReportDate1");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'P'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "ReportTime1");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'Q'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "ReportDate2");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'R'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "ReportTime2");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'S'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "TransporterM");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);	
	$col_tmp = 'T'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Plant");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);	
	$col_tmp = 'U'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Latitude");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'V'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Longitude");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'W'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "DistVar");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'X'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "IMEI");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$row++;
		
	echo "\nSizeRoute=".sizeof($route_name_rdb);
	
	for($i=0;$i<sizeof($route_name_rdb);$i++)
	{
		//echo "\nCreateHrly".$i;
		for($j=0;$j<sizeof($customer_sel[$route_name_rdb[$i]]);$j++)
		{
			$col_tmp = 'A'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $vehicle_name_rdb[$i]);
			
			$col_tmp = 'B'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $j+1);
			
			$col_tmp = 'C'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $customer_sel[$route_name_rdb[$i]][$j]);
			
			$col_tmp = 'D'.$row;
			if($type[$route_name_rdb[$i]][$j]==1)
			{
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Plant");
			}
			else
			{
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Customer");
			}
			
			$col_tmp = 'E'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $route_name_rdb[$i]);
			
			$col_tmp = 'F'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $shift);

			$col_tmp = 'K'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $expected_time_sel[$i][$j]);

			$col_tmp = 'N'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $remark_rdb[$i]);

			//$col_tmp = 'L'.$row;
			//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $shift);			

			$coord_str = explode(",",$station_coord[$route_name_rdb[$i]][$j]);
			
			$col_tmp = 'U'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $coord_str[0]);
			
			$col_tmp = 'V'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $coord_str[1]);
			
			$col_tmp = 'W'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $distance_variable[$route_name_rdb[$i]][$j]);
			
			$col_tmp = 'X'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $vehicle_imei_rdb[$i]);
			
			$row++;
		}
	}
	//####### INSERT UNMATCHED ROUTES
	/*$row++;
	echo "\nSizeAllRoute2=".sizeof($all_routes);
	for($i=0;$i<sizeof($all_routes);$i++)
	{
		for($j=0;$j<sizeof($route_name_rdb);$j++)
		{
			if( trim($all_routes[$i])!=trim($route_name_rdb[$j]) )
			{
				//echo "\nUnMatched Route=".$all_routes[$i];
				$col_tmp = 'E'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $all_routes[$i]);
				$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($styleFontRed);
				$row++;
			}
		}
	}		
	//#### FIRST TAB CLOSED ###################################################################
	
	//####################### SECOND TAB ######################################################
	//if($row > $sheet2_row_count)
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
	$row++;
	*/	
		
	//#### THIRD TAB CLOSED ########################################
	
	//#### WRITE FINAL XLSX
	echo date('H:i:s') , " Write to Excel2007 format" , EOL;
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel_1, 'Excel2007');
	$objWriter->save($read_excel_path);
	echo date('H:i:s') , " File written to " , $read_excel_path , EOL;		
}

?>