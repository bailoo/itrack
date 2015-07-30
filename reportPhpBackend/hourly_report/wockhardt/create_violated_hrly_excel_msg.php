<?php
function create_violated_hrly_excel_msg($read_excel_path)
{
	global $village_violate_msg;
	global $message1;
	global $message2;
	global $message3;
	global $message4;
	echo "\nInCreateFinal :ViolatedHrlyExcel";
	global $VehicleName;
	global $SNo;
	global $VehicleID;
	global $BaseStation;	
	global $BSCoordinate;	
	global $BSExpectedDeptTime;
	global $BSExpectedArrTime;	
	global $VillageName;	
	global $VLCoordinate;	
	global $VLExpectedMinHaltDuration;	
	global $VLExpectedMaxHaltDuration;
	global $ActualBSDeptTime;	
	global $ActualBSArrTime;	
	global $DelayBSDept;	
	global $DelayBSArr;
	global $ActualVLArrTime;
	global $ActualVLDeptTime;	
	global $DelayVLArr;	
	global $DelayVLDept;	
	global $VLHaltDuration;
	global $VLHaltViolation;	
	global $TotalDistanceTravelled;	
	global $IMEI;
	global $ReportRunDate;
	global $ReportRunTime1;
	global $ReportRunTime2;		
	global $Remark;
	global $objPHPExcel_1;
	global $SubVehicles;
	global $sub_village_violate_msg;	
	
	$run_time1 = explode(' ',$time1);
	$run_time2 = explode(' ',$time2);
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

	$objPHPExcel_1->setActiveSheetIndex(0)->setTitle('WOKHARDT HALT REPORT');

	/*$objPHPExcel_1->createSheet();
	$objPHPExcel_1->setActiveSheetIndex(1)->setTitle('Route Completed');
	
	$objPHPExcel_1->createSheet();
	$objPHPExcel_1->setActiveSheetIndex(2)->setTitle('Route Pending');*/	

	$cellIterator = null;
	$column = null;
	$row = null;
	echo "\n1";
	//################ FIRST TAB ############################################
	//#######################################################################
	$row=1;
	
	/*
	//###### COLOR STYLES
	$header_font = array(
		'font'  => array(
		'bold'  => true,
		'color' => array('rgb' => '000000'), //RED
		'size'  => 10
		//'name'  => 'Verdana'
	));
	$styleFontRed = array(
	'font'  => array(
		'bold'  => true,
		'color' => array('rgb' => 'FF0000'), //RED
		'size'  => 10
		//'name'  => 'Verdana'
	));	
	
	//###### FILL THE HEADER
	$col_tmp = 'A'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "VehicleName");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'B'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "SNo");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
	$col_tmp = 'C'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "VehicleID");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);	
	$col_tmp = 'D'.$row;					
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Base Station");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);	
	$col_tmp = 'E'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "BS Coordinate");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'F'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "BS Expected DeptTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'G'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "BS Expected ArrTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'H'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Village Name");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'I'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "VL Coordinate");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'J'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "VL Expected MinHaltDuration");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'K'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "VL Expected MaxHaltDuration");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);	
	$col_tmp = 'L'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Actual BS DeptTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'M'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Actual BS ArrTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'N'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Delay BS Dept");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'O'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Delay BS Arr");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);	
	$col_tmp = 'P'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Actual VL ArrTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'Q'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Actual VL DeptTime");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);	
	$col_tmp = 'R'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "VL Halt Duration (h:m)");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);	
	$col_tmp = 'S'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "VL Halt Violation (h:m)");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);	
	$col_tmp = 'T'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Total Distance Travelled (km)");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);	
	$col_tmp = 'U'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "IMEI");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'V'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Report RunDate");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'W'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Report RunTime1");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'X'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Report RunTime2");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);	
	$col_tmp = 'Y'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , "Remark");
	$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
	$row++;
	*/	
	//echo "\nSizeRoute=".sizeof($route_name_rdb);	
	$msg_bs_tmp = "";
	$msg_vl_tmp = "";
	$msg_sub_bs_tmp = "";

	
	//$result_all_routes = array_unique($all_routes);	
	
	for($i=0;$i<sizeof($VehicleName);$i++)
	{
		$j=$i;
		
		//echo "<br>MAIN::i=".$i." ,j=".$j." ,RouteNo_CI[$i]=".$RouteNo_CI[$i];		
		/*
		$col_tmp = 'A'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $VehicleName[$i]);
				
		$col_tmp = 'B'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $SNo[$i]);
				
		$col_tmp = 'C'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $VehicleID[$i]);
		
		$col_tmp = 'D'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $BaseStation[$i]);
		
		$col_tmp = 'E'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $BSCoordinate[$i]);

		$temp_string = PHPExcel_Style_NumberFormat::toFormattedString($BSExpectedDeptTime[$i], 'hh:mm');
		$col_tmp = 'F'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $temp_string);

		$temp_string = PHPExcel_Style_NumberFormat::toFormattedString($BSExpectedArrTime[$i], 'hh:mm');
		$col_tmp = 'G'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $temp_string);
		
		$col_tmp = 'H'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $VillageName[$i]);
		
		$col_tmp = 'I'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $VLCoordinate[$i]);

		$temp_string = PHPExcel_Style_NumberFormat::toFormattedString($VLExpectedMinHaltDuration[$i], 'hh:mm');
		$col_tmp = 'J'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $temp_string);

		$temp_string = PHPExcel_Style_NumberFormat::toFormattedString($VLExpectedMaxHaltDuration[$i], 'hh:mm');
		$col_tmp = 'K'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $temp_string);

		$temp_string = PHPExcel_Style_NumberFormat::toFormattedString($ActualBSDeptTime[$i], 'hh:mm');
		$col_tmp = 'L'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $temp_string);

		$temp_string = PHPExcel_Style_NumberFormat::toFormattedString($ActualBSArrTime[$i], 'hh:mm');
		$col_tmp = 'M'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $temp_string);

		$temp_string = PHPExcel_Style_NumberFormat::toFormattedString($DelayBSDept[$i], 'hh:mm');
		$col_tmp = 'N'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $temp_string);

		$temp_string = PHPExcel_Style_NumberFormat::toFormattedString($DelayBSArr[$i], 'hh:mm');
		$col_tmp = 'O'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $temp_string);

		$temp_string = PHPExcel_Style_NumberFormat::toFormattedString($ActualVLArrTime[$i], 'hh:mm');
		$col_tmp = 'P'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $temp_string);

		$temp_string = PHPExcel_Style_NumberFormat::toFormattedString($ActualVLDeptTime[$i], 'hh:mm');
		$col_tmp = 'Q'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $temp_string);

		$temp_string = PHPExcel_Style_NumberFormat::toFormattedString($VLHaltDuration[$i], 'hh:mm');
		$col_tmp = 'R'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $temp_string);

		$temp_string = PHPExcel_Style_NumberFormat::toFormattedString($VLHaltViolation[$i], 'hh:mm');
		$col_tmp = 'S'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $temp_string);
		
		$col_tmp = 'T'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $TotalDistanceTravelled[$i]);
		
		$col_tmp = 'U'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $IMEI[$i]);

		$temp_string = PHPExcel_Style_NumberFormat::toFormattedString($ReportRunDate[$i], 'YYYY-mm-dd');
		$col_tmp = 'V'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $temp_string);

		$temp_string = PHPExcel_Style_NumberFormat::toFormattedString($ReportRunTime1[$i], 'hh:mm:ss');
		$col_tmp = 'W'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $temp_string);

		$temp_string = PHPExcel_Style_NumberFormat::toFormattedString($ReportRunTime2[$i], 'hh:mm:ss');
		$col_tmp = 'X'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $temp_string);
		
		$col_tmp = 'Y'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $Remark[$i]);						
		$row++;*/
		
											
		//######## BASE STATION VIOLATION ALERT
		/*
		$VehicleName[$i];
		$DelayBSDept[$i]
		$DelayBSArr[$i]			
			
		$BaseStation[$i];
		$BSExpectedDeptTime[$i];	
		$BSExpectedArrTime[$i]		
		
		$ActualBSDeptTime[$i]
		$ActualBSArrTime[$i]*/	
		//$message.= "<br><font color=red size=2><strong>BASE STATION VIOLATION</strong></font></br>";
		while(trim($VehicleName[$j])==trim($VehicleName[$i]))
		{
			$j++;
		}		
		echo "<br>DelayBSDept[i]=".$DelayBSDept[$i];
		
		//if(($DelayBSDept[$i]!="-") && ($DelayBSDept[$i]!="") && ($ActualVLArrTime[$i]==""))
		if(($DelayBSDept[$i]!="-") && ($DelayBSDept[$i]!=""))
		{
			$pos_c1 = strpos($DelayBSDept[$i], "-");
			//echo "\nPOS=".$pos;
			if($pos_c1 !== false)
			{	
				echo "<br>TRUE OCCURED";
			}
			else
			{
				echo "<br>FALSE OCCURED";
				//$msg_bs_tmp.= "<br><font color='blue' size=1><Strong>BaseStation Dept Violation:</strong></font><font color='red' size=1><Strong>Vehicle:</Strong>".$VehicleName[$i]." ,<Strong>Delay:</Strong>".$DelayBSDept[$i]." ,<Strong>DeptTime:</Strong>".$ActualBSDeptTime[$i]." ,<Strong>From BaseStation:</Strong>".$BaseStation[$i]."</font>";
				//$msg_bs_tmp.= "<br><font color='purple' size=1>*VEHICLE:</font><font color='blue' size=1>".$VehicleName[$i]."</font> <font color='red' size=1>Delayed in Departure From Base Location :</font><font color='blue' size=1>".$BaseStation[$i]."</font><font color='red' size=1>,DEPT-TIME:</font><font color='blue' size=1>".$ActualBSDeptTime[$i]."</font>";
				if($SubVehicles[$VehicleName[$i]]!="")
				{
					$msg_sub_bs_tmp.='<TR>
					<TD style="color:purple;font-size:14px;font-weight:bold;" align="left">Vehicle:</TD>
					<TD style="color:blue;font-size:14px;" align="left">'.$SubVehicles[$VehicleName[$i]].'</TD>
					<TD style="color:red;font-size:14px;" align="left">Delayed in Departure From Base Location :</TD>
					<TD style="color:blue;font-size:14px;" align="left">'.$BaseStation[$i].'</TD>
					<TD style="color:red;font-size:14px;" align="left">,DEPT-TIME:</TD>
					<TD style="color:blue;font-size:14px;" align="left">'.$ActualBSDeptTime[$i].'</TD>
				</TR>';
				}
			
				$bs_tmp = '
				<TR>
					<TD style="color:purple;font-size:14px;font-weight:bold;" align="left">Vehicle:</TD>
					<TD style="color:blue;font-size:14px;" align="left">'.$VehicleName[$i].'</TD>
					<TD style="color:red;font-size:14px;" align="left">Delayed in Departure From Base Location :</TD>
					<TD style="color:blue;font-size:14px;" align="left">'.$BaseStation[$i].'</TD>
					<TD style="color:red;font-size:14px;" align="left">,DEPT-TIME:</TD>
					<TD style="color:blue;font-size:14px;" align="left">'.$ActualBSDeptTime[$i].'</TD>
				</TR>';
				$msg_bs_tmp.= $bs_tmp;
			}								
		}
				
		if($j>$i)
		{
			$i=$j-1;
		}
		/*if( ($DelayBSArr[$i]!="-") && ($DelayBSArr[$i]!=""))
		{
			$pos_c1 = strpos($DelayBSArr[$i], "-");
			//echo "\nPOS=".$pos;
			if($pos_c1 !== false)
			{				
			}
			else
			{
				$msg_bs_tmp.= "<br><font color='blue' size=1>BaseStation Arrival Violation:</font><font color='blue' size='1'>Vehicle:".$VehicleName[$i]." <font color='blue' size=1>,Delay:".$DelayBSArr[$i]." <font color='blue' size=1>,ArrTime:".$ActualBSArrTime[$i]." <font color='blue' size=1>,To BaseStation:".$BaseStation[$i]."</font>";;
			}								
		}*/		

		//######## VILLAGE VIOLATION ALERT						
		/*$VillageName[$i];		
		$VLExpectedMinHaltDuration[$i]
		$VLExpectedMaxHaltDuration[$i]

		//$ActualVLArrTime[$i]
		//$ActualVLDeptTime[$i]

		$VLHaltDuration[$i]
		$VLHaltViolation[$i];*/
		
		//$message.= "<br><br><font color=red size=2><strong>VILLAGE HALT VIOLATION</strong></font></br>";
		
		/*if( ($VLHaltViolation[$i]!="-") && ($VLHaltViolation[$i]!="") )
		{
			$pos_c1 = strpos($VLHaltViolation[$i], "-");
			//echo "\nPOS=".$pos;
			if($pos_c1 !== false)
			{
				$msg_vl_tmp.= "<br><font color='red' blue='1'>Village Min Halt Violation:</font><font color='blue' size='1'>Vehicle:".$VehicleName[$i]." ,Halt Violation:".$VLHaltViolation[$i]." ,Halt Durtaion:".$VLHaltDuration[$i]." ,Village:".$VillageName[$i]." ,MinExpectedHalt:".$VLExpectedMinHaltDuration[$i]."</font>";
			}
			else
			{
				$msg_vl_tmp.= "<br><font color='red' blue='1'>Village Max Halt Violation:</font><font color='blue' size='1'>Vehicle:".$VehicleName[$i]." ,Halt Violation:".$VLHaltViolation[$i]." ,Halt Durtaion:".$VLHaltDuration[$i]." ,Village:".$VillageName[$i]." ,MaxExpectedHalt:".$VLExpectedMaxHaltDuration[$i]."</font>";
			}								
		}*/		
	}
	
		
	if($msg_bs_tmp!="")
	{	
		$message1.= '<TABLE BORDER=1 CELLPADDING=3 CELLSPACING=1 RULES=ROWS FRAME=BOX><TR><TD align="left" colspan="6" style="color:black;font-size:17px;font-weight:bold;">*BASE LOCATION DEPARTURE VIOLATION</TD></TR>';
		$message1.= $msg_bs_tmp;
		$message1.= '</TABLE>';			
	}
	
	if($village_violate_msg!="")
	{		
		$message2.= '<TABLE BORDER=1 CELLPADDING=3 CELLSPACING=1 RULES=ROWS FRAME=BOX><TR><TD align="left" colspan="3" style="color:black;font-size:17px;font-weight:bold;">*VILLAGE ROUTE VIOLATION ALERT</TD></TR>';
		$message2.= $village_violate_msg;
		$message2.= '</TABLE>';
	}
	
	if($msg_sub_bs_tmp!="")
	{	
		$message3.= '<TABLE BORDER=1 CELLPADDING=3 CELLSPACING=1 RULES=ROWS FRAME=BOX><TR><TD align="left" colspan="6" style="color:black;font-size:17px;font-weight:bold;">*BASE LOCATION DEPARTURE VIOLATION</TD></TR>';
		$message3.= $msg_sub_bs_tmp;
		$message3.= '</TABLE>';
	}
	
	if($sub_village_violate_msg!="")
	{
		$message4.= '<TABLE BORDER=1 CELLPADDING=3 CELLSPACING=1 RULES=ROWS FRAME=BOX><TR><TD align="left" colspan="3" style="color:black;font-size:17px;font-weight:bold;">*VILLAGE ROUTE VIOLATION ALERT</TD></TR>';
		$message4.= $sub_village_violate_msg;
		$message4.= '</TABLE>';
	}
	

	
	/*
	//####### INSERT UNMATCHED ROUTES
	$row++;
	//echo "\nSizeAllRoute2=".sizeof($all_routes);
	$result_all_routes = array_unique($all_routes);
	foreach($result_all_routes as $array_key => $array_value)
    {
        $found = false;
		for($j=0;$j<sizeof($route_name_rdb);$j++)
		{
			if( trim($array_value)== trim($route_name_rdb[$j]) )
			{
				//echo "\nUnMatched Route=".$all_routes[$i];
				$found = true;
			}
		}		
		if(!$found)
		{
			$col_tmp = 'E'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $array_value);
			$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($styleFontRed);
			$row++;		
		}
	}
	*/	
	//#### FIRST TAB CLOSED ###################################################################
	/*
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
	//#### THIRD TAB CLOSED ########################################
	*/
	
	//#### WRITE FINAL XLSX
	/*echo date('H:i:s') , " Write to Excel2007 format" , EOL;
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel_1, 'Excel2007');
	$objWriter->save($read_excel_path);
	echo date('H:i:s') , " File written to " , $read_excel_path , EOL;*/		
}

?>