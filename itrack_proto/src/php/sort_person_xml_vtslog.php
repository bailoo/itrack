<?php
	function SortFile($xml_unsorted, $xml_original_tmp,$conditional_date)
	{ 
		global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
		global $old_xml_date;
		global $report;
		//echo "in function";
		$fexist =1;
		//$fix_tmp = 1;
		$xml = fopen($xml_unsorted, "r") or $fexist = 0;
		$count = count(file($xml_unsorted));
		//echo "<BR>COUNT======== $count lines in $xml";
		//$xml2 = '"'.$xml.'"';
		if($fexist)
		{
			$i=0;
			$format = 2;
			set_master_variable($conditional_date);	
			while(!feof($xml))          // WHILE LINE != NULL
			{
				$DataValid = 0;
				$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE            
				//echo "<textarea>".$line."</textarea>";
				if( (strlen($line)>15) )
				{
					$DataValid = 1;
				}
				//echo "DataValid=".$DataValid." lineStLenght=".$line[0]." lineEndLength=".$line[strlen($line)-2]."<br>";
				//echo "<br>line[0]=".$line[0]." ,".$line[strlen($line)-2]." ,".$DataValid." ,".$fix_tmp;          
				// if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($fix_tmp==1) && ($format==1) && ($DataValid == 1) )
				//if(($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($fix_tmp==1) && ($format==1) && ($DataValid == 1))
				if(($line[0] == '<') && ($line[strlen($line)-2] == '>'))
				{ 
					$status = preg_match('/'.$vg.'="[^"]+/', $line, $sts_tmp);
					if($status==0)
					{
						// continue;
					}
					
					$status = preg_match('/'.$vh.'="[^"]+/', $line, $datetime_tmp);
					if($status==0)
					{
						// continue;
					}
					//echo "test5".$datetime_tmp[0].'<BR>';
					$status = preg_match('/'.$vd.'="[^" ]+/', $line, $lat_tmp);
					if($status==0)
					{
						//continue;
					}
					//echo "test6".'<BR>';
					$status = preg_match('/'.$ve.'="[^" ]+/', $line, $lng_tmp);
					if($status==0)
					{
						//continue;
					}
					$status = preg_match('/'.$vf.'="[^" ]+/', $line, $speed_tmp);
					if($status==0)
					{
						//continue;
					}
			  
					$status = preg_match('/'.$vc.'="[^" ]+/', $line, $fix_tmp);
					if($status==0)
					{
						//continue;
					}				
					$status = preg_match('/'.$vab.'="[^"]+/', $line, $cellname_tmp);
					if($status==0)
					{
						// continue;
					}
				} // FORMAT 1 CLOSED
				//if(($format == 1) && ($DataValid == 1))
				if($DataValid == 1)
				{
					$sts[$i] = $sts_tmp[0].'"';                // Store Name with Value
					$vehicleserial[$i] = $vehicleserial_tmp[0].'"';
					//echo "vserial1=".$vehicleserial[$i]."<br>";
					$datetime[$i] = $datetime_tmp[0].'"';
					//echo "datetime=".$datetime[$i]."<br>";					
// Store Name with Value
					$vehicleserial[$i] = $vehicleserial_tmp[0].'"';
					$lat[$i] = $lat_tmp[0].'"';
					$lng[$i] = $lng_tmp[0].'"';
					$cellname[$i] = $cellname_tmp[0].'"';				
					$speed[$i] = $speed_tmp[0].'"';
					$fuel[$i] = $fuel_tmp[0].'"'; 					
					$fix[$i] = $fix_tmp[0].'"'; 
					$i++;                      
				}    // IF FORMAT 1 CLOSED 
			}   // WHILE CLOSED
      
			// FORMAT 1 OPENS      
			//if($format == 1)
			{
				//echo "<br>In Format111";
				for($x = 1; $x < $i; $x++) 
				{
					$date_x=null;
					$date_x = explode("=",$datetime[$x]);
					$value = preg_replace('/"/', '', $date_x[1]);
					
					$tmp_sts = $sts[$x];
					$tmp_datetime = $datetime[$x];
					$tmp_vehicleserial = $vehicleserial[$x];      				              
      				$tmp_lat = $lat[$x];				
      				$tmp_lng = $lng[$x]; 
      				$tmp_cellname = $cellname[$x]; 
      				$tmp_speed = $speed[$x];      				
					$tmp_fix = $fix[$x];    				
      				
					$z = $x - 1;
					$done = false;
					while($done == false)
					{
						$date_x=null;
						$date_x = explode("=",$datetime[$z]);
						$date_tmp1 = preg_replace('/"/', '', $date_x[1]);
      
						if ($date_tmp1 >$value)
						{
							$sts[$z + 1] = $sts[$z];
							$datetime[$z + 1] = $datetime[$z];
							$vehicleserial[$z + 1] = $vehicleserial[$z];
							$lat[$z + 1] = $lat[$z];
							$lng[$z + 1] = $lng[$z]; 
							$cellname[$z + 1] = $cellname[$z];
              				$speed[$z + 1] = $speed[$z];
							$fix[$z + 1] = $fix[$z];
							//////////////////
							$z = $z - 1;
							if ($z < 0)
							{
								$done = true;
							}
						}
						else
						{
							$done = true;
						}
					} 
					$sts[$z + 1] = $tmp_sts;
					$datetime[$z + 1] = $tmp_datetime;
					$vehicleserial[$z + 1] = $tmp_vehicleserial;
					//$vehiclename[$z + 1] = $tmp_vehiclename;
					$lat[$z + 1] = $tmp_lat;
					$lng[$z + 1] = $tmp_lng;
					$cellname[$z + 1] = $tmp_cellname;   
					$speed[$z + 1] = $tmp_speed;
					$fix[$z + 1] = $tmp_fix;              
                        	//echo "vserial2=".$vehicleserial[$z + 1]."<br>";       
				}            
				///////////// SORTING ALGO 2 CLOSED /////////////////////                  
				$fh = fopen($xml_original_tmp, 'a') or die("can't open file 5"); //append
				$linetowrite ="<t1>"; 
				fwrite($fh, $linetowrite); 
				//echo "<br>SORTED FILE".$i;         
				for($y=0;$y<$i;$y++)
				{
					$linetowrite =  "\n"."<x ".$lat[$y]." ".$lng[$y]." ".$cellname[$y]." ".$speed[$y]." ".$sts[$y]." ".$datetime[$y]." ".$fix[$y]."/>";
					//echo "<textarea>".$linetowrite."</textarea>";
					fwrite($fh, $linetowrite);     
				}
				$linetowrite ="\n</t1>"; 
				fwrite($fh, $linetowrite);
				fclose($fh);  //              
			}  // IF file CLOSED   //   
		}     // FUNCTION CLOSED  
	}