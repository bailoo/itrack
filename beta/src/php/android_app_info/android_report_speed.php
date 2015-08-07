<?php
include_once('util_android_php_mysql_connectivity.php');  	   //util_session_variable.php sets values in session
include_once('util_android_session_variable.php');   //util_php_mysql_connectivity.php make set connection of user to database  
include_once("android_calculate_distance.php");
require_once "lib/nusoap.php"; 

$pathInPieces = explode(DIRECTORY_SEPARATOR ,dirname(__FILE__));
//print_r($pathInPieces);
$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2];
//echo "pathToRoot=".$pathToRoot."<br>";
	//====cassamdra //////////////
  include_once($pathToRoot.'/beta/src/php/xmlParameters.php');
    include_once($pathToRoot.'/beta/src/php/parameterizeData.php'); /////// for seeing parameters
    include_once($pathToRoot.'/beta/src/php/data.php');   
    include_once($pathToRoot.'/beta/src/php/getDeviceDataTest.php');
////////////////////////
    /*$deviceImeiNo="862170017134329";
    $startDate="2015/08/06 00:00:00";
    $endDate="2015/08/06 16:38:36";
    $userInterval="5";
   
    $result=getSpeedDeviceDataPrev($vehicleSerial,$startDate,$endDate,$userInterval);
    echo $result;*/
           
    function getSpeedDeviceDataPrev($vehicleSerial,$startDate,$endDate,$userInterval)
    {
	set_time_limit(800);
	$DEBUG = 0;
	$device_str = $vehicleSerial;
	//$device_str="862170017134329:";
	$device_str=substr($device_str,0,-1); 
	//echo "<br>devicestr=".$device_str;
	$vserial = explode(':',$device_str);
	$vsize=count($vserial);
	$date1 = $startDate;
	$date2 = $endDate;
	/*$date1 = "2014/02/19 00:00:02";
	$date2 = "2014/02/21 23:46:54";*/
	$date1 = str_replace("/","-",$date1);
	$date2 = str_replace("/","-",$date2);
	//echo "date1=".$date1." date2=".$date2."<br>";
	$user_interval = $userInterval;
	//$user_interval = "1";
	//echo "user_interval=".$user_interval."<br>";
	global $speed_data;
	$speed_data=array();
	if($vsize>0)
	{
            $parameterizeData=new parameterizeData();
            $ioFoundFlag=0;
            global $o_cassandra;

            $parameterizeData->latitude="d";
            $parameterizeData->longitude="e";
            $current_dt = date("Y_m_d_H_i_s");	
            //$xmltowrite = "/../../../xml_tmp/filtered_xml/tmp_".$current_dt.".xml";
            for($i=0;$i<sizeof($vserial);$i++)
            { 
                    $Query="SELECT vehicle.vehicle_name,vehicle.vehicle_type,vehicle.vehicle_number FROM vehicle USE INDEX(v_vehicleid_status)".
                    ",vehicle_assignment USE INDEX(va_vehicleid_status,va_vehicleid_imei_status) WHERE vehicle.vehicle_id=vehicle_assignment.".
                    "vehicle_id AND vehicle.status=1 AND vehicle_assignment.status=1 AND vehicle_assignment.device_imei_no='$vserial[$i]'";
                    //echo "Query=".$Query."<br>";
                    $Result=mysql_query($Query,$DbConnection);
                    $Row=mysql_fetch_row($Result);
                   // echo   "<br>vserial[i] =".$vserial[$i];
                    getSpeedDeviceData($vserial[$i], $Row[0], $date1, $date2, $user_interval,$parameterizeData,$o_cassandra);
                    //echo   "t2".' '.$i;
            }           
            $parameterizeData=null;
            $o_cassandra->close();
            return json_encode($speed_data);
	}
    }   
	function getSpeedDeviceData($vehicle_serial, $vname, $startdate, $enddate, $user_interval, $parameterizeData,$o_cassandra)
	{
            //echo   "<br>vserial1 =".$vehicle_serial;
              $requiredData="All";
     $sortBy='h';
		global $speed_data;
		$total_speed_tmp = 0;
		$total_time_tmp = 0;	
	
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
		$dateRangeStart = $date_1[0];
		$dateRangeEnd = $date_2[0];
		$timefrom = $date_1[1];
		$timeto = $date_2[1];
		//echo "dateFrom=".$datefrom." dateTo=".$dateto."<br>";
                get_All_Dates($dateRangeStart, $dateRangeEnd, $userdates);    
                $date_size = sizeof($userdates); 
		//date_default_timezone_set("Asia/Calcutta");
		$current_datetime = date("Y-m-d H:i:s");
		$current_date = date("Y-m-d");
		
		$count = 0;
		$j = 0;	
		$avg_speed = null;
		$max_speed = null;	
		$total_avg_speed = null;
		$total_max_speed = null;  									
		for($i=0;$i<=($date_size-1);$i++)
		{
			$SortedDataObject=new data();
                        //echo "date=".$userdates[$i]."<br>";
                        //echo "date=".$parameterizeData->latitude."<br>";
            readFileXmlNew($vehicle_serial,$userdates[$i],$requiredData,$sortBy,$parameterizeData,$SortedDataObject);
            //var_dump($SortedDataObject);	

			if(count($SortedDataObject->deviceDatetime)>0)
			{	
				//echo "in if";		
				$t=time();
				
				$DataComplete=false;
				$vehicleserial_tmp=null;
				$format =2;
				if(count($SortedDataObject->deviceDatetime)>0)
				{
				
					$speed_threshold = 1;
					$start_runflag = 0;
					$stop_runflag = 1;
					$total_speed = 0.0;
					$r1 =0;
					$r2 =0;
					$StopTimeCnt = $xml_date;
					$StopStartFlag = 0;        
					$runtime_start = array();
					$runtime_stop = array();
            
					$prevSortedSize=sizeof($SortedDataObject->deviceDatetime);
                                        for($obi=0;$obi<$prevSortedSize;$obi++)
                                        {
                                            $DataValid = 0;
                                            $lat = $SortedDataObject->latitudeData[$obi];
                                            $lng = $SortedDataObject->longitudeData[$obi];
                                            if((strlen($lat)>5) && ($lat!="-") && (strlen($lng)>5) && ($lng!="-"))
                                            {
                                                $DataValid = 1;
                                            }
                                            $datetime=$SortedDataObject->deviceDatetime[$obi];
                                            $xml_date=$datetime;				
						//echo "Final0=".$xml_date." datavalid=".$DataValid;          
						if($xml_date!=null)
						{				  
							//echo "<br>".$xml_date.",".$startdate.",".$enddate.",".$DataValid;
							//$lat = $lat_value[1] ;
							//$lng = $lng_value[1];
                                                    
							 if($DataValid==1 && ($SortedDataObject->deviceDatetime[$obi]>$startdate && $SortedDataObject->deviceDatetime[$obi]<$enddate))
                                                            {							           	
								//echo "<br>One";             
								//$vserial = get_xml_data('/'.$vv.'="[^"]+"/', $line);
								$vserial=$vehicle_serial;							          	
								$speed =    $speed =$SortedDataObject->speedData[$obi];
												   
								//echo "<br>first=".$firstdata_flag;                                        
								if($firstdata_flag==0)
								{
									//echo "<br>FirstData";
									$firstdata_flag = 1;                
									$lat1 = $lat;
									$lng1 = $lng; 
									///////// FIXING SPEED PROBLEM ///////////            
									$speed_str = $speed;
									if($speed_str > 200)
									{
										$speed_str =0;
									}
                
									$speed_tmp = "";
									for ($x = 0, $y = strlen($speed_str); $x < $y; $x++) 
									{
										if($speed_str[$x]>='0' && $speed_str[$x]<='9')
										{
											$speed_tmp = $speed_tmp.$speed_str[$x];
										}      
										else
										{
											$speed_tmp = $speed_tmp.".";
										}  
									}
									$speed = $speed_tmp;  
									$speed = round($speed,2);  
									//echo "speed=".$speed_tmp;    
									///////////////////////////////////////////
									$speed_arr[$j] = $speed;			
									$time1 = $datetime;
									$date_secs1 = strtotime($time1);
									//echo "<br>DateSec1 before=".$date_secs1." time_int=".$user_interval;
									$interval = (double)$user_interval*60*60;
									$date_secs1 = (double)($date_secs1 + $interval);  							
									//echo "<br>DateSec1 after=".$date_secs1;	      
                
									if(($speed > $speed_threshold) && ($start_runflag==0))   // START
									{
										//echo "<br>start condition1";
										$runtime_start[$r1] = $xml_date;
										$r1++;
										$start_runflag = 1;
										$stop_runflag = 0; 
										$StopStartFlag = 0;
									}                                  	
								} 
								//echo "<br>k2=".$k2."<br>";
								else
								{                           
									///////// FIXING SPEED PROBLEM ///////////            
									$speed_str = $speed;
									if($speed_str > 200)
									{
										$speed_str =0;
									}							  
									$speed_tmp = "";
									for ($x = 0, $y = strlen($speed_str); $x < $y; $x++) 
									{
										if($speed_str[$x]>='0' && $speed_str[$x]<='9')
										{
											$speed_tmp = $speed_tmp.$speed_str[$x];
										}      
										else
										{
											$speed_tmp = $speed_tmp.".";
										}  
									}
									$speed = $speed_tmp;  
									$speed = round($speed,2);                                                                        
									$speed_arr[$j] = $speed;   
									///////////////////////////////////////////   											
									$time2 = $datetime;											
									$date_secs2 = strtotime($time2);			
									//echo "<br>speed=".$speed." ,time=".$time2;
									$lat2 = $lat;
									$lng2 = $lng;			
									calculate_distance($lat1, $lat2, $lng1, $lng2, $distance);			
									//if($distance>0.25)
									if($distance>0.1)
									{	                                     													
										$total_dist = (float) ($total_dist + $distance);	
										//echo "<br>dist greater than 0.025: dist=".$total_dist." time2=".$time2;
										$lat1 = $lat2;
										$lng1 = $lng2;
									
										//////// TMP VARIABLES TO CALCULATE LAST XML RECORD  //////
										$vname_tmp  = $vname;
										$vserial_tmp = $vserial;
										$time1_tmp = $time1;
										$time2_tmp = $time2;
										$total_dist_tmp = $total_dist;    			
										////// TMP CLOSED	////////////////////////////////////////		    						
									}							
									//echo "<br>Else-speed=".$speed." ,start_runflag=".$start_runflag." ,stop_runflag=".$stop_runflag;                   
									if(($speed < $speed_threshold) && ($stop_runflag ==0))   // STOP 
									{
										if(((strtotime($xml_date) - strtotime($StopTimeCnt))>15) && ($StopStartFlag==1))
										{
											//echo ", stop<br>";
											$runtime_stop[$r2] = $xml_date;
											$r2++;
											$stop_runflag = 1;
											$start_runflag = 0;
										}
										else if($StopStartFlag==0)
										{
											$StopTimeCnt = $xml_date;
											$StopStartFlag = 1;
										}
									}										  
									if($speed > $speed_threshold && ($start_runflag ==0) && ($distance>0.1)  )    // START
									{
										//echo "<br>start";
										$runtime_start[$r1] = $xml_date;
										$r1++;
										$start_runflag =1;
										$stop_runflag = 0;
										$StopStartFlag = 0;
									} 														
									if($date_secs2 >= $date_secs1)
									{
										//echo "<br>In SpeedAction";
										/////////
										if(sizeof($runtime_start) == 0)
										{
											$total_runtime =0;	
										}
										//echo "<br>SIZE1=".sizeof($runtime_start)." ,SIZE2=".sizeof($runtime_start);					  
										//if( (sizeof($runtime_stop) == 0) && (sizeof($runtime_start)>0) )
										if( ((sizeof($runtime_stop)) == (sizeof($runtime_start)-1)) )  
										{
											//echo "<br>A:RunStop";
											$runtime_stop[$r2] = $xml_date;
											$stop_runflag = 1;
											$start_runflag = 0; 
											$r2++;
										}			  
										$total_runtime = 0;
										for($m=0;$m<(sizeof($runtime_start));$m++)
										{
											//echo "<br>A:run1=".$runtime_stop[$m]." ,run2=".$runtime_start[$m]."<br>";                   
											$runtime = strtotime($runtime_stop[$m]) - strtotime($runtime_start[$m]);
											$total_runtime = $total_runtime + $runtime;
											//echo "<br>A:runtime=".$runtime." ,total_runtime=".$total_runtime;                    
										}                 
										//echo "<br>total_speed=".$total_speed." ,total_runtime1=".$total_runtime."<br>";
										//$total_test_time = $total_test_time + $total_runtime;															
										if(($interval>=1800) && ($total_dist<3.0))
										{
											$total_dist = 0.0;
										} 
										else
										{
											$total_dist = round($total_dist,3);
										}						
										$avg_speed = ($total_dist / $total_runtime)*3600;                  
										/////////
										//$avg_speed = array_sum($speed_arr)/sizeof($speed_arr);	
										$avg_speed = round($avg_speed,2);
										$max_speed = max($speed_arr);
										$max_speed = round($max_speed,2);							
										//echo "<BR><br>SPEED THRESHOLD=".$speed_threshold." ,TOTAL DISTANCE(km) = ".$total_dist."<BR>TOTAL RUNTIME(seconds)= ".$total_runtime." <BR>AVERAGE SPEED = ".$avg_speed." <BR>MAX SPEED = ".$max_speed." <BR>TOTAL SPEED = ".$total_speed_tmp." <BR>TIME1 = ".$time1." <BR>TIME2 = ".$time2."<BR>----------------------------------------------------------";							
										/*if($avg_speed ==0)
										{
											$max_speed = 0;
										}*/
										if( ($avg_speed > $max_speed) && ($max_speed > 2.0) )
										{
											$avg_speed = $max_speed - 2;
										}              
										else if(($avg_speed > $max_speed) && ($max_speed > 0.2) && ($max_speed <= 2.0))
										{								
											$avg_speed = $max_speed - 0.2;
										}							              							
										//echo "avg_speed=".$avg_speed."<br>";
										if($avg_speed<150)
										{							
											$speed_data[]=array("deviceImeiNo"=>$vserial,"vehicleName"=>$vname,"dateFrom"=>$time1,"dateTo"=>$time2,"avgSpeed"=>$avg_speed,"runTime"=>$total_runtime,"maxSpeed"=>$max_speed,"distance"=>$total_dist);
										}
										//reassign time1
										$time1 = $datetime;
										$date_secs1 = strtotime($time1);
										$date_secs1 = (double)($date_secs1 + $interval);		
										$speed_arr = null;
										$j=0;
										$avg_speed = 0.0;
										$total_dist = 0.0;
										$runtime_start = array();
										$runtime_stop = array();
										$start_runflag = 0;
										$stop_runflag = 1;
										$total_runtime =0; 
										$r1 = 0;
										$r2 = 0;                  	 						                  				
										///////////////////////
									}											                               
								}
							} // $xml_date_current >= $startdate closed
						}   // if xml_date!null closed
						$count++;
						$j++;
					}   // while closed
				} // if original_tmp exist closed       
				fclose($xml);            
				unlink($xml_original_tmp);
			} // if (file_exists closed
		}  // for closed 
	}
	//global $speed_data;
	//print_r($speed_data);
	$server = new soap_server();
$server->register("getSpeedDeviceDataPrev");
$server->service($HTTP_RAW_POST_DATA);

?>