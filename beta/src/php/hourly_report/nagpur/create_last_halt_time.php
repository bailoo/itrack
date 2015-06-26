<?php
function create_last_halt_time($last_halt_time_path)
{
	echo "\nInCreateLast HaltTime";
	global $vehicle_name_rdb;				//##### VEHICLE ROUTE -DB DETAIL
	global $vehicle_imei_rdb;
	global $route_name_rdb;
	
	global $relative_customer_input;		//##### VEHICLE CUSTOMER -MASTER DETAIL
	global $relative_route_input;
	global $objPHPExcel_1;
	
	//######### SENT FILE NAME CLOSED 			
	$objPHPExcel_1 = null;
	$objPHPExcel_1 = new PHPExcel();

	$cellIterator = null;
	$column = null;
	$row = 1;
		
	//echo "\nSizeRoute=".sizeof($route_name_rdb);	
	for($i=0;$i<sizeof($route_name_rdb);$i++)
	{		
		$col_tmp = 'A'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $vehicle_name_rdb[$i]);
					
		$col_tmp = 'B'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "0");			
		$row++;
	}	
	
	//#### WRITE FINAL XLSX
	echo date('H:i:s') , " Write to Excel2007 format" , EOL;
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel_1, 'Excel2007');
	$objWriter->save($last_halt_time_path);
	echo date('H:i:s') , " File written to " , $last_halt_time_path , EOL;		
}

?>