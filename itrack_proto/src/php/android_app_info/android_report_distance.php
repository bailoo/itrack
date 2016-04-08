<?php
//error_reporting(-1);
//ini_set('display_errors', 'On');
set_time_limit(3000);
$pathInPieces = explode(DIRECTORY_SEPARATOR ,dirname(__FILE__));
//print_r($pathInPieces);
//$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2]."/".$pathInPieces[3];
$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2];
//echo "pathToRoot=".$pathToRoot."<br>";
include_once('util_android_php_mysql_connectivity.php');  	   //util_session_variable.php sets values in session
include_once('util_android_session_variable.php');   //util_php_mysql_connectivity.php make set connection of user to database  
include_once("android_calculate_distance.php");
require_once "lib/nusoap.php"; 

//====cassamdra //////////////
    include_once($pathToRoot.'/beta/src/php/xmlParameters.php');
    include_once($pathToRoot.'/beta/src/php/parameterizeData.php'); /////// for seeing parameters
    include_once($pathToRoot.'/beta/src/php/data.php');   
    include_once($pathToRoot.'/beta/src/php/getDeviceDataTest.php');
    
////////////////////////

   /* $deviceImeiNo="862170017134329";
            $startDate="2015/08/06 00:00:00";
            $endDate="2015/08/06 16:38:36";
            $userInterval="60";
$result=getDistanceDeviceData($deviceImeiNo, $startDate, $endDate, $userInterval);
echo $result;*/

$DEBUG = 0;		
global $distance_data;
$distance_data=array();	
function getDistanceDeviceData($deviceImeiNo, $startDate, $endDate, $userInterval)
{
    $parameterizeData=new parameterizeData();
    $ioFoundFlag=0;
    global $o_cassandra;
     $requiredData="All";
     $sortBy='h';
    $parameterizeData->latitude="d";
    $parameterizeData->longitude="e";
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
	//$distance_data[]=$datefrom;
	//$distance_data[]=$dateto;
        get_All_Dates($dateRangeStart, $dateRangeEnd, $userdates);    
        $date_size = sizeof($userdates);  
        //echo "Size=".$date_size."<br>";
	$current_datetime = date("Y-m-d H:i:s");
	$current_date = date("Y-m-d");
	//print "<br>CurrentDate=".$current_date;
	
       // print_r($userdates);
        $j = 0;
	$total_dist = 0.0;
 									
	for($i=0;$i<=($date_size-1);$i++)
        {
            $SortedDataObject=new data();
            //echo "date=".$userdates[$i]."<br>";
            readFileXmlNew($deviceImeiNo,$userdates[$i],$requiredData,$sortBy,$parameterizeData,$SortedDataObject);
            //var_dump($SortedDataObject);
            $startdate1 = $startdate;
            $enddate1 = $enddate; 
            //echo "arrCnt=".count($SortedDataObject->deviceDatetime)."<br>";
            if(count($SortedDataObject->deviceDatetime)>0)
            {		  
                $t=time();

                $total_lines = count(file($xml_original_tmp));
                $xml = @fopen($xml_original_tmp, "r") or $fexist = 0; 
                $logcnt=0;
                $DataComplete=false;                  
                $vehicleserial_tmp=null;
                $format =2;
                $c = -1;      
                $f=0;


                        $daily_dist =0;
                        //echo "<br>exist original";

                        //set_master_variable($userdates[$i]);
                    $prevSortedSize=sizeof($SortedDataObject->deviceDatetime);
                    for($obi=0;$obi<$prevSortedSize;$obi++)
                    {
                        $c++;
                        $DataValid = 0;
                        $lat = $SortedDataObject->latitudeData[$obi];
                        $lng = $SortedDataObject->longitudeData[$obi];
                       // echo "lat=".$lat."lng=".$lng."<br>";
                        if((strlen($lat)>5) && ($lat!="-") && (strlen($lng)>5) && ($lng!="-"))
                        {
                            $DataValid = 1;
                        }
                        $datetime=$SortedDataObject->deviceDatetime[$obi];
                        $xml_date=$datetime;
                        //echo "datatime=".$datetime."<br>";
                        if($xml_date!=null)
                        {					  
                                       //echo "<br>".$xml_date.",".$startdate.",".$enddate.",".$DataValid;
                            //$lat = $lat_value[1] ;
                            //$lng = $lng_value[1];    					
                            //echo "<br>xml_date=".$xml_date." ,end_date=".$enddate." ,data_valide=".$DataValid;      			
                            //if( ($xml_date >= $startdate1 && $xml_date <= $enddate1) && ($xml_date!="-") && ($DataValid==1) )

                            //if( ($xml_date >= $startdate && $xml_date <= $enddate) && ($xml_date!="-") && ($DataValid==1) )
                            if($DataValid==1 && ($SortedDataObject->deviceDatetime[$obi]>$startdate1 && $SortedDataObject->deviceDatetime[$obi]<$enddate1))
                            {                          
                                if($firstdata_flag==0)
                                {  
                                    $firstdata_flag = 1;

                                    $lat1 = $lat;
                                    $lng1 = $lng;

                                    //echo "<br>DateSec1 before=".$date_secs1." time_int=".$user_interval;
                                    $interval = (double)$userInterval*60;							

                                    $time1 = $datetime;					
                                    $date_secs1 = strtotime($time1);					
                                    //echo "<br>DateSec1 before=".$date_secs1." time_int=".$interval;
                                    $date_secs1 = (double)($date_secs1 + $interval); 
                                    $date_secs2 = 0; 
                                    $last_time = $datetime;
                                    $last_time1 = $datetime;
                                    $latlast = $lat;
                                    $lnglast =  $lng;
                                    //echo "<br>FirstData:".$date_secs1." ".$time1;
                                }
                                else
							{ 
								$time2 = $datetime;											
								$date_secs2 = strtotime($time2);	
								$vserial=$vehicle_serial;													                                      													      					
								$lat2 = $lat;      				        					
								$lng2 = $lng; 			
								calculate_distance($lat1, $lat2, $lng1, $lng2, $distance);
								$tmp_time_diff1 = (double)(strtotime($datetime) - strtotime($last_time1)) / 3600;
						
								calculate_distance($latlast, $lat2, $lnglast, $lng2, $distance1);
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
                                                                
                                                                
                                                                
                                                                
                                                                
                                                                
                                                                // echo "<br>Total lines orig=".$total_lines." ,c=".$c;
                            $time2 = $datetime;											
                            $date_secs2 = strtotime($time2);	
                            														                                      													      					
                            $lat2 = $lat;      				        					
                            $lng2 = $lng; 
                            calculate_distance($lat1, $lat2, $lng1, $lng2, $distance);
                            if($distance>2000)
                            {
                                $distance=0;
                                $lat1 = $lat2;
                                $lng1 = $lng2;
                            }
                            //echo "<br>lat1=".$lat1." ,lat2=".$lat2." ,lng1=".$lng1." ,lng2=".$lng2." ,dist=".$distance." ,datetime=".$datetime;
                            $tmp_time_diff1 = (double)(strtotime($datetime) - strtotime($last_time1)) / 3600;
                            calculate_distance($latlast, $lat2, $lnglast, $lng2, $distance1);
                            //echo "<br>latlast=".$latlast." ,lat2=".$lat2." ,lnglast=".$lnglast." ,lng2=".$lng2." ,distance1=".$distance1." , datetime=".$datetime."<br>";

                            $tmp_time_diff = ((double)( strtotime($datetime) - strtotime($last_time) )) / 3600;

                            if($tmp_time_diff1>0)
                            {									
                                $tmp_speed = ((double) ($distance)) / $tmp_time_diff;
                                $tmp_speed1 = ((double) ($distance1)) / $tmp_time_diff1;
                            }
                            else
                            {
                                $tmp_speed1 = 1000.0; //very high value
                            }
                            if($tmp_speed<300.0)
                            {
                                $speeed_data_valid_time = $datetime;
                            }

                            if(( strtotime($datetime) - strtotime($speeed_data_valid_time) )>300) //data high speed for 5 mins
                            {
                                $lat1 = $lat2;
                                $lng1 = $lng2;
                                $last_time = $datetime;
                            }
                            $last_time1 = $datetime;
                            $latlast = $lat2;
                            $lnglast =  $lng2;
                            
                            //echo "lat1=".$lat1."lng1=".$lng1."lat2=".$lat2." lng2=".$lng2."<br>";
                            //echo "datetime=".$datetime." distance=".$distance." total_dist=".$total_dist." tmpspeed=".$tmp_speed." tmpspeed1=".$tmp_speed1." tmp_time_diff=".$tmp_time_diff." tmp_time_diff1=".$tmp_time_diff1."<br>";

                            if($tmp_speed<300.0 && $tmp_speed1<300.0 && $distance>0.1 && $tmp_time_diff>0.0 && $tmp_time_diff1>0)
                            {								
                                $total_dist = (double)( $total_dist + $distance );                                  
                                $daily_dist= (double) ($daily_dist + $distance);	
                                $daily_dist = round($daily_dist,2);							                          
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
                            {                                //reassign time1
                                $distance_data[]=array("deviceImeiNo"=>$vserial,"vehicleName"=>$vname,"dateFrom"=>$time1,"dateTo"=>$time2,"distance"=>$total_dist);
                                $time1 = $datetime;
                                $date_secs1 = strtotime($time1);
                                $date_secs1 = (double)($date_secs1 + $interval);		    									    						    						
                                //echo "<br>datesec1=".$datetime;    						                  
                                $total_dist = 0.0;															
                            }  //if datesec2       			
                                                                
                                                                
							}   // else closed      				    				
						} // $xml_date_current >= $startdate closed     			
					}   // if xml_date!null closed	    		    		
					$j++;          
					$f++;
				}   // while closed
		      	  $distance_data[]=array("deviceImeiNo"=>$vserial,"vehicleName"=>$vname,"dateFrom"=>$time1,"dateTo"=>$time2,"distance"=>$total_dist);
                $time1 = $datetime;
                $date_secs1 = strtotime($time1);
                $date_secs1 = (double)($date_secs1 + $interval);		    									    						    						
                //echo "<br>datesec1=".$datetime;    						                  
                $total_dist = 0.0;	 
                $lat1 = $lat2;
                $lng1 = $lng2;
                $SortedDataObject=null;   	      				
			
		} // if (file_exists closed
	}  // for closed
        $parameterizeData=null;
        $o_cassandra->close(); 
	//return json_encode($distance_data);
	return json_encode($distance_data);
}

$server = new soap_server();
$server->register("getDistanceDeviceData");
$server->service($HTTP_RAW_POST_DATA);
?>