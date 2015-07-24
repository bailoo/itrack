<?php

function update_last_processed_time($last_processed_time_path, $current_time)
{	
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
	
	$cellIterator = null;
	$column = null;
	$row = 1;

	foreach ($objPHPExcel_1->setActiveSheetIndex(0)->getRowIterator() as $row) 
	{
		$cellIterator = $row->getCellIterator();
		$cellIterator->setIterateOnlyExistingCells(false);

		foreach ($cellIterator as $cell) 
		{
			if (!is_null($cell)) 
			{
				$column = $cell->getColumn();
				$row = $cell->getRow();
				//if($row > $sheet2_row_count)
				//if($row >1)													
				$last_processed_time_excel = PHPExcel_Style_NumberFormat::toFormattedString($current_time, 'YYYY-mm-dd hh:mm:ss');
									
				$col_tmp = 'A'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $last_processed_time_excel);				
				break;						
			}		
		}
	}	
	
	//echo date('H:i:s') , " Write to Excel2007 format" , EOL;
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel_1, 'Excel2007');
	$objWriter->save($last_processed_time_path);
	echo date('H:i:s') , " File written to " , $last_processed_time_path , EOL;	
}

?>