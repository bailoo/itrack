<?php
function process_data()
{
	global $csv_string_halt_arr;
	global $csv_string_halt_final;
	
	for($p=0;$p<sizeof($csv_string_halt_arr);$p++)
	{
		//echo "vehicle_name=".$vname_label[$p]."<br><br>escalation_id=".$escalation_id[$i]."<br><br>csv_string_dist_final".$sheet2[$p]."<br><br>";
		//$report_title_halt = "Halt Report -Vehicle:".$vname_label[$p]." -($date1 to $date2)";   //COMMENTED ON REQ
		//echo "report_title_halt=".$report_title_halt."<br>";			              
		//$r++;
				
		//############### REBUILD ARRAY - ELIMINATE MULTIPLE HALT ENTRIES ###################//
		//####### TEMPORARY ASSIGNMENT		
		
		if($csv_string_halt_arr[$p]!="")
		{
			//echo "\nAllRows=".$csv_string_halt_arr[$p];		
			$sheet2_row_tmp = explode('#',$csv_string_halt_arr[$p]);	
			
			//echo "\nSizeSheetRowTmp=".sizeof($sheet2_row_tmp);			
			for($i=0;$i<sizeof($sheet2_row_tmp);$i++)
			{
				//$data_flag = false;
				//$sheet2_data_main_string="";
				//echo "\nSheetDataTmp=".$sheet2_row_tmp[$i];				
				$sheet2_data_tmp = explode(',',$sheet2_row_tmp[$i]);
			 				
				$vname_temp[] = $sheet2_data_tmp[0];
				$sno_temp[] = $sheet2_data_tmp[1];				
				//echo "\nSNO1=".$sheet2_data_tmp[1];
				$station_no_temp[] = $sheet2_data_tmp[2];
				$type_str_temp[] = $sheet2_data_tmp[3];
				$route_no_temp[] = $sheet2_data_tmp[4];
				$report_shift_temp[] = $sheet2_data_tmp[5];
				$arrivale_time_temp[] = $sheet2_data_tmp[6];
				$depature_time_temp[] = $sheet2_data_tmp[7];
				$schedule_in_time_temp[] = $sheet2_data_tmp[8];
				$time_delay_temp[] = $sheet2_data_tmp[9];
				$hrs_min_temp[] = $sheet2_data_tmp[10];
				$report_date1_temp[] = $sheet2_data_tmp[11];
				$report_time1_temp[] = $sheet2_data_tmp[12];
				$report_date2_temp[] = $sheet2_data_tmp[13];
				$report_time2_temp[] = $sheet2_data_tmp[14];
				$transporter_name_master_temp[] = $sheet2_data_tmp[15];
				$transporter_name_input_temp[] = $sheet2_data_tmp[16];
				$relative_plant_temp[] = $sheet2_data_tmp[17];
				$km_temp[] = $sheet2_data_tmp[18];					
			  //$sheet2_data_main_string=$sheet2_data_main_string.$sheet2_data_tmp[$m].",";
			}
		}
	}
		
	for($i=0;$i<sizeof($vname_temp);$i++)
	{	
		$mark[$i] = 0;	
	}
	
	$n =0 ;
	
	echo "\n";
	for($i=0;$i<sizeof($vname_temp);$i++)
	{				
		//echo "\nMark[$i]=".$mark[$i]." ,i=".$i;
		
		if($mark[$i] == 1)
		{
			//echo "\nMARK_I_ONE=".$i."\n";
			continue;
		}

		$vname[$n] = $vname_temp[$i];
		$sno[$n] = $sno_temp[$i];
		//echo "\nSNO2=".$sno[$n];
		$station_no[$n] = $station_no_temp[$i];
		$type_str[$n] = $type_str_temp[$i];
		$route_no[$n] = $route_no_temp[$i];
		$report_shift[$n] = $report_shift_temp[$i];
		$arrivale_time[$n] = $arrivale_time_temp[$i];
		$depature_time[$n] = $depature_time_temp[$i];
		$schedule_in_time[$n] = $schedule_in_time_temp[$i];
		$time_delay[$n] = $time_delay_temp[$i];
		$hrs_min[$n] = $hrs_min_temp[$i];
		$report_date1[$n] = $report_date1_temp[$i];
		$report_time1[$n] = $report_time1_temp[$i];
		$report_date2[$n] = $report_date2_temp[$i];
		$report_time2[$n] = $report_time2_temp[$i];
		$transporter_name_master[$n] = $transporter_name_master_temp[$i];
		$transporter_name_input[$n] = $transporter_name_input_temp[$i];
		$relative_plant[$n] = $relative_plant_temp[$i];
		$km[$n] = $km_temp[$i];												
		
		//echo "\n\n";
		for($j=1;$j<100;$j++)
		{
			$ji_sum = $j + $i;
			
			if(($vname_temp[$ji_sum])!=$vname_temp[$ji_sum-1])
			{
				break;
			}			
			//echo "\nI=".$i." ,JI_SUM=".$ji_sum;
			
			if( $ji_sum < sizeof($vname_temp))
			{
				/*echo "\n\nONE::";
				echo "\narrivale_time_temp[ji_sum]=".$arrivale_time_temp[$ji_sum]." ,depature_time_temp[i]=".$depature_time_temp[$i];
				echo "\nvname_temp[$ji_sum]=".$vname_temp[$ji_sum]." ,vname_temp[$i]=".$vname_temp[$i];
				echo "\nstation_no_tmp[$ji_sum]=".$station_no_temp[$ji_sum]." ,station_no_temp[$i]=".$station_no_temp[$i];
				echo "\ntype_str_temp[$ji_sum]=".$type_str_temp[$ji_sum]." ,type_str_temp[$i]=".$type_str_temp[$i];
				echo "\nroute_no_temp[$ji_sum]=".$route_no_temp[$ji_sum]." ,route_no_temp[$i]=".$route_no_temp[$i]."\n";*/
				
				if( ($vname_temp[$ji_sum] == $vname_temp[$i]) && ($station_no_temp[$ji_sum] == $station_no_temp[$i]) && ($type_str_temp[$ji_sum] == $type_str_temp[$i]) && ($route_no_temp[$ji_sum] == $route_no_temp[$i]) )
				{
					//echo "\nFilter Vehicle Matched2";
					//echo "\narrivale_time_temp[ji_sum]=".$arrivale_time_temp[$ji_sum]." ,depature_time_temp[i]=".$depature_time_temp[$i];
					
					$diff = strtotime($arrivale_time_temp[$ji_sum]) - strtotime($depature_time_temp[$i]);
					//echo "\nDIF==".$diff;
					//if($diff < 600)
					if($diff < 3600)
					{
						/*echo "\n\nFOUND::i=".$i." ,j_sum=".$ji_sum."\n";
						echo "\narrivale_time_temp[ji_sum]=".$arrivale_time_temp[$ji_sum]." ,depature_time_temp[i]=".$depature_time_temp[$i];
						echo "\nvname_temp[$ji_sum]=".$vname_temp[$ji_sum]." ,vname_temp[$i]=".$vname_temp[$i];
						echo "\nstation_no_tmp[$ji_sum]=".$station_no_temp[$ji_sum]." ,station_no_temp[$i]=".$station_no_temp[$i];
						echo "\ntype_str_temp[$ji_sum]=".$type_str_temp[$ji_sum]." ,type_str_temp[$i]=".$type_str_temp[$i];
						echo "\nroute_no_temp[$ji_sum]=".$route_no_temp[$ji_sum]." ,route_no_temp[$i]=".$route_no_temp[$i];*/
						
						$depature_time[$n] = $depature_time_temp[$ji_sum];
						$depature_time_temp[$i] = $depature_time_temp[$ji_sum];
						$mark[$ji_sum] = 1;
					}
				}
			}
		}
		$n++;
	} 			
	//}

	//########### STORE FINAL ARRAY 
	$csv_string_halt_final = "";
	$substr_count = 0;
	//echo "\nFinal Size Vname=".sizeof($vname);
	echo "\n";
	$sno_tmp = 1;
	
	for($i=0;$i<sizeof($vname);$i++)
	{						
		//echo "\nSNO2=".$sno[$i];		
		$date_obj1 = strtotime($report_date1[$i]);
		$report_date1[$i] = date('d-m-Y',$date_obj1);
		//$report_date1[$i] = intval($date_obj1 / 86400 + 25569);			
		
		$date_obj2 = strtotime($report_date2[$i]);
		$report_date2[$i] = date('d-m-Y',$date_obj2);
		//$report_date2[$i] = intval($date_obj2 / 86400 + 25569);	
		
		$arrival_tmp = explode(" ",$arrivale_time[$i]);
		$departure_tmp = explode(" ",$depature_time[$i]);
		
		if($arrival_tmp[1]!="" || $departure_tmp[1]!="") 
		{	
			/*if(($vname_prev == $vname[$i]) && ($station_no_prev == $station_no[$i]) && ($type_str_prev == $type_str[$i]) && ($report_shift_prev == $report_shift[$i]) && ($route_no_prev == $route_no[$i]) && ($arrivale_time_prev == $arrivale_time[$i]))
			{
				continue;
			}*/
			if($route_no[$i]=="")	$route_no[$i]="-";
			if($arrivale_time[$i]=="") $arrivale_time[$i]="-";
			if($depature_time[$i]=="")	$depature_time[$i]="-";
			if($schedule_in_time[$i]=="")	$schedule_in_time[$i]="-";
			if($time_delay[$i]=="")	$time_delay[$i]="-";
			if($hrs_min[$i]=="")	$hrs_min[$i]="-";
			if($report_date1[$i]=="")	$report_date1[$i]="-";
			if($report_time1[$i]=="")	$report_time1[$i]="-";
			if($report_date2[$i]=="")	$report_date2[$i]="-";
			if($report_time2[$i]=="")	$report_time2[$i]="-";
			if($transporter_name_master[$i]=="")	$transporter_name_master[$i]="-";
			if($transporter_name_input[$i]=="")	$transporter_name_input[$i]="-";
			if($relative_plant[$i]=="")	$relative_plant[$i]="-";			
			if($km[$i]=="")	$km[$i]="-";
			
			if($vname_prev != $vname[$i])
			{
				$sno_tmp = 1;
				$sno[$i] = $sno_tmp;
			}
			else
			{
				$sno[$i] = $sno_tmp;
			}
						
			$halt_duration = strtotime($depature_time[$i]) - strtotime($arrivale_time[$i]);
			$hms_2 = secondsToTime($halt_duration);
			$hrs_min[$i] = $hms_2[h].":".$hms_2[m].":".$hms_2[s];			 			  
			
			//echo "\nSize:RelativeCustomer=".sizeof($relative_customer_input)." ,Size:RelativePlant=".sizeof($relative_plant_input);
						
			//###### GET RELATIVE PLANT, KMs DETAIL OF INPUT EVENING FILE			
			$relative_tmp_string = "";
			$relative_plants = "-";			
			$relative_transporters = "-";
			$relative_routes = "-";
			$flag1 = false;
											
			if($type_str[$i]!="Plant")
			{
				//*************************PD
				$pos_c = strpos($station_no[$i], "@");
				if($pos_c !== false)
				{
					//echo "\nNegative Found";
					$customer_at_the_rateA = explode("@", $station_no[$i]);											
				}
				else
				{
					$customer_at_the_rateA[0] = $station_no[$i];
				}
								
				$relative_tmp_string = binary_plant_search($customer_at_the_rateA[0], $relative_customer_input, $relative_plant_input, $relative_transporter_input, $relative_route_input);				
				$relative_tmp_string_arr = explode(":",$relative_tmp_string);
				//echo "\nRelativeTMPStr=".$relative_tmp_string."\n";				
				$relative_plants = $relative_tmp_string_arr[0];
				$relative_transporters = $relative_tmp_string_arr[1];
				$relative_routes = $relative_tmp_string_arr[2];		
				//echo "\nrelative_plants1=".$relative_plants." ,relative_transporters=".$relative_transporters." ,relative_routes=".$relative_routes;
			}
			$km[$i] = round($km[$i],2);
			//#########################################################################
			
			//$relative_plants = "-";		//COMMENT IT -TEMPORARY
			//$km[$i] = "-";
			/*
			if($substr_count == 0)
			{											
				$csv_string_halt_final = $csv_string_halt_final.$vname[$i].','.$sno[$i].','.$station_no[$i].','.$type_str[$i].','.$route_no[$i].','.$report_shift[$i].','.$arrivale_time[$i].','.$depature_time[$i].','.$schedule_in_time[$i].','.$time_delay[$i].','.$hrs_min[$i].','.$report_date1[$i].','.$report_time1[$i].','.$report_date2[$i].','.$report_time2[$i].','.$transporter_name_master[$i].','.$transporter_name_input[$i].','.$relative_plants.','.$km[$i];
				$substr_count =1;  
			}
			else
			{
				$csv_string_halt_final = $csv_string_halt_final."#".$vname[$i].','.$sno[$i].','.$station_no[$i].','.$type_str[$i].','.$route_no[$i].','.$report_shift[$i].','.$arrivale_time[$i].','.$depature_time[$i].','.$schedule_in_time[$i].','.$time_delay[$i].','.$hrs_min[$i].','.$report_date1[$i].','.$report_time1[$i].','.$report_date2[$i].','.$report_time2[$i].','.$transporter_name_master[$i].','.$transporter_name_input[$i].','.$relative_plants.','.$km[$i]; 
			}
			*/
			//echo "\nrelative_plants2=".$relative_plants." ,relative_transporters=".$relative_transporters." ,relative_routes=".$relative_routes;
			
			if($substr_count == 0)
			{											
				$csv_string_halt_final = $csv_string_halt_final.$vname[$i].','.$sno[$i].','.$station_no[$i].','.$type_str[$i].','.$relative_routes.','.$report_shift[$i].','.$arrivale_time[$i].','.$depature_time[$i].','.$schedule_in_time[$i].','.$time_delay[$i].','.$hrs_min[$i].','.$report_date1[$i].','.$report_time1[$i].','.$report_date2[$i].','.$report_time2[$i].','.$transporter_name_master[$i].','.$relative_transporters.','.$relative_plants.','.$km[$i];
				$substr_count =1;  
			}
			else
			{
				$csv_string_halt_final = $csv_string_halt_final."#".$vname[$i].','.$sno[$i].','.$station_no[$i].','.$type_str[$i].','.$relative_routes.','.$report_shift[$i].','.$arrivale_time[$i].','.$depature_time[$i].','.$schedule_in_time[$i].','.$time_delay[$i].','.$hrs_min[$i].','.$report_date1[$i].','.$report_time1[$i].','.$report_date2[$i].','.$report_time2[$i].','.$transporter_name_master[$i].','.$relative_transporters.','.$relative_plants.','.$km[$i]; 
			}			
			
			//echo "\nCSV_STRING=".$csv_string_halt_final;			
			$vname_prev = $vname[$i];
			$station_no_prev = $station_no[$i];
			$type_str_prev = $type_str[$i];
			$report_shift_prev = $report_shift[$i];
			$route_no_prev = $relative_routes;
			$arrivale_time_prev = $arrivale_time[$i];			
			$sno_tmp++;			
		}
	}
	//############################################################################################	
}

?>