<?php
set_time_limit(3000);	
date_default_timezone_set("Asia/Kolkata"); 
include_once("main_vehicle_information_1.php");
include_once('Hierarchy.php');
$root=$_SESSION["root"];
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
include_once('user_type_setting.php');
include_once('xmlParameters.php');
include_once('parameterizeData.php');
include_once('data.php');
include_once("sortXmlData.php");
include_once("getXmlData.php");
//set_time_limit(300);
include_once("calculate_distance.php");
include_once('googleMapApi.php');


	
	
$xmltowrite = $_REQUEST['xml_file']; 	
$mode = $_REQUEST['mode'];
$vserial1 = $_REQUEST['vserial'];
$date1 = $_REQUEST['startdate'];
$date2 = $_REQUEST['enddate']; 
$time_interval1 = $_REQUEST['time_interval'];	

$date1 = str_replace('/', '-', $startdate);  
$date2 = str_replace('/', '-', $date2); 

$date_1 = explode(" ",$date1);
$date_2 = explode(" ",$date2);
$datefrom = $date_1[0];
$dateto = $date_2[0];

$sortBy='h';
$firstDataFlag=0;
$endDateTS=strtotime($date2);

//$userInterval = "0";
$requiredData="All";

$parameterizeData=new parameterizeData();
$ioFoundFlag=0;



$finalVNameArr=array();	
$vserial = explode(',',$vserial1) ;   
	
	//include_once("sort_xml.php");
	$minlat = 180; 
	$maxlat = -180;
	$minlong = 180;
	$maxlong = -180;
	$maxPoints = 1000;
	$file_exist = 0;	
	$tmptimeinterval = strtotime($enddate) - strtotime($startdate);
	
	if($time_interval1=="auto")
	{
		$userInterval =   ($tmptimeinterval/$maxPoints);
		$distanceinterval = 0.1; 
	}
	else
	{
		if($tmptimeinterval>86400)
		{
			$timeinterval =   $time_interval1;		
			$distanceinterval = 0.3;
		}
		else
		{
			$userInterval =   $time_interval1;
			$distanceinterval = 0.02;
		}
	} 
	
	$parameterizeData->latitude="d";
	$parameterizeData->longitude="e";
	$si=0;
	$dataCnt=0;
	if($mode==2)
	{		
		$vehicle_info=get_vehicle_info($root,$vserial[0]);
		$vehicle_detail_local=explode(",",$vehicle_info);
		$finalVNameArr[$si]=$vehicle_detail_local[0];
			
		$LastSortedDate = getLastSortedDate($vserial[$si],$datefrom,$dateto);
		$SortedDataObject=new data();
		$UnSortedDataObject=new data();
		
		if(($LastSortedDate+24*60*60)>=$endDateTS) //All sorted data
		{	
			//echo "in if1";
			$type="sorted";
			readFileXml($vserial[$si],$date1,$date2,$datefrom,$dateto,$userInterval,$requiredData,$sortBy,$type,$parameterizeData,$firstDataFlag,$SortedDataObject);
		}
		else if($LastSortedDate==null) //All Unsorted data
		{
			//echo "in if2";
			$type="unSorted";
			readFileXml($vserial[$si],$date1,$date2,$datefrom,$dateto,$userInterval,$requiredData,$sortBy,$type,$parameterizeData,$firstDataFlag,$UnSortedDataObject);
		}
		else //Partially Sorted data
		{
			$LastSDate=date("Y-m-d",$LastSortedDate+24*60*60);
			//echo "in else";
			$type="sorted";					
			readFileXml($vserial[$si],$date1,$date2,$datefrom,$LastSDate,$userInterval,$requiredData,$sortBy,$type,$parameterizeData,$firstDataFlag,$SortedDataObject);
		
			$type="unSorted";
			readFileXml($vserial[$si],$date1,$date2,$LastSDate,$dateto,$userInterval,$requiredData,$sortBy,$type,$parameterizeData,$firstDataFlag,$UnSortedDataObject);
		}
	
		/*echo "udt1=".$UnSortedDataObject->deviceDatetime[0]."<br>";
		echo "udt2=".$UnSortedDataObject->deviceDatetime[1]."<br>";
		echo "<br><br>";*/
		
		if(count($SortedDataObject->deviceDatetime)>0)
		{
			/*echo "psdt1=".$SortedDataObject->deviceDatetime[0]."<br>";
		echo "psdt2=".$SortedDataObject->deviceDatetime[1]."<br>";
		echo "<br><br>";*/
			$prevSortedSize=sizeof($SortedDataObject->deviceDatetime);
			for($obi=0;$obi<$prevSortedSize;$obi++)
			{			
				$finalDateTimeArr[$dataCnt]=$SortedDataObject->deviceDatetime[$obi];
				$finalLatitudeArr[$dataCnt]=$SortedDataObject->latitudeData[$obi];
				$finalLongitudeArr[$dataCnt]=$SortedDataObject->longitudeData[$obi];
				$dataCnt++;
			}
		}
		if(count($UnSortedDataObject->deviceDatetime)>0)
		{
			$sortObjTmp=sortData($UnSortedDataObject,$sortBy,$parameterizeData);
			//var_dump($sortObjTmp);
			/*echo"sdt1=".$sortObjTmp->deviceDatetime[0]."<br>";
			echo "sdt2=".$sortObjTmp->deviceDatetime[1]."<br>";*/
			$sortedSize=sizeof($sortObjTmp->deviceDatetime);
			for($obi=0;$obi<$sortedSize;$obi++)
			{				
				$finalDateTimeArr[$dataCnt]=$sortObjTmp->deviceDatetime[$obi];	
				$finalLatitudeArr[$dataCnt]=$sortObjTmp->latitudeData[$obi];
				$finalLongitudeArr[$dataCnt]=$sortObjTmp->longitudeData[$obi];	
				$dataCnt++;
			}
		}
		$SortedDataObject=null;			
		$sortObjTmp=null;
		$UnsortedDataObject =null;
	

		$prev_halt = false;
		$norepeat_loc = false;		
		$LastDTForDiff = "";
		$firstData = 0;
		$distance =0.0;
	
		for($i=0;$i<$dataCnt;$i++)
		{
			$CurrentLat = $lat_value[1] ;
			$CurrentLong = $lng_value[1];
			$CurrentDTForDiffTmp=strtotime($datetime);
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

				$LastDTForDiffTS=strtotime($LastDTForDif);	
				$tmpdifff=$CurrentDTForDiffTmp-$LastDTForDiffTS;								
				$dateDifference=($CurrentDTForDiffTmp-$LastDTForDiffTS)/3600;
				$dateDifference_1=round($dateDifference,5);
				//echo  "Lat=".$tmp1lat.' Lng='.$tmp1lng.' Lat2='.$tmp2lat.' Lng2='.$tmp2lng.'<BR>';             							
				calculate_distance($tmp1lat,$tmp2lat,$tmp1lng,$tmp2lng,$distance);                
				$linetolog = $CurrentLat.','.$CurrentLong.','.$LastLat.','.$LastLong.','.$distance.','.$xml_date_current.','.$xml_date_last.','.(strtotime($xml_date_current)-strtotime($xml_date_last)).','.$timeinterval.','.$distanceinterval.','.$enddate.','.$startdate."\n";
				if($distance!=0)
				{
					$overSpeed=$distance/$dateDifference_1;
				}
				else
				{
					$overSpeed=0;
				}
				//echo "datetime=".$datetime."CurrentDTForDiffTmp=".$CurrentDTForDiffTmp."LastDTForDif=".$LastDTForDif."LastDTForDiffTS=".$LastDTForDiffTS." tmpdifff=".$tmpdifff." dateDifference_1=".$dateDifference_1." distance=".$distance." overSpeed=".$overSpeed."<br>";
				/*echo "overSpeed=".$overSpeed."<br>";*/								
				//fwrite($xmllog, $linetolog);
			}
			
			if($distance<$distanceinterval)
			{
				$LastDTForDif=$xml_date_current;
			}
			/*if((((((strtotime($xml_date_current)-strtotime($xml_date_last))>$timeinterval) && ($distance>=$distanceinterval)) || ($firstData==0)) && 
			(($xml_date_current<=$enddate) && ($xml_date_current>=$startdate))) || ($f==$total_lines-2) )*/
			//if(($distance>=$distanceinterval) || ($firstData==0) )
			if($distance<=$distanceinterval)
			{
				if($prev_halt)
				{
					$norepeat_loc = true;
				}
				if(!$prev_halt)
				{
					$prev_halt = true;
				}
			}
			else
			{
				$prev_halt = false;
				$norepeat_loc = false;
			}
										
			if($overSpeed<80)			
			{
				$xml_date_last = $xml_date_current;
				$LastLat =$CurrentLat;
				$LastLong =$CurrentLong;
				$LastDTForDif=$xml_date_current;
				$line = substr($line, 0, -3);   // REMOVE LAST TWO /> CHARARCTER
				$finalDistance = $finalDistance + $distance;
				if($userdates[$i]<$old_xml_date)
				{
					//echo "in replace 1";
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
					$line=str_replace("day_max_speed=","s=",$line);
					$line=str_replace("day_max_speed_time=","t=",$line);
					$line=str_replace("last_halt_time=","u=",$line);
					$line=str_replace("cellname=","ab=",$line);
					if($norepeat_loc) {$location_norepeat="1";} else {$location_norepeat="0";}
					$linetowrite = "\n".$line.' w="'.$vname.'" x="'.$vehicle_number.'" z="'.round($finalDistance,2).'" noloc="'.$location_norepeat.'"/>'; // for distance       // ADD DISTANCE
				}
				else
				{
					if($norepeat_loc) {$location_norepeat="1";} else {$location_norepeat="0";}
					$linetowrite = "\n".$line.' v="'.$vehicle_serial.'" w="'.$vname.'" x="'.$vehicle_number.'" z="'.round($finalDistance,2).'" noloc="'.$location_norepeat.'"/>'; // for distance       // ADD DISTANCE
				}
				//$lat[]=
				//$lng[]=
				//$noloc[]=
				//$vehicleserialA[]=
				 //read_xml_imei2($xmltowrite, $vehicleserialA, $lat, $lng, $noloc);
				$firstData = 1;
			}
			
		}
	}
	$parameterizeData=null;
	$i=0;
	
	if($crd_data==1)
	{
		$fh = fopen($xmltowrite, 'w') or die("can't open file 1"); // new
		fwrite($fh, "<t1>");  
		fclose($fh);
		$vserial_arr = explode(',',$vserial);
		$vname1 ="";

		for($i=0;$i<sizeof($vserial_arr);$i++)
		{
			$tmp = explode('#',$vserial_arr[$i]);
			$imei = $tmp[0];
			$last_time = $tmp[1];
			$vehicle_info=get_vehicle_info($root,$imei);
			$vehicle_detail_local=explode(",",$vehicle_info);	
			get_vehicle_last_data($current_date, $imei, $last_time, $vehicle_detail_local[0], $xmltowrite);
		}

		$fh = fopen($xmltowrite, 'a') or die("can't open file 2"); //append
		fwrite($fh, "\n<a1 datetime=\"unknown\"/>");       
		fwrite($fh, "\n</t1>");  
		fclose($fh);
  
	}
	
	
	
	
	function get_vehicle_last_data($current_date, $imei, $last_time, $vname, $pathtowrite)
	{
		//date_default_timezone_set('Asia/Calcutta');
		$current_time = date('Y-m-d H:i:s');
		global $d;
		$d++;
		$xml_file = "../../../xml_vts/xml_last/".$imei.".xml";
		$file = file_get_contents($xml_file);
		if(!strpos($file, "</t1>")) 
		{
			usleep(1000);
		}		
  
		$t=time();
		$rno = rand();			
		$xml_original_tmp = "../../../xml_tmp/original_xml/tmp_".$imei."_".$t."_".$rno.".xml";		
		copy($xml_file,$xml_original_tmp); 
	    
		if (file_exists($xml_original_tmp))
		{
			//echo "<br>exist2";
			$fexist =1;
			$fp = fopen($xml_original_tmp, "r") or $fexist = 0;   
			$total_lines =0;
			$total_lines = count(file($xml_original_tmp));
			//echo "<br>total_lines=".$total_lines;
			$c =0;
			while(!feof($fp)) 
			{
				$line = fgets($fp);
				$c++;		
				if(strlen($line)>15)
				{
					if($userdates[$i]<$old_xml_date)  /// for sorted xml
					{
						old_xml_variables();						
					}
					else
					{
						new_xml_variables();
					}
					if ( (preg_match('/'.$d.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) && (preg_match('/'.$e.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
					{
						$status = preg_match('/'.$h.'="[^"]+/', $line, $datetime_tmp);
						$datetime_tmp1 = explode("=",$datetime_tmp[0]);
						$datetime = preg_replace('/"/', '', $datetime_tmp1[1]);
						$xml_date = $datetime;

						$status = preg_match('/'.$f.'="[^"]+/', $line, $speed_tmp);
						$speed_tmp1 = explode("=",$speed_tmp[0]);
						$speed = preg_replace('/"/', '', $speed_tmp1[1]);

						$status = preg_match('/'.$d.'="[^"]+/', $line, $lat_tmp);
						$lat_tmp1 = explode("=",$lat_tmp[0]);
						$lat = preg_replace('/"/', '', $lat_tmp1[1]);

						$status = preg_match('/'.$e.'="[^"]+/', $line, $lng_tmp);
						$lng_tmp1 = explode("=",$lng_tmp[0]);
						$lng = preg_replace('/"/', '', $lng_tmp1[1]);

						$status = preg_match('/'.$s.'="[^"]+/', $line, $day_max_speed_tmp);
						$day_max_speed_tmp1 = explode("=",$day_max_speed_tmp[0]);
						$day_max_speed = preg_replace('/"/', '', $day_max_speed_tmp1[1]);

						$status = preg_match('/'.$t.'="[^"]+/', $line, $day_max_speed_time_tmp);
						$day_max_speed_time_tmp1 = explode("=",$day_max_speed_time_tmp[0]);
						$day_max_speed_time = preg_replace('/"/', '', $day_max_speed_time_tmp1[1]);

						$status = preg_match('/'.$u.'="[^"]+/', $line, $last_halt_time_tmp);
						$last_halt_time_tmp1 = explode("=",$last_halt_time_tmp[0]);
						$last_halt_time = preg_replace('/"/', '', $last_halt_time_tmp1[1]);                                                                 
								  

						$xml_date_sec = strtotime($xml_date);   
						$current_time_sec = strtotime($current_time);

						//////////////////////////////////////////
						$diff = ($current_time_sec - $xml_date_sec);   // IN SECONDS
							  
						//if( ($diff < 120) && ($lat!="" || $lng!="") && ($speed>=5) )         //< 2 min
						//if( ($diff < 180) && ($lat!="" || $lng!="") )
						//if($speed>=10 && $diff <=180)
						if($speed>=5 && $diff <=600)
						{
							$status = "Running";
							//echo "<br>Running";
						}               
						/*else if((($diff < 120) || ($diff >180 && $diff <1200)) && ($speed<10))      //>2 and <20 min
						{
							$status = "Idle";
							//echo "<br>Idle";
						}
						//else if(($diff >1200) && ($speed <10))               //>20 min
						else if($diff >1200)        //>20 min
						{
						$status = "Stopped";
						//echo "<br>Stopped";
						} */
						else
						{
							$status = "Stopped";
						}                           
				
						$line = substr($line, 0, -3);
						$line2 = "\n".$line.' s="'.$vehicle_serial.'" t="'.$vname.'" aa="'.$status.'"/>';                          									
					}																			
				}			
			}		
			//echo "<br>pathtowrite1:".$pathtowrite."<br>";			
			$len = strlen($line2);
			if($len>0)
			{
				//echo "<br>pathtowrite2:".$pathtowrite."<br>";				
				$fh = fopen($pathtowrite, 'a') or die("can't open file pathtowrite"); //append
				//$fh = fopen($pathtowrite, 'w') or die("can't open file 1");
				fwrite($fh, $line2);  
				fclose($fh);
				fclose($fp);
				unlink($xml_original_tmp);
				//break;
			}
			else
			{
				fclose($fp);
				unlink($xml_original_tmp);
			}							
		}	
	}

	
	function getTrack($vehicle_serial,$vname,$vehicle_number,$startdate,$enddate,$xmltowrite,$timeinterval,$distanceinterval)
	{
		//echo "in function<br>";		
		global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
		global $old_xml_date;	
		//echo "In Track";

		$prev_halt = false;
		$norepeat_loc = false;		
		$fix_tmp = 1;
		$xml_date_latest="1900-00-00 00:00:00";
		$CurrentLat = 0.0;
		$CurrentLong = 0.0;
		$LastLat = 0.0;
		$LastLong = 0.0;
		$LastDTForDiff = "";
		$firstData = 0;
		$distance =0.0;
		$linetowrite="";
		$date_1 = explode(" ",$startdate);
		$date_2 = explode(" ",$enddate);
		$date_time_cmp=$date_2[0]." 23:59:59";
		$datefrom = $date_1[0];
		$dateto = $date_2[0];
		$timefrom = $date_1[1];
		$timeto = $date_2[1];

		get_All_Dates($datefrom, $dateto, $userdates);

		date_default_timezone_set("Asia/Calcutta");
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
				//echo "<br>CurrNew";
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
				//echo "<br>exist1";
				set_master_variable($userdates[$i]);
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
					//echo "\nFileExist";
					while(!feof($xml))          // WHILE LINE != NULL
					{
						//echo fgets($file). "<br />";
						$DataValid = 0;
						$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE
						//echo "0:line:".$line;					
					
						$linetolog =  $logcnt." ".$line;
						$logcnt++;
						//fwrite($xmllog, $linetolog);
						
						//echo "vc:".$vc;
						/*if(strpos($line,''.$vc.'="1"'))     // RETURN FALSE IF NOT FOUND
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
						}*/
						$fix_tmp = 1;				
						if((preg_match('/'.$vd.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) &&  (preg_match('/'.$ve.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
						{ 
							$lat_value = explode('=',$lat_match[0]);
							$lng_value = explode('=',$lng_match[0]);
							//echo " lat_value=".$lat_value[1];         
							if( (strlen($lat_value[1])>5) && ($lat_value[1]!="-") && (strlen($lng_value[1])>5) && ($lng_value[1]!="-") )
							{
								$DataValid = 1;
							}         
						}
						/*echo "datavalie=".$DataValid;
						echo "line1=".$line[strlen($line)-2];
						echo "fix_tmp=".$fix_tmp;*/
						$linetmp = "";
						//if( (substr($line, 0,1) == '<') && (substr( (strlen($line)-1), 0,1) == '>') && ($fix_tmp==1) && ($f>0) && ($f<$total_lines-1) )        
						if(($line[0] == '<') && ($line[strlen($line)-2] == '>') && (($fix_tmp==1) || ($fix_tmp == 2))&& ($DataValid == 1) )        
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
						if($DataValid==1)
						{
							$break_flag=1;
							if(strtotime($datetime)>strtotime($date_time_cmp))
							{
								$break_flag=0;
							}						
							if($break_flag==1)
							{
								if($xml_date_current>=$enddate)
								{									
									break;
								}
							}
						}
						//echo "Final0=".$xml_date_current." datavalid=".$DataValid;
			  
						if (($xml_date_current!=null)  && ($DataValid==1) && (($fix_tmp==1) || ($fix_tmp == 2)))
						{
							$linetolog = $xml_date_current.' '.$firstData."\n";
							//fwrite($xmllog, $linetolog);
							//echo "Final1";
							$CurrentLat = $lat_value[1] ;
							$CurrentLong = $lng_value[1];
							$CurrentDTForDiffTmp=strtotime($datetime);

							//if(($xml_date_current >= $startdate && $xml_date_current <= $enddate) && ($xml_date_current!="-"))
							if(($xml_date_current >= $startdate && $xml_date_current <= $enddate) && ((strtotime($xml_date_current)-strtotime($xml_date_last))>$timeinterval) && ($xml_date_current!="-") && $xml_date_current >= $xml_date_latest && $xml_date_current<=($userdates[$i]." 23:59:59"))
							{
								$xml_date_latest = $xml_date_current;
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

									$LastDTForDiffTS=strtotime($LastDTForDif);	
									$tmpdifff=$CurrentDTForDiffTmp-$LastDTForDiffTS;								
									$dateDifference=($CurrentDTForDiffTmp-$LastDTForDiffTS)/3600;
									$dateDifference_1=round($dateDifference,5);
									//echo  "Lat=".$tmp1lat.' Lng='.$tmp1lng.' Lat2='.$tmp2lat.' Lng2='.$tmp2lng.'<BR>';             							
									calculate_distance($tmp1lat,$tmp2lat,$tmp1lng,$tmp2lng,$distance);                
									$linetolog = $CurrentLat.','.$CurrentLong.','.$LastLat.','.$LastLong.','.$distance.','.$xml_date_current.','.$xml_date_last.','.(strtotime($xml_date_current)-strtotime($xml_date_last)).','.$timeinterval.','.$distanceinterval.','.$enddate.','.$startdate."\n";
									
									$overSpeed=$distance/$dateDifference_1;
									//echo "datetime=".$datetime."CurrentDTForDiffTmp=".$CurrentDTForDiffTmp."LastDTForDif=".$LastDTForDif."LastDTForDiffTS=".$LastDTForDiffTS." tmpdifff=".$tmpdifff." dateDifference_1=".$dateDifference_1." distance=".$distance." overSpeed=".$overSpeed."<br>";
									/*echo "overSpeed=".$overSpeed."<br>";*/								
									//fwrite($xmllog, $linetolog);
								}
								
								if($distance<$distanceinterval)
								{
									$LastDTForDif=$xml_date_current;
								}
								/*if((((((strtotime($xml_date_current)-strtotime($xml_date_last))>$timeinterval) && ($distance>=$distanceinterval)) || ($firstData==0)) && 
								(($xml_date_current<=$enddate) && ($xml_date_current>=$startdate))) || ($f==$total_lines-2) )*/
								//if(($distance>=$distanceinterval) || ($firstData==0) )
								if($distance<=$distanceinterval)
								{
									if($prev_halt)
									{
										$norepeat_loc = true;
									}
									if(!$prev_halt)
									{
										$prev_halt = true;
									}
								}
								else
								{
									$prev_halt = false;
									$norepeat_loc = false;
								}
							
								//{
									$linetolog = "Data Written\n";
									//fwrite($xmllog, $linetolog);
									//echo "<br>FinalWrite";								
									if($overSpeed<80)
									
									{
										$xml_date_last = $xml_date_current;
										$LastLat =$CurrentLat;
										$LastLong =$CurrentLong;
										$LastDTForDif=$xml_date_current;
										$line = substr($line, 0, -3);   // REMOVE LAST TWO /> CHARARCTER
										$finalDistance = $finalDistance + $distance;
										if($userdates[$i]<$old_xml_date)
										{
											//echo "in replace 1";
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
											$line=str_replace("day_max_speed=","s=",$line);
											$line=str_replace("day_max_speed_time=","t=",$line);
											$line=str_replace("last_halt_time=","u=",$line);
											$line=str_replace("cellname=","ab=",$line);
											if($norepeat_loc) {$location_norepeat="1";} else {$location_norepeat="0";}
											$linetowrite = "\n".$line.' w="'.$vname.'" x="'.$vehicle_number.'" z="'.round($finalDistance,2).'" noloc="'.$location_norepeat.'"/>'; // for distance       // ADD DISTANCE
										}
										else
										{
											if($norepeat_loc) {$location_norepeat="1";} else {$location_norepeat="0";}
											$linetowrite = "\n".$line.' v="'.$vehicle_serial.'" w="'.$vname.'" x="'.$vehicle_number.'" z="'.round($finalDistance,2).'" noloc="'.$location_norepeat.'"/>'; // for distance       // ADD DISTANCE
										}
										//echo "<br>finalDistance=".$finalDistance;
										//$linetowrite = "\n".$line.' cumdist="'.$finalDistance.'"./>'; // for distance       // ADD DISTANCE
										//$linetowrite = "\n".$line.'/>';
										//echo "<textarea>".$linetowrite."</textarea>";
										//echo "lintowrite=".$linetowrite;
										$firstData = 1;
																			
										//$fh = fopen($xmltowrite, 'a') or die("can't open file 4"); //append
										fwrite($fh, $linetowrite); 
									}
								//}
							}
							if((strlen($line)>20) && ($xml_date_current >= $startdate && $xml_date_current <= $enddate))
							{
								$linelast =  $line;
							}
							
						}
						/*if((strlen($line)>20) && ($xml_date_current<=($userdates[$i]." 23:59:59")))
						{
							$linelast =  $line;
						}*/
						$f++;
					}   // while closed
				} // if original_tmp exist closed 
		  
				      
				fclose($xml);            
				unlink($xml_original_tmp);
			} // if (file_exists closed
		}  // for closed 
		$linelast=substr($linelast,0,-3);
		if($userdates[$i-1]<$old_xml_date)
		{
			//echo "in replace 1";
			$linelast=str_replace("marker","x",$linelast);
			$linelast=str_replace("msgtype=","a=",$linelast);
			$linelast=str_replace("vehicleserial=","v=",$linelast);
			$linelast=str_replace("ver=","b=",$linelast);
			$linelast=str_replace("fix=","c=",$linelast);
			$linelast=str_replace("lat=","d=",$linelast);
			$linelast=str_replace("lng=","e=",$linelast);
			$linelast=str_replace("speed=","f=",$linelast);
			$linelast=str_replace("sts=","g=",$linelast);
			$linelast=str_replace("datetime=","h=",$linelast);
			$linelast=str_replace("io1=","i=",$linelast);
			$linelast=str_replace("io2=","j=",$linelast);
			$linelast=str_replace("io3=","k=",$linelast);
			$linelast=str_replace("io4=","l=",$linelast);
			$linelast=str_replace("io5=","m=",$linelast);
			$linelast=str_replace("io6=","n=",$linelast);
			$linelast=str_replace("io7=","o=",$linelast);
			$linelast=str_replace("io8=","p=",$linelast);
			$linelast=str_replace("sig_str=","q=",$linelast);
			$linelast=str_replace("sup_v=","r=",$linelast);
			$linelast=str_replace("day_max_speed=","s=",$linelast);
			$linelast=str_replace("day_max_speed_time=","t=",$linelast);
			$linelast=str_replace("last_halt_time=","u=",$linelast);
			$linelast=str_replace("cellname=","ab=",$linelast);
			$linetowrite = "\n".$linelast.' w="'.$vname.'" x="'.$vehicle_number.'" z="'.round($finalDistance,2).'"/>'; // for distance       // ADD DISTANCE
		}
		else
		{
			$linetowrite = "\n".$linelast.' v="'.$vehicle_serial.'" w="'.$vname.'" x="'.$vehicle_number.'" z="'.round($finalDistance,2).'"/>'; // for distance       // ADD DISTANCE
		}		
		fwrite($fh, $linetowrite);
		fclose($fh);
		//echo "Test1";
		fclose($fh);
	//fclose($xmllog);
	}
	

  


?>
