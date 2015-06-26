<?php

function read_last_processed_detail($last_processed_path)
{	
	global $last_vehicle_name;
	global $last_halt_time;
		
	//######### EVENING FILE NAME CLOSED
	$objPHPExcel_1 = PHPExcel_IOFactory::load($last_processed_path);
	//$objPHPExcel = new PHPExcel();  //write new file

	//$highestColumm = $objPHPExcel_1->setActiveSheetIndex(0)->getHighestColumn();
	//$highestRow = $objPHPExcel_1->setActiveSheetIndex(0)->getHighestRow();		
	//$highestRow = 0;
	//$no = $highestRow+1;

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
				if($row >1)
				{										
					$tmp_val="A".$row;
					$last_vehicle_name[] = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					
					$tmp_val="B".$row;
					$last_halt_time[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					
					break;				
				}			
			}		
		}
	}
	
	/*//######### READ SECOND TAB -COLOR
	$cellIterator = null;
	$column = null;
	$row = null;

	foreach ($objPHPExcel_1->setActiveSheetIndex(1)->getRowIterator() as $row) 
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
				if($row >1)
				{										
					$tmp_val="A".$row;
					$last_vehicle_name[] = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					
					$tmp_val="B".$row;
					$last_halt_time[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					
					break;				
				}			
			}		
		}
	}
	//###################*/
}

/*
echo date('H:i:s') , " Write to Excel2007 format" , EOL;
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save("lat_lng/test2.xlsx");
echo date('H:i:s') , " File written to " , "lat_lng/test.xlsx" , EOL;
*/
?>