<?php
include_once('util_android_php_mysql_connectivity.php');  	   //util_session_variable.php sets values in session
include_once('util_android_session_variable.php');   //util_php_mysql_connectivity.php make set connection of user to database  
set_time_limit(800);
//$vserial='862170018370831,862170018371367,';
//$vserial = $_POST['deviceImeiNo'];

$device_str= $_POST["vehicleserialWithIo"];
//$device_str="862170018324168:862170018322923:#1^fuel:7^engine:5^door_open:2^fuel_voltage:6^fuel_lead,1^fuel:7^engine:5^door_open:2^fuel_voltage:6^fuel_lead,";

$device_str = explode('#',$device_str);
//echo "device_str1=".$device_str[0]."<br>";
//echo "device_str2=".$device_str[1]."<br>";
$vesrial_2 = explode(':',substr($device_str[0],0,-1));
$iotype_element = explode(',',substr($device_str[1],0,-1));

/*$vesrial_1=substr($vserial,0,-1);
$vesrial_2=explode(",",$vesrial_1);*/
$final_str="";

for($i=0;$i<sizeof($vesrial_2);$i++)
{
	$sub_str="";
	$io_str="";
	$t=time();
	$rno = rand();
	$xml_file = "../../../../xml_vts/xml_last/".$vesrial_2[$i].".xml";

	if(file_exists($xml_file))
	{
		//echo "xml_file=".$xml_file."<br>";
		$xml_original_tmp = "../../../../android_xml_tmp/tmp_".$vesrial_2[$i]."_".$t."_".$rno.".xml";	
		//echo "xml_original_tmp=".$xml_original_tmp."<br>";		
		copy($xml_file,$xml_original_tmp);		
	}	
	if (file_exists($xml_original_tmp))
	{
		$fexist =1;
		$fp = fopen($xml_original_tmp, "r") or $fexist = 0;   
		$total_lines =0;
		$total_lines = count(file($xml_original_tmp));	
		$c =0;	
		while(!feof($fp)) 
		{
			$line = fgets($fp);
			$c++;		
			if(strlen($line)>15)
			{
				if((preg_match('/d="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) && (preg_match('/e="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
				{
					$status = preg_match('/h="[^"]+/', $line, $datetime_tmp);
					$datetime_tmp1 = explode("=",$datetime_tmp[0]);
					$datetime = preg_replace('/"/', '', $datetime_tmp1[1]);
					$xml_date = $datetime;
					
					$status = preg_match('/f="[^"]+/', $line, $speed_tmp);
					$speed_tmp1 = explode("=",$speed_tmp[0]);
					$speed = preg_replace('/"/', '', $speed_tmp1[1]);
					if($speed=='-')
					{
						$speed="0.0";
					}
					
					$status = preg_match('/d="[^"]+/', $line, $lat_tmp);
					$lat_tmp1 = explode("=",$lat_tmp[0]);
					$lat = preg_replace('/"/', '', $lat_tmp1[1]);
					
					$status = preg_match('/e="[^"]+/', $line, $lng_tmp);
					$lng_tmp1 = explode("=",$lng_tmp[0]);
					$lng = preg_replace('/"/', '', $lng_tmp1[1]);
								
					$status = preg_match('/s="[^"]+/', $line, $day_max_speed_tmp);
					$day_max_speed_tmp1 = explode("=",$day_max_speed_tmp[0]);
					$day_max_speed = preg_replace('/"/', '', $day_max_speed_tmp1[1]);
					if($day_max_speed=='')
					{
						$day_max_speed="0.0";
					}
					$status = preg_match('/t="[^"]+/', $line, $day_max_speed_time_tmp);
					$day_max_speed_time_tmp1 = explode("=",$day_max_speed_time_tmp[0]);
					$day_max_speed_time = preg_replace('/"/', '', $day_max_speed_time_tmp1[1]);
					

					$status = preg_match('/u="[^"]+/', $line, $last_halt_time_tmp);
					$last_halt_time_tmp1 = explode("=",$last_halt_time_tmp[0]);
					$last_halt_time = preg_replace('/"/', '', $last_halt_time_tmp1[1]);  

					preg_match('/i="[^"]+/', $line, $io1_tmp);
					$io1_tmp1 = explode("=",$io1_tmp[0]);
					$io1= preg_replace('/"/', '', $io1_tmp1[1]);
				// echo "io1=".$io1."<br>";

					preg_match('/j="[^"]+/', $line, $io2_tmp);
					$io2_tmp1 = explode("=",$io2_tmp[0]);
					$io2= preg_replace('/"/', '', $io2_tmp1[1]);
					// echo "io2=".$io2."<br>";

					preg_match('/k="[^"]+/', $line, $io3_tmp);
					$io3_tmp1 = explode("=",$io3_tmp[0]);
					$io3= preg_replace('/"/', '', $io3_tmp1[1]);
					//echo "io3=".$io3."<br>";

					preg_match('/l="[^"]+/', $line, $io4_tmp);
					$io4_tmp1 = explode("=",$io4_tmp[0]);
					$io4= preg_replace('/"/', '', $io4_tmp1[1]);
					//echo "io4=".$io4."<br>";

					preg_match('/m="[^"]+/', $line, $io5_tmp);
					$io5_tmp1 = explode("=",$io5_tmp[0]);
					$io5= preg_replace('/"/', '', $io5_tmp1[1]);
					//echo "io5=".$io5."<br>";

					preg_match('/n="[^"]+/', $line, $io6_tmp);
					$io6_tmp1 = explode("=",$io6_tmp[0]);
					$io6= preg_replace('/"/', '', $io6_tmp1[1]);
					//echo "io6=".$io6."<br>";

					preg_match('/o="[^"]+/', $line, $io7_tmp);
					$io7_tmp1 = explode("=",$io7_tmp[0]);
					$io7= preg_replace('/"/', '', $io7_tmp1[1]);
					 //echo "io7=".$io7."<br>";

					preg_match('/p="[^"]+/', $line, $io8_tmp);
					$io8_tmp1 = explode("=",$io8_tmp[0]);
					$io8= preg_replace('/"/', '', $io8_tmp1[1]);
					//echo "io8=".$io8."<br>";
																										 
					$xml_date_sec = strtotime($xml_date);
					$last_halt_time_sec = strtotime($last_halt_time);			
					$current_time_sec = strtotime($current_time);
					//$diff = ($current_time_sec - $xml_date_sec);   // IN SECONDS
					$diff = ($current_time_sec - $last_halt_time_sec); 
					
					if($speed>=5 && $diff <=600)
					{
					  $status = "Running";
					  //echo "<br>Running";
					} 
					else
					{
					  $status = "Stopped";
					}
					
					$io_typ_value=explode(":",$iotype_element[$i]);				
					$io_cnt=count($io_typ_value);
					if($io_cnt>0)
					{						
						for($ui=0;$ui<sizeof($io_typ_value);$ui++)
						{
							$iotype_iovalue_str1=explode("^",$io_typ_value[$ui]);	
							//echo "io_name=".$iotype_iovalue_str1[1]."io_value=".$iotype_iovalue_str1[0]."<br>";
							if($iotype_iovalue_str1[0]=="1")
							{
								$io_values=$io1;
							}
							else if($iotype_iovalue_str1[0]=="2")
							{
								$io_values=$io2;
							}
							else if($iotype_iovalue_str1[0]=="3")
							{
								$io_values=$io3;
							}
							else if($iotype_iovalue_str1[0]=="4")
							{
								$io_values=$io4;
							}
							else if($iotype_iovalue_str1[0]=="5")
							{
								$io_values=$io5;
							}
							else if($iotype_iovalue_str1[0]=="6")
							{
								$io_values=$io6;
							}
							else if($iotype_iovalue_str1[0]=="7")
							{
								$io_values=$io7;
							}
							else if($iotype_iovalue_str1[0]=="8")
							{
								$io_values=$io8;
							}
							//echo "temperature=".$iotype_iovalue_str1[1]."<br>";
							if($iotype_iovalue_str1[1]=="temperature")
							{					
								if($io_values!="")
								{
									if($io_values>=-30 && $io_values<=70)
									{
										//echo "in if";
										$io_str=$io_str.$iotype_iovalue_str1[1].":".$io_values."@";
									}
									else
									{
										//echo "in if 1";									
										$io_str=$io_str."Temperature:-@";
									}
								}
								else
								{
									//echo "in if 2";
									$io_str=$io_str."Temperature:-@";
								}
							}
							else if($iotype_iovalue_str1[1]!="")
							{
								//echo "engine".$iotype_iovalue_str1[1]."<br>";
								if(trim($iotype_iovalue_str1[1])=="engine")
								{
									if($io_values<=350)
									{
										$io_str=$io_str.$iotype_iovalue_str1[1].":Off@";										
									}
									else
									{
										$io_str=$io_str.$iotype_iovalue_str1[1].":ON@";										
									}
								}
								else if($iotype_iovalue_str1[1]=="door_open")
								{
									if($io_values<=350)
									{
										$io_str=$io_str.$iotype_iovalue_str1[1].":Close@";										
									}
									else
									{
										$io_str=$io_str.$iotype_iovalue_str1[1].":Open@";										
									}
								}
								else if($iotype_iovalue_str1[1]=="fuel_lead")
								{
									if($io_values<=350)
									{					
										$io_str=$io_str.$iotype_iovalue_str1[1].":Close@";										
									}
									else
									{
										$io_str=$io_str.$iotype_iovalue_str1[1].":Open@";	
									}
								}
								else
								{
									if($io_values!="")
									{
										$io_str=$io_str.$iotype_iovalue_str1[1].":".$io_values."@";										
									}
									else
									{
										$io_str=$io_str.$iotype_iovalue_str1[1].":-@";										
									}
								}
							}			
						}
					}					
					$io_str=substr($io_str,0,-1);
					$sub_str=$sub_str.$xml_date.",".$speed.",".$lat.",".$lng.",".$day_max_speed.",".$day_max_speed_time.",".$last_halt_time.",".$status.",".$io_str;
				}																			
			}			
		}	
		fclose($fp);
		unlink($xml_original_tmp);    							
	}
	if($sub_str=="")
	{
		$sub_str="No Data Found";
	}
	$final_str=$final_str.$sub_str."#";
}
$final_str=substr($final_str,0,-1);
echo $final_str;
?>