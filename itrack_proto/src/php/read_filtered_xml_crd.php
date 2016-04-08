<?php
  //set_time_limit(2000);
  include_once("get_io.php");
  include_once("util.fuel_calibration.php");
  
  //echo "In function";
  //$report_type = "report_halt";    // TYPES  
  // report_last_pos
  // report_track
  // report_halt
  // report_distance
  // report_fuel
  // report_speed
  // alert_area_violation
  // alert_speed_violation   
  //get_lp_or_track($xml_path, &$datetime, &$vehicleserial, &$vehiclename, &$lat, &$lng, &$vehicletype, &$speed, &$fuel);
  
  function read_xml_imei($xml_path, &$vehicleserial, &$lat, &$lng)
  {
    $fexist =1;
    $fix_tmp = 1;
    $DataValid == 0;
    $final_xml = $xml_path;
    $xml = fopen($final_xml, "r") or $fexist = 0;
    
    $count = count(file($xml_path));
    //echo "<BR>xml_path=".$xml_path." ,COUNT======== $count lines in $xml";
    //$xml2 = '"'.$xml.'"';
    if($fexist)
    {
      //echo "<br>exists";
      $i=0;
      $format = 2;
      
    	while(!feof($xml))          // WHILE LINE != NULL
    	{
    		$DataValid = 0;
        $line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE   
       // echo '<textarea>'.$line.'</textarea>';  
    
        if(strpos($line,'c="1"'))     // RETURN FALSE IF NOT FOUND
        {
          $format = 1;
          $fix_tmp = 1;
        }      
        if(strpos($line,'c="0"'))
        {
          $format = 1;
          $fix_tmp = 0;
        } 
                   
        if ((preg_match('/d="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match) ) && (preg_match('/e="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
        { 
          $lat_value = explode('=',$lat_match[0]);
          $lng_value = explode('=',$lng_match[0]);
          //echo "<br>lat=".$lat_value[1]." lng=".$lng_value[1];
          
          if( (strlen($lat_value[1])>5) && ($lat_value[1]!="-") && (strlen($lng_value[1])>5) && ($lng_value[1]!="-") )
          {
            $DataValid = 1;
          }
        }
                    
        // FORMAT 1 OPENS          
        if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($fix_tmp==1) && ($format == 1) && ($DataValid == 1))
        {
         // echo "<br>IN format1";             
            $status = preg_match('/v="[^" ]+/', $line, $vehicleserial_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
          //  echo 'test4<BR>';
            $status = preg_match('/d="[^" ]+/', $line, $lat_tmp);
            if($status==0)
            {
              continue;
            }
           // echo "test5".'<BR>';
            $status = preg_match('/e="[^" ]+/', $line, $lng_tmp);
            if($status==0)
            {
              continue;
            }                                                                                                                                                                                                                                                                                        
            
            $tmp = explode("=",$vehicleserial_tmp[0]);
            $vehicleserial[$i] = preg_replace('/"/', '', $tmp[1]);
			//echo "vehicleserial=".$vehicleserial[$i]."<br>";
                        
            $tmp = explode("=",$lat_tmp[0]);
            $lat[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$lng_tmp[0]);
            $lng[$i] = preg_replace('/"/', '', $tmp[1]);
                      
            $i++;                      
        } // IF IMEI FORMAT 1 CLOSED              
                      
        /////////////////////////////////////////////////////////////                            
      }   // WHILE CLOSED
    }
 }  // FUNCTION READ LP XML IMEI CLOSED
 
  
  function read_cld_xml($xml_path, &$datetime, &$vehicleserial, &$vehiclename, &$vehiclenumber, &$last_halt_time, &$lat, &$lng, &$vehicletype, &$speed, &$io1, &$io2, &$io3, &$io4, &$io5, &$io6, &$io7, &$io8)
  {
    $fexist =1;
    $fix_tmp = 1;
    $DataValid == 0;
    $final_xml = $xml_path;
    $xml = fopen($final_xml, "r") or $fexist = 0;
    
    $count = count(file($xml_unsorted));
    //echo "<BR>COUNT======== $count lines in $xml";
    //$xml2 = '"'.$xml.'"';
    if($fexist)
    {
      $i=0;
      $format = 2;
      
    	while(!feof($xml))          // WHILE LINE != NULL
    	{
    		$DataValid = 0;
        $line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE   
        //echo '<textarea>'.$line.'</textarea>';  
    
        if(strpos($line,'c="1"'))     // RETURN FALSE IF NOT FOUND
        {
          $format = 1;
          $fix_tmp = 1;
        }      
        if(strpos($line,'c="0"'))
        {
          $format = 1;
          $fix_tmp = 0;
        } 
                   
        if ((preg_match('/d="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match) ) && (preg_match('/d="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
        { 
          $lat_value = explode('=',$lat_match[0]);
          $lng_value = explode('=',$lng_match[0]);
          //echo "<br>lat=".$lat_value[1]." lng=".$lng_value[1];
          
          if( (strlen($lat_value[1])>5) && ($lat_value[1]!="-") && (strlen($lng_value[1])>5) && ($lng_value[1]!="-") )
          {
            $DataValid = 1;
          }
        }
                
        if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($fix_tmp==1) && ($format == 1) && ($DataValid == 1))
        {
           // echo "<br>IN format1";             
            $status = preg_match('/v="[^" ]+/', $line, $vehicleserial_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
            //echo "test2".'<BR>';
            $status = preg_match('/w="[^"]+/', $line, $vehiclename_tmp);
            if($status==0)
            {
              continue;
            }
            
             //echo "test2".'<BR>';
            $status = preg_match('/x="[^"]+/', $line, $vehiclenumber_tmp);
           /*if($status==0)
            {
              continue;
            }*/
           // echo "test3".'<BR>';
            //$status = preg_match('/\d+-\d+-\d+ \d+:\d+:\d+/', $line, $datetime_tmp);
            $status = preg_match('/h="[^"]+/', $line, $datetime_tmp);	
            if($status==0)
            {
              continue;
            }
			
			 $status = preg_match('/u="[^"]+/', $line, $lasthalt_tmp);	
            if($status==0)
            {
              continue;
            }
		
            //echo 'test4<BR>';
            $status = preg_match('/d="[^" ]+/', $line, $lat_tmp);
            if($status==0)
            {
              continue;
            }
            //echo "test5".'<BR>';
            $status = preg_match('/e="[^" ]+/', $line, $lng_tmp);
            if($status==0)
            {
              continue;
            }
            //echo "test6".'<BR>';
           /* $status = preg_match('/vehicletype="[^" ]+/', $line, $vehicletype_tmp); 
            if($status==0)
            {
              continue;
            }*/  
            //echo "test7".'<BR>';           
            $status = preg_match('/f="[^" ]+/', $line, $speed_tmp);
            if($status==0)
            {
              continue;
            }
			
			
            //echo "test8".'<BR>';
            
            $tmp = explode("=",$vehicleserial_tmp[0]);
            $vehicleserial[$i] = preg_replace('/"/', '', $tmp[1]);
            
			$status = preg_match('/i="[^" ]+/', $line, $io1_tmp);
            if($status==0)
            {
              continue;
            } 
            
            $status = preg_match('/j="[^" ]+/', $line, $io2_tmp);
            if($status==0)
            {
              continue;
            }   
            
            $status = preg_match('/k="[^" ]+/', $line, $io3_tmp);
            if($status==0)
            {
              continue;
            }  
            
            $status = preg_match('/l="[^" ]+/', $line, $io4_tmp);
            if($status==0)
            {
              continue;
            }  
            
            $status = preg_match('/m="[^" ]+/', $line, $io5_tmp);
            if($status==0)
            {
              continue;
            }  
            
            $status = preg_match('/n="[^" ]+/', $line, $io6_tmp);
            if($status==0)
            {
              continue;
            }  
            
            $status = preg_match('/o="[^" ]+/', $line, $io7_tmp);
            if($status==0)
            {
              continue;
            }  
			
			$status = preg_match('/p="[^" ]+/', $line, $io8_tmp);
            if($status==0)
            {
              continue;
            }             
          
            
           
          /*  $tmp = explode("=",$vehicleserial_tmp[0]);
            $vehicleserial[$i] = preg_replace('/"/', '', $tmp[1]);
            $io = get_io($vehicleserial[$i],'fuel');
            //echo "io=".$io;
            if($io!="io")
            {
              $status = preg_match('/'.$io.'="[^" ]+/', $line, $fuel_tmp);
              if($status==0)
              {
                continue;
              }
            }
			
			$tmp = explode("=",$fuel_tmp[0]);
            $fuel[$i] = preg_replace('/"/', '', $tmp[1]);*/
           
            $tmp = explode("=",$datetime_tmp[0]);
            $datetime[$i] = preg_replace('/"/', '', $tmp[1]); 
			
			$tmp = explode("=",$lasthalt_tmp[0]);
            $last_halt_time[$i] = preg_replace('/"/', '', $tmp[1]);		
            
            $tmp = explode("=",$vehiclename_tmp[0]);
            $vehiclename[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$vehiclenumber_tmp[0]);
            $vehiclenumber[$i] = preg_replace('/"/', '', $tmp[1]);
                       
            $tmp = explode("=",$lat_tmp[0]);
            $lat[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$lng_tmp[0]);
            $lng[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$speed_tmp[0]);
            $speed[$i] = preg_replace('/"/', '', $tmp[1]);
            if($speed[$i]>200)
			{
              $speed[$i] =0;  
			}
			
			$tmp = explode("=",$io1_tmp[0]);
            $io1[$i] = preg_replace('/"/', '', $tmp[1]);
			
			$tmp = explode("=",$io2_tmp[0]);
            $io2[$i] = preg_replace('/"/', '', $tmp[1]);
			
			$tmp = explode("=",$io3_tmp[0]);
            $io3[$i] = preg_replace('/"/', '', $tmp[1]);
			
			$tmp = explode("=",$io4_tmp[0]);
            $io4[$i] = preg_replace('/"/', '', $tmp[1]);
			
			$tmp = explode("=",$io5_tmp[0]);
            $io5[$i] = preg_replace('/"/', '', $tmp[1]);
			
			$tmp = explode("=",$io6_tmp[0]);
            $io6[$i] = preg_replace('/"/', '', $tmp[1]);
			
			$tmp = explode("=",$io7_tmp[0]);
            $io7[$i] = preg_replace('/"/', '', $tmp[1]);
			
			$tmp = explode("=",$io8_tmp[0]);
            $io8[$i] = preg_replace('/"/', '', $tmp[1]);
                             
            $i++;                      
        } // IF FORMAT 2 CLOSED              
                      
        /////////////////////////////////////////////////////////////                            
      }   // WHILE CLOSED
   }
}  // FUNCTION READ LP XML CLOSED
  // FUNCTION SupVoltage closed	     
?>
