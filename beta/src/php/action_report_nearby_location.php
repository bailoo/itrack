<?php
	include_once("main_vehicle_information_1.php");
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION["root"];
	set_time_limit(300);
	include_once('common_xml_element.php');
	include_once("get_all_dates_between.php");
	include_once("sort_xml.php");
	include_once("calculate_distance.php");
	include_once("report_title.php");
	include_once("report_get_parsed_string.php");
	//include_once("read_filtered_xml.php");
	$DEBUG = 0;
	
	$selectVehicleImei = $_POST['vehicleSerial'];
	$selectedAccountId = $_POST['selectedAccountId'];	
	
	/*echo "selectedAccountId=".$selectedAccountId."<br>";
	echo "selectVehicleImei=".$selectVehicleImei."<br>";*/
	
	$xml_file="../../../xml_vts/xml_last/".$selectVehicleImei.".xml";
	
	/*if(file_exists($xml_original_tmp))
	{
		echo "<br>exist2";
	}*/
	
	$file = file_get_contents($xml_file);
	if(!strpos($file, "</t1>")) 
	{
		usleep(1000);
	}
	
	$xml_original_tmp = "../../../xml_tmp/original_xml/tmp_".$selectVehicleImei."_".$t."_".$rno.".xml";		
	copy($xml_file,$xml_original_tmp);
	$current_date_this = date('Y-m-d');
	if(file_exists($xml_original_tmp))
	{
		global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
		global $old_xml_date;
		//echo "<br>exist2";
		$fexist =1;
		$fp = fopen($xml_original_tmp, "r") or $fexist = 0;   
		
		set_master_variable($current_date_this);
		while(!feof($fp)) 
		{
			$line = fgets($fp);
			//echo "line=".$line;
			$c++;				
			//echo"vd=".$vd;
			if(strlen($line)>15)
			{
				if((preg_match('/'.$vd.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) && (preg_match('/'.$ve.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)))
				{
					if($current_date_this<$old_xml_date)
					{
						$line=str_replace("lat=","d=",$line);
						$line=str_replace("lng=","e=",$line);
						
						$status = preg_match('/'.$vd.'="[^"]+/', $line, $lat_tmp);
						$lat_tmp1 = explode("=",$lat_tmp[0]);
						$latFirst = preg_replace('/"/', '', $lat_tmp1[1]);
				
						$status = preg_match('/'.$ve.'="[^"]+/', $line, $lng_tmp);
						$lng_tmp1 = explode("=",$lng_tmp[0]);
						$lngFirst = preg_replace('/"/', '', $lng_tmp1[1]);						
					}
					else
					{
						$status = preg_match('/'.$vd.'="[^"]+/', $line, $lat_tmp);
						$lat_tmp1 = explode("=",$lat_tmp[0]);
						$latFirst = preg_replace('/"/', '', $lat_tmp1[1]);
				
						$status = preg_match('/'.$ve.'="[^"]+/', $line, $lng_tmp);
						$lng_tmp1 = explode("=",$lng_tmp[0]);
						$lngFirst = preg_replace('/"/', '', $lng_tmp1[1]);						
					}          									
				}																			
			}			
		}									
	} 
	fclose($fp);
	unlink($xml_original_tmp);
	global $distanceFlag;
	$distanceFlag=0;
	//echo "distance=".$distance."<br>";
	echo "<br><center><b>Near By Vehicle</center><br>";
echo'<div style="height:500px;overflow:auto">
<table border=1 width="55%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
		<tr>
			<td class="text">
			<b>Serial
			</td>
			<td class="text">
			<b>Vehicle Name
			</td>
			<td class="text">
			<b>Distance
			</td>
			<td class="text">
			<b>Mobile Number
			</td>
		</tr>';
global $serial;
$serial=0;
PrintAllVehicle($root, $selectedAccountId,$selectVehicleImei,$latFirst,$lngFirst);
global $distanceFlag;
if($distanceFlag==0)
{
	echo "<tr>
			<td colspan='4' align='center' class='text'>No Data Found</td>
		</tr>";
}
echo"</table></div>";
function PrintAllVehicle($root, $local_account_id,$selectVehicleImei,$latFirst,$lngFirst)
{
	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
	global $old_xml_date;
	global $vehicleid;
	global $serial;
	global $vehicle_cnt;
	global $distanceFlag;
	//$distanceFlag=0;
	global $title;	
	$type = 0;
	
	global $current_date;
	$vehicle_name_arr=array();
	$imei_arr=array();
	$vehicleid_or_imei_arr=array();
	$vehicle_color=array();
	if($root->data->AccountID==$local_account_id)
	{
		$td_cnt =0;
		for($j=0;$j<$root->data->VehicleCnt;$j++)
		{			    
			$vehicle_id = $root->data->VehicleID[$j];
			$vehicle_name = $root->data->VehicleName[$j];
			$vehicle_imei = $root->data->DeviceIMEINo[$j];
			$mobile_number = $root->data->VehicleNumber[$j];
			if($vehicle_id!=null)
			{
				for($i=0;$i<$vehicle_cnt;$i++)
				{
					if($vehicleid[$i]==$vehicle_id)
					{
						break;
					}
				}			
				if($i>=$vehicle_cnt)
				{
					$vehicleid[$vehicle_cnt]=$vehicle_id;
					$vehicle_cnt++;
					///echo "firstImei=".trim($selectVehicleImei)."SecondImei=".trim($vehicle_imei)."<br>";
					if(trim($selectVehicleImei)!=trim($vehicle_imei))
					{
						$xml_file = "../../../xml_vts/xml_last/".$vehicle_imei.".xml";
						
						$file = file_get_contents($xml_file);
						if(!strpos($file, "</t1>")) 
						{
							usleep(1000);
						}
						$xml_original_tmp = "../../../xml_tmp/original_xml/tmp_".$vehicle_imei."_".$t."_".$rno.".xml";		
						copy($xml_file,$xml_original_tmp);
						$current_date_this = date('Y-m-d');
						/*if(file_exists($xml_original_tmp))
						{
							echo "<br>exist2";
						}*/
						if(file_exists($xml_original_tmp))
						{
							$fexist =1;
							$fp = fopen($xml_original_tmp, "r") or $fexist = 0;  
							set_master_variable($current_date_this);
							while(!feof($fp)) 
							{
								$line = fgets($fp);
								//echo"<textarea>".$line."</textarea>";
								//echo "line=".$line;
								$c++;				
								//echo"vd=".$vd;
								if(strlen($line)>15)
								{
									if((preg_match('/'.$vd.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) && (preg_match('/'.$ve.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)))
									{
										if($current_date_this<$old_xml_date)
										{
											$line=str_replace("lat=","d=",$line);
											$line=str_replace("lng=","e=",$line);
											
											$status = preg_match('/'.$vd.'="[^"]+/', $line, $lat_tmp);
											$lat_tmp1 = explode("=",$lat_tmp[0]);
											$latNext = preg_replace('/"/', '', $lat_tmp1[1]);
									
											$status = preg_match('/'.$ve.'="[^"]+/', $line, $lng_tmp);
											$lng_tmp1 = explode("=",$lng_tmp[0]);
											$lngNext = preg_replace('/"/', '', $lng_tmp1[1]);
											calculate_distance($latFirst, $latNext, $lngFirst, $lngNext, &$distance);
											//echo "distance=".$distance."<br>";
											if($distance<=10)
											{
												$distanceFlag=1;
												$serial++;
											echo"<tr>
													<td class='text'>".$serial."</td>
													<td class='text'>".$vehicle_name."</td>
													<td class='text'>".round($distance,2)."</td>";
													if($mobile_number=="")
													{
													echo"<td class='text'>-</td>";
													}
													else
													{
														echo"<td class='text'>".$mobile_number."</td>";
													}
											echo"</tr>";
											}
										}
										else
										{
											$status = preg_match('/'.$vd.'="[^"]+/', $line, $lat_tmp);
											$lat_tmp1 = explode("=",$lat_tmp[0]);
											$latNext = preg_replace('/"/', '', $lat_tmp1[1]);
									
											$status = preg_match('/'.$ve.'="[^"]+/', $line, $lng_tmp);
											$lng_tmp1 = explode("=",$lng_tmp[0]);
											$lngNext = preg_replace('/"/', '', $lng_tmp1[1]);
											//echo "latFirst=".$latFirst."lngFirst=".$lngFirst."latNext=".$latNext."lngNext=".$lngNext."<br>";
											calculate_distance($latFirst, $latNext, $lngFirst, $lngNext, &$distance);
											//echo "distance=".$distance."<br>";
											if($distance<=10)
											{
												$distanceFlag=1;
												$serial++;
											echo "<tr>
													<td class='text'>".$serial."</td>
													<td class='text'>".$vehicle_name."</td>
													<td class='text'>".round($distance,2)."</td>";
													if($mobile_number=="")
													{
													echo"<td class='text'>-</td>";
													}
													else
													{
														echo"<td class='text'>".$mobile_number."</td>";
													}
											echo"</tr>";
											}
										}          									
									}																			
								}			
							}
							fclose($fp);
							unlink($xml_original_tmp);
						}
					}
				}
			}
		}
	}
	
	$ChildCount=$root->ChildCnt;
	for($i=0;$i<$ChildCount;$i++)
	{ 
		PrintAllVehicle($root->child[$i],$local_account_id,$select_vehicle,$latFirst,$lngFirst);
	}
}


?>								
