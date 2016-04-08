<?php
//error_reporting(-1);
//ini_set('display_errors', 'On');
//include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
include_once('common_xml_element.php');
include_once('get_location_hourly_person.php');
include_once('calculate_distance.php');
include_once('xmlParameters.php');
include_once('parameterizeData.php');
include_once('lastRecordData.php');
include_once("getXmlData.php");	

set_time_limit(3000); 

$parameterizeData=new parameterizeData();
$parameterizeData->messageType='a';
$parameterizeData->version='b';
$parameterizeData->fix='c';
$parameterizeData->latitude='d';
$parameterizeData->longitude='e';
$parameterizeData->speed='f';	
$parameterizeData->io1='i';
$parameterizeData->io2='j';
$parameterizeData->io3='k';
$parameterizeData->io4='l';
$parameterizeData->io5='m';
$parameterizeData->io6='n';
$parameterizeData->io7='o';
$parameterizeData->io8='p';	
$parameterizeData->sigStr='q';
$parameterizeData->supVoltage='r';
$parameterizeData->dayMaxSpeed='s';
$parameterizeData->dayMaxSpeedTime='t';
$parameterizeData->lastHaltTime='u';
$parameterizeData->cellName='ab';	
$sortBy="h";
//$accountId="1613";
$queryVD = "SELECT DISTINCT vehicle.vehicle_name,vehicle_assignment.device_imei_no FROM vehicle,vehicle_grouping,vehicle_assignment".
	 " WHERE vehicle.vehicle_id=vehicle_assignment.vehicle_id AND vehicle_grouping.vehicle_id=vehicle.vehicle_id".
	 " AND vehicle.status=1 AND vehicle_assignment.status=1 AND vehicle_grouping.status=1 AND vehicle_grouping.".
	 "account_id=767";

//echo "query=".$queryVD."<br>";
$resultVD=mysql_query($queryVD,$DbConnection);
$frcnt=0;
//global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
//global $old_xml_date;
global $vd,$ve,$vh;
$vehicleDetailArr=array();
$current_date_this = date('Y-m-d');
while($rowVD=mysql_fetch_object($resultVD))
{
    $LastRecordObject=new lastRecordData();	
    //echo "imei=".$imei."<br>";
    $LastRecordObject=getLastRecord($rowVD->device_imei_no,$sortBy,$parameterizeData);  
    //var_dump($LastRecordObject);    
    
    if(!empty($LastRecordObject))
    { 
        $dateTime=$LastRecordObject->deviceDatetimeLR[0];
        if($dateTime>$current_date_this." 00:00:00")
        {
            $lat =  $LastRecordObject->latitudeLR[0];
            $lng = $LastRecordObject->longitudeLR[0];			
            $vehicleDetailArr[trim($rowVD->device_imei_no)]=$dateTime."#".$lat."#".$lng."#".$rowVD->vehicle_name;			
            //echo "vehicleName=".$rowVD->vehicle_name." DeviceSerial=".$rowVD->device_imei_no."<br>";
        }	
    }	
}

$current_date = date('Y-m-d');
//$destfile = "C:\\xampp/htdocs/logBetaXml";
$destfile="../../../logBetaXml/".$current_date;
if(!file_exists($destfile))
{
	mkdir($destfile);
	@chmod( $destfile, 0777);
}
$pathtowrite=$destfile."/processedData.xml";

if(!file_exists($pathtowrite))
{
	$fh = fopen($pathtowrite, 'w') or die("can't open file 1"); // new
	//fwrite($fh, "<t1>");
	
	$tmpCnt=0;
	//$tmpTest=0;
	foreach($vehicleDetailArr as $key=>$value)
	{
		$tmpD=explode("#",$value);
		//echo "lat=".$tmpD[1]."lng=".$tmpD[2]."<br>";
		//echo "placename=".$placename."<br>";
		get_location($tmpD[1],$tmpD[2],$placename);
		$todayTime=explode(":",date("H:i:s"));
		if($tmpCnt==0)
		{
                    $tmpCnt=1;
                    $line2 ='<marker d="'.$tmpD[1].'" e="'.$tmpD[2].'" vs="'.$key.'" vn="'.$tmpD[3].'" a'.$todayTime[0].'="'.$placename.'" cdi="0"/>';   
		}
		else
		{
                    $line2 = "\n".'<marker d="'.$tmpD[1].'" e="'.$tmpD[2].'" vs="'.$key.'" vn="'.$tmpD[3].'" a'.$todayTime[0].'="'.$placename.'" cdi="0"/>';   
		}
		fwrite($fh, $line2); 
		/*$tmpTest++;
		if($tmpTest==5)
		{
			break;
		}*/
	}
	//fwrite($fh, "\n".'</t1>');  
	fclose($fh);
}
else
{
	$pathToRead1=$destfile."/processedDataTmp.xml";
	$fw = fopen($pathToRead1, "w") or $fexist = 0;
	$pathToRead=$destfile."/processedData.xml";
	$fp = fopen($pathToRead, "r") or $fexist = 0; 
	$todayTime=explode(":",date("H:i:s"));
	$tmpCnt=0;
	$c=1;
	$total_lines = count(file($pathToRead));
	//echo "totalLines=".$total_lines."<br>";
	while(!feof($fp)) 
	{
		$line = fgets($fp);
		//echo "line=".$line;
						
		//echo"vd=".$vd;
		if(strlen($line)>15)
		{
			if ( (preg_match('/'.$vd.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) && (preg_match('/'.$ve.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
			{
				$status = preg_match('/vs="[^"]+/', $line, $fImeiTmp);
				$fImeiTmp1 = explode("=",$fImeiTmp[0]);
				$fImei = preg_replace('/"/', '', $fImeiTmp1[1]);
				//echo "vehicleName=".$fVehicleName."<br>";
				//echo "vDetail=".$vehicleDetailArr[$fImei]."<br>";
				//echo "Vserila=".$fImei."<br>";
				if($vehicleDetailArr[trim($fImei)]!="")
				{
					$dVdetail=explode("#",$vehicleDetailArr[$fImei]);
					
					$status = preg_match('/d="[^"]+/', $line, $lat_tmp);
					$lat_tmp1 = explode("=",$lat_tmp[0]);
					$lat = preg_replace('/"/', '', $lat_tmp1[1]);
					
					$status = preg_match('/e="[^"]+/', $line, $lng_tmp);
					$lng_tmp1 = explode("=",$lng_tmp[0]);
					$lng = preg_replace('/"/', '', $lng_tmp1[1]);
					
					$status = preg_match('/cdi="[^"]+/', $line, $cum_dist);
					$cum_dist1 = explode("=",$cum_dist[0]);
					$final_cum_dist = preg_replace('/"/', '', $cum_dist1[1]);
					//echo "final_cum_dist_len=".strlen($final_cum_dist)."<br>";
					calculate_distance($lat, $dVdetail[1], $lng, $dVdetail[2], $distance);
					get_location($dVdetail[1],$dVdetail[2],$placename);
					
					$nline = substr(trim($line),0,-2);
					//$nline = substr($line, 0, -10);
					if($tmpCnt==0)
					{
						$tmpCnt=1;
						$nline=$nline.' a'.$todayTime[0].'="'.$placename.'" cdi="'.round($distance,2).'"/>'; 
					}
					else
					{
						if($total_lines==$c)
						{	
							//echo"in total<br>";
						 $nline="\n".$nline.' a'.$todayTime[0].'="'.$placename.'" cdi="'.round($distance,2).'"/>';
						}
						else
						{
							//echo"in else<br>";
						 $nline="\n".$nline.' a'.$todayTime[0].'="'.$placename.'" cdi="'.round($distance,2).'"/>';
						}
					}
					//$nline="\n".$nline.'a'.$todayTime[0].'="'.$placename.'" cdi="'.round($distance,2).'"/>'; 
					fwrite($fw,$nline);
					unset($vehicleDetailArr[$fImei]);
				}
				else
				{
					//echo "inold<br><textarea>".$line."</textarea>";
					$lineOld=trim($line);
					fwrite($fw,"\n".$lineOld);
				}
			}																			
		}	
		$c++;
	}
	fclose($fw); 
	unlink($pathToRead);
	//echo "<br><br>2";
	//print_r($vehicleDetailArr);

	//$vehicleDetailArr=array_values($vehicleDetailArr);
	//echo "<br><br>3";
	//print_r($vehicleDetailArr);
	if(count($vehicleDetailArr)>0)
	{
		$fw = fopen($pathToRead1, 'a');
		$tmpCnt=0;
		foreach($vehicleDetailArr as $key=>$value)
		{
			//calculate_distance($lat, $dVdetail[1], $lng, $dVdetail[2], &$distance);
			$tmpD=explode("#",$value);
			get_location($tmpD[1],$tmpD[2],$placename);
			$addressStr="";
			$todayTime=explode(":",date("H:i:s"));
			$timeCnt=(integer)$todayTime[0];
			for($i=1;$i<=$timeCnt;$i++)
			{
				if($i==$timeCnt)
				{
					if($i<10)
					{
						$addressStr.='a0'.$i.'="'.$placename.'" ';
					}
					else
					{
						$addressStr.='a'.$i.'="'.$placename.'" ';
					}
				}
				/*else
				{
					if($i<10)
					{
						$addressStr.='a0'.$i.'="" ';
					}
					else
					{
						$addressStr.='a'.$i.'="" ';
					}
				}*/
			}
			$aline = "\n".'<marker d="'.$tmpD[1].'" e="'.$tmpD[2].'" vs="'.trim($key).'" vn="'.$tmpD[3].'" '.$addressStr.'cdi="0"/>';
			$lineNew=trim($aline);
			//echo "New<br><textarea>".$aline."</textarea>";
			fwrite($fw,"\n".$lineNew);
		}
		fclose($fw);
	}
	rename($pathToRead1,$destfile."/processedData.xml");
}
?>