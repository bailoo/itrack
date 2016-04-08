<?php
set_time_limit(80000);
include_once('util_php_mysql_connectivity.php');
include_once('common_xml_element.php');
include_once("get_all_dates_between.php");
include_once('get_location_hourly_person.php');
//echo "New";
include_once('xmlParameters.php');
include_once("report_title.php");
include_once('parameterizeData.php');
include_once('data.php');
include_once("sortXmlData.php");
include_once("getXmlData.php");
include_once("calculate_distance.php");



$hDisArr=getHourlyDistance(1613,1,$DbConnection);

$t=time();
$date1=$pdate." 00:00:00";
$date2=$pdate." 23:59:59";
$userInterval=60;
date_default_timezone_set("Europe/Berlin");

$last_time=null;

$date1 = $_POST['start_date'];
$date2 = $_POST['end_date'];
$date1 = str_replace("/","-",$date1);
$date2 = str_replace("/","-",$date2);
$date_1 = explode(" ",$date1);
$date_2 = explode(" ",$date2);
$datefrom = $date_1[0];
$dateto = $date_2[0];	

$sortBy='h';
$firstDataFlag=0;
$endDateTS=strtotime($date2);
$requiredData="All";
$parameterizeData=new parameterizeData();

$parameterizeData->latitude="d";
$parameterizeData->longitude="e";
	
$finalVNameArr=array();
$i=0;
foreach($hDisArr as $hDValue)
{
	$dataCnt=0;
	$LastSortedDate = getLastSortedDate($vserial[$i],$datefrom,$dateto);
	$SortedDataObject=new data();
	$UnSortedDataObject=new data();
		
	if(($LastSortedDate+24*60*60)>=$endDateTS) //All sorted data
	{	
		//echo "in if1";
		$type="sorted";
		readFileXml($hDValue['device_imei_no'],$date1,$date2,$datefrom,$dateto,$userInterval,$requiredData,$sortBy,$type,$parameterizeData,$firstDataFlag,$SortedDataObject);
	}
	else if($LastSortedDate==null) //All Unsorted data
	{
		//echo "in if2";
		$type="unSorted";
		readFileXml($hDValue['device_imei_no'],$date1,$date2,$datefrom,$dateto,$userInterval,$requiredData,$sortBy,$type,$parameterizeData,$firstDataFlag,$UnSortedDataObject);
	}
	else //Partially Sorted data
	{
		$LastSDate=date("Y-m-d",$LastSortedDate+24*60*60);
		//echo "in else";
		$type="sorted";					
		readFileXml($hDValue['device_imei_no'],$date1,$date2,$datefrom,$LastSDate,$userInterval,$requiredData,$sortBy,$type,$parameterizeData,$firstDataFlag,$SortedDataObject);
	
		$type="unSorted";
		readFileXml($hDValue['device_imei_no'],$date1,$date2,$LastSDate,$dateto,$userInterval,$requiredData,$sortBy,$type,$parameterizeData,$firstDataFlag,$UnSortedDataObject);
	}
	
	//var_dump($UnSortedDataObject);		
	//var_dump($SortedDataObject);	
	//echo "<br><br>";
	
	if(count($SortedDataObject->deviceDatetime)>0)
	{
		$prevSortedSize=sizeof($SortedDataObject->deviceDatetime);
		for($obi=0;$obi<$prevSortedSize;$obi++)
		{			
			$finalDateTimeArr[$i][$dataCnt]=$SortedDataObject->deviceDatetime[$obi];
			$finalLatitudeArr[$i][$dataCnt]=$SortedDataObject->latitudeData[$obi];
			$finalLongitudeArr[$i][$dataCnt]=$SortedDataObject->longitudeData[$obi];		
			$dataCnt++;
		}
	}
	if(count($UnSortedDataObject->deviceDatetime)>0)
	{
		$sortObjTmp=sortData($UnSortedDataObject,$sortBy,$parameterizeData);
		//var_dump($sortObjTmp);
		$sortedSize=sizeof($sortObjTmp->deviceDatetime);
		for($obi=0;$obi<$sortedSize;$obi++)
		{				
			$finalDateTimeArr[$i][$dataCnt]=$sortObjTmp->deviceDatetime[$obi];	
			$finalLatitudeArr[$i][$dataCnt]=$sortObjTmp->latitudeData[$obi];
			$finalLongitudeArr[$i][$dataCnt]=$sortObjTmp->longitudeData[$obi];			
			$dataCnt++;
		}
	}
	$i++;
	$SortedDataObject=null;			
	$sortObjTmp=null;
	$UnsortedDataObject =null;	
}
$parameterizeData=null;
$o_cassandra->close();


//echo "pdate=".$pdate."<br>";
//$date="2015-06-18";
$pdate = date('Y-m-d', strtotime($date .' -1 day'));
//echo "pdate=".$pdate."<br>";
//$pdate='2015-05-02';
if(!file_exists("../../../logBetaXml/".$pdate))
{
	mkdir("../../../logBetaXml/".$pdate);
}

$xmltowrite = "../../../logBetaXml/".$pdate."/distanceFileGet.xml";
if(file_exists($xmltowrite))
{
	unlink($xmltowrite);
}
//echo "xmltowrite12=".$xmltowrite."<br>";
// echo "<br>xml1=".$xmltowrite;

$fh = fopen($xmltowrite, 'w') or die("can't open file 3"); // new
fwrite($fh, "<t1>"); 
$i=0; 
foreach($hDisArr as $hDValue)
{
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
	$total_dist = 0.0;
	$innerSize=sizeof($finalDateTimeArr[$i]);
	for($j=0;$j<$innerSize;$j++)
	{
		
		$lat =$finalLatitudeArr[$i][$j];
		$lng = $finalLongitudeArr[$i][$j];
		$datetimeN = new DateTime($finalDateTimeArr[$i][$j]);		                                        
		if($firstdata_flag==0)
		{					
			$firstdata_flag = 1;
			$lat1 = $lat;
			$lng1 = $lng;
			//echo "<br>DateSec1 before=".$date_secs1." time_int=".$user_interval;
			$interval = (double)$user_interval*60;
			$time1 =$datetimeN;					
			$date_secs1 = $time1->format('U');					
			//echo "<br>DateSec1 before=".$date_secs1." time_int=".$interval;
			$date_secs1 = (double)($date_secs1 + $interval); 
			$date_secs2 = 0;  
			$last_time1 = $datetimeN;
			$latlast = $lat;
			$lnglast =  $lng;  
			//echo "datetime=".$datetimeN."<br>";
		}				
		else
		{
			$time2 = $datetimeN;										
			$date_secs2 = $time2->format('U');	
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
			$tmp_time_diff1 = (double)($datetimeN->format('U') - $last_time1->format('U')) / 3600;
			calculate_distance($latlast, $lat2, $lnglast, $lng2, $distance1);
			if($tmp_time_diff1>0)
			{
				$tmp_speed = ((double) ($distance1)) / $tmp_time_diff1;
				$last_time1 = $datetimeN;
				$latlast = $lat2;
				$lnglast =  $lng2;
			}				
			if($last_time_exception=="")
			{
				$tmp_time_diff = ((double)($datetimeN->format('U') -0)) / 3600;
			} 
			else
			{
				$tmp_time_diff = ((double)($datetimeN->format('U') - $last_time->format('U'))) / 3600;
			}                             
			if($tmp_speed<500.0 && $distance>0.1 && $tmp_time_diff>0.0)
			//if($distance>0.1)
			{
				$total_dist = (double)( $total_dist + $distance );
				$daily_dist= (double) ($daily_dist + $distance);	
				$daily_dist = round($daily_dist,2);
				$last_time_exception=$datetime;
				$lat1 = $lat2;
				$lng1 = $lng2;
				$last_time = $datetimeN;
				$vname_tmp  = $vname;
				$vserial_tmp = $vserial;
				$time1_tmp = $time1;
				$time2_tmp = $time2;
				$total_dist_tmp = $total_dist;                		    						
			}  
			if( ($date_secs2 >= $date_secs1))// || ($f == $total_lines-5))
			{
				get_location($lat2,$lng2,$placename);				
				$dateThis=explode(" ",$xml_date);
				$dateThis1=explode(":",$dateThis[1]);
				$distanceVar="#d".$dateThis1[0];									
				$distance_data = "\n<marker imei=\"".trim($vehicle_serial)."\" vn=\"".trim($vname)."\" dis=\"".$total_dist.$distanceVar."\" add=\"".$placename."\" dtm=\"".$xml_date."\"/>";
				$linetowrite = $distance_data; // for distance       // ADD DISTANCE
				fwrite($fh, $linetowrite); 
				$time1 = $datetimeN;
				$date_secs1 = $time1->format('U');
				$date_secs1 = (double)($date_secs1 + $interval);	
				$total_dist = 0.0;	 															
			}	                                                                        									                               
		}   // else closed 
	}
	$i++;
}
fwrite($fh, "\n</t1>");  
fclose($fh);
?>
