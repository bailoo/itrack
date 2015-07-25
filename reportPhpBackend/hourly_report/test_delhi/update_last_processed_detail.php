<?php

function update_last_processed_detail($last_processed_path, $current_time)
{
	global $last_vehicle_name;
	global $last_halt_time;
	
	//echo "\nUPDATE_LAST_FILE";	
	//echo "\nPath=".$path;
	//######### EVENING FILE NAME CLOSED 			
	$objPHPExcel_1 = null;
	//$objPHPExcel_1 = PHPExcel_IOFactory::load($last_file_path);
	$objPHPExcel_1 = new PHPExcel();  //write new file
	
	$objPHPExcel_1->createSheet();
	$objPHPExcel_1->setActiveSheetIndex(1)->setTitle('Customer_Color');

	//$highestColumm = $objPHPExcel_1->setActiveSheetIndex(0)->getHighestColumn();
	//$highestRow = $objPHPExcel_1->setActiveSheetIndex(0)->getHighestRow();
		
	//$highestRow = 0;
	//$no = $highestRow+1;
	$count = 0;
	$last_record_size = sizeof($last_vehicle_arr);
	
	$cellIterator = null;
	$column = null;
	$row = null;

	foreach ($objPHPExcel_1->setActiveSheetIndex(0)->getRowIterator() as $row) 
	{
		$cellIterator = $row->getCellIterator();
		$cellIterator->setIterateOnlyExistingCells(false);
		$i=0;
		foreach ($cellIterator as $cell) 
		{
			if (!is_null($cell)) 
			{
				$column = $cell->getColumn();
				$row = $cell->getRow();
				//if($row > $sheet2_row_count)
				//if($row >1)				
				if($row == 1)				
				{			
					$col_tmp = 'A'.$row;
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Last Vehicle Name");				
					$col_tmp = 'B'.$row;
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Last Halt Time");					
					break;
				}
				else
				{				
					$last_halt_time_excel = PHPExcel_Style_NumberFormat::toFormattedString($last_halt_time[$count], 'YYYY-mm-dd hh:mm:ss');					
					
					$col_tmp = 'A'.$row;
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $last_vehicle_name[$count]); 
					$col_tmp = 'B'.$row;
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $last_halt_time_excel); 										
					$count++;
					break;				
				}			
			}		
		}
		
		if($count == ($last_record_size-1))
		{
			break;
		}
	}

	/*//######## UPDATE SHEET 2
	$row=1;
	$col_tmp = 'A'.$row;
	$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , "Vehicle");
	$objPHPExcel_1->getActiveSheet(2)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'A'.$row;
	$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , "Vehicle");
	$objPHPExcel_1->getActiveSheet(2)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'A'.$row;
	$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , "Vehicle");
	$objPHPExcel_1->getActiveSheet(2)->getStyle($col_tmp)->applyFromArray($header_font);*/
	
	
	//echo date('H:i:s') , " Write to Excel2007 format" , EOL;
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel_1, 'Excel2007');
	$objWriter->save($last_processed_path);
	//echo date('H:i:s') , " File written to " , $read_excel_path , EOL;	
}

?>