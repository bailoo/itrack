<?php
  include_once('util_php_mysql_connectivity.php');
  include_once('calculate_distance.php');
	
  //echo "getfiltered";
  $xmltowrite = $_REQUEST['xml_file']; 
	$mode = $_REQUEST['mode'];
	$vserial = $_REQUEST['vserial'];
	$startdate = $_REQUEST['startdate'];
	$enddate = $_REQUEST['enddate']; 
	$time_interval1 = $_REQUEST['time_interval'];  
	
	set_time_limit(300);
	
	//$vserial = explode(',',$vserial1) ;

  $vname1 ="";
  
    $query = "SELECT vehicle_name FROM vehicle WHERE ".
    " vehicle_id=(SELECT vehicle_id FROM vehicle_assignment ".
    "WHERE device_imei_no='$vserial' AND status=1) AND status=1";
    
    echo $query;
    $result = mysql_query($query, $DbConnection);
    $row = mysql_fetch_object($result);
    $vname = $row->vehicle_name;
    
    if($i==0)
      $vname1 = $vname1.$vname;
    else
      $vname1 = $vname1.":".$vname;
    //echo "<br>vname1=".$vname[$i];
 
  include_once("sort_xml.php");	
  //echo   $xmltowrite.' '.$mode.' '.$vserial1.' '.$startdate.' '.$enddate.' '.$time_interval1;
	
  $minlat = 180; 
  $maxlat = -180;
  $minlong = 180;
  $maxlong = -180; 
	
	$maxPoints = 1000;
	$file_exist = 0;
	
	$tmptimeinterval = strtotime($enddate) - strtotime($startdate);
	
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
  } 
  
  //echo  "2".$timeinterval.' '.$distanceinterval.'<BR>';
  //echo "<br>Mode=".$mode;
  if($mode==1)
  {
  	//echo $xmltowrite."<br>";
    $fh = fopen($xmltowrite, 'w') or die("can't open file 1"); // new
  	fwrite($fh, "<t1>");  
  	fclose($fh);
  
  	//echo "size=".sizeof($vserial);  	
    //for($i=0;$i<sizeof($vserial);$i++)
  	//{  	
      //echo   "t1".' '.$i;
      //echo "<br>vname2=".$vname[$i];
      getLastPosition($vserial,$vname,$startdate,$enddate,$xmltowrite);
      //echo   "t2".' '.$i;
  	//}  
  	$fh = fopen($xmltowrite, 'a') or die("can't open file 2"); //append
  	fwrite($fh, "\n<a1 datetime=\"unknown\"/>");
  	fwrite($fh, "\n</t1>");  
  	fclose($fh);
  }

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

function getLastPosition($vehicle_serial,$vname,$startdate,$enddate,$xmltowrite)
{
	//echo "<br>".$vehicle_serial.", ,".$vname." ,".$startdate.", ".$enddate.", ".$xmltowrite."<br>";	
  $fix_tmp = 1;
	$xml_date_latest="1900-00-00 00:00:00";
	$linetowrite="";
	$dataValid = 0;
	$date_1 = explode(" ",$startdate);
	$date_2 = explode(" ",$enddate);

	$datefrom = $date_1[0];
	$dateto = $date_2[0];
	$timefrom = $date_1[1];
	$timeto = $date_2[1];

	get_All_Dates($datefrom, $dateto, &$userdates);	
	//echo "3:datefrom=".$datefrom.' '."dateto=".$dateto.' '."userdates=".$userdates[0].'<BR>';

	//date_default_timezone_set("Asia/Calcutta");
	$current_datetime = date("Y-m-d H:i:s");
	$current_date = date("Y-m-d");
	//print "<br>CurrentDate=".$current_date;
	$date_size = sizeof($userdates);
	
	//echo "4:date_size=".$date_size.'<BR>';

	for($i=($date_size-1);$i>=0;$i--)
	{		
    //if($userdates[$i] == $current_date)
		//{	
    $xml_current = "../../../xml_vts/xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";	
    		
    if (file_exists($xml_current))      
    {    		
			//echo "in else";
			$xml_file = $xml_current;						
		}		
		else
		{
			$xml_file = "../../../sorted_xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";	
		}
		//echo "5:xml_file=".$xml_file;		
    if (file_exists($xml_file)) 
		{
		  //echo "file exists1";
      $t=time();
      //$current_datetime1 = date("Y_m_d_H_i_s");     
      //$xml_original_tmp = "xml_tmp/original_xml/".$current_datetime1.".xml";
      //$xml_original_tmp = "../../../xml_tmp/original_xml/tmp_".$t."_".$i.".xml";
      $xml_original_tmp = "../../../xml_tmp/original_xml/tmp_".$vehicle_serial."_".$t."_".$i.".xml";
      //echo "<br>xml_file=".$xml_file." <br>tmpxml=".$xml_original_tmp."<br>";
			copy($xml_file,$xml_original_tmp); 

			//echo "<br>orginal_tmp=".$xml_original_tmp."<br>";
			$fexist =1;
      $xml = fopen($xml_original_tmp, "r") or $fexist = 0;   
     // echo " fexist=".$fexist."<br>";          
      //fclose($file);  
      //$tmpcnt=0;               
      $format = 2; 
      
      
      if (file_exists($xml_original_tmp)) 
      {      
        //echo "fileexist2";
        while(!feof($xml))          // WHILE LINE != NULL
  			{								
          $DataValid = 0;
          $line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE
  				//echo "line1: ".$line;
                  
  				if(strpos($line,'fix="1"'))     // RETURN FALSE IF NOT FOUND
  				{
  					$fix_tmp = 1;
  				}
                
  				else if(strpos($line,'fix="0"'))
  				{
  					$fix_tmp = 0;
  				}
  				
          if ((preg_match('/lat="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match) ) && (preg_match('/lng="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
          { 
            $lat_value = explode('=',$lat_match[0]);
            $lng_value = explode('=',$lng_match[0]);
            //echo "<br>lat=".$lat_value[1]." lng=".$lng_value[1];
            
            if( (strlen($lat_value[1])>5) && ($lat_value[1]!="-") && (strlen($lng_value[1])>5) && ($lng_value[1]!="-") )
            {
              $DataValid = 1;
            }
          }
                  
          if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($fix_tmp==1) && ($DataValid == 1) )
  				{					
            //preg_match('/\d+-\d+-\d+ \d+:\d+:\d+/', $line, $str3tmp);    // EXTRACT DATE FROM LINE
  					//$xml_date_current = $str3tmp[0];					
  					
  					$status = preg_match('/datetime="[^"]+/', $line, $datetime_tmp);
            $datetime_tmp1 = explode("=",$datetime_tmp[0]);
            $datetime = preg_replace('/"/', '', $datetime_tmp1[1]);
  					$xml_date_current = $datetime;					
  					//echo "0:xml_date_current=".$xml_date_current.'<BR>';	
  				}
  				if ($xml_date_current!=null)
  				{	
  					//echo "1:xml_date_current=".$xml_date_current.' '."startdate=".$startdate.' '."enddate=".$enddate.'<BR>';
            if( ($xml_date_current >= $startdate && $xml_date_current <= $enddate) && ($xml_date_current!="-") )
  					{
  					
  					  //echo "2:xml_date_latest=".$xml_date_latest.'<BR>';
              if (($xml_date_current>$xml_date_latest) && 
  							(($xml_date_current<=$enddate) && ($xml_date_current>=$startdate)))
  						{
  						
  							//echo "linetowrite".$vname;
                $xml_date_latest = $xml_date_current;
  							$line = substr($line, 0, -3);
  							$linetowrite = "\n".$line.' vname="'.$vname.'"/>';
  							//echo "<br>vname2=".$vname;
  							//echo "line:".$linetowrite;
  						}
  					}
  				}
  			} // while closed
  		}  // if original_tmp exist closed
        
			if(strlen($linetowrite)!=0)
			{
        //echo "<br>".$xmltowrite."<br>";				
        $fh = fopen($xmltowrite, 'a') or die("can't open file 5"); //append
				fwrite($fh, $linetowrite);  
				fclose($fh);
				fclose($xml);
        unlink($xml_original_tmp);
				break;
			}
			fclose($xml);
			unlink($xml_original_tmp);
		} 
	} // Date closed
}

  
/////////////////////////////////////////	

?>