<?php
echo "DAILY DISTANCE LOG\n";
set_time_limit(20000);

//$HOST = "111.118.181.156";
include_once("database_ip.php");
$DBASE = "iespl_vts_beta";
$USER = "root";
$PASSWD = "mysql";
//echo "\nDBASE=".$DBASE." ,User=".$USER." ,PASS=".$PASSWD;
$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("Connection to server is down. Please try after few minutes.");
mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");

$abspath = "/var/www/html/vts/beta/src/php";
include_once($abspath."/common_xml_element.php");
include_once($abspath."/get_all_dates_between.php");
include_once($abspath."/sort_xml.php");
include_once($abspath."/calculate_distance.php");

//echo "\nAfter include";

read_current_xmlfiles();

function read_current_xmlfiles()
{
	$date = date('Y-m-d');
	$previous_date = date('Y-m-d', strtotime($date .' -1 day'));
	$source_dir = "/sorted_xml_data_ln/".$previous_date;
	
	$date_tmp = explode('-',$previous_date);
	$previous_year = $date_tmp[0];
	$previous_month = $date_tmp[1];
	$dest_month = $previous_year."-".$previous_month;
	
	$dest_dir ="/home/VTS/daily_distance_log/".$dest_month;
	//echo "\nDestDir=".$dest_dir;

	mkdir ($dest_dir, 0755, false);
	//echo "\nDirectory Available";
	
	$dh = opendir($source_dir);
	//echo "\nSourcepath=".$source_dir;
	
	while (($file = readdir($dh)) !== false) {
		//echo "<A HREF=\"$file\">$file</A><BR>\n";		
		$ext = explode(".",$file);
		if($ext[1]=="xml")
		{
			//echo "\nFile=".$file;
			$file_list[] = $file;
		}
	}
	
	$total_files = sizeof($file_list);
	
	for($i=0;$i<$total_files;$i++)
	{
		$imei = explode(".",$file_list[$i]);
		$startdate = $previous_date." 00:00:00";
		$enddate = $previous_date." 23:00:00";		
		//READ AND WRITE DISTANCE RECORD		
		get_distance_xml_data($imei[0], $startdate, $enddate, $dest_dir);
		echo "\nRecord Added, SNo=".$i." ,IMEI=".$imei[0]." ,Date=".$previous_date." ,Total Files=".$total_files;
	}
}


function get_distance_xml_data($vehicle_serial, $startdate, $enddate, $dest_dir)
{
	$abspath = "/var/www/html/vts/beta/src/php";
	include($abspath."/common_xml_element_for_function.php");
	//echo "<br>vs=".$vehicle_serial." ,vname=".$vname." ,startdate=".$startdate." ,enddate=".$enddate;
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
	$breakflag = 0;
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

	//$j = 0;
	$total_dist = 0; 									  
	global $timetmp1;
	global $breakflag;
  
	for($i=0;$i<=($date_size-1);$i++)
	{
		//echo "<br>time=".$timetmp1;
		$timetmp2 = date("Y-m-d H:i:s");	
		$timetmp2 = strtotime($timetmp2);    
		$difftmp = ($timetmp2 - $timetmp1);
		//echo "<br>diff=".$difftmp;    
	
		$daily_dist = 0;    
		$xml_current = "/xml_data_ln/".$userdates[$i]."/".$vehicle_serial.".xml";	    		
		//echo "<br>xml_current=".$xml_current;
		
		if (file_exists($xml_current))      
		{		    		
			//echo "in else";
			$xml_file = $xml_current;
			$CurrentFile = 1;
		}		
		else
		{
			$xml_file = "/sorted_xml_data_ln/".$userdates[$i]."/".$vehicle_serial.".xml";
			$CurrentFile = 0;
		}
		
			//echo "<br>xml_file =".$xml_file;	    	
		if (file_exists($xml_file)) 
		{			
			//$current_datetime1 = date("Y_m_d_H_i_s");
			$t=time();
			//$xml_original_tmp = "/var/www/html/itrack_vts/xml_tmp/original_xml/tmp_".$vehicle_serial."_".$current_datetime1.".xml";
			$xml_original_tmp = "/var/www/html/itrack_vts/xml_tmp/original_xml/tmp_".$vehicle_serial."_".$t."_".$i.".xml";
			//$xml_log = "/var/www/html/itrack_vts/xml_tmp/filtered_xml/tmp_".$current_datetime1.".xml";
			//echo "<br>xml_file=".$xml_file." <br>tmpxml=".$xml_original_tmp."<br>";

			if($CurrentFile == 0)
			{
				//echo "<br>ONE<br>";
				copy($xml_file,$xml_original_tmp);
			}
			else
			{
				//echo "<br>TWO<br>";
				//$xml_unsorted = "/var/www/html/itrack_vts/xml_tmp/unsorted_xml/tmp_".$vehicle_serial."_".$current_datetime1."_unsorted.xml";
				$xml_unsorted = "/var/www/html/itrack_vts/xml_tmp/unsorted_xml/tmp_".$vehicle_serial."_".$t."_".$i."_unsorted.xml";
				//echo  "<br>".$xml_file." <br>".$xml_unsorted."<br><br>";

				copy($xml_file,$xml_unsorted);        // MAKE UNSORTED TMP FILE
				SortFile($xml_unsorted, $xml_original_tmp);    // SORT FILE
				unlink($xml_unsorted);                // DELETE UNSORTED TMP FILE
			}
      
			$total_lines = count(file($xml_original_tmp));
			//echo "<br>Total lines orig=".$total_lines;

			$xml = @fopen($xml_original_tmp, "r") or $fexist = 0;  
			//$xmllog = fopen($xml_log, "w") or $fexist1 = 0;
			$logcnt=0;
			$DataComplete=false;
					  
			$vehicleserial_tmp=null;
			$format =2;
            
			if (file_exists($xml_original_tmp)) 
			{      
				$daily_dist =0;
				//$firstdata_flag =0;

				while(!feof($xml))          // WHILE LINE != NULL
				{
					$DataValid = 0;
					//echo fgets($file). "<br />";
					$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE			

					if(strlen($line)>20)
					{
						$linetmp =  $line;
					}
  				
					$linetolog =  $logcnt." ".$line;
					$logcnt++;
					//fwrite($xmllog, $linetolog);
					//SWITCH MASTER VARIABLES
					set_master_variable($userdates[$i]);

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
						//preg_match('/\d+-\d+-\d+ \d+:\d+:\d+/', $line, $str3tmp);    // EXTRACT DATE FROM LINE
						//echo "<br>str3tmp[0]=".$str3tmp[0];
						$status = preg_match('/'.$vh.'="[^"]+/', $line, $datetime_tmp);
						$datetime_tmp1 = explode("=",$datetime_tmp[0]);
						$datetime = preg_replace('/"/', '', $datetime_tmp1[1]);	
						$xml_date = $datetime;
					}				
					//echo "Final0=".$xml_date." datavalid=".$DataValid;
          
					if($xml_date!=null)
					{				  
						//echo "<br>".$xml_date.",".$startdate.",".$enddate.",".$DataValid;
						//$lat = $lat_value[1] ;
						//$lng = $lng_value[1];
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

							$lat_tmp1 = explode("=",$lat_tmp[0]);
							$lat = preg_replace('/"/', '', $lat_tmp1[1]);

							$lng_tmp1 = explode("=",$lng_tmp[0]);
							$lng = preg_replace('/"/', '', $lng_tmp1[1]); 
                              							  					
							/*$vehicleserial_tmp1 = explode("=",$vehicleserial_tmp[0]);
							$vserial = preg_replace('/"/', '', $vehicleserial_tmp1[1]);*/
							$vserial = $vehicle_serial;
							//echo "<br>first=".$firstdata_flag;                                        
							if($firstdata_flag==0)
							{
								//echo "<br>FirstData";
								$firstdata_flag = 1;
								$lat1 = $lat;
								$lng1 = $lng;
								$last_time1 = $datetime;                
								$latlast = $lat;
								$lnglast =  $lng;                                                                        													                 	
							}           	
							//echo "<br>k2=".$k2."<br>";
							else
							{                           
								$time2 = $datetime;											
								$date_secs2 = strtotime($time2);	
						
								$lat2 = $lat;
								$lng2 = $lng;  

								calculate_distance($lat1, $lat2, $lng1, $lng2, &$distance);
								//echo "<br>lat1=".$lat1." ,lat2=".$lat2." ,lng1=".$lng1." ,lng2=".$lng2." ,dist=".$distance;
						
								$tmp_time_diff1 = ((double) (strtotime($datetime) - strtotime($last_time1))) / 3600;
								calculate_distance($latlast, $lat2, $lnglast, $lng2, &$distance1);

								if($tmp_time_diff1>0)
								{
									$tmp_speed = ((double) ($distance1)) / $tmp_time_diff1;
									$last_time1 = $datetime;        					
									$latlast = $lat2;
									$lnglast =  $lng2;        					
								}
								$tmp_time_diff = ((double)( strtotime($datetime) - strtotime($last_time) )) / 3600;
								
								if($tmp_speed<500 && $distance>0.1 && $tmp_time_diff>0)
								{		              
									$daily_dist= (float) ($daily_dist + $distance);	                  						                          
									//echo "\nDailyDist=".$daily_dist;

									$lat1 = $lat2;
									$lng1 = $lng2;

									$last_time = $datetime;			
								}							                               
							}
						} // $xml_date_current >= $startdate closed
					}   // if xml_date!null closed
					//$j++;
				}   // while closed
			} // if original_tmp closed         
		
			//WRITE DAILY DISTANCE DATA
			$daily_dist = round($daily_dist,2);	 		
		  
			fclose($xml);            
			unlink($xml_original_tmp);
		} // if (file_exists closed
	}  // for closed 
	//echo "Test1";
	
	//######## WRITE DISTANCE TO IMEI
	$dest_filepath =  $dest_dir."/".$vserial.".xml";
	$daily_distance_data = "<marker imei=\"".$vserial."\" date=\"".$datefrom."\" daily_dist=\"".$daily_dist."\"/>\n";
	$file = fopen($dest_filepath,"a");
	fwrite($file, $daily_distance_data);
	fclose($file);		
	//###############################
}
