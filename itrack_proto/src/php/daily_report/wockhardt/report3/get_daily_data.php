<?php

$DEBUG =0; 

function get_daily_data($imei, $vname, $startdate, $enddate)
{
  global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
  global $old_xml_date;

  //echo "\nFUNCTION1::imei=".$imei.",vname=".$vname.",startdate=".$startdate.",enddate=".$enddate.",opdate1=".$op_date1.",optdate2=".$op_date2.",geopt_size=".sizeof($geo_point);  
  //echo "\ndebug1:";
  /*$geo_coord = explode(',',$geo_point);
  $lat_g = trim($geo_coord[0]);
  $lng_g = trim($geo_coord[1]); */
  //echo "\nindividual-geo_lat=".$lat_g." ,geo_lng=".$lng_g;
  $nonpoi_halt_time = $nonpoi_halt_time."0";
  $non_poi_halt_seconds = get_seconds($nonpoi_halt_time);
  
  for($k=0;$k<sizeof($geo_point);$k++)
  {    
    $firstdata_flag_halt[$k] = 0;
    $halt_flag[$k] = 0;
    $total_halt_time[$k] = 0;
    $total_nof_halt[$k] = 0;
    $valid_location[$k] = 0;
  }
  
  $flag_violated_d1 = 0;
  $flag_violated_d2 = 0;            

  $firstdata_flag_non_poi = 0;
  $halt_flag_non_poi = 0;
  $total_nof_halt_non_poi = 0;       
  
  //$back_dir = "../../../../../..";
  $back_dir = "/var/www/html/itrack_vts";
  //$abspath_current = "/mnt/volume3";
  //$abspath_sorted = "/mnt/volume4";			
  
  $fix_tmp = 1;
	$xml_date_latest="1900-00-00 00:00:00";
	$CurrentLat = 0.0;
	$CurrentLong = 0.0;
	$LastLat = 0.0;
	$LastLong = 0.0;
	$firstData = 0;
	$distance =0.0;
	$linetowrite="";
	$firstdata_flag_dist =0;
	//$firstdata_flag_halt =0;
  	  
  $date_1 = explode(" ",$startdate);
	$date_2 = explode(" ",$enddate);

	$datefrom = $date_1[0];
	$dateto = $date_2[0];
	$timefrom = $date_1[1];
	$timeto = $date_2[1];

	get_All_Dates($datefrom, $dateto, &$userdates);

	//date_default_timezone_set("Asia/Calcutta");
	$current_datetime = date("Y-m-d H:i:s");
	$current_date = date("Y-m-d");
	//print "<br>CurrentDate=".$current_date;
	$date_size = sizeof($userdates);
  $interval = 300;   //5 mins
  $flag_file_found =0; 									   
  
  $nodevice_reporting_flag = 1;
  $no_movement_flag =1;
  $total_dist = 0;
  include("/var/www/html/vts/beta/src/php/common_xml_path.php"); 
  for($i=0;$i<=($date_size-1);$i++)
	{   
    //echo " debug2:";
    $flag_file_found =0; 	
    //#####DEFINE VARIABLES  
    $daily_dist = 0;
    $total_dist = 0;
    $ophr_dist = 0;
    $non_ophr_dist = 0;
    
    $total_nof_halt = 0;
    $total_halt_time = 0;
    $avg_halt_time = 0;
    //#############      
        
    $xml_current = $xml_data."/".$userdates[$i]."/".$imei.".xml";	    		

    //echo "\nxml_path=".$xml_current;
    
    if (file_exists($xml_current))      
    {		    		
			//echo "in else";
			$xml_file = $xml_current;
			$CurrentFile = 1;
		}		
		else
		{
			$xml_file = $sorted_xml_data."/".$userdates[$i]."/".$imei.".xml";
			$CurrentFile = 0;
		}
		
		//echo "<br>xml_file =".$xml_file;	    	
    if (file_exists($xml_file)) 
	{			
		//echo " debug3:";
		//echo "\nSorted xml file exists";
		$t=time();
		$xml_original_tmp = $back_dir."/xml_tmp/original_xml/tmp_".$imei."_".$t."_".$i.".xml";
									  
		if($CurrentFile == 0)
		{
			//echo "<br>ONE<br>";
			copy($xml_file,$xml_original_tmp);
		}
		else
		{
			//echo "<br>TWO<br>";
			$xml_unsorted = $back_dir."/xml_tmp/unsorted_xml/tmp_".$imei."_".$t."_".$i."_unsorted.xml";
					
			copy($xml_file,$xml_unsorted);        // MAKE UNSORTED TMP FILE
			SortFile($xml_unsorted, $xml_original_tmp, $userdates[$i]);    // SORT FILE
			unlink($xml_unsorted);                // DELETE UNSORTED TMP FILE
		}

		$total_lines = count(file($xml_original_tmp));
		//echo "\nTotal lines orig=".$total_lines;

		$xml = @fopen($xml_original_tmp, "r") or $fexist = 0;  
		//$xmllog = fopen($xml_log, "w") or $fexist1 = 0;
		$logcnt=0;
		$DataComplete=false;
				  
		$vehicleserial_tmp=null;
		$format =2;

		if (file_exists($xml_original_tmp)) 
		{      
			//echo " debug4:";
			$flag_file_found =1; 	
			//echo "\nOriginal file exists";
			$daily_dist =0;
			// $firstdata_flag =0;

			//SET MASTER VARIABLE
			set_master_variable($userdates[$i]);
					
			while(!feof($xml))                // WHILE LINE != NULL
			{
				$non_poi_halt_status = 1; 
				$DataValid = 0;
				//echo fgets($file). "<br />";
				$line = fgets($xml);            // STRING SHOULD BE IN SINGLE QUOTE			

				if(strlen($line)>20)
				{
				  $linetmp =  $line;
				}

				$linetolog =  $logcnt." ".$line;
				$logcnt++;
				//fwrite($xmllog, $linetolog);


				if(strpos($line,''.$vc.'="1"'))     // RETURN FALSE IF NOT FOUND
				{
					$format = 1;
					$fix_tmp = 1;
				}

				else if(strpos($line,''.$vc.'="0"'))
				{
				  $format = 1;
					$fix_tmp = 0;
				}
					
				if( (preg_match('/'.$vd.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) &&  (preg_match('/'.$ve.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
				{ 
				$lat_value = explode('=',$lat_match[0]);
				$lng_value = explode('=',$lng_match[0]);
				//echo " lat_value=".$lat_value[1];         
				if( (strlen($lat_value[1])>5) && ($lat_value[1]!="-") && (strlen($lng_value[1])>5) && ($lng_value[1]!="-") )
				{
				  $DataValid = 1;
				}
			}

			//if( (substr($line, 0,1) == '<') && (substr( (strlen($line)-1), 0,1) == '>') && ($fix_tmp==1) && ($f>0) && ($f<$total_lines-1) )        
			if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($DataValid == 1) )   // FIX_TMP =1 COMES IN BOTH CASE     
			{
				$status = preg_match('/'.$vh.'="[^"]+/', $line, $datetime_tmp);
				$datetime_tmp1 = explode("=",$datetime_tmp[0]);
				$datetime = preg_replace('/"/', '', $datetime_tmp1[1]);	
				$xml_date = $datetime;
			}				
			//echo "Final0=".$xml_date." datavalid=".$DataValid;
          
			if($xml_date!=null)
  			{				  
				//echo "\nxml_data=".$xml_date.",".$startdate.",".$enddate.",".$DataValid; 					
				if( ($xml_date >= $startdate && $xml_date <= $enddate) && ($xml_date!="-") && ($DataValid==1) )
				{							           	
					//echo "<br>One";             
					/*$status = preg_match('/vehicleserial="[^" ]+/', $line, $vehicleserial_tmp);              
					//echo "Status=".$status.'<BR>';
					//echo "test1".'<BR>';
					if($status==0)
					{
						continue;
					}*/                                                                                       
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
					   
					/*$vehicleserial_tmp1 = explode("=",$vehicleserial_tmp[0]);
					$vserial = preg_replace('/"/', '', $vehicleserial_tmp1[1]);*/
					$vserial = $imei;
					$lat_tmp1 = explode("=",$lat_tmp[0]);
					$lat = preg_replace('/"/', '', $lat_tmp1[1]);

					$lng_tmp1 = explode("=",$lng_tmp[0]);
					$lng = preg_replace('/"/', '', $lng_tmp1[1]);                               
					//echo "<br>first=".$firstdata_flag;                                        
					   
					if($firstdata_flag==0)
					{
						$firstdata_flag = 1;
						$nodevice_reporting_flag = 0;                  
						//$vehicleserial_tmp1 = explode("=",$vehicleserial_tmp[0]);
						//$vserial = preg_replace('/"/', '', $vehicleserial_tmp1[1]);

						$vserial = $imei; 
						$lat_tmp1 = explode("=",$lat_tmp[0]);
						//echo "<br>lat_tmp=".$lat_tmp[0]." ,lat_tmp1=".$lat_tmp1;
						$lat_ref = preg_replace('/"/', '', $lat_tmp1[1]);

						$lng_tmp1 = explode("=",$lng_tmp[0]);
						$lng_ref = preg_replace('/"/', '', $lng_tmp1[1]);

						$datetime_ref = $datetime;            	
					}           	
					//echo "<br>k2=".$k2."<br>";                	
					else
					{           
						//echo "<br>Next";               
						$lat_tmp1 = explode("=",$lat_tmp[0]);                           //  GET NEXT RECO
						$lat_cr = preg_replace('/"/', '', $lat_tmp1[1]);

						$lng_tmp1 = explode("=",$lng_tmp[0]);
						$lng_cr = preg_replace('/"/', '', $lng_tmp1[1]);

						$datetime_cr = $datetime;                		
							
						calculate_distance($lat_ref, $lat_cr, $lng_ref, $lng_cr, &$distance);
						if( ($distance > 0.150) )
						  {            		
						  $total_dist = $total_dist + $distance;
						  $lat_ref = $lat_cr;
						  $lng_ref = $lng_cr;                   
						}
					}           
						// HALT LOGIC CLOSED 
					  
					  //############# HALT SECTION CLOSED ################//                
					 //echo " debug6:";
				} // $xml_date_current >= $startdate closed
					//echo " debug7:";
			}   // if xml_date!null closed  				
  			 //$j++;
        }   // while closed
      } // if original_tmp closed   
      //echo " debug8:";      			
		} // if (file_exists closed
		//echo " debug9:";
	}  // for closed 
	
    
  $nodata_string = "-";
  $nomovement_string ="-";
  
	if($nodevice_reporting_flag)                //NO DEVICE REPORTING
	{
	  $nodata_string = $datefrom; 
	}
	else
	{
		//echo "\nTotalDistance=".$total_dist;

		if( ($total_dist < 1) )    // >1 KM       //NO DEVICE MOVEMENT
		{
		  $nomovement_string = $datefrom;	
		}
	}	
			                             
  if($flag_file_found)
  { 	    
    fclose($xml); 
    unlink($xml_original_tmp);
  }
  //echo " debug11:";
  $daily_data = $nomovement_string."#".$nodata_string;

  //echo "\nDailyData=".$daily_data;
                   
  return $daily_data;	
  
}


function get_seconds($time_format) 
{
    /*$hours = substr($time, 0, -6);
    $minutes = substr($time, -5, 2);
    $seconds = substr($time, -2);*/
    $time = explode(':',$time_format);
    $hours = $time[0];
    $minutes = $time[1];
    $seconds = $time[2];
    //echo "\ntime=".$hours."::".$minutes;    
    $seconds = ($hours * 3600) + ($minutes * 60) + ($seconds);
    return $seconds; 
}
                 							
?>
						
