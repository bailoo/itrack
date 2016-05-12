<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  include_once('user_type_setting.php');
  //include_once('common_xml_element.php');
  //print_r($root);
  //echo "<br>z=".$root->data->AccountID;
  //casandra------
  include_once("../../../phpApi/Cassandra/Cassandra.php");     //##### INCLUDE CASSANDRA API
  include_once("../../../phpApi/libLog.php");     //##### INCLUDE CASSANDRA API*/
 //include_once("util_casandra_call.php");
  $o_cassandra = new Cassandra();	
  $o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);

  
  $common_id1=$account_id;
  
  $query="SELECT DISTINCT device_imei_no from device_assignment where account_id='$account_id' and status='1'";
  //echo $query;
  $result=mysql_query($query,$DbConnection);
   function common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$option_name)
	{	
		//casandra
                global $o_cassandra;
                //$td_cnt++;
		global $td_cnt;
		if($td_cnt==1)
		{
			echo'<tr>';
		}
		
		//date_default_timezone_set('Asia/Calcutta');
		$current_date = date('Y-m-d');
                /*
                 * old code
		$xml_file = "../../../xml_vts/xml_data/".$current_date."/".$vehicle_imei.".xml";
		//====code updated on 13032015=============//
		include("/var/www/html/vts/beta/src/php/common_xml_path.php");
		$xml_file =$xml_data."/".$current_date."/".$vehicle_imei.".xml";
                */
                //$vehicle_imei= '861074026115546';
                
               $logResult=hasImeiLogged($o_cassandra, $vehicle_imei, $current_date);
                if($logResult!='')
                {
                    $vehicle_active_flag=1;
                }
                 //$vehicle_active_flag=1;
		//========XXXXXXXXXXXXXXXXXXXXXXX===========//
		//if(file_exists($xml_file))
                if($vehicle_active_flag==1)
		{		
		echo'<td align="left">&nbsp;<INPUT TYPE="radio"  name="vehicleserial_radio" VALUE="'.$vehicle_imei.'"></td>
			   <td class=\'text\'>&nbsp;
				 <font color="darkgreen">'.$vehicle_name.'</font>
		
			   </td>';
		}
		else
		{
			echo'<td align="left">&nbsp;
					<INPUT TYPE="radio"  name="vehicleserial_radio" VALUE="'.$vehicle_imei.'">
				</td>
				<td class=\'text\'>
				  <font color="grey">'.$vehicle_name.'</font>
					
				</td>';
		}
		if($td_cnt==3)
		{ 
			echo'</tr>';
		}

	}
  function common_function_for_vehicle_old($vehicle_imei,$vehicle_id,$vehicle_name,$option_name)
	{	
		//$td_cnt++;
		global $td_cnt;
		if($td_cnt==1)
		{
			echo'<tr>';
		}
		
		//date_default_timezone_set('Asia/Calcutta');
		$current_date = date('Y-m-d');

		$xml_file = "../../../xml_vts/xml_data/".$current_date."/".$vehicle_imei.".xml";
		//====code updated on 13032015=============//
		include("/var/www/html/vts/beta/src/php/common_xml_path.php");
		$xml_file =$xml_data."/".$current_date."/".$vehicle_imei.".xml";
		
		//========XXXXXXXXXXXXXXXXXXXXXXX===========//
		if(file_exists($xml_file))
		{
		//echo"in";
		echo'<td align="left">&nbsp;<INPUT TYPE="radio"  name="vehicleserial_radio" VALUE="'.$vehicle_imei.'"></td>
			   <td class=\'text\'>&nbsp;
				 <font color="darkgreen">'.$vehicle_name.'</font>
		
			   </td>';
		}
		else
		{
			echo'<td align="left">&nbsp;
					<INPUT TYPE="radio"  name="vehicleserial_radio" VALUE="'.$vehicle_imei.'">
				</td>
				<td class=\'text\'>
				  <font color="grey">'.$vehicle_name.'</font>
					
				</td>';
		}
		if($td_cnt==3)
		{ 
			echo'</tr>';
		}

	}

			function get_user_vehicle($AccountNode,$account_id)
			{
				//echo"in=".$account_id;
				//echo "<br>a=".$AccountNode->data->AccountID;
				global $vehicleid;
				global $vehicle_cnt;
				global $td_cnt;
				global $DbConnection;
				if($AccountNode->data->AccountID==$account_id)
				{
					$td_cnt =0;
					for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
                                        //for($j=0;$j<2;$j++)
					{			    
						$vehicle_id = $AccountNode->data->VehicleID[$j];
						$vehicle_name = $AccountNode->data->VehicleName[$j];
						$vehicle_imei = $AccountNode->data->DeviceIMEINo[$j];
						//echo "VI=".$vehicle_imei;
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
								/*$query="SELECT vehicle_id FROM polyline_assignment WHERE vehicle_id='$vehicle_id' AND status='1'";
								//echo "query=".$query;
								$result=mysql_query($query,$DbConnection);
								$num_rows=mysql_num_rows($result);
								if($num_rows==0)
								{	*/						
									common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$AccountNode->data->AccountGroupName);
								//}
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
<div id="blackout"></div>
<div id="divpopupmap">

		<a href="#" onclick="toggle_visibility('foo');" style="text-decoration:none">Route from History <span id='for_polyline_name'></span> </a>
        <div id="foo" style='display:none;'>
			<table table width=100% align=center>
				<tr>
					<td align=center><span>Select Vehicle<a href="#" onclick="toggle_visibility('foo');" style="text-decoration:none"> | Hide</a></span></td>
				</tr>
				<tr>
					<td>
						<?php
			
							 echo'<br>
												
							  <input type="hidden" id="common_id">
							  <input type="hidden" id="action_name" value="device">
							  <table border=0 width="100%">
								<tr>
								  <td>
									<table align="center" width="70%">
										<tr>
											<td>
									<fieldset class=\'assignment_manage_fieldset\'>
									<legend>
									  <strong>Device IMEI No</strong>
									</legend>
											<div style="height:200px;overflow:auto;width:100%">
												<table border="0" class="manage_interface" bgcolor=ghostwhite align="center">
												
												'; //echo "caling"; 
												//echo "<br>c=".$root->data->AccountID;
													//echo "<br>comm=".$common_id1;
												get_user_vehicle($root,$common_id1);
                                                                                                $o_cassandra->close();
                                                                                                echo'
												</table>
												
											</div>
											</fieldset>
											</td>
										</tr>
									</table>
										</td>
									</tr>
								</table>';
							?>
					</td>
				</tr>
				<tr>
					<td align=center>
						<table width=50%>
						<tr>
							<td>
								StartDate:<input type="text" id="date1" name="start_date"  size="18" maxlength="19" />
							</td>
							<td><a href=javascript:NewCal_SD("date1","yyyymmdd",true,24)>
								<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
								</a>
							</td>
							<td>
								EndDate:<input type="text" id="date2" name="end_date"  size="18" maxlength="19" />
							</td>
							<td>
								<a href=javascript:NewCal("date2","yyyymmdd",true,24)>
								<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
								</a>
							</td>
						</tr>
						<tr>
							<td colspan=2>
								<table class="module_left_menu" width="100%">
									<tr>
										<td class="module_select_track1">Interval</td>
										<td class="module_select_track2" align="center">:</td>
										<td>
											<select name='interval' name='id' style="width:78%">
												<option value='auto'>Auto</option> 
												<option value="5">5 sec</option>
												<option value="30" selected>30 sec</option>
												<option value="60">1 min</option>
												<option value="120">2 min</option>
												<option value="240">4 min</option>
												<option value="300">5 min</option>
												<option value="600">10 min</option>
												<option value="900">15 min</option>
												<option value="1200">20 min</option>
												<option value="1800">30 min</option>
												<option value="3600">60 min</option>								
										  </select>
										</td>
									</tr>
								</table>
							</td>
							<span style="display:none"><input type="radio" name="mode" value="2" checked ></span>
							<!--<td colspan=1 align="center"><input type="button" value="Draw Marker Route" Onclick="javascript:return show_data_on_map_manage_polyline('map_report');" ></td>-->
                                                        <td colspan=1 align="center"><input type="button" value="Draw Route over History" Onclick="javascript:return show_overlay_on_map_manage_polyline('map_report');" ></td>
					        
						</tr>				
					</table>
					</td>
				</tr>
			</table>
					
		</div>
 

	
  <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#c3c3c3">	
	<tr>		
		<td class="manage_interfarce" align="center"><a href="javascript:clear_initialize()" style="text-decoration:none"><span>Clear/Refresh</span></a></td>
		<td class="manage_interfarce" align="center"><a href="#" onclick="javascript:return save_route_or_geofencing()" style="text-decoration:none"><span>OK</span></a></td>
		
		<!--<td class="manage_interfarce" align="center"><a href='#' Onclick="javascript:return remove_data_on_map_manage_polyline('map_report');" style="text-decoration:none"><span>Remove Overlays</span></a></td>-->
		<td class="manage_interfarce" align="center"><a href="#" onclick="javascript:return close_div_polyline()" style="text-decoration:none"><span>[ X ]</span></a></td>
	</tr>
	         			
	<tr>
		<td colspan="5" valign="top" align="justify">
			<div id="map_div" style="width:925px; height:570px; position: relative; background-color: rgb(229, 227, 223);" class="ukseries_div_map"></div>							
		</td>	
   </tr>							
  </table>
</div>
<input type="hidden" id="close_geo_route_coord" name="close_geo_route_coord">
<input type="hidden" id="route_event" name="route_event"><input type="hidden" id="close_route_coord" name="close_route_coord">
<div id="available_message" align="center"></div>

<p id="prepage" style="position:absolute; font-family:arial; font-size:16; left:48%; top:220px; layer-background-color:#e5e3df; height:10%; width:20%; visibility:hidden"><img src="images/load_data.gif">		
<div id="dummy_div" style="display:none;"/>
<input type="hidden" id="schedule_location_flag" name="schedule_location_flag" value="0">
<input type="hidden" id="station_flag_map" name="station_flag_map" value="0">
