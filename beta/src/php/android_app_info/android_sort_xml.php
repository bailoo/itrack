<?php
 function SortFile($xml_unsorted, $xml_original_tmp,$conditional_date)
{
	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
	global $old_xml_date;
	global $report;
	//echo "<br>unsorted=".$xml_unsorted." <br>orgi=".$xml_original_tmp;  
	$fexist =1;
	$fix_tmp = 1;
	$xml = fopen($xml_unsorted, "r") or $fexist = 0;

		$count = count(file($xml_unsorted));
		//echo "<BR>COUNT======== $count lines in $xml";
		//$xml2 = '"'.$xml.'"';
		if($fexist)
		{
			$i=0; 
			set_master_variable($conditional_date);	
			while(!feof($xml))          // WHILE LINE != NULL
			{
				$DataValid = 0;
				$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE		
				//echo "line=".$line;
				//echo '<textarea>'.$line.'</textarea>';  
					
///echo "vc1=".$vc;				
				
				if(strpos($line,''.$vc.'="1"'))     // RETURN FALSE IF NOT FOUND
				{
					$fix_tmp = 1;
					$format = 1;
				}      
				else if(strpos($line,''.$vc.'="0"'))
				{
					$fix_tmp = 0;
					$format = 1;
				} 
				
				if ((preg_match('/'.$vd.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match) ) && (preg_match('/'.$ve.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
				{ 
					$lat_value = explode('=',$lat_match[0]);
					$lng_value = explode('=',$lng_match[0]);
					//echo "<br>lat=".$lat_value[1]." lng=".$lng_value[1];

					if( (strlen($lat_value[1])>5) && ($lat_value[1]!="-") && (strlen($lng_value[1])>5) && ($lng_value[1]!="-") )
					{
						$DataValid = 1;
					}
				}

				//echo "<br>line[0]=".$line[0]." ,".$line[strlen($line)-2]." ,".$DataValid." ,".$fix_tmp;                    
				if($report == "engine")
				{
					$DataValid = 1;
					$fix_tmp = 1;
					$format =1;
				} 
			  
				if(($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($fix_tmp==1) && ($format==1) && ($DataValid == 1))
				{
					$imei_flag=1;
					if($vv=="v")
					{
						$imei_flag=0;
					}
					else
					{
					//echo "<br>In Format1";			           
						$status = preg_match('/'.$vv.'="[^" ]+/', $line, $vehicleserial_tmp);
					}
					//echo "Status=".$status.'<BR>';				
					/*if($status==0)
					{
						continue;
					}*/		
					//echo "test4".'<BR>';
					$status = preg_match('/'.$vh.'="[^"]+/', $line, $datetime_tmp);
					if($status==0)
					{
						continue;
					}
					//echo "test5".$datetime_tmp[0].'<BR>';
					$status = preg_match('/'.$vd.'="[^" ]+/', $line, $lat_tmp);
					if($status==0)
					{
						continue;
					}
					//echo "test6".'<BR>';
					$status = preg_match('/'.$ve.'="[^" ]+/', $line, $lng_tmp);
					if($status==0)
					{
						continue;
					}
				  
					$status = preg_match('/'.$vf.'="[^" ]+/', $line, $speed_tmp);
					if($status==0)
					{
						continue;
					}
				  //echo "test9".'<BR>';              
					$status = preg_match('/'.$vc.'="[^" ]+/', $line, $fix_tmp);
					if($status==0)
					{
						continue;
					} 			
					$status = preg_match('/'.$vi.'="[^" ]+/', $line, $io1_tmp);
					if($status==0)
					{
						continue;
					}              
					$status = preg_match('/'.$vj.'="[^" ]+/', $line, $io2_tmp);
					if($status==0)
					{
						continue;
					}    
				  
					$status = preg_match('/'.$vk.'="[^" ]+/', $line, $io3_tmp);
					if($status==0)
					{
						continue;
					}   

					$status = preg_match('/'.$vl.'="[^" ]+/', $line, $io4_tmp);
					if($status==0)
					{
						continue;
					}             

					$status = preg_match('/'.$vm.'="[^" ]+/', $line, $io5_tmp);
					if($status==0)
					{
						continue;
					}     

					$status = preg_match('/'.$vn.'="[^" ]+/', $line, $io6_tmp);
					if($status==0)
					{
						continue;
					}     
				  
					$status = preg_match('/'.$vo.'="[^"]+/', $line, $io7_tmp);
					if($status==0)
					{
						continue;
					}  
					$status = preg_match('/'.$vp.'="[^" ]+/', $line, $io8_tmp);
					if($status==0)
					{
						continue;
					} 
					$status = preg_match('/'.$vr.'="[^" ]+/', $line, $sup_v_tmp);
					if($status==0)
					{
						continue;
					}  
				} // FORMAT 1 CLOSED    
				
				if(($format == 1) && ($DataValid == 1))
				{   
					//echo "<br>In Format11";
					$datetime[$i] = $datetime_tmp[0].'"';                // Store Name with Value
					$vehicleserial[$i] = $vehicleserial_tmp[0].'"';
					$lat[$i] = $lat_tmp[0].'"';
					$lng[$i] = $lng_tmp[0].'"';
					$speed[$i] = $speed_tmp[0].'"';
					$fuel[$i] = $fuel_tmp[0].'"';
					$io7[$i] = $io7_tmp[0].'"'; 
					$fix[$i] = $fix_tmp[0].'"';  
					$io1[$i] = $io1_tmp[0].'"'; 
					$io2[$i] = $io2_tmp[0].'"'; 
					$io3[$i] = $io3_tmp[0].'"'; 
					$io4[$i] = $io4_tmp[0].'"'; 
					$io5[$i] = $io5_tmp[0].'"'; 
					$io6[$i] = $io6_tmp[0].'"'; 
					$io7[$i] = $io7_tmp[0].'"'; 
					$io8[$i] = $io8_tmp[0].'"';
					//$sig_str[$i] = $sig_str_tmp[0].'"'; 
					$sup_v[$i] = $sup_v_tmp[0].'"'; 
					$i++;                      
				}    // IF FORMAT 1 CLOSED  
			}
			if($format == 1)
			{
				//echo "<br>In Format111";
				for($x = 1; $x < $i; $x++) 
				{
					$date_x=null;
					$date_x = explode("=",$datetime[$x]);
					$value = preg_replace('/"/', '', $date_x[1]);

					$tmp_datetime = $datetime[$x];
					$tmp_vehicleserial = $vehicleserial[$x];
					//$tmp_vehiclename = $vehiclename[$x];
					$tmp_lat = $lat[$x];
					$tmp_lng = $lng[$x];
					//$tmp_alt = $alt[$x];
					//$tmp_vehicletype = $vehicletype[$x];
					$tmp_speed = $speed[$x];
					$tmp_fuel = $fuel[$x];
					$tmp_io7 = $io7[$x];
					$tmp_fix = $fix[$x];               
					/*$tmp_no_of_sat = $no_of_sat[$x]; 
					$tmp_cellname = $cellname[$x]; 
					$tmp_distance = $distance[$x]; */
					$tmp_io1 = $io1[$x]; 
					$tmp_io2 = $io2[$x]; 
					$tmp_io3 = $io3[$x]; 
					$tmp_io4 = $io4[$x]; 
					$tmp_io5 = $io5[$x]; 
					$tmp_io6 = $io6[$x]; 
					$tmp_io7 = $io7[$x];
					$tmp_io8 = $io8[$x];
					//$tmp_sig_str = $sig_str[$x]; 
					$tmp_sup_v = $sup_v[$x];
					$z = $x - 1;
					$done = false;
					while($done == false)
					{
						$date_x=null;
						$date_x = explode("=",$datetime[$z]);
						$date_tmp1 = preg_replace('/"/', '', $date_x[1]);

						if ($date_tmp1 >$value)
						{
							$datetime[$z + 1] = $datetime[$z];
							$vehicleserial[$z + 1] = $vehicleserial[$z];
							//$vehiclename[$z + 1] = $vehiclename[$z];
							$lat[$z + 1] = $lat[$z];
							$lng[$z + 1] = $lng[$z];
							//$alt[$z + 1] = $alt[$z];
							//$vehicletype[$z + 1] = $vehicletype[$z];
							$speed[$z + 1] = $speed[$z];
							$fuel[$z + 1] = $fuel[$z];
							$io7[$z + 1] = $io7[$z];
							
							$fix[$z + 1] = $fix[$z]; 
							$io1[$z + 1] = $io1[$z];
							$io2[$z + 1] = $io2[$z];
							$io3[$z + 1] = $io3[$z];
							$io4[$z + 1] = $io4[$z];
							$io5[$z + 1] = $io5[$z];
							$io6[$z + 1] = $io6[$z];
							$io7[$z + 1] = $io7[$z];
							$io8[$z + 1] = $io8[$z];
							//$sig_str[$z + 1] = $sig_str[$z];  
							$sup_v[$z + 1] = $sup_v[$z]; 
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
					$datetime[$z + 1] = $tmp_datetime;
					$vehicleserial[$z + 1] = $tmp_vehicleserial;
					$lat[$z + 1] = $tmp_lat;
					$lng[$z + 1] = $tmp_lng;
					$speed[$z + 1] = $tmp_speed;
					$fuel[$z + 1] = $tmp_fuel;
					$io7[$z + 1] = $tmp_io7; 
					$fix[$z + 1] = $tmp_fix;
					$io1[$z + 1] = $tmp_io1;
					$io2[$z + 1] = $tmp_io2;
					$io3[$z + 1] = $tmp_io3;
					$io4[$z + 1] = $tmp_io4;
					$io5[$z + 1] = $tmp_io5;
					$io6[$z + 1] = $tmp_io6;
					$io7[$z + 1] = $tmp_io7;
					$io8[$z + 1] = $tmp_io8;             
					$sup_v[$z + 1] = $tmp_sup_v;			   
				} 
				//echo "1=".$xml_original_tmp;
				$fh = fopen($xml_original_tmp, 'a') or die("can't open file 5"); //append
				$linetowrite ="<t1>"; 
				fwrite($fh, $linetowrite);          
				for($y=0;$y<$i;$y++)
				{
					//$linetowrite =  "\n"."<marker ".$vehicleserial[$y]." ".$lat[$y]." ".$lng[$y]." ".$speed[$y]." ".$datetime[$y]." ".$io1[$y]." ".$io2[$y]." ".$io3[$y]." ".$io4[$y]." ".$io5[$y]." ".$io6[$y]." ".$io7[$y]." ".$io8[$y]." ".$fix[$y]." ".$sup_v[$y]."/>";
					if($imei_flag==1)
					{
						$linetowrite =  "\n"."<x ".$vehicleserial[$y]." ".$lat[$y]." ".$lng[$y]." ".$speed[$y]." ".$datetime[$y]." ".$io1[$y]." ".$io2[$y]." ".$io3[$y]." ".$io4[$y]." ".$io5[$y]." ".$io6[$y]." ".$io7[$y]." ".$io8[$y]." ".$fix[$y]." ".$sup_v[$y]."/>";
					}
					else
					{
						$linetowrite =  "\n"."<x ".$lat[$y]." ".$lng[$y]." ".$speed[$y]." ".$datetime[$y]." ".$io1[$y]." ".$io2[$y]." ".$io3[$y]." ".$io4[$y]." ".$io5[$y]." ".$io6[$y]." ".$io7[$y]." ".$io8[$y]." ".$fix[$y]." ".$sup_v[$y]."/>";
					}//echo "jfajds=".$linetowrite; 
					//echo "2=".$xml_original_tmp;
					fwrite($fh, $linetowrite);  			      
				}   
			}// FORMAT 1 CLOSED 
	//echo "3=".$xml_original_tmp;			
			$linetowrite ="\n</t1>"; 
			fwrite($fh, $linetowrite);
			fclose($fh);             
		}  // IF file CLOSED   //
	}     // FUNCTION CLOSED

?>
