<?php
function read_sent_file($read_excel_path)
{
    global $TripDate;
    global $DCSM_NAME;
    global $Route;
    global $VehicleNo;
    global $ActivityTimeForWeightOut;
    global $ActivityTimeForWeightIn;
    global $UniqueVehicle;

    echo "\nREAD_SENT_FILE";
    echo "\nPath=".$read_excel_path;
    //######### SENT FILE NAME CLOSED 			
    $objPHPExcel_1 = null;
    $objPHPExcel_1 = PHPExcel_IOFactory::load($read_excel_path);	

    $cellIterator = null;
    $column = null;
    $row = null;

    //################ FIRST TAB ############################################
    $read_completed = false;
    $read_red = false;
    foreach ($objPHPExcel_1->setActiveSheetIndex(0)->getRowIterator() as $row) {
        
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);
        $i=0;
        foreach ($cellIterator as $cell) {
            
            $column = $cell->getColumn();
            $row = $cell->getRow();
            //if($row > $sheet2_row_count)				
            if($row>1) { 
                
                $tmp_val="D".$row;
                $vehicle_tmp = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();

                if($vehicle_tmp=="") {
                    $read_completed = true;
                    break;
                }
                
                $UniqueVehicle[$vehicle_tmp] = $vehicle_tmp;
                $VehicleNo[] = $vehicle_tmp;
                               
                //$tmp_val="A".$row;
                //$TripDate[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd');					
                $tmp_val="A".$row;
                $TripDateTmp = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
		$TripDateTmp1 = explode('.',$TripDateTmp);
		$TripDate[] = $TripDateTmp1[2]."-".$TripDateTmp1[1]."-".$TripDateTmp1[0]; 


                $tmp_val="B".$row;
                $DCSM_NAME[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
                
                $tmp_val="C".$row;
                $Route[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
                
                $tmp_val="E".$row;
                $ActivityTimeForWeightOut[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');					
                
                $tmp_val="F".$row;
                $ActivityTimeForWeightIn[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'hh:mm:ss');					
                
                break;
            }						
        }
        if($read_completed)
        {
            break;
        }
    }	
	//#### READ FIRST TAB CLOSED ################################################	
}

?>
