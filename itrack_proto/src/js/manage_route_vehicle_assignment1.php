<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	//include_once('manage_hierarchy_header1.php');
	$root=$_SESSION['root'];
	$common_id1=$_POST['common_id'];
	$shift1 = $_POST['shift'];
	//echo "common_id=".$common_id1;
	echo "route_vehicle##";
	
	$shift_name = "";
	if($shift1 == "1")
	{
		//echo "EVENING SHIFT";
		$shift_name = "Evening Shift";
		$query_assigned = "SELECT vehicle_name,route_name,remark FROM route_assignment2 WHERE user_account_id='$account_id' AND status=1";	
		$result_assigned = mysql_query($query_assigned,$DbConnection);
	}
	else if($shift1 == "2")
	{
		//echo "MORNING SHIFT";
		$shift_name = "Morning Shift";
		$query_assigned = "SELECT vehicle_name,route_name,remark FROM route_assignment3 WHERE user_account_id='$account_id' AND status=1";	
		$result_assigned = mysql_query($query_assigned,$DbConnection);
	}
	
	$vname_assigned = array();
	$route_assigned = array();
	$remark_assigned = array();
	
	while($row=mysql_fetch_object($result_assigned))
	{
		$vname_assigned[] = $row->vehicle_name;
		$route_assigned[] = $row->route_name;
		$remark_assigned[] = $row->remark;
	}
	
	$route_input = array();
	get_route_detail($account_id, "ZPME");
	get_route_detail($account_id, "ZPMM");
	
	$route_input1 = array_unique($route_input);	
	
	echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';
	echo'<input type="hidden" id="shift" value='.$shift1.'>';
	
	echo"			
			<form name='manage1' method='post'>
			<table width='100%'>				
				<tr>
					<td align='center'>			
					<div style='overflow:auto;height:400px;width:670px;'> 	
					<table border=1 cellspacing=4 cellpadding=4 class='module_left_menu' rules='all' bordercolor='gray'>";
						echo "<tr><td colspan=3 align=left><font color=red><strong>".$shift_name."</strong></font></td></tr>";
						echo "<tr><td><strong>Vehicle</strong></td><td><strong>Route</strong></td><td><strong>Remark</strong></td></tr>";
						get_user_vehicle($root,$common_id1);
				 	echo'</table>
				</div>
				</td>
			</tr>
		</table>
		<center><br>
			<input type="button" id="enter_button" name="enter_button" Onclick="javascript:return action_manage_route2(\'assign\')" value="Assign">&nbsp;<input type="reset" value="Cancel">
		<br><a href="javascript:show_option(\'manage\',\'route\');" class="back_css">&nbsp;<b>Back</b></a>
		</center>
	</form>';	
			
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
			echo'
			<tr>
			<td align="left">'.$vehicle_name.'</td>
			<td align="left">';
				
				if($found)
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
				}
				$rem = $vehicle_id."rem";
				echo'</td>	
				<td>&nbsp;<input type="text" id="'.$rem.'" size="20" value="'.$remark_matched.'"></td>
			   </td>
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
