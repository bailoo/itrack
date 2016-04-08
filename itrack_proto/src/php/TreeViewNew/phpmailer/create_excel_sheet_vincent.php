<?php
  /////************ CREATE EXCEL SHEETS ***************   
  $r=0;   //row
  echo "\nExcel Sheet".$j." ,Total_days".$total_days;

  //$t=time();
  //$imei_param = $vserial_db."_".$t;
  $imei_param = $vserial_db;
  
  $worksheet =& $workbook->addworksheet($imei_param);  
  $report_title = "VINCENT MONTHLY VTS REPORT -Vehicle:".$vname_global[$j]." Month-($previous_month)";
  $worksheet->write ($r, 0, $report_title, $border1);
  
  for($b=1;$b<=6;$b++)
  {
    $worksheet->write_blank($r, $b,$border1);
  }                              
  $r++;
  
  //echo "\nbefore heading";         
  $worksheet->write($r, 0, "SNo", $text_format);
  $worksheet->write($r, 1, "StartTime", $text_format);
  $worksheet->write($r, 2, "StopTime", $text_format);
  $worksheet->write($r, 3, "Distance(km)", $text_format);
  $worksheet->write($r, 4, "Halt(H:m:s)", $text_format);  
  $r++;     

  
  for($q=0;$q<$total_days;$q++)                       //******** TOTAL DAYS IN PREVIOUS MONTH
  {
    //echo "\nDAYS=".$q;
    $sno_tmp = $q + 1;

    $starttime = $date_global[$j][$q]." 00:00:00";
    $endtime = $date_global[$j][$q]." 23:59:59";
    $distance_csv = $daily_dist_global[$j][$q];
    $halt_csv = $daily_halt_global[$j][$q]; 
    $hms_halt = secondsToTime($halt_csv);
    $halt_csv_dur = $hms_halt[h].":".$hms_halt[m].":".$hms_halt[s];
      
    //NO OF COLUMNS =4 (SNO, STARTITME, ENDTIME, DISTANCE, HALT)          
    echo "\nstarttime=".$starttime." ,endtime=".$endtime." ,distance_csv=".$distance_csv." ,halt_csv=".$halt_csv;
    //echo "\nbefore write";
    $worksheet->write($r,0, $sno_tmp);
    $worksheet->write($r,1, $starttime);
    $worksheet->write($r,2, $endtime);
    $worksheet->write($r,3, $distance_csv);
    $worksheet->write($r,4, $halt_csv_dur);
    //echo "\nafter write";
               
    $r++; 
  } 
  
  $date1 = $previous_year."-".$previous_month."-01 00:00:00";
  $date2 = $previous_year."-".$previous_month."-".$total_days." 23:59:59";
  $worksheet->write($r,0, "Total", $text_format);
  $worksheet->write($r,1, $date1, $text_format);
  $worksheet->write($r,2, $date2, $text_format);
  $hms_halt_total = secondsToTime($total_halt_global[$j]);
  $halt_total_dur = $hms_halt_total[h].":".$hms_halt_total[m].":".$hms_halt_total[s];
  $worksheet->write($r,3, $total_distance_global[$j], $text_format);
  $worksheet->write($r,4, $halt_total_dur, $text_format);         

  echo "\n SHEET CLOSED\n";          
        
?>                                                