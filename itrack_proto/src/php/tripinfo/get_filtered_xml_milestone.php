<?php	  
  $DEBUG =0;
  
  $root_dir = getcwd();
  
  //$dist_path = $root_dir."/calculate_distance.php";
  //include_once($dist_path);
  include_once("calculate_distance.php");
    
  //$xmltowrite = $_REQUEST['xml_file']; 
	$mode = $_REQUEST['mode'];
	$vserial1 = $_REQUEST['vserial'];
	if($DEBUG) echo "<br>vserial=".$vserial1;
	
	$startdate = $_REQUEST['start_date'];
	$enddate = $_REQUEST['end_date'];
  
  $startdate = str_replace('/','-',$startdate);
  $enddate = str_replace('/','-',$enddate);  
	//$time_interval1 = $_REQUEST['time_interval'];  	
	$vserial2 = explode(':',$vserial1) ;
  //echo "<br>rootdir=".$root_dir.'<br>xmltowrite='.$xmltowrite;
	
  set_time_limit(300);	
	
  for($i=0;$i<sizeof($vserial2);$i++)
  {
    $query = "SELECT vehicle_name FROM vehicle WHERE vehicle_id IN (SELECT vehicle_id FROM vehicle_assignment ".
    "WHERE device_imei_no='$vserial2[$i]' AND status=1) AND status=1";
    if($DEBUG) echo "<br>".$query."<br>";
    $result = mysql_query($query, $DbConnection);
    $row = mysql_fetch_object($result);
    $vname[$i] = $row->vehicle_name;
  }		

  //$path_sorted = $root_dir."/sort_xml.php";
  //include_once($path_sorted);  
  include_once("sort_xml.php");	  	
	if($DEBUG) echo   "<br>".$xmltowrite.' '.$mode.' '.$vserial1.' '.$startdate.' '.$enddate.' '.$time_interval1."<br>";
	  
  $minlat = 180; 
  $maxlat = -180;
  $minlong = 180;
  $maxlong = -180; 
	
	$maxPoints = 10000;
	$file_exist = 0;
	
	/*$tmptimeinterval = strtotime($enddate) - strtotime($startdate);
	
	//echo  "1".$time_interval1.'<BR>';
  
  if($time_interval1=="auto")
	{
	   $timeinterval =   ($tmptimeinterval/$maxPoints);
     $distanceinterval = 0.1; 
   //echo  "2".$timeinterval.' '.$distanceinterval.'<BR>';
  }
  else
  {
    if($tmptimeinterval>86400)
    {
      $timeinterval =   ($tmptimeinterval/$maxPoints);
      $distanceinterval = 0.3;
    }
    else
    {
      $timeinterval =   $time_interval1;
      $distanceinterval = 0.02;
    }
  } */
  
  //echo "<br>distint=".$distanceinterval." ,time_interval=".$time_interval;
  $timeinterval =   5;
  $distanceinterval = 0.02;

  //if($mode==2)
  //{
  	//echo "<br>xmltowrite=:".$xmltowrite;
    $fh = fopen($xmltowrite, 'w') or die("can't open file 3"); // new
  	fwrite($fh, "<t1>");  
  	fclose($fh);

  	//$i=0;
    for($i=0;$i<sizeof($vserial2);$i++)
  	{  	
      //echo   "<br>t1".' '.$i;
      getTrack($vserial2[$i],$vname[$i],$startdate,$enddate,$xmltowrite,$timeinterval,$distanceinterval);
      //echo   "t2".' '.$i;
  	}    	
  	$fh = fopen($xmltowrite, 'a') or die("can't open file 4"); //append
  	fwrite($fh, "\n</t1>");  
  	fclose($fh);
  //}


function get_All_Dates($fromDate, $toDate, &$userdates)
{
	$dateMonthYearArr = array();
	$fromDateTS = strtotime($fromDate);
	$toDateTS = strtotime($toDate);

	for ($currentDateTS = $fromDateTS; $currentDateTS <= $toDateTS; $currentDateTS += (60 * 60 * 24)) {
		// use date() and $currentDateTS to format the dates in between
		$currentDateStr = date("Y-m-d",$currentDateTS);
		$dateMonthYearArr[] = $currentDateStr;
	//print $currentDateStr.”<br />”;
	}

	$userdates = $dateMonthYearArr;
}


function getTrack($vehicle_serial,$vname,$startdate,$enddate,$xmltowrite,$timeinterval,$distanceinterval)
{
	//echo "In Track";
	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
	global $old_xml_date;
  $fix_tmp = 1;
	$xml_date_latest="1900-00-00 00:00:00";
	$CurrentLat = 0.0;
	$CurrentLong = 0.0;
	$LastLat = 0.0;
	$LastLong = 0.0;
	$firstData = 0;
	$distance =0.0;
	$linetowrite="";
	$date_1 = explode(" ",$startdate);
	$date_2 = explode(" ",$enddate);

	$datefrom = $date_1[0];
	$dateto = $date_2[0];
	$timefrom = $date_1[1];
	$timeto = $date_2[1];

	get_All_Dates($datefrom, $dateto, &$userdates);

	//date_default_timezone_set("Asia/Calcutta");
	$current_datetime = date("Y-m-d H:i:s");
	$current_date = date("Y-m-d");
	//print "<br>CurrentDate=".$current_date;
	$date_size = sizeof($userdates);

	$fh = fopen($xmltowrite, 'a') or die("can't open file 6"); //append

	for($i=0;$i<=($date_size-1);$i++)
	{
		//if($userdates[$i] == $current_date)
		//{	
    $xml_current = "../../../xml_vts/xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";	
    		
    if (file_exists($xml_current))      
    {    		
			//echo "in else";
			$xml_file = $xml_current;
			$CurrentFile = 1;
		}		
		else
		{
			$xml_file = "../../../sorted_xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";
			$CurrentFile = 0;
		}

		//echo "<br>xml_file=".$xml_file;
		
    if (file_exists($xml_file)) 
		{			
		  $t=time();
      //$current_datetime1 = date("Y_m_d_H_i_s");      
      //$xml_original_tmp = "xml_tmp/original_xml/tmp_".$current_datetime1.".xml";
      $xml_original_tmp = "../../../xml_tmp/original_xml/tmp_".$t."_".$i.".xml";
      //$xml_log = "xml_tmp/filtered_xml/tmp_".$current_datetime1.".xml";
      //echo "<br>xml_file=".$xml_file." <br>tmpxml=".$xml_original_tmp."<br>";
			//copy($xml_file,$xml_original_tmp); 
									      
      if($CurrentFile == 0)
			{
				//echo "<br>ONE<br>";
        copy($xml_file,$xml_original_tmp);
			}
			else
			{
				//echo "<br>TWO<br>";
        //$xml_unsorted = "xml_tmp/unsorted_xml/tmp".$current_datetime1."_unsorted.xml";
        $xml_unsorted = "../../../xml_tmp/unsorted_xml/tmp_".$t."_".$i."_unsorted.xml";
				//echo  "<br>".$xml_file." <br>".$xml_unsorted;				
        copy($xml_file,$xml_unsorted);        // MAKE UNSORTED TMP FILE
        SortFile($xml_unsorted, $xml_original_tmp,$userdates[$i]);    // SORT FILE
				unlink($xml_unsorted);                // DELETE UNSORTED TMP FILE
			}
      
      $f=0;      
      
      $xml = @fopen($xml_original_tmp, "r") or $fexist = 0;
      $total_lines = count(file($xml_original_tmp));  
      //$xmllog = fopen($xml_log, "w") or $fexist1 = 0;
      $logcnt=0;
      $DataComplete=false;
      
      
      if (file_exists($xml_original_tmp)) 
      {      
		set_master_variable($userdates[$i]);
        while(!feof($xml))          // WHILE LINE != NULL
  			{
  				//echo fgets($file). "<br />";
  				$DataValid = 0;
          $line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE
  				//echo "0:line:".$line;
  				
  				if(strlen($line)>20)
  				{
  				 // $linetmp =  $line;
          }
  				
  				$linetolog =  $logcnt." ".$line;
  				$logcnt++;
  				//fwrite($xmllog, $linetolog);
  
  				if(strpos($line,''.$vc.'="1"'))     // RETURN FALSE IF NOT FOUND
  				{
  					$fix_tmp = 1;
  				}
                
  				else if(strpos($line,''.$vc.'="0"'))
  				{
  					$fix_tmp = 0;
  				}
  				
  				else
  				{
  				  $fix_tmp = 2;
          }
  				
          if( (preg_match('/'.$vd.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) &&  (preg_match('/'.$ve.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
          { 
            $lat_value = explode('=',$lat_match[0]);
            $lng_value = explode('=',$lng_match[0]);
            //echo " lat_value=".$lat_value[1];         
            if( (strlen($lat_value[1])>5) && ($lat_value[1]!="-") && (strlen($lng_value[1])>5) && ($lng_value[1]!="-") )
            {
              $DataValid = 1;
            }         
          }
          $linetmp = "";
          //if( (substr($line, 0,1) == '<') && (substr( (strlen($line)-1), 0,1) == '>') && ($fix_tmp==1) && ($f>0) && ($f<$total_lines-1) )        
          if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && (($fix_tmp==1) || ($fix_tmp == 2))&& ($DataValid == 1) )        
  				{
  					//preg_match('/\d+-\d+-\d+ \d+:\d+:\d+/', $line, $str3tmp);    // EXTRACT DATE FROM LINE
  					//echo "<br>str3tmp[0]=".$str3tmp[0];
  					//$xml_date_current = $str3tmp[0];
  					$linetmp =  $line;
  					//echo "linetmp=".$linetmp;
  					$status = preg_match('/'.$vh.'="[^"]+/', $line, $datetime_tmp);
            $datetime_tmp1 = explode("=",$datetime_tmp[0]);
            $datetime = preg_replace('/"/', '', $datetime_tmp1[1]);
  					$xml_date_current = $datetime;										
  				}
  				
          //echo "Final0=".$xml_date_current." datavalid=".$DataValid;
          
          if (($xml_date_current!=null)  && ($DataValid==1) && (($fix_tmp==1) || ($fix_tmp == 2)))
  				{
  				  $linetolog = $xml_date_current.' '.$firstData."\n";
  				  //fwrite($xmllog, $linetolog);
  					//echo "Final1";
            $CurrentLat = $lat_value[1] ;
  					$CurrentLong = $lng_value[1];
  					
  					if( ($xml_date_current >= $startdate && $xml_date_current <= $enddate) && ($xml_date_current!="-"))
  					{
  						//echo "Final2";
              if($firstData==1)
  						{
  						  if($minlat>$CurrentLat)
  						  {
  						    $minlat = $CurrentLat;
                }
                if($maxlat<$CurrentLat)
  						  {
  						    $maxlat = $CurrentLat;
                }
                
                if($minlong>$CurrentLong)
  						  {
  						    $minlong = $CurrentLong;
                }
                if($maxlong<$CurrentLong)
  						  {
  						    $maxlong = $CurrentLong;
                }
                
  							$tmp1lat = round(substr($CurrentLat,1,(strlen($CurrentLat)-3)),4);
  							$tmp2lat = round(substr($LastLat,1,(strlen($LastLat)-3)),4);
  							$tmp1lng = round(substr($CurrentLong,1,(strlen($CurrentLong)-3)),4);
  							$tmp2lng = round(substr($LastLong,1,(strlen($LastLong)-3)),4);
  							
  						  //echo  "Coord: ".$tmp1lat.' '.$tmp2lat.' '.$tmp1lng.' '.$tmp2lng.'<BR>';             							
                calculate_distance($tmp1lat,$tmp2lat,$tmp1lng,$tmp2lng,&$distance);
                
  							$linetolog = $CurrentLat.','.$CurrentLong.','.$LastLat.','.$LastLong.','.$distance.','.$xml_date_current.','.$xml_date_last.','.(strtotime($xml_date_current)-strtotime($xml_date_last)).','.$timeinterval.','.$distanceinterval.','.$enddate.','.$startdate."\n";
  				      //fwrite($xmllog, $linetolog);
  						}
  						if ( (((((strtotime($xml_date_current)-strtotime($xml_date_last))>$timeinterval) && ($distance>=$distanceinterval)) || ($firstData==0)) && 
  							(($xml_date_current<=$enddate) && ($xml_date_current>=$startdate))) || ($f==$total_lines-2) )
  						{
  						  //echo "please wait..";
                $linetolog = "Data Written\n";
  				      //fwrite($xmllog, $linetolog);
  							//echo "<br>FinalWrite";
                $xml_date_last = $xml_date_current;
  							$LastLat =$CurrentLat;
  							$LastLong =$CurrentLong;
  							$line = substr($line, 0, -3);   // REMOVE LAST TWO /> CHARARCTER
  							$finalDistance = $finalDistance + $distance;
  							//echo "<br>finalDistance=".$finalDistance;
  							if($userdates[$i]<$old_xml_date)
									{
										//echo "in if 111";
										$line=str_replace("marker","x",$line);
										$line=str_replace("msgtype=","a=",$line);
										$line=str_replace("vehicleserial=","v=",$line);
										$line=str_replace("ver=","b=",$line);
										$line=str_replace("fix=","c=",$line);
										$line=str_replace("lat=","d=",$line);
										$line=str_replace("lng=","e=",$line);
										$line=str_replace("speed=","f=",$line);
										$line=str_replace("sts=","g=",$line);
										$line=str_replace("datetime=","h=",$line);
										$line=str_replace("io1=","i=",$line);
										$line=str_replace("io2=","j=",$line);
										$line=str_replace("io3=","k=",$line);
										$line=str_replace("io4=","l=",$line);
										$line=str_replace("io5=","m=",$line);
										$line=str_replace("io6=","n=",$line);
										$line=str_replace("io7=","o=",$line);
										$line=str_replace("io8=","p=",$line);
										$line=str_replace("sig_str=","q=",$line);
										$line=str_replace("sup_v=","r=",$line);
										
										$linetowrite = "\n".$line.' w="'.$vname.'"/>';
									}
									else
									{
										$linetowrite = "\n".$line.' v="'.$vehicle_serial.'" w="'.$vname.'"/>';
									}
  							//$linetowrite = "\n".$line.' cumdist="'.$finalDistance.'"./>'; // for distance       // ADD DISTANCE
  							//$linetowrite = "\n".$line.'/>';
  							//echo "<textarea>".$linetowrite."</textarea>";
                $firstData = 1;
  
  							//$fh = fopen($xmltowrite, 'a') or die("can't open file 4"); //append
                fwrite($fh, $linetowrite);  
  						}
  					}
  					else if(($xml_date_current > $enddate) && ($xml_date_current!="-") && ($DataValid==1) )
  					{
  					 //echo "in first";
             $linetolog = "Data Written1\n";
  				   //fwrite($xmllog, $linetolog);
  				   $line = substr($line, 0, -3);   // REMOVE LAST TWO /> CHARARCTER
  					 //$linetowrite = "\n".$line.' distance="'.round($finalDistance,2).'"/>'; // for distance       // ADD DISTANCE
  					// $linetowrite = "\n".$line.'/>'; // for distance       // ADD DISTANCE
  					 if($userdates[$i]<$old_xml_date)
									{
										//echo "in if 111";
										$line=str_replace("marker","x",$line);
										$line=str_replace("msgtype=","a=",$line);
										$line=str_replace("vehicleserial=","v=",$line);
										$line=str_replace("ver=","b=",$line);
										$line=str_replace("fix=","c=",$line);
										$line=str_replace("lat=","d=",$line);
										$line=str_replace("lng=","e=",$line);
										$line=str_replace("speed=","f=",$line);
										$line=str_replace("sts=","g=",$line);
										$line=str_replace("datetime=","h=",$line);
										$line=str_replace("io1=","i=",$line);
										$line=str_replace("io2=","j=",$line);
										$line=str_replace("io3=","k=",$line);
										$line=str_replace("io4=","l=",$line);
										$line=str_replace("io5=","m=",$line);
										$line=str_replace("io6=","n=",$line);
										$line=str_replace("io7=","o=",$line);
										$line=str_replace("io8=","p=",$line);
										$line=str_replace("sig_str=","q=",$line);
										$line=str_replace("sup_v=","r=",$line);
										
										$linetowrite = "\n".$line.' w="'.$vname.'"/>';
									}
									else
									{
										$linetowrite = "\n".$line.' v="'.$vehicle_serial.'" w="'.$vname.'"/>';
									}
  					 fwrite($fh, $linetowrite);
  					 $DataComplete=true;
  					 break;
            }
  				}
  				$f++;
  			}   // while closed
      } // if original_tmp exist closed 
      
      if($DataComplete==false)
      {
		    //echo "in false";
        if( (preg_match('/lat="\d+.\d+[a-zA-Z0-9]\"/', $linetmp, $lat_match)) &&  (preg_match('/lng="\d+.\d+[a-zA-Z0-9]\"/', $linetmp, $lng_match)) )
        { 
          $lat_value = explode('=',$lat_match[0]);
          $lng_value = explode('=',$lng_match[0]);
          //echo " lat_value=".$lat_value[1];         
          if( (strlen($lat_value[1])>5) && ($lat_value[1]!="-") && (strlen($lng_value[1])>5) && ($lng_value[1]!="-") )
          {
            $DataValid = 1;
          } 
          else
          {
            $DataValid = 0;
          }
        }
    		else
    		{
    		 $DataValid = 0;
    		}
		
    		if($DataValid == 1)
    		{
    			$linetolog = "Data Written2\n";
    			//fwrite($xmllog, $linetolog);
    			//echo "linetmp=".$linetmp;
          $line = substr($linetmp, 0, -3);   // REMOVE LAST TWO /> CHARARCTER
    			//$linetowrite = "\n".$line.' distance="'.round($finalDistance,2).'"/>'; // for distance       // ADD DISTANCE
    			//$linetowrite = "\n".$line.'/>'; // for distance       // ADD DISTANCE
				if($userdates[$i]<$old_xml_date)
									{
										//echo "in if 111";
										$line=str_replace("marker","x",$line);
										$line=str_replace("msgtype=","a=",$line);
										$line=str_replace("vehicleserial=","v=",$line);
										$line=str_replace("ver=","b=",$line);
										$line=str_replace("fix=","c=",$line);
										$line=str_replace("lat=","d=",$line);
										$line=str_replace("lng=","e=",$line);
										$line=str_replace("speed=","f=",$line);
										$line=str_replace("sts=","g=",$line);
										$line=str_replace("datetime=","h=",$line);
										$line=str_replace("io1=","i=",$line);
										$line=str_replace("io2=","j=",$line);
										$line=str_replace("io3=","k=",$line);
										$line=str_replace("io4=","l=",$line);
										$line=str_replace("io5=","m=",$line);
										$line=str_replace("io6=","n=",$line);
										$line=str_replace("io7=","o=",$line);
										$line=str_replace("io8=","p=",$line);
										$line=str_replace("sig_str=","q=",$line);
										$line=str_replace("sup_v=","r=",$line);
										
										$linetowrite = "\n".$line.' w="'.$vname.'"/>';
									}
									else
									{
										$linetowrite = "\n".$line.' v="'.$vehicle_serial.'" w="'.$vname.'"/>';
									}
    			//$linetowrite = "\n".$line.'"/>'; // for distance       // ADD DISTANCE
    			fwrite($fh, $linetowrite);
    		}
      }         
      fclose($xml);            
			unlink($xml_original_tmp);
		} // if (file_exists closed
	}  // for closed 
	
	//echo "Test1";
	fclose($fh);
	//fclose($xmllog);
}

?>
