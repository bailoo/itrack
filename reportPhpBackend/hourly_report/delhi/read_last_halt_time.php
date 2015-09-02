<?php

function read_last_halt_time($last_halt_time_path)
{	
	global $last_vehicle_name;
	global $last_halt_time;
	global $objPHPExcel_1;	
	//######### EVENING FILE NAME CLOSED	
	$objPHPExcel_1 = PHPExcel_IOFactory::load($last_halt_time_path);
	//$objPHPExcel = new PHPExcel();  //write new file

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
				if($row ==1)
				{										
					$tmp_val="A".$row;
					$last_vehicle_name[] = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					
					$tmp_val="B".$row;
					$last_halt_time[] = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					//$last_halt_time[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					
					break;				
				}			
			}		
		}
	}
}

?>