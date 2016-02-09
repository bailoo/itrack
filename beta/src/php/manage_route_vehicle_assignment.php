<?php
    //error_reporting(-1);
    //ini_set('display_errors', 'On');
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	
	//include_once('manage_hierarchy_header1.php');
	$root=$_SESSION['root'];
	//$common_id1=$_POST['common_id'];
	//$shift1 = $_POST['shift'];
	//echo "common_id=".$common_id1;
	//echo "route_vehicle##";		
	$common_id1=$account_id;
	$vname_assigned_ev = array();
	$route_assigned_ev = array();
	$update_time_ev = array();
	$remark_assigned_ev = array();
	$user_id_ev = array();

	$vname_assigned_mor = array();
	$route_assigned_mor = array();
	$update_time_mor = array();
	$remark_assigned_mor = array();
	$user_id_mor = array();
		
	//EVENING SHIFT	
	$data_ev = getDetailAllRouteAssignment2($account_id,$DbConnection);
	//print_r($data_ev);
	foreach($data_ev as $dt)
	{
		$vNameE=$dt['vname_assigned_ev'];		
		$user_id_tmp = getUserID($account_id,1,$DbConnection);
		$vname_assigned_ev[$vNameE] = $dt['route_assigned_ev']."*".$dt['update_time_ev']."*".$dt['remark_assigned_ev']."*".$dt['edit_id']."*".$user_id_tmp;
	}	


	$data_mr = getDetailAllRouteAssignment2Next($account_id,$DbConnection);
	
	print_r($data_mr);
        exit;
	foreach($data_mr as $dt)
	{		
		$vNameM = $dt['vname_assigned_mor'];
		$user_id_tmp = getUserID($account_id,1,$DbConnection);
		$vname_assigned_mor[$vNameM] = $dt['route_assigned_mor']."*".$dt['update_time_mor']."*".$dt['remark_assigned_mor']."*".$dt['edit_id']."*".$user_id_tmp;
	}	
	
	//GET ALL ROUTES
	$route_input = array();
	get_route_detail($account_id, "ZPME");
	get_route_detail($account_id, "ZPMM");
	
	$route_input1 = array_unique($route_input);	
	
	echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';
	echo'<input type="hidden" id="shift" value='.$shift1.'>';
	
	echo "<center><h3>Assign Route</h3><br></center>";
	echo"
			<form name='manage1' method='post' target='_blank' action='src/php/action_manage_shift_download.php'>					
			<input type='hidden' id='excel_morning_shift' name='excel_morning_shift'>	
			<input type='hidden' id='excel_evening_shift' name='excel_evening_shift'>			
			<table width='100%'>						
				<tr>
					<td align='center'>			
					<div style='overflow:auto;height:400px;width:950px;'> 	
					<table border=1 cellspacing=4 cellpadding=4 class='module_left_menu' rules='all' bordercolor='gray'>";
						echo '<tr bgcolor="#d3d3d3" align="left">
						<td></td>
						<td colspan=4 align=left><font color=red><strong>Evening Shift</strong></font></td>
						<td colspan=4 align=left><font color=red><strong>Morning Shift</strong></font></td></tr>
						<tr bgcolor="#d3d3d3"><td><strong>Vehicle</strong></td>
						<td><strong>Route(E)</strong></td>						
						<td><strong>Remark(E)</strong></td>
						<td><strong>UpdateTime(E)</strong></td>
						<td><strong>UpdatedBy</strong></td>
						<td><strong>Route(M)</strong></td>						
						<td><strong>Remark(M)</strong></td>
						<td><strong>UpdateTime(M)</strong></td>
						<td><strong>UpdatedBy</strong></td>
						</tr>';
						get_user_vehicle($root,$common_id1);
				 	echo'</table>
				</div>
				</td>
			</tr>
		</table>
		<center><br>
			<input type="button" id="enter_button" name="enter_button" Onclick="javascript:return action_manage_route2(\'assign\')" value="Assign">
			&nbsp;<input type="reset" value="Cancel">
			<input type="button" id="enter_button" name="enter_button" Onclick="javascript:return action_manage_mor_shift(\'mor_shift\')" value="Download Morning Sift">
			<input type="button" id="enter_button" name="enter_button" Onclick="javascript:return action_manage_ev_shift(\'ev_shift\')" value="Download Evening Sift">
		<!--<br><a href="javascript:show_option(\'manage\',\'route\');" class="back_css">&nbsp;<b>Back</b></a>-->
		</center>
	</form>';	
			
	//############ FUNTION ROUTE DETAIL ####################
	function get_route_detail($account_id, $shift_time)
	{
		//echo "\nAccount_id=".$account_id." ,shift_time=".$shift_time;			
		global $route_input;
	
		//$dir = "c:\\gps_report/231/master";
		//$dir = "c:\\halt2/test_master";
		

		//$dir = "C:\\Program Files/Apache Software Foundation/Apache2.2/htdocs/vts_beta_new/src/php/gps_report/".$account_id."/master";
		//$dir = "C:\\xampp/htdocs/beta/src/php/gps_report/".$account_id."/master";
		
                $dir = "/var/www/html/vts/beta/src/php/gps_report/".$account_id."/master";
		//echo "dir=".$dir."<br>";
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
		//closedir($dh);
	} //function closed			
	
	//############ FUNCTION ROUTE CLOSED ########################
	
	//############ FUNCTION VEHICLE DETAIL ##################

	$alternate_row = 1; //light blue
	function common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$option_name)
	{
		//$td_cnt++;
		//####### ROUTE MATCHED EVENING
		global $alternate_row;
		global $td_cnt;
		global $vname_assigned_ev;
			

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
		
		//$found_ev = 0;
		$route_matched_ev = "";
		$remark_matched_ev = "";
		
		if($vname_assigned_ev[$vehicle_name]!="")
		{
			//echo "eveningStr=".$vname_assigned_ev[$vehicle_name]."<br>";
			$evStr=explode("*",$vname_assigned_ev[$vehicle_name]);
			$found_ev = 1;
			$route_matched_ev = $evStr[0];
			$update_time_ev1 = $evStr[1];
			$remark_matched_ev = $evStr[2];
			//echo "remark_matched_ev=".$remark_matched_ev."<br>";
			$user_id_ev1 = $evStr[4];
		}
				
		//####### ROUTE MATCHED MORNING
		global $vname_assigned_mor;
	

		//$found_ev = 0;
		$route_matched_mor = "";
		$remark_matched_mor = "";
		
		if($vname_assigned_mor[$vehicle_name]!="")
		{
			//echo "str=".$vname_assigned_mor[$vehicle_name]."<br>";
			$morStr=explode("*",$vname_assigned_mor[$vehicle_name]);
			$found_mor = 1;
			$route_matched_mor = $morStr[0];
			$update_time_mor1 = $morStr[1];
			if($update_time_mor1=="1") 
			{
				$update_time_mor1="";
			}
			$remark_matched_mor = $morStr[2];
			$user_id_mor1 = $morStr[4];		
		}
		
				
		//date_default_timezone_set('Asia/Calcutta');
		$current_date = date('Y-m-d');
		$xml_file = "../../../xml_vts/xml_data/".$current_date."/".$vehicle_imei.".xml";
	
		//if(file_exists($xml_file))
		//{		
			echo'
			<tr bgcolor="'.$row_color.'">
			<td align="left"><strong>'.$vehicle_name.'</strong></td>';
				
				//################### EVENING COLUMN #######################
				echo '<td align="left">';				
				if(($found_ev) && ($route_matched_ev!=""))
				{
					//echo "in if evening=".$cnt."<br>";
					//echo '<font color="green"><strong>'.$route_matched.'</strong></font>';
					echo '<input type="hidden" name="excel_assigned_ev_vname[]" value="'.$vehicle_name.'"/>';
					echo '<input type="hidden" name="download_excel_assigned_ev_shift[]" value="'.$route_matched_ev.'"/>';
					echo '<input type="hidden" name="download_excel_update_time_ev[]" value="'.$update_time_ev1.'"/>';
					echo '<input type="hidden" name="unassigned_vehicles[]" value="'.$vehicle_id.'"/>';
					echo '<input type="hidden" name="excel_assigned_ev_userid[]" value="'.$user_id_ev1.'"/>';
					
					
					$text_content1 =$vehicle_id."text_content";
					echo '<input type="text" name="excel_assigned_ev_shift[]" onKeyUp="getScriptPage1(\'box\',\'text_content\',\''.$vehicle_id.'\',this.value)" id="'.$text_content1.'" size="20" name="veh_name" value="'.$route_matched_ev.'" style="background-color:#EDEBEC;">';
						$box1_id =$vehicle_id."box";
						//echo "id=".$box1_id."<br>";
					echo '<div id="'.$box1_id.'" class="input-div-route" style="display:none"></div>';							
				}
				else
				{						
					echo '<input type="hidden" name="unassigned_vehicles[]" value="'.$vehicle_id.'"/>';
					
					$text_content1 =$vehicle_id."text_content";
					echo '<input type="text" name="excel_assigned_ev_shift[]" onKeyUp="getScriptPage1(\'box\',\'text_content\',\''.$vehicle_id.'\',this.value)" id="'.$text_content1.'" size="20" name="veh_name" style="background-color:#FFFFFF;">';
						$box1_id =$vehicle_id."box";
						//echo "id=".$box1_id."<br>";
					echo '<div id="'.$box1_id.'" class="input-div-route" style="display:none"></div>';

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
				$rem = $vehicle_id."rem";
				echo'</td>';

				if(($found_ev) && ($remark_matched_ev!=""))
				{	
						echo '<input type="hidden" name="excel_assigned_ev_remark[]" value="'.$remark_matched_ev.'"/>';					
						echo '<td><input type="text" id="'.$rem.'" size="20" value="'.$remark_matched_ev.'" style="background-color:#EDEBEC;"></td>';
				}
				else
				{
					echo '<td><input type="text" id="'.$rem.'" size="20" value="'.$remark_matched_ev.'" style="background-color:#FFFFFF;"></td>';
				}
				echo '<td>'.$update_time_ev1.'</td>';
				echo '<td>'.$user_id_ev1.'</td>';
				//#### EVENING COLUMN CLOSED
				
				//##################### MORNING COLUMN #########################
				echo '<td align="left">';
				
				if($found_mor && $route_matched_mor!="")
				{					
					echo '<input type="hidden" name="unassigned_vehicles[]" value="'.$vehicle_id.'"/>';
					echo '<input type="hidden" name="excel_assigned_mor_vname[]" value="'.$vehicle_name.'"/>';
					echo '<input type="hidden" name="download_excel_assigned_mor_shift[]" value="'.$route_matched_mor.'"/>';
					echo '<input type="hidden" name="download_excel_update_time_mor[]" value="'.$update_time_mor1.'"/>';	
					echo '<input type="hidden" name="excel_assigned_mor_userid[]" value="'.$user_id_mor1.'"/>';
					
					$text_content2 =$vehicle_id."text_content2";
					echo '<input type="text"  name="excel_assigned_mor_shift[]" onKeyUp="getScriptPage2(\'box2\',\'text_content2\',\''.$vehicle_id.'\',this.value)" id="'.$text_content2.'" size="20" name="veh_name2" value="'.$route_matched_mor.'" style="background-color:#EDEBEC;">';
						$box2_id =$vehicle_id."box2";
						//echo "id=".$box1_id."<br>";
					echo '<div id="'.$box2_id.'" class="input-div-route" style="display:none"></div>';							
				}
				else
				{											
					echo '<input type="hidden" name="unassigned_vehicles[]" value="'.$vehicle_id.'"/>';
					$text_content2 =$vehicle_id."text_content2";
					echo '<input type="text" name="excel_assigned_mor_shift[]" onKeyUp="getScriptPage2(\'box2\',\'text_content2\',\''.$vehicle_id.'\',this.value)" id="'.$text_content2.'" size="20" name="veh_name2" style="background-color:#FFFFFF;">';
						$box2_id =$vehicle_id."box2";
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
				$rem2 = $vehicle_id."rem2";
				echo'</td>';
				//echo "found_mor=".$found_mor."remark_matched_mor=".$remark_matched_mor."<br>";
				if(($found_mor) && ($remark_matched_mor!=""))
				{	
					echo '<input type="hidden" name="excel_assigned_mor_remark[]" value="'.$remark_matched_mor.'"/>';
					echo '<td><input type="text" id="'.$rem2.'" size="20" value="'.$remark_matched_mor.'" style="background-color:#EDEBEC;"></td>';				
				}
				else
				{
					//echo '<input type="text" name="excel_assigned_vname[]" value="'.$vehicle_name.'"/>';
					//echo '<input type="text" name="excel_morning_shift[]" value="'.$remark_matched_mor.'"/>';	
					echo '<td><input type="text" id="'.$rem2.'" size="20" value="'.$remark_matched_mor.'" style="background-color:#FFFFFF;"></td>';				
				}
				//##### MORNING COLUMNS CLOSED
				
			   echo '</td>
			   <td>'.$update_time_mor1.'</td>
			   <td>'.$user_id_mor1.'</td>
			</tr>
			';
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
													
						common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$AccountNode->data->AccountGroupName);
						
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
