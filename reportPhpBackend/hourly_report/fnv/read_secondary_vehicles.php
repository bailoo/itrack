<?php
echo "\nIncludeSecondaryRead";
function read_secondary_vehicles($read_excel_path)
{
	global $objPHPExcel_1;
	global $SecondaryVehicle;			//SENT FILE	
	echo "\nSECONDARY_VEHICLES:".$read_excel_path;
	//echo "\nPath=".$path;
	//######### SENT FILE NAME CLOSED 			
	$objPHPExcel_1 = null;
	$objPHPExcel_1 = PHPExcel_IOFactory::load($read_excel_path);	

	$cellIterator = null;
	$column = null;
	$row = null;

	//################ FIRST TAB ############################################
	foreach ($objPHPExcel_1->setActiveSheetIndex(0)->getRowIterator() as $row) 
	{
		$cellIterator = $row->getCellIterator();
		$cellIterator->setIterateOnlyExistingCells(false);
		$i=0;
		foreach ($cellIterator as $cell) 
		{
			//if (!is_null($cell)) 
			//{
				$column = $cell->getColumn();
				$row = $cell->getRow();
				//if($row > $sheet2_row_count)				
				
				$tmp_val="A".$row;
				$vehicle_tmp = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();

				if($vehicle_tmp=="")
				{
					$read_completed = true;
					break;
				}			
				if($row>1)
				{
					$SecondaryVehicle[] = $vehicle_tmp;					
					break;
				}				
			//}		
		}
		if($read_completed)
		{
			break;
		}
	}
}

?>
