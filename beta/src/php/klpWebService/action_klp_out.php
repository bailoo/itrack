<?php
function checkAreaDistance($icdLat,$icdLng,$lat,$lng,$status,$distanceLimit)
{
    //echo "status=".$status." distanceLimit=".$distanceLimit."icdLat=".$icdLat."icdLng=".$icdLng."lat=".$lat."lng=".$lng."<br>";
    calculate_distance($icdLat, $icdLng, $lng, $lng, $distance);
    //echo "distance=".$distance."<br>";
    if($distance < $distanceLimit)
    {
        //echo "\nICD OUT-2";
        $status = true;         
    }
    return $status;
}

function getDataToFillDetail($icdCoord, $cusotmerCoord, $wSInputDataObj, $startdate, $enddate,$DbConnection)
{
    // echo "in function<br>";   
    $parameterizeData=null;
    $parameterizeData=new parameterizeData();

    $parameterizeData->latitude='d';
    $parameterizeData->longitude='e';
    $parameterizeData->speed='f';	
   
    $SortedDataObject=null;
    $SortedDataObject=new data();
    
    $outDistanceLimit=2;
    $inDistanceLimit=1;
    
   // echo "vehcileName=".$wSInputDataObj->vehicleName."<br>";
    
    //echo "imeiNo=".$wSInputDataObj->imeiNo." startDate=".$startdate." endDate=".$enddate."<br>";
    deviceDataBetweenDates($wSInputDataObj->imeiNo,$startdate,$enddate,$sortBy,$parameterizeData,$SortedDataObject);
    //var_dump($SortedDataObject);
    $StatusFlag=0;
    if(count($SortedDataObject->deviceDatetime)>0)
    {
        $firstdata_flag =0;
        $total_dist = 0.0;
        $distance =0.0;
        $userInterval=5;
        $prevSortedSize=sizeof($SortedDataObject->deviceDatetime);                   
        for($obi=0;$obi<$prevSortedSize;$obi++)
        {					
            $DataValid = 0;
            $lat = $SortedDataObject->latitudeData[$obi];
            $lng = $SortedDataObject->longitudeData[$obi];
            $speed = $SortedDataObject->speed[$obi];
            $datetime=$SortedDataObject->deviceDatetime[$obi];
            $xml_date=$datetime;
            if((strlen($lat)>5) && ($lat!="-") && (strlen($lng)>5) && ($lng!="-"))
            {
                $DataValid = 1;
            }
            if($DataValid==1)
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
                    /*if( ($date_secs2 >= $date_secs1))// || ($f == $total_lines-5))
                    {
                        $time1 = $datetime;
                        $date_secs1 = strtotime($time1);
                        $date_secs1 = (double)($date_secs1 + $interval);		    									    						    						
                        //echo "<br>datesec1=".$datetime;    						                  
                        $total_dist = 0.0;															
                    }  //if datesec2 */      					
//echo "<br>REACHED-3";		                                                                        									                               
                }   // else closed  
                if($wSInputDataObj->actualIcdOutDatetime=="")
                {
                    $coord = explode(',',$icdCoord);
                    $icdLat = trim($coord[0]);
                    $icdLng = trim($coord[1]);
                    //echo "olat1=".$icdLat."olng1".$icdLng."olat2=".$lat."olng2=".$lng."oio <br>";
                    $iOStatus=false;
                    $icdOutStatus=checkAreaDistance($icdLat,$lat,$icdLng,$lng,$iOStatus,$outDistanceLimit);
                    //echo "icdOutStatus=".$icdOutStatus."<br>";
                    // echo "lat1=".$icdLat."lng1".$icdLng."lat2=".$lat."lng2=".$lng."icdOutStatus=".$icdOutStatus."<br>";
                    if($icdOutStatus=='')
                    {
                        //update icd out actual time
                        $updateIcdOutDatetime=$datetime;
                        $wSInputDataObj->actualIcdOutDatetime = $datetime;
                        $StatusFlag=1;
                    }
                    else 
                    {
                        continue;
                    }                   
                }
                //echo "actualIcdInDatetime=".$wSInputDataObj->actualIcdInDatetime."<br>";
                if($wSInputDataObj->actualIcdInDatetime=="" && $speed<10)
                {
                    $coord = explode(',',$icdCoord);
                    $icdLat = trim($coord[0]);
                    $icdLng = trim($coord[1]);
                    $iIstatus=false;
                    //echo "olat1=".$icdLat."olng1".$icdLng."olat2=".$lat."olng2=".$lng."oio <br>";
                    $icdInStatus=checkAreaDistance($icdLat,$lat,$icdLng,$lng,$iIstatus,$inDistanceLimit);
                    //echo "icdInStatus=".$icdInStatus."<br>";
                    if($icdInStatus==true)
                    {
                        //update icd out actual time
                        $wSInputDataObj->actualIcdInDatetime=$datetime;
                        $StatusFlag=1;
                    }
                }
                //echo "customerInDatetime=".$wSInputDataObj->customerInDatetime."<br>";
                /*echo "actualIcdIn=".$wSInputDataObj->actualIcdInDatetime."<br>";
                echo "customerInDatetime=".$wSInputDataObj->customerInDatetime."<br>";
                echo "speed=".$speed."<br>";*/
                
                if(($wSInputDataObj->actualIcdOutDatetime!="") && ($wSInputDataObj->customerInDatetime=="" && $speed<10))
                {
                    //echo "in customer";
                    $coord = explode(',',$cusotmerCoord);
                    $customerLat = trim($coord[0]);
                    $customerLng = trim($coord[1]);
                    //echo "lat=".$customerLat."lng=".$customerLng."<br>";
                    $cOStatus=false;
                    $customerInStatus=checkAreaDistance($customerLat,$lat,$customerLng,$lng,$cOStatus,$inDistanceLimit);
                    $wSInputDataObj->actualIcdInDatetime="";
                    //echo "inStatus=".$customerInStatus."<br>";
                    if($customerInStatus==true)
                    {
                        //update icd out actual time
                        $wSInputDataObj->customerInDatetime=$datetime;
                        $StatusFlag=1;
                    }
                }
                //echo "customerOutDatetime=".$wSInputDataObj->customerOutDatetime."<br>";
                if(($wSInputDataObj->actualIcdOutDatetime!="") && ($wSInputDataObj->customerInDatetime!="") && ($wSInputDataObj->customerOutDatetime==""))
                {
                    $coord = explode(',',$cusotmerCoord);
                    $customerLat = trim($coord[0]);
                    $customerLng = trim($coord[1]);
                    $cIStatus=false;
                    $customerOutStatus=checkAreaDistance($customerLat,$lat,$customerLng,$lng,$cIStatus,$outDistanceLimit);
                    
                    if($customerOutStatus=='')
                    {
                        //update icd out actual time
                        $wSInputDataObj->customerOutDatetime=$datetime;
                        $StatusFlag=1;
                    }
                } 
            }
        }
        if($datetime!="")
        {
            $Query="UPDATE vehicle_last_execution_time set last_execution_datetime='$datetime'".
                    " WHERE vehicle_name='$wSInputDataObj->vehicleName'";
            //echo "Query=".$Query."<br>";
            $Result=mysql_query($Query,$DbConnection);          
        }
        if($StatusFlag==1)
        {
            $total_dist = round($total_dist,2);
            $queryDistance="SELECT distance_travelled FROM icd_webservice_data WHERE vehicle_name=".
                            "'$wSInputDataObj->vehicleName' AND icd_in_datetime=''";
            $resultDistance=mysql_query($queryDistance,$DbConnection);
            $rowDistance=  mysql_fetch_row($resultDistance);
            if($rowDistance[0]!="")
            {
                $finalTotalDistance=$total_dist+$rowDistance[0];
            }
            else
            {
                $finalTotalDistance=$total_dist;
            }
            
           $QueryWSDb="UPDATE icd_webservice_data set actual_icd_in_datetime='$wSInputDataObj->actualIcdInDatetime',actual_icd_out_datetime=".
                    "'$wSInputDataObj->actualIcdOutDatetime',factory_in_datetime='$wSInputDataObj->customerInDatetime',factory_out_datetime=".
                    "'$wSInputDataObj->customerOutDatetime',distance_travelled='$finalTotalDistance',".
                    "hourly_distance='$total_dist' WHERE vehicle_name='$wSInputDataObj->vehicleName'".
                    " AND icd_in_datetime=''";
            
            //echo "Query=".$QueryWSDb."<br>";
            $ResultWS=mysql_query($QueryWSDb,$DbConnection); 
        }
    }
}
                    							
?>
						
