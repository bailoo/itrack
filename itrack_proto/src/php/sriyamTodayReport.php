<?php
//error_reporting(-1);
//ini_set('display_errors', 'On');
set_time_limit(3000); 

include_once('util_php_mysql_connectivity.php');
include_once('xmlParameters.php');   
include_once('parameterizeData.php');
include_once('data.php');
include_once("sortXmlData.php");
include_once("getXmlData.php");
include_once("calculate_distance.php");


$abspath = "/var/www/html/vts/beta/src/php";
//$abspath = "C:\\xampp/htdocs/itrackDevelop/beta/src/php";
 //echo "exist=".file_exists($abspath)."<br>";
include_once($abspath . "/mail_api/mailgun-php/attachment_mailgun.php");
//echo "exist1=".file_exists($abspath . "/mail_api/mailgun-php/attachment_mailgun.php")."<br>";
$query_assignment = "SELECT DISTINCT vehicle.vehicle_id,vehicle.vehicle_name,vehicle_assignment.device_imei_no FROM vehicle,vehicle_assignment,vehicle_grouping WHERE ".
                "vehicle.vehicle_id = vehicle_assignment.vehicle_id AND vehicle_assignment.vehicle_id = vehicle_grouping.vehicle_id AND ".
                "vehicle_grouping.account_id='908' AND vehicle.status=1 AND vehicle_assignment.status=1 AND vehicle_grouping.status=1";					

$result_assignment = mysql_query($query_assignment,$DbConnection);
while($row_assignment = mysql_fetch_object($result_assignment))
{
    $vehicle_name_db[] = array('imeiNo'=>$row_assignment->device_imei_no,
                                'vehicleName'=>$row_assignment->vehicle_name);
}
    
date_default_timezone_set('Asia/Calcutta');
$currentDate=date("Y-m-d");

// echo "priviousDate=".$previousDate."<br>";

/*$startdate = "2015-10-02 00:00:00";
$enddate = "2015-10-02 23:59:59";*/

$startdate = $currentDate." 00:00:00";
$enddate = $currentDate." 23:59:59"; 

$sortBy='h';
$firstDataFlag=0;
$endDateTS=strtotime($date2);
$userInterval = "0";
$requiredData="All";

$parameterizeData=null;
$parameterizeData=new parameterizeData();

$parameterizeData->latitude="d";
$parameterizeData->longitude="e";
$parameterizeData->speed="f";
$tmpCn=0;
foreach($vehicle_name_db as $key=>$vehicleDetailArr)
{       
    $CurrentLat = 0.0;
    $CurrentLong = 0.0;
    $LastLat = 0.0;
    $LastLong = 0.0;
    $firstData = 0;
    $start_time_flag = 0;
    $distance_total = 0;
    $daily_dist=0;
    $distance_threshold = 0.200;
    $datetime_threshold = 300;
    $distance_error = 0.100;
    $distance_incriment =0.0;
    $firstdata_flag =0;  
    $max_speed=0.0;
    $j=0;
    $halt_flag = 0;
    $distance_travel=0;
    $alertMaxSpeed=False;
    $SortedDataObject=null;
    $SortedDataObject=new data();
    $twelveHrHaltFlag=0;
    
    //echo "startDate=".$startdate."endDate=".$enddate."<br>";
    //var_dump($SortedDataObject);
  
   // if($vehicleDetailArr['vehicleName']=="UP78 CN 1765")
    {
          //echo "twelveHrHaltFlag=".$twelveHrHaltFlag."<br>";
         deviceDataBetweenDates($vehicleDetailArr['imeiNo'],$startdate,$enddate,$sortBy,$parameterizeData,$SortedDataObject);
        //echo "in if";
    if(count($SortedDataObject->deviceDatetime)>0)
    {               
        $sortedSize=sizeof($SortedDataObject->deviceDatetime);                 
        for($obi=0;$obi<$sortedSize;$obi++)
        {
           $DataValid=0;
           $lat = $SortedDataObject->latitudeData[$obi];                           
           $lng = $SortedDataObject->longitudeData[$obi];                       
           // echo "lat=".$lat." lng=".$lng." datetime=".$datetime." speed=".$speed."<br>";
            if((strlen($lat)>5) && ($lat!="-") && (strlen($lng)>5) && ($lng!="-"))
            {
                $DataValid = 1;
            }
            if(($DataValid==1))
            {
                $datetime=$SortedDataObject->deviceDatetime[$obi];
                if($firstdata_flag==0)
                {                                
                    $firstdata_flag = 1;
                    $distance_travel=0;                                    

                    $lat_S = $lat;
                    $lng_S = $lng;
                    $datetime_S = $datetime;
                    $datetime_travel_start = $datetime_S; 
                    $datetime_E=$datetime;
                    $lat_travel_start = $lat_S;
                    $lng_travel_start = $lng_S;  
                    $last_time = $datetime;
                    $last_time1 = $datetime;
                    $latlast = $lat;
                    $lnglast =  $lng;  
                    $max_speed	=0.0;
                    $speed_prev = 0.0;
                    $halt_flag = 0;
                    $datetime_ref = $datetime;
                }           	              	
                else
                {
                    $datetime_cr = $datetime;
                    $lat_E = $lat;
                    $lng_E = $lng;
                    $datetime_prev = $datetime_E;
                    $speed_prev = $speed;
                    $datetime_E = $datetime; 

                    calculate_distance($lat_S, $lat_E, $lng_S, $lng_E, $distance_incriment);								         		
                    $tmp_time_diff = (double)(strtotime($datetime) - strtotime($last_time)) / 3600;                
                    //echo "1 tmp_time_diff=".$tmp_time_diff." last_time=".$last_time." datetime".$datetime." distance_incriment=".$distance_incriment." lat_S=".$lat_S." lng_S=".$lng_S." lat_E=".$lat_E." lng_E=".$lng_E."<br>";       
                    calculate_distance($latlast, $lat_E, $lnglast, $lng_E, $distance1);
                    $tmp_time_diff1 = ((double)( strtotime($datetime) - strtotime($last_time1) )) / 3600; 
                    //echo "2 tmp_time_diff1=".$tmp_time_diff1." last_time1=".$last_time1." datetime".$datetime." distance1=".$distance1." latlast=".$latlast." lnglast=".$lnglast." lat_E=".$lat_E." lng_E=".$lng_E."<br>";       
                    if($tmp_time_diff1>0)
                    {
                        $tmp_speed = ((double) ($distance_incriment)) / $tmp_time_diff;
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

                    //echo "3 tmp_speed=".$tmp_speed." tmp_speed1=".$tmp_speed1."<br>";       
                    if((strtotime($datetime) - strtotime($speeed_data_valid_time) )>300) //data high speed for 5 mins
                    {
                        $lat_S = $lat_E;
                        $lng_S = $lng_E;
                        $last_time = $datetime;
                    }

                    $last_time1 = $datetime;
                    $latlast = $lat_E;
                    $lnglast =  $lng_E;
                    //echo"maxspeed=".$max_speed."speed=".$speed."<br>";
                    //echo "haltFlag=".$haltFlag."<br>";
                    $speed = $SortedDataObject->speedData[$obi];

                    //echo "datetime=".$datetime." tmpSpeed=".round($tmp_speed,2)." tmpSpeed1=".round($tmp_speed1,2)." distance_incriment=".$distance_incriment." latS=".$lat_S." latE=".$lat_E." lngs=".$lng_S." lngE=".$lng_E." speed=".$speed."<br>";

                    if(round($tmp_speed,2)<300.0 && round($tmp_speed1,2)<300.0 && $distance_incriment>0.1 && $tmp_time_diff>0.0 && $tmp_time_diff1>0)
                    {
                        //echo "haltEndDateTime=".$haltEndDateTime."<br>";
                        $tmp_time_diff_maxspeed = (double)(strtotime($datetime) - strtotime($datetime_prev)) / 3600;
                        if(($max_speed<$speed) && ($speed<200) && ($haltFlag==False) && ((abs($speed_prev-$speed)<50.0) || ($tmp_time_diff_maxspeed>30)))
                        {
                            $max_speed = $speed;
                            if($max_speed>70)
                            {
                               $alertMaxSpeed=True;
                            }
                            //echo "maxpeed=".$max_speed."<br>";
                        }
                        if ($halt_flag == 1)
                        {
                            $starttime = strtotime($datetime_ref);
                            //$stoptime = strtotime($datetime_cr);  
                            $stoptime = strtotime($datetime_cr);                        			
                            $halt_dur =  ($stoptime - $starttime);
                            //echo "haltDuration=".$halt_dur."stoptime=".$datetime_cr."starttime=".$datetime_ref."<br>";
                            if($halt_dur>12*60*60)
                            {
                                $twelveHrHaltFlag=1;
                            }
                        }
                        $daily_dist += $distance_incriment;
                        $lat_S = $lat_E;
                        $lng_S = $lng_E;
                        $last_time = $datetime_E;
                        $datetime_S = $datetime_E;
                        $datetime_ref= $datetime_cr;
                        $halt_flag = 0;
                    }
                    else if(((strtotime($datetime_cr)-strtotime($datetime_ref))>60) && ($halt_flag != 1))
                    {            			
                        //echo "<br>normal flag set "." datetime_cr ".$datetime_cr."<br>";
                        $halt_flag = 1;
                    }                   
                }
            }
        } 
        if($halt_flag==1 && $twelveHrHaltFlag!=1)
        {
           //echo "<br>In Halt1";
            //echo "<br>datetime_ref=".$datetime_ref;
            $arrivale_time=$datetime_ref;
            $tmp_arr=$tmp_ref;
            $starttime = strtotime($datetime_ref);
            //$stoptime = strtotime($datetime_cr);  
            $stoptime = strtotime($datetime_cr);
            $depature_time=$datetime_cr;
            $tmp_dep=$tmp_cr;
            //echo "<br>".$starttime." ,".$stoptime;
            $halt_dur =  ($stoptime - $starttime); 
            if($halt_dur>12*60*60)
            {
                $twelveHrHaltFlag=1;
            }
        } 
        
        //echo "haltDuration=".$halt_dur."stoptime=".$datetime_cr."starttime=".$datetime_ref."<br>";
        if($halt_dur>12*60*60)
        {
            $twelveHrHaltFlag=1;
        }
       
        if($alertMaxSpeed==True || $twelveHrHaltFlag==1 || $daily_dist<300)
        {           
            $alertSpeedStatus = ($alertMaxSpeed==True ? "yes" : "no"); // returns true
            $dailyDistStatus = ($daily_dist<300 ? "yes" : "no"); // returns true
            $alertHaltDuration =($twelveHrHaltFlag==1 ? "yes" : "no"); // returns true
            $vehicleDetailReport[]=array(
                                            "vName"=>$vehicleDetailArr['vehicleName'],
                                            "imeiNo"=>$vehicleDetailArr['imeiNo'],
                                            "alertSpeedStatus"=>$alertSpeedStatus,                                            
                                            "alertDistanceStatus"=>$dailyDistStatus,
                                            "alertHaltStatus"=>$alertHaltDuration,
                                        );
        }
    }
    /*if($tmpCn==5)
    {
        break;
    }
    $tmpCn++;*/
}
}
    $o_cassandra->close();
    $htmlFormat='';
    $i=0;
    ?>
    <style>
    table.menu
{font-size: 11pt;
margin: 0px;
padding: 0px;
font-weight: normal;}
</style>
<?php
$sno=1;
    foreach($vehicleDetailReport as $key=>$aValue)
    {
        if($i==0) 
        {
            $htmlFormat=$htmlFormat.'<table border=1 rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3 class="menu">
                                    <tr>
                                        <td align="center" colspan=6>Report Date : '.$currentDate.'</td>
                                    </tr>
                                    <tr>
                                        <td>Serial No</td>
                                        <td>Vehicle Name</td>
                                        <td>Imei No</td>
                                        <td>Speed Status(70km/hr)</td>
                                        <td>Distance Status(less than 300km)</td>
                                        <td>Halt Status(more than 12 hr)</td>
                                    </tr>';
            $i=1;
        }
        
        $speedStatus = ($aValue['alertSpeedStatus']=="yes" ? 'style="color:red;"' : 'style="color:green;"'); // returns true
        $distanceStatus = ($aValue['alertDistanceStatus']=="yes" ? 'style="color:red;"' : 'style="color:green;"'); // returns true
       //echo "distanceStatue=".$distanceStatus."<br>";
        $haltStatus = ($aValue['alertHaltStatus']=="yes" ? 'style="color:red;"' : 'style="color:green;"'); // returns true
        $htmlFormat=$htmlFormat.'<tr>
                                 <td>'.$sno.'</td>
                                <td>'.$aValue['vName'].'</td>
                                <td>'.$aValue['imeiNo'].'</td>
                                <td '.$speedStatus.'>'.$aValue['alertSpeedStatus'].'</td>                          
                                <td '.$distanceStatus.'>'.$aValue['alertDistanceStatus'].'</td>
                                <td '.$haltStatus.'>'.$aValue['alertHaltStatus'].'</td>
                            </tr>';
        $sno++;
    }
    $htmlFormat=$htmlFormat.'</table>';
    //echo $htmlFormat;
      $to="support3@iembsys.com";
    $subject="Sriyam Alert Report"; 
    
    $result = $mgClient->sendMessage($domain, array(
      'from' => 'Itrack <support@iembsys.co.in>',
      'to' => $to,
      //'cc'      => 'taseen@iembsys.com',
      'cc' => 'ashish@iembsys.co.in,kapil.maurya@iembsys.com,sriyam2@hotmail.com,sriyam1@hotmail.com,sriyam.khanna@gmail.com,sriyam@outlook.in,jyoti.jaiswal@iembsys.com',
      //'cc'      => 'hourlyreport4@gmail.com',
      // 'bcc'     => 'astaseen83@gmail.com',
      'subject' => $subject,
      'text' => $message,
      'html' => $htmlFormat
          )); 
    print_r($result);
    				
?>
						
