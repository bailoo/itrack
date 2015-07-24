<?php
function create_hrly_excel($read_excel_path, $min_max_temp_path, $shift, $route_type_param)
{
	echo "\nInCreateHrly";
	global $customer_sel;
	global $customer_name_sel;
	global $plant_sel;
	global $transporter_sel;
	global $expected_time_sel;
	global $station_id;
	global $type;
	global $station_coord;
	global $distance_variable;	
	global $vehicle_name_rdb;				//##### VEHICLE ROUTE -DB DETAIL
	global $customer_name_rdb;
	global $vehicle_imei_rdb;
	global $route_name_rdb;
	global $remark_rdb;
	global $all_routes;
	global $all_customers;
	global $objPHPExcel_1;
	global $relative_customer_input;		//##### VEHICLE CUSTOMER -MASTER DETAIL
	global $relative_route_input;
	
	global $transporter_m;
	global $vehicle_m;
	
	global $route_type_rdb;
	//######### SENT FILE NAME CLOSED 			
	$objPHPExcel_1 = null;
	$objPHPExcel_1 = new PHPExcel();
	//echo "\nobjPHPExcel_1=".$objPHPExcel_1;
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
	//echo "\n1";
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
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "StationName");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);	
	$col_tmp = 'E'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Type");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);	
	$col_tmp = 'F'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "ArrivalDate");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'G'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "ArrivalTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'H'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "DepartureDate");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'I'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "DepartureTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'J'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "HaltDuration");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'K'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "IN-Temperature");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'L'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "OUT-Temperature");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);	
	$col_tmp = 'M'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Min-Temperature");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'N'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Min-TemperatureDate");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'O'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Min-TemperatureTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'P'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Max-Temperature");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'Q'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Max-TemperatureDate");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'R'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Max-TemperatureTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);	
	$col_tmp = 'S'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Remark");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);	
	$col_tmp = 'T'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "ReportDate1");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'U'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "ReportTime1");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'V'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "ReportDate2");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'W'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "ReportTime2");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'X'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Transporter(M)");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'Y'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Transporter(I)");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);	
	$col_tmp = 'Z'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Latitude");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'AA'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Longitude");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'AB'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "DistVar");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'AC'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "IMEI");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'AD'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "NO GPS");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$row++;
		
	//echo "\nSizeRoute=".sizeof($customer_name_rdb);
	
	$vehicle_name_rdb_tmp = array_unique($vehicle_name_rdb);
	$vehicle_name_rdb1 = array();

	foreach($vehicle_name_rdb_tmp as $vrd1)
	{	
		$vehicle_name_rdb1[] = $vrd1;
	}
	
	//echo "\nSizeOf_Vehicle=".sizeof($vehicle_name_rdb1);
	for($i=0;$i<sizeof($vehicle_name_rdb1);$i++)
	{
		$sno=1;
		//if((strpos($route_type_rdb[$i],$route_type_param) !== false) || ($route_type_param=="ALL"))
		//$customer_name_rdb_1 = explode('/',$customer_name_rdb[$i]);
		//$size_rdb1 = 0;
		//$size_rdb1 = sizeof($customer_sel[$vehicle_name_rdb]);		
		//echo "\nVehicle=".$vehicle_name_rdb1[$i];
		//echo "\nSizeCustomer=".sizeof($customer_sel[$vehicle_name_rdb1[$i]]);
		for($k=0;$k<sizeof($customer_sel[$vehicle_name_rdb1[$i]]);$k++)
		{			
			$customer_name_rdb_3 = trim($customer_sel[$vehicle_name_rdb1[$i]][$k]);	
			//echo "\nOne";
			//echo "<br>sizeTransporter=".sizeof($transporter_sel[$customer_name_rdb_3]);						
			//for($j=0;$j<sizeof($transporter_sel[$customer_name_rdb_3]);$j++)				
			//{
				$col_tmp = 'A'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $vehicle_name_rdb1[$i]);
				
				$col_tmp = 'B'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $sno);
														
				$col_tmp = 'C'.$row;
				//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $route_name_rdb_1[$k]);
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $customer_name_rdb_3);

				$col_tmp = 'D'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $customer_name_sel[$vehicle_name_rdb1[$i]][$k]);													
				if($type[$vehicle_name_rdb1[$i]][$k]==0) { $typ_tmp="Customer"; }
				else if($type[$vehicle_name_rdb1[$i]][$k]==1) { $typ_tmp="Plant"; }
				
				$col_tmp = 'E'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $typ_tmp);
								
				//echo "\nSizeTransporter=".sizeof($transporter_m);
				$transporter_name_master = "";
				for($n=0;$n<sizeof($transporter_m);$n++)
				{
					if($vehicle_name_rdb[$i] == $vehicle_m[$n])
					{
						//echo "\nTransporter Matched";
						$transporter_name_master = $transporter_m[$n];
						break;                                  
					}
				}	
				//echo "\nThree";				
				$col_tmp = 'X'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $transporter_name_master);
				
				$col_tmp = 'Y'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $transporter_sel[$vehicle_name_rdb1[$i]][$k]);															
				
				//echo "\nvehicle_name_rdb[i]=".$vehicle_name_rdb1[$i]." ,Customer=".$customer_name_rdb_3." ,StationCoord=".$station_coord[$vehicle_name_rdb1[$i]][$k];
				$coord_str = explode(',',$station_coord[$vehicle_name_rdb1[$i]][$k]);
				
				$col_tmp = 'Z'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , trim($coord_str[0]));
				
				$col_tmp = 'AA'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , trim($coord_str[1]));
				
				$col_tmp = 'AB'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $distance_variable[$vehicle_name_rdb1[$i]][$k]);
				//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $distance_variable[$vehicle_imei_rdb[$i]][$j]);
				
				//echo "<BR>A_CreateHrly ".$i." ".$vehicle_imei_rdb[$i];
				$col_tmp = 'AC'.$row;
				
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValueExplicit($col_tmp, $vehicle_imei_rdb[$vehicle_name_rdb1[$i]], PHPExcel_Cell_DataType::TYPE_STRING);
				
				$col_tmp = 'AD'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "");

				$row++;
				$sno++;
			//}
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
	$col_tmp = 'C'.$row;
	$objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp , "CustomerCompleted(All)");
	$objPHPExcel_1->getActiveSheet(1)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'D'.$row;
	$objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp , "Transporter(I)");
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
	$col_tmp = 'C'.$row;
	$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , "Customer Completed");
	$objPHPExcel_1->getActiveSheet(2)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'D'.$row;
	$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , "Customer Incompleted");					
	$objPHPExcel_1->getActiveSheet(2)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'E'.$row;
	$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , "Transporter(I)");					
	$objPHPExcel_1->getActiveSheet(2)->getStyle($col_tmp)->applyFromArray($header_font);
	$row++;		
		
	//#### THIRD TAB CLOSED ########################################

	//#### WRITE FINAL XLSX
	echo date('H:i:s') , " Write to Excel2007 format" , EOL;
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel_1, 'Excel2007');
	$objWriter->save($read_excel_path);
	echo date('H:i:s') , " File written to " , $read_excel_path , EOL;

	//##### CREATE VEHICLE OUT_TEMPERATURE FILE
	//######### SENT FILE NAME CLOSED 			
	$objPHPExcel_1 = null;
	$objPHPExcel_1 = new PHPExcel();

	$cellIterator = null;
	$column = null;
	$row = 1;
		
	//echo "\nSizeRoute=".sizeof($route_name_rdb);	
	$col_tmp = 'A'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Vehicle");

	$col_tmp = 'B'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "MinTemp");
				
	$col_tmp = 'C'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "MinTempDate");
	
	$col_tmp = 'D'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "MinTempTime");

	$col_tmp = 'E'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "MaxTemp");

	$col_tmp = 'F'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "MaxTempDate");

	$col_tmp = 'G'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "MaxTempTime");
	$row++;
	
	for($i=0;$i<sizeof($vehicle_name_rdb1);$i++)
	{		
		$col_tmp = 'A'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $vehicle_name_rdb1[$i]);
					
		$col_tmp = 'B'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, "");

		$col_tmp = 'C'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, "");	

		$col_tmp = 'D'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, "");

		$col_tmp = 'E'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, "");	

		$col_tmp = 'F'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, "");		
		$row++;
	}	
	
	//#### WRITE FINAL XLSX
	echo date('H:i:s') , " Write to Excel2007 format" , EOL;
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel_1, 'Excel2007');
	$objWriter->save($min_max_temp_path);
	echo date('H:i:s') , " File written to " , $min_max_temp_path , EOL;	

} //FUNCTION CLOSED

?>
