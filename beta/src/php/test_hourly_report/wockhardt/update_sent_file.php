<?php
function update_sent_file($read_excel_path)
{
	global $last_vehicle_name;	//READ LAST PROCESSED RECORDS
	global $last_halt_time;
	global $last_time_processed;
					
	global $csv_string_halt_final;
	
	global $Vehicle;			//SENT FILE
	global $SNo;
	global $StationNo;
	global $Type;
	global $RouteNo;
	global $ReportShift;
	global $ArrivalDate;
	global $ArrivalTime;
	global $DepartureDate;
	global $DepartureTime;
	global $ScheduleTime;
	global $Delay;
	global $HaltDuration;
	global $ReportDate1;
	global $ReportTime1;
	global $ReportDate2;
	global $ReportTime2;
	global $TransporterM;
	global $TransporterI;
	global $Plant;
	global $Km;
	
	global $vehicle_name_rdb;		//VEHICLE ROUTE DETAIL
	global $route_name_rdb;
	
	global $relative_customer_input;		//VEHICLE CUSTOMER DETAIL														
	global $relative_plant_input;
	global $relative_transporter_input;
	global $relative_route_input;
	
	
	echo "\nREAD_SENT_FILE";
	//echo "\nPath=".$path;
	//######### SENT FILE NAME CLOSED 			
	$objPHPExcel_1 = null;
	$objPHPExcel_1 = new PHPExcel();
	/*if (file_exists($read_excel_path))
	{		
		$objPHPExcel_1 = new PHPExcel();  //write new file
	}
	else
	{
		$objPHPExcel_1 = PHPExcel_IOFactory::load($read_excel_path);
	}*/	

	$objPHPExcel_1->setActiveSheetIndex(0)->setTitle('Halt Report');

	$objPHPExcel_1->createSheet();
	$objPHPExcel_1->setActiveSheetIndex(1)->setTitle('Route Completed');
	
	$objPHPExcel_1->createSheet();
	$objPHPExcel_1->setActiveSheetIndex(2)->setTitle('Route Pending');	

	$cellIterator = null;
	$column = null;
	$row = null;
	echo "\n1";
	//################ FIRST TAB ############################################
	//#######################################################################
	$row=1;
	
	//###### COLOR STYLES
	$header_font = array(
		'font'  => array(
		'bold'  => true,
		'color' => array('rgb' => '000000'), //RED
		'size'  => 10
		//'name'  => 'Verdana'
	));
	
	//###### FILL THE HEADER
	$col_tmp = 'A'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Vehicle");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'B'.$row;					
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "SNo");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);	
	$col_tmp = 'C'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "StationNo)");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'D'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Type)");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'E'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "RouteNo)");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'F'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "ReportShift)");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'G'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "ArrivalDate)");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'H'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "ArrivalTime)");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'I'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "DepartureDate)");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'J'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "DepartureTime)");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'K'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "ScheduleTime)");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'L'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Delay)");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'M'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "HaltDuration)");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'N'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "ReportDate1)");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'O'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "ReportTime1)");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'P'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "ReportDate2)");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'Q'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "ReportTime2)");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'R'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "TransporterM)");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'S'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "TransporterI)");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'T'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Plant)");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'U'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Km)");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);	
	$row++;
	echo "\n2";
	$data_flag = false;	
	//echo "\nCSV_STRING=".$csv_string_halt_final."\n";	
	$tmpclr=0;
	$prevVehicle="";
	$sheet2_row = explode('#',$csv_string_halt_final);        
	
	$prev_vehicles_arr = array();
	$prev_customers_arr = array();
	$p = 0;
	$EntryCnt=0;
	
	$plant_trip_flag = false;
	
	$vehicle_out_plant = "";
	$plant_out = "";
	$vehicle_out_distance = 0.0;

	$vehicle_in_plant = "";
	$plant_in = "";
	$vehicle_in_distance = 0.0;
	
	$route_no_str = "";
	$transporter_m_str = "";
	$transporter_i_str = "";
	
	echo "\n3:sizeSheet2_row=".sizeof($sheet2_row);
	for($q=0;$q<sizeof($sheet2_row);$q++)
	{
	    $data_flag = false;
		$sheet2_data_main_string="";
		$sheet2_data = explode(',',$sheet2_row[$q]);
		$c = 0;		
		$repeat_vehicle_customer = false;
		
		if($sheet2_data[0]!=$prevVehicle)
		{
			//echo"Test0\n";
			$tmpclr=0;
			$EntryCnt=0;
			$prev_vehicles_arr[$EntryCnt] = $sheet2_data[0];
			$prev_customers_arr[$EntryCnt] = $sheet2_data[2];
			
			//######## OFF THE PLANT DISTANCE FLAG 
			$plant_trip_flag = false;
			
			if( ($vehicle_out_plant == $vehicle_in_plant) && ($vehicle_out_plant!="" && $plant_out!="" && $vehicle_in_plant!="" && $plant_in!=""))
			{
				//### STORE PLANT DISTANCE INFORMATION
				$vehicle_pd[] = $vehicle_out_plant;
				$plant_out_pd[] = $plant_out;
				$plant_out_time_pd[] = $plant_out_time;
				$plant_in_pd[] = $plant_in;
				$plant_in_time_pd[] = $plant_in_time;
				$distance_pd[] = ($vehicle_in_distance - $vehicle_out_distance);
				$route_pd[] = $route_no_str;
				$transporter_m_pd[] = $transporter_m_str;
				$transporter_i_pd[] = $transporter_i_str;
				
				//##RESET VARIABLES
				$vehicle_out_plant = "";
				$plant_out = "";
				$plant_out_time = "";
				$vehicle_out_distance = 0.0;

				$vehicle_in_plant = "";
				$plant_in = "";
				$plant_in_time = "";
				$vehicle_in_distance = 0.0;
				$route_no_str ="";
				$transporter_m_str = "";
				$transporter_i_str = "";
			}
			$route_no_str = "";

			//####################################
		}
		else
		{
			$prev_vehicles_arr[$EntryCnt] = $sheet2_data[0];
			$prev_customers_arr[$EntryCnt] = $sheet2_data[2];						
		}
		
		//echo "\nEntryCount=".$EntryCnt;		
		if($EntryCnt>0)
		{
			for($k=0;$k<$EntryCnt;$k++)
			{
				if( ($sheet2_data[0] == $prev_vehicles_arr[$k]) && ($sheet2_data[2] == $prev_customers_arr[$k]) )
				{
					$repeat_vehicle_customer = true;
					break;
				}
			}
		}
		$EntryCnt++;
				
		$prevVehicle = $sheet2_data[0];
		$prevCustomer = $sheet2_data[2];
		
		//echo "\nq=".$q." ,repeat_vehicle_customer=".$repeat_vehicle_customer." ,tmpclr0=".$tmpclr."\n";
						
		//######## CODE FOR PLANT DISTANCE				
		if( (trim($sheet2_data[3]) == "Plant") && (!$plant_trip_flag) )
		{
				$vehicle_out_plant = $sheet2_data[0];
				$plant_out = $sheet2_data[2];
				$plant_out_time = $sheet2_data[7];	//DEPARTURE TIME
				$vehicle_out_distance = $sheet2_data[18];
				$plant_trip_flag = true;
		}
		
		if( (trim($sheet2_data[3]) == "Customer") && ($plant_trip_flag) )
		{
			$route_no_str = $route_no_str.$sheet2_data[4]."/";
			$transporter_m_str = $transporter_m_str.$sheet2_data[15]."/";
			$transporter_i_str = $transporter_i_str.$sheet2_data[16]."/";
		}
		
		if( (trim($sheet2_data[3]) == "Plant") && ($plant_trip_flag) )
		{
			if($vehicle_out_plant == $sheet2_data[0])
			{
				$vehicle_in_plant = $sheet2_data[0];
				$plant_in = $sheet2_data[2];
				$plant_in_time = $sheet2_data[6];	//ARRIVAL TIME
				$vehicle_in_distance = $sheet2_data[18];				
			}
		}
		//################################		
		
		if($tmpclr==1)
		{		
			//echo"RED\n";	-0x0A
			$excel_date_format = array(	'fill'=> array('type'=> PHPExcel_Style_Fill::FILL_SOLID,'color'=> array('argb' => 'FF0000')),'borders'=> array('bottom'=> array('style'=> PHPExcel_Style_Border::BORDER_THIN),'right'=> array('style'=> PHPExcel_Style_Border::BORDER_MEDIUM)));
			$excel_time_format = array(	'fill'=> array('type'=> PHPExcel_Style_Fill::FILL_SOLID,'color'=> array('argb' => 'FF0000')),'borders'=> array('bottom'=> array('style'=> PHPExcel_Style_Border::BORDER_THIN),'right'=> array('style'=> PHPExcel_Style_Border::BORDER_MEDIUM)));
			$excel_normal_format = array('fill'=> array('type'=> PHPExcel_Style_Fill::FILL_SOLID,'color'=> array('argb' => 'FF0000')),'borders'=> array('bottom'=> array('style'=> PHPExcel_Style_Border::BORDER_THIN),'right'=> array('style'=> PHPExcel_Style_Border::BORDER_MEDIUM)));
			$color = "red";
		}
		else if ( ($sheet2_data[15]!=$sheet2_data[16]) && (trim($sheet2_data[3])!="Plant") )				//TRANSOPORTER NOT MATCHED(15 AND 16-LIGHT BLUE)
		{								
			//echo"BLUE\n";
			$transporter_arr = explode("/",$sheet2_data[16]);
			$transporter_arr1 = explode("/",$sheet2_data[15]);
			$transporterMatch=false;
			for($i=0;$i<sizeof($transporter_arr);$i++)
			{
				for($j=0;$j<sizeof($transporter_arr1);$j++)
				{
					//echo "T1=".$transporter_arr1[$j].",T2=".$transporter_arr[$i]."\n";
					if($transporter_arr1[$j]==$transporter_arr[$i])
					{
						//echo "Matched\n";
						$transporterMatch=true;
						break;
					}
				}
			}
			
			if($transporterMatch==false)
			{
				//BLUE	- 0x2C
				$excel_date_format = array(	'fill'=> array('type'=> PHPExcel_Style_Fill::FILL_SOLID,'color'=> array('argb' => '99CCFF')),'borders'=> array('bottom'=> array('style'=> PHPExcel_Style_Border::BORDER_THIN),'right'=> array('style'=> PHPExcel_Style_Border::BORDER_MEDIUM)));
				$excel_time_format = array(	'fill'=> array('type'=> PHPExcel_Style_Fill::FILL_SOLID,'color'=> array('argb' => '99CCFF')),'borders'=> array('bottom'=> array('style'=> PHPExcel_Style_Border::BORDER_THIN),'right'=> array('style'=> PHPExcel_Style_Border::BORDER_MEDIUM)));
				$excel_normal_format = array('fill'=> array('type'=> PHPExcel_Style_Fill::FILL_SOLID,'color'=> array('argb' => '99CCFF')),'borders'=> array('bottom'=> array('style'=> PHPExcel_Style_Border::BORDER_THIN),'right'=> array('style'=> PHPExcel_Style_Border::BORDER_MEDIUM)));
				$color = "blue";				
			}
			else if($repeat_vehicle_customer)
			{
				//echo"YELLOW\n";	-0x2B
				$excel_date_format = array(	'fill'=> array('type'=> PHPExcel_Style_Fill::FILL_SOLID,'color'=> array('argb' => 'FFFF99')),'borders'=> array('bottom'=> array('style'=> PHPExcel_Style_Border::BORDER_THIN),'right'=> array('style'=> PHPExcel_Style_Border::BORDER_MEDIUM)));
				$excel_time_format = array(	'fill'=> array('type'=> PHPExcel_Style_Fill::FILL_SOLID,'color'=> array('argb' => 'FFFF99')),'borders'=> array('bottom'=> array('style'=> PHPExcel_Style_Border::BORDER_THIN),'right'=> array('style'=> PHPExcel_Style_Border::BORDER_MEDIUM)));
				$excel_normal_format = array('fill'=> array('type'=> PHPExcel_Style_Fill::FILL_SOLID,'color'=> array('argb' => 'FFFF99')),'borders'=> array('bottom'=> array('style'=> PHPExcel_Style_Border::BORDER_THIN),'right'=> array('style'=> PHPExcel_Style_Border::BORDER_MEDIUM)));				
				$color = "yellow";				
			}
			else
			{	//PINK -0x2D
				$excel_date_format = array(	'fill'=> array('type'=> PHPExcel_Style_Fill::FILL_SOLID,'color'=> array('argb' => 'FF99CC')),'borders'=> array('bottom'=> array('style'=> PHPExcel_Style_Border::BORDER_THIN),'right'=> array('style'=> PHPExcel_Style_Border::BORDER_MEDIUM)));
				$excel_time_format = array(	'fill'=> array('type'=> PHPExcel_Style_Fill::FILL_SOLID,'color'=> array('argb' => 'FF99CC')),'borders'=> array('bottom'=> array('style'=> PHPExcel_Style_Border::BORDER_THIN),'right'=> array('style'=> PHPExcel_Style_Border::BORDER_MEDIUM)));
				$excel_normal_format = array('fill'=> array('type'=> PHPExcel_Style_Fill::FILL_SOLID,'color'=> array('argb' => 'FF99CC')),'borders'=> array('bottom'=> array('style'=> PHPExcel_Style_Border::BORDER_THIN),'right'=> array('style'=> PHPExcel_Style_Border::BORDER_MEDIUM)));
				$color = "pink";
			}			
		}		
		else if( ($repeat_vehicle_customer))
		{
			//echo"YELLOW\n";	-0x2B
			$excel_date_format = array(	'fill'=> array('type'=> PHPExcel_Style_Fill::FILL_SOLID,'color'=> array('argb' => 'FFFF99')),'borders'=> array('bottom'=> array('style'=> PHPExcel_Style_Border::BORDER_THIN),'right'=> array('style'=> PHPExcel_Style_Border::BORDER_MEDIUM)));
			$excel_time_format = array(	'fill'=> array('type'=> PHPExcel_Style_Fill::FILL_SOLID,'color'=> array('argb' => 'FFFF99')),'borders'=> array('bottom'=> array('style'=> PHPExcel_Style_Border::BORDER_THIN),'right'=> array('style'=> PHPExcel_Style_Border::BORDER_MEDIUM)));
			$excel_normal_format = array('fill'=> array('type'=> PHPExcel_Style_Fill::FILL_SOLID,'color'=> array('argb' => 'FFFF99')),'borders'=> array('bottom'=> array('style'=> PHPExcel_Style_Border::BORDER_THIN),'right'=> array('style'=> PHPExcel_Style_Border::BORDER_MEDIUM)));
			$color = "yellow";
		}		
		else
		{
			//echo"NORMAL\n"; WHITE -0x09
			$excel_date_format = array(	'fill'=> array('type'=> PHPExcel_Style_Fill::FILL_SOLID,'color'=> array('argb' => 'FFFFFF')),'borders'=> array('bottom'=> array('style'=> PHPExcel_Style_Border::BORDER_THIN),'right'=> array('style'=> PHPExcel_Style_Border::BORDER_MEDIUM)));
			$excel_time_format = array(	'fill'=> array('type'=> PHPExcel_Style_Fill::FILL_SOLID,'color'=> array('argb' => 'FFFFFF')),'borders'=> array('bottom'=> array('style'=> PHPExcel_Style_Border::BORDER_THIN),'right'=> array('style'=> PHPExcel_Style_Border::BORDER_MEDIUM)));
			$excel_normal_format = array('fill'=> array('type'=> PHPExcel_Style_Fill::FILL_SOLID,'color'=> array('argb' => 'FFFFFF')),'borders'=> array('bottom'=> array('style'=> PHPExcel_Style_Border::BORDER_THIN),'right'=> array('style'=> PHPExcel_Style_Border::BORDER_MEDIUM)));
			$color = "white";
		}
		//echo "\nSHEET_COUNT=".sizeof($sheet2_data);
		
		$valid_row_entry = true;
		$departure_flag = false;
		
		echo "\nSizeDeparture=".sizeof($DepartureDate);
		$vname_current = $sheet2_data[0];
		$station_current = $sheet2_data[2];		
		
		for($x=1;$x<sizeof($DepartureDate);$x++)		//IF ARRIVAL DATE TIME EXISTS, DONT UPDATE
		{
			if( ($Vehicle[$x]==$vname_current) && ($StationNo[$x]==$station_current) )
			{
				if($DepartureDate[$x] != "")
				{
					$departure_flag = true;
					$departure_row = $x;
				}
				break;
			}
		}					
						
		echo "\nSheet2Data=".sizeof($sheet2_data)." ,DepartureRow=".$departure_row." ,departure_flag=".$departure_flag;
		for($m=0;$m<sizeof($sheet2_data);$m++)
		{           
			echo "\nSheet2DataM=".$sheet2_data[$m];
			if( (!$departure_flag) && ($sheet2_data[$m]!="-") && ($sheet2_data[$m]!="") && ($sheet2_data[$m]!=" ") && ($sheet2_data[$m]!=null) )
			{					
				echo "\nValidRecord";
				if($m==6 || $m==7)	//ARRIVAL, DEPARTURE DATE AND TIME : GHIJ
				{
					$valid_col_entry = true;
					
					if($m==6) 
					{
						$cell_1 = 'G'.$row;
						$cell_2 = 'H'.$row;
						
						for($x=1;$x<sizeof($ArrivalDate);$x++)		//IF ARRIVAL DATE TIME EXISTS, DONT UPDATE
						{
							if( ($Vehicle[$x]==$vname_current) && ($StationNo[$x]==$station_current) )
							{
								if($ArrivalDate[$x] != "")
								{
									$valid_col_entry = false;
								}
								break;
							}
						}
					}
					else if($m==7)
					{
						$cell_1 = 'I'.$row;
						$cell_2 = 'J'.$row;

						for($x=1;$x<sizeof($DepartureDate);$x++)		//IF ARRIVAL DATE TIME EXISTS, DONT UPDATE
						{
							if( ($Vehicle[$x]==$vname_current) && ($StationNo[$x]==$station_current) )
							{
								if($DepartureDate[$x] != "")
								{
									$valid_col_entry = false;
									$valid_row_entry = false;
									$departure_flag = true;
								}
								break;
							}
						}						
					}					
					$datetime_tmp = explode(" ",$sheet2_data[$m]);									
					
					//echo "\nDATETIME=".$datetime_tmp[0];										
					if( ($valid_row_entry && $valid_col_entry) && ($datetime_tmp[0]!="-" && $datetime_tmp[1]!="-" && $datetime_tmp[0]!="" && $datetime_tmp[1]!=""))
					{
						echo "\nDateTimeWrite";
						//$date_obj1 = strtotime($datetime_tmp[0]);
						//$date_tmp = date('d/m/Y',$date_obj);
						//$excel_date1 = intval( ($date_obj1+86400) / 86400 + 25569);
						//$worksheet2->write($r,$c, $excel_date1, $excel_date_format);																						
						//$c++;
						$xlsx_date = PHPExcel_Style_NumberFormat::toFormattedString($datetime_tmp[0], 'YYYY-mm-dd');
						$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($cell_1 , $xlsx_date);
						$objPHPExcel_1->getActiveSheet(0)->getStyle($cell_1)->applyFromArray($excel_date_format); 						
						
						//$worksheet2->write($r,$c, $datetime_tmp[1]);												
						$tmp_date = "1970-01-01";
						$tmp_date = $tmp_date." ".$datetime_tmp[1];
						$time_obj1 = strtotime($tmp_date);
						$time_obj1 = $time_obj1 + 19800;
						//$date_tmp = date('d/m/Y',$date_obj);
						//$excel_time1= $time_obj1 / 86400 + 25569;
						//$worksheet2->write($r,$c, $excel_time1, $excel_time_format);										
						//$c++;
						$xlsx_time = PHPExcel_Style_NumberFormat::toFormattedString($datetime_tmp[1], 'hh:mm:ss');
						$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($cell_2 , $xlsx_time); 
						$objPHPExcel_1->getActiveSheet(0)->getStyle($cell_2)->applyFromArray($excel_time_format);
						
						$tmp_date1 = "1970-01-01 02:30:00";
						if(($m==7) && ($current_date == $datetime_tmp[0]) && (strtotime($tmp_date)>strtotime($tmp_date1)) && ($tmpclr==0) && ($sheet2_data[3]=="Plant"))
						{
							//echo"Test3\n";
							$tmpclr=1;
						}
					}
				}
				else if($m==8)	//SCHEDULE TIME
				{					
					echo "\nScheduleWrite";
					/*$tmp_date = "1970-01-01";
					$tmp_date = $tmp_date." ".$sheet2_data[$m].":00";
					//echo "\nScheduleTime";
					$time_obj2 = strtotime($tmp_date);
					$time_obj2 = $time_obj2 + 19800;	//ADD 5:30
					//$date_tmp = date('d/m/Y',$date_obj);
					$excel_time2= $time_obj2 / 86400 + 25569;*/
					//$worksheet2->write($r,$c, $excel_time2, $excel_time_format);										
					//$c++;	
					$cell = 'K'.$row;
					$xlsx_schedule_time = PHPExcel_Style_NumberFormat::toFormattedString($sheet2_data[$m], 'hh:mm:ss');
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($cell , $xlsx_schedule_time); 
					$objPHPExcel_1->getActiveSheet(0)->getStyle($cell)->applyFromArray($excel_time_format);					
				}
				else if($m==9)	//DELAY TIME
				{
					echo "\nDelayWrite";
					$tmp_date = "1970-01-01";
					
					$pos = false;
					//echo "\nDELAY::sheet2_data[m]=".$sheet2_data[$m];
					$pos = strpos($sheet2_data[$m], "-");
					//echo "\nPOS=".$pos;
					
					if($pos !== false)
					{
						//echo "\nNegative Found";
						$sheet2_data[$m] = str_replace("-", "", $sheet2_data[$m]);
					}					
					$tmp_date = $tmp_date." ".$sheet2_data[$m];
					//echo "\nTMP_DATE9=".$tmp_date;
					$time_obj3 = strtotime($tmp_date);					
					$time_obj3 = $time_obj3 + 19800;	//ADD 5:30
					//IN MINUTES
					//$date_tmp = date('d/m/Y',$date_obj);
					if($pos !== false)
					{
						//echo "\nAdded Negative";
						//$excel_time3= "-".($time_obj3 / 86400 + 25569);
						//$excel_time3= "-".($time_obj3 / 86400 + 25569);
						$delay_mins = intval(-$time_obj3 / 60);
					}
					else
					{
						//echo "\nNo Negative Found";
						//$excel_time3= $time_obj3 / 86400 + 25569;
						$delay_mins = intval($time_obj3 / 60);
					}
									
					//$worksheet2->write($r,$c, $delay_mins, $excel_normal_format);										
					//$c++;
					$cell = 'L'.$row;					
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($cell , $delay_mins); 
					$objPHPExcel_1->getActiveSheet(0)->getStyle($cell)->applyFromArray($excel_normal_format);					
				}
				else if($m==10)	//HALT DURATION
				{
					echo "\nHaltDurationWrite";
					/*$tmp_date = "1970-01-01";
					$tmp_date = $tmp_date." ".$sheet2_data[$m];
					//echo "\nHalt Duration10=".$tmp_date;
					$time_obj4 = strtotime($tmp_date);
					$time_obj4 = $time_obj4 + 19800;	//ADD 5:30
					//$date_tmp = date('d/m/Y',$date_obj);
					$excel_time4= $time_obj4 / 86400 + 25569;*/
					//$worksheet2->write($r,$c, $excel_time4, $excel_time_format);										
					//$c++;
					if($departure_flag)
					{
						$cell = 'M'.$row;
						$xlsx_halt_duration = PHPExcel_Style_NumberFormat::toFormattedString($sheet2_data[$m], 'hh:mm:ss');
						$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($cell , $xlsx_halt_duration); 
						$objPHPExcel_1->getActiveSheet(0)->getStyle($cell)->applyFromArray($excel_time_format);										
					}
				}
				else if($m==12 || $m==14)	//REPORT TIME
				{																								
					echo "\nReportTimeWrite";
					/*$tmp_date = "1970-01-01";
					$tmp_date = $tmp_date." ".$sheet2_data[$m];
					//echo "\nReportTime12=".$tmp_date;
					$time_obj3 = strtotime($tmp_date);
					$time_obj3 = $time_obj3 + 19800;
					//$date_tmp = date('d/m/Y',$date_obj);
					$excel_time3= $time_obj3 / 86400 + 25569;
					$worksheet2->write($r,$c, $excel_time3, $excel_time_format);										
					$c++;*/
					
					if($m==12)	$cell = 'O'.$row;
					else if($m==14) $cell = 'Q'.$row;					
					$xlsx_time2 = PHPExcel_Style_NumberFormat::toFormattedString($sheet2_data[$m], 'hh:mm:ss');
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($cell , $xlsx_time2); 
					$objPHPExcel_1->getActiveSheet(0)->getStyle($cell)->applyFromArray($excel_time_format);															
				}				
				else if($m==11 || $m==13)	//REPORT DATE
				{																			
					echo "\nReportDateWrite";
					/*$date_obj2 = strtotime($sheet2_data[$m]);
					//$date_tmp = date('d/m/Y',$date_obj);
					$excel_date2 = intval( ($date_obj2+86400) / 86400 + 25569);
					$worksheet2->write($r,$c, $excel_date2, $excel_date_format);					
					$c++;*/	

					if($m==11)	$cell = 'N'.$row;
					else if($m==13) $cell = 'P'.$row;					
					$xlsx_date2 = PHPExcel_Style_NumberFormat::toFormattedString($sheet2_data[$m], 'YYYY-mm-dd');
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($cell , $xlsx_date2); 
					$objPHPExcel_1->getActiveSheet(0)->getStyle($cell)->applyFromArray($excel_date_format);					
				}
				/*else if($m==7 || $m==9 || $m ==10 || $m ==11 || $m==12 || $m ==14 $m ==16)
				{
					$time_obj = strtotime($sheet2_data[$m]);
					//$date_tmp = date('d/m/Y',$date_obj);
					$excel_no3 = intval($time_obj / 86400 + 25569);
					$worksheet2->write($r,$c, $excel_no3, $excel_time_format);					
					$c++;										
				}*/				
				else
				{
					if($m==0) $col = 'A'.$row;
					else if($m==1) $cell = 'B'.$row;
					else if($m==2) $cell = 'C'.$row;
					else if($m==3) $cell = 'D'.$row;
					else if($m==4) $cell = 'E'.$row;
					else if($m==5) $cell = 'F'.$row;
					//else if($m==6) $cell = 'G'.$row;
					//else if($m==7) $cell = 'H'.$row;
					//else if($m==8) $cell = 'I'.$row;
					//else if($m==9) $cell = 'J'.$row;
					//else if($m==10) $cell = 'K'.$row;
					//else if($m==11) $cell = 'L'.$row;
					//else if($m==12) $cell = 'M'.$row;
					//else if($m==13) $cell = 'N'.$row;
					//else if($m==14) $cell = 'O'.$row;
					else if($m==15) $cell = 'P'.$row;
					else if($m==16) $cell = 'Q'.$row;
					else if($m==17) $cell = 'R'.$row;
					else if($m==18) $cell = 'S'.$row;
					else if($m==19) $cell = 'T'.$row;
					else if($m==20) $cell = 'U'.$row;
										
					$objPHPExcel_1->getActiveSheet(0)->getCell($cell)->setValue($sheet2_data[$m]);
					$objPHPExcel_1->getActiveSheet(0)->getStyle($cell)->applyFromArray($excel_normal_format);
					//$worksheet2->write($r,$c, $sheet2_data[$m],$excel_normal_format);					
					//$c++;
				}
			}
			/*else
			{
				//echo "::ELSE BLANK\n";
				if($m==6 || $m==7)
				{					
					$worksheet2->write($r,$c, "-",$excel_normal_format);
					$c++;
					$worksheet2->write($r,$c, "-",$excel_normal_format);
					$c++;					
				}
				else
				{
					$worksheet2->write($r,$c, "-",$excel_normal_format);
					$c++;	
				}										
			}*/
			//$sheet2_data_main_string=$sheet2_data_main_string.$sheet2_data[$m].",";
			$data_flag = true;     
		}
		//echo "sheet2_data_main_string=".$sheet2_data_main_string."<br>";
		if($data_flag)
		{
			echo "\nDataFlagTrue";
			if($departure_flag)
			{
				$vehicle_arr[$q] = $Vehicle[$departure_row];	    //vehicle no
				$customer_arr[$q] = $StationNo[$departure_row];	//customer no
				$type_arr[$q] = $Type[$departure_row];
				$route_arr[$q] = $RouteNo[$departure_row];				
				//$record_arr[$q] = $sheet2_row[$q];			
				//$color_arr[$q] = $color;				
			}
			else
			{
				$vehicle_arr[$q] = $sheet2_data[0];	    //vehicle no
				$customer_arr[$q] = $sheet2_data[2];	//customer no
				$type_arr[$q] = $sheet2_data[3];
				$route_arr[$q] = $sheet2_data[4];
				//$record_arr[$q] = $sheet2_row[$q];			
				//$color_arr[$q] = $color;				
			}
			$row++;
		}
		echo "\nRow=".$row;
	}	
	//#### FIRST TAB CLOSED ###################################################################
	
	global $vehicle_name_rdb;				//##### VEHICLE ROUTE -DB DETAIL
	global $route_name_rdb;
	
	global $relative_customer_input;		//##### VEHICLE CUSTOMER -MASTER DETAIL
	global $relative_route_input;
	
	//####################### SECOND TAB ######################################################
	//if($row > $sheet2_row_count)
	echo "\nSecond tab";
	$row =1;
	//###### HEADER
	$col_tmp = 'A'.$row;
	$objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp , "Vehicle");
	$objPHPExcel_1->getActiveSheet(1)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'B'.$row;					
	$objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp , "Route"); 					
	$objPHPExcel_1->getActiveSheet(1)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'C'.$row;
	$objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp , "CustomerCompleted(All)");
	$objPHPExcel_1->getActiveSheet(1)->getStyle($col_tmp)->applyFromArray($header_font);
	$row++;
	
	//######## LOGIC -IF ALL THE CUSTOMERS COMPLETED IN THE ROUTE	
	//$vehicle_arr[$q]     					//##### XLSX -REPORT DETAIL
	//$customer_arr[$q]	
	//$type_arr[$q]
	//$route_arr[$q]
	
	for($j=0;$j<sizeof($vehicle_name_rdb);$j++)		//###### LOOP THROUGH ALL ROUTES
	{
		$all_completed = false;
		$valid_match = false;
		$customer_str = "";
		$vehicle_name_rdb1 = $vehicle_name_rdb[$j];
		$route_name_rdb1 = $route_name_rdb[$j];
		
		//##### FIND ALL CUSTOMERS IN THIS ROUTE	
		for($k=0;$k<sizeof($relative_route_input);$k++)
		{
			$route_main = $relative_route_input;
			$search_str   = $route_name_rdb1;
			$pos = strpos($route_main, $search_str);

			if ($pos === false) 
			{
				//NOT FOUND
			} 
			else 
			{								
				$customer_str = $customer_str.$relative_customer_input[$k].",";		//TOTAL CUSTOMERS IN THE ROUTE
			}		
		}
		if($customer_str!="") { $customer_str = substr($customer_str, 0, -1);}
		
		for($k=0;$k<sizeof($vehicle_arr);$k++)				//#### REPORT DATA
		{
			if($vehicle_arr[$k] == $vehicle_name_rdb[$j])
			{
				$cust_tmp = explode(',',$customer_str);
				for($m=0;$m<sizeof($cust_tmp);$m++)
				{
					if($customer_arr[$k] != $cust_tmp)
					{
						$all_completed = false;
					}
					$valid_match = true;
				}								
			}
		}
		
		if($valid_match && $all_completed)
		{
			echo "\nValidSheet2";
			//######### FILL SHEET2
			$col_tmp = 'A'.$row;
			$objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp , $vehicle_name_rdb1);
			$col_tmp = 'B'.$row;					
			$objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp , $route_name_rdb1); 					
			$col_tmp = 'C'.$row;
			$objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp , $customer_str);
			$row++;			
		}
	}							
	//#### SECOND TAB CLOSED ##################################################################
	
	//############################### THIRD TAB ###############################################
	echo "\nThird tab";
	$row =1;
	//####### DEFINE HEADER
	$col_tmp = 'A'.$row;
	$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , "Vehicle");
	$objPHPExcel_1->getActiveSheet(2)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'B'.$row;					
	$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , "Route"); 			
	$objPHPExcel_1->getActiveSheet(2)->getStyle($col_tmp)->applyFromArray($header_font);	
	$col_tmp = 'C'.$row;
	$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , "Customer Completed");
	$objPHPExcel_1->getActiveSheet(2)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'D'.$row;
	$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , "Customer Incompleted");					
	$objPHPExcel_1->getActiveSheet(2)->getStyle($col_tmp)->applyFromArray($header_font);
	$row++;
		
	//######## LOGIC -ALL THE CUSTOMERS COMPLETED / INCOMPLETED IN THE ROUTE	
	//$vehicle_arr[$q]     					//##### XLSX -REPORT DETAIL
	//$customer_arr[$q]	
	//$type_arr[$q]
	//$route_arr[$q]
	
	for($j=0;$j<sizeof($vehicle_name_rdb);$j++)		//###### LOOP THROUGH ALL ROUTES
	{
		$all_completed = false;
		$valid_match = false;
		$customer_str = "";
		$vehicle_name_rdb1 = $vehicle_name_rdb[$j];
		$route_name_rdb1 = $route_name_rdb[$j];
		
		//##### FIND ALL CUSTOMERS IN THIS ROUTE	
		for($k=0;$k<sizeof($relative_route_input);$k++)
		{
			$route_main = $relative_route_input;
			$search_str   = $route_name_rdb1;
			$pos = strpos($route_main, $search_str);

			if ($pos === false) 
			{
				//NOT FOUND
			} 
			else 
			{								
				$customer_str = $customer_str.$relative_customer_input[$k].",";		//TOTAL CUSTOMERS IN THE ROUTE
			}		
		}
		if($customer_str!="") { $customer_str = substr($customer_str, 0, -1);}
		
		$customer_completed = "";
		$customer_incompleted = "";
		
		for($k=0;$k<sizeof($vehicle_arr);$k++)				//#### REPORT DATA
		{
			if($vehicle_arr[$k] == $vehicle_name_rdb[$j])
			{
				$cust_tmp = explode(',',$customer_str);
				for($m=0;$m<sizeof($cust_tmp);$m++)
				{
					if($customer_arr[$k] != $cust_tmp)
					{						
						$customer_completed = $customer_completed.$customer_arr[$k].",";
					}
					else
					{
						$customer_incompleted = $customer_incompleted.$customer_arr[$k].",";
					}
					$valid_match = true;
				}								
			}
		}
		
		if($valid_match)
		{
			echo "\nValidSheet3";
			//######### FILL SHEET3
			if($customer_completed!="") { $customer_completed = substr($customer_completed, 0, -1);}
			if($customer_incompleted!="") { $customer_incompleted = substr($customer_incompleted, 0, -1);}
			
			$col_tmp = 'A'.$row;
			$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , $vehicle_name_rdb1);
			$col_tmp = 'B'.$row;					
			$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , $route_name_rdb1); 					
			$col_tmp = 'C'.$row;
			$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , $customer_completed);
			$col_tmp = 'D'.$row;
			$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , $customer_incompleted);					
			$row++;			
		}
	}			
		
	//#### THIRD TAB CLOSED ########################################
	
	//#### WRITE FINAL XLSX
	echo date('H:i:s') , " Write to Excel2007 format" , EOL;
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel_1, 'Excel2007');
	$objWriter->save($read_excel_path);
	echo date('H:i:s') , " File written to " , $read_excel_path , EOL;
		
}

?>