<?php
set_time_limit(80000);
//include_once('util_session_variable.php');
//include_once('util_php_mysql_connectivity.php');
include_once('common_xml_element.php');
include_once("get_all_dates_between.php");
include_once("calculate_distance.php");
$vserial = "862170014122293,862170014122905,359231039589781,862170017810829,862170017809466,862170016975755,862170016973073,861074026113574,861074026113301,861074026113798,861074026114416,862170018377927,359231031730235,862170018371714,862170018372506,862170018372274,862170018372159,862170018371219,862170018371987,359231030288979,359231030157885,359231030298259,359231031721465,359231039742083,861074026113087,862170018367373,862170018371748,359231035567419,862170018364230,862170018377844,862170014122491,862170014123325,861074026114259,862170018368694,359231039742554,862170018315182,862170018370542,862170018381689,862170014123309,359231031721002,862170018314599,862170014330227,862170018323962,359231031711649,862170017809870,862170017810324,862170014374142,862170014122137,359231031742503,359231031742719";
$vSerial1=explode(",",$vserial);
$vSerialSize=sizeof($vSerial1);
  $t=time();
  $xmltowrite = "distanceFileGet.xml";
  echo "xmltowrite=".$xmltowrite."<br>";
  //echo "<br>xml1=".$xmltowrite;
  $date1="2014-12-30 00:00:00";
  $date2="2014-12-30 23:59:59";
  $user_interval=60;
  
	$maxPoints = 1000;
	$file_exist = 0;
	$fh = fopen($xmltowrite, 'w') or die("can't open file 3"); // new
	fwrite($fh, "<t1>");  
	//fclose($fh);
	for($in=0;$in<$vSerialSize;$in++)
	{
		//echo "vSerial1=".$vSerial1[$in]."<br>";
		get_distance_xml_data($vSerial1[$in], $date1, $date2, $user_interval, $xmltowrite,$fh);
	}
	fwrite($fh, "\n</t1>");  
	fclose($fh);


function get_distance_xml_data($vehicle_serial, $startdate, $enddate, $user_interval, $xmltowrite,$fh)
{
	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
	global $old_xml_date;
	//include('common_xml_element_for_function.php');
	//new_xml_variables();
	//echo "<br>vserial=".$vehicle_serial." ,vname=".$vname." ,st=".$startdate." ,ed=".$enddate;
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
	//echo "datefrom=".$datefrom."dateto=".$dateto."<br>";
	$timefrom = $date_1[1];
	$timeto = $date_2[1];

	get_All_Dates($datefrom, $dateto, &$userdates);

	////date_default_timezone_set("Asia/Calcutta");
	$current_datetime = date("Y-m-d H:i:s");
	$current_date = date("Y-m-d");
	//print "<br>CurrentDate=".$current_date;
	$date_size = sizeof($userdates);

	//$fh = fopen($xmltowrite, 'a') or die("can't open file 6"); //append

	$j = 0;
	$total_dist = 0.0;
 									
	for($i=0;$i<=($date_size-1);$i++)
	{
		$startdate1 = $startdate;
		$enddate1 = $enddate;		
		$xml_file ="../../../sorted_xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";
		//echo "<br>xml in get_halt_xml_data =".$xml_file;	 
		//echo "xml_file=".$xml_file."<br>";	
		
			//echo "in exit<br>";
			$t=time();			
			$total_lines = count(file($xml_file));
			//echo "<br>Total lines orig=".$total_lines;

			$xml = @fopen($xml_file, "r") or $fexist = 0;  
			//$xmllog = fopen($xml_log, "w") or $fexist1 = 0;
			$logcnt=0;
			$DataComplete=false;                  
			$vehicleserial_tmp=null;
			$format =2;
			$c = -1;

			$f=0;
      
			if (file_exists($xml_file)) 
			{              
				$daily_dist =0;
				echo "<br>exist original";
				
				set_master_variable($userdates[$i]);
				//echo "next<br>";
				
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
								
  				
					if( (preg_match('/'.$vd.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) &&  (preg_match('/'.$ve.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
					{ 
						$lat_value = explode('=',$lat_match[0]);
						$lng_value = explode('=',$lng_match[0]);
						//echo " lat_value=".$lat_value[1];         
						//if( (strlen($lat_value[1])>5) && ($lat_value[1]!="-") && (strlen($lng_value[1])>5) && ($lng_value[1]!="-") )
						if((strlen($lat_value[1])>5) && ($lat_value[1]!="-") && (strlen($lng_value[1])>5) && ($lng_value[1]!="-") && (strpos($lat_value[1],'.')==3 && strpos($lng_value[1],'.')==3))
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
						if(($xml_date >= $startdate1 && $xml_date <= $enddate1  && $xml_date >= $xml_date_latest && $xml_date<=($userdates[$i]." 23:59:59")) && ($xml_date!="-") && ($DataValid==1))
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
                      				
							//echo "<br>first=".$firstdata_flag;                                        
							if($firstdata_flag==0)
							{					
								$firstdata_flag = 1;
								$lat1 = $lat;
								$lng1 = $lng;
								//echo "<br>DateSec1 before=".$date_secs1." time_int=".$user_interval;
								$interval = (double)$user_interval*60;
								$time1 = $datetime;					
								$date_secs1 = strtotime($time1);					
								//echo "<br>DateSec1 before=".$date_secs1." time_int=".$interval;
								$date_secs1 = (double)($date_secs1 + $interval); 
								$date_secs2 = 0;  
								$last_time1 = $datetime;
								$latlast = $lat;
								$lnglast =  $lng;                	
							}
							
							else
							{
								$time2 = $datetime;											
								$date_secs2 = strtotime($time2);
								$vserial=$vehicle_serial;														                                      													      					
								$lat2 = $lat;      				        					
								$lng2 = $lng; 
								calculate_distance($lat1, $lat2, $lng1, $lng2, &$distance);
								if($distance>2000)
								{
									$distance=0;
									$lat1 = $lat2;
									$lng1 = $lng2;
								}
								
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
								//echo "$date_secs2".$date_secs2." $date_secs1".$date_secs1;
								if( ($date_secs2 >= $date_secs1))// || ($f == $total_lines-5))
								{
									//calculate_distance($lat1, $lat2, $lng1, $lng2, &$distance);
									// echo "<br>IN DATESEC";                                                  						
									$distance_data = "\n<marker imei=\"".$vehicle_serial."\" distance=\"".$total_dist."\"/>";						          						
									//echo "<br>distance_data=".$distance_data;
									$linetowrite = $distance_data; // for distance       // ADD DISTANCE
									fwrite($fh, $linetowrite);  		

									//reassign time1
									$time1 = $datetime;
									$date_secs1 = strtotime($time1);
									$date_secs1 = (double)($date_secs1 + $interval);		    									    						    						
									//echo "<br>datesec1=".$datetime;    						                  
									$total_dist = 0.0;	 

									$lat1 = $lat2;
									$lng1 = $lng2;
									///////////////////////																
								}  //if datesec2       					
      					//echo "<br>REACHED-3";		                                                                        									                               
							}   // else closed     				    				
						} // $xml_date_current >= $startdate closed
						//echo "<br>REACHED-5";      			
					}   // if xml_date!null closed       		
					//echo "<br>REACHED-6";              		    		    		
					$j++;          
					$f++;
					//echo "<br>REACHED-7";
				}   // while closed
			} // if original_tmp closed 		
			//fclose($xml);           
			
	
	}  // for closed 
	//echo "<br>XmlDate=".$xml_date." ,enddate=".$enddate;
	//if(($xml_date >= $enddate1))
	{                                                						
		$distance_data = "\n<marker imei=\"".$vehicle_serial."\" distance=\"".$total_dist."\"/>";						          						
		$linetowrite = $distance_data; // for distance       // ADD DISTANCE
		fwrite($fh, $linetowrite); 
		//reassign time1
		$time1 = $datetime;
		$date_secs1 = strtotime($time1);
		$date_secs1 = (double)($date_secs1 + $interval);		    									    						    						
		//echo "<br>datesec1=".$datetime;    						                  
		$total_dist = 0.0;	 
		$lat1 = $lat2;
		$lng1 = $lng2;														
	}  //if datesec2*/
	//echo "Test1";
	//fclose($fh);
}	 
?>
