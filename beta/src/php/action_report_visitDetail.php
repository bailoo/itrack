<?php
include_once("main_vehicle_information_1.php");
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
$root=$_SESSION["root"];

set_time_limit(380);

include_once("get_all_dates_between.php");
include_once("sort_person_xml.php");
include_once("calculate_distance.php");
include_once("report_title.php");
include_once("read_filtered_xml.php");
include_once("get_location.php");
include_once("get_location_cellname.php");
include_once("util.hr_min_sec.php");
       

$DEBUG = 0;

$device_str = $_POST['vehicleserial'];
//echo "<br>devicestr=".$device_str;
$pserial = explode(':',$device_str);
$psize=count($pserial);

$date1 = $_POST['start_date'];
$date2 = $_POST['end_date'];

$date1 = str_replace("/","-",$date1);
$date2 = str_replace("/","-",$date2);

$user_interval = $_POST['user_interval'];
//echo "psize=".$psize;
////////////////////////////////////////////////////////////////////////////

function calculate_mileage($lat1, $lat2, $lon1, $lon2, &$distance) 
{	
	// used internally, this function actually performs that calculation to
	// determine the mileage between 2 points defined by lattitude and
	// longitude coordinates.  This calculation is based on the code found
	// at http://www.cryptnet.net/fsp/zipdy/

	// Convert lattitude/longitude (degrees) to radians for calculations
	$lat1 = deg2rad($lat1);
	$lon1 = deg2rad($lon1);

	$lat2 = deg2rad($lat2);
	$lon2 = deg2rad($lon2);

	// Find the deltas
	$delta_lat = $lat2 - $lat1;
	$delta_lon = $lon2 - $lon1;

	// Find the Great Circle distance
	$temp = pow(sin($delta_lat/2.0),2) + cos($lat1) * cos($lat2) * pow(sin($delta_lon/2.0),2);
	$distance = 3956 * 2 * atan2(sqrt($temp),sqrt(1-$temp));

	//convert into km
	$distance = $distance*1.609344;
	//echo "dist=".$distance;
	// return $distance;
} 
/////////////////////////////////////XML CODE STARTS//////////////////////////////////////

if($psize>0)
{
  /*for($i=0;$i<$vsize;$i++)
  {
    $query = "SELECT vehicle_name FROM vehicle WHERE ".
    " vehicle_id=(SELECT vehicle_id FROM vehicle_assignment ".
    "WHERE device_imei_no='$vserial[$i]' AND status=1) AND status=1";
    //echo $query;
    $result = mysql_query($query, $DbConnection);
    $row = mysql_fetch_object($result);
    $vname[$i] = $row->vehicle_name;
  }*/
  
  for($i=0;$i<$psize;$i++)
  {
    //$query="SELECT person_id, person_name, mobile_no FROM person WHERE imei_no = '$pserial[$i]' AND status=1";
    /*$query = "SELECT vehicle_name,vehicle_number FROM vehicle WHERE ".
    " vehicle_id IN(SELECT vehicle_id FROM vehicle_assignment ".
    "WHERE device_imei_no='$pserial[$i]' AND status=1) AND status=1";
    //echo $query;
    //if($_SERVER["HTTP_X_FORWARDED_FOR"] == "172.26.48.90") 
      //echo $query;
      
    $result = mysql_query($query, $DbConnection);
    $row = mysql_fetch_object($result);
    $pname[$i]=$row->vehicle_name;
    $pmobile[$i]=$row->vehicle_number;*/
    $vehicle_info=get_vehicle_info($root,$vserial[$i]);
    $vehicle_detail_local=explode(",",$vehicle_info);	
    $pname[$i] = $vehicle_detail_local[0];
    $pmobile[$i] = $vehicle_detail_local[2];
  } 
    
  $current_dt = date("Y_m_d_H_i_s");	
  $xmltowrite = "../../../xml_tmp/filtered_xml/tmp_".$current_dt.".xml";
  write_visit_report_xml($pserial, $pname,$pmobile, $date1, $date2, $user_interval, $xmltowrite);
}

function write_visit_report_xml($pserial, $pname, $pmobile, $startdate, $enddate, $user_interval, $xmltowrite)
{
  $maxPoints = 1000;
	$file_exist = 0;

	$fh = fopen($xmltowrite, 'w') or die("can't open file 3"); // new
	fwrite($fh, "<t1>");  
	fclose($fh);

	//$i=0;
	for($i=0;$i<sizeof($pserial);$i++)
	{  	
     //echo   "<br>vserial[i] =".$vserial[$i];
     get_visit_xml_data($pserial[$i], $pname[$i], $pmobile[$i], $startdate, $enddate, $user_interval, $xmltowrite);
    //echo   "t2".' '.$i;
	}  
 
	$fh = fopen($xmltowrite, 'a') or die("can't open file 4"); //append
	fwrite($fh, "\n</t1>");  
	fclose($fh);
}


function get_visit_xml_data($person_serial, $pname, $pmobile, $startdate, $enddate, $user_interval, $xmltowrite)
{
	$fix_tmp = 1;
	$xml_date_latest="1900-00-00 00:00:00";
	$CurrentLat = 0.0;
	$CurrentLong = 0.0;
	$LastLat = 0.0;
	$LastLong = 0.0;
	$firstData = 0;
	$distance =0.0;
	$linetowrite="";
	$firstdata_flag =0;
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

	$count = 0;
	$j = 0;
	
	$avg_speed = null;
	$max_speed = null;
	
	$total_avg_speed = null;
	$total_max_speed = null;
	
  									
  for($i=0;$i<=($date_size-1);$i++)
	{
		//if($userdates[$i] == $current_date)
		//{	
    $xml_current = "../../../xml_vts/xml_data/".$userdates[$i]."/".$person_serial.".xml";	
    		
    if (file_exists($xml_current))      
    {		    		
			//echo "in else";
			$xml_file = $xml_current;
			$CurrentFile = 1;
		}		
		else
		{
			$xml_file = "../../../sorted_xml_data/".$userdates[$i]."/".$person_serial.".xml";
			$CurrentFile = 0;
		}
		
		//echo "<br>xml in get_halt_xml_data =".$xml_file;	
    	
    if (file_exists($xml_file)) 
		{			
      $t=time();
      $xml_original_tmp = "../../../xml_tmp/original_xml/tmp_".$person_serial."_".$t."_".$i.".xml";

      $xml_log = "../../../xml_tmp/filtered_xml/tmp_".$current_datetime1.".xml";
      //echo "<br>xml_file=".$xml_file." <br>tmpxml=".$xml_original_tmp."<br>";
									      
      if($CurrentFile == 0)
			{
				//echo "<br>ONE<br>";
        copy($xml_file,$xml_original_tmp);
			}
			else
			{
				//echo "<br>TWO<br>";
        $xml_unsorted = "../../../xml_tmp/unsorted_xml/tmp_".$person_serial."_".$t."_".$i."_unsorted.xml";
				//echo  "<br>".$xml_file." <br>".$xml_unsorted."<br><br>";
				        
        copy($xml_file,$xml_unsorted);        // MAKE UNSORTED TMP FILE
        SortFile($xml_unsorted, $xml_original_tmp);    // SORT FILE
				unlink($xml_unsorted);                // DELETE UNSORTED TMP FILE
			}
      
      $total_lines = count(file($xml_original_tmp));
      //echo "<br>Total lines orig=".$total_lines;
      
      $xml = @fopen($xml_original_tmp, "r") or $fexist = 0;  
      //$xmllog = fopen($xml_log, "w") or $fexist1 = 0;
      $logcnt=0;
      $DataComplete=false;
                  
      $personserial_tmp=null;
      $format =2;
      
      while(!feof($xml))          // WHILE LINE != NULL
			{
				$DataValid = 0;
        //echo fgets($file). "<br />";
				$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE			
				
				if(strlen($line)>20)
				{
				  $linetmp =  $line;
        }
				
				$linetolog =  $logcnt." ".$line;
				$logcnt++;
				//fwrite($xmllog, $linetolog);

				if(strpos($line,'fix="1"'))     // RETURN FALSE IF NOT FOUND
				{
					$format = 1;
          $fix_tmp = 1;
				}
              
				else if(strpos($line,'fix="0"'))
				{
				  $format = 1;
					$fix_tmp = 0;
				}		
				
      if( (strlen($line)>15) )
        {
          $DataValid = 1;
        }
                
        //if( (substr($line, 0,1) == '<') && (substr( (strlen($line)-1), 0,1) == '>') && ($fix_tmp==1) && ($f>0) && ($f<$total_lines-1) )        
        if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($DataValid == 1) )   // FIX_TMP =1 COMES IN BOTH CASE     
				{
					//preg_match('/\d+-\d+-\d+ \d+:\d+:\d+/', $line, $str3tmp);    // EXTRACT DATE FROM LINE
					//echo "<br>str3tmp[0]=".$str3tmp[0];
					$status = preg_match('/datetime="[^"]+/', $line, $datetime_tmp);
					$datetime_tmp1 = explode("=",$datetime_tmp[0]);
          $datetime = preg_replace('/"/', '', $datetime_tmp1[1]);	
					$xml_date = $datetime;
				}				
        //echo "Final0=".$xml_date." datavalid=".$DataValid;
        
        if($xml_date!=null)
				{				  
          //echo "<br>".$xml_date.",".$startdate.",".$enddate.",".$DataValid;
          //$lat = $lat_value[1] ;
					//$lng = $lng_value[1];
					
					if( ($xml_date >= $startdate && $xml_date <= $enddate) && ($xml_date!="-") && ($DataValid==1) )
					{							           	
            //echo "<br>One";             
            $status = preg_match('/vehicleserial="[^" ]+/', $line, $personserial_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }
            //echo "<textarea>".$line."</textarea>"; 
            //$line = '<marker msgtype="NORMAL" vehicleserial="354776033544005" vehiclename="SJ DKL-4005" ver="v1.34" fix="1" lat="26.60652N" lng="80.20758E" alt="123.0" speed="0.000000" datetime="2011-01-25 03:16:23" fuel="-" vehicletype="Heavy" no_of_sat="09" cellname="" distance="0.0" io1="7" io2="6" io3="387" io4="10" io5="6" io6="6" io7="4" io8="2" sig_str="0" sup_v="0.000000" speed_a="0" geo_in_a="0" geo_out_a="0" stop_a="0" move_a="0" lowv_a="0"/>';  
            /*$status = preg_match('/vehiclename="[^"]+/', $line, $vehiclename_tmp);          
            //echo "<br>vname=".$vehiclename_tmp[0];
            if($status==0)
            {
              continue;
            }*/
            
            $status = preg_match('/lat="[^" ]+/', $line, $lat_tmp);
            
            if($status==0)
            {
              continue;               
            }
            
            //if($_SERVER["HTTP_X_FORWARDED_FOR"] == "172.26.48.90")
              //echo "<br>status1";            
            //echo "test6".'<BR>';
            $status = preg_match('/lng="[^" ]+/', $line, $lng_tmp);
            if($status==0)
            {
              continue;
            }
            
            //if($_SERVER["HTTP_X_FORWARDED_FOR"] == "172.26.48.90")
              //echo "<br>status2";
              
            //echo "test7".'<BR>';
            $status = preg_match('/datetime="[^"]+/', $line, $datetime_tmp);
            if($status==0)
            {
              continue;
            } 
            
           // if($_SERVER["HTTP_X_FORWARDED_FOR"] == "172.26.48.90")
              //echo "<br>status3";           
           //echo "test8".'<BR>';
            $status = preg_match('/cellname="[^"]+/', $line, $cellname_tmp);
            if($status==0)
            {
              continue;
            }
            
            //if($_SERVER["HTTP_X_FORWARDED_FOR"] == "172.26.48.90")
              //echo "<br>status4";            
            /*
            //echo "test8".'<BR>';
            $status = preg_match('/mobilestatus="[^" ]+/', $line, $mobilestatus_tmp);
            if($status==0)
            {
              continue;
            }
            
            //echo "test9".'<BR>';
            $status = preg_match('/cellid="[^" ]+/', $line, $cellid_tmp);
            if($status==0)
            {
              continue;
            }
            
            //echo "test10".'<BR>';
            $status = preg_match('/mcc="[^" ]+/', $line, $mcc_tmp);
            if($status==0)
            {
              continue;
            }
            
            //echo "test11".'<BR>';
            $status = preg_match('/mnc="[^" ]+/', $line, $mnc_tmp);
            if($status==0)
            {
              continue;
            }
            
            //echo "test12".'<BR>';
            $status = preg_match('/lac="[^" ]+/', $line, $lac_tmp);
            if($status==0)
            {
              continue;
            }     
            */
            
            $tmp = explode("=",$personserial_tmp[0]);
            $pserial = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$lat_tmp[0]);
            $lat = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$lng_tmp[0]);
            $lng = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$datetime_tmp[0]);
            $datetime = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$cellname_tmp[0]);
            $cellname = preg_replace('/"/', '', $tmp[1]);
            
            /*
            $tmp = explode("=",$mobilestatus_tmp[0]);
            $mobilestatus = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$cellid_tmp[0]);
            $cellid = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$mcc_tmp[0]);
            $mcc = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$mnc_tmp[0]);
            $mnc = preg_replace('/"/', '', $tmp[1]);
            
            $tmp = explode("=",$lac_tmp[0]);
            $lac = preg_replace('/"/', '', $tmp[1]);
            */
                                      
            //echo "test4".'<BR>';
            /*$status = preg_match('/\d+-\d+-\d+ \d+:\d+:\d+/', $line, $datetime_tmp);
            if($status==0)
            {
              continue;
            }*/ 
                         
            //echo "<br>first=".$firstdata_flag;                                        
            
    				if($lat!="0.0" || $lng!="0.0")
    				{
              if($firstdata_flag==0)
      				{					
      					$firstdata_flag = 1;      						           
      							 
      					$lat1 = $lat;
      					$lng1 = $lng;
      					$datetime1 = $datetime;                 		           
              }
              else
              {              
      					$lat2 = $lat;      				        					
      					$lng2 = $lng;
                $datetime2 = $datetime;
                                
                calculate_distance($lat1, $lat2, $lng1, $lng2, &$distance);
                
                //if($_SERVER["HTTP_X_FORWARDED_FOR"] == "172.26.48.90")
                  //echo "<br>distance".$distance;
                //echo "<br>lat1=".$lat1." ,lng1=".$lng1." #lat2=".$lat2." ,lng2=".$lng2." dist=".$distance;
                if($distance > 0.5)
                {
        					/////////////////////////////
                  $stoptime = strtotime($datetime2); 
                  $starttime = strtotime($datetime1); 
                  
        					$visit_dur =  ($stoptime - $starttime)/3600;
        				
        					$halt_duration = round($visit_dur,2);										
        					$total_min = $halt_duration * 60;
        
        					$total_min1 = $total_min;
        					
         					$hr = (int)($total_min / 60);
        					$minutes = $total_min % 60;										
        
        					$hrs_min = $hr.".".$minutes;       					
                  /////////////////////////////                  
                  
                  if($pmobile=="")
                  $pmobile = "-";
                  
                  $visit_data = "\n< marker imei=\"".$pserial."\" pname=\"".$pname."\" pmobile=\"".$pmobile."\" datetime1=\"".$datetime1."\" datetime2=\"".$datetime2."\" duration=\"".$hrs_min."\" lat=\"".$lat."\" lng=\"".$lng."\" cellname=\"".$cellname."\"/>";						          						
                  //if($_SERVER["HTTP_X_FORWARDED_FOR"] == "172.26.48.90")
                  //echo "<br>visit_data=".$visit_data;
                  $linetowrite = $visit_data; // for distance       // ADD DISTANCE
                  fwrite($fh, $linetowrite);
                  
                  $lat1 = $lat2;
                  $lng1 = $lng2;
                  $datetime1 = $datetime2;                
                }                		              
              }
            } // outer if lat!="0.0" closed		
            
            //reassign time1
						/*$time1 = $datetime;
						$date_secs1 = strtotime($time1);
						$date_secs1 = (double)($date_secs1 + $interval);		
            $speed_arr = null;
            $j=0;	*/			
								///////////////////////
						
					} // $xml_date_current >= $startdate closed
				}   // if xml_date!null closed
			 $count++;
			 $j++;
      }   // while closed 
			
     fclose($xml);            
		 unlink($xml_original_tmp);
		} // if (file_exists closed
	}  // for closed 
	
	//echo "Test1";
	fclose($fh);
}

////////////////////////////// XML CODE ENDS ////////////////////////////////////////////

$m1=date('M',mktime(0,0,0,$month,1));
						
	echo '<center>';
	        
   $size_pserial = sizeof($pserial);

    report_title("Person Visit Report",$date1,$date2);
		echo'<div style="overflow: auto;height: 300px; width: 820px;" align="center">';
		
		//echo '<div align="center" style="width:100%;height:450px;overflow:auto;">';                
		//echo '<table border="0" width="100%"><tr><td>';        								
			
    ///////////////////  READ SPEED XML 	//////////////////////////////				                      
    $xml_path = $xmltowrite;
    //echo "<br>xml_path=".$xml_path;
		read_visitdetail_xml($xml_path, &$imei0, &$pname0, &$pmobile0, &$datetime1, &$datetime2, &$duration0, &$lat0, &$lng0, &$cellname0);
		//convert_in_two_dimension
    //echo "<br><br>size, pname=".sizeof($pname0);
		//////////////////////////////////////////////////////////////////////
    $j=-1;
    $k=0;
  	$endtable=0;
  	$tempCelldata="";
			             
    for($i=0;$i<sizeof($imei0);$i++)
		{								              
      //echo "<br>lat0[".$i."]=".$lat0[$i]." #lng[".$i."]=".$lng0[$i];
      
      if(($i===0) || (($i>0) && ($imei0[$i-1] != $imei0[$i])) )
      {
        $k=0;
                
        $j++;
        
        $sno = 1;
        $title="Visit Report : ".$pname0[$i]."(".$pmobile0[$i].")";
        $pname1[$j][$k] = $pname0[$i];
        
        echo'
        <br><table align="center">
        <tr>
        	<td class="text" align="center"><b>'.$title.'</b> <div style="height:8px;"></div></td>
        </tr>
        </table>
        <table border=1 width="95%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
        <tr bgcolor="grey">
				<td class="text" align="left"><b>SNo</b></td>
				<td class="text" align="left"><b>Start Time</b></td>
				<td class="text" align="left"><b>Stop Time</b></td>
				<td class="text" align="left"><b>Location</b></td>
        <td class="text" align="left"><b>Duration (hrs.min)</b></td>	
				<!--<td class="text" align="left"><b>GPS</b></td>-->	
        </tr>';  								
      }                                                                        		
			
      
			$alt="0";
		  
		  if($lat0[$i]=="0.0" || $lng0[$i]=="0.0"){
		   if($cellname0[$i]!=$tempCelldata)
		   {		   
			   get_location_cellname($cellname0[$i],&$cell_lat,&$cell_lng);
			   
         //echo "<br>celllat=".$cell_lat." len=".strlen($cell_lat);
         if( (strlen($cell_lat) > 5)  && (strlen($cell_lng) > 5) )
			   {
					//continue;
					get_location($cell_lat,$cell_lng,$alt,&$placename,$DbConnection);
					$tempCelldata=$cellname0[$i];
			   }
			   else
			   {
			     continue;
         }
			   //get_location($cell_lat,$cell_lng,$alt,&$placename,$DbConnection);
			   //$tempCelldata=$cellname0[$i];
		   }
		   
       $gps="NO";
           //continue;
		  }
		  else{
			   get_location($lat0[$i],$lng0[$i],$alt,&$placename,$DbConnection);    
			   $gps="YES";
		  }
           
      ///// CONVERT DATE TIME IN DD, MM, YYYY FORMA
      $datestr = explode(' ',$datetime1[$i]);
      $date_tmp = $datestr[0];
      $time_tmp = $datestr[1];
      
      $date_substr = explode('-',$date_tmp);
      $year = $date_substr[0];
      $month = $date_substr[1];
      $day = $date_substr[2];
      
      $display_datetime = $day."-".$month."-".$year." ".$time_tmp;
      $datetime1[$i] = $display_datetime;
      ///////////////////////////////////////////////
      
      ///// CONVERT DATE TIME IN DD, MM, YYYY FORMA
      $datestr = explode(' ',$datetime2[$i]);
      $date_tmp = $datestr[0];
      $time_tmp = $datestr[1];
      
      $date_substr = explode('-',$date_tmp);
      $year = $date_substr[0];
      $month = $date_substr[1];
      $day = $date_substr[2];
      
      $display_datetime = $day."-".$month."-".$year." ".$time_tmp;
      $datetime2[$i] = $display_datetime;
      ///////////////////////////////////////////////      
                 
      echo'<tr><td class="text" align="left" width="4%"><b>'.$sno.'</b></td>';        												
			echo'<td class="text" align="left">'.$datetime1[$i].'</td>';
      echo'<td class="text" align="left">'.$datetime2[$i].'</td>';      	
      echo'<td class="text" align="left">'.$placename.'</td>';
      echo'<td class="text" align="left">'.$duration0[$i].'</td>';		
			//echo'<td class="text" align="left"><b>'.$gps.'</b></td>';						
			echo'</tr>';	          		
			//echo "<br>arr_time1[$j][$k]main=".$arr_time1[$j][$k];
      
      $datetime_1[$j][$k] = $datetime1[$i];
      $datetime_2[$j][$k] = $datetime2[$i];
      $duration_1[$j][$k] = $duration0[$i];		
      $placename1[$j][$k] = $placename;										
      //$gps1[$j][$k] = $gps;
            			    				  				
		
		  //if( (($i>0) && ($imei0[$i+1] != $imei0[$i])) )
		  if( (($i>0) && ($imei0[$i+1] != $imei0[$i])) )
      {         
        $endtable=1;
        echo '</table>';        
        $no_of_data[$j] = $k;
        //echo "<br>NO data1=".$no_of_data[$j];      
			}  
			
      $k++;   
      $sno++;                                   							  		
   }
   
  if(!$endtable)
  {
    $no_of_data[$j] = $k-1;
    echo '</table>';
  }

   echo "</div>";     

  ///////// GET PDF   
	//echo'<form method = "post" action="src/php/report_getpdf_type4.php?size='.$psize.'" target="_blank">';
	echo'<form method = "post" target="_blank">';
	//echo "<br>j=".$j;
  $csv_string = "";
  $csv_string = $csv_string."Title,SNo,Datetime1,Datetime2,Location,Duration (hrs.min)\n";
  
  for($x=0;$x<=$j;$x++)
	{								
    $title=$pname1[$x][0].":Visit Report From DateTime : ".$date1."-".$date2;
		$title_csv = $pname1[$x][0];
		
		echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[$x]\">";
		
		$sno=0;
		//echo "<br>nodata=".$no_of_data[$x];
    for($y=0;$y<=$no_of_data[$x];$y++)
		{
			//$k=$j-1;
			$sno++;
                    
      $datetimetmp1 = $datetime_1[$x][$y];
      $datetimetmp2 = $datetime_2[$x][$y];
      $durationtmp = $duration_1[$x][$y];	
			$placenametmp = $placename1[$x][$y];										
			//$gpttemp = $gps1[$x][$y];
			
			//echo "dt=".$datetmp1;				
			echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$x][$y][SNo]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$datetimetmp1\" NAME=\"temp[$x][$y][Start Time]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$datetimetmp2\" NAME=\"temp[$x][$y][End Time]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$placenametmp\" NAME=\"temp[$x][$y][Location]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$durationtmp\" NAME=\"temp[$x][$y][Duration (hrs.min)]\">";
			
			//echo"<input TYPE=\"hidden\" VALUE=\"$gpttemp\" NAME=\"temp[$x][$y][GPS]\">";
      
      /// CODE FOR CSV
      $placenametmp = str_replace(',',':',$placenametmp);
      //$csv_string = $csv_string.$title_csv.','.$sno.','.$datetimetmp1.','.$datetimetmp2.','.$placenametmp.','.$gpttemp."\n";
      $csv_string = $csv_string.$title_csv.','.$sno.','.$datetimetmp1.','.$datetimetmp2.','.$placenametmp.','.$durationtmp."\n";  
      ////////////////////////////////////																					
		}		
	}

	/*echo'	        
    <table align="center">
		<tr>
			<td><input type="submit" value="Get PDF" class="noprint">&nbsp;<input type="button" onclick="javascript:report_csv();" value="Get CSV" class="noprint">&nbsp;<input type="button" value="Print it" onclick="window.print()" class="noprint">&nbsp;</td>
		</tr>
		</table>
		</form>';
    
    echo'<form method = "post" name="csv_form" action="src/php/report_csv.php" target="_blank">';
      echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';
    echo '</form>';	 */
     				 
     				 
  echo'	
    <table align="center">
		<tr>
			<td>';
      
  		if(sizeof($imei0)==0)
  		{						
  			print"<center><FONT color=\"Red\" size=2><strong>No Visit Record found</strong></font></center>";
  			//echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=HaltReport.php\">";
  			echo'<br><br>';
  		}	
  		else
  		{
        echo'<input TYPE="hidden" VALUE="Visit" NAME="csv_type">';
        echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';                 
        echo'<br><center><input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type4.php?size='.$psize.'\');" value="Get PDF" class="noprint">&nbsp;<input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
        <!--<input type="button" value="Print it" onclick="window.print()" class="noprint">-->&nbsp;';      
      }
                  
      echo'</td>		
    </tr>
		</table>
		</form>
 ';
      				 
unlink($xml_path);
echo'</center>';
?>								