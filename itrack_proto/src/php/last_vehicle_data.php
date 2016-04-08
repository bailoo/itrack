<?php
  $imei = $device_imei_no_local;
  $record_type = "datetime";
  
  $xml_file = "../xml_vts/xml_data/".$current_date."/".$imei.".xml";
	//echo "<br>xmlfile=".$xml_file;
  //$fp = fopen($xml_file, 'r');
  //echo "<br>CWD=".getcwd() . "<br>";
  
	if (file_exists($xml_file)) 
	{
		//echo "<br>exists";
    //$t=time();
		//$xml_original_tmp = "../../../xml_tmp/original_xml/tmp_".$vehicle_serial."_".$t."_".$i.".xml";
		//$xml_original_tmp = "./test/tmp_".$vehicle_id_local."_".$t.".xml";
		
    $t=time();
    $xml_original_tmp = "../xml_tmp/original_xml/tmp_".$imei."_".$t."_".$d.".xml";
		
		copy($xml_file,$xml_original_tmp); 

		if (file_exists($xml_original_tmp))
		{
			//echo "<br>orginal_tmp=".$xml_original_tmp."<br>";
			
			$fexist =1;
			$fp = fopen($xml_original_tmp, "r") or $fexist = 0;   
			$total_lines =0;
			$total_lines = count(file($xml_original_tmp));

			//echo "<br>total_lines=".$total_lines;
			$c =0;
			// read some data
			while(!feof($fp)) 
			{
				$line = fgets($fp);
				$c++;				
				
				if($c==($total_lines-1))
				{
					if ((preg_match('/lat="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match) ) && (preg_match('/lng="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
					{
						if($record_type == "datetime")
						{
							$status = preg_match('/datetime="[^"]+/', $line, $datetime_tmp);
							$datetime_tmp1 = explode("=",$datetime_tmp[0]);
							$datetime = preg_replace('/"/', '', $datetime_tmp1[1]);
							$last_datetime = $datetime;
							//echo "<br>dt=<textarea>".$last_datetime."</textarea>";
						}
						/*echo "<br>last_datetime=".$last_datetime;
						echo "<br>last_lat=".$last_lat;
						echo "<br>last_lng=".$last_lng;
						echo "<br>last_speed=".$last_speed;*/
					}								
				}			
			}
			//$offset = -500;
					
			//echo "<br>line2=".$line;		
		}
		
		fclose($fp);
		unlink($xml_original_tmp);	
	}
?>
