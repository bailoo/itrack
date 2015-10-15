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
        $daily_dist =0;
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
                if($wSInputDataObj->actualIcdOutDatetime=="")
                {
                    $coord = explode(',',$icdCoord);
                    $icdLat = trim($coord[0]);
                    $icdLng = trim($coord[1]);
                    //echo "lat1=".$icdLat."lng1".$icdLng."lat2=".$lat."lng2=".$lng."io <br>";
                    $iOStatus=false;
                    $icdOutStatus=checkAreaDistance($icdLat,$lat,$icdLng,$lng,$iOStatus,$outDistanceLimit);
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
                    //break;
                }
                
                if($wSInputDataObj->actualIcdInDatetime=="" && $speed<10)
                {
                    $coord = explode(',',$icdCoord);
                    $icdLat = trim($coord[0]);
                    $icdLng = trim($coord[1]);
                    $iIstatus=false;
                    $icdInStatus=checkAreaDistance($icdLat,$lat,$icdLng,$lng,$iIstatus,$inDistanceLimit);
                    
                    if($icdInStatus==true)
                    {
                        //update icd out actual time
                        $wSInputDataObj->actualIcdInDatetime=$datetime;
                        $StatusFlag=1;
                    }
                }
                
                if(($wSInputDataObj->actualIcdInDatetime=="") && ($wSInputDataObj->customerInDatetime=="" && $speed<10))
                {
                    $coord = explode(',',$cusotmerCoord);
                    $customerLat = trim($coord[0]);
                    $customerLng = trim($coord[1]);
                    $cOStatus=false;
                    $customerInStatus=checkAreaDistance($customerLat,$lat,$customerLng,$lng,$cOStatus,$inDistanceLimit);
                    
                    if($customerInStatus==true)
                    {
                        //update icd out actual time
                        $wSInputDataObj->customerInDatetime=$datetime;
                        $StatusFlag=1;
                    }
                }
                
                if(($wSInputDataObj->actualIcdInDatetime=="") && ($wSInputDataObj->customerInDatetime!="") && ($wSInputDataObj->customerOutDatetime==""))
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
           $QueryWSDb="UPDATE icd_webservice_data set actual_icd_in_datetime='$wSInputDataObj->actualIcdInDatetime',actual_icd_out_datetime=".
                    "'$wSInputDataObj->actualIcdOutDatetime',factory_in_datetime='$wSInputDataObj->customerInDatetime',factory_out_datetime=".
                    "'$wSInputDataObj->customerOutDatetime' WHERE vehicle_name='$wSInputDataObj->vehicleName'";
            
            //echo "Query=".$QueryWSDb."<br>";
            $ResultWS=mysql_query($QueryWSDb,$DbConnection); 
        }
    }
}
                    							
?>
						
