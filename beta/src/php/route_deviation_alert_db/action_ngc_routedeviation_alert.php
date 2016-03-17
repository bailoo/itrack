<?php
//==getting trip open detail
function getVIDINVnameAr($local_account_id,$DbConnection)
 {
  $query ="SELECT DISTINCT vehicle.vehicle_id, vehicle.vehicle_name, vehicle_assignment.device_imei_no FROM vehicle,vehicle_assignment,".
            "vehicle_grouping USE INDEX(vg_accountid_status) WHERE vehicle.vehicle_id = vehicle_assignment.vehicle_id AND vehicle.status='1' AND ".
            " vehicle_assignment.status = '1' AND vehicle.vehicle_id=vehicle_grouping.vehicle_id AND vehicle_grouping.account_id".
            "=$local_account_id AND vehicle_grouping.status=1";       
      $result = @mysql_query($query, $DbConnection);		 
      while($row = mysql_fetch_object($result))
      {
            /*$vid[]= $row->vehicle_id;
            $device[] = $row->device_imei_no;
            $vname[] = $row->vehicle_name;*/
            $data[]=array('vid'=>$row->vehicle_id,'device'=>$row->device_imei_no,'vname'=>$row->vehicle_name);
      } 
      return $data;
 }
function PolylineAssignVehilce($vehicle_id,$DbConnection)
{
	$query="SELECT polyline_id FROM polyline_assignment WHERE vehicle_id='$vehicle_id' AND status='1'";
        //echo "query=".$query;
        $result=mysql_query($query,$DbConnection);
        $num_rows=mysql_num_rows($result);    
         if($num_rows>0)
         {
             $row=mysql_fetch_object($result);
             $polyline_id=$row->polyline_id;
         }
	return $polyline_id;
}
 
function PolylineNGCAlert($vid,$DbConnection)
{
    $firstLogDateTime="";
    $query="SELECT firstLogDateTime from polyline_ngc_alert where vehicle_id=$vid";
    $result=mysql_query($query,$DbConnection);
    $num_rows=mysql_num_rows($result);    
     if($num_rows>0)
     {
         $row=mysql_fetch_object($result);
         $firstLogDateTime=$row->firstLogDateTime;
     }
    return $firstLogDateTime;
}
function PolylineNGCAlertAdd($vid,$firstLogDateTime,$DbConnection)
{
    date_default_timezone_set('Asia/Calcutta'); // add conversion based on the time zone of user
    $createdate=date("Y-m-d H:i:s");
    $query="INSERT INTO polyline_ngc_alert (vehicle_id,firstLogDateTime,datetime)VALUES($vid,'$firstLogDateTime','$createdate')";
    $result=mysql_query($query,$DbConnection);
    return $result;
}
function PolylineVoilationHistoryAddNew($imei,$vname,$polyline_name,$first_voilation_time,$first_voilation_details,$first_voilation_location,$DbConnection)
{
   date_default_timezone_set('Asia/Calcutta'); // add conversion based on the time zone of user
   $createdate=date("Y-m-d H:i:s"); 
   //update query to set status=0
   $query_update="UPDATE polyline_voilation_history set status=0 where status=1 and  vehicleno='$vname' and  accountid='1991' ";
   $result_update=mysql_query($query_update,$DbConnection);
   
   $query="INSERT INTO polyline_voilation_history (imeino,vehicleno,route,first_voilation_time,first_voilation_details,first_voilation_location,create_date,status,accountid)VALUES('$imei','$vname','$polyline_name','$first_voilation_time','$first_voilation_details','$first_voilation_location','$createdate',1,'1991')";
   $result=mysql_query($query,$DbConnection);
   return $result;
}

function PolylineVoilationHistoryUpdate($imei,$vname,$polyline_name,$last_voilation_time,$last_voilation_details,$last_voilation_location,$DbConnection)
{
   date_default_timezone_set('Asia/Calcutta'); // add conversion based on the time zone of user
   $createdate=date("Y-m-d H:i:s");  
   
   $query_update="UPDATE polyline_voilation_history set last_voilation_time='$last_voilation_time',last_voilation_details='$last_voilation_details',last_voilation_location='$last_voilation_location',edit_date='$createdate' where status=1 and imeino='$imei' and vehicleno='$vname' and route='$polyline_name' and accountid='1991'";
   echo $query_update;
   $result_update=mysql_query($query_update,$DbConnection);
}
function PolylineNGCAlertDelete($vid,$DbConnection)
{
 
    $query="DELETE FROM polyline_ngc_alert where vehicle_id NOT IN($vid)";
    echo $query;
    $result=mysql_query($query,$DbConnection);
    return $result;
}


function get_last_location_xml($imei, &$liveXmlData)
{
	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
	global $old_xml_date;
	$data = 0;
	$gps =1;
	//date_default_timezone_set('Asia/Calcutta');
	$current_time = date('Y-m-d H:i:s');
	$current_date_this = date('Y-m-d');
	$xml_file = "../../../../xml_vts/xml_last/".$imei.".xml";
	//$xml_file = "F:\\XML/".$imei.".xml";
	$file = file_get_contents($xml_file);
	
	if(!strpos($file, "</t1>")) 
	{
		usleep(1000);
	}		
  
	$t=time();
	$rno = rand();			
	$xml_original_tmp = "../../../../xml_tmp/original_xml/tmp_".$imei."_".$t."_".$rno.".xml";
	//$xml_original_tmp = "F:\\XML/tmp/tmp_".$imei."_".$t."_".$rno.".xml";	
	copy($xml_file,$xml_original_tmp);	    
	if (file_exists($xml_original_tmp))
	{
		//echo "IMEI=".$imei."/";
		//echo "<br>exist2";
		$fexist =1;
		$fp = fopen($xml_original_tmp, "r") or $fexist = 0;   
		$total_lines =0;
		$total_lines = count(file($xml_original_tmp));
		//echo "<br>total_lines=".$total_lines;
		$c =0;
		set_master_variable($current_date_this);
		
		while(!feof($fp)) 
		{
			$data = 1;			
			$line = fgets($fp);
			//echo "line=".$line;
			$c++;				
			if(strlen($line)>15)
			{
				$status = preg_match('/'.$vh.'="[^"]+/', $line, $datetime_tmp);
				$datetime_tmp1 = explode("=",$datetime_tmp[0]);
				$datetime = preg_replace('/"/', '', $datetime_tmp1[1]);
				$xml_date = $datetime;				
				if($xml_date!="")
				{            
					$status = preg_match('/'.$vf.'="[^"]+/', $line, $speed_tmp);
					$speed_tmp1 = explode("=",$speed_tmp[0]);
					$speed = preg_replace('/"/', '', $speed_tmp1[1]);
            
					$status = preg_match('/'.$vd.'="[^"]+/', $line, $lat_tmp);
					$lat_tmp1 = explode("=",$lat_tmp[0]);
					$lat = preg_replace('/"/', '', $lat_tmp1[1]);
            
					$status = preg_match('/'.$ve.'="[^"]+/', $line, $lng_tmp);
					$lng_tmp1 = explode("=",$lng_tmp[0]);
					$lng = preg_replace('/"/', '', $lng_tmp1[1]);
								
					if(($lat =="") || ($lng ==""))
					{
						$gps = 0;
					}
										
					$status = preg_match('/'.$vs.'="[^"]+/', $line, $day_max_speed_tmp);
					$day_max_speed_tmp1 = explode("=",$day_max_speed_tmp[0]);
					$day_max_speed = preg_replace('/"/', '', $day_max_speed_tmp1[1]);

					$status = preg_match('/'.$vt.'="[^"]+/', $line, $day_max_speed_time_tmp);
					$day_max_speed_time_tmp1 = explode("=",$day_max_speed_time_tmp[0]);
					$day_max_speed_time = preg_replace('/"/', '', $day_max_speed_time_tmp1[1]);

					$status = preg_match('/'.$vu.'="[^"]+/', $line, $last_halt_time_tmp);
					$last_halt_time_tmp1 = explode("=",$last_halt_time_tmp[0]);
					$last_halt_time = preg_replace('/"/', '', $last_halt_time_tmp1[1]);                                                                 																										
					$xml_date_sec = strtotime($xml_date);
					$last_halt_time_sec = strtotime($last_halt_time);			
					$current_time_sec = strtotime($current_time);
            
				
					$diff = ($current_time_sec - $xml_date_sec);					
					if($speed>=5 && $diff <=600)
					{
						$status = "Running";						
					}               
					
					else if( ($speed<5 && $diff <=600) || (($diff >600) && ($diff <=1800)) )
					{
						$status = "Stopped";
					} 
					else
					{
						$status = "NOD";
					}					
					$line = substr($line, 0, -3);
					//echo $line;
					$line2 = "\n".$line.' v="'.$imei.'" w="'.$vname.'" y="'.$vtype.'" aa="'.$status.'" gps="'.$gps.'"/>';                          									
					                    									
				}																			
			}			
		}
				
		$len = strlen($line2);
		if($len>0)
		{			
			$liveXmlData=$liveXmlData.$line2."@";
			unlink($xml_original_tmp);
		}
		else
		{
			// fclose($fp);
			//code to fixed on 04082015
			$gps = "0";
			$status ="NA";
			$vtype ="-";
			$line ='<x a="0" b="0" c="1" d="" e="" f="000.016" g="" h="" i="" j="0" k="0" l="0" m="0" n="0" o="0" p="0" q="0" r="0"/>';
			$line = substr($line, 0, -3);
			$line2 = "\n".$line.' v="'.$imei.'" w="'.$vname.'" y="'.$vtype.'" aa="'.$status.'" gps="'.$gps.'"/>';
			$liveXmlData=$liveXmlData.$line2."@";			
			//----end of fixed line
			unlink($xml_original_tmp);
		}							
	}
	else
	{
		$gps = "0";
		$status ="NA";
		$vtype ="-";
		$line ='<x a="0" b="0" c="1" d="" e="" f="000.016" g="" h="" i="" j="0" k="0" l="0" m="0" n="0" o="0" p="0" q="0" r="0"/>';
		$line = substr($line, 0, -3);
		$line2 = "\n".$line.' v="'.$imei.'" w="'.$vname.'" y="'.$vtype.'" aa="'.$status.'" gps="'.$gps.'"/>'; 
		$liveXmlData=$liveXmlData.$line2."@";
	}
}

function get_location_casandra($imei,$parameterizeData,$LastRecordObject)
{
	
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
	date_default_timezone_set('Asia/Calcutta');	
	//print_r($parameterizeData);
        //echo $imei;
	$LastRecordObject=getLastRecord($imei,$sortBy,$parameterizeData);
      
	$data=array();
	if(!empty($LastRecordObject))
	{
		$current_time_sec =strtotime($LastRecordObject->deviceDatetimeLR[0]);
		$data[]=array(
                            'messageTypeLR'=>$LastRecordObject->messageTypeLR[0],
                            'versionLR'=>$LastRecordObject->versionLR[0],
                            'fixLR'=>$LastRecordObject->fixLR[0],
                            'latitudeLR'=>$LastRecordObject->latitudeLR[0],
                            'longitudeLR'=>$LastRecordObject->longitudeLR[0],
                            'speedLR'=>$LastRecordObject->speedLR[0],
                            'serverDatetimeLR'=>$LastRecordObject->serverDatetimeLR[0],
                            'deviceDatetimeLR'=>$LastRecordObject->deviceDatetimeLR[0],
                            'io1LR'=>$LastRecordObject->io1LR[0],
                            'io2LR'=>$LastRecordObject->io1LR[0],
                            'io3LR'=>$LastRecordObject->io1LR[0],
                            'io4LR'=>$LastRecordObject->io1LR[0],
                            'io5LR'=>$LastRecordObject->io1LR[0],
                            'io6LR'=>$LastRecordObject->io1LR[0],
                            'io7LR'=>$LastRecordObject->io1LR[0], 
                            'io8LR'=>$LastRecordObject->io1LR[0], 
                            'sigStrLR'=>$LastRecordObject->sigStrLR[0], 
                            'suplyVoltageLR'=>$LastRecordObject->suplyVoltageLR[0], 
                            'dayMaxSpeedLR'=>$LastRecordObject->dayMaxSpeedLR[0], 
                            'dayMaxSpeedTimeLR'=>$LastRecordObject->dayMaxSpeedTimeLR[0],
                            'lastHaltTimeLR'=>$LastRecordObject->lastHaltTimeLR[0],
                            'deviceImeiNo'=>$imei,
                            'vehicleName'=>$vehicle_detail_local[0],
                            'vehilceType'=>$vehicle_detail_local[2],
                            'vehilceNumber'=>$vehicle_detail_local[2],                               
                            'status'=>$status,
                            'dispatch_time'=>$dispatch_time,
                            'target_time'=>$target_time,
                            'plant_number'=>$plant_number
                        );
	}
	return $data;
}
?>
					 					
