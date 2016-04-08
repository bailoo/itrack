<?php
//echo "\nKLP_INPUT";
//error_reporting(E_ALL);
//ini_set('display_errors', TRUE);
//ini_set('display_startup_errors', TRUE);

//require_once '../PHPExcel/IOFactory.php';

/*
$objPHPExcel = PHPExcel_IOFactory::load("lat_lng/test.xlsx");

$highestColumm = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();
$highestRow = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
*/
$account_id = "715";
$input_date = date('Y-m');
$path = $abspath."/daily_report/klp_out/klp_input/".$account_id."/".$input_date.".xlsx";
//$path = $abspath."/daily_report/klp_out/klp_input/".$account_id."/dummy3.xlsx";
echo "\nPath=".$path;

$objPHPExcel_1 = null;
$objPHPExcel_1 = PHPExcel_IOFactory::load($path);
//$objPHPExcel = new PHPExcel();  //write new file

$highestColumm = $objPHPExcel_1->setActiveSheetIndex(0)->getHighestColumn();
$highestRow = $objPHPExcel_1->setActiveSheetIndex(0)->getHighestRow();
//echo 'getHighestColumn() =  [' . $highestColumm . ']<br/>';
//echo 'getHighestRow() =  [' . $highestRow . ']<br/>';

//$objPHPExcel= $objPHPExcel->setActiveSheetIndex(0);
//$objPHPExcel->getActiveSheet()->setCellValue('A'.$highestRow+1, "SHEET2 AFTER");				
//$objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$highestRow+1, "SHEET1 AFTER");			
$highestRow = 0;
$no = $highestRow+1;

//echo "\nNo=".$no."\n";
//$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$no , 'Hello'); 

//########### GET SHEET2 RECORDS
/*
foreach ($objPHPExcel_1->setActiveSheetIndex(1)->getRowIterator() as $row) 
{
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(false);
	
	foreach ($cellIterator as $cell) 
	{
        if (!is_null($cell)) 
		{
			$column = $cell->getColumn();
			$row = $cell->getRow();	
		}
	}
	$sheet2_row_count = $row;
}
echo "\nSheet2Row=".$sheet2_row_count;
*/

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
				$tmp_val="H".$row;
				$icd_indate_tmp = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd');
								
				$tmp_val="I".$row;
				$icd_intime_tmp = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');
			
				echo "\nicd_indate_tmp=".$icd_indate_tmp;
				
				if( ($icd_indate_tmp == "") && ($icd_intime_tmp == "") )
				{
					//echo "\nInICD";
					$tmp_val="A".$row;
					$vehicle_name[] = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();	
					//echo  "\n".$objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					$tmp_val="B".$row;
					$icd_code[] = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();				
					
					$tmp_val="C".$row;
					$icd_outdate[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd');
					
					$tmp_val="D".$row;
					$icd_outtime[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');
									
					$tmp_val="E".$row;
					$factory_code[] = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					
					$tmp_val="F".$row;
					$factory_expected_arrival_date[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd');
					
					$tmp_val="G".$row;
					$factory_expected_arrival_time[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');					
									
					//$tmp_val="H".$row;
					//$icd_indate[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd');
									
					//$tmp_val="I".$row;
					//$icd_intime[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel->getActiveSheet()->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');
									
					$tmp_val="J".$row;
					$remark[] = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					break;
				}
			}			
		}		
    }
}

/*
echo date('H:i:s') , " Write to Excel2007 format" , EOL;
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save("lat_lng/test2.xlsx");
echo date('H:i:s') , " File written to " , "lat_lng/test.xlsx" , EOL;
*/
?>