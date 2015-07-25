<?php

function update_last_halt_time($last_processed_path)
{
	global $last_vehicle_name;
	global $last_halt_time_new;
	
	/*for($i=0;$i<sizeof($last_halt_time_new);$i++)
	{
		echo "<br>LastHaltTime[$i]:UPDATE=".$last_halt_time_new[$i];
	}*/
	
	//echo "\nUPDATE_LAST_FILE";	
	//echo "\nPath=".$path;
	//######### EVENING FILE NAME CLOSED 			
	$objPHPExcel_1 = null;
	//$objPHPExcel_1 = PHPExcel_IOFactory::load($last_file_path);
	$objPHPExcel_1 = new PHPExcel();  //write new file

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
				//$last_halt_time_excel = PHPExcel_Style_NumberFormat::toFormattedString($last_halt_time[$count], 'YYYY-mm-dd hh:mm:ss');										
				$col_tmp = 'A'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $last_vehicle_name[$count]); 
				$col_tmp = 'B'.$row;
				//echo "<br>LAST_HALT_TIME :BEFORE FINAL UPDATE:".$last_halt_time_new[$count];
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $last_halt_time_new[$count]); 										
				$count++;
				break;						
			}		
		}
		
		if($count == ($last_record_size-1))
		{
			break;
		}
	}	
	
	//echo date('H:i:s') , " Write to Excel2007 format" , EOL;
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel_1, 'Excel2007');
	$objWriter->save($last_processed_path);
	//echo date('H:i:s') , " File written to " , $read_excel_path , EOL;	
}

?>