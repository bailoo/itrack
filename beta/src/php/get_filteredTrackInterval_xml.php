<?php
set_time_limit(3000);	
date_default_timezone_set("Asia/Kolkata"); 
include_once("main_vehicle_information_1.php");
include_once('Hierarchy.php');
$root=$_SESSION["root"];
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
include_once('user_type_setting.php');
include_once("calculate_distance.php");

include_once('xmlParameters.php');
include_once('parameterizeData.php');
include_once('data.php');
include_once("getXmlData.php");

	
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
$vserial = explode(',',$vserial1) ;   
//print_r($vserial);	
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
    $timeinterval =   ($tmptimeinterval/$maxPoints);
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
        $timeinterval =   $time_interval1;
        $distanceinterval = 0.02;
    }
} 
$sortBy='h';
$requiredData="All";
$parameterizeData=new parameterizeData();
$parameterizeData->latitude="d";
$parameterizeData->longitude="e";
	
get_All_Dates($datefrom, $dateto, $userdates);    
$date_size = sizeof($userdates);
//print_r($userdates);
//echo "mode2=".$mode."<br>";
if($mode==2)
{		
    $vehicle_info=get_vehicle_info($root,$vserial[0]);
    $vehicle_detail_local=explode(",",$vehicle_info);
    $finalVNameArr[$si]=$vehicle_detail_local[0];
    
    $prev_halt = false;
    $norepeat_loc = false;	
    $CurrentLat = 0.0;
    $CurrentLong = 0.0;
    $LastLat = 0.0;
    $LastLong = 0.0;
    $LastDTForDiff = "";
    $firstData = 0;
    $distance =0.0;
   
    for($di=0;$di<=($date_size-1);$di++)
    {
        //echo "userdate=".$userdates[$di]."<br>";
        $SortedDataObject=new data();
        readFileXmlNew($vserial[0],$userdates[$di],$requiredData,$sortBy,$parameterizeData,$SortedDataObject);
        //var_dump($SortedDataObject);
        $logcnt=0;
        $DataComplete=false;
        if(count($SortedDataObject->deviceDatetime)>0)
        {
            $prevSortedSize=sizeof($SortedDataObject->deviceDatetime);
            for($obi=0;$obi<$prevSortedSize;$obi++)
            {		
                $datetime=$SortedDataObject->deviceDatetime[$obi];
                
                $CurrentDTForDiffTmp=strtotime($datetime);
                $CurrentLat = $SortedDataObject->latitudeData[$obi];
                $CurrentLong = $SortedDataObject->longitudeData[$obi]; 
                if((strlen($CurrentLat)>5) && ($CurrentLat!="-") && (strlen($CurrentLong)>5) && ($CurrentLong!="-"))
                {
                    $DataValid = 1;
                }
                if($DataValid==1)
                {  
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

                        //$linetolog = $CurrentLat.','.$CurrentLong.','.$LastLat.','.$LastLong.','.$distance.','.$xml_date_current.','.$xml_date_last.','.(strtotime($xml_date_current)-strtotime($xml_date_last)).','.$timeinterval.','.$distanceinterval.','.$enddate.','.$startdate."\n";

                        $overSpeed=$distance/$dateDifference_1;
                        $xml_date_last=$datetime;
                    }
                    $xml_date_current=$datetime;
                    if($distance<$distanceinterval)
                    {
                        $LastDTForDif=$xml_date_current;
                    }
                    
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
                    if((strtotime($xml_date_current)-strtotime($xml_date_last))>$timeinterval)
                    {
                        if($overSpeed<80)
                        {                           
                            $LastLat =$CurrentLat;
                            $LastLong =$CurrentLong;
                            $LastDTForDif=$xml_date_current;
                            $line = substr($line, 0, -3);   // REMOVE LAST TWO /> CHARARCTER
                            $finalDistance = $finalDistance + $distance;


                            if($norepeat_loc) 
                            {
                                $location_norepeat="1";                                
                            } 
                            else 
                            {
                                $location_norepeat="0";                                
                            }                        
                            $vehicleserialA[]=$vserial[0];
                            $latDisplay[]=$CurrentLat;
                            $lngDisplay[]=$CurrentLong;
                            $noloc[]=$location_norepeat;
                            $firstData = 1; 
                        }
                        $xml_date_last = $xml_date_current;
                    }
                }
            }
            $vehicleserialA[]=$vserial[0];
            $latDisplay[]=$CurrentLat;
            $lngDisplay[]=$CurrentLong;
            $noloc[]=$location_norepeat;
            $SortedDataObject=null;
        }	
    }
}

print_r($vehicleserialA);
print_r($latDisplay);
print_r($lngDisplay);
print_r($noloc);
$parameterizeData=null;
$o_cassandra->close();
	
?>
