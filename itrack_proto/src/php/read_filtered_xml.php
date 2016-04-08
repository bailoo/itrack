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
	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
	global $old_xml_date;
	new_xml_variables();
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
      //$format = 2;
      
    	while(!feof($xml))          // WHILE LINE != NULL
    	{
    		$DataValid = 0;
        $line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE   
      // echo '<textarea>'.$line.'</textarea>';  
  
        /*if(strpos($line,''.$vc.'="1"'))     // RETURN FALSE IF NOT FOUND
        {
          $format = 1;
          $fix_tmp = 1;
        }      
        if(strpos($line,''.$vc.'="0"'))
        {
          $format = 1;
          $fix_tmp = 0;
        } */
       $format = 1;
	  $fix_tmp = 1;
        if ((preg_match('/'.$vd.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match) ) && (preg_match('/'.$ve.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
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
		/*echo "length=".$line[strlen($line)-2]."<br>";
		echo "line=".$line[0]."<br>";
		echo "fix_tmp=".$fix_tmp."<br>";
		echo "format=".$format."<br>";
		echo "DataValid=".$DataValid."<br>";*/
        if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($fix_tmp==1) && ($format == 1) && ($DataValid == 1))
        {
           //echo "<br>IN format1";             
            $status = preg_match('/'.$vv.'="[^" ]+/', $line, $vehicleserial_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
            //echo 'test4<BR>';
            $status = preg_match('/'.$vd.'="[^" ]+/', $line, $lat_tmp);
            if($status==0)
            {
              continue;
            }
            //echo "test5".'<BR>';
            $status = preg_match('/'.$ve.'="[^" ]+/', $line, $lng_tmp);
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
	  //echo "vehicleserial=".sizeof($vehicleserial)."<br>";
    }
 }  // FUNCTION READ LP XML IMEI CLOSED
 
  function read_xml_imei2($xml_path, &$vehicleserial, &$lat, &$lng, &$noloc ,&$alt ,&$datetime, &$vehiclename, &$vehicletype, &$speed, &$cumdist, &$io1, &$io2, &$io3, &$io4, &$io5, &$io6, &$io7, &$io8 )
  {
	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
	global $old_xml_date;
	new_xml_variables();
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
  
        /*if(strpos($line,''.$vc.'="1"'))     // RETURN FALSE IF NOT FOUND
        {
          $format = 1;
          $fix_tmp = 1;
        }      
        if(strpos($line,''.$vc.'="0"'))
        {
          $format = 1;
          $fix_tmp = 0;
        } */
       $format = 1;
	  $fix_tmp = 1;
        if ((preg_match('/'.$vd.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match) ) && (preg_match('/'.$ve.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
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
		/*echo "length=".$line[strlen($line)-2]."<br>";
		echo "line=".$line[0]."<br>";
		echo "fix_tmp=".$fix_tmp."<br>";
		echo "format=".$format."<br>";
		echo "DataValid=".$DataValid."<br>";*/
        if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($fix_tmp==1) && ($format == 1) && ($DataValid == 1))
        {
           //echo "<br>IN format1";             
            $status = preg_match('/'.$vv.'="[^" ]+/', $line, $vehicleserial_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
            //echo 'test4<BR>';
            $status = preg_match('/'.$vd.'="[^" ]+/', $line, $lat_tmp);
            if($status==0)
            {
              continue;
            }
            //echo "test5".'<BR>';
            $status = preg_match('/'.$ve.'="[^" ]+/', $line, $lng_tmp);
            if($status==0)
            {
              continue;
			}
            $status = preg_match('/noloc="[^" ]+/', $line, $noloc_tmp);
            if($status==0)
            {
              continue;
            }  
			/////////
			//echo "test2".'<BR>';
            $status = preg_match('/'.$vw.'="[^"]+/', $line, $vehiclename_tmp);
            //echo "status1=".$status."<br>";
            if($status==0)
            {
              continue;
            }
			
			$status = preg_match('/'.$vh.'="[^"]+/', $line, $datetime_tmp);
            //echo "status2=".$status."<br>";
            if($status==0)
            {
              continue;
            }
                      
                     
            $status = preg_match('/'.$vf.'="[^" ]+/', $line, $speed_tmp);
            //echo "status6=".$status."<br>";
            if($status==0)
            {
              continue;
            }
           // echo "test9".'<BR>';
            $tmp = explode("=",$vehicleserial_tmp[0]);
            $vehicleserial[$i] = preg_replace('/"/', '', $tmp[1]);
           
            $status = preg_match('/'.$vz.'="[^" ]+/', $line, $cumdist_tmp);
            //echo "status8=".$status."<br>";
            if($status==0)
            {
              continue;
            }    
            
            
            $status = preg_match('/'.$vi.'="[^" ]+/', $line, $io1_tmp);
            if($status==0)
            {
              continue;
            } 
            
            $status = preg_match('/'.$vj.'="[^" ]+/', $line, $io2_tmp);
            if($status==0)
            {
              continue;
            }   
            
            $status = preg_match('/'.$vk.'="[^" ]+/', $line, $io3_tmp);
            if($status==0)
            {
              continue;
            }  
            
            $status = preg_match('/'.$vl.'="[^" ]+/', $line, $io4_tmp);
            if($status==0)
            {
              continue;
            }  
            
            $status = preg_match('/'.$vm.'="[^" ]+/', $line, $io5_tmp);
            if($status==0)
            {
              continue;
            }  
            
            $status = preg_match('/'.$vn.'="[^" ]+/', $line, $io6_tmp);
            if($status==0)
            {
              continue;
            }  
            
            $status = preg_match('/'.$vo.'="[^" ]+/', $line, $io7_tmp);
            if($status==0)
            {
              continue;
            }  
			
			$status = preg_match('/'.$vp.'="[^" ]+/', $line, $io8_tmp);
            if($status==0)
            {
              continue;
            }             
			/////////
			//echo "test6".'<BR>';			
            $tmp = explode("=",$vehicleserial_tmp[0]);
            $vehicleserial[$i] = preg_replace('/"/', '', $tmp[1]);
			//echo "vehicleserial=".$vehicleserial[$i]."<br>";
                        
            $tmp = explode("=",$lat_tmp[0]);
            $lat[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$lng_tmp[0]);
            $lng[$i] = preg_replace('/"/', '', $tmp[1]);
                      
            $tmp = explode("=",$noloc_tmp[0]);
            $noloc[$i] = preg_replace('/"/', '', $tmp[1]);
			////
			$tmp = explode("=",$datetime_tmp[0]);
            $datetime[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$vehiclename_tmp[0]);
            $vehiclename[$i] = preg_replace('/"/', '', $tmp[1]);
			
			 $tmp = explode("=",$speed_tmp[0]);
            $speed[$i] = preg_replace('/"/', '', $tmp[1]);
            $speed[$i] = round($speed[$i],2);
            if($speed[$i]>200)
              $speed[$i] =0;
            
            $tmp = explode("=",$cumdist_tmp[0]);
            $cumdist[$i] = preg_replace('/"/', '', $tmp[1]);  

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
			///
					  
            $i++;                      
        } // IF IMEI FORMAT 1 CLOSED              
                      
        /////////////////////////////////////////////////////////////                            
      }   // WHILE CLOSED
	  //echo "vehicleserial=".sizeof($vehicleserial)."<br>";
    }
 }  // FUNCTION READ LP XML IMEI CLOSED 
 
  function read_lp_xml($xml_path, &$datetime, &$vehicleserial, &$vehiclename, &$vehiclenumber, &$lat, &$lng, &$vehicletype, &$speed, &$io1, &$io2, &$io3, &$io4, &$io5, &$io6, &$io7, &$io8)
  {
	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
	global $old_xml_date;
	new_xml_variables();
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
		
       /* if(strpos($line,''.$vc.'="1"'))     // RETURN FALSE IF NOT FOUND
        {
          $format = 1;
          $fix_tmp = 1;
        }      
        if(strpos($line,''.$vc.'="0"'))
        {
          $format = 1;
          $fix_tmp = 0;
        }*/ 
		
		$format = 1;
		$fix_tmp = 1;         
        if ((preg_match('/'.$vd.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match) ) && (preg_match('/'.$ve.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
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
            $status = preg_match('/'.$vv.'="[^" ]+/', $line, $vehicleserial_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
            //echo "test2".'<BR>';
            $status = preg_match('/'.$vw.'="[^"]+/', $line, $vehiclename_tmp);
            if($status==0)
            {
              continue;
            }
            
             //echo "test2".'<BR>';
            $status = preg_match('/'.$vx.'="[^"]+/', $line, $vehiclenumber_tmp);
            /*if($status==0)
            {
              continue;
            }*/
           // echo "test3".'<BR>';
            //$status = preg_match('/\d+-\d+-\d+ \d+:\d+:\d+/', $line, $datetime_tmp);
            $status = preg_match('/'.$vh.'="[^"]+/', $line, $datetime_tmp);	
            if($status==0)
            {
              continue;
            }
            //echo 'test4<BR>';
            $status = preg_match('/'.$vd.'="[^" ]+/', $line, $lat_tmp);
            if($status==0)
            {
              continue;
            }
            //echo "test5".'<BR>';
            $status = preg_match('/'.$ve.'="[^" ]+/', $line, $lng_tmp);
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
            $status = preg_match('/'.$vf.'="[^" ]+/', $line, $speed_tmp);
            if($status==0)
            {
              continue;
            }
            //echo "test8".'<BR>';
            
            $tmp = explode("=",$vehicleserial_tmp[0]);
            $vehicleserial[$i] = preg_replace('/"/', '', $tmp[1]);
            
			$status = preg_match('/'.$vi.'="[^" ]+/', $line, $io1_tmp);
            if($status==0)
            {
              continue;
            } 
            
            $status = preg_match('/'.$vj.'="[^" ]+/', $line, $io2_tmp);
            if($status==0)
            {
              continue;
            }   
            
            $status = preg_match('/'.$vk.'="[^" ]+/', $line, $io3_tmp);
            if($status==0)
            {
              continue;
            }  
            
            $status = preg_match('/'.$vl.'="[^" ]+/', $line, $io4_tmp);
            if($status==0)
            {
              continue;
            }  
            
            $status = preg_match('/'.$vm.'="[^" ]+/', $line, $io5_tmp);
            if($status==0)
            {
              continue;
            }  
            
            $status = preg_match('/'.$vn.'="[^" ]+/', $line, $io6_tmp);
            if($status==0)
            {
              continue;
            }  
            
            $status = preg_match('/'.$vo.'="[^" ]+/', $line, $io7_tmp);
            if($status==0)
            {
              continue;
            }  
			
			$status = preg_match('/'.$vp.'="[^" ]+/', $line, $io8_tmp);
            if($status==0)
            {
              continue;
            }             
          
            
            //////////////           
           // echo "test10".'<BR>';
            /*$status = preg_match('/alt="[^" ]+/', $line, $alt_tmp);
            if($status==0)
            {
              continue;
            } */
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

function read_lp_xml_person($xml_path, &$datetime, &$vehicleserial, &$vehiclename, &$vehiclenumber, &$lat, &$lng, &$vehicletype, &$speed, &$io1, &$io2, &$io3, &$io4, &$io5, &$io6, &$io7, &$io8)
  {
	global $va,$vb,$vc,$vd,$ve,$vg,$vh,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
	global $old_xml_date;
	new_xml_variables();
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
    
        /*if(strpos($line,''.$vc.'="1"'))     // RETURN FALSE IF NOT FOUND
        {
          $format = 1;
          $fix_tmp = 1;
        }      
        if(strpos($line,''.$vc.'="0"'))
        {
          $format = 1;
          $fix_tmp = 0;
        }*/ 
                   
        if ((preg_match('/'.$vd.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match) ) && (preg_match('/'.$ve.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
        { 
          $lat_value = explode('=',$lat_match[0]);
          $lng_value = explode('=',$lng_match[0]);
          //echo "<br>lat=".$lat_value[1]." lng=".$lng_value[1];
          
          if( (strlen($lat_value[1])>5) && ($lat_value[1]!="-") && (strlen($lng_value[1])>5) && ($lng_value[1]!="-") )
          {
            $DataValid = 1;
          }
        }
                
       // if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($fix_tmp==1) && ($format == 1) && ($DataValid == 1))
         if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($DataValid == 1))
		{
           // echo "<br>IN format1";             
            $status = preg_match('/'.$vv.'="[^" ]+/', $line, $vehicleserial_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
            //echo "test2".'<BR>';
            $status = preg_match('/'.$vw.'="[^"]+/', $line, $vehiclename_tmp);
            if($status==0)
            {
              continue;
            }
            
             //echo "test2".'<BR>';
            $status = preg_match('/'.$vx.'="[^"]+/', $line, $vehiclenumber_tmp);
            /*if($status==0)
            {
              continue;
            }*/
           // echo "test3".'<BR>';
            //$status = preg_match('/\d+-\d+-\d+ \d+:\d+:\d+/', $line, $datetime_tmp);
            $status = preg_match('/'.$vh.'="[^"]+/', $line, $datetime_tmp);	
            if($status==0)
            {
              continue;
            }
            //echo 'test4<BR>';
            $status = preg_match('/'.$vd.'="[^" ]+/', $line, $lat_tmp);
            if($status==0)
            {
              continue;
            }
            //echo "test5".'<BR>';
            $status = preg_match('/'.$ve.'="[^" ]+/', $line, $lng_tmp);
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
          
            
            $tmp = explode("=",$vehicleserial_tmp[0]);
            $vehicleserial[$i] = preg_replace('/"/', '', $tmp[1]);            
           
            $tmp = explode("=",$datetime_tmp[0]);
            $datetime[$i] = preg_replace('/"/', '', $tmp[1]);          
            
            $tmp = explode("=",$vehiclename_tmp[0]);
            $vehiclename[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$vehiclenumber_tmp[0]);
            $vehiclenumber[$i] = preg_replace('/"/', '', $tmp[1]);
                       
            $tmp = explode("=",$lat_tmp[0]);
            $lat[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$lng_tmp[0]);
            $lng[$i] = preg_replace('/"/', '', $tmp[1]);                             
            $i++;                      
        } // IF FORMAT 2 CLOSED              
                      
        /////////////////////////////////////////////////////////////                            
      }   // WHILE CLOSED
   }
}  // FUNCTION READ LP XML CLOSED

//////// FUNCTION READ TRACK XML STARTS //////////////
  
  //function read_track_xml($xml_path, &$vehicleserial, &$lat, &$lng, &$alt, &$datetime, &$vehiclename, &$vehicletype, &$speed, &$cumdist, &$place_name_arr, &$io1, &$io2, &$io3, &$io4, &$io5, &$io6, &$io7, &$io8)
  function read_track_xml($xml_path, &$vehicleserial, &$lat, &$lng, &$alt, &$datetime, &$vehiclename, &$vehicletype, &$speed, &$cumdist, &$io1, &$io2, &$io3, &$io4, &$io5, &$io6, &$io7, &$io8)
  { 
	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
	global $old_xml_date;
	new_xml_variables();
    $fexist =1;
    $fix_tmp = 1;
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
       // echo '<textarea>'.$line.'</textarea>';  
    
        /*if(strpos($line,''.$vc.'="1"'))     // RETURN FALSE IF NOT FOUND
        {
          $format = 1;
          $fix_tmp = 1;          
        }      
        else if(strpos($line,''.$vc.'="0"'))
        {
          $format = 1;
          $fix_tmp = 0;
        }*/ 
                   
        if ((preg_match('/'.$vd.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match) ) && (preg_match('/'.$ve.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
        { 
          $lat_value = explode('=',$lat_match[0]);
          $lng_value = explode('=',$lng_match[0]);
          //echo "<br>lat=".$lat_value[1]." lng=".$lng_value[1];
          
          if( (strlen($lat_value[1])>5) && ($lat_value[1]!="-") && (strlen($lng_value[1])>5) && ($lng_value[1]!="-") )
          {
            $DataValid = 1;
          }
        }
           
		//if(($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($fix_tmp==1) && ($format == 1) && ($DataValid == 1))
        if(($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($DataValid == 1))
		{
            //echo "<br>IN format1";             
            $status = preg_match('/'.$vv.'="[^" ]+/', $line, $vehicleserial_tmp);
            //echo "Status=".$status.'<BR>';
           // echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
            //echo "test2".'<BR>';
            $status = preg_match('/'.$vw.'="[^"]+/', $line, $vehiclename_tmp);
            //echo "status1=".$status."<br>";
            if($status==0)
            {
              continue;
            }
           // echo "test4".'<BR>';
            //$status = preg_match('/\d+-\d+-\d+ \d+:\d+:\d+/', $line, $datetime_tmp);
            $status = preg_match('/'.$vh.'="[^"]+/', $line, $datetime_tmp);
            //echo "status2=".$status."<br>";
            if($status==0)
            {
              continue;
            }
            //echo "test5".$datetime_tmp[0].'<BR>';
            $status = preg_match('/'.$vd.'="[^" ]+/', $line, $lat_tmp);
            //echo "status3=".$status."<br>";
            if($status==0)
            {
              continue;
            }
            //echo "test6".'<BR>';
            $status = preg_match('/'.$ve.'="[^" ]+/', $line, $lng_tmp);
            //echo "status4=".$status."<br>";
            if($status==0)
            {
              continue;
            }            
                     
            $status = preg_match('/'.$vf.'="[^" ]+/', $line, $speed_tmp);
            //echo "status6=".$status."<br>";
            if($status==0)
            {
              continue;
            }
           // echo "test9".'<BR>';
            $tmp = explode("=",$vehicleserial_tmp[0]);
            $vehicleserial[$i] = preg_replace('/"/', '', $tmp[1]);
           
            $status = preg_match('/'.$vz.'="[^" ]+/', $line, $cumdist_tmp);
            //echo "status8=".$status."<br>";
            if($status==0)
            {
              continue;
            }    
            
            
            $status = preg_match('/'.$vi.'="[^" ]+/', $line, $io1_tmp);
            if($status==0)
            {
              continue;
            } 
            
            $status = preg_match('/'.$vj.'="[^" ]+/', $line, $io2_tmp);
            if($status==0)
            {
              continue;
            }   
            
            $status = preg_match('/'.$vk.'="[^" ]+/', $line, $io3_tmp);
            if($status==0)
            {
              continue;
            }  
            
            $status = preg_match('/'.$vl.'="[^" ]+/', $line, $io4_tmp);
            if($status==0)
            {
              continue;
            }  
            
            $status = preg_match('/'.$vm.'="[^" ]+/', $line, $io5_tmp);
            if($status==0)
            {
              continue;
            }  
            
            $status = preg_match('/'.$vn.'="[^" ]+/', $line, $io6_tmp);
            if($status==0)
            {
              continue;
            }  
            
            $status = preg_match('/'.$vo.'="[^" ]+/', $line, $io7_tmp);
            if($status==0)
            {
              continue;
            }  
			
			$status = preg_match('/'.$vp.'="[^" ]+/', $line, $io8_tmp);
            if($status==0)
            {
              continue;
            }             
          
            $tmp = explode("=",$datetime_tmp[0]);
            $datetime[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$vehiclename_tmp[0]);
            $vehiclename[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$lat_tmp[0]);
            $lat[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$lng_tmp[0]);
            $lng[$i] = preg_replace('/"/', '', $tmp[1]);
           
			/*get_report_location($lat[$i],$lng[$i],&$placename);
			$place_name_arr[$i]=$placename; */       
            
            $tmp = explode("=",$speed_tmp[0]);
            $speed[$i] = preg_replace('/"/', '', $tmp[1]);
            $speed[$i] = round($speed[$i],2);
            if($speed[$i]>200)
              $speed[$i] =0;
            
            $tmp = explode("=",$cumdist_tmp[0]);
            $cumdist[$i] = preg_replace('/"/', '', $tmp[1]);  

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
        } // IF FORMAT 1 CLOSED     
                      
        /////////////////////////////////////////////////////////////                            
      }   // WHILE CLOSED
   }
}  // FUNCTION READ TRACK CLOSED

	function read_track_xml_person($xml_path, &$vehicleserial, &$lat, &$lng, &$datetime, &$vehiclename, &$vehicletype, &$cumdist)
  { 
	global $va,$vb,$vc,$vd,$ve,$vg,$vh,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
	global $old_xml_date;
	new_xml_variables();
    $fexist =1;
    $fix_tmp = 1;
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
       // echo '<textarea>'.$line.'</textarea>';  
    
        /*if(strpos($line,''.$vc.'="1"'))     // RETURN FALSE IF NOT FOUND
        {
          $format = 1;
          $fix_tmp = 1;          
        }      
        else if(strpos($line,''.$vc.'="0"'))
        {
          $format = 1;
          $fix_tmp = 0;
        } */
                   
        if ((preg_match('/'.$vd.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match) ) && (preg_match('/'.$ve.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
        { 
          $lat_value = explode('=',$lat_match[0]);
          $lng_value = explode('=',$lng_match[0]);
          //echo "<br>lat=".$lat_value[1]." lng=".$lng_value[1];
          
          if( (strlen($lat_value[1])>5) && ($lat_value[1]!="-") && (strlen($lng_value[1])>5) && ($lng_value[1]!="-") )
          {
            $DataValid = 1;
          }
        }
           
		//if(($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($fix_tmp==1) && ($format == 1) && ($DataValid == 1))
        if(($line[0] == '<') && ($line[strlen($line)-2] == '>') &&($DataValid == 1))
		{
            //echo "<br>IN format1";             
            $status = preg_match('/'.$vv.'="[^" ]+/', $line, $vehicleserial_tmp);
            //echo "Status=".$status.'<BR>';
           // echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
            //echo "test2".'<BR>';
            $status = preg_match('/'.$vw.'="[^"]+/', $line, $vehiclename_tmp);
            //echo "status1=".$status."<br>";
            if($status==0)
            {
              continue;
            }
           // echo "test4".'<BR>';
            //$status = preg_match('/\d+-\d+-\d+ \d+:\d+:\d+/', $line, $datetime_tmp);
            $status = preg_match('/'.$vh.'="[^"]+/', $line, $datetime_tmp);
            //echo "status2=".$status."<br>";
            if($status==0)
            {
              continue;
            }
            //echo "test5".$datetime_tmp[0].'<BR>';
            $status = preg_match('/'.$vd.'="[^" ]+/', $line, $lat_tmp);
            //echo "status3=".$status."<br>";
            if($status==0)
            {
              continue;
            }
            //echo "test6".'<BR>';
            $status = preg_match('/'.$ve.'="[^" ]+/', $line, $lng_tmp);
            //echo "status4=".$status."<br>";
            if($status==0)
            {
              continue;
            } 
            $tmp = explode("=",$vehicleserial_tmp[0]);
            $vehicleserial[$i] = preg_replace('/"/', '', $tmp[1]);
           
            $status = preg_match('/'.$vz.'="[^" ]+/', $line, $cumdist_tmp);
           
			$tmp = explode("=",$cumdist_tmp[0]);
            $cumdist[$i] = preg_replace('/"/', '', $tmp[1]);
          
            $tmp = explode("=",$datetime_tmp[0]);
            $datetime[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$vehiclename_tmp[0]);
            $vehiclename[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$lat_tmp[0]);
            $lat[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$lng_tmp[0]);
            $lng[$i] = preg_replace('/"/', '', $tmp[1]);  
            $i++;                      
        } // IF FORMAT 1 CLOSED     
                      
        /////////////////////////////////////////////////////////////                            
      }   // WHILE CLOSED
   }
}  // FUNCTION READ TRACK CLOSED


///////////// FUNCTION READ TRACK XML CLOSED /////////

////////////////////// FUNCTION HALT  ////////////////////////////////////////

 // function read_halt_xml($xml_path, &$imei, &$vname, &$lat, &$lng, &$arr_time, &$dep_time, &$duration, &$place_name_arr)
  //function read_halt_xml($xml_path, &$imei, &$vname, &$lat, &$lng, &$arr_time, &$dep_time, &$duration)
  function read_halt_xml($xml_path, &$imei, &$vname, &$lat, &$lng, &$arr_time, &$dep_time, &$in_temperature, &$out_temperature, &$duration)	
  {
    //echo "<br>Read Halt";
    //echo "<br>Read Halt";
    //echo "<br>xml_path in read_xml=".$xml_path;    
    $fexist =1;    
    $xml = fopen($xml_path, "r") or $fexist = 0;
    
    $count = count(file($xml_path));
    //echo "<BR>COUNT======== $count lines in $xml";
    //$xml2 = '"'.$xml.'"';
    if($fexist)
    {
      $i=0;
    	
      while(!feof($xml))          // WHILE LINE != NULL
    	{
    		$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE   
        //echo '<br>line='.$line;  
        //echo "<br>line[0]=".$line[0]." ,".$line[strlen($line)-2]." ,".$DataValid." ,".$fix_tmp;          
        
        // FORMAT 2 OPENS
        if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') )
        {
            //echo "<br>IN format2";             							
            $status = preg_match('/imei="[^"]+/', $line, $imei_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/vname="[^"]+/', $line, $vname_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/lat="[^" ]+/', $line, $lat_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/lng="[^" ]+/', $line, $lng_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
                                    
            //echo "test2".'<BR>';
            $status = preg_match('/arr_time="[^"]+/', $line, $arr_time_tmp);
            if($status==0)
            {
              continue;
            }
            //echo "test4".'<BR>';
            $status = preg_match('/dep_time="[^"]+/', $line, $dep_time_tmp);
            if($status==0)
            {
              continue;
            }
			$status = preg_match('/in_temp="[^"]+/', $line, $in_temp_tmp);
            if($status==0)
            {
              continue;
            }
			$status = preg_match('/out_temp="[^"]+/', $line, $out_temp_tmp);
            if($status==0)
            {
              continue;
            }
            //echo "test5".$datetime_tmp[0].'<BR>';
            $status = preg_match('/duration="[^" ]+/', $line, $duration_tmp);
            if($status==0)
            {
              continue;
            }
            
            // Store Name with Value                  
            $tmp = explode("=",$imei_tmp[0]);
            $imei[$i] = preg_replace('/"/', '', $tmp[1]);            
            
            $tmp = explode("=",$vname_tmp[0]);
            $vname[$i] = preg_replace('/"/', '', $tmp[1]);
                        
            $tmp = explode("=",$lat_tmp[0]);
            $lat[$i] = preg_replace('/"/', '', $tmp[1]);
                         
            $tmp = explode("=",$lng_tmp[0]);
            $lng[$i] = preg_replace('/"/', '', $tmp[1]);                        
            
            $tmp = explode("=",$arr_time_tmp[0]);
            $arr_time[$i] = preg_replace('/"/', '', $tmp[1]);
            //$arr_time[$i] = $arr_time_tmp[0];
            
            $tmp = explode("=",$dep_time_tmp[0]);
            $dep_time[$i] = preg_replace('/"/', '', $tmp[1]);
			
			$tmp = explode("=",$in_temp_tmp[0]);
            $in_temperature[$i] = preg_replace('/"/', '', $tmp[1]);
			//echo "in_tempature=".$in_temperature[$i]."<br>";
			
			$tmp = explode("=",$out_temp_tmp[0]);
            $out_temperature[$i] = preg_replace('/"/', '', $tmp[1]);
			//echo "out_temperature=".$out_temperature[$i]."<br>";
            //$dep_time[$i] = $dep_time_tmp[0];

            $tmp = explode("=",$duration_tmp[0]);
            $duration[$i] = preg_replace('/"/', '', $tmp[1]);
			
			/*get_report_location($lat[$i],$lng[$i],&$placename);
			$place_name_arr[$i]=$placename;*/
            
            $i++;                      
        } //  // IF LINE 0                        
      }   // WHILE CLOSED
   }   // IF FEXIST
}  // FUNCTION HALT CLOSED


  function read_fuel_halt_xml($xml_path, &$vid, &$imei_xml, &$vname, &$lat, &$lng, &$arr_time, &$dep_time, &$duration, &$fuel_data, &$fuel_dist)
  {
    //echo "<br>Read Halt";
    //echo "<br>xml_path in read_xml=".$xml_path;    
    $fexist =1;    
    $xml = fopen($xml_path, "r") or $fexist = 0;
    
    $count = count(file($xml_path));
    //echo "<BR>COUNT======== $count lines in $xml";
    //$xml2 = '"'.$xml.'"';
    if($fexist)
    {
      $i=0;
    	
      while(!feof($xml))          // WHILE LINE != NULL
    	{
    		$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE   
        //echo '<br>line='.$line;  
        //echo "<br>line[0]=".$line[0]." ,".$line[strlen($line)-2]." ,".$DataValid." ,".$fix_tmp;          
        
        // FORMAT 2 OPENS
        if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') )
        {
            //echo "<br>IN format2";             							
            $status = preg_match('/imei="[^"]+/', $line, $imei_xml_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/vid="[^"]+/', $line, $vid_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }            
            
            $status = preg_match('/vname="[^"]+/', $line, $vname_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/lat="[^" ]+/', $line, $lat_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/lng="[^" ]+/', $line, $lng_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
                                    
            //echo "test2".'<BR>';
            $status = preg_match('/arr_time="[^"]+/', $line, $arr_time_tmp);
            if($status==0)
            {
              continue;
            }
            //echo "test4".'<BR>';
            $status = preg_match('/dep_time="[^"]+/', $line, $dep_time_tmp);
            if($status==0)
            {
              continue;
            }
            //echo "test5".$datetime_tmp[0].'<BR>';
            $status = preg_match('/duration="[^" ]+/', $line, $duration_tmp);
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/fuel_data="[^" ]+/', $line, $fuel_data_tmp);
            //echo "<br>st1";
            if($status==0)
            {
              continue;
            }  
            
            $status = preg_match('/fuel_dist="[^" ]+/', $line, $fuel_dist_tmp);
            //echo "<br>st2";
            if($status==0)
            {
              continue;
            }            
            
            // Store Name with Value                  
            $tmp = explode("=",$imei_xml_tmp[0]);
            $imei_xml[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$vid_tmp[0]);
            $vid[$i] = preg_replace('/"/', '', $tmp[1]);                                    
            
            $tmp = explode("=",$vname_tmp[0]);
            $vname[$i] = preg_replace('/"/', '', $tmp[1]);
                        
            $tmp = explode("=",$lat_tmp[0]);
            $lat[$i] = preg_replace('/"/', '', $tmp[1]);
                         
            $tmp = explode("=",$lng_tmp[0]);
            $lng[$i] = preg_replace('/"/', '', $tmp[1]);                        
            
            $tmp = explode("=",$arr_time_tmp[0]);
            $arr_time[$i] = preg_replace('/"/', '', $tmp[1]);
            //$arr_time[$i] = $arr_time_tmp[0];
            
            $tmp = explode("=",$dep_time_tmp[0]);
            $dep_time[$i] = preg_replace('/"/', '', $tmp[1]);
            //$dep_time[$i] = $dep_time_tmp[0];

            $tmp = explode("=",$duration_tmp[0]);
            $duration[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$fuel_data_tmp[0]);
            $fuel_data[$i] = preg_replace('/"/', '', $tmp[1]);            

            $tmp = explode("=",$fuel_dist_tmp[0]);
            $fuel_dist[$i] = preg_replace('/"/', '', $tmp[1]);            
            
            $i++;                      
        } //  // IF LINE 0                        
      }   // WHILE CLOSED
   }   // IF FEXIST
}  // FUNCTION FUEL HALT CLOSED



  function read_travel_xml($xml_path, &$imei_xml, &$vname_xml, &$time1, &$time2, &$lat_start, &$lng_start, &$lat_end, &$lng_end, &$distance_travelled, &$travel_time)
  {
    //echo "<br>Read Halt";
    //echo "<br>xml_path in read_xml=".$xml_path;    
    $fexist =1;    
    $xml = fopen($xml_path, "r") or $fexist = 0;
    
    $count = count(file($xml_path));
    //echo "<BR>COUNT======== $count lines in $xml";
    //$xml2 = '"'.$xml.'"';
    if($fexist)
    {
      $i=0;
    	
      while(!feof($xml))          // WHILE LINE != NULL
    	{
    		$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE   
        //echo '<br>line='.$line;  
        //echo "<br>line[0]=".$line[0]." ,".$line[strlen($line)-2]." ,".$DataValid." ,".$fix_tmp;          
        
        // FORMAT 2 OPENS
        if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') )
        {
            //echo "<br>IN format2";             							
            $status = preg_match('/imei="[^"]+/', $line, $imei_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/vname="[^"]+/', $line, $vname_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/time1="[^"]+/', $line, $time1_tmp);
            //echo "<br>Status1=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/time2="[^"]+/', $line, $time2_tmp);
            //echo "<br>Status2=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }            

            $status = preg_match('/lat_start="[^" ]+/', $line, $lat_start_tmp);
            //echo "<br>Status3=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/lng_start="[^" ]+/', $line, $lng_start_tmp);
            //echo "<br>Status4=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/lat_end="[^" ]+/', $line, $lat_end_tmp);
            //echo "<br>Status5=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/lng_end="[^" ]+/', $line, $lng_end_tmp);
            //echo "<br>Status6=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }            
                                              
            //echo "test2".'<BR>';
            $status = preg_match('/distance_travelled="[^"]+/', $line, $distance_travelled_tmp);
            //echo "<br>Status7=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
            //echo "test4".'<BR>';
            $status = preg_match('/travel_time="[^"]+/', $line, $travel_time_tmp);
            //echo "<br>Status8=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }          
            
            // Store Name with Value                  
            $tmp = explode("=",$imei_tmp[0]);
            $imei_xml[$i] = preg_replace('/"/', '', $tmp[1]);            
            
            $tmp = explode("=",$vname_tmp[0]);
            $vname_xml[$i] = preg_replace('/"/', '', $tmp[1]);
                        
            $tmp = explode("=",$time1_tmp[0]);
            $time1[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$time2_tmp[0]);
            $time2[$i] = preg_replace('/"/', '', $tmp[1]);                                

            $tmp = explode("=",$lat_start_tmp[0]);
            $lat_start[$i] = preg_replace('/"/', '', $tmp[1]);
                         
            $tmp = explode("=",$lng_start_tmp[0]);
            $lng_start[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$lat_end_tmp[0]);
            $lat_end[$i] = preg_replace('/"/', '', $tmp[1]);
                         
            $tmp = explode("=",$lng_end_tmp[0]);
            $lng_end[$i] = preg_replace('/"/', '', $tmp[1]);                                    
            
            $tmp = explode("=",$distance_travelled_tmp[0]);
            $distance_travelled[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$travel_time_tmp[0]);
            $travel_time[$i] = preg_replace('/"/', '', $tmp[1]);
           
            $i++;                      
        } //  // IF LINE 0                        
      }   // WHILE CLOSED
   }   // IF FEXIST
}  // FUNCTION HALT CLOSED


////////////////////// FUNCTION SPEED REPORT  ////////////////////////////////////////

  function read_speed_xml($xml_path, &$imei, &$vname, &$datefrom, &$dateto, &$avgspeed, &$runtime, &$maxspeed, &$distance)
  {
    //echo "<br>Read Halt";
    //echo "<br>xml_path in read_xml=".$xml_path;   
    $fexist =1;    
    $xml = fopen($xml_path, "r") or $fexist = 0;
    
    $count = count(file($xml_path));
    //echo "<BR>COUNT======== $count lines in $xml";
    //$xml2 = '"'.$xml.'"';
    if($fexist)
    {
      $i=0;
    	
      while(!feof($xml))          // WHILE LINE != NULL
    	{
    		$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE   
        //echo '<br>line='.$line;  
        //echo "<br>line[0]=".$line[0]." ,".$line[strlen($line)-2]." ,".$DataValid." ,".$fix_tmp;          
        
        // FORMAT 2 OPENS
        if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') )
        {
            //echo "<br>IN format2";             							
            $status = preg_match('/imei="[^"]+/', $line, $imei_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
                        
            $status = preg_match('/vname="[^"]+/', $line, $vname_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            } 
            
            $status = preg_match('/datefrom="[^"]+/', $line, $datefrom_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }                
            
            $status = preg_match('/dateto="[^"]+/', $line, $dateto_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }                
                                    
            //echo "test2".'<BR>';
            $status = preg_match('/avgspeed="[^" ]+/', $line, $avgspeed_tmp);
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/runtime="[^" ]+/', $line, $runtime_tmp);
            if($status==0)
            {
              continue;
            }                
                        
            //echo "test4".'<BR>';
            $status = preg_match('/maxspeed="[^" ]+/', $line, $maxspeed_tmp);
            if($status==0)
            {
              continue;
            }                
            
            $status = preg_match('/distance="[^" ]+/', $line, $distance_tmp);
            if($status==0)
            {
              continue;
            }                
            
            // Store Name with Value                  
            $tmp = explode("=",$imei_tmp[0]);
            $imei[$i] = preg_replace('/"/', '', $tmp[1]);            
            
            $tmp = explode("=",$vname_tmp[0]);
            $vname[$i] = preg_replace('/"/', '', $tmp[1]);
                        
            $tmp = explode("=",$datefrom_tmp[0]);
            $datefrom[$i] = preg_replace('/"/', '', $tmp[1]);
                         
            $tmp = explode("=",$dateto_tmp[0]);
            $dateto[$i] = preg_replace('/"/', '', $tmp[1]);    
            
            $tmp = explode("=",$avgspeed_tmp[0]);
            $avgspeed[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$runtime_tmp[0]);
            $runtime[$i] = preg_replace('/"/', '', $tmp[1]);                                                            

            $tmp = explode("=",$maxspeed_tmp[0]);
            $maxspeed[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$distance_tmp[0]);
            $distance[$i] = preg_replace('/"/', '', $tmp[1]);                                    
             
            $i++;                      
        } //  // IF LINE 0                        
      }   // WHILE CLOSED
   }   // IF FEXIST
}  // FUNCTION SPEED CLOSED


////////////////////// FUNCTION LOAD CELL REPORT  ////////////////////////////////////////
  function read_load_cell_xml($xml_path, &$imei, &$vname, &$datetime, &$load_status1, &$location, &$load, &$load_status2)
  {
    //echo "<br>Read Halt";
    //echo "<br>xml_path in read_xml=".$xml_path;   
    $fexist =1;    
    $xml = fopen($xml_path, "r") or $fexist = 0;
    
    $count = count(file($xml_path));
    //echo "<BR>COUNT======== $count lines in $xml";
    //$xml2 = '"'.$xml.'"';
    if($fexist)
    {
      $i=0;
    	
      while(!feof($xml))          // WHILE LINE != NULL
    	{
    		$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE   
        //echo '<br>line='.$line;  
        //echo "<br>line[0]=".$line[0]." ,".$line[strlen($line)-2]." ,".$DataValid." ,".$fix_tmp;          
        
        // FORMAT 2 OPENS
        if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') )
        {
            //echo "<br>IN format2";             							
            $status = preg_match('/imei="[^"]+/', $line, $imei_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
                        
            $status = preg_match('/vname="[^"]+/', $line, $vname_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/datetime="[^"]+/', $line, $datetime_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/load_status1="[^"]+/', $line, $load_status1_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/location="[^"]+/', $line, $location_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/load="[^"]+/', $line, $load_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/load_status2="[^"]+/', $line, $load_status2_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }                                     
            
            // Store Name with Value                  
            $tmp = explode("=",$imei_tmp[0]);
            $imei[$i] = preg_replace('/"/', '', $tmp[1]);            
            
            $tmp = explode("=",$vname_tmp[0]);
            $vname[$i] = preg_replace('/"/', '', $tmp[1]);
                        
            $tmp = explode("=",$datetime_tmp[0]);
            $datetime[$i] = preg_replace('/"/', '', $tmp[1]);
                         
            $tmp = explode("=",$load_status1_tmp[0]);
            $load_status1[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$location_tmp[0]);
            $location[$i] = preg_replace('/"/', '', $tmp[1]);            
            
            $tmp = explode("=",$load_tmp[0]);
            $load[$i] = preg_replace('/"/', '', $tmp[1]); 
            
            $tmp = explode("=",$load_status2_tmp[0]);
            $load_status2[$i] = preg_replace('/"/', '', $tmp[1]);                                                                      
             
            $i++;                      
        } //  // IF LINE 0                        
      }   // WHILE CLOSED
   }   // IF FEXIST
}  // FUNCTION LOAD CELL CLOSED


////////////////////// FUNCTION DISTANCE REPORT  ////////////////////////////////////////
  function read_distance_xml($xml_path, &$imei, &$vname, &$datefrom, &$dateto, &$distance)
  {
    //echo "<br>Read Halt";
    //echo "<br>xml_path in read_xml=".$xml_path;    
    $fexist =1;    
    $xml = fopen($xml_path, "r") or $fexist = 0;
    
    $count = count(file($xml_path));
    //echo "<BR>COUNT======== $count lines in $xml";
    //$xml2 = '"'.$xml.'"';
    if($fexist)
    {
      $i=0;
    	
      while(!feof($xml))          // WHILE LINE != NULL
    	{
    		$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE   
        //echo '<br>line='.$line;  
        //echo "<br>line[0]=".$line[0]." ,".$line[strlen($line)-2]." ,".$DataValid." ,".$fix_tmp;          
        
        // FORMAT 2 OPENS
        if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') )
        {
            //echo "<br>IN format2";             
            $status = preg_match('/imei="[^"]+/', $line, $imei_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }							
            
            $status = preg_match('/vname="[^"]+/', $line, $vname_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/datefrom="[^"]+/', $line, $datefrom_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/dateto="[^"]+/', $line, $dateto_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
                                    
            //echo "test5".$datetime_tmp[0].'<BR>';
            $status = preg_match('/distance="[^" ]+/', $line, $distance_tmp);
            if($status==0)
            {
              continue;
            }
            
            // Store Name with Value                  
            $tmp = explode("=",$imei_tmp[0]);
            $imei[$i] = preg_replace('/"/', '', $tmp[1]);                        
            
            $tmp = explode("=",$vname_tmp[0]);
            $vname[$i] = preg_replace('/"/', '', $tmp[1]);
                        
            $tmp = explode("=",$datefrom_tmp[0]);
            $datefrom[$i] = preg_replace('/"/', '', $tmp[1]);
                         
            $tmp = explode("=",$dateto_tmp[0]);
            $dateto[$i] = preg_replace('/"/', '', $tmp[1]);    
            
            $tmp = explode("=",$distance_tmp[0]);
            $distance[$i] = preg_replace('/"/', '', $tmp[1]);                        
            
            //echo "<br>datefrom=".$datefrom[$i]." dateto=".$dateto[$i];
            $i++;                      
        } //  // IF LINE 0                        
      }   // WHILE CLOSED
   }   // IF FEXIST
}  // FUNCTION DISTANCE CLOSED


////////////////////// FUNCTION TEMPERATURE REPORT  ////////////////////////////////////////
  function read_temperature_xml($xml_path, &$imei, &$vname, &$datefrom, &$dateto, &$temperature)
  {
    //echo "<br>Read Halt";
    //echo "<br>xml_path in read_xml=".$xml_path;    
    $fexist =1;    
    $xml = fopen($xml_path, "r") or $fexist = 0;
    
    $count = count(file($xml_path));
    //echo "<BR>COUNT======== $count lines in $xml";
    //$xml2 = '"'.$xml.'"';
    if($fexist)
    {
      $i=0;
    	
      while(!feof($xml))          // WHILE LINE != NULL
    	{
    		$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE   
        //echo '<br>line='.$line;  
        //echo "<br>line[0]=".$line[0]." ,".$line[strlen($line)-2]." ,".$DataValid." ,".$fix_tmp;          
        
        // FORMAT 2 OPENS
        if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') )
        {
            //echo "<br>IN format2";             
            $status = preg_match('/imei="[^"]+/', $line, $imei_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }							
            
            $status = preg_match('/vname="[^"]+/', $line, $vname_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/datefrom="[^"]+/', $line, $datefrom_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/dateto="[^"]+/', $line, $dateto_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
                                    
            //echo "test5".$datetime_tmp[0].'<BR>';
            $status = preg_match('/temperature="[^" ]+/', $line, $temperature_tmp);
            if($status==0)
            {
              continue;
            }
            
            // Store Name with Value                  
            $tmp = explode("=",$imei_tmp[0]);
            $imei[$i] = preg_replace('/"/', '', $tmp[1]);                        
            
            $tmp = explode("=",$vname_tmp[0]);
            $vname[$i] = preg_replace('/"/', '', $tmp[1]);
                        
            $tmp = explode("=",$datefrom_tmp[0]);
            $datefrom[$i] = preg_replace('/"/', '', $tmp[1]);
                         
            $tmp = explode("=",$dateto_tmp[0]);
            $dateto[$i] = preg_replace('/"/', '', $tmp[1]);    
            
            $tmp = explode("=",$temperature_tmp[0]);
            $temperature[$i] = preg_replace('/"/', '', $tmp[1]);                        
            
            //echo "<br>datefrom=".$datefrom[$i]." dateto=".$dateto[$i];
            $i++;                      
        } //  // IF LINE 0                        
      }   // WHILE CLOSED
   }   // IF FEXIST
}  // FUNCTION TEMPERATURE CLOSED


////////////////////// FUNCTION PERFORMANCE REPORT  ////////////////////////////////////////
  function read_performance_xml($xml_path, &$imei, &$vname, &$datefrom, &$dateto, &$avg_speed, &$runtime, &$distance)
  {
    //echo "<br>Read Halt";
    //echo "<br>xml_path in read_xml=".$xml_path;    
    $fexist =1;    
    $xml = fopen($xml_path, "r") or $fexist = 0;
    
    $count = count(file($xml_path));
    //echo "<BR>COUNT======== $count lines in $xml";
    //$xml2 = '"'.$xml.'"';
    if($fexist)
    {
      $i=0;
    	
      while(!feof($xml))          // WHILE LINE != NULL
    	{
    		$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE   
        //echo '<br>line='.$line;  
        //echo "<br>line[0]=".$line[0]." ,".$line[strlen($line)-2]." ,".$DataValid." ,".$fix_tmp;          
        
        // FORMAT 2 OPENS
        if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') )
        {
            //echo "<br>IN format2";             
            $status = preg_match('/imei="[^"]+/', $line, $imei_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }							
            
            $status = preg_match('/vname="[^"]+/', $line, $vname_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/datefrom="[^"]+/', $line, $datefrom_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/dateto="[^"]+/', $line, $dateto_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
                                    
            //echo "test5".$datetime_tmp[0].'<BR>';            
            $status = preg_match('/avg_speed="[^"]+/', $line, $avg_speed_tmp);
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/runtime="[^"]+/', $line, $runtime_tmp);
            if($status==0)
            {
              continue;
            } 
            
            $status = preg_match('/distance="[^"]+/', $line, $distance_tmp);
            if($status==0)
            {
              continue;
            }                       
            
            // Store Name with Value                  
            $tmp = explode("=",$imei_tmp[0]);
            $imei[$i] = preg_replace('/"/', '', $tmp[1]);                        
            
            $tmp = explode("=",$vname_tmp[0]);
            $vname[$i] = preg_replace('/"/', '', $tmp[1]);
                        
            $tmp = explode("=",$datefrom_tmp[0]);
            $datefrom[$i] = preg_replace('/"/', '', $tmp[1]);
                         
            $tmp = explode("=",$dateto_tmp[0]);
            $dateto[$i] = preg_replace('/"/', '', $tmp[1]);    
                        
            $tmp = explode("=",$avg_speed_tmp[0]);
            $avg_speed[$i] = preg_replace('/"/', '', $tmp[1]);
                        
            $tmp = explode("=",$runtime_tmp[0]);
            $runtime[$i] = preg_replace('/"/', '', $tmp[1]);
                        
            $tmp = explode("=",$distance_tmp[0]);
            $distance[$i] = preg_replace('/"/', '', $tmp[1]);
            //echo "<br>dist=".$distance;                                                
            //echo "<br>datefrom=".$datefrom[$i]." dateto=".$dateto[$i];
            $i++;                      
        } //  // IF LINE 0                        
      }   // WHILE CLOSED
   }   // IF FEXIST
}  // FUNCTION PERFORMANCE CLOSED


////////////////////// FUNCTION MONTHLY DISTANCE  ////////////////////////////////////////	
  function read_monthly_dist_xml($xml_path, &$imei, &$vname, &$mdate, &$daily_dist)
  {
    //echo "<br>read_monthly_dist_xml";
    //echo "<br>xml_path in read_xml=".$xml_path;    
    $fexist =1;    
    $xml = fopen($xml_path, "r") or $fexist = 0;
    
    $count = count(file($xml_path));
    //echo "<BR>COUNT======== $count lines in $xml";
    //$xml2 = '"'.$xml.'"';
    if($fexist)
    {
      $i=0;
    	
      while(!feof($xml))          // WHILE LINE != NULL
    	{
    		$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE   
        //echo '<br>line='.$line;  
        //echo "<br>line[0]=".$line[0]." ,".$line[strlen($line)-2]." ,".$DataValid." ,".$fix_tmp;          
        
        // FORMAT 2 OPENS
        if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') )
        {
            //echo "<br>IN format2";             
            $status = preg_match('/imei="[^"]+/', $line, $imei_tmp);
            //echo "Status1=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }							
            
            $status = preg_match('/vname="[^"]+/', $line, $vname_tmp);
            //echo "Status2=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/date="[^"]+/', $line, $mdate_tmp);
            //echo "Status3=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/daily_dist="[^" ]+/', $line, $daily_dist_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
                                    
            // Store Name with Value                  
            $tmp = explode("=",$imei_tmp[0]);
            $imei[$i] = preg_replace('/"/', '', $tmp[1]);            
            
            $tmp = explode("=",$vname_tmp[0]);
            $vname[$i] = preg_replace('/"/', '', $tmp[1]);
                        
            $tmp = explode("=",$mdate_tmp[0]);
            $mdate[$i] = preg_replace('/"/', '', $tmp[1]);
                         
            $tmp = explode("=",$daily_dist_tmp[0]);
            $daily_dist[$i] = preg_replace('/"/', '', $tmp[1]); 
            //echo "<br>dailydist=".$daily_dist[$i];                                               
            
            $i++;                      
        } //  // IF LINE 0                        
      }   // WHILE CLOSED
   }   // IF FEXIST
}  // FUNCTION MONTHLY DISTANCE CLOSED

//##### VEHICLE REVERSE VIOLATION
  function read_reverse_violation_data($xml_path, &$imei, &$vname, &$violation_time1, &$violation_time2, &$violation_distance)
  {
    //echo "<br>read_monthly_dist_xml";
    //echo "<br>xml_path in read_xml=".$xml_path;    
    $fexist =1;    
    $xml = fopen($xml_path, "r") or $fexist = 0;
    
    $count = count(file($xml_path));
    //echo "<BR>COUNT======== $count lines in $xml";
    //$xml2 = '"'.$xml.'"';
    if($fexist)
    {
      $i=0;
    	
		while(!feof($xml))          // WHILE LINE != NULL
		{
    		$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE   
			//echo '<br>line='.$line;  
			//echo "<br>line[0]=".$line[0]." ,".$line[strlen($line)-2]." ,".$DataValid." ,".$fix_tmp;          
        
			// FORMAT 2 OPENS
			if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') )
			{
				//echo "<br>IN format2";             
				$status = preg_match('/imei="[^"]+/', $line, $imei_tmp);
				//echo "Status1=".$status.'<BR>';
				if($status==0)
				{
				  continue;
				}							
            
				$status = preg_match('/vname="[^"]+/', $line, $vname_tmp);
				//echo "Status2=".$status.'<BR>';
				//echo "test1".'<BR>';
				if($status==0)
				{
				  continue;
				}
				
				$status = preg_match('/violation_time1="[^"]+/', $line, $violation_time1_tmp);
				//echo "Status3=".$status.'<BR>';
				//echo "test1".'<BR>';
				if($status==0)
				{
				  continue;
				}
            
				$status = preg_match('/violation_time2="[^"]+/', $line, $violation_time2_tmp);
				//echo "Status=".$status.'<BR>';
				//echo "test1".'<BR>';
				if($status==0)
				{
				  continue;
				}
				
				$status = preg_match('/violation_distance="[^" ]+/', $line, $violation_distance_tmp);
				//echo "Status=".$status.'<BR>';
				//echo "test1".'<BR>';
				if($status==0)
				{
				  continue;
				}				
                                    
				// Store Name with Value                  
				$tmp = explode("=",$imei_tmp[0]);
				$imei[$i] = preg_replace('/"/', '', $tmp[1]);            
				
				$tmp = explode("=",$vname_tmp[0]);
				$vname[$i] = preg_replace('/"/', '', $tmp[1]);
							
				$tmp = explode("=",$violation_time1_tmp[0]);
				$violation_time1[$i] = preg_replace('/"/', '', $tmp[1]);
							 
				$tmp = explode("=",$violation_time2_tmp[0]);
				$violation_time2[$i] = preg_replace('/"/', '', $tmp[1]);

				$tmp = explode("=",$violation_distance_tmp[0]);
				$violation_distance[$i] = preg_replace('/"/', '', $tmp[1]); 
				
				//echo "<br>dailydist=".$daily_dist[$i];                                               
            
				$i++;                      
			} //  // IF LINE 0                        
		}   // WHILE CLOSED
	}   // IF FEXIST
}  // FUNCTION VEHICLE REVERSE VIOLATION CLOSED
////////////////////// FUNCTION FUEL REPORT  ////////////////////////////////////////
  function read_fuel_xml($xml_path, &$imei, &$vname, &$datetime, &$fuel_level)
  {
    //echo "<br>Read Fuel";
    //echo "<br>xml_path in read_xml=".$xml_path;
    
    $fexist =1;    
    $xml = fopen($xml_path, "r") or $fexist = 0;
    
    $count = count(file($xml_path));
    //echo "<BR>COUNT======== $count lines in $xml";
    //$xml2 = '"'.$xml.'"';
    if($fexist)
    {
      $i=0;   	
      while(!feof($xml))          // WHILE LINE != NULL
    	{
    		$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE   
        //echo '<br>line='.$line;  
        //echo "<br>line[0]=".$line[0]." ,".$line[strlen($line)-2]." ,".$DataValid." ,".$fix_tmp;          
        
        // FORMAT 2 OPENS
        if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') )
        {
            //echo "<br>IN format2";             							
            $status = preg_match('/imei="[^"]+/', $line, $imei_tmp);
            //echo "Status1=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
                        
            $status = preg_match('/vname="[^"]+/', $line, $vname_tmp);
            //echo "Status2=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/datetime="[^"]+/', $line, $datetime_tmp);
            //echo "Status3=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
                                    
            //echo "test5".$datetime_tmp[0].'<BR>';
            /*$status = preg_match('/fuel_litres="[^" ]+/', $line, $fuel_litres_tmp);
            if($status==0)
            {
              continue;
            } */
            
            //echo "test5".$datetime_tmp[0].'<BR>';
            $status = preg_match('/fuel_level="[^" ]+/', $line, $fuel_level_tmp);
            //echo "Status4=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }            
            
            // Store Name with Value                  
            $tmp = explode("=",$imei_tmp[0]);
            $imei[$i] = preg_replace('/"/', '', $tmp[1]); 
                        
            $tmp = explode("=",$vname_tmp[0]);
            $vname[$i] = preg_replace('/"/', '', $tmp[1]);
                        
            $tmp = explode("=",$datetime_tmp[0]);
            $datetime[$i] = preg_replace('/"/', '', $tmp[1]);
                         
            //$tmp = explode("=",$fuel_litres_tmp[0]);
            //$fuel_litres[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$fuel_level_tmp[0]);
            $fuel_level[$i] = preg_replace('/"/', '', $tmp[1]); 
            
            //echo "<br>fuel_level=".$fuel_level[$i];                                                           
            
            $i++;                      
        } //  // IF LINE 0                        
      }   // WHILE CLOSED
   }   // IF FEXIST
}  // FUNCTION FUEL CLOSED

////////////////////// FUNCTION AREA VIOLATION  ////////////////////////////////////////
  function read_area_violation_xml($xml_path, &$imei, &$vname, &$lat, &$lng, &$datefrom, &$dateto, &$duration)
  {
    //echo "<br>Read Halt";
    //echo "<br>xml_path in read_xml=".$xml_path;
    
    $fexist =1;    
    $xml = fopen($xml_path, "r") or $fexist = 0;
    
    $count = count(file($xml_path));
    //echo "<BR>COUNT======== $count lines in $xml";
    //$xml2 = '"'.$xml.'"';
    if($fexist)
    {
      $i=0;
    	
      while(!feof($xml))          // WHILE LINE != NULL
    	{
    		$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE   
        //echo '<br>line='.$line;  
        //echo "<br>line[0]=".$line[0]." ,".$line[strlen($line)-2]." ,".$DataValid." ,".$fix_tmp;          
        
        // FORMAT 2 OPENS
        if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') )
        {
            //echo "<br>IN format2";             
            $status = preg_match('/imei="[^"]+/', $line, $imei_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/vname="[^"]+/', $line, $vname_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/lat="[^" ]+/', $line, $lat_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/lng="[^" ]+/', $line, $lng_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
                                    
            //echo "test2".'<BR>';
            $status = preg_match('/datefrom="[^"]+/', $line, $datefrom_tmp);
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/dateto="[^"]+/', $line, $dateto_tmp);
            if($status==0)
            {
              continue;
            }            

            //echo "test5".$datetime_tmp[0].'<BR>';
            $status = preg_match('/duration="[^" ]+/', $line, $duration_tmp);
            if($status==0)
            {
              continue;
            }
            
            // Store Name with Value                  
            $tmp = explode("=",$imei_tmp[0]);
            $imei[$i] = preg_replace('/"/', '', $tmp[1]);            
            
            $tmp = explode("=",$vname_tmp[0]);
            $vname[$i] = preg_replace('/"/', '', $tmp[1]);
                        
            $tmp = explode("=",$lat_tmp[0]);
            $lat[$i] = preg_replace('/"/', '', $tmp[1]);
                         
            $tmp = explode("=",$lng_tmp[0]);
            $lng[$i] = preg_replace('/"/', '', $tmp[1]);                        

            $tmp = explode("=",$datefrom_tmp[0]);
            $datefrom[$i] = preg_replace('/"/', '', $tmp[1]);                        

            $tmp = explode("=",$dateto_tmp[0]);
            $dateto[$i] = preg_replace('/"/', '', $tmp[1]);                                    
            
            $tmp = explode("=",$duration_tmp[0]);
            $duration[$i] = preg_replace('/"/', '', $tmp[1]);                                                           
            
            $i++;                      
        } //  // IF LINE 0                        
      }   // WHILE CLOSED
   }   // IF FEXIST
}  // FUNCTION AREA VIOLATION CLOSED



////////////////////// FUNCTION STATION HALT  ////////////////////////////////////////
  function read_station_halt_xml($xml_path, &$imei, &$vname, &$enter_time, &$leave_time, &$station, &$google_loc, &$duration)
  {
    //echo "<br>Read Halt";
    //echo "<br>xml_path in read_xml=".$xml_path;    
    $fexist =1;    
    $xml = fopen($xml_path, "r") or $fexist = 0;
    
    $count = count(file($xml_path));
    //echo "<BR>COUNT======== $count lines in $xml";
    //$xml2 = '"'.$xml.'"';
    if($fexist)
    {
      $i=0;
    	
      while(!feof($xml))          // WHILE LINE != NULL
    	{
    		$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE   
        //echo '<br>line='.$line;  
        //echo "<br>line[0]=".$line[0]." ,".$line[strlen($line)-2]." ,".$DataValid." ,".$fix_tmp;          
        
        // FORMAT 2 OPENS
        if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') )
        {
            //echo "<br>IN format2";             
            $status = preg_match('/imei="[^"]+/', $line, $imei_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/vname="[^"]+/', $line, $vname_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
            
            /*$status = preg_match('/lat="[^" ]+/', $line, $lat_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/lng="[^" ]+/', $line, $lng_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }*/
                                    
            //echo "test2".'<BR>';
            $status = preg_match('/enter_time="[^"]+/', $line, $enter_time_tmp);
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/leave_time="[^"]+/', $line, $leave_time_tmp);
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/station="[^"]+/', $line, $station_tmp);
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/google_loc="[^"]+/', $line, $google_loc_tmp);
            if($status==0)
            {
              continue;
            }                                        

            //echo "test5".$datetime_tmp[0].'<BR>';
            $status = preg_match('/duration="[^" ]+/', $line, $duration_tmp);
            if($status==0)
            {
              continue;
            }
            
            // Store Name with Value                  
            $tmp = explode("=",$imei_tmp[0]);
            $imei[$i] = preg_replace('/"/', '', $tmp[1]);            
            
            $tmp = explode("=",$vname_tmp[0]);
            $vname[$i] = preg_replace('/"/', '', $tmp[1]);
                        
            /*$tmp = explode("=",$lat_tmp[0]);
            $lat[$i] = preg_replace('/"/', '', $tmp[1]);
                         
            $tmp = explode("=",$lng_tmp[0]);
            $lng[$i] = preg_replace('/"/', '', $tmp[1]);*/                        

            $tmp = explode("=",$enter_time_tmp[0]);
            $enter_time[$i] = preg_replace('/"/', '', $tmp[1]);                        

            $tmp = explode("=",$leave_time_tmp[0]);
            $leave_time[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$station_tmp[0]);
            $station[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$google_loc_tmp[0]);
            $google_loc[$i] = preg_replace('/"/', '', $tmp[1]);                                                                                                            
            
            $tmp = explode("=",$duration_tmp[0]);
            $duration[$i] = preg_replace('/"/', '', $tmp[1]);                                                           
            
            $i++;                      
        } //  // IF LINE 0                        
      }   // WHILE CLOSED
   }   // IF FEXIST
}  // FUNCTION STATION HALT CLOSED


////////////////////// FUNCTION SPEED VIOLATION  ////////////////////////////////////////
  function read_speed_violation_xml($xml_path, &$imei, &$vname, &$time1, &$time2, &$violated_time)
  {
    //echo "<br>Read Halt";
    //echo "<br>xml_path in read_xml=".$xml_path;    
    $fexist =1;    
    $xml = fopen($xml_path, "r") or $fexist = 0;
    
    $count = count(file($xml_path));
    //echo "<BR>COUNT======== $count lines in $xml";
    //$xml2 = '"'.$xml.'"';
    if($fexist)
    {
      $i=0;
    	
      while(!feof($xml))          // WHILE LINE != NULL
    	{
    		$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE   
        //echo '<br>line='.$line;  
        //echo "<br>line[0]=".$line[0]." ,".$line[strlen($line)-2]." ,".$DataValid." ,".$fix_tmp;          
        
        // FORMAT 2 OPENS
        if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') )
        {
            //echo "<br>IN format2";             
            $status = preg_match('/imei="[^"]+/', $line, $imei_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
                        
            $status = preg_match('/vname="[^"]+/', $line, $vname_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
            
            //$status = preg_match('/\d+-\d+-\d+ \d+:\d+:\d+/', $line, $datetime_tmp);
            $status = preg_match('/time1="[^"]+/', $line, $time1_tmp);
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/time2="[^"]+/', $line, $time2_tmp);
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/violated_time="[^"]+/', $line, $violated_time_tmp);
            if($status==0)
            {
              continue;
            }                           
                        
            // Store Name with Value                  
            $tmp = explode("=",$imei_tmp[0]);
            $imei[$i] = preg_replace('/"/', '', $tmp[1]);

            $tmp = explode("=",$vname_tmp[0]);
            $vname[$i] = preg_replace('/"/', '', $tmp[1]);
                        
            $time1_tmp = explode("=",$time1_tmp[0]);
            $time1[$i] = preg_replace('/"/', '', $time1_tmp[1]);
            
            $time2_tmp = explode("=",$time2_tmp[0]);
            $time2[$i] = preg_replace('/"/', '', $time2_tmp[1]);            	                                      
            
            $tmp = explode("=",$violated_time_tmp[0]);
            $violated_time[$i] = preg_replace('/"/', '', $tmp[1]);                                                                              
            
            $i++;                      
        } //  // IF LINE 0                        
      }   // WHILE CLOSED
   }   // IF FEXIST
}  // FUNCTION SPEED VIOLATION CLOSED



////////////////////// FUNCTION FUEL REPORT  ////////////////////////////////////////
  function read_engine_runhr_xml($xml_path, &$imei, &$vname, &$datefrom, &$dateto, &$engine_runhr)
  {
    //echo "<br>Read Halt";
    //echo "<br>xml_path in read_xml=".$xml_path;
    
    $fexist =1;    
    $xml = fopen($xml_path, "r") or $fexist = 0;
    
    $count = count(file($xml_path));
    //echo "<BR>COUNT======== $count lines in $xml";
    //$xml2 = '"'.$xml.'"';
    if($fexist)
    {
      $i=0;   	
      while(!feof($xml))          // WHILE LINE != NULL
    	{
    		$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE   
        //echo '<br>line='.$line;  
        //echo "<br>line[0]=".$line[0]." ,".$line[strlen($line)-2]." ,".$DataValid." ,".$fix_tmp;          
        
        // FORMAT 2 OPENS
        if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') )
        {
            //echo "<br>IN format2";             							
            $status = preg_match('/imei="[^"]+/', $line, $imei_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
                        
            $status = preg_match('/vname="[^"]+/', $line, $vname_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/datefrom="[^"]+/', $line, $datefrom_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
                                    
            //echo "test5".$datetime_tmp[0].'<BR>';
            $status = preg_match('/dateto="[^"]+/', $line, $dateto_tmp);
            if($status==0)
            {
              continue;
            }
            
            //echo "test5".$datetime_tmp[0].'<BR>';
            $status = preg_match('/engine_runhr="[^" ]+/', $line, $engine_runhr_tmp);
            if($status==0)
            {
              continue;
            }            
            
            // Store Name with Value                  
            $tmp = explode("=",$imei_tmp[0]);
            $imei[$i] = preg_replace('/"/', '', $tmp[1]); 
                        
            $tmp = explode("=",$vname_tmp[0]);
            $vname[$i] = preg_replace('/"/', '', $tmp[1]);
                        
            $tmp = explode("=",$datefrom_tmp[0]);
            $datefrom[$i] = preg_replace('/"/', '', $tmp[1]);
                         
            $tmp = explode("=",$dateto_tmp[0]);
            $dateto[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$engine_runhr_tmp[0]);
            $engine_runhr[$i] = preg_replace('/"/', '', $tmp[1]);                                                            
            
            $i++;                      
        } //  // IF LINE 0                        
      }   // WHILE CLOSED
   }   // IF FEXIST
}  // FUNCTION ENGINE DURATION CLOSED

  function read_ac_runhr_xml($xml_path, &$imei, &$vname, &$datefrom, &$dateto, &$ac_runhr)
  {
    //echo "<br>Read Halt";
    //echo "<br>xml_path in read_xml=".$xml_path;
    
    $fexist =1;    
    $xml = fopen($xml_path, "r") or $fexist = 0;
    
    $count = count(file($xml_path));
    //echo "<BR>COUNT======== $count lines in $xml";
    //$xml2 = '"'.$xml.'"';
    if($fexist)
    {
      $i=0;   	
      while(!feof($xml))          // WHILE LINE != NULL
    	{
    		$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE   
        //echo '<br>line='.$line;  
        //echo "<br>line[0]=".$line[0]." ,".$line[strlen($line)-2]." ,".$DataValid." ,".$fix_tmp;          
        
        // FORMAT 2 OPENS
        if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') )
        {
            //echo "<br>IN format2";             							
            $status = preg_match('/imei="[^"]+/', $line, $imei_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
                        
            $status = preg_match('/vname="[^"]+/', $line, $vname_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/datefrom="[^"]+/', $line, $datefrom_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
                                    
            //echo "test5".$datetime_tmp[0].'<BR>';
            $status = preg_match('/dateto="[^"]+/', $line, $dateto_tmp);
            if($status==0)
            {
              continue;
            }
            
            //echo "test5".$datetime_tmp[0].'<BR>';
            $status = preg_match('/ac_runhr="[^" ]+/', $line, $ac_runhr_tmp);
            if($status==0)
            {
              continue;
            }            
            
            // Store Name with Value                  
            $tmp = explode("=",$imei_tmp[0]);
            $imei[$i] = preg_replace('/"/', '', $tmp[1]); 
                        
            $tmp = explode("=",$vname_tmp[0]);
            $vname[$i] = preg_replace('/"/', '', $tmp[1]);
                        
            $tmp = explode("=",$datefrom_tmp[0]);
            $datefrom[$i] = preg_replace('/"/', '', $tmp[1]);
                         
            $tmp = explode("=",$dateto_tmp[0]);
            $dateto[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$ac_runhr_tmp[0]);
            $ac_runhr[$i] = preg_replace('/"/', '', $tmp[1]);                                                            
            
            $i++;                      
        } //  // IF LINE 0                        
      }   // WHILE CLOSED
   }   // IF FEXIST
}  // FUNCTION ENGINE DURATION CLOSED

//DOOR OPEN
  function read_dooropen_xml($xml_path, &$imei, &$vname, &$latArr, &$lngArr, &$datefrom, &$dateto, &$door_open,$type_str)
  {
    //echo "<br>Read Halt";
    //echo "<br>xml_path in read_xml=".$xml_path;
    
    $fexist =1;    
    $xml = fopen($xml_path, "r") or $fexist = 0;
    
    $count = count(file($xml_path));
    //echo "<BR>COUNT======== $count lines in $xml";
    //$xml2 = '"'.$xml.'"';
    if($fexist)
    {
      $i=0;   	
      while(!feof($xml))          // WHILE LINE != NULL
    	{
    		$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE   
        //echo '<br>line='.$line;  
        //echo "<br>line[0]=".$line[0]." ,".$line[strlen($line)-2]." ,".$DataValid." ,".$fix_tmp;          
        
        // FORMAT 2 OPENS
        if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') )
        {
            //echo "<br>IN format2";
			 $status = preg_match('/door_open_type="[^"]+/', $line, $door_open_type_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
			
            $status = preg_match('/imei="[^"]+/', $line, $imei_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
                        
            $status = preg_match('/vname="[^"]+/', $line, $vname_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
			
			$status = preg_match('/lat="[^"]+/', $line, $lat_tmp);
           /* //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }*/
			
			$status = preg_match('/lng="[^"]+/', $line, $lng_tmp);
            /*//echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }*/
            
            $status = preg_match('/datefrom="[^"]+/', $line, $datefrom_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
                                    
            //echo "test5".$datetime_tmp[0].'<BR>';
            $status = preg_match('/dateto="[^"]+/', $line, $dateto_tmp);
            if($status==0)
            {
              continue;
            }
            
            //echo "test5".$datetime_tmp[0].'<BR>';
            $status = preg_match('/door_open="[^" ]+/', $line, $door_open_tmp);
            if($status==0)
            {
              continue;
            }            
            
            // Store Name with Value   
			$tmp = explode("=",$door_open_type_tmp[0]);
            $type[$i] = preg_replace('/"/', '', $tmp[1]);
			//echo "type1=".$type[$i]." type_str=".$type_str."<br>";
			if($type[$i]==$type_str)
			{			
				$tmp = explode("=",$imei_tmp[0]);
				$imei[$i] = preg_replace('/"/', '', $tmp[1]); 
							
				$tmp = explode("=",$vname_tmp[0]);
				$vname[$i] = preg_replace('/"/', '', $tmp[1]);
				
				$tmp = explode("=",$lat_tmp[0]);
				$latArr[$i] = preg_replace('/"/', '', $tmp[1]);
				
				$tmp = explode("=",$lng_tmp[0]);
				$lngArr[$i] = preg_replace('/"/', '', $tmp[1]);
							
				$tmp = explode("=",$datefrom_tmp[0]);
				$datefrom[$i] = preg_replace('/"/', '', $tmp[1]);
							 
				$tmp = explode("=",$dateto_tmp[0]);
				$dateto[$i] = preg_replace('/"/', '', $tmp[1]);
				
				$tmp = explode("=",$door_open_tmp[0]);
				$door_open[$i] = preg_replace('/"/', '', $tmp[1]);
				$i++; 
			}			
                               
        } //  // IF LINE 0                        
      }   // WHILE CLOSED
   }   // IF FEXIST
}  // FUNCTION ENGINE DURATION CLOSED

//FUEL LEAD
  function read_fuel_lead_xml($xml_path, &$imei, &$vname, &$datefrom, &$dateto, &$fuel_lead)
  {
    //echo "<br>Read Halt";
    //echo "<br>xml_path in read_xml=".$xml_path;
    
    $fexist =1;    
    $xml = fopen($xml_path, "r") or $fexist = 0;
    
    $count = count(file($xml_path));
    //echo "<BR>COUNT======== $count lines in $xml";
    //$xml2 = '"'.$xml.'"';
    if($fexist)
    {
      $i=0;   	
      while(!feof($xml))          // WHILE LINE != NULL
    	{
    		$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE   
        //echo '<br>line='.$line;  
        //echo "<br>line[0]=".$line[0]." ,".$line[strlen($line)-2]." ,".$DataValid." ,".$fix_tmp;          
        
        // FORMAT 2 OPENS
        if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') )
        {
            //echo "<br>IN format2";             							
            $status = preg_match('/imei="[^"]+/', $line, $imei_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
                        
            $status = preg_match('/vname="[^"]+/', $line, $vname_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/datefrom="[^"]+/', $line, $datefrom_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
                                    
            //echo "test5".$datetime_tmp[0].'<BR>';
            $status = preg_match('/dateto="[^"]+/', $line, $dateto_tmp);
            if($status==0)
            {
              continue;
            }
            
            //echo "test5".$datetime_tmp[0].'<BR>';
            $status = preg_match('/fuel_lead="[^" ]+/', $line, $fuel_lead_tmp);
            if($status==0)
            {
              continue;
            }            
            
            // Store Name with Value                  
            $tmp = explode("=",$imei_tmp[0]);
            $imei[$i] = preg_replace('/"/', '', $tmp[1]); 
                        
            $tmp = explode("=",$vname_tmp[0]);
            $vname[$i] = preg_replace('/"/', '', $tmp[1]);
                        
            $tmp = explode("=",$datefrom_tmp[0]);
            $datefrom[$i] = preg_replace('/"/', '', $tmp[1]);
                         
            $tmp = explode("=",$dateto_tmp[0]);
            $dateto[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$fuel_lead_tmp[0]);
            $fuel_lead[$i] = preg_replace('/"/', '', $tmp[1]);                                                            
            
            $i++;                      
        } //  // IF LINE 0                        
      }   // WHILE CLOSED
   }   // IF FEXIST
}  // FUNCTION ENGINE DURATION CLOSED

///////////////////// FUNCTION SUMMARY REPORT  ////////////////////////////////////////
  function read_summary_report_xml($xml_path, &$imei, &$vname, &$startdate, &$enddate, &$startlat, &$startlng, &$endlat, &$endlng, &$total_dist, &$journey_time, &$halt_str, &$track_str) 
  {
    //echo "<br>Read Halt";
    //echo "<br>xml_path in read_xml=".$xml_path;
    
    $fexist =1;    
    $xml = fopen($xml_path, "r") or $fexist = 0;
    
    $count = count(file($xml_path));
    //echo "<BR>COUNT======== $count lines in $xml";
    //$xml2 = '"'.$xml.'"';
    if($fexist)
    {
      $i=0;   	
      while(!feof($xml))          // WHILE LINE != NULL
    	{
    		$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE   
        //echo '<br>line='.$line;  
        //echo "<br>line[0]=".$line[0]." ,".$line[strlen($line)-2]." ,".$DataValid." ,".$fix_tmp;          
        
        // FORMAT 2 OPENS
        if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') )
        {
            //echo "<br>IN format2";             							
            $status = preg_match('/imei="[^"]+/', $line, $imei_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
                        
            $status = preg_match('/vname="[^"]+/', $line, $vname_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/startdate="[^"]+/', $line, $startdate_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
                                    
            //echo "test5".$datetime_tmp[0].'<BR>';
            $status = preg_match('/enddate="[^"]+/', $line, $enddate_tmp);
            if($status==0)
            {
              continue;
            }
            
            //echo "test5".$datetime_tmp[0].'<BR>';
            $status = preg_match('/startlat="[^" ]+/', $line, $startlat_tmp);
            if($status==0)
            {
              continue;
            }     
            
            //echo "test5".$datetime_tmp[0].'<BR>';
            $status = preg_match('/startlng="[^" ]+/', $line, $startlng_tmp);
            if($status==0)
            {
              continue;
            }        
            
            //echo "test5".$datetime_tmp[0].'<BR>';
            $status = preg_match('/endlat="[^" ]+/', $line, $endlat_tmp);
            if($status==0)
            {
              continue;
            }     
            
            //echo "test5".$datetime_tmp[0].'<BR>';
            $status = preg_match('/endlng="[^" ]+/', $line, $endlng_tmp);
            if($status==0)
            {
              continue;
            }       
            
            //echo "test5".$datetime_tmp[0].'<BR>';
            $status = preg_match('/total_dist="[^" ]+/', $line, $total_dist_tmp);
            if($status==0)
            {
              continue;
            }     
            
            //echo "test5".$datetime_tmp[0].'<BR>';
            $status = preg_match('/journey_time="[^" ]+/', $line, $journey_time_tmp);
            if($status==0)
            {
              continue;
            }       
            
            //echo "test5".$datetime_tmp[0].'<BR>';
            $status = preg_match('/halt_str="[^"]+/', $line, $halt_str_tmp);
            if($status==0)
            {
              continue;
            }     
            
            //echo "test5".$datetime_tmp[0].'<BR>';
            $status = preg_match('/track_str="[^"]+/', $line, $track_str_tmp);
            if($status==0)
            {
              continue;
            }                                                                    
            
            // Store Name with Value                  
            $tmp = explode("=",$imei_tmp[0]);
            $imei[$i] = preg_replace('/"/', '', $tmp[1]);            
            
            $tmp = explode("=",$vname_tmp[0]);
            $vname[$i] = preg_replace('/"/', '', $tmp[1]);
                        
            $tmp = explode("=",$startdate_tmp[0]);
            $startdate[$i] = preg_replace('/"/', '', $tmp[1]);
                         
            $tmp = explode("=",$enddate_tmp[0]);
            $enddate[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$startlat_tmp[0]);
            $startlat[$i] = preg_replace('/"/', '', $tmp[1]);  
            
            $tmp = explode("=",$startlng_tmp[0]);
            $startlng[$i] = preg_replace('/"/', '', $tmp[1]);                                                            

            $tmp = explode("=",$endlat_tmp[0]);
            $endlat[$i] = preg_replace('/"/', '', $tmp[1]);                                                            

            $tmp = explode("=",$endlng_tmp[0]);
            $endlng[$i] = preg_replace('/"/', '', $tmp[1]);                                                            

            $tmp = explode("=",$total_dist_tmp[0]);
            $total_dist[$i] = preg_replace('/"/', '', $tmp[1]);                                                            

            $tmp = explode("=",$journey_time_tmp[0]);
            $journey_time[$i] = preg_replace('/"/', '', $tmp[1]);        
            
            $tmp = explode("=",$halt_str_tmp[0]);
            $halt_str[$i] = preg_replace('/"/', '', $tmp[1]);                                                                                                                            

            $tmp = explode("=",$track_str_tmp[0]);
            $track_str[$i] = preg_replace('/"/', '', $tmp[1]);                                                            
            
            $i++;                      
        } //  // IF LINE 0                        
      }   // WHILE CLOSED
   }   // IF FEXIST
}  // FUNCTION SUMMARY CLOSED


///////////////////// FUNCTION DATALOG REPORT  ////////////////////////////////////////
  //function read_datalog_report_xml($xml_path, &$sts, &$msgtype, &$ver, &$fix, &$vserial, &$vname, &$userid, &$datetime, &$lat, &$lng, &$alt, &$speed, &$fuel, &$vtype, &$no_of_sat, &$cellname, &$cbc, &$distance, &$io8, &$sig_str, &$sup_v, &$speed_a, &$geo_in_a, &$geo_out_a, &$stop_a, &$move_a, &$lowv_a) 
  function read_datalog_report_xml($xml_path, &$sts, &$msgtype, &$ver, &$fix, &$imei, &$vname, &$userid, &$datetime, &$lat, &$lng, &$speed, &$vtype, &$cellname, &$io8, &$sig_str, &$sup_v)
  {
    //echo "<br>Read datalog".$xml_path;
    //echo "<br>xml_path in read_xml=".$xml_path;
    $fexist =1;    
    $xml = fopen($xml_path, "r") or $fexist = 0;
    
    $count = count(file($xml_path));
    //echo "<BR>COUNT======== $count lines in $xml";
    //$xml2 = '"'.$xml.'"';
    if($fexist)
    {
      //echo "<br>file exists";
      $i=0;   	
      while(!feof($xml))          // WHILE LINE != NULL
    	{
    		$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE   
        //echo "<br>i in while".$i;
        //echo '<br>line='.$line;  
        //echo "<br>line[0]=".$line[0]." ,".$line[strlen($line)-2]." ,".$DataValid." ,".$fix_tmp;                  
        // FORMAT 2 OPENS
    		$line_1 = preg_replace('/ /', '', $line);
    		$line_1 = preg_replace('/\//', '', $line_1);
    		$reg = '/^<[^<].*">$/';
          
        if( (preg_match($reg, $line_1, $data_match)) && (strlen($line)>10) )
        {          
            //echo "<br>i=".$i;
            $status = preg_match('/sts="[^"]+/', $line, $sts_tmp);
           // echo "<br>status1=".$status;
            $status = preg_match('/msgtype="[^"]+/', $line, $msgtype_tmp);
            //echo "<br>status2=".$status;
            $status = preg_match('/ver="[^"]+/', $line, $ver_tmp);
            //echo "<br>status3=".$status;
            $status = preg_match('/fix="[^"]+/', $line, $fix_tmp);
            //echo "<br>status4=".$status;
            $status = preg_match('/imei="[^"]+/', $line, $vserial_tmp);
           // echo "<br>status5=".$status;
            $status = preg_match('/vname="[^"]+/', $line, $vname_tmp);
           // echo "<br>status6=".$status;
            $status = preg_match('/userid="[^"]+/', $line, $userid_tmp);
           // echo "<br>status7=".$status;
            $status = preg_match('/datetime="[^"]+/', $line, $datetime_tmp);
           // echo "<br>status8=".$status;
            $status = preg_match('/lat="[^"]+/', $line, $lat_tmp);
           // echo "<br>status9=".$status;
            $status = preg_match('/lng="[^"]+/', $line, $lng_tmp);
          
            //echo "<br>status11=".$status;
            $status = preg_match('/speed="[^"]+/', $line, $speed_tmp);
           // echo "<br>status12=".$status;
           
            $status = preg_match('/vtype="[^"]+/', $line, $vtype_tmp);
           
            $status = preg_match('/cellname="[^"]+/', $line, $cellname_tmp);
            //echo "<br>status16=".$status;
         
            $status = preg_match('/io8="[^"]+/', $line, $io8_tmp);
           // echo "<br>status19=".$status;
            $status = preg_match('/sig_str="[^"]+/', $line, $sig_str_tmp);
            //echo "<br>status20=".$status;
            $status = preg_match('/sup_v="[^"]+/', $line, $sup_v_tmp);
            //echo "<br>status21=".$status;
                                                                                                  
            
            // Store Name with Value                  
            $tmp = explode("=",$sts_tmp[0]);
            $sts[$i] = preg_replace('/"/', '', $tmp[1]);

            $tmp = explode("=",$msgtype_tmp[0]);
            $msgtype[$i] = preg_replace('/"/', '', $tmp[1]);

            $tmp = explode("=",$ver_tmp[0]);
            $ver[$i] = preg_replace('/"/', '', $tmp[1]);

            $tmp = explode("=",$fix_tmp[0]);
            $fix[$i] = preg_replace('/"/', '', $tmp[1]);
                         
            $tmp = explode("=",$vserial_tmp[0]);
            $imei[$i] = preg_replace('/"/', '', $tmp[1]);
			//echo "vehicle_serail=".$vserial[$i]."<br>";
                                                         
            $tmp = explode("=",$vname_tmp[0]);
            $vname[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$userid_tmp[0]);
            $userid[$i] = preg_replace('/"/', '', $tmp[1]);            
            
            $tmp = explode("=",$datetime_tmp[0]);
            $datetime[$i] = preg_replace('/"/', '', $tmp[1]);

            $tmp = explode("=",$lat_tmp[0]);
            $lat[$i] = preg_replace('/"/', '', $tmp[1]);

            $tmp = explode("=",$lng_tmp[0]);
            $lng[$i] = preg_replace('/"/', '', $tmp[1]);

            $tmp = explode("=",$speed_tmp[0]);
            $speed[$i] = preg_replace('/"/', '', $tmp[1]);

            $tmp = explode("=",$vtype_tmp[0]);
            $vtype[$i] = preg_replace('/"/', '', $tmp[1]);

            $tmp = explode("=",$cellname_tmp[0]);
            $cellname[$i] = preg_replace('/"/', '', $tmp[1]);

            $tmp = explode("=",$distance_tmp[0]);
            $distance[$i] = preg_replace('/"/', '', $tmp[1]);

            $tmp = explode("=",$io8_tmp[0]);
            $io8[$i] = preg_replace('/"/', '', $tmp[1]);

            $tmp = explode("=",$sig_str_tmp[0]);
            $sig_str[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$sup_v_tmp[0]);
            $sup_v[$i] = preg_replace('/"/', '', $tmp[1]);            
            //echo "<br>vname=".$vname[$i];
            //echo "<br>msgtype=".$msgtype[$i];            
            
            $i++;                      
        } //  // IF LINE 0                        
      }   // WHILE CLOSED
   }   // IF FEXIST
}  // FUNCTION DATALOG CLOSED

  function read_datalog2_report_xml($xml_path, &$sts, &$msgtype, &$ver, &$fix, &$imei, &$vname, &$userid, &$datetime, &$lat, &$lng, &$speed, &$vtype, &$cellname, &$io8, &$sig_str, &$sup_v, &$ax, &$ay, &$az, &$mx, &$my, &$mz, &$bx, &$by, &$bz)
  {
    //echo "<br>Read datalog".$xml_path;
    //echo "<br>xml_path in read_xml=".$xml_path;
    $fexist =1;    
    $xml = fopen($xml_path, "r") or $fexist = 0;
    
    $count = count(file($xml_path));
    //echo "<BR>COUNT======== $count lines in $xml";
    //$xml2 = '"'.$xml.'"';
    if($fexist)
    {
      //echo "<br>file exists";
      $i=0;   	
      while(!feof($xml))          // WHILE LINE != NULL
    	{
    		$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE   
        //echo "<br>i in while".$i;
        //echo '<br>line='.$line;  
        //echo "<br>line[0]=".$line[0]." ,".$line[strlen($line)-2]." ,".$DataValid." ,".$fix_tmp;                  
        // FORMAT 2 OPENS
    		$line_1 = preg_replace('/ /', '', $line);
    		$line_1 = preg_replace('/\//', '', $line_1);
    		$reg = '/^<[^<].*">$/';
          
        if( (preg_match($reg, $line_1, $data_match)) && (strlen($line)>10) )
        {          
            //echo "<br>i=".$i;
            $status = preg_match('/sts="[^"]+/', $line, $sts_tmp);
           // echo "<br>status1=".$status;
            $status = preg_match('/msgtype="[^"]+/', $line, $msgtype_tmp);
            //echo "<br>status2=".$status;
            $status = preg_match('/ver="[^"]+/', $line, $ver_tmp);
            //echo "<br>status3=".$status;
            $status = preg_match('/fix="[^"]+/', $line, $fix_tmp);
            //echo "<br>status4=".$status;
            $status = preg_match('/imei="[^"]+/', $line, $vserial_tmp);
           // echo "<br>status5=".$status;
            $status = preg_match('/vname="[^"]+/', $line, $vname_tmp);
           // echo "<br>status6=".$status;
            $status = preg_match('/userid="[^"]+/', $line, $userid_tmp);
           // echo "<br>status7=".$status;
            $status = preg_match('/datetime="[^"]+/', $line, $datetime_tmp);
           // echo "<br>status8=".$status;
            $status = preg_match('/lat="[^"]+/', $line, $lat_tmp);
           // echo "<br>status9=".$status;
            $status = preg_match('/lng="[^"]+/', $line, $lng_tmp);
          
            //echo "<br>status11=".$status;
            $status = preg_match('/speed="[^"]+/', $line, $speed_tmp);
           // echo "<br>status12=".$status;
           
            $status = preg_match('/vtype="[^"]+/', $line, $vtype_tmp);
           
            $status = preg_match('/cellname="[^"]+/', $line, $cellname_tmp);
            //echo "<br>status16=".$status;
         
            $status = preg_match('/io8="[^"]+/', $line, $io8_tmp);
           // echo "<br>status19=".$status;
            $status = preg_match('/sig_str="[^"]+/', $line, $sig_str_tmp);
            //echo "<br>status20=".$status;
            $status = preg_match('/sup_v="[^"]+/', $line, $sup_v_tmp);
			
			$status = preg_match('/ax="[^"]+/', $line, $ax_tmp);			
			$status = preg_match('/ay="[^"]+/', $line, $ay_tmp);
			$status = preg_match('/az="[^"]+/', $line, $az_tmp);
			$status = preg_match('/mx="[^"]+/', $line, $mx_tmp);
			$status = preg_match('/my="[^"]+/', $line, $my_tmp);
			$status = preg_match('/mz="[^"]+/', $line, $mz_tmp);
			$status = preg_match('/bx="[^"]+/', $line, $bx_tmp);
			$status = preg_match('/by="[^"]+/', $line, $by_tmp);
			$status = preg_match('/bz="[^"]+/', $line, $bz_tmp);
            //echo "<br>status21=".$status;
                                                                                                  
            
            // Store Name with Value                  
            $tmp = explode("=",$sts_tmp[0]);
            $sts[$i] = preg_replace('/"/', '', $tmp[1]);

            $tmp = explode("=",$msgtype_tmp[0]);
            $msgtype[$i] = preg_replace('/"/', '', $tmp[1]);

            $tmp = explode("=",$ver_tmp[0]);
            $ver[$i] = preg_replace('/"/', '', $tmp[1]);

            $tmp = explode("=",$fix_tmp[0]);
            $fix[$i] = preg_replace('/"/', '', $tmp[1]);
                         
            $tmp = explode("=",$vserial_tmp[0]);
            $imei[$i] = preg_replace('/"/', '', $tmp[1]);
			//echo "vehicle_serail=".$vserial[$i]."<br>";
                                                         
            $tmp = explode("=",$vname_tmp[0]);
            $vname[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$userid_tmp[0]);
            $userid[$i] = preg_replace('/"/', '', $tmp[1]);            
            
            $tmp = explode("=",$datetime_tmp[0]);
            $datetime[$i] = preg_replace('/"/', '', $tmp[1]);

            $tmp = explode("=",$lat_tmp[0]);
            $lat[$i] = preg_replace('/"/', '', $tmp[1]);

            $tmp = explode("=",$lng_tmp[0]);
            $lng[$i] = preg_replace('/"/', '', $tmp[1]);

            $tmp = explode("=",$speed_tmp[0]);
            $speed[$i] = preg_replace('/"/', '', $tmp[1]);

            $tmp = explode("=",$vtype_tmp[0]);
            $vtype[$i] = preg_replace('/"/', '', $tmp[1]);

            $tmp = explode("=",$cellname_tmp[0]);
            $cellname[$i] = preg_replace('/"/', '', $tmp[1]);

            $tmp = explode("=",$distance_tmp[0]);
            $distance[$i] = preg_replace('/"/', '', $tmp[1]);

            $tmp = explode("=",$io8_tmp[0]);
            $io8[$i] = preg_replace('/"/', '', $tmp[1]);

            $tmp = explode("=",$sig_str_tmp[0]);
            $sig_str[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$sup_v_tmp[0]);
            $sup_v[$i] = preg_replace('/"/', '', $tmp[1]);

            $tmp = explode("=",$ax_tmp[0]);
            $ax[$i] = preg_replace('/"/', '', $tmp[1]);

            $tmp = explode("=",$ay_tmp[0]);
            $ay[$i] = preg_replace('/"/', '', $tmp[1]);            

            $tmp = explode("=",$az_tmp[0]);
            $az[$i] = preg_replace('/"/', '', $tmp[1]);            

            $tmp = explode("=",$mx_tmp[0]);
            $mx[$i] = preg_replace('/"/', '', $tmp[1]);            

            $tmp = explode("=",$my_tmp[0]);
            $my[$i] = preg_replace('/"/', '', $tmp[1]);            

            $tmp = explode("=",$mz_tmp[0]);
            $mz[$i] = preg_replace('/"/', '', $tmp[1]);            

            $tmp = explode("=",$bx_tmp[0]);
            $bx[$i] = preg_replace('/"/', '', $tmp[1]);            

            $tmp = explode("=",$by_tmp[0]);
            $by[$i] = preg_replace('/"/', '', $tmp[1]);            

            $tmp = explode("=",$bz_tmp[0]);
            $bz[$i] = preg_replace('/"/', '', $tmp[1]);            
            
            //echo "<br>vname=".$vname[$i];
            //echo "<br>msgtype=".$msgtype[$i];            
            
            $i++;                      
        } //  // IF LINE 0                        
      }   // WHILE CLOSED
   }   // IF FEXIST
}  // FUNCTION DATALOG CLOSED

////////////////////// FUNCTION NO GPS REPORT  //////////////////////////////////////// 
  function read_datagap_xml($xml_path, &$imei_datagap, &$vname_datagap, &$t1_no_gps, &$t2_no_gps, &$tdiff_no_gps, &$lat1_no_gps, &$lng1_no_gps, &$lat2_no_gps, &$lng2_no_gps, &$speed1_no_gps, &$speed2_no_gps, &$t1_no_data, &$t2_no_data, &$tdiff_no_data, &$lat1_no_data, &$lng1_no_data, &$lat2_no_data, &$lng2_no_data, &$speed1_no_data, &$speed2_no_data)
  {
    //echo "<br>Read Datagap";
    //echo "<br>xml_path in read_xml=".$xml_path;    
    $fexist =1;    
    $xml = fopen($xml_path, "r") or $fexist = 0;
    
    $count = count(file($xml_path));
    //echo "<BR>COUNT======== $count lines in $xml";
    //$xml2 = '"'.$xml.'"';
    if($fexist)
    {
      $i=0;   	
      while(!feof($xml))          // WHILE LINE != NULL
    	{
    		$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE   
        //echo '<br>line='.$line;  
        //echo "<br>line[0]=".$line[0]." ,".$line[strlen($line)-2]." ,".$DataValid." ,".$fix_tmp;          
        
        // FORMAT 2 OPENS
        if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && (strlen($line)>20))
        {
            //echo "<br>IN format2";             							
            $imei_datagap[$i] = get_xml_data('/imei_datagap="[^"]+/', $line);
            $vname_datagap[$i] = get_xml_data('/vname_datagap="[^"]+/', $line);
            
            $t1_no_gps[$i] = get_xml_data('/t1_no_gps="[^"]+/', $line);
            $t2_no_gps[$i] = get_xml_data('/t2_no_gps="[^"]+/', $line);
            $tdiff_no_gps[$i] = get_xml_data('/tdiff_no_gps="[^"]+/', $line);
            $la1_no_gps[$i] = get_xml_data('/lat1_no_gps="[^"]+/', $line);
            $lng1_no_gps[$i] = get_xml_data('/lng1_no_gps="[^"]+/', $line);
            $lat2_no_gps[$i] = get_xml_data('/lat2_no_gps="[^"]+/', $line);
            $lng2_no_gps[$i] = get_xml_data('/lng2_no_gps="[^"]+/', $line);            
            $speed1_no_gps[$i] = get_xml_data('/speed1_no_gps="[^"]+/', $line);
            $speed2_no_gps[$i] = get_xml_data('/speed2_no_gps="[^"]+/', $line);
            
            $t1_no_data[$i] = get_xml_data('/t1_no_data="[^"]+/', $line);
            $t2_no_data[$i] = get_xml_data('/t2_no_data="[^"]+/', $line);
            $tdiff_no_data[$i] = get_xml_data('/tdiff_no_data="[^"]+/', $line);
           
            $la1_no_data[$i] = get_xml_data('/lat1_no_data="[^"]+/', $line);
            $lng1_no_data[$i] = get_xml_data('/lng1_no_data="[^"]+/', $line);
            $lat2_no_data[$i] = get_xml_data('/lat2_no_data="[^"]+/', $line);
            $lng2_no_data[$i] = get_xml_data('/lng2_no_data="[^"]+/', $line);            
            $speed1_no_data[$i] = get_xml_data('/speed1_no_data="[^"]+/', $line);
            $speed2_no_data[$i] = get_xml_data('/speed2_no_data="[^"]+/', $line);            //echo "<br>imei=".$imei[$i]." ,vname=".$vname[$i]." ,datetime=".$datetime[$i]." ,lat=".$lat[$i]." ,lng=".$lng[$i];                                                                 
            
            $i++;                      
        } //  // IF LINE 0                        
      }   // WHILE CLOSED
   }   // IF FEXIST
}  // FUNCTION NO GPS CLOSED

////////////////////// FUNCTION BUS REPORT  ////////////////////////////////////////
function read_bus_xml($xml_path, &$vname, &$datefor, &$busstop, &$arrival, &$scheduledtime, &$shiftname ,&$satelliteposition)
{

    //echo "<br>Read Halt";
    //echo "<br>xml_path in read_xml=".$xml_path;    
    $fexist =1;    
    $xml = fopen($xml_path, "r") or $fexist = 0;
    
    $count = count(file($xml_path));  
    //echo "<BR>COUNT======== $count lines in $xml";
    //$xml2 = '"'.$xml.'"';
    if($fexist)
    {
      $i=0;
    	
      while(!feof($xml))          // WHILE LINE != NULL
    	{
    		$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE   
        //echo '<br>line='.$line;  
        //echo "<br>line[0]=".$line[0]." ,".$line[strlen($line)-2]." ,".$DataValid." ,".$fix_tmp;          
        
        // FORMAT 2 OPENS
        if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') )
        {
            //echo "<br>IN format2";             
							
            $status = preg_match('/vname="[^"]+/', $line, $vname_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/date="[^"]+/', $line, $date_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/busstop="[^"]+/', $line, $busstop_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/arrival="[^"]+/', $line, $arrival_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/scheduledtime="[^"]+/', $line, $scheduledtime_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
                                    
            //echo "test5".$datetime_tmp[0].'<BR>';
            $status = preg_match('/shift="[^" ]+/', $line, $shift_tmp);
            if($status==0)
            {
             // continue;
            }
            $status = preg_match('/latlngpos="[^" ]+/', $line, $satellite_tmp);
            if($status==0)
            {
             // continue;
            }
              
            // Store Name with Value                  
            $tmp = explode("=",$vname_tmp[0]);
            $vname[$i] = preg_replace('/"/', '', $tmp[1]);
                        
            $tmp = explode("=",$date_tmp[0]);
            $datefor[$i] = preg_replace('/"/', '', $tmp[1]);
                         
            $tmp = explode("=",$busstop_tmp[0]);
            $busstop[$i] = preg_replace('/"/', '', $tmp[1]);    
            
            $tmp = explode("=",$arrival_tmp[0]);
            $arrival[$i] = preg_replace('/"/', '', $tmp[1]); 
            
            $tmp = explode("=",$scheduledtime_tmp[0]);
            $scheduledtime[$i] = preg_replace('/"/', '', $tmp[1]);    
            
            $tmp = explode("=",$shift_tmp[0]);
            $shiftname[$i] = preg_replace('/"/', '', $tmp[1]); 
            
            $tmp = explode("=",$satellite_tmp[0]);
            $satelliteposition[$i] = preg_replace('/"/', '', $tmp[1]);                            
            
            $i++;                      
        } //  // IF LINE 0                        
      }   // WHILE CLOSED
   }   // IF FEXIST    
}  // FUNCTION BUS CLOSED
/*function read_bus_xml($xml_path, &$vname, &$datefor, &$busstop, &$arrival, &$scheduledtime, &$shiftname)
{

    //echo "<br>Read Halt";
    //echo "<br>xml_path in read_xml=".$xml_path;    
    $fexist =1;    
    $xml = fopen($xml_path, "r") or $fexist = 0;
    
    $count = count(file($xml_path));  
    //echo "<BR>COUNT======== $count lines in $xml";
    //$xml2 = '"'.$xml.'"';
    if($fexist)
    {
      $i=0;
    	
      while(!feof($xml))          // WHILE LINE != NULL
    	{
    		$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE   
        //echo '<br>line='.$line;  
        //echo "<br>line[0]=".$line[0]." ,".$line[strlen($line)-2]." ,".$DataValid." ,".$fix_tmp;          
        
        // FORMAT 2 OPENS
        if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') )
        {
            //echo "<br>IN format2";             
							
            $status = preg_match('/vname="[^"]+/', $line, $vname_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/date="[^"]+/', $line, $date_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/busstop="[^"]+/', $line, $busstop_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/arrival="[^"]+/', $line, $arrival_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/scheduledtime="[^"]+/', $line, $scheduledtime_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
                                    
            //echo "test5".$datetime_tmp[0].'<BR>';
            $status = preg_match('/shift="[^" ]+/', $line, $shift_tmp);
            if($status==0)
            {
              continue;
            }
              
            // Store Name with Value                  
            $tmp = explode("=",$vname_tmp[0]);
            $vname[$i] = preg_replace('/"/', '', $tmp[1]);
                        
            $tmp = explode("=",$date_tmp[0]);
            $datefor[$i] = preg_replace('/"/', '', $tmp[1]);
                         
            $tmp = explode("=",$busstop_tmp[0]);
            $busstop[$i] = preg_replace('/"/', '', $tmp[1]);    
            
            $tmp = explode("=",$arrival_tmp[0]);
            $arrival[$i] = preg_replace('/"/', '', $tmp[1]); 
            
            $tmp = explode("=",$scheduledtime_tmp[0]);
            $scheduledtime[$i] = preg_replace('/"/', '', $tmp[1]);    
            
            $tmp = explode("=",$shift_tmp[0]);
            $shiftname[$i] = preg_replace('/"/', '', $tmp[1]);                        
            
            $i++;                      
        } //  // IF LINE 0                        
      }   // WHILE CLOSED
   }   // IF FEXIST    
}  // FUNCTION BUS CLOSED

*/
////////////////////// FUNCTION STUDENT REPORT  ////////////////////////////////////////
function read_student_xml($xml_path, &$vname, &$sname, &$datefor, &$busstop, &$arrival, &$scheduledtime, &$shiftname)
{

   // echo "<br>Read Halt";
   // echo "<br>xml_path in read_xml=".$xml_path;
     
    
    $fexist =1;    
    $xml = fopen($xml_path, "r") or $fexist = 0;
    
    $count = count(file($xml_path));  
    //echo "<BR>COUNT======== $count lines in $xml";
    //$xml2 = '"'.$xml.'"';
    if($fexist)
    {
      $i=0;
    	
      while(!feof($xml))          // WHILE LINE != NULL
    	{
    		$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE   
        //echo '<br>line='.$line;  
        //echo "<br>line[0]=".$line[0]." ,".$line[strlen($line)-2]." ,".$DataValid." ,".$fix_tmp;          
        
        // FORMAT 2 OPENS
        if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') )
        {
            //echo "<br>IN format2";             
							
            $status = preg_match('/vname="[^"]+/', $line, $vname_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/sname="[^"]+/', $line, $sname_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
             // continue;
            }
            
            $status = preg_match('/date="[^"]+/', $line, $date_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
             // continue;
            }
            
            $status = preg_match('/busstop="[^"]+/', $line, $busstop_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              //continue;
            }
            
            $status = preg_match('/arrival="[^"]+/', $line, $arrival_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
             // continue;
            }
            
            $status = preg_match('/scheduledtime="[^"]+/', $line, $scheduledtime_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
             // continue;
            }
                                    
            //echo "test5".$datetime_tmp[0].'<BR>';
            $status = preg_match('/shift="[^" ]+/', $line, $shift_tmp);
            if($status==0)
            {
             // continue;
            }
              
            // Store Name with Value                  
            $tmp = explode("=",$vname_tmp[0]);
            $vname[$i] = preg_replace('/"/', '', $tmp[1]);
            //echo "vanme==".$vname[$i];
            $tmp = explode("=",$sname_tmp[0]);
            $sname[$i] = preg_replace('/"/', '', $tmp[1]);
                        
            $tmp = explode("=",$date_tmp[0]);
            $datefor[$i] = preg_replace('/"/', '', $tmp[1]);
                         
            $tmp = explode("=",$busstop_tmp[0]);
            $busstop[$i] = preg_replace('/"/', '', $tmp[1]);    
            
            $tmp = explode("=",$arrival_tmp[0]);
            $arrival[$i] = preg_replace('/"/', '', $tmp[1]); 
            
            $tmp = explode("=",$scheduledtime_tmp[0]);
            $scheduledtime[$i] = preg_replace('/"/', '', $tmp[1]);    
            
            $tmp = explode("=",$shift_tmp[0]);
            $shiftname[$i] = preg_replace('/"/', '', $tmp[1]);                        
            
            $i++;                      
        } //  // IF LINE 0                        
      }   // WHILE CLOSED
   }   // IF FEXIST    
}  // FUNCTION STUDENT CLOSED
/*function read_student_xml($xml_path, &$vname, &$sname, &$datefor, &$busstop, &$arrival, &$scheduledtime, &$shiftname)
{

    //echo "<br>Read Halt";
    //echo "<br>xml_path in read_xml=".$xml_path;    
    $fexist =1;    
    $xml = fopen($xml_path, "r") or $fexist = 0;
    
    $count = count(file($xml_path));  
    //echo "<BR>COUNT======== $count lines in $xml";
    //$xml2 = '"'.$xml.'"';
    if($fexist)
    {
      $i=0;
    	
      while(!feof($xml))          // WHILE LINE != NULL
    	{
    		$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE   
        //echo '<br>line='.$line;  
        //echo "<br>line[0]=".$line[0]." ,".$line[strlen($line)-2]." ,".$DataValid." ,".$fix_tmp;          
        
        // FORMAT 2 OPENS
        if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') )
        {
            //echo "<br>IN format2";             
							
            $status = preg_match('/vname="[^"]+/', $line, $vname_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/sname="[^"]+/', $line, $sname_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/date="[^"]+/', $line, $date_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/busstop="[^"]+/', $line, $busstop_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/arrival="[^"]+/', $line, $arrival_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/scheduledtime="[^"]+/', $line, $scheduledtime_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
                                    
            //echo "test5".$datetime_tmp[0].'<BR>';
            $status = preg_match('/shift="[^" ]+/', $line, $shift_tmp);
            if($status==0)
            {
              continue;
            }
              
            // Store Name with Value                  
            $tmp = explode("=",$vname_tmp[0]);
            $vname[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$sname_tmp[0]);
            $sname[$i] = preg_replace('/"/', '', $tmp[1]);
                        
            $tmp = explode("=",$date_tmp[0]);
            $datefor[$i] = preg_replace('/"/', '', $tmp[1]);
                         
            $tmp = explode("=",$busstop_tmp[0]);
            $busstop[$i] = preg_replace('/"/', '', $tmp[1]);    
            
            $tmp = explode("=",$arrival_tmp[0]);
            $arrival[$i] = preg_replace('/"/', '', $tmp[1]); 
            
            $tmp = explode("=",$scheduledtime_tmp[0]);
            $scheduledtime[$i] = preg_replace('/"/', '', $tmp[1]);    
            
            $tmp = explode("=",$shift_tmp[0]);
            $shiftname[$i] = preg_replace('/"/', '', $tmp[1]);                        
            
            $i++;                      
        } //  // IF LINE 0                        
      }   // WHILE CLOSED
   }   // IF FEXIST    
}  // FUNCTION STUDENT CLOSED
*/
////////////////////// FUNCTION VISIT REPORT  ////////////////////////////////////////
   
  function read_visitdetail_xml($xml_path, &$imei0, &$pname0, &$pmobile0, &$datetime1, &$datetime2, &$duration0, &$lat0, &$lng0, &$cellname0)
  {
    //echo "<br>Read Halt";
    //echo "<br>xml_path in read_xml=".$xml_path;   
    $fexist =1;    
    $xml = fopen($xml_path, "r") or $fexist = 0;
    
    $count = count(file($xml_path));
    //echo "<BR>COUNT======== $count lines in $xml";
    //$xml2 = '"'.$xml.'"';
    if($fexist)
    {
      $i=0;
    	
      while(!feof($xml))          // WHILE LINE != NULL
    	{
    		$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE   
        //echo '<br>line='.$line;  
        //echo "<br>line[0]=".$line[0]." ,".$line[strlen($line)-2]." ,".$DataValid." ,".$fix_tmp;          
        
        // FORMAT 2 OPENS
        if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') )
        {
            //echo "<br>IN format2";             							
            $status = preg_match('/imei="[^"]+/', $line, $imei_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
                        
            $status = preg_match('/pname="[^"]+/', $line, $pname_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/pmobile="[^"]+/', $line, $pmobile_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/datetime1="[^"]+/', $line, $datetime_tmp1);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/datetime2="[^"]+/', $line, $datetime_tmp2);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/duration="[^"]+/', $line, $duration_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }                         
                                    
            //echo "test2".'<BR>';
            $status = preg_match('/lat="[^" ]+/', $line, $lat_tmp);
            if($status==0)
            {
              continue;
            }
            //echo "test4".'<BR>';
            $status = preg_match('/lng="[^" ]+/', $line, $lng_tmp);
            if($status==0)
            {
              continue;
            }
             
             //echo "test4".'<BR>';
            $status = preg_match('/cellname="[^"]+/', $line, $cellname_tmp);
            if($status==0)
            {
              continue;
            }
            
            
            // Store Name with Value                  
            $tmp = explode("=",$imei_tmp[0]);
            $imei0[$i] = preg_replace('/"/', '', $tmp[1]);            
            
            $tmp = explode("=",$pname_tmp[0]);
            $pname0[$i] = preg_replace('/"/', '', $tmp[1]);
                        
            $tmp = explode("=",$pmobile_tmp[0]);
            $pmobile0[$i] = preg_replace('/"/', '', $tmp[1]);
                         
            $tmp = explode("=",$datetime_tmp1[0]);
            $datetime1[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$datetime_tmp2[0]);
            $datetime2[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$duration_tmp[0]);
            $duration0[$i] = preg_replace('/"/', '', $tmp[1]);                                            
            
            $tmp = explode("=",$lat_tmp[0]);
            $lat0[$i] = preg_replace('/"/', '', $tmp[1]);                        

            $tmp = explode("=",$lng_tmp[0]);
            $lng0[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$cellname_tmp[0]);
            $cellname0[$i] = preg_replace('/"/', '', $tmp[1]);                        
             
            $i++;                      
        } //  // IF LINE 0                        
      }   // WHILE CLOSED
   }   // IF FEXIST
}  // FUNCTION VISIT CLOSED


// FUNCTION READ TRIP DATA OPENS
  function read_tripdata_xml($xml_path, &$datetime, &$vehicleserial, &$vehiclename, &$lat, &$lng, &$speed)
  {    
    $fexist =1;
    $fix_tmp = 1;
    $final_xml = $xml_path;
    //echo "<br>final path=".$final_xml;
    $xml = fopen($final_xml, "r") or $fexist = 0;
    
    $count = count(file($xml_path));
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
        else if(strpos($line,'c="0"'))
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
          //  echo "<br>IN format1";             
            $status = preg_match('/v="[^" ]+/', $line, $vehicleserial_tmp);
            //echo "Status=".$status.'<BR>';
           // echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
            //echo "test2".'<BR>';
            $status = preg_match('/w="[^"]+/', $line, $vehiclename_tmp);
            //echo "status=".$status."<br>";
            //if($status==0)
            //{
              //continue;
            //}
           // echo "test4".'<BR>';
            //$status = preg_match('/\d+-\d+-\d+ \d+:\d+:\d+/', $line, $datetime_tmp);
            $status = preg_match('/h="[^"]+/', $line, $datetime_tmp);
            if($status==0)
            {
              continue;
            }
            //echo "test5".$datetime_tmp[0].'<BR>';
            $status = preg_match('/d="[^" ]+/', $line, $lat_tmp);
            if($status==0)
            {
              continue;
            }
            //echo "test6".'<BR>';
            $status = preg_match('/e="[^" ]+/', $line, $lng_tmp);
            if($status==0)
            {
              continue;
            }
			$status = preg_match('/f="[^" ]+/', $line, $lng_speed);
            if($status==0)
            {
              continue;
            }
            $tmp = explode("=",$vehicleserial_tmp[0]);
            $vehicleserial[$i] = preg_replace('/"/', '', $tmp[1]);

            $tmp = explode("=",$datetime_tmp[0]);
            $datetime[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$vehiclename_tmp[0]);
            $vehiclename[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$lat_tmp[0]);
            $lat[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$lng_tmp[0]);
            $lng[$i] = preg_replace('/"/', '', $tmp[1]);
			
			$tmp = explode("=",$lng_speed[0]);
            $speed[$i] = preg_replace('/"/', '', $tmp[1]);
            $i++;                      
        } // IF FORMAT 1 CLOSED     
                      
        /////////////////////////////////////////////////////////////                            
      }   // WHILE CLOSED
   }
}  // FUNCTION READ TRIP DATA CLOSED



////////////////////// FUNCTION HALT  ////////////////////////////////////////

  function read_sector_data_xml($xml_path, &$imei_xml, &$vname_xml, &$lat_xml, &$lng_xml, &$arr_time_xml, &$dep_time_xml, &$duration_xml, &$route_name_xml, &$sector_name_xml)
  {
    //echo "<br>Read Halt";
    //echo "<br>xml_path in read_xml=".$xml_path;    
    $fexist =1;    
    $xml = fopen($xml_path, "r") or $fexist = 0;
    
    $count = count(file($xml_path));
    //echo "<BR>COUNT======== $count lines in $xml";
    //$xml2 = '"'.$xml.'"';
    if($fexist)
    {
      $i=0;
    	
      while(!feof($xml))          // WHILE LINE != NULL
    	{
    		$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE   
        //echo '<br>line='.$line;  
        //echo "<br>line[0]=".$line[0]." ,".$line[strlen($line)-2]." ,".$DataValid." ,".$fix_tmp;          
        
        // FORMAT 2 OPENS
        if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') )
        {
            //echo "<br>IN format2";             							
            $status = preg_match('/imei="[^"]+/', $line, $imei_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/vname="[^"]+/', $line, $vname_tmp);
            //echo "Status=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/lat="[^" ]+/', $line, $lat_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/lng="[^" ]+/', $line, $lng_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
                                    
            //echo "test2".'<BR>';
            $status = preg_match('/arr_time="[^"]+/', $line, $arr_time_tmp);
            if($status==0)
            {
              continue;
            }
            //echo "test4".'<BR>';
            $status = preg_match('/dep_time="[^"]+/', $line, $dep_time_tmp);
            if($status==0)
            {
              continue;
            }
            //echo "test5".$datetime_tmp[0].'<BR>';
            $status = preg_match('/duration="[^" ]+/', $line, $duration_tmp);
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/route_name="[^"]+/', $line, $route_name_tmp);
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/sector_name="[^"]+/', $line, $sector_name_tmp);
            if($status==0)
            {
              continue;
            }                        
            
            // Store Name with Value                  
            $tmp = explode("=",$imei_tmp[0]);
            $imei_xml[$i] = preg_replace('/"/', '', $tmp[1]);            
            
            $tmp = explode("=",$vname_tmp[0]);
            $vname_xml[$i] = preg_replace('/"/', '', $tmp[1]);
                        
            $tmp = explode("=",$lat_tmp[0]);
            $lat_xml[$i] = preg_replace('/"/', '', $tmp[1]);
                         
            $tmp = explode("=",$lng_tmp[0]);
            $lng_xml[$i] = preg_replace('/"/', '', $tmp[1]);                        
            
            $tmp = explode("=",$arr_time_tmp[0]);
            $arr_time_xml[$i] = preg_replace('/"/', '', $tmp[1]);
            //$arr_time[$i] = $arr_time_tmp[0];
            
            $tmp = explode("=",$dep_time_tmp[0]);
            $dep_time_xml[$i] = preg_replace('/"/', '', $tmp[1]);
            //$dep_time[$i] = $dep_time_tmp[0];

            $tmp = explode("=",$duration_tmp[0]);
            $duration_xml[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$route_name_tmp[0]);
            $route_name_xml[$i] = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$sector_name_tmp[0]);
            $sector_name_xml[$i] = preg_replace('/"/', '', $tmp[1]);                        
            
            $i++;                      
        } //  // IF LINE 0                        
      }   // WHILE CLOSED
   }   // IF FEXIST
}  // FUNCTION SECTOR CLOSED  


////////////////////// FUNCTION SUPPLY VOLTAGE REPORT  ////////////////////////////////////////
  function read_suppv_xml($xml_path, &$vname, &$imei,  &$datetime, &$supv)
  {
    //echo "<br>Read Halt";
    //echo "<br>xml_path in read_xml=".$xml_path;    
    $fexist =1;    
    $xml = fopen($xml_path, "r") or $fexist = 0;
    
    $count = count(file($xml_path));
    //echo "<BR>COUNT======== $count lines in $xml";
    //$xml2 = '"'.$xml.'"';
    if($fexist)
    {
      $i=0;
    	
      while(!feof($xml))          // WHILE LINE != NULL
    	{
    		$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE   
        //echo '<br>line='.$line;  
        //echo "<br>line[0]=".$line[0]." ,".$line[strlen($line)-2]." ,".$DataValid." ,".$fix_tmp;          
        
        // FORMAT 2 OPENS
        if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') )
        {
            //echo "<br>IN format2";             
            $status = preg_match('/imei="[^"]+/', $line, $imei_tmp);
            //echo "Status1=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }							
            
            $status = preg_match('/vname="[^"]+/', $line, $vname_tmp);
            //echo "Status2=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
            
            $status = preg_match('/datetime="[^"]+/', $line, $datetime_tmp);
            //echo "Status3=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
                                    
            //echo "test5".$datetime_tmp[0].'<BR>';
            $status = preg_match('/supv="[^" ]+/', $line, $supv_tmp);
            //echo "Status5=".$status.'<BR>';
            if($status==0)
            {
              continue;
            }
            
            // Store Name with Value                  
            $tmp = explode("=",$imei_tmp[0]);
            $imei[$i] = preg_replace('/"/', '', $tmp[1]);                        
            
            $tmp = explode("=",$vname_tmp[0]);
            $vname[$i] = preg_replace('/"/', '', $tmp[1]);
                        
            $tmp = explode("=",$datetime_tmp[0]);
            $datetime[$i] = preg_replace('/"/', '', $tmp[1]); 
            
            $tmp = explode("=",$supv_tmp[0]);
            $supv[$i] = preg_replace('/"/', '', $tmp[1]);                        
            
            //echo "<br>datefrom=".$datefrom[$i]." dateto=".$dateto[$i];
            $i++;                      
        } //  // IF LINE 0                        
      }   // WHILE CLOSED
   }   // IF FEXIST
}  // FUNCTION SupVoltage closed	     
?>
