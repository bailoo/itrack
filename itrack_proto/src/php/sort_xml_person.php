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
					//echo "vv=".$vv."<br>";
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
				  
					             
					$status = preg_match('/'.$vc.'="[^" ]+/', $line, $fix_tmp);
					if($status==0)
					{
						continue;
					} 			
					
					$status = preg_match('/'.$vab.'="[^" ]+/', $line, $cellname_tmp);
					if($status==0)
					{
						continue;
					}  
					//echo "vab=".$vab."<br>";
				} // FORMAT 1 CLOSED    
				
				if(($format == 1) && ($DataValid == 1))
				{   
					//echo "<br>In Format11";
					$datetime[$i] = $datetime_tmp[0].'"';                // Store Name with Value
					$vehicleserial[$i] = $vehicleserial_tmp[0].'"';
					$lat[$i] = $lat_tmp[0].'"';
					$lng[$i] = $lng_tmp[0].'"';	
					$fix[$i] = $fix_tmp[0].'"'; 					
					$cellname_v[$i] = $cellname_tmp[0].'"'; 
					$i++;                      
				}    // IF FORMAT 1 CLOSED  
			}
			if($format == 1)
			{
				//echo "<br>In Format111";
				//echo "i=".$i."<br>";
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
					$tmp_fix = $fix[$x]; 
					//$tmp_cellname_v = $cellname_v[$x];
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
							
							$cellname_v[$z + 1] = $cellname_v[$z]; 
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
					///$cellname_v[$z + 1] = $tmp_cellname_v;			   
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
						$linetowrite =  "\n"."<x ".$vehicleserial[$y]." ".$lat[$y]." ".$lng[$y]." ".$datetime[$y]." ".$fix[$y]." ".$cellname_v[$y]."/>";
						//echo '<textarea>'.$line.'</textarea>'; 
					}
					else
					{
						//echo '<textarea>'.$linetowrite.'</textarea>'; 
						$linetowrite =  "\n"."<x ".$lat[$y]." ".$lng[$y]." ".$datetime[$y]." ".$fix[$y]." ".$cellname_v[$y]."/>";
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
                        