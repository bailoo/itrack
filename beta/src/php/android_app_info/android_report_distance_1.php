<?php
	//error_reporting(-1);
	//ini_set('display_errors', 'On');
include_once('util_android_php_mysql_connectivity.php');  	   //util_session_variable.php sets values in session
include_once('util_android_session_variable.php');   //util_php_mysql_connectivity.php make set connection of user to database  
include_once("android_common_xml_element.php");
include_once("android_get_all_dates_between.php");
include_once("android_sort_person_xml.php");
include_once("android_calculate_distance.php");
include_once("android_report_get_parsed_string.php");
//include_once("android_read_filtered_xml.php");
//require_once "lib/nusoap.php"; 
date_default_timezone_set("Asia/Kolkata");
set_time_limit(800);
$DEBUG = 0;		
global $distance_data;
$distance_data=array();	

$deviceImeiNo="862170018371961";
$startDate="2015/05/30 00:00:00";
$endDate="2015/05/30 15:11:08";
$userInterval=60;
get_distance_xml_data($deviceImeiNo, $startDate, $endDate, $userInterval);	



function get_distance_xml_data($deviceImeiNo, $startDate, $endDate, $userInterval)
{
	global $DbConnection;
	$Query="SELECT vehicle.vehicle_name,vehicle.vehicle_type,vehicle.vehicle_number FROM vehicle USE INDEX(v_vehicleid_status)".
	",vehicle_assignment USE INDEX(va_vehicleid_status,va_vehicleid_imei_status) WHERE vehicle.vehicle_id=vehicle_assignment.".
	"vehicle_id AND vehicle.status=1 AND vehicle_assignment.status=1 AND vehicle_assignment.device_imei_no='$deviceImeiNo'";
	//echo "Query=".$Query."<br>";
	$Result=mysql_query($Query,$DbConnection);
	$Row=mysql_fetch_row($Result);
	$vname=$Row[0];
	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
	global $old_xml_date;
	global $distance_data;
	//include('common_xml_element_for_function.php');
	//new_xml_variables();
	//echo "<br>vserial=".$vehicle_serial." ,vname=".$vname." ,st=".$startdate." ,ed=".$enddate;

$startdate = str_replace("/","-",$startDate);
$enddate = str_replace("/","-",$endDate);
	//$distance_data[]=$startdate;
	//$distance_data[]=$enddate;
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
	//$distance_data[]=$datefrom;
	//$distance_data[]=$dateto;
	get_All_Dates($datefrom, $dateto, &$userdates);

	////date_default_timezone_set("Asia/Calcutta");
	$current_datetime = date("Y-m-d H:i:s");
	$current_date = date("Y-m-d");
	//print "<br>CurrentDate=".$current_date;
	$date_size = sizeof($userdates);

	

	$j = 0;
	$total_dist = 0.0;
 									
	for($i=0;$i<=($date_size-1);$i++)
	{
		$startdate1 = $startdate;
		$enddate1 = $enddate;      
		$xml_current = "../../../../xml_vts/xml_data/".$userdates[$i]."/".$deviceImeiNo.".xml";	
		//$distance_data[]=$xml_current;			
		if (file_exists($xml_current))      
		{		
			//echo "in else";
			$xml_file = $xml_current;
			$CurrentFile = 1;
		}		
		else
		{
			$xml_file = "../../../../sorted_xml_data/".$userdates[$i]."/".$deviceImeiNo.".xml";
			$CurrentFile = 0;
		}
		
		//echo "<br>xml in get_halt_xml_data =".$xml_file;	    	
		if (file_exists($xml_file)) 
		{		  
			$t=time();
			$xml_original_tmp = "../../../../xml_tmp/original_xml/tmp_".$deviceImeiNo."_".$t."_".$i.".xml";									      
			if($CurrentFile == 0)
			{
				copy($xml_file,$xml_original_tmp);
			}
			else
			{
				$xml_unsorted = "../../../../xml_tmp/unsorted_xml/tmp_".$deviceImeiNo."_".$t."_".$i."_unsorted.xml";
				copy($xml_file,$xml_unsorted);        // MAKE UNSORTED TMP FILE
				SortFile($xml_unsorted, $xml_original_tmp,$userdates[$i]);    // SORT FILE
				unlink($xml_unsorted);                // DELETE UNSORTED TMP FILE
			}      
			$total_lines = count(file($xml_original_tmp));
			$xml = @fopen($xml_original_tmp, "r") or $fexist = 0; 
			$logcnt=0;
			$DataComplete=false;                  
			$vehicleserial_tmp=null;
			$format =2;
			$c = -1;      
			$f=0;
      
			if(file_exists($xml_original_tmp)) 
			{              
				$daily_dist =0;
				//echo "<br>exist original";
				set_master_variable($userdates[$i]);
				//set_master_variable($userdates[$i]);
				while(!feof($xml))          // WHILE LINE != NULL
				{
					$c++;
					$DataValid = 0;
					//echo "<br>line";
					//echo fgets($file). "<br />";
					$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE			

					if(strlen($line)>20)
					{
						$linetmp =  $line;
					}      				
					$linetolog =  $logcnt." ".$line;
					$logcnt++;
					//fwrite($xmllog, $linetolog);
			  
					if(strpos($line,''.$vc.'="1"'))     // RETURN FALSE IF NOT FOUND
					{
						$format = 1;
						$fix_tmp = 1;
					}                    
					else if(strpos($line,''.$vc.'="0"'))
					{
						$format = 1;
						$fix_tmp = 0;
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
					//if( (substr($line, 0,1) == '<') && (substr( (strlen($line)-1), 0,1) == '>') && ($fix_tmp==1) && ($f>0) && ($f<$total_lines-1) )        
					if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($DataValid == 1) )   // FIX_TMP =1 COMES IN BOTH CASE     
					{					
						$status = preg_match('/'.$vh.'="[^"]+/', $line, $datetime_tmp);
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
						//echo "<br>xml_date=".$xml_date." ,end_date=".$enddate." ,data_valide=".$DataValid;      			
						//if( ($xml_date >= $startdate1 && $xml_date <= $enddate1) && ($xml_date!="-") && ($DataValid==1) )
						if(($xml_date >= $startdate1 && $xml_date <= $enddate1  && $xml_date >= $xml_date_latest && $xml_date<=($userdates[$i]." 23:59:59")) && ($xml_date!="-") && ($DataValid==1) )
						//if( ($xml_date >= $startdate && $xml_date <= $enddate) && ($xml_date!="-") && ($DataValid==1) )
						{
							$xml_date_latest = $xml_date;	                  
							$status = preg_match('/'.$vd.'="[^" ]+/', $line, $lat_tmp);
							if($status==0)
							{
							  continue;               
							}
							//echo "test6".'<BR>';
							$status = preg_match('/'.$ve.'="[^" ]+/', $line, $lng_tmp);
							if($status==0)
							{
							  continue;
							}     
													  
							$lat_tmp1 = explode("=",$lat_tmp[0]);
							$lat = preg_replace('/"/', '', $lat_tmp1[1]);

							$lng_tmp1 = explode("=",$lng_tmp[0]);
							$lng = preg_replace('/"/', '', $lng_tmp1[1]);  
							//$distance_data[]=$lat;
							//echo "<br>first=".$firstdata_flag;                                        
							if($firstdata_flag==0)
							{					
								$firstdata_flag = 1;      							
								$lat1 = $lat;
								$lng1 = $lng;
								$interval = (double)$userInterval*60;	
								$time1 = $datetime;					
								$date_secs1 = strtotime($time1);     					
								$date_secs1 = (double)($date_secs1 + $interval); 
								$date_secs2 = 0;  
								$last_time1 = $datetime;
								$latlast = $lat;
								$lnglast =  $lng;
								//echo "<br>FirstData:".$date_secs1;                 	
							} 
							else
							{ 
								$time2 = $datetime;											
								$date_secs2 = strtotime($time2);	
								$vserial=$vehicle_serial;													                                      													      					
								$lat2 = $lat;      				        					
								$lng2 = $lng; 			
								calculate_distance($lat1, $lat2, $lng1, $lng2, &$distance);
								$tmp_time_diff1 = (double)(strtotime($datetime) - strtotime($last_time1)) / 3600;
						
								calculate_distance($latlast, $lat2, $lnglast, $lng2, &$distance1);
								if($tmp_time_diff1>0)
								{
									$tmp_speed = ((double) ($distance1)) / $tmp_time_diff1;
									$last_time1 = $datetime;
									$latlast = $lat2;
									$lnglast =  $lng2;
								}
								$tmp_time_diff = ((double)( strtotime($datetime) - strtotime($last_time) )) / 3600;
									   
									 
								if($tmp_speed<500.0 && $distance>0.1 && $tmp_time_diff>0.0)
								{														
									$total_dist = (double)( $total_dist + $distance );
									$daily_dist= (double) ($daily_dist + $distance);	
									$daily_dist = round($daily_dist,2);							                          
									//echo "<br>daily_dist=".$daily_dist;                                    	
									//echo "<br>dist greater than 0.025: dist=".$total_dist." time2=".$time2;
									$lat1 = $lat2;
									$lng1 = $lng2;
									$last_time = $datetime;      						
									//////// TMP VARIABLES TO CALCULATE LAST XML RECORD  //////
									$vname_tmp  = $vname;
									$vserial_tmp = $vserial;
									$time1_tmp = $time1;
									$time2_tmp = $time2;
									$total_dist_tmp = $total_dist;
									//echo "<br>distance=".$distance." ,total_dist=".$total_dist;    			
									////// TMP CLOSED	////////////////////////////////////////                  		    						
								}      					
								//echo "<br>REACHED-2";
								if( ($date_secs2 >= $date_secs1))
								{
									$distance_data[]=array("deviceImeiNo"=>$vserial,"vehicleName"=>$vname,"dateFrom"=>$time1,"dateTo"=>$time2,"distance"=>$total_dist);
									$time1 = $datetime;
									$date_secs1 = strtotime($time1);
									$date_secs1 = (double)($date_secs1 + $interval);	                  
									$total_dist = 0.0;	 
									$lat1 = $lat2;
									$lng1 = $lng2;																		
								}  //if datesec2 
								//echo "<br>REACHED-3";		                                                                        									                               
							}   // else closed      				    				
						} // $xml_date_current >= $startdate closed     			
					}   // if xml_date!null closed	    		    		
					$j++;          
					$f++;
				}   // while closed
			} // if original_tmp closed       	    	      				
			fclose($xml);            
			unlink($xml_original_tmp);
		} // if (file_exists closed
	}  // for closed 
	//return json_encode($distance_data);
	return json_encode($distance_data);
}
$server = new soap_server();
$server->register("get_distance_xml_data");
$server->service($HTTP_RAW_POST_DATA);
?>