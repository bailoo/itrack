<?php
$debug_msg ="";

function updateAll_last_processed_time($type, $last_time,$route_type) {
    //echo "\nLAST PROCESSED TIME";
    global $LOG;
    $title = "delhi";
    global $DbConnection;
    global $account_id;
    global $vehicle_imei_rdb;
    global $DEBUG_OFFLINE;
    global $DEBUG_ONLINE;
    global $difftime;
    global $debug_msg;
        
    echo "\nSize=".sizeof($vehicle_imei_rdb);
    $msg = "\nSizeImei=".sizeof($vehicle_imei_rdb);
    
    if($LOG) {$debug_msg.=$msg."\n";}
    
    foreach($vehicle_imei_rdb as $imei) {
        $query = "SELECT imei FROM last_processed_time USE INDEX(last_processed_time_key) WHERE account_id='$account_id' AND imei='$imei' AND type='$type' AND routetype='$route_type'" ;
        echo "\nQA1=".$query;
        $result = mysql_query($query,$DbConnection);
        $numrows = mysql_num_rows($result);
        $msg = "\nFIRST::IMEI=".$imei."\n SELECT QUERY::=>".$query." \nRESULT RESULT::=>".$result." \nNUMROWS::=>".$numrows;     
        if($LOG) {$debug_msg.=$msg."\n";}
        if($numrows ==0) {
            $query_insert = "INSERT INTO last_processed_time(imei,last_time,account_id,type,routetype) values('$imei','$last_time','$account_id','$type','$route_type')";
            $result_insert = mysql_query($query_insert,$DbConnection); 
            echo "\nQA2=".$query_insert;
            $msg = "\nFIRST::IMEI=".$imei."\nSELECT INSERT::=>".$query_insert." \nRESULT INSERT::=>".$result_insert;
            if($LOG) {$debug_msg.=$msg."\n";}
        } /*else {
            $query_update = "UPDATE last_processed_time SET last_time='$last_time' WHERE account_id='$account_id' AND imei='$imei' AND type='$type' AND routetype='$route_type'";
            $result_update = mysql_query($query_update,$DbConnection); 
            echo "\nQA3=".$query_update;
        }*/
     }   
        $query_update = "UPDATE last_processed_time SET last_time='$last_time' WHERE account_id='$account_id' AND type='$type' AND routetype='$route_type'";
        $result_update = mysql_query($query_update,$DbConnection);
        
        $msg = "\nFIRST::IMEI=".$imei."\nQUERY UPDATE::=>".$query_update." \nRESULT UPDATE::=>".$result_update;
        if($LOG) {$debug_msg.=$msg."\n";}
        
        echo "\nQA3=".$query_update;       
    //}
    if(!$DEBUG_OFFLINE && !$DEBUG_ONLINE && $LOG) {
        write_log($title,$difftime);
    }
}


function update_last_processed_time($type, $last_time, $imei, $route_type) {
    //echo "\nLAST PROCESSED TIME";
    global $LOG;
    $title = "delhi";
    global $DbConnection;
    global $account_id;
    global $vehicle_imei_rdb;
    global $DEBUG_OFFLINE;
    global $DEBUG_ONLINE;
    global $difftime;
    global $debug_msg;


        $query = "SELECT imei FROM last_processed_time USE INDEX(last_processed_time_key) WHERE account_id='$account_id' AND imei='$imei' AND type='$type' AND routetype='$route_type'";
        echo "\nQ1=".$query;

        $result = mysql_query($query,$DbConnection);
        $numrows = mysql_num_rows($result);
        $msg = "\nACTION::IMEI=".$imei."\n SELECT QUERY::=>".$query." \nRESULT RESULT::=>".$result." \nNUMROWS::=>".$numrows;
        if($LOG) {$debug_msg.=$msg."\n";}


        /*if($numrows ==0) {
            $query_insert = "INSERT INTO last_processed_time(imei,last_time,account_id,type,routetype) values('$imei','$last_time','$account_id','$type','$route_type')";
            $result_insert = mysql_query($query_insert,$DbConnection);
            echo "\nQ2=".$query_insert;
        } else {*/
        if($numrows ==1) {
            $query_update = "UPDATE last_processed_time SET last_time='$last_time' WHERE account_id='$account_id' AND imei='$imei' AND type='$type' AND routetype='$route_type'";
            $result_update = mysql_query($query_update,$DbConnection);
            echo "\nQ3=".$query_update;
	    $msg = "\nACTION::IMEI=".$imei."\nQUERY UPDATE::=>".$query_update." \nRESULT UPDATE::=>".$result_update;
            if($LOG) {$debug_msg.=$msg."\n";}

        }
        //}
    if(!$DEBUG_OFFLINE && !$DEBUG_ONLINE && $LOG) {
        write_log($title,$difftime);
    }

}


/*function update_last_processed_time($last_processed_time_path, $current_time)
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

    $last_processed_time_excel = PHPExcel_Style_NumberFormat::toFormattedString($current_time, 'YYYY-mm-dd hh:mm:ss');
    $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue('A1' , $last_processed_time_excel);
    $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue("B1" , '');

    //echo date('H:i:s') , " Write to Excel2007 format" , EOL;
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel_1, 'Excel2007');
    $objWriter->save($last_processed_time_path);
    echo date('H:i:s') , " File written to " , $last_processed_time_path , EOL;	
}*/

?>
