<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  include_once('user_type_setting.php');
  include_once('common_xml_element.php');
  //print_r($root);
  //echo "<br>z=".$root->data->AccountID;
  $common_id1=$account_id;
  
  $query="SELECT DISTINCT device_imei_no from device_assignment where account_id='$account_id' and status='1'";
  //echo $query;
  $result=mysql_query($query,$DbConnection);
  
  function common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$option_name)
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
<div id="divpopup">
<style>
 @charset "UTF-8";
#cssmenu {
  border: none;
  border: 0px;
  margin: 0px;
  padding: 0px;
  font-family: verdana, geneva, arial, helvetica, sans-serif;
  font-size: 10px;
  font-weight: bold;
  color: #8e8e8e;
  width: auto;
}
#cssmenu > ul {
  margin-top: 6px !important;
}
#cssmenu ul {
  background: #CDCDCD;
  background: -webkit-linear-gradient(#cdcdcd 0%, #e2e2e2 80%, #cdcdcd 100%);
  background: linear-gradient(#cdcdcd 0%, #e2e2e2 80%, #cdcdcd 100%);
  border-top: 1px solid #A8A8A8;
  -webkit-box-shadow: inset 0 1px 0 #e9e9e9, 0 1px 0 #e3b078, 0 2px 0 #b81c40, 0 8px 0 #e3b078, 0 9px 0 #7b021e, 0 -1px 1px rgba(0, 0, 0, 0.1);
  -moz-box-shadow: inset 0 1px 0 #e9e9e9, 0 1px 0 #e3b078, 0 2px 0 #b81c40, 0 8px 0 #e3b078, 0 9px 0 #7b021e, 0 -1px 1px rgba(0, 0, 0, 0.1);
  box-shadow: inset 0 1px 0 #e9e9e9, 0 1px 0 #e3b078, 0 2px 0 #b81c40, 0 8px 0 #e3b078, 0 9px 0 #7b021e, 0 -1px 1px rgba(0, 0, 0, 0.1);
  height: 14px;
  list-style: none;
  margin: 0;
  padding: 0;
}
#cssmenu ul ul {
  border-top: 6px solid #e3b078;
  -webkit-box-shadow: none;
  -moz-box-shadow: none;
  box-shadow: none;
}
#cssmenu ul ul a {
  line-height: 20px;
}
#cssmenu ul ul ul {
  left: 100%;
  top: 0;
}
#cssmenu li {
  float: left;
  padding: 0px 8px 0px 8px;
}
#cssmenu li a {
  color: #666666;
  display: block;
  font-weight: bold;
  line-height: 18px;
  padding: 0px 25px;
  text-align: center;
  text-decoration: none;
}
#cssmenu li a:hover {
  color: #000000;
  text-decoration: none;
  
}
#cssmenu li ul {
  background: #e0e0e0;
  border-left: 2px solid #e3b078;
  border-right: 2px solid #e3b078;
  border-bottom: 2px solid #e3b078;
  display: none;
  height: auto;
  filter: alpha(opacity=95);
  opacity: 0.95;
  position: absolute;
  width: 425px;
  z-index: 200;
  /*top:1em;
		/*left:0;*/

}


#cssmenu li:hover > ul {
  display: block;
}

#cssmenu li li {
  display: block;
  float: none;
  padding: 0px;
  position: relative;
  width: 425px;
}
#cssmenu li ul a {
  display: block;
  font-size: 12px;
  font-style: normal;
  padding: 0px 10px 0px 15px;
  text-align: left;
}

#cssmenu li ul a:hover {
  background: #949494;
  color: #000000;
  opacity: 1.0;
  filter: alpha(opacity=100);
}
#cssmenu p {
  clear: left;
}

#cssmenu .active > a {
  background: #e3b078;
  -webkit-box-shadow: 0 -4px 0 #e3b078, 0 -5px 0 #b81c40, 0 -6px 0 #e3b078;
  -moz-box-shadow: 0 -4px 0 #e3b078, 0 -5px 0 #b81c40, 0 -6px 0 #e3b078;
  box-shadow: 0 -4px 0 #e3b078, 0 -5px 0 #b81c40, 0 -6px 0 #e3b078;
  color: #ffffff;
}

#cssmenu .active > a:hover {
  color: white;
}

   
</style>
 <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
  <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#c3c3c3">							
			<tr>
				<td class="manage_interfarce" align="center">
            <!--<a href="javascript:manage_draw_geofencing_route()" class="hs3">Draw</a>&nbsp;|&nbsp;								
            <a href="javascript:clear_initialize()" class="hs3">Clear/Refresh</a>&nbsp;|&nbsp;
            <a href="#" onclick="javascript:return save_route_or_geofencing()" class="hs3">OK</a>&nbsp;|&nbsp;
			<a href="#" onclick="javascript:return close_div()" class="hs3">Close</a>-->
			
			<div id='cssmenu'>
			<ul>
			   <li class='active has-sub'><a href="javascript:clear_initialize()" ><span>Clear/Refresh</span></a>
			   <li class='active has-sub'><a href="#" onclick="javascript:return save_route_or_geofencing()" ><span>OK</span></a>
			   
			   <li class='active has-sub'><a href='#'><span>Add Overlays</span></a>
				  <ul>
					 <li class='has-sub'><a href='#'><span>Select Vehicle</span></a>
						<?php
						
						 echo'<br>
						
      <input type="hidden" id="common_id">
      <input type="hidden" id="action_name" value="device">
      <table border=0 width="100%">
        <tr>
          <td>
			<table align="center" width="52%">
				<tr>
					<td>
            <fieldset class=\'assignment_manage_fieldset\'>
            <legend>
              <strong>Device IMEI No</strong>
            </legend>
					<div style="height:200px;overflow:auto">
						<table border="0" class="manage_interface" bgcolor=ghostwhite align="center">
						
						'; //echo "caling"; 
						//echo "<br>c=".$root->data->AccountID;
							//echo "<br>comm=".$common_id1;
						get_user_vehicle($root,$common_id1);echo'
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
					 </li>
					 <li>
					 <table>
						 <tr>
							 <td>
							 StartDate:<input type="text" id="date1" name="start_date"  size="18" maxlength="19" />
							  </td><td><a href=javascript:NewCal_SD("date1","yyyymmdd",true,24)>
									<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
							  </a>
							 </td>
							  <td>
							 EndDate:<input type="text" id="date2" name="end_date"  size="18" maxlength="19" />
							 </td><td><a href=javascript:NewCal("date2","yyyymmdd",true,24)>
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
							<td colspan=2 align="center"><input type="button" value="Draw Route" Onclick="javascript:return show_data_on_map_manage_polyline('map_report');" ></td>
							
						</tr>
						
					</table>
					 </li>
				  </ul>
			   </li>
			   <li class='active has-sub'><a href='#' Onclick="javascript:return remove_data_on_map_manage_polyline('map_report');" ><span>Remove Overlays</span></a>
			<li class='active has-sub'><a href="#" onclick="javascript:return close_div()"><span>[ X ]</span></a>
			
			</ul>
			</div>
				</td> 													
			</tr>           			
			<tr>
				<td colspan="5" valign="top" align="justify">
					<div id="map_div" style="width:707px; height:397px; position: relative; background-color: rgb(229, 227, 223);" class="ukseries_div_map"></div>							
				</td>
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
