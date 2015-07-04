<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include('manage_route_vehicle_substation_inherit.php');
	
	$root=$_SESSION['root'];
	//$common_id1=$_POST['common_id'];
	$common_id1=$account_id;
	
	//*****************************************Getting Admin Account ID and Current UserID*******************************************//	
	global $parent_admin_id;
	$account_admin_id =getAccountAdminId($account_id,$DbConnection);
	$parent_admin_id=getAccountIdByAdminId($account_admin_id,$DbConnection);
	
	global $user_name;	
	$user_name=getUserID($account_id,1,$DbConnection);
	//echo  $user_name;	
	/*******************************************************************************************************************************/
	
	$vname_assigned_ev = array();
	$route_assigned_ev = array();
	$remark_assigned_ev = array();

	$vname_assigned_mor = array();
	$route_assigned_mor = array();
	$remark_assigned_mor = array();
		
	//EVENING SHIFT
	
	$dataEV = getDetailAllRouteAssignment2VSA($parent_admin_id,$DbConnection);
	foreach($dataEV as $dt)
	{
			
		$vname_assigned_ev[] = $dt['vname_assigned_ev'];
		$route_assigned_ev[] = $dt['route_assigned_ev'];
		$remark_assigned_ev[] = $dt['remark_assigned_ev'];
	}	

	//MORNING SHIFT		
	$dataMR = getDetailAllRouteAssingment2VRR($parent_admin_id,$DbConnection);
	foreach($dataMR as $dt)
	{
		$vname_assigned_mor[] = $dt['vname_assigned_mor'];
		$route_assigned_mor[] = $dt['route_assigned_mor'];
		$remark_assigned_mor[] =$dt['remark_assigned_mor'];
	}	
	
	//GET ALL ROUTES
	$route_input_ZPME = array();
	$route_input_ZPMM = array();
	get_route_detail($parent_admin_id, "ZPME",$user_name);
	get_route_detail($parent_admin_id, "ZPMM",$user_name);
	
	$route_input_eve = array_unique($route_input_ZPME);	
	$route_input_mor = array_unique($route_input_ZPMM);	
	//class called
	$get_table=new manage_route_vehicle_substation_inherit();
	
	$vehicle_list=array();
	get_user_vehicle($root,$account_id);
	//print_r($vehicle_list);
	$vehicle_list1 = array_unique($vehicle_list);	
	$final_vehicle_list=array();
	$all_vehicles = "";
	//$v2 = " DL12345";
	//$v3 = trim($v2);
	//echo "v3=".$v3;
	foreach($vehicle_list1 as $vl){
		//$final_vehicle_list[]=$vl;
		//echo "<br>vl=".$vl."<br>"; 
		$all_vehicles.= trim($vl).",";
		//echo "all_vehicles=".$all_vehicles."<br>";
	}
	$all_vehicles = substr($all_vehicles, 0, -1);
	//$all_vehicles = trim($all_vehicles);
	$all_vehicles = str_replace(' ','%20',$all_vehicles);
	//print_r($all_vehicles);
	echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';
	echo'<input type="hidden" id="shift" value='.$shift1.'>';
	echo'<input type="hidden" id="parent_admin_id_hidden" value='.$parent_admin_id.'>';
	echo'<input type="hidden" id="vehicle_list_hidden" value='.$all_vehicles.'>';
	echo "<center><h3>Assign Route</h3><br></center>";
	echo"
			<form name='manage1' method='post' target='_blank' action='src/php/action_manage_shift_download.php'>					
				<center>
					<input type='radio' id='block_sel' name='block_sel' onclick='javascript:show_route_block(this.value)' value='1'/>Evening
					<input type='radio' id='block_sel' name='block_sel' onclick='javascript:show_route_block(this.value)' value='2'/>Morning
					<br>
					<table width='auto' align=center>						
						<tr>
							<td align='center'>	
								<div id='evening_block' style='display:none;'>";
								$tableid="t1";
								$routedetails=$route_input_eve;
								$type="EveningShift";	
								
								//echo $get_table->display_shift_table($tableid,$routedetails,$type);
								$arr1 = $get_table->display_shift_table($tableid,$routedetails,$type,$parent_admin_id,$vehicle_list1);
								//hidden string send
								$hiddenarray1="";
								//print_r($arr1);
								foreach($arr1 as $a1){
									$hiddenarray1.=$a1['ROUTE'].":".$a1['VEHICLE'].":0".",";
									//echo $hiddenarray1."<br>";
								}
								$hiddenarray1 = str_replace(' ','%20',$hiddenarray1);
								echo'<input type="hidden" id="arr1" value="'.$hiddenarray1.'">';
								
								echo"
								<table width='100%'>						
											<tr>
												<td align='center'>			
												<div style='overflow:auto;height:400px;width:580px;'> 	
												<table border=1 cellspacing=4 cellpadding=4 class='module_left_menu' rules='all' bordercolor='gray' id=".$tableid."
													<tr bgcolor='#d3d3d3' align='left'>								
														<td colspan=4 align=left><font color=red><strong>".$type."</strong></font></td>	
													</tr>
													<tr bgcolor='#d3d3d3'>									
														<td><strong>Route</strong></td>
														<td><strong>Vehicle</strong></td>
														<td><strong>Updation</strong></td>
													</tr>";								
							common_function_for_route($arr1,'t1');
													
							echo"</table>
											</div>
											</td>
										</tr>
									</table>
							";
							
								
							echo"
							</div></td>
							<td align='center'>			
								<div id='morning_block' style='display:none;'>							
							";
								$tableid="t2";
								$routedetails=$route_input_mor;
								$type="MorningShift";	
								
								//echo $get_table->display_shift_table($tableid,$routedetails,$type);
								$arr2 =$get_table->display_shift_table($tableid,$routedetails,$type,$parent_admin_id,$vehicle_list1);
								$hiddenarray2="";
								foreach($arr2 as $a2){
									$hiddenarray2.=$a2['ROUTE'].":".$a2['VEHICLE'].":0".",";
								}
								$hiddenarray2 = str_replace(' ','%20',$hiddenarray2);
								echo'<input type="hidden" id="arr2" value="'.$hiddenarray2.'">';
									echo"<table width='100%'>						
												<tr>
													<td align='center'>			
													<div style='overflow:auto;height:400px;width:580px;'> 	
													<table border=1 cellspacing=4 cellpadding=4 class='module_left_menu' rules='all' bordercolor='gray' id=".$tableid."
														<tr bgcolor='#d3d3d3' align='left'>								
															<td colspan=4 align=left><font color=red><strong>".$type."</strong></font></td>	
														</tr>
														<tr bgcolor='#d3d3d3'>									
															<td><strong>Route</strong></td>
															<td><strong>Vehicle</strong></td>
															<td><strong>Updation</strong></td>
														</tr>";								
								common_function_for_route($arr2,'t2');
														
								echo"</table>
												</div>
												</td>
											</tr>
										</table>
								";
								
								//print_r($arr2);
							echo'
							</div></td>
					</tr>
				</table>
				</center>
				<center><br>
					<input type="button" id="enter_button" name="enter_button" Onclick="javascript:return action_manage_route2_substation(\'assign\')" value="Assign">
					&nbsp;<input type="reset" value="Cancel">	
		
				</center>
			</form>';	
			
	//############ FUNTION ROUTE DETAIL ####################
	function get_route_detail($account_id, $shift_time,$user_name)
	{	
		global $route_input_ZPME;
		global $route_input_ZPMM;
		
		$dir = "/var/www/html/vts/beta/src/php/gps_report/".$account_id."/master";		
		$dh = opendir($dir);
		while (($file = readdir($dh)) !== false) {			
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
								if($data[5]==$user_name){
								$route_input_ZPME[] = $data[6];
								}								
							}
						}
						fclose($handle);						
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
								if($data[5]==$user_name){
									$route_input_ZPMM[] = $data[6];
								}								
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

//	$alternate_row = 1; //light blue
	//function common_function_for_route($vehicle_imei,$vehicle_id,$vehicle_name,$option_name)
	function common_function_for_route($final_data,$type)
	{
		//$td_cnt++;
		$current_date = date('Y-m-d');
		if($type=="t1")
		{
			//####### ROUTE MATCHED EVENING
			global $alternate_row;
			global $td_cnt;
			global $vname_assigned_ev;
			global $route_assigned_ev;		
			global $remark_assigned_ev;	

			$alternate_row = 1;
			
			
			//$found_ev = 0;
			$route_matched_ev = "";
			$remark_matched_ev = "";

			foreach ($final_data as $fdata)
			{		
				if($alternate_row)
				{
					$row_color = "#FFFFFF";
					$alternate_row = 0;
				}
				else
				{
					$row_color = "#C3E2FF";		
					$alternate_row = 1;
				}
				if(trim($fdata['ROUTE'])!="")
				{
					$found_ev = 1;
					$vehicle_matched_ev = $fdata['VEHICLE'];
					$updatetime_ev = str_replace('#',':',$fdata['UPDATETIME']);
				}


				echo'
				<tr bgcolor="'.$row_color.'">
				<td align="left"><strong>'.$fdata['ROUTE'].'</strong></td>';
					
					//################### EVENING COLUMN #######################
					echo '<td align="left">';				
					if(($found_ev) && ($vehicle_matched_ev!=""))
					{
						//echo "in if evening=".$cnt."<br>";
						//echo '<font color="green"><strong>'.$route_matched.'</strong></font>';
						echo '<input type="hidden" name="excel_assigned_ev_vname[]" value="'.$fdata['ROUTE'].'"/>';
						echo '<input type="hidden" name="download_excel_assigned_ev_shift[]" value="'.$vehicle_matched_ev.'"/>';
						echo '<input type="hidden" name="unassigned_route_'.$type.'[]" value="'.$fdata['ROUTE'].'"/>';
						
						
						$text_content1 =$fdata['ROUTE']."text_content1";
						//echo $text_content1;
						echo '<input type="text" name="excel_assigned_ev_shift[]" onKeyUp="getScriptPage1_substation(\'box\',\'text_content\',\''.$fdata['ROUTE'].'\',this.value)" id="'.$text_content1.'" size="70" name="veh_name" value="'.$vehicle_matched_ev.'" style="background-color:#EDEBEC;" onpaste="return false;"
 >';
							$box1_id =$fdata['ROUTE']."box";
							//echo "id=".$box1_id."<br>";
						echo '<div id="'.$box1_id.'" class="input-div-route" style="display:none"></div>';							
					}
					else
					{						
						echo '<input type="hidden" name="unassigned_route_'.$type.'[]" value="'.$fdata['ROUTE'].'"/>';
						
						$text_content1 =$fdata['ROUTE']."text_content1";
						echo '<input type="text" name="excel_assigned_ev_shift[]" onKeyUp="getScriptPage1_substation(\'box\',\'text_content\',\''.$fdata['ROUTE'].'\',this.value)" id="'.$text_content1.'" size="70" name="veh_name" style="background-color:#FFFFFF;" onpaste="return false;"
>';
							$box1_id =$fdata['ROUTE']."box";
							//echo "id=".$box1_id."<br>";
						echo '<div id="'.$box1_id.'" class="input-div-route" style="display:none"></div>';

						//echo '<SELECT id="'.$vehicle_id.'">';
						//echo '<option value="0">Select</option>';
						//if(sizeof($route_input1)>0)					
						//{								
						//}
						//echo '</SELECT>';
					}
					//$rem = $vehicle_id."rem";
					echo'</td>';
					if($updatetime_ev < $current_date." 12:00:00")
					{
						echo '<td><input type="checkbox" id="'.$text_content1.'_time_ev"></td>';
					}
					else
					{
						//echo '<td>UPDT='.$updatetime_ev.'<input type="hidden" id="update_time_ev[]">CD='.$current_date.' 12:00:00</td>';
						echo '<td><input type="hidden" id="'.$text_content1.'_time_ev"></td>';
					}
					//#### EVENING COLUMN CLOSED
				echo "</tr>";	
			}
		}
		else if($type=="t2")
		{
			//####### ROUTE MATCHED MORNING
			global $vname_assigned_mor;
			global $route_assigned_mor;		
			global $remark_assigned_mor;

			//$found_ev = 0;
			$route_matched_mor = "";
			$remark_matched_mor = "";
			$alternate_row =1;
			foreach ($final_data as $fdata)
			{
				if($alternate_row)
				{
					$row_color = "#FFFFFF";
					$alternate_row = 0;
				}
				else
				{
					$row_color = "#C3E2FF";		
					$alternate_row = 1;
				}			
				if(trim($fdata['ROUTE'])!="")
				{
					$found_mor = 1;
					$vehicle_matched_mor = $fdata['VEHICLE'];
					$updatetime_mor = str_replace('#',':',$fdata['UPDATETIME']);
				}
				//##################### MORNING COLUMN #########################
				echo'
				<tr bgcolor="'.$row_color.'">
				<td align="left"><strong>'.$fdata['ROUTE'].'</strong></td>';

				echo '<td align="left">';
				if($found_mor && $vehicle_matched_mor!="")
				{					
					echo '<input type="hidden" name="unassigned_route_'.$type.'[]" value="'.$fdata['ROUTE'].'"/>';
					echo '<input type="hidden" name="excel_assigned_mor_vname[]" value="'.$fdata['ROUTE'].'"/>';
					echo '<input type="hidden" name="download_excel_assigned_mor_shift[]" value="'.$vehicle_matched_mor.'"/>';	
					$text_content2 = trim($fdata['ROUTE']."text_content2");
					//echo $text_content2;
					echo '<input type="text"  name="excel_assigned_mor_shift[]" onKeyUp="getScriptPage2_substation(\'box2\',\'text_content\',\''.$fdata['ROUTE'].'\',this.value)" id="'.trim($text_content2).'" size="70" name="veh_name2" value="'.$vehicle_matched_mor.'" style="background-color:#EDEBEC;" onpaste="return false;"
 >';
						$box2_id =$fdata['ROUTE']."box2";
						//echo "id=".$box1_id."<br>";
					echo '<div id="'.$box2_id.'" class="input-div-route" style="display:none"></div>';							
				}
				else
				{											
					echo '<input type="hidden" name="unassigned_route_'.$type.'[]" value="'.$fdata['ROUTE'].'"/>';
					$text_content2 = trim($fdata['ROUTE']."text_content2");
					echo '<input type="text" name="excel_assigned_mor_shift[]" onKeyUp="getScriptPage2_substation(\'box2\',\'text_content22\',\''.$fdata['ROUTE'].'\',this.value)" id="'.trim($text_content2).'" size="70" name="veh_name2" style="background-color:#FFFFFF;" onpaste="return false;"
 >';
						$box2_id =$fdata['ROUTE']."box2";
						//echo "id=".$box1_id."<br>";
					echo '<div id="'.$box2_id.'" class="input-div-route" style="display:none"></div>';

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
				}
				
				echo'</td>';
				//echo "<br>".$updatetime_mor." $$ ".$current_date." 03:00:00";
				if($updatetime_mor < $current_date." 03:00:00")
				{
					echo '<td><input type="checkbox" id="'.$text_content2.'_time_mor"></td>';
				}
				else
				{
					echo '<td><input type="hidden" id="'.$text_content2.'_time_mor"></td>';
				}
			
				//echo "found_mor=".$found_mor."remark_matched_mor=".$remark_matched_mor."<br>";				
				//##### MORNING COLUMNS CLOSED
				
			   echo '</td>
			</tr>
			';
			}
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
		//echo "hi".$account_id;
		global $vehicleid;
		global $vehicle_cnt;
		global $td_cnt;
		global $DbConnection;
		global $vehicle_list;
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
						
						$num_rows=getNumRowSecVechicle($vehicle_id,$DbConnection);
						//if($num_rows==0)
						if($num_rows)
						{							
							//common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$AccountNode->data->AccountGroupName);
							//$vehicle_list[]=$vehicle_name;
						}
						else
						{
							$vehicle_list[]=$vehicle_name;
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
