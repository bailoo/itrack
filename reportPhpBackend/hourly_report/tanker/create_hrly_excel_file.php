<?php
function create_hrly_excel($read_excel_path, $shift, $route_type_param, $startdate, $shift_type)
{
	$DEBUG_HRLY = false;
	echo "\nInCreateHrly";
	global $customer_sel;
	global $plant_sel;
	global $transporter_sel;
	global $expected_time_sel;
	global $expected_time_plant_out_sel;
	
	global $station_id;
	global $type;
	global $station_coord;
	global $distance_variable;	
	global $vehicle_name_rdb;				//##### VEHICLE ROUTE -DB DETAIL
	global $vehicle_imei_rdb;
	global $route_name_rdb;
	global $remark_rdb;
	global $all_routes;
	global $all_customers;
	global $objPHPExcel_1;
	global $relative_customer_input;		//##### VEHICLE CUSTOMER -MASTER DETAIL
	global $relative_route_input;
	
	global $transporter_m;
	global $vehicle_m;	
	
	global $route_type_rdb;
	
	global $plant_station_coord;
	global $plant_distance_variable;
	
	//######### SENT FILE NAME CLOSED 			
	$objPHPExcel_1 = null;
	$objPHPExcel_1 = new PHPExcel();
	//echo "\nobjPHPExcel_1=".$objPHPExcel_1;
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
	//echo "\n1";
	//################ FIRST TAB ############################################
	//#######################################################################
	$row=1;
	
	//###### COLOR STYLES
	/*$header_font = array(
		'font'  => array(
		'bold'  => true,
		'color' => array('rgb' => '000000'), //RED
		'size'  => 10
		//'name'  => 'Verdana'
	));*/
	$header_font = array(
	'fill'  => array(
			'type'          => PHPExcel_Style_Fill::FILL_SOLID,
			'color'         => array('argb' => 'd3d3d3')            //grey
			//'text' => array('argb' => 'FFFC64')
	),
	'borders' => array(
			'bottom'        => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			'right'         => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
	)
	);	
	$styleFontRed = array(
	'font'  => array(
		'bold'  => true,
		'color' => array('rgb' => 'FF0000'), //RED
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
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "StationNo");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'D'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Type");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'E'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "RouteNo");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'F'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "ReportShift");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'G'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Plant");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);	
	$col_tmp = 'H'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "HourBand");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);	
	$col_tmp = 'I'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "ArrivalDate");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'J'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "ArrivalTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'K'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "DepartureDate");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'L'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "DepartureTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'M'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "ScheduledDate");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'N'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "ScheduleTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'O'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Delay (Mins)");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'P'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "HaltDuration");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'Q'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Remark");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);	
	$col_tmp = 'R'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "PLANT-INDATE");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'S'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "PLANT-INTIME");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'T'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "PLANT-OUTDATE");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'U'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "PLANT-OUTTIME");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'V'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "PLANT-OUT-SCHEDULE DATE");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'W'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "PLANT-OUT-SCHEDULE TIME");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);	
	$col_tmp = 'X'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "PLANT-OUT DELAY");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'Y'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Transporter(M)");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'Z'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Transporter(I)");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);	
	$col_tmp = 'AA'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "RouteType");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);	
	$col_tmp = 'AB'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "NO GPS");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);	
	$col_tmp = 'AC'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "LatitudeC");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'AD'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "LongitudeC");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'AE'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "DistVarC");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'AF'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "IMEI");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'AG'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "PLANT-COORD");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'AH'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "PLANT-DISTVAR");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'AI'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "PLANT-STATUS");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'AJ'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "ReportDate1");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'AK'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "ReportTime1");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'AL'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "ReportDate2");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'AM'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "ReportTime2");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'AN'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "VisitStatus");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	

	$row++;
	echo "\nSizeRoute=".sizeof($route_name_rdb);
	
	if($shift=="ZBVE")
	{
		if($shift_type=="focal")
		{
			$date_tmp = explode(' ',$startdate);
			//$cdate = date($date_tmp[0]);
			$nextdate = date($date_tmp[0]);
			//$nextdate = date('Y-m-d', strtotime($cdate .' +1 day'));
			$cdate = date('Y-m-d', strtotime($nextdate .' -1 day'));
		}
		else
		{
			$date_tmp = explode(' ',$startdate);
			$cdate = date($date_tmp[0]);
			$nextdate = date('Y-m-d', strtotime($cdate .' +1 day'));
		}
	}
	else
	{
		$date_tmp = explode(' ',$startdate);
		$cdate = date($date_tmp[0]);	
	}
	//echo "\nCdate=".$cdate." ,nextdate=".$nextdate;

	for($i=0;$i<sizeof($route_name_rdb);$i++)
	{
		$sno=1;
		//if((strpos($route_type_rdb[$i],$route_type_param) !== false) || ($route_type_param=="ALL"))
		$route_name_rdb_1 = explode('/',$route_name_rdb[$i]);

		$size_rdb1 = 0;
		$size_rdb1 = sizeof($route_name_rdb_1);
		//echo "<br>size_rdb1=".sizeof($route_name_rdb_1);
		/*if($size_rdb1>1)
		{
			echo "<br>RouteNameRDBSize2=".sizeof($route_name_rdb_1);
		}*/		
		for($k=0;$k<sizeof($route_name_rdb_1);$k++)
		{
			$route_name_rdb_3="";
			if(strpos($route_name_rdb_1[$k],'@') !== false)
			{
				$route_name_rdb_2 = explode('@',$route_name_rdb_1[$k]);
				$route_name_rdb_3 = $route_name_rdb_2[1];
			}
			else
			{
				$route_name_rdb_3 = $route_name_rdb_1[$k];
			}			
			
			//echo "<br>route_name_rdb_3=".$route_name_rdb_3;
			$pre_match = false;
			if($route_type_param=="CASH")
			{
				if(strpos($route_type_rdb[$i],$route_type_param) !== false)
				{
					//echo "\nCASH2";
					$pre_match = true;
				}
			}
			else if($route_type_param=="FOCAL")
			{
				$tmp_type="CASH";
				if(strpos($route_type_rdb[$i],$tmp_type) === false)
				{
					//echo "\nFOCAL2";
					$pre_match = true;
				}
			}
			else if($route_type_param=="ALL")
			{
				//echo "\nALL2";
				$pre_match = true;
			}						
							
			if($pre_match)
			{						
				//echo "<BR>CreateHrly ".$i." ".$vehicle_imei_rdb[$i];
				for($j=0;$j<sizeof($customer_sel[$route_name_rdb_3]);$j++)
				{					
					$col_tmp = 'A'.$row;
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $vehicle_name_rdb[$i]);
					
					$col_tmp = 'B'.$row;
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $sno);
					
					$col_tmp = 'C'.$row;
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $customer_sel[$route_name_rdb_3][$j]);
					//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $customer_sel[$vehicle_imei_rdb[$i]][$j]);
					
					$col_tmp = 'D'.$row;					
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Customer");
					
					$col_tmp = 'E'.$row;
					//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $route_name_rdb_1[$k]);
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $route_name_rdb_3);					
					
					$col_tmp = 'F'.$row;
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $shift);
					
					$col_tmp = 'G'.$row;
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $plant_sel[$route_name_rdb_3][$j]);
					//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $plant_sel[$vehicle_imei_rdb[$i]][$j]);
					

					//echo "\nExpectedTimeSel=".$expected_time_sel[$route_name_rdb_3][$j];
					$cshift_date = "";
					
					if($shift=="ZBVE")
					{
						$cdatetime1 = strtotime(date('00:00:00'));
						$cdatetime2 = strtotime(date($expected_time_sel[$route_name_rdb_3][$j]));
						$difftime = $cdatetime2 - $cdatetime1;
						
						if($DEBUG_HRLY)
						{
							$difftime = 44200;
						}
						if($difftime > 43200)		//## > 12 PM CURRENT DAY ELSE NEXT DAY
						{
							$cshift_date = $cdate;
						}					
						else
						{
							$cshift_date = $nextdate;
						}
					}
					else
					{
						$cshift_date = $cdate;
					}					
					$col_tmp = 'M'.$row;
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $cshift_date);		

					$col_tmp = 'N'.$row;
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $expected_time_sel[$route_name_rdb_3][$j]);

					$col_tmp = 'Q'.$row;
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $remark_rdb[$i]);
					
					//####### PLANT SHIFT
					$cshift_date2 = "";
					if($shift=="ZBVE")
					{
						$cdatetime1 = strtotime(date('00:00:00'));
						$cdatetime2 = strtotime(date($expected_time_plant_out_sel[$route_name_rdb_3][$j]));
						$difftime = $cdatetime2 - $cdatetime1;
						
						if($DEBUG_HRLY)
						{
							$difftime = 44200;
						}
						
						if($difftime > 43200)		//## > 12 PM CURRENT DAY ELSE NEXT DAY
						{
							$cshift_date2 = $cdate;
						}					
						else
						{
							$cshift_date2 = $nextdate;
						}
					}
					else
					{
						$cshift_date2 = $cdate;
					}
					$col_tmp = 'V'.$row;
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $cshift_date2);
					
					$col_tmp = 'W'.$row;
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $expected_time_plant_out_sel[$route_name_rdb_3][$j]);					
					//####### PLANT SHIFT ENDS
					
					//echo "\nSizeTransporter=".sizeof($transporter_m);
					$transporter_name_master = "";
					for($n=0;$n<sizeof($transporter_m);$n++)
					{
						if($vehicle_name_rdb[$i] == $vehicle_m[$n])
						{
							//echo "\nTransporter Matched";
							$transporter_name_master = $transporter_m[$n];
							break;                                  
						}
					}			
					$col_tmp = 'Y'.$row;
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $transporter_name_master);
					
					$col_tmp = 'Z'.$row;
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $transporter_sel[$route_name_rdb_3][$j]);
					//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $transporter_sel[$vehicle_imei_rdb[$i]][$j]);					
					
					//$col_tmp = 'L'.$row;
					//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $shift);			

					$coord_str = explode(",",$station_coord[$route_name_rdb_3][$j]);
					//$coord_str = explode(",",$station_coord[$vehicle_imei_rdb[$i]][$j]);
					
					$col_tmp = 'AA'.$row;
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $route_type_rdb[$i]);						

					$col_tmp = 'AB'.$row;
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "");
					
					$col_tmp = 'AC'.$row;
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $coord_str[0]);
					
					$col_tmp = 'AD'.$row;
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $coord_str[1]);
					
					$col_tmp = 'AE'.$row;
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $distance_variable[$route_name_rdb_3][$j]);

					$col_tmp = 'AF'.$row;					
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValueExplicit($col_tmp, $vehicle_imei_rdb[$i], PHPExcel_Cell_DataType::TYPE_STRING);

					//#####
					//echo "\nPlant1=".$plant_sel[$route_name_rdb_3][$j];
					$temp_plant = explode('/',$plant_sel[$route_name_rdb_3][$j]);					

					$coord_tmp="";
					$radius_tmp="";
//echo "\nSizeplant=".sizeof($temp_plant);
					for($p=0;$p<sizeof($temp_plant);$p++)
					{
						$coord_tmp.=$plant_station_coord[$temp_plant[$p]]."#";
						$radius_tmp.=$plant_distance_variable[$temp_plant[$p]]."#";
						//echo "\nPlant2=".$temp_plant[$p];
						//echo "\nCoordtmp=".$coord_tmp." ,Radius=".$radius_tmp;
					}
					$coord_tmp = substr($coord_tmp,0,-1);
					$radius_tmp = substr($radius_tmp,0,-1);
					//echo "\nDistPlant=".$plant_station_coord[$plant_sel[$route_name_rdb_3][$j]];					
					//echo "\nCoordtmp=".$coord_tmp." ,Radius=".$radius_tmp;
										
					$col_tmp = 'AG'.$row;
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $coord_tmp);
					
					$col_tmp = 'AH'.$row;
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $radius_tmp);
						
					$col_tmp = 'AI'.$row;
					$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "0");
					
					$row++;
					$sno++;					
				}
			}
		}
	}
	
	//if($route_type_param!="ALL")
	//{
		$col_tmp = 'A'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , ":");
	//}
	//####### INSERT UNMATCHED ROUTES
	$row++;
	//echo "\nSizeAllRoute2=".sizeof($all_routes);
	/*for($y=0;$y<sizeof($all_routes);$y++)
	{
		echo "<br>AllRoute=".$all_routes[$y];
	}*/
	
	$result_all_routes = array_unique($all_routes);	
	//foreach($all_routes as $array_key => $array_value)	
	foreach($result_all_routes as $array_key => $array_value)	
        {
        $found = false;		
		
		//echo "<br>".$array_value;		
		//echo "<br>RouteNameRDB=".sizeof($route_name_rdb);
		for($j=0;$j<sizeof($route_name_rdb);$j++)
		{
			//if( (trim($array_value)== trim($route_name_rdb[$j])) || (trim($route_type_rdb[$i])==trim($route_type_param)) || (trim($route_type_param)=="ALL") )
			//if( (trim($array_value)== trim($route_name_rdb[$j])) && ((strpos($route_type_rdb[$j],$route_type_param) !== false) || ($route_type_param=="ALL") ) )

			$route_name_rdb_1 = explode('/',$route_name_rdb[$j]);
			//echo "<br>Route_name_rdb=".sizeof($route_name_rdb_1);
			
			for($k=0;$k<sizeof($route_name_rdb_1);$k++)
			{
				$route_name_rdb_3="";
				if(strpos($route_name_rdb_1[$k],'@') !== false)
				{
					$route_name_rdb_2 = explode('@',$route_name_rdb_1[$k]);
					$route_name_rdb_3 = $route_name_rdb_2[1];
				}
				else
				{
					$route_name_rdb_3 = $route_name_rdb_1[$k];
				}		
				
				//echo "<br>RouteNameRDb=".$route_name_rdb_3;
				$pre_match = false;
				if($route_type_param=="CASH")
				{
					//if((trim($array_value)== trim($route_name_rdb[$j])) && (strpos($route_type_rdb[$i],$route_type_param) !== false))
					if((trim($array_value)== trim($route_name_rdb_3)) && (strpos($route_type_rdb[$j],$route_type_param) !== false))
					{
						//echo "\nCASH2";
						$pre_match = true;					
					}
				}
				else if($route_type_param=="FOCAL")
				{
					$tmp_type="CASH";
					//if((trim($array_value)== trim($route_name_rdb[$j])) && (strpos($route_type_rdb[$i],$tmp_type) === false))
					if((trim($array_value)== trim($route_name_rdb_3)) && (strpos($route_type_rdb[$j],$tmp_type) === false))
					{
						//echo "\nFOCAL2";
						$pre_match = true;						
					}
				}
				//else if((trim($array_value)== trim($route_name_rdb[$j])) && ($route_type_param=="ALL"))
				else if((trim($array_value)== trim($route_name_rdb_3)) && ($route_type_param=="ALL"))
				{
					//echo "\nALL2";
					$pre_match = true;					
				}
				if($pre_match)
				{
					//echo "\nUnMatched Route=".$all_routes[$i];
					$found = true;
					break;
				}
			}				
		}
		if(!$found)
		{
			$customer_str="";
			for($i=0;$i<sizeof($all_customers);$i++)
			{
				$tmp_customer_arr = explode(":",$all_customers[$i]);
				if(trim($tmp_customer_arr[0])==trim($array_value))	//IF ROUTE MATCHED
				{
					//$relative_customer = $all_customers[$array_key];
					$customer_str.= $tmp_customer_arr[1].",";
				}
			}
			$customer_str = substr($customer_str, 0, -1);			
			$col_tmp = 'A'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $array_value);
			$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($styleFontRed);
			$col_tmp = 'B'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $customer_str);
			$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($styleFontRed);
			$row++;			
		}		
	}
	
	/*for($i=0;$i<sizeof($all_routes);$i++)
	{
		for($j=0;$j<sizeof($route_name_rdb);$j++)
		{
			if( trim($all_routes[$i])!= trim($route_name_rdb[$j]) )
			{
				//echo "\nUnMatched Route=".$all_routes[$i];
				$col_tmp = 'E'.$row;
				$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $all_routes[$i]);
				$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($styleFontRed);
				$row++;
			}
		}
	}*/		
	//#### FIRST TAB CLOSED ###################################################################
	
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
	$col_tmp = 'D'.$row;
	$objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp , "Transporter(I)");
	$objPHPExcel_1->getActiveSheet(1)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'E'.$row;					
	$objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp , "RouteType"); 					
	$objPHPExcel_1->getActiveSheet(1)->getStyle($col_tmp)->applyFromArray($header_font);

	$row++;
						
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
	$col_tmp = 'E'.$row;
	$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , "Unmapped Customers");					
	$objPHPExcel_1->getActiveSheet(2)->getStyle($col_tmp)->applyFromArray($header_font);	
	$col_tmp = 'F'.$row;
	$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , "Transporter(I)");					
	$objPHPExcel_1->getActiveSheet(2)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'G'.$row;
	$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , "RouteType");					
	$objPHPExcel_1->getActiveSheet(2)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'H'.$row;
	$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , "Remark");					
	$objPHPExcel_1->getActiveSheet(2)->getStyle($col_tmp)->applyFromArray($header_font);	
	$row++;		
		
	//#### THIRD TAB CLOSED ########################################
	
	//#### WRITE FINAL XLSX
	echo date('H:i:s') , " Write to Excel2007 format" , EOL;
echo "\nPATH=".$read_excel_path;
	@chmod($read_excel_path, 0777);
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel_1, 'Excel2007');
	$objWriter->save($read_excel_path);
	echo date('H:i:s') , " File written to " , $read_excel_path , EOL;	
}

?>
