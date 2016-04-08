<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	//include_once('manage_hierarchy_header1.php');
	$root=$_SESSION['root'];
	$common_id1=$_POST['common_id'];
	$Query="SELECT customer_no FROM station WHERE user_account_id='$account_id' and status=1";
	//echo "Query=".$Query."<br>";
	$Result=mysql_query($Query,$DbConnection);
	global $customer_no_arr;
	$customer_no_arr=array();
	$ui=0;
	while($Row=mysql_fetch_object($Result))
	{
		$customer_no_arr[$ui]=$Row->customer_no;
		//echo"cn=".$Row->customer_no."<br>";
		$ui++;
	}
		
	//echo "store_file=".$store_file_path."<br>";
	/*$highestColumm = $objPHPExcel->setActiveSheetIndex(5)->getHighestColumn();
	$highestRow = $objPHPExcel->setActiveSheetIndex(5)->getHighestRow();
	echo 'getHighestColumn() =  [' . $highestColumm . ']<br/>';
	echo 'getHighestRow() =  [' . $highestRow . ']<br/>';*/	
	global $excel_vehicle_name;
	$excel_vehicle_name=array();
	global $excel_icd_code;
	$excel_icd_code=array();
	global $excel_icd_out_datetime;
	$excel_icd_out_datetime=array();
	global $excel_factory_code;
	$excel_factory_code=array();
	global $excel_factory_e_a_t;
	$excel_factory_e_a_t=array();
	global $excel_icd_in_datetime;
	$excel_icd_in_datetime=array();
	global $excel_remark;
	$excel_remark=array();
	global $vehicle_cnt;
	$vehicle_cnt=0;
	$excel_data_cnt=0;
	$date_tmp=substr(date("Y-m-d"),0,-3);
	$store_file_path="daily_report/klp_out/klp_input/".$common_id1."/".$date_tmp.".xlsx";
	//echo "stor_path=".$store_file_path."<br>";
	if(file_exists($store_file_path))
	{
		//echo "in if";
		error_reporting(E_ALL);
		set_time_limit(1000);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
		date_default_timezone_set('Europe/London');
		/** Include PHPExcel_IOFactory */	
		require_once 'PHPExcel2/Classes/PHPExcel/IOFactory.php';		
		$objPHPExcel_Old = PHPExcel_IOFactory::load($store_file_path);
		//echo "in if <br>";
		foreach ($objPHPExcel_Old->setActiveSheetIndex(0)->getRowIterator() as $row) 
		{
			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false);	
			foreach ($cellIterator as $cell) 
			{
				if (!is_null($cell)) 
				{
					$column = $cell->getColumn();
					$row = $cell->getRow();
					//if($row>1 && $row<=50)			
					if($row>1)
					{
						$excel_vehicle_name[]=$objPHPExcel_Old->getActiveSheet()->getCell("A".$row)->getValue();					
						$excel_icd_code[]=$objPHPExcel_Old->getActiveSheet()->getCell("B".$row)->getValue();
						$excel_icd_out_datetime[]=$objPHPExcel_Old->getActiveSheet()->getCell("C".$row)->getValue()." ".$objPHPExcel_Old->getActiveSheet()->getCell("D".$row)->getValue();
						$excel_factory_code[]=$objPHPExcel_Old->getActiveSheet()->getCell("E".$row)->getValue();
						$excel_factory_e_a_t[]=$objPHPExcel_Old->getActiveSheet()->getCell("F".$row)->getValue()." ".$objPHPExcel_Old->getActiveSheet()->getCell("G".$row)->getValue();					
						$excel_icd_in_datetime[]=$objPHPExcel_Old->getActiveSheet()->getCell("H".$row)->getValue()." ".$objPHPExcel_Old->getActiveSheet()->getCell("I".$row)->getValue();
						//echo "in date time=".$objPHPExcel_Old->getActiveSheet()->getCell("G".$row)->getValue()." ".$objPHPExcel_Old->getActiveSheet()->getCell("H".$row)->getValue()."<br>";
						//echo "in time=".$objPHPExcel_Old->getActiveSheet()->getCell("H".$row)->getValue()."<br>";
						$excel_remark[]=$objPHPExcel_Old->getActiveSheet()->getCell("I".$row)->getValue();
						break;
					}			
				}		
			} 
		}
	}	
	echo"<center>
			<table cellspacing=5 cellpadding=5>
				<tr>
					<td>
						<b>
							Assign Klp Input
						</b>
					</td>
				</tr>
			</table>
		</center>";
	echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';
	echo"<form name='manage1' method='post'>
			<table width='100%'>
				<tr>
					<td align='center'>			
					<div style='overflow:auto;height:400px;'> 	
					<table border=0 cellspacing=3 cellpadding=3 class='module_left_menu' bordercolor='gray'>";
					echo"<tr>
							<td>
								<strong>
									&nbsp;Vehicle
								</strong>
							</td>
							<td>
								<strong>
									&nbsp;ICD Code
								</strong>
							</td>
							<td>
								<strong>
									&nbsp;ICD Out Date Time
								</strong>
							</td>						
							<td>
								<strong>
									&nbsp;Factory Code
								</strong>
							</td>
							<td>
								<strong>
									&nbsp;Factory E.A.DT.
								</strong>
							</td>
							<td>
								<strong>
									&nbsp;ICD In Date Time
								</strong>
							</td>							
							<td>
								<strong>
									&nbsp;Remark
								</strong>
							</td>
						</tr>";						
						get_user_vehicle($root,$common_id1);
				 	echo'</table>
				</div>
				</td>
			</tr>
		</table>
		<center>
		<br>
		<br>
					<input type="button" id="enter_button" name="enter_button" Onclick="javascript:return action_manage_klp_input(\'assign\')" value="Assign">&nbsp;<input type="reset" value="Cancel">
				<br><a href="javascript:show_option(\'manage\',\'klp_input\');" class="back_css">&nbsp;<b>Back</b></a>
				</center>
			</form>';	

			//echo "testdsfadfasfdsf";
			//############ FUNTION ROUTE DETAIL ####################
			function get_route_detail($account_id, $shift_time)
			{
				//echo "\nAccount_id=".$account_id." ,shift_time=".$shift_time;			
				global $route_input;
			
				//$dir = "c:\\gps_report/231/master";
				//$dir = "c:\\halt2/test_master";
				$dir = "/var/www/html/vts/beta/src/php/gps_report/".$account_id."/master";
				$dh = opendir($dir);
				while (($file = readdir($dh)) !== false) {
					//echo "<A HREF=\"$file\">$file</A><BR>\n";
					$file_tmp = explode("#",$file);
					$file_ext = explode(".",$file_tmp[2]);
					if($file_ext[0]!="")
					{			
						//echo "\nfile_ext[0]=".$file_ext[0]." ,shift_time=".$shift_time;
						if( ($file_ext[0] == "4") && ($shift_time=="ZPME") )		//###### EVENING FILE
						{
							$path = $dir."/".$file;
			
							$row = 1;
							if (($handle = fopen($path, "r")) !== FALSE)
							{
								while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
								{
									$num = count($data);
									//echo "<p> $num fields in line $row: <br /></p>\n";
									$row++;
										
									if($num<10)
									{
										continue;
									}
									if($row > 2)
									{
										//$shift_input[] = $data[4];
										$route_input[] = $data[6];
										//$vehicle_input[] = $data[7];
										//$transporter_input[] = $data[8];
										//echo "\nEV:r=".$row." ,data[4]=".$data[4]." ,data[6]=".$data[6]." ,data[7]=".$data[7]." ,data[9]=".$data[9];
									}
								}
								fclose($handle);
								//echo "\nsizeof(route_input)=".sizeof($route_input);
							}
						}
						 
						if( ($file_ext[0] == "5") && ($shift_time=="ZPMM") )			//###### MORNING FILE
						{
							$path = $dir."/".$file;
							$row = 1;
							if (($handle = fopen($path, "r")) !== FALSE) {
									
								while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
									$num = count($data);
									//echo "<p> $num fields in line $row: <br /></p>\n";
									$row++;
										
									if($num<10)
									{
										continue;
									}
									if($row > 2)
									{
										//$shift_input[] = $data[4];
										$route_input[] = $data[6];
										//$vehicle_input[] = $data[7];
										//$customer_input[] = $data[9];
										//echo "\nMOR::r=".$row." ,data[4]=".$data[4]." ,data[6]=".$data[6]." ,data[7]=".$data[7]." ,data[9]=".$data[9];
									}
								}
								fclose($handle);
							}
						}								
					}  //
				}
				closedir($dh);
			} //function closed			
			
			//############ FUNCTION ROUTE CLOSED ########################
			
			//############ FUNCTION VEHICLE DETAIL ##################
			function common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$option_name)
			{
				global $customer_no_arr;
				global $excel_vehicle_name;				
				global $excel_icd_code;				
				global $excel_icd_out_datetime;			
				global $excel_factory_code;			
				global $excel_factory_e_a_t;			
				global $excel_icd_in_datetime;			
				global $excel_remark;			
				//$td_cnt++;
				global $td_cnt;
				global $vname_assigned;
				global $route_assigned;
				global $route_input1;
				global $remark_assigned;
				
				$found = 0;
				$route_matched = "";
				$remark_matched = "";
				for($k=0;$k<sizeof($vname_assigned);$k++)
				{
					if(trim($vname_assigned[$k]) == trim($vehicle_name))
					{
						$found = 1;
						$route_matched = $route_assigned[$k];
						$remark_matched = $remark_assigned[$k];
						break;
					}
				}
				
				//date_default_timezone_set('Asia/Calcutta');
				$current_date = date('Y-m-d');

				$xml_file = "../../../xml_vts/xml_data/".$current_date."/".$vehicle_imei.".xml";
			
				//if(file_exists($xml_file))
				//{
				$excel_entry_flag=0;
				if(count($excel_icd_in_datetime)>0)
				{
					for($ei=0;$ei<sizeof($excel_icd_in_datetime);$ei++)
					{
						//echo "vehicle_id1=".$excel_vehicle_name[$ei]."vehicle_id2=".$vehicle_id."<br>";
						if($excel_vehicle_name[$ei]==$vehicle_name)
						{
							//echo "excel_icd_in_datetime=".$excel_icd_in_datetime[$ei]."<br>";
							if($excel_icd_in_datetime[$ei]==" ")
							{								
								$excel_entry_flag=1;
								$icd_vehicle_id = trim($vehicle_id)."_icd_vehicle_name";
								echo '<input type="hidden" name="unassigned_vehicles[]" value="'.$vehicle_id.'"/>';
								echo '<input type="hidden" id="'.$icd_vehicle_id.'" value="'.$vehicle_name.'"/>';
								echo'
								<tr>
								<td align="left">'.$vehicle_name.'</td>';
									
									/*if($found)
									{
										//echo '<font color="green"><strong>'.$route_matched.'</strong></font>';
										echo '<input type="hidden" name="unassigned_vehicles[]" value="'.$vehicle_id.'"/>';
										$text_content1 =$vehicle_id."text_content";
										echo '<input type="text" onKeyUp="getScriptPage1(\'box\',\'text_content\',\''.$vehicle_id.'\')" id="'.$text_content1.'" size="20" name="veh_name" value="'.$route_matched.'">';
											$box1_id =$vehicle_id."box";
											//echo "id=".$box1_id."<br>";
										echo '<div id="'.$box1_id.'"></div>';							
									}
									else
									{						
										//echo '<input type="hidden" name="unassigned_vehicles[]" value="'.$vehicle_id.'"/>';
										echo '<input type="hidden" name="unassigned_vehicles[]" value="'.$vehicle_id.'"/>';
										$text_content1 =$vehicle_id."text_content";
										echo '<input type="text" onKeyUp="getScriptPage1(\'box\',\'text_content\',\''.$vehicle_id.'\')" id="'.$text_content1.'" size="20" name="veh_name">';
											$box1_id =$vehicle_id."box";
											//echo "id=".$box1_id."<br>";
										echo '<div id="'.$box1_id.'"></div>';

										//echo '<SELECT id="'.$vehicle_id.'">';
										//echo '<option value="0">Select</option>';
										//if(sizeof($route_input1)>0)					
										//{								
											/*foreach ($route_input1 as $route_new) 
											{													
												$val = $vehicle_id.':'.$route_new;
												echo '<option value="'.$val.'">'.$route_new.'</option>';
											}*/
										//}
										//echo '</SELECT>';
									//}
									$icd_code = $vehicle_id."_icd_code";
									$icd_out_datetime = $vehicle_id."_icd_out_datetime";
									//echo "id=".$icd_out_datetime."ds=".$icd_out_datetime."<br>";
									$factory_code = $vehicle_id."_factory_code";
									$factory_e_a_t = $vehicle_id."_factory_e_a_t";
									$icd_in_datetime = $vehicle_id."_icd_in_datetime";					
									$rem = $vehicle_id."_rem";								
									echo'<td>
											&nbsp;<input type="text" id="'.$icd_code.'" size="14" value="'.$excel_icd_code[$ei].'">
										</td>
										<td>
											&nbsp;<input type="text" id="'.$icd_out_datetime.'" name="start_date" value="'.$excel_icd_out_datetime[$ei].'" size="18" maxlength="19">
											<a href=javascript:NewCal_SD("'.$icd_out_datetime.'","yyyymmdd",true,24)>
														<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
													</a>
										</td>
									
										<td>
											<SELECT id="'.$factory_code.'">
												<option value="select">Select</option>';
											for($i=0;$i<sizeof($customer_no_arr);$i++)
											{
												if($customer_no_arr[$i]==$excel_factory_code[$ei])
												{
											echo'<option value="'.$customer_no_arr[$i].'" selected>
													'.$customer_no_arr[$i].'
												</option>';
												}
												else
												{
												echo'<option value="'.$customer_no_arr[$i].'">
														'.$customer_no_arr[$i].'
													</option>';
												}
											}
											echo'</SELECT>
											<!--&nbsp;<input type="text" id="'.$factory_code.'" size="20" value="'.$excel_factory_code[$ei].'">-->
										</td>
										<td>
											<!--&nbsp;<input type="text" id="'.$factory_e_a_t.'" size="10" value="'.$excel_factory_e_a_t[$ei].'">-->
											&nbsp;<input type="text" id="'.$factory_e_a_t.'" name="start_date" value="'.$excel_factory_e_a_t[$ei].'" size="18" maxlength="19">
											<a href=javascript:NewCal_SD("'.$factory_e_a_t.'","yyyymmdd",true,24)>
														<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
													</a>
										</td>							
										<td>
											&nbsp;<input type="text" id="'.$icd_in_datetime.'" name="start_date" value="'.$excel_icd_in_datetime[$ei].'" size="18" maxlength="19">
											<a href=javascript:NewCal_SD("'.$icd_in_datetime.'","yyyymmdd",true,24)>
														<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
													</a>
										</td>							
										<td>
											&nbsp;<input type="text" id="'.$rem.'" size="20" value="'.$excel_remark[$ei].'">
										</td>
								   </td>
								</tr>
								';
							}
							//break;
						}
					}
				}
				if($excel_entry_flag==0)
				{			
					$icd_vehicle_id = $vehicle_id."_icd_vehicle_name";
					echo '<input type="hidden" name="unassigned_vehicles[]" value="'.$vehicle_id.'"/>';
					echo '<input type="hidden" id="'.$icd_vehicle_id.'" value="'.$vehicle_name.'"/>';
						echo'
						<tr>
						<td align="left">'.$vehicle_name.'</td>';
							
							/*if($found)
							{
								//echo '<font color="green"><strong>'.$route_matched.'</strong></font>';
								echo '<input type="hidden" name="unassigned_vehicles[]" value="'.$vehicle_id.'"/>';
								$text_content1 =$vehicle_id."text_content";
								echo '<input type="text" onKeyUp="getScriptPage1(\'box\',\'text_content\',\''.$vehicle_id.'\')" id="'.$text_content1.'" size="20" name="veh_name" value="'.$route_matched.'">';
									$box1_id =$vehicle_id."box";
									//echo "id=".$box1_id."<br>";
								echo '<div id="'.$box1_id.'"></div>';							
							}
							else
							{						
								//echo '<input type="hidden" name="unassigned_vehicles[]" value="'.$vehicle_id.'"/>';
								echo '<input type="hidden" name="unassigned_vehicles[]" value="'.$vehicle_id.'"/>';
								$text_content1 =$vehicle_id."text_content";
								echo '<input type="text" onKeyUp="getScriptPage1(\'box\',\'text_content\',\''.$vehicle_id.'\')" id="'.$text_content1.'" size="20" name="veh_name">';
									$box1_id =$vehicle_id."box";
									//echo "id=".$box1_id."<br>";
								echo '<div id="'.$box1_id.'"></div>';

								//echo '<SELECT id="'.$vehicle_id.'">';
								//echo '<option value="0">Select</option>';
								//if(sizeof($route_input1)>0)					
								//{								
									/*foreach ($route_input1 as $route_new) 
									{													
										$val = $vehicle_id.':'.$route_new;
										echo '<option value="'.$val.'">'.$route_new.'</option>';
									}*/
								//}
								//echo '</SELECT>';
							//}
							$icd_code = $vehicle_id."_icd_code";
							$icd_out_datetime =$vehicle_id."_icd_out_datetime";						
							$factory_code = $vehicle_id."_factory_code";
							$factory_e_a_t = $vehicle_id."_factory_e_a_t";
							$icd_in_datetime = $vehicle_id."_icd_in_datetime";					
							$rem = $vehicle_id."_rem";								
							echo'<td>
									&nbsp;<input type="text" id="'.$icd_code.'" size="14">
								</td>
								<td>
									&nbsp;<input type="text" id="'.$icd_out_datetime.'" name="start_date" size="18" maxlength="19">
									<a href=javascript:NewCal_SD("'.$icd_out_datetime.'","yyyymmdd",true,24)>
												<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
											</a>
								</td>
							
								<td>
									<!--&nbsp;<input type="text" id="'.$factory_code.'" size="20">-->
									<SELECT id="'.$factory_code.'">
										<option value="select">Select</option>';
										for($i=0;$i<sizeof($customer_no_arr);$i++)
										{													
										echo'<option value="'.$customer_no_arr[$i].'">
												'.$customer_no_arr[$i].'
											</option>';											
										}
									echo'</SELECT>
								</td>
								<td>
									<!--&nbsp;<input type="text" id="'.$factory_e_a_t.'" size="10">-->
									&nbsp;<input type="text" id="'.$factory_e_a_t.'" name="start_date" size="18" maxlength="19">
									<a href=javascript:NewCal_SD("'.$factory_e_a_t.'","yyyymmdd",true,24)>
												<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
											</a>
								</td>							
								<td>
									&nbsp;<input type="text" id="'.$icd_in_datetime.'" name="start_date" size="18" maxlength="19">
									<a href=javascript:NewCal_SD("'.$icd_in_datetime.'","yyyymmdd",true,24)>
												<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
											</a>
								</td>							
								<td>
									&nbsp;<input type="text" id="'.$rem.'" size="20">
								</td>
						   </td>
						</tr>
						';
				}
				//}
				/*else
				{
					echo'
					<tr>
					<td align="left"><font color="grey">'.$vehicle_name.'</font></td><td>:</td>
					<td align="left">';
						
						if($found)
						{
							echo '<font color="green"><strong>'.$route_matched.'</strong></font>';
						}
						else
						{						
							echo '<input type="hidden" name="unassigned_vehicles[]" value="'.$vehicle_id.'"/>';
							echo '<SELECT id="'.$vehicle_id.'">';
							echo '<option value="0">Select</option>';
							if(sizeof($route_input1)>0)					
							{								
								foreach ($route_input1 as $route_new) 
								{													
									$val = $vehicle_id.':'.$route_new;
									echo '<option value="'.$val.'">'.$route_new.'</option>';
								}
							}
							echo '</SELECT>';
						}
						$rem = $vehicle_id."rem";
						echo'</td>	
						<td>&nbsp;<input type="text" id="'.$rem.'" size="10"></td>						
					   </td>
					</tr>
					<tr><td coslpan="3"><br></td></tr>';
				}*/
			}

			function get_user_vehicle($AccountNode,$account_id)
			{
				global $customer_no_arr;
				global $vehicleid;
				global $vehicle_cnt;
				global $td_cnt;
				global $DbConnection;
				if($AccountNode->data->AccountID==$account_id)
				{
					$td_cnt =0;
					for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
					{			    
						$vehicle_id = $AccountNode->data->VehicleID[$j];
						$vehicle_name = $AccountNode->data->VehicleName[$j];
						$vehicle_imei = $AccountNode->data->DeviceIMEINo[$j];
						if($vehicle_id!=null)
						{
							for($i=0;$i<$vehicle_cnt;$i++)
							{
								if($vehicleid[$i]==$vehicle_id)
								{
									break;
								}
							}			
							if($i>=$vehicle_cnt)
							{
								$vehicleid[$vehicle_cnt]=$vehicle_id;
								$vehicle_cnt++;
								$td_cnt++;
								$query="SELECT vehicle_id FROM station_assignment WHERE vehicle_id='$vehicle_id' AND status='1'";
								//echo "query=".$query;
								$result=mysql_query($query,$DbConnection);
								$num_rows=mysql_num_rows($result);
								//if($num_rows==0)
								{							
									common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$AccountNode->data->AccountGroupName);
								}
								if($td_cnt==3)
								{
									$td_cnt=0;
								}
							}
						}
					}
				}
				$ChildCount=$AccountNode->ChildCnt;
				for($i=0;$i<$ChildCount;$i++)
				{ 
					get_user_vehicle($AccountNode->child[$i],$account_id);
				}
			}			
?>  
