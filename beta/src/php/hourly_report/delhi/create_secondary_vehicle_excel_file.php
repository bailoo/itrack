<?php
//echo "\nIncludeSecondaryFile";
function create_secondary_vehicles($read_excel_path, $shift, $route_type_param)
{
	echo "\nInCreateSecondaryVehicle";
	global $objPHPExcel_1;
	global $DbConnection;
	global $account_id;
	
	$query ="SELECT vehicle.vehicle_name FROM vehicle,secondary_vehicle WHERE secondary_vehicle.vehicle_id=vehicle.vehicle_id AND secondary_vehicle.status=1 AND vehicle.status=1 AND secondary_vehicle.shift='$shift' AND secondary_vehicle.create_id='$account_id'";
	echo $query."\n";
	$result = mysql_query($query,$DbConnection);
	while($row = mysql_fetch_object($result))
	{
		$secondary_vehicle_list[] = $row->vehicle_name;
	}
	
	//print_r($secondary_vehicle_list);	
	$objPHPExcel_1 = null;
	$objPHPExcel_1 = new PHPExcel();
	$objPHPExcel_1->setActiveSheetIndex(0)->setTitle('SecondaryVehicles');
	
	$cellIterator = null;
	$column = null;
	$row = null;
	//echo "\n1";
	//################ FIRST TAB ############################################
	//#######################################################################
	$row=1;
	
	//###### FILL THE HEADER
	$col_tmp = 'A'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Vehicle");
	//$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
		
	$row++;		
	echo "\nSecondary_vehicle_list=".sizeof($secondary_vehicle_list);
	
	for($i=0;$i<sizeof($secondary_vehicle_list);$i++)
	{		
		$col_tmp = 'A'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $secondary_vehicle_list[$i]);
		$row++;	
	}	
	//#### WRITE FINAL XLSX
	echo date('H:i:s') , " Write to Excel2007 format" , EOL;
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel_1, 'Excel2007');
	$objWriter->save($read_excel_path);
	echo date('H:i:s') , " File written to " , $read_excel_path , EOL;	
}

?>
