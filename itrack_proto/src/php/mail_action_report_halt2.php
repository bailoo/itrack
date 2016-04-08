<?php
//echo "MAIL ACTION";
function get_halt_xml_data($vehicle_serial, $vid, $vname, $startdate, $enddate, $user_interval)
{
	//echo "\nHALT";
	$abspath = "/var/www/html/vts/beta/src/php";
	include_once($abspath."/get_location_lp_track_report.php");
	
  global $DbConnection;
	global $account_id;
	global $geo_id1;
	$sno =1;
	global $csv_string_halt;
	global $overall_dist;
	global $total_halt_dur;
	
  global $station_id;
  global $type;
  global $customer_no;
  global $station_name;
  global $station_coord;
  global $distance_variable;
  global $google_location;

  for($k=0;$k<sizeof($station_coord);$k++)       //INITIALISE VARIABLES
  {    
    $halt_flag[$k] = 0;
    $firstdata_flag_halt[$k] = 0; 
    $substr_count[$k] =0;  
    //$total_halt_time[$k] = 0;
  }
	
	$interval=$user_interval*60;
	//echo "interval=".$interval."<br>";
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
		
	$arrivale_time="";
	$depature_time="";
	$hrs_min="";
	
	$date_1 = explode(" ",$startdate);
	$datefrom = $date_1[0];
	$timefrom = $date_1[1];
	$date_2 = explode(" ",$enddate);	
	$dateto = $date_2[0];
	$timeto = $date_2[1];	
	
	get_All_Dates($datefrom, $dateto, &$userdates);
	//date_default_timezone_set("Asia/Calcutta");
	$current_datetime = date("Y-m-d H:i:s");
	$current_date = date("Y-m-d");

	$date_size = sizeof($userdates);
	$substr_count =0;
	
	$back_dir = "/var/www/html/itrack_vts";
	
  for($i=0;$i<=($date_size-1);$i++)
	{	
		$xml_current = $back_dir."/xml_vts/xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";
		if (file_exists($xml_current))      
		{  
			$xml_file = $xml_current;
			$CurrentFile = 1;
		}		
		else
		{
			$xml_file = $back_dir."/sorted_xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";
			$CurrentFile = 0;
		}		
		//echo "<br>xml in get_halt_xml_data =".$xml_file;	
    	
		if (file_exists($xml_file)) 
		{			
			$t=time();
			//echo "t=".$t."<br>";
			$xml_original_tmp = $back_dir."/xml_tmp/original_xml/tmp_".$vehicle_serial."_".$t."_".$i.".xml";									      
			if($CurrentFile == 0)
			{
				copy($xml_file,$xml_original_tmp);
			}
			else
			{
				$xml_unsorted = $back_dir."/xml_tmp/unsorted_xml/tmp_".$vehicle_serial."_".$t."_".$i."_unsorted.xml";
				copy($xml_file,$xml_unsorted);        // MAKE UNSORTED TMP FILE
				SortFile($xml_unsorted, $xml_original_tmp);    // SORT FILE
				unlink($xml_unsorted);                // DELETE UNSORTED TMP FILE
			}      
			
			$total_lines = count(file($xml_original_tmp));		      
			$xml = @fopen($xml_original_tmp, "r") or $fexist = 0;  
			$logcnt=0;
			$DataComplete=false;                  
			$vehicleserial_tmp=null;      
			$f=0;

			//echo "\nxml_original_tmp=".$xml_original_tmp;
      
			if (file_exists($xml_original_tmp)) 
			{      
				//echo "\nFile Exist";

				while(!feof($xml))          // WHILE LINE != NULL
				{
					//echo "\nIN WHILE";
					$DataValid = 0;			
					$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE	
					if(strlen($line)>20)
					{
						$linetmp =  $line;
					}  				
					$linetolog =  $logcnt." ".$line;
					$logcnt++;
					//fwrite($xmllog, $linetolog);
					if(strpos($line,'Fix="1"'))     // RETURN FALSE IF NOT FOUND
					{
						$format = 1;
						$fix_tmp = 1;
					}                
					if(strpos($line,'Fix="0"'))
					{
						$format = 1;
						$fix_tmp = 0;
					}
					else
					{
						$format = 2;
					}  				
					if( (preg_match('/lat="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) &&  (preg_match('/lng="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
					{ 
						$lat_value = explode('=',$lat_match[0]);
						$lng_value = explode('=',$lng_match[0]);					        
						if( (strlen($lat_value[1])>5) && ($lat_value[1]!="-") && (strlen($lng_value[1])>5) && ($lng_value[1]!="-") )
						{
							$DataValid = 1;
						}
					}       
					//if( (substr($line, 0,1) == '<') && (substr( (strlen($line)-1), 0,1) == '>') && ($fix_tmp==1) && ($f>0) && ($f<$total_lines-1) )        
					if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($DataValid == 1) )   // FIX_TMP =1 COMES IN BOTH CASE     
					{					
						$status = preg_match('/datetime="[^"]+/', $line, $datetime_tmp);
						$datetime_tmp1 = explode("=",$datetime_tmp[0]);
						$datetime = preg_replace('/"/', '', $datetime_tmp1[1]);	
						$xml_date = $datetime;
					}       

					 //echo "\nXML_DATE=".$xml_date." ,DataValid=".$DataValid." ,vehicle_serial=".$vehicle_serial;   

					if($xml_date!=null)
					{
						//echo "\nStartDate=".$startdate." ,EndDate=".$enddate;

						if(($xml_date >= $startdate && $xml_date <= $enddate) && ($xml_date!="-") && ($DataValid==1) )
						{
							//echo "\nIN DATE";

							$status = preg_match('/vehicleserial="[^" ]+/', $line, $vehicleserial_tmp);						
							if($status==0)
							{
								//echo "\nStatus0";
								continue;
							}
							//echo "<textarea>".$line."</textarea>"; 
							//$line = '<marker msgtype="NORMAL" vehicleserial="354776033544005" vehiclename="SJ DKL-4005" ver="v1.34" fix="1" lat="26.60652N" lng="80.20758E" alt="123.0" speed="0.000000" datetime="2011-01-25 03:16:23" fuel="-" vehicletype="Heavy" no_of_sat="09" cellname="" distance="0.0" io1="7" io2="6" io3="387" io4="10" io5="6" io6="6" io7="4" io8="2" sig_str="0" sup_v="0.000000" speed_a="0" geo_in_a="0" geo_out_a="0" stop_a="0" move_a="0" lowv_a="0"/>';  
							/*$status = preg_match('/vehiclename="[^"]+/', $line, $vehiclename_tmp);          
							//echo "<br>vname=".$vehiclename_tmp[0];
							if($status==0)
							{
								continue;
							} */                
							$status = preg_match('/lat="[^" ]+/', $line, $lat_tmp);
							if($status==0)
							{
								//echo "\nStatus1";
								continue;               
							}
							//echo "test6".'<BR>';
							$status = preg_match('/lng="[^" ]+/', $line, $lng_tmp);
							if($status==0)
							{
								//echo "\nStatus2";
								continue;
							}                 
							$status = preg_match('/speed="[^" ]+/', $line, $speed_tmp);
							if($status==0)
							{
								//echo "\nStatus3";
								continue;
							}      
							       
							if($firstdata_flag==0)
							{							
								$halt_flag = 0;
								$firstdata_flag = 1;

								$vehicleserial_tmp1 = explode("=",$vehicleserial_tmp[0]);
								$vserial = preg_replace('/"/', '', $vehicleserial_tmp1[1]);							

								$lat_tmp1 = explode("=",$lat_tmp[0]);							
								$lat_ref = preg_replace('/"/', '', $lat_tmp1[1]);
								$lng_tmp1 = explode("=",$lng_tmp[0]);
								$lng_ref = preg_replace('/"/', '', $lng_tmp1[1]);
							
								$datetime_ref = $datetime;							                	
								$date_secs1 = strtotime($datetime_ref);							
								$date_secs1 = (double)($date_secs1 + $interval);      	
							}                 	
							else
							{           
								//echo "<br>Next";               
								$lat_tmp1 = explode("=",$lat_tmp[0]);                           //  GET NEXT RECO
								$lat_cr = preg_replace('/"/', '', $lat_tmp1[1]);
								$lng_tmp1 = explode("=",$lng_tmp[0]);
								$lng_cr = preg_replace('/"/', '', $lng_tmp1[1]);							
								$datetime_cr = $datetime;																		
								$date_secs2 = strtotime($datetime_cr);	
								calculate_distance($lat_ref, $lat_cr, $lng_ref, $lng_cr, &$distance);
								//if(($distance > 0.0100) || ($f== $total_lines-2) )
								if(($distance > 0.150) || ($f == $total_lines-10) )
								{								
									if ($halt_flag == 1)
									{								
										$arrivale_time=$datetime_ref;
										$starttime = strtotime($datetime_ref);										  
										$stoptime = strtotime($datetime_cr);
										$depature_time=$datetime_cr;
										$halt_dur = ($stoptime - $starttime);
                    
										$hms_2 = secondsToTime($halt_dur);
										$hrs_min = $hms_2[h].":".$hms_2[m].":".$hms_2[s];
                              										
										/*$halt_dur1 =  $halt_dur/3600;
										$halt_duration = round($halt_dur1,2);										
										$total_min = $halt_duration * 60;              
										$total_min1 = $total_min;						          
										$hr = (int)($total_min / 60);
										$minutes = $total_min % 60;             
										$hrs_min = $hr.".".$minutes;*/   
                            					              					
										if(($halt_dur>=$interval) || ($f==$total_lines-2))
										{																						
												//get location
												/*$total_halt_dur = $total_halt_dur + $halt_dur;   //SUM HALT TIME
												
												$landmark="";
												get_landmark($lat_ref,$lng_ref,&$landmark);    // CALL LANDMARK FUNCTION
												$place = $landmark;
												$alt1 = "-";  */
												
												/*if($place=="")
												{
													get_location($lat_ref,$lng_ref,$alt1,&$place,$DbConnection);    // CALL GOOGLE LOCATION FUNCTION
												}
												if($place == "")
												{
													$place = "-";
												}									                               
												$place = str_replace(',',':',$place);
												*/
 												
												$place = "";												
                        $station_no = "";
                        //######### STATION CODE ###################
                        $entered_station = 0;
                        
                        for($k=0;$k<sizeof($station_coord);$k++)
                        {
                            $coord = explode(',',$station_coord[$k]);
                            $lat_g = trim($coord[0]);
                            $lng_g = trim($coord[1]);  
  
                            $distance_station = 0;              
                            if($lat_g!="" && $lng_g!="")
                            {
                              //echo "\nDIST::datetime=".$datetime." ,op_date1=".$op_date1." ,op_date2=".$op_date2." \ndistance=".$distance;
                              calculate_distance($lat_cr, $lat_g, $lng_cr, $lng_g, &$distance_station);
                            }               
          
            								//calculate_distance($lat_ref, $lat_cr, $lng_ref, $lng_cr, &$distance);
            								//if(($distance > 0.0100) || ($f== $total_lines-2) )
            								if($distance_variable[$k] == 0)
            								{
            								  $distance_variable[$k] = 0.1;
            								}
            								  
                            //if( (($distance > 0.0100) && ($distance < $distance_variable[$k])) || ($f == $total_lines-2) )
                            //echo "\ndistance_station=".$distance_station;
                            
                            if( ($distance_station > 0.0100) && ($distance_station < $distance_variable[$k]) )
            								{
            								  echo "\nVehicle entered withing station";
                              //$place = $google_location[$k];
                              
                              $place1 = "";
                              if($lat_g!="" && $lng_g!="")
                              {
                                get_report_location($lat_g,$lng_g,&$place1);
                                //echo "<br>PL1=".$placename1;
                                $place1 = preg_replace('/???? ???????/', '' , $place1);
                                $place1 = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '' , $place1);
                                //echo "<br>place=".$place;
                              }  
                                
                              $station_no = $customer_no[$k];
                              $place1 = str_replace(',',':',$place1);
                              
                              echo "\nPlace=".$place1." ,lt=".$lat_g." ,lng=".$lng_g;
            								  $entered_station = 1;
                              break;
                            }
                         }								
                        
                          //##########################################                        
                          if($entered_station)
                          {
                              $total_halt_dur = $total_halt_dur + $halt_dur;
                              
                              $type_str = "";
                              if($type[$k]=="0") 
                              {
                                $type_str = "Customer";
                              } 
                              else if($type[$k] == "1") 
                              {
                                $type_str = "Plant";
                              }
                              
                              if($substr_count == 0)
      												{											
      													$csv_string_halt = $csv_string_halt.$vname.','.$sno.','.$place1.','.$station_no.','.$type_str.','.$arrivale_time.','.$depature_time.','.$hrs_min.','.$lat_ref.','.$lng_ref;
      													$substr_count =1;  
      												}
      												else
      												{
      													$csv_string_halt = $csv_string_halt."#".$vname.','.$sno.','.$place1.','.$station_no.','.$type_str.','.$arrivale_time.','.$depature_time.','.$hrs_min.','.$lat_ref.','.$lng_ref;;  
      												}												
      												$sno++;
      												$date_secs1 = strtotime($datetime_cr);
      												$date_secs1 = (double)($date_secs1 + $interval);
                          }                              
											}																		
									}   //IF HALT FLAG              			
									$lat_ref = $lat_cr;
									$lng_ref = $lng_cr;
									$datetime_ref= $datetime_cr;            				
									$halt_flag = 0;
								}  //if moved
      					else if((strtotime($datetime_cr)-strtotime($datetime_ref))>60)    // IF VEHICLE STOPS FOR 2 MINS 
      					{            			
      						//echo "<br>normal flag set";
      						$halt_flag = 1;
      					}	
							}  //else closed
						} // $xml_date_current >= $startdate closed
					}   // if xml_date!null closed
					$f++;
				}   // while closed
			} // if original_tmp closed 
			//echo "vehicle_name=".$vname."csv_string_halt==".$csv_string_halt."<br>";
			fclose($xml);            
		//	unlink($xml_original_tmp);
		} // if (file_exists closed
	}  // for closed
	
	echo "\nCSV_STRING_HALT=".$csv_string_halt;

echo "\nHALT CLCLOSED";
}	
?>