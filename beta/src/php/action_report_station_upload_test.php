<?php
	include_once("calculate_distance.php");
	//echo "<center><h3>THIS PAGE IS GOING UNDER UPDATION</h3></center><br>";
	$DEBUG=0;	

	$target_path = "upload/";

	$target_path = $target_path . basename( $_FILES['file']['name']); 

	if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) 
	{
	  //echo "The Input file ".  basename( $_FILES['file']['name']). " has been processed.. please wait..";
	} 
	else
	{
	  //echo "There was an error in accepting input file, please try again!";
	}
  
	//***************READ XLS FILE RECORD ***********************
	/////////////////////////////////////////////////////////////     
	/*require_once 'Excel/reader.php';
	// ExcelFile($filename, $encoding);
	$data = new Spreadsheet_Excel_Reader();
	// Set output Encoding.
	$data->setOutputEncoding('CP1251');  */
	$path = "/var/www/html/vts/beta/src/php/upload/".$_FILES['file']['name'];

	$download_file = $_FILES['file']['name'];  
	$customer_no =null;
	$date =null;
	$stringData ="";

	$row = 1;
	$i=0;
	//$title = "";
	if (($handle = fopen($path, "r")) !== FALSE) 
	{
	  while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
	  {
		  $num = count($data);
		  
		  $record ="";
		  $date1 ="";
		  $time1 ="";
		  $date2 ="";
		  $time2 ="";
		  $doctype1 ="";
		  $plant1 ="";
		  $route1 ="";
		  $vname1 = "";
		  $vendor_name1="";
		  $customer_no1 = "";
		  $qty1 ="";
		  $unit1 ="";          
			  
		  //echo "<p> $num fields in line $row: <br /></p>\n";
		  $row++;
		  if($i==0)
		  {
			for ($c=0; $c < $num; $c++) 
			{
				//echo $data[$c] . "<br />\n";
				$record = $data[$c];
				if($c==0)
				  $stringData = $stringData.$record;
				else
				  $stringData = $stringData.",".$record;
			}            
		  }
		  else
		  {
			for ($c=0; $c < $num; $c++) 
			{            
				//echo $data[$c] . "<br />\n";
				$record = $data[$c];
				
				//echo "<br>record=".$record;               
				if($c==0)
				{
				  $date1 = $record;
				  //echo "<br>dt=".$date1;
				}
				else if($c==1)
				{
				  $time1 = $record;
				  //echo "<br>dt=".$date1;
				}
				else if($c==2)
				{
				  $date2 = $record;
				  //echo "<br>dt=".$date1;
				}
				else if($c==3)
				{
				  $time2 = $record;
				  //echo "<br>dt=".$date1;
				}                                
				else if($c==4)
				{
				  $doctype1 = $record;
				}
				else if($c==5)
				{
				  $plant1 = $record;
				}
				else if($c==6)
				{
				  $route1 = $record;
				}
				else if($c==7)
				{
				  $vname_tmp1 = $record;
				  //echo "<br>vname=".$vname1;
				}
				else if($c==8)
				{
				  $vendor_name1 = $record;
				}                     
				else if($c==9)
				{
				  $customer_no1 = $record;
				  //echo "<br>customer_no1=".$customer_no1;
				}
				else if($c==10)
				{
				  $qty1 = $record;
				}
				else if($c==11)
				{
				  $unit1 = $record;
				}                                
			}
		  }
		  $i++;

		  if($customer_no1!="")
		  {
			$date1_csv[] = $date1;
			$time1_csv[] = $time1;

			$date2_csv[] = $date2;
			$time2_csv[] = $time2;

			///////////********MAKE INDIVIDUAL START DATE AND END DATE*******//////////              
			$datetmp_a1 = explode('-',$date1);              
			$date1_tmp = $datetmp_a1[2]."-".$datetmp_a1[1]."-".$datetmp_a1[0]." ".$time1;
			
			$datetmp_a2 = explode('-',$date2);
			$date2_tmp = $datetmp_a2[2]."-".$datetmp_a2[1]."-".$datetmp_a2[0]." ".$time2;
			
			$input_date1[] = $date1_tmp;
			$input_date2[] = $date2_tmp;
			//echo "<br>input date1=".$date1_tmp." ,input date2=".$date2_tmp;              
			///////////////********************************************////////////////          	  
			$doctype[] = $doctype1;
			$plant[] = $plant1;
			$route[] = $route1;
			$vname[] = $vname_tmp1;
			$vendor_name[] = $vendor_name1;
			$customer_no[] = $customer_no1;
			//echo "<br>customer_no1=".$customer_no1."<br>";              
			$qty[] = $qty1;
			$unit[] = $unit1;      	  
			}                    
	  }
	  fclose($handle);
	}               
	unlink($path); 

	/// ******* STATION HALT LOGIC ********************************
	///////////////////////////////////////////////////////////////
	  
	$vname_tmp ="";

	for($i=0;$i<sizeof($vname);$i++)
	{
	if($i==0)
	{
	   $vname_tmp = $vname_tmp."'".$vname[$i]."'";
	}
	else
	{
	  $vname_tmp = $vname_tmp.",'".$vname[$i]."'";
	}
	}     

	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');

	set_time_limit(200);

	include_once("get_all_dates_between.php");
	include_once("sort_xml.php");
	include_once("calculate_distance.php");
	include_once("report_title.php");
	include_once("read_filtered_xml.php");
	include_once("select_landmark_report.php");
	include_once("util.hr_min_sec.php");

	include_once("area_violation/check_with_range.php");
	include_once("area_violation/pointLocation.php");
	include("user_type_setting.php");
	  
	$device_str = "";

	$query = "SELECT DISTINCT device_imei_no,vehicle_id FROM vehicle_assignment WHERE ".
	"vehicle_id IN(SELECT vehicle_id FROM vehicle WHERE vehicle_name IN($vname_tmp) AND status=1)".
	" AND status=1"; 
	//echo "<br>query1=".$query."<br>";
	$result = mysql_query($query,$DbConnection);  
	$i=0; 

	while($row = mysql_fetch_object($result))
	{
		$vserial[] =  $row->device_imei_no;
		$tmp_vid = $row->vehicle_id;
		$query2 = "SELECT vehicle_name FROM vehicle WHERE vehicle_id='$tmp_vid'";		
		//echo "<br>query2=".$query2."<br>";
		$result2 = mysql_query($query2,$DbConnection);
		$row2 = mysql_fetch_object($result2);
		$vname1[] =  $row2->vehicle_name;
	}                        

	//echo "<br>After query";	
	$vsize=sizeof($vserial);	
	
	//CODE FOR MULTIPLE DATES
	for($i=0;$i<sizeof($vname1);$i++)		//SIZE OF VNAME IN DATABASE 
	{			
		$in_min_date = "3000-00-00 00:00:00";
		$in_max_date = "0000-00-00 00:00:00";
		
		$out_min_date = "3000-00-00 00:00:00";		
		$out_max_date = "0000-00-00 00:00:00";
	
		for($j=0;$j<sizeof($vname);$j++)	//SIZE OF VNAME IN CSV
		{				
			if($vname1[$i] == $vname[$j])
			{
				//echo "<br>vehicle found".$vname[$j].", date1=".$date1_csv[$j]." ,time1=".$time1_csv[$j]." ,date2=".$date2_csv[$j]." ,time2=".$time2_csv[$j];
				//DATE1
				$datetmp_input1 = explode('-',$date1_csv[$j]);
				$in_date_csv = $datetmp_input1[2]."-".$datetmp_input1[1]."-".$datetmp_input1[0]." ".$time1_csv[$j];
				
				//DATE2
				$datetmp_input2 = explode('-',$date2_csv[$j]);
				$out_date_csv = $datetmp_input2[2]."-".$datetmp_input2[1]."-".$datetmp_input2[0]." ".$time2_csv[$j];				
				
				//echo "<br>IN::in_date_csv=".$in_date_csv." ,in_min_date=".$in_min_date." in_max_date=".$in_max_date;
				if($in_date_csv < $in_min_date)				//IN DATE
				{
					//echo "<br>in_date_csv < in_min_date";					
					$in_min_date_final[$i] = $in_date_csv;
					$in_min_date = $in_min_date_final[$i];
				}
				if($in_date_csv > $in_max_date)
				{
					//echo "<br>in_date_csv > in_max_date";
					$in_max_date_final[$i] = $in_date_csv;
					$in_max_date = $in_max_date_final[$i];
				}
				
				
				//echo "<br>OUT::in_date_csv=".$in_date_csv." ,out_min_date=".$out_min_date." out_max_date=".$out_max_date;
				if($out_date_csv < $out_min_date)				//OUT DATE
				{
					//echo "<br>out_date_csv < out_min_date";	
					$out_min_date_final[$i] = $out_date_csv;
					$out_min_date = $out_min_date_final[$i]; 
				}
				if($out_date_csv > $out_max_date)
				{
					//echo "<br>out_date_csv > out_max_date";
					$out_max_date_final[$i] = $out_date_csv;
					$out_max_date = $out_max_date_final[$i];
				}												
			}
		}
		//echo "<br>vname1=".$vname1[$i]." ,inmindate=".$in_min_date_final[$i]." ,inmaxdate=".$in_max_date_final[$i]." #outmindate=".$out_min_date_final[$i]." ,outmaxdate=".$out_max_date_final[$i];
	}
	/////////////////////////
	
	//echo "<br>vsize1=".$vsize;
	$datetmp_start_xls = $date1_csv[0];
	$datetmp_input = explode('-',$datetmp_start_xls);

	$date1 = $datetmp_input[2]."-".$datetmp_input[1]."-".$datetmp_input[0]." 00:00:00";
	$date2 = $datetmp_input[2]."-".$datetmp_input[1]."-".$datetmp_input[0]." 23:59:59";

	$local_account_ids1 = $_POST['local_account_ids'];	
	//echo "<br>vsize2=".$vsize;

	if($vsize>0)
	{
		$entered_flag = array();  
		$datetime_counter = array(); 
	  
		$stringData = $stringData.",IN DATE,IN TIME,OUT DATE,OUT TIME,DURATION(H:m:s),IN Distance from Station (km),OUT Distance from Station (km)";
		$stringData = $stringData.",IN DATE,IN TIME,OUT DATE,OUT TIME,DURATION(H:m:s),IN Distance from Station (km),OUT Distance from Station (km)";
		$stringData = $stringData.",IN DATE,IN TIME,OUT DATE,OUT TIME,DURATION(H:m:s),IN Distance from Station (km),OUT Distance from Station (km)";
		$stringData = $stringData.",IN DATE,IN TIME,OUT DATE,OUT TIME,DURATION(H:m:s),IN Distance from Station (km),OUT Distance from Station (km)";
		$stringData = $stringData.",IN DATE,IN TIME,OUT DATE,OUT TIME,DURATION(H:m:s),IN Distance from Station (km),OUT Distance from Station (km)\n";

		$geo_coord_station = array();  
		$geo_station = array();  
		$customer_no_db = array();  
				
		$intime_halt = array(array());
		$outime_halt = array(array());

		$in_distance = array(array());
		$out_distance = array(array());  
		$time_dur_halt = array(array());

		$vname_halt = array(array());
		$cust_no_halt = array(array());

		$current_dt = date("Y_m_d_H_i_s");	
		write_station_report_xml($vserial, $vname1, $date1, $date2);

		download_report_csv();   // CALL DOWNLOAD CSV FUNCTION  	       
	}

	function write_station_report_xml($vserial, $vname1, $startdate, $enddate)
	{
		//echo "<br>IN write_station_report_xml";
		$maxPoints = 1000;
		$file_exist = 0;

		global $DbConnection;
		global $account_id;
		global $local_account_ids1;
			
		//global $date;
		global $date1_csv;
		global $date2_csv;
		global $time1_csv;
		global $time2_csv;
		global $input_date1;
		global $input_date2;
		global $doctype;
		global $plant;
		global $route;
		global $vname;
		global $customer_no;
		global $vendor_name;
		global $qty;
		global $unit;
		
		global $in_min_date_final;
		global $out_max_date_final;

		global $geo_coord_station;  
		global $geo_station;  
		global $customer_no_db;  
		
		global $entered_flag;  
		global $datetime_counter;  

		global $intime_halt;
		global $outime_halt;  
		global $in_distance;
		global $out_distance;    
		global $time_dur_halt;

		global $vname_halt;
		global $cust_no_halt;
		global $stringData;
		
		for($i=0;$i<sizeof($customer_no);$i++)
		{
			/*$query_geo = "SELECT DISTINCT station_coord,station_name,customer_no FROM station WHERE user_account_id ='$local_account_ids1' AND customer_no ='$customer_no[$i]' AND ".
					"station_id =(SELECT DISTINCT station_id FROM station_assignment WHERE vehicle_id =(SELECT DISTINCT vehicle_id FROM vehicle WHERE vehicle_name='$vname[$i]' AND status=1) AND status=1) AND status=1";
			*/
			//echo "<br>Q=".$query_geo;    

			$query_geo = "SELECT DISTINCT station_coord,station_name,customer_no FROM station WHERE user_account_id ='$local_account_ids1' AND customer_no ='$customer_no[$i]' AND status=1";				
			$res_geo = mysql_query($query_geo,$DbConnection);

			/*$numrows = mysql_num_rows($res_geo);
			if($numrows>0)
			echo "<br>Station Coord Found";*/
			//$row_geo = mysql_fetch_object($res_geo);	
			if($row_geo = mysql_fetch_object($res_geo))
			{
				$geo_coord = $row_geo->station_coord;
				$geo_coord = str_replace(', ',',',$geo_coord);
				$geo_coord_station[] =  $geo_coord;
				$geo_station[] = $row_geo->station_name;
				$customer_no_db[] = $row_geo->customer_no;
				$entered_flag[$i] = 0;
				$datetime_counter[$i] = 0;
			}
			else
			{
				$geo_coord = "";
				$geo_coord = "";
				$geo_coord_station[] =  "";
				$geo_station[]  =  "";
				$customer_no_db[]  =  "";
				$entered_flag[$i] = 0;
				$datetime_counter[$i] = 0;      
			}
		}
	  
		for($i=0;$i<sizeof($vserial);$i++)
		{  	
			$startdate = $in_min_date_final[$i];
			$enddate = $out_max_date_final[$i];			
			//echo "<br>ST=".$startdate." ,enddate=".$enddate;
			get_station_xml_data($vserial[$i], $vname1[$i], $startdate, $enddate);
		}
	  
		//echo "<br>sizecust1=".sizeof($customer_no)." ,sizegeo1=".sizeof($geo_coord_station);
		
		for($i=0;$i<sizeof($geo_coord_station);$i++)
		{
			$stringData = $stringData.$date1_csv[$i].",".$time1_csv[$i].",".$date2_csv[$i].",".$time2_csv[$i].",".$doctype[$i].",".$plant[$i].",".$route[$i].",".$vname[$i].",".$vendor_name[$i].",".$customer_no[$i].",".$qty[$i].",".$unit[$i];
			
			for($j=0;$j<$datetime_counter[$i];$j++)
			{				
				//if( ($vname[$i] == $vname_halt[$i][$j]) && ($customer_no[$i] == $cust_no_halt[$i][$j]) )
				//{				
					//echo "<br>Customer and Vehicle matched";
					$in_date_arr = explode(' ',$intime_halt[$i][$j]);
					$out_date_arr = explode(' ',$outime_halt[$i][$j]);
					
					//FORMAT DATE TO DD-MM-YY
					$in_date = explode('-',$in_date_arr[0]);              
					$in_date_tmp = $in_date[2]."-".$in_date[1]."-".$in_date[0];
					
					$out_date = explode('-',$out_date_arr[0]);              
					$out_date_tmp = $out_date[2]."-".$out_date[1]."-".$out_date[0];
					//echo "<br>in_date=".$in_date_tmp." ,out_date=".$out_date_tmp;
					/////////////////////////        
					
					$in_dist_tmp = round($in_distance[$i][$j],2);
					$out_dist_tmp = round($out_distance[$i][$j],2);
					
					$stringData = $stringData.",".$in_date_tmp.",".$in_date_arr[1].",".$out_date_tmp.",".$out_date_arr[1].",".$time_dur_halt[$i][$j].",".$in_dist_tmp.",".$out_dist_tmp;
				//}
			}  // FOR DATETIME COUNTER CLOSED   
			$stringData = $stringData."\n";
		}   // FOR GEO COORD SIZE CLOSED
		
		//echo "<br>String Data1=".$stringData."<br>";
	}	// FUNCTION CLOSED


	function get_station_xml_data($vehicle_serial, $vname1, $startdate, $enddate)
	{
		global $date1_csv;
		global $date2_csv;
		global $time1_csv;
		global $time2_csv;
		global $input_date1;
		global $input_date2;
		global $doctype;
		global $plant;
		global $route;
		global $vname;
		global $customer_no;
		global $qty;
		global $unit;

		global $geo_coord_station;
		//echo "<br>size=".sizeof($geo_coord_station)		;
		//global $geo_station;  
		//global $customer_no_db; 
		
		global $intime_halt;
		global $outime_halt;  
		global $in_distance;
		global $out_distance;    
		global $time_dur_halt;

		//global $vname_halt;
		//global $cust_no_halt;	
		
		//echo "<br>IN get_station_xml_data";
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

		$j = 0;
		$total_dist = 0; 									  

		global $entered_flag;  
		global $datetime_counter; 

		global $vname;          //CSV DATA
		global $customer_no;    //CSV DATA

		//global $vname_halt;     //HALT DATA
		//global $cust_no_halt;   //HALT DATA

		global $stringData;  
				
		$outflag=0;          	
       
		for($i=0;$i<=($date_size-1);$i++)
		{  
			$xml_current = "../../../xml_vts/xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";	
					
			if (file_exists($xml_current))      
			{    		
				$xml_file = $xml_current;
				$CurrentFile = 1;
			}		
			else
			{
				$xml_file = "../../../sorted_xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";
				$CurrentFile = 0;
			}
		
			if (file_exists($xml_file)) 
			{			
				$t=time();
				$xml_original_tmp = "../../../xml_tmp/original_xml/tmp_".$vehicle_serial."_".$t."_".$i.".xml";
									      
				if($CurrentFile == 0)
				{
					copy($xml_file,$xml_original_tmp);
				}
				else
				{
					$xml_unsorted = "../../../xml_tmp/unsorted_xml/tmp_".$vehicle_serial."_".$t."_".$i."_unsorted.xml";
				        
					copy($xml_file,$xml_unsorted);        // MAKE UNSORTED TMP FILE
					SortFile($xml_unsorted, $xml_original_tmp);    // SORT FILE
					unlink($xml_unsorted);                // DELETE UNSORTED TMP FILE
				}
      
				$total_lines = count(file($xml_original_tmp));
            
				$xml = @fopen($xml_original_tmp, "r") or $fexist = 0;  
				$logcnt=0;
				$DataComplete=false;                  
				$vehicleserial_tmp=null;
				$format =2;
      
				$datetime = null;
				$hrs_min = null;
				$j=0; 
				$v=0;
				$f = 0;     
            
				if (file_exists($xml_original_tmp)) 
				{                
					while(!feof($xml))          // WHILE LINE != NULL
					{                
						$DataValid = 0;
						$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE			
  				
						if(strlen($line)>20)
						{
						$linetmp =  $line;
						}
  				
						$linetolog =  $logcnt." ".$line;
						$logcnt++;

						if(strpos($line,'fix="1"'))     // RETURN FALSE IF NOT FOUND
						{
						$format = 1;
						$fix_tmp = 1;
						}                
						else if(strpos($line,'fix="0"'))
						{
							$format = 1;
							$fix_tmp = 0;
						}			  				
						if( (preg_match('/lat="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) &&  (preg_match('/lng="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
						{ 
							$lat_value = explode('=',$lat_match[0]);
							$lng_value = explode('=',$lng_match[0]);
							//echo " lat_value=".$lat_value[1];         
							if( (strlen($lat_value[1])>5) && ($lat_value[1]!="-") && (strlen($lng_value[1])>5) && ($lng_value[1]!="-") )
							{
							  $DataValid = 1;
							}
						}          
						if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($DataValid == 1) )   // FIX_TMP =1 COMES IN BOTH CASE     
						{
							//preg_match('/\d+-\d+-\d+ \d+:\d+:\d+/', $line, $str3tmp);    // EXTRACT DATE FROM LINE
							$status = preg_match('/datetime="[^"]+/', $line, $datetime_tmp);
							$datetime_tmp1 = explode("=",$datetime_tmp[0]);
							$datetime = preg_replace('/"/', '', $datetime_tmp1[1]);	
							//echo "<br>str3tmp[0]=".$str3tmp[0];
							$xml_date = $datetime;
						}				          
						if($xml_date!=null)
						{				    					
							if( ($xml_date >= $startdate && $xml_date <= $enddate) && ($xml_date!="-") && ($DataValid==1) )
							{							           	              
								$status = preg_match('/vehicleserial="[^" ]+/', $line, $vehicleserial_tmp);

								if($status==0)
								{
								continue;
								}              
								$status = preg_match('/lat="[^" ]+/', $line, $lat_tmp);
								if($status==0)
								{
								continue;               
								}
								$status = preg_match('/lng="[^" ]+/', $line, $lng_tmp);
								if($status==0)
								{
								continue;
								}                                          
											
								$vehicleserial_tmp1 = explode("=",$vehicleserial_tmp[0]);
								$vserial = preg_replace('/"/', '', $vehicleserial_tmp1[1]);

								$lat_tmp1 = explode("=",$lat_tmp[0]);
								$lat = preg_replace('/"/', '', $lat_tmp1[1]);

								$lng_tmp1 = explode("=",$lng_tmp[0]);
								$lng = preg_replace('/"/', '', $lng_tmp1[1]);    
																	   
								if(sizeof($geo_coord_station)>0)
								{                
									$halt_complete = 0;                
                
									for($g=0;$g<sizeof($geo_coord_station);$g++)
									{
										//echo "<br>vname=".$vname1." ,vname[g]=".$vname[$g];
										if($vname1!=$vname[$g])
										{
											continue;
										}
										if( ($xml_date >= $input_date1[$g]) && ($xml_date <= $input_date2[$g]) )  //** CSV INPUT DATE COMPARISON
										{
											if($geo_coord_station[$g]!="")
											{
											$geo_data = explode(',',$geo_coord_station[$g]);
  											$geo_lat = $geo_data[0];
  											$geo_lng = $geo_data[1];
                    
											check_with_range_landmark($lat, $lng, $geo_lat, $geo_lng, &$status_geo); 
										}                               
                    
											//if($_SERVER["HTTP_X_FORWARDED_FOR"] == "172.26.48.90")
											//echo "<br>status_geo=".$status_geo." entered_flag[".$g."]=".$entered_flag[$g]." ,g=".$g; 
                    
											if( ($status_geo==true) && ($entered_flag[$g] ==0))
											{                                            
												$entered_flag[$g] = 1;
												//echo "<br>HALT OCCURRED: ,entered_flag[".$g."]=".$entered_flag[$g];                        
												$enter_time = $xml_date;
												
												$geo_lat1 = round($geo_lat,4);
												$geo_lng1 = round($geo_lng,4);  
												calculate_distance($lat, $geo_lat1, $lng, $geo_lng1, &$in_dist);                                            
											   
												$intime_halt[$g][$datetime_counter[$g]] = $enter_time;
												$in_distance[$g][$datetime_counter[$g]] = $in_dist;
												
												//STORE HALT VNAME AND CUSTOMER NO
												//$vname_halt[$g][$datetime_counter[$g]] = $vname[$g];
												//$cust_no_halt[$g][$datetime_counter[$g]] = $customer_no[$g];
												//////////////////////////////////                       
											}
                    
											//echo "<br>closed";                  
											if( ($status_geo == false) && ($entered_flag[$g] ==1) )
											{                    
											  //echo "<br>HALT COMPLETED1";
											  $entered_flag[$g] = 0;
											  $leave_time = $xml_date;
											  $outime_halt[$g][$datetime_counter[$g]] = $leave_time;
											  //echo "<br>HALT COMPLETED2:".$outime_halt[$g][$datetime_counter[$g]];
											  
											  $geo_lat1 = round($geo_lat,4);
											  $geo_lng1 = round($geo_lng,4);                       
											  calculate_distance($lat, $geo_lat1, $lng, $geo_lng1, &$out_dist);
											  
											  $out_distance[$g][$datetime_counter[$g]] = $out_dist;
											  
											  $enter_time_tmp = $intime_halt[$g][$datetime_counter[$g]];
											  $leave_time_tmp = $outime_halt[$g][$datetime_counter[$g]];                                       
											  
											  $time = strtotime($leave_time_tmp) - strtotime($enter_time_tmp);                  
											  $hms = secondsToTime($time);
											  $duration = $hms[h].":".$hms[m].":".$hms[s];
											  $time_dur_halt[$g][$datetime_counter[$g]] = $duration; 
											  
											  //STORE HALT VNAME AND CUSTOMER NO
											  //$vname_halt[$g][$datetime_counter[$g]] = $vname[$g];
											  //$cust_no_halt[$g][$datetime_counter[$g]] = $customer_no[$g];
											  //////////////////////////////////
											  
											  //echo "<br>time1=".$enter_time_tmp." ,time2=".$leave_time_tmp." ,duration=".$time_dur_halt[$g][$datetime_counter[$g]];                  
											  $datetime_counter[$g]++;
											} //IF GEO STATUS CLOSED 
										} // IF INPUT CSV DATE COMPARISON               
									}  // GEO COORD LOOP                
								} // if geo_coord!="" closed
								//echo "<br>Astatus=".$status." ,time2=".$time2.", outflag=".$outflag;	                                              	                         																                              										                               
							} // $xml_date_current >= $startdate closed
						}   // if xml_date!null closed
						$j++;
						$f++;
					}   // while closed
				} // if original_tmp closed 
			
				fclose($xml);            
				unlink($xml_original_tmp);
			} // if (file_exists closed
		}  // for closed 
                       
		fclose($fh);		
	}

	function check_with_range_landmark($lat1, $lng1, $lat2, $lng2, &$status_geo)
	{
	  calculate_distance($lat1, $lat2, $lng1, $lng2, &$distance);
	  
	  if($distance < 1)
	  //if($distance < 4)
	  {                                                        
		$status_geo = true; 
	  }  
	  else
	  {
		$status_geo = false;
	  }
	}

	// DOWNLOAD CSV REPORT CODE
	function download_report_csv()
	{
	  global $date_csv;
	  global $doctype;
	  global $plant;
	  global $route;
	  global $vname;
	  global $vendor_name;
	  global $customer_no;
	  global $customer_tmp;
	  global $qty;
	  global $unit;
	  global $download_file;
	  global $stringData;
		
	  //echo $stringData."<br>";
	  
	  if($stringData=="")
	  {						
		print"<center><FONT color=\"Red\" size=2><strong>No Station Halt Record found</strong></font></center>";    
	  }	
	  else
	  {
		//WRITE FILE
		$path = "/var/www/html/vts/beta/src/php/download";
		
		$fullPath = $path."/".$download_file;
		//echo "<br>path=".$fullPath;
		 
		$fh = fopen($fullPath, 'w') or die("can't open file");
		//$stringData = "Bobby Bopper\n";
		fwrite($fh, $stringData);
		fclose($fh);
		//////////////    
		
		//DOWNLOAD FILE 
		
		if ($fd = fopen ($fullPath, "r")) {
			$fsize = filesize($fullPath);
			$path_parts = pathinfo($fullPath);
			$ext = strtolower($path_parts["extension"]);
			//echo "<br>ext=".$ext;
			
			switch ($ext) {
				case "pdf":
				header("Content-type: application/pdf"); // add here more headers for diff. extensions
				header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a download
				break;
				case "csv":
				header("Content-type: application/csv"); // add here more headers for diff. extensions
				header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a download
				break;                             
				default;
				header("Content-type: application/octet-stream");
				header("Content-Disposition: filename=\"".$path_parts["basename"]."\"");
			}
			header("Content-length: $fsize");
			header('Pragma: no-cache');
			//header("Cache-control: private"); //use this to open files directly
			while(!feof($fd)) {
				$buffer = fread($fd, 2048);
				echo $buffer;
			}
		}
		fclose ($fd);
		exit;
		
		//UNLINK FILE
		unlink($fullPath);
		//////////////////////////////////////////////////////////////////////////
	 } // ELSE CLOSED 			
	  //////////////
	}  	
?>
					