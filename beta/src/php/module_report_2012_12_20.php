<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include("user_type_setting.php");

	$flag_sector = 0;
	for($k=0;$k<$size_feature_session;$k++)
	{
		//$feature_id_session[$k];
		//echo "<br>feature_name=".$feature_name_session[$k];
		
  	/*if($_SERVER["HTTP_X_FORWARDED_FOR"]=="172.26.48.61")
  	{
  	 echo "<br>FNAME=".$feature_name_session[$k];
    } */
      
    if($feature_name_session[$k] == "sector")
		{
			$flag_sector = 1;
		}
		
		if($feature_name_session[$k] == "distance")
		{
			$flag_distance = 1;
		}
    
		if($feature_name_session[$k] == "monthly_distance")
		{
			$flag_mdistance = 1;
		}
    
		if($feature_name_session[$k] == "performance")
		{
			$flag_performance = 1;
		}
    
		if($feature_name_session[$k] == "summary")
		{
			$flag_summary = 1;
		}
    
		if($feature_name_session[$k] == "travel")
		{
			$flag_travel = 1;
		}
    
		if($feature_name_session[$k] == "engine")
		{
			$flag_engine = 1;
		}
		if($feature_name_session[$k] == "load_cell")
		{
			$flag_load_cell = 1;
		}
    
		if($feature_name_session[$k] == "station")
		{
			$flag_station = 1;
		} 
    
		if($feature_name_session[$k] == "visit_track")
		{
			$flag_visit = 1;
		}                                            		
	}
	//$person_user_type=1;
	$exp_download_path="src/php/exception_timing_files/download";
	$upload_download_path="src/php/download_data_files";
	echo'<tr>
			<td align="left">
				<div style="overflow-x:hidden;overflow-y:auto;" id="leftMenu">';
					$js_function_name = "report_common_prev";    // FUNCTION NAME
					$js_function_name_mining = "report_common_prev_mining"; 	
					$js_function_name_person = "report_common_prev_person"; 		
          echo'<div id="treeView" style="background-color:white;margin:7px;"> </div>
					<div id="msg">&nbsp;</div>
						<script type="text/javascript">
						var tree;
						(function() {
						var treeInit = function() {
						tree = new YAHOO.widget.TreeView("treeView",
						[					
							{type:"Text", label:"Regular", editable:false, children:
							[
								';
								if($person_user_type==1) // this is for root and person user
								{
									echo'"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_visitdetail.php\',\'Visit%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'<img src=\'images/report_icons/speed violation.jpeg\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>"+ 	
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_visitdetail.php\',\'Visit%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Visit Detail"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",';								
								}
								if($fleet_user_type==1 || $mining_user_type==1 || $courier_user_type==1 || $school_user_type ==1 || $pos_user_type==1) //for all user except person
								{
									echo'"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_vehicle.php\',\'Vehicle%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'<img src=\'images/report_icons/vehicle_report.jpeg\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>"+ 	
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_vehicle.php\',\'Vehicle%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Vehicle Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",';
									echo'"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_speed.php\',\'Speed%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'<img src=\'images/report_icons/speed_detail.jpeg\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>"+ 	
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_speed.php\',\'Speed%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Speed Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",';							
									
									if($flag_distance)
									{
										echo'"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_distance.php\',\'Distance%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'<img src=\'images/report_icons/distance.jpeg\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>"+ 	
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_distance.php\',\'Distance%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Distance Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",';                 
  								}
									
									if($flag_mdistance)
									{
										echo'"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_daily_distance.php\',\'Daily%20Distance%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'<img src=\'images/report_icons/distance_daily.jpeg\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>"+ 	
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_daily_distance.php\',\'Daily%20Distance%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Daily Distance"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",';
										echo'"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_monthly_distance.php\',\'Monthly%20Distance%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'<img src=\'images/report_icons/distance_monthly.jpeg\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>"+ 	
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_monthly_distance.php\',\'Monthly%20Distance%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Monthly Distance"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",'; 								
  								}
									echo'"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_fuel.php\',\'Fuel%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'<img src=\'images/report_icons/fuel_detail.jpeg\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>"+ 	
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_fuel.php\',\'Fuel%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Fuel Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",'; 								
									
									//if($_SERVER["REMOTE_ADDR"]== "202.3.77.11")
									//{
										echo'"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_fuel_halt.php\',\'Fuel%20Halt%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'<img src=\'images/report_icons/fuel_detail.jpeg\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>"+ 	
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_fuel_halt.php\',\'Fuel%20Halt%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Fuel Halt Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",'; 
  									
									//} 
									
									if($flag_summary)
									{	
									echo'"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_summary.php\',\'Summary%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'<img src=\'images/report_icons/summary_rep.png\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>"+ 	
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_summary.php\',\'Summary%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Summary Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",'; 
  								}
									
									if($flag_engine)
									{
										echo'"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_engine_runhr.php\',\'Engine%20RunHr%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'<img src=\'images/report_icons/fuel_detail.jpeg\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>"+ 	
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_engine_runhr.php\',\'Engine%20RunHr%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Engine RunHr Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",'; 
  								}
									
                  /*echo'
                  \'<table border=0>\'+
									\'<tr>\'+
										\'<td valign="top">&nbsp;\'+
													\'<a href=javascript:'.$js_function_name.'("src/php/report_door_open.php","Door%20Open");>\'+
														\'<img src="images/report_icons/fuel_detail.jpeg" style="border:none;width:20px;height:20px;">\'+
													\'</a>\'+
										\'</td>\'+
										\'<td valign="top">\'+
											\'<div style="height:2px"></div>\'+
											\'<a href=javascript:'.$js_function_name.'("src/php/report_door_open.php","Door%20Open"); class="menuitem">\'+
												\'&nbsp;Door Open\'+
											\'</a>\'+
										\'</td>\'+
									\'</tr>\'+
									\'</table>\',
									\'<table border=0>\'+
									\'<tr>\'+
										\'<td valign="top">&nbsp;\'+
													\'<a href=javascript:'.$js_function_name.'("src/php/report_sos.php","Door%20Report");>\'+
														\'<img src="images/report_icons/fuel_detail.jpeg" style="border:none;width:20px;height:20px;">\'+
													\'</a>\'+
										\'</td>\'+
										\'<td valign="top">\'+
											\'<div style="height:2px"></div>\'+
											\'<a href=javascript:'.$js_function_name.'("src/php/report_sos.php","SOS%20Report"); class="menuitem">\'+
												\'&nbsp;SOS\'+
											\'</a>\'+
										\'</td>\'+
									\'</tr>\'+
									\'</table>\',';*/ 
								}									
								if($mining_user_type==1) ////////// this only for mining user
								{
									echo'"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:'.$js_function_name_mining.'(\'src/php/report_trip.php\',\'Trip%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'<img src=\'images/report_icons/add_vehicle.jpeg\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>"+ 	
																"<a href=javascript:'.$js_function_name_mining.'(\'src/php/report_trip.php\',\'Trip%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Trip Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",'; 
									echo'"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:'.$js_function_name_mining.'(\'src/php/report_trip_vehicle_movement.php\',\'Vehicle%20Movement%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'<img src=\'images/report_icons/add_vehicle.jpeg\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>"+ 	
																"<a href=javascript:'.$js_function_name_mining.'(\'src/php/report_trip_vehicle_movement.php\',\'Vehicle%20Movement%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Trip Vehicle Movement"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",';
										echo'"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:'.$js_function_name_mining.'(\'src/php/report_trip_summary.php\',\'Trip%20Summary%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'<img src=\'images/report_icons/add_vehicle.jpeg\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>"+ 	
																"<a href=javascript:'.$js_function_name_mining.'(\'src/php/report_trip_summary.php\',\'Trip%20Summary%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Trip Summary Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",';  								
							}
								echo'"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_halt.php\',\'Halt%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'<img src=\'images/report_icons/vehicle_halt.jpeg\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>"+ 	
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_halt.php\',\'Halt%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Halt Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",';  
									
								if($flag_station)
								{
									echo'"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_station_halt.php\',\'Supply%20Timing%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'<img src=\'images/report_icons/station_list.png\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>"+ 	
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_station_halt.php\',\'Supply%20Timing%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Supply Timing Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",';
										echo'"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:report_common_prev_station(\'src/php/action_report_station_list.php\',\'Station%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'<img src=\'images/report_icons/station_list.png\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>"+ 	
																"<a href=javascript:'.$js_function_name.'(\'src/php/action_report_station_list.php\',\'Station%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Station Record"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",';								
								echo '{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td>"+
												"<table border=0 class=mystyle>"+
													"<tr>"+
														"<td height=0px></td>"+
													"</tr>"+
													"<tr>"+
														"<td> &nbsp;"+ 																				
															"Download Data File"+ 																	
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
										"</tr>"+
									"</table>",editable:false, children:
								[
									{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td>"+
												"<table border=0 class=mystyle>"+
													"<tr>"+
														"<td height=0px></td>"+
													"</tr>"+
													"<tr>"+
														"<td> &nbsp;"+ 																				
															"Halt Report"+ 																	
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
										"</tr>"+
									"</table>",editable:false, children:
									[';
										if($handle = opendir($upload_download_path."/".$account_id)) 
										{
											while($file = readdir($handle)) 
											{
												if($file!="." && $file!="..")
												{
											echo'{type:"Text",
													label:"<table border=0 class=\'mystyle\'>"+
																"<tr>"+ 																				
																	"<td valign=\'top\'>"+
																		"<table border=0 class=\'mystyle\'>"+																					
																			"<tr>"+
																				"<td>"+ 
																					"<a href=javascript:report_show_download_file(\'src/php/report_download_files.php\',\''.$file.'\'); class=\'menuitem\'>&nbsp;"+
																						"'.$file.'"+
																					"</a>"+
																				"</td>"+
																			"</tr>"+
																		"</table>"+
																	"</td>"+
																"</tr>"+
															"</table>",editable:false, children:																	
													[]},';											
												}
											}
										}
								echo']},';
							echo'{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td>"+
												"<table border=0 class=mystyle>"+
													"<tr>"+
														"<td height=0px></td>"+
													"</tr>"+
													"<tr>"+
														"<td> &nbsp;"+ 																				
															"Exception Timing"+ 																	
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
										"</tr>"+
									"</table>",editable:false, children:
									[';
										if($handle1 = opendir($exp_download_path."/".$account_id)) 
										{
											while($file1 = readdir($handle1)) 
											{
												if($file1!="." && $file1!="..")
												{
											echo'{type:"Text",
													label:"<table border=0 class=\'mystyle\'>"+
																"<tr>"+ 																				
																	"<td valign=\'top\'>"+
																		"<table border=0 class=\'mystyle\'>"+																					
																			"<tr>"+
																				"<td>"+ 
																					"<a href=javascript:report_show_download_file(\'src/php/report_download_files.php\',\''.$file.'\'); class=\'menuitem\'>&nbsp;"+
																						"'.$file1.'"+
																					"</a>"+
																				"</td>"+
																			"</tr>"+
																		"</table>"+
																	"</td>"+
																"</tr>"+
															"</table>",editable:false, children:																	
													[]},';											
												}
											}
										}
								echo']},';
								
							echo']},';
						echo '{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td>"+
												"<table border=0 class=mystyle>"+
													"<tr>"+
														"<td height=0px></td>"+
													"</tr>"+
													"<tr>"+
														"<td> &nbsp;"+ 																				
															"Upload Data File"+ 																	
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
										"</tr>"+
									"</table>",editable:false, children:
								[';
									echo'"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:report_upload_file(\'src/php/report_upload_station_halt.php\',\'Upload%20Station%20Halt%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'<img src=\'images/report_icons/station_list.png\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>"+ 	
																"<a href=javascript:report_upload_file(\'src/php/report_upload_station_halt.php\',\'Upload%20Station%20Halt%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Upload File Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",';
										echo'"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:report_upload_file(\'src/php/report_upload_exception_timing.php\',\'Upload%20Exception%20Timing%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'<img src=\'images/report_icons/station_list.png\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>"+ 	
																"<a href=javascript:report_upload_file(\'src/php/report_upload_exception_timing.php\',\'Upload%20Exception%20Timing%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Exception Timing Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",';																										
							echo']},';				  									
  									
									/*echo '{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td>"+
												"<table border=0 class=mystyle>"+
													"<tr>"+
														"<td height=0px></td>"+
													"</tr>"+
													"<tr>"+
														"<td> &nbsp;"+ 																				
															"Download Data File"+ 																	
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
										"</tr>"+
									"</table>",editable:false, children:
								[';
									if($handle = opendir($dir_tmp."/".$account_id)) 
									{
										while($file = readdir($handle)) 
										{
											if($file!="." && $file!="..")
											{
										echo'{type:"Text",
												label:"<table border=0 class=\'mystyle\'>"+
															"<tr>"+ 																				
																"<td valign=\'top\'>"+
																	"<table border=0 class=\'mystyle\'>"+																					
																		"<tr>"+
																			"<td>"+ 
																				"<a href=javascript:report_show_download_file(\'src/php/report_download_files.php\',\''.$file.'\'); class=\'menuitem\'>&nbsp;"+
																					"'.$file.'"+
																				"</a>"+
																			"</td>"+
																		"</tr>"+
																	"</table>"+
																"</td>"+
															"</tr>"+
														"</table>",editable:false, children:																	
												[]},';											
											}
										}
									}							
						echo']},';*/
                 }									
                  
                  if($flag_travel)
                  {
					echo'"<table border=0 class=\'mystyle\'>"+
							"<tr>"+ 
								"<td valign=\'top\'>"+
									"<table border=0 class=\'mystyle\'>"+
										"<tr>"+
											"<td height=\'1px\'></td>"+
										"</tr>"+
										"<tr>"+
											"<td>'.$nbsp.'"+
												"<a href=javascript:'.$js_function_name.'(\'src/php/report_travel.php\',\'Travel%20Report\'); class=\'menuitem\'>"+
													"'.$nbsp.'<img src=\'images/report_icons/travel.png\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
												"</a>"+
											"</td>"+
										"</tr>"+
									"</table>"+
								"</td>"+
								"<td valign=\'top\'>"+
									"<table border=0 class=\'mystyle\'>"+
										"<tr>"+
											"<td height=\'1px\'></td>"+
										"</tr>"+
										"<tr>"+
											"<td>"+ 	
												"<a href=javascript:'.$js_function_name.'(\'src/php/report_travel.php\',\'Travel%20Report\'); class=\'menuitem\'>"+
													"'.$nbsp.'Travel Report"+
												"</a>"+
											"</td>"+
										"</tr>"+
									"</table>"+
								"</td>"+
							"</tr>"+
						"</table>",';               
                  }
                  
                  if($flag_load_cell )
                  {
					echo'"<table border=0 class=\'mystyle\'>"+
							"<tr>"+ 
								"<td valign=\'top\'>"+
									"<table border=0 class=\'mystyle\'>"+
										"<tr>"+
											"<td height=\'1px\'></td>"+
										"</tr>"+
										"<tr>"+
											"<td>'.$nbsp.'"+
												"<a href=javascript:'.$js_function_name.'(\'src/php/report_load_cell.php\',\'Load%20Cell%20Report\'); class=\'menuitem\'>"+
													"'.$nbsp.'<img src=\'images/report_icons/load_cell.png\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
												"</a>"+
											"</td>"+
										"</tr>"+
									"</table>"+
								"</td>"+
								"<td valign=\'top\'>"+
									"<table border=0 class=\'mystyle\'>"+
										"<tr>"+
											"<td height=\'1px\'></td>"+
										"</tr>"+
										"<tr>"+
											"<td>"+ 	
												"<a href=javascript:'.$js_function_name.'(\'src/php/report_load_cell.php\',\'Load%20Cell%20Report\'); class=\'menuitem\'>"+
													"'.$nbsp.'Load Cell Report"+
												"</a>"+
											"</td>"+
										"</tr>"+
									"</table>"+
								"</td>"+
							"</tr>"+
						"</table>",';                    
                  }                                                     
									
								  if($account_id==1)
								  {
									echo'"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_version.php\',\'Version%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'<img src=\'images/report_icons/performance.png\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>"+ 	
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_version.php\',\'Version%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Version Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",';                
								  }							            
                  								
								if($person_user_type!=1)
								  {	
									echo'"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_battery_voltage.php\',\'Battery%20Voltage%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'<img src=\'images/report_icons/travel.png\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>"+ 	
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_battery_voltage.php\',\'Battery%20Voltage%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Battery Voltage Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",';                   
								  }
									
								//if($person_user_type!=1)
								//{                  
								  if($flag_performance)
								  {
									echo'"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_performance.php\',\'Performance%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'<img src=\'images/report_icons/performance.png\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>"+ 	
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_performance.php\',\'Performance%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Performance Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",';                  
								  }
								  
								  //if($flag_performance)
								  //{
								  if($person_user_type!=1)
								  {
									echo'"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_temperature.php\',\'Temperature%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'<img src=\'images/report_icons/temperature.png\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>"+ 	
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_temperature.php\',\'Temperature%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Temperature Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",';              
								  }								  
									
      						//if($_SERVER['HTTP_X_FORWARDED_FOR'] == "172.26.48.181")      						
                  //echo '<br>In DistanceAction1: dist='.$total_dist.'<br><br>';
                    									
    							//echo  $flag_sector;
								if($flag_visit == 1)
								  {
										echo'"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_schedule_assignment.php\',\'Schedule%20Assignment%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'<img src=\'images/report_icons/temperature.png\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>"+ 	
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_schedule_assignment.php\',\'Schedule%20Assignment%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Schedule Assignment Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",'; 
										echo'"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:schedule_location_prev(\'src/php/action_report_schedule_location.php\',\'Schedule%20Location%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'<img src=\'images/report_icons/temperature.png\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>"+ 	
																"<a href=javascript:schedule_location_prev(\'src/php/action_report_schedule_location.php\',\'Schedule%20Location%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Schedule Location Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",';															
								  }	
								if($flag_sector)
    							{
                    echo '
                    {type:"Text", label:"Sector Report", editable:false, children:
      							[';
										echo'"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_sector_halt.php\',\'Sector%20Halt\'); class=\'menuitem\'>"+
																	"'.$nbsp.'<img src=\'images/report_icons/sector.png\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>"+ 	
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_sector_halt.php\',\'Sector%20Halt\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Sector Halt"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",'; 
      																							
      									      
      									//if($_SERVER["REMOTE_ADDR"] == "202.3.77.206")  
      									//{
										echo'"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_sector_change.php\',\'Sector%20Change\'); class=\'menuitem\'>"+
																	"'.$nbsp.'<img src=\'images/report_icons/sector_change.png\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>"+ 	
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_sector_change.php\',\'Sector%20Change\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Sector Change"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",';                        
                       // }																							    								       
                  echo']},';    
              } // if flag sector closed
              
            // }  // person_user_type!=1 closed             								
							
              echo'	
							]},       
							{type:"Text", label:"Alert", editable:false, children:
							[
								';
								echo'"<table border=0 class=\'mystyle\'>"+
										"<tr>"+ 
											"<td valign=\'top\'>"+
												"<table border=0 class=\'mystyle\'>"+
													"<tr>"+
														"<td height=\'1px\'></td>"+
													"</tr>"+
													"<tr>"+
														"<td>'.$nbsp.'"+
															"<a href=javascript:'.$js_function_name.'(\'src/php/alert_area_violation.php\',\'Geofence%20Violation%20Report\'); class=\'menuitem\'>"+
																"'.$nbsp.'<img src=\'images/report_icons/area_violation.jpeg\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
															"</a>"+
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td valign=\'top\'>"+
												"<table border=0 class=\'mystyle\'>"+
													"<tr>"+
														"<td height=\'1px\'></td>"+
													"</tr>"+
													"<tr>"+
														"<td>"+ 	
															"<a href=javascript:'.$js_function_name.'(\'src/php/alert_area_violation.php\',\'Geofence%20Violation%20Report\'); class=\'menuitem\'>"+
																"'.$nbsp.'Geofence violation"+
															"</a>"+
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
										"</tr>"+
									"</table>",';
								if($fleet_user_type==1 || $mining_user_type==1 || $courier_user_type==1 || $school_user_type ==1 || $pos_user_type==1) // for all user except person
								{
									echo'"<table border=0 class=\'mystyle\'>"+
										"<tr>"+ 
											"<td valign=\'top\'>"+
												"<table border=0 class=\'mystyle\'>"+
													"<tr>"+
														"<td height=\'1px\'></td>"+
													"</tr>"+
													"<tr>"+
														"<td>'.$nbsp.'"+
															"<a href=javascript:'.$js_function_name.'(\'src/php/alert_speed_violation.php\',\'Speed%20Violation%20Report\'); class=\'menuitem\'>"+
																"'.$nbsp.'<img src=\'images/report_icons/speed violation.jpeg\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
															"</a>"+
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td valign=\'top\'>"+
												"<table border=0 class=\'mystyle\'>"+
													"<tr>"+
														"<td height=\'1px\'></td>"+
													"</tr>"+
													"<tr>"+
														"<td>"+ 	
															"<a href=javascript:'.$js_function_name.'(\'src/php/alert_speed_violation.php\',\'Speed%20Violation%20Report\'); class=\'menuitem\'>"+
																"'.$nbsp.'Speed violation"+
															"</a>"+
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
										"</tr>"+
									"</table>"';
								
								}
							echo']},';
							if($person_user_type!=1 || ($fleet_user_type==1 || $mining_user_type==1 || $courier_user_type==1 || $school_user_type ==1 || $pos_user_type==1)) // for all user except person
							{
							echo'
							{type:"Text", label:"Graph", editable:false, children:
							[';
								echo'"<table border=0 class=\'mystyle\'>"+
										"<tr>"+ 
											"<td valign=\'top\'>"+
												"<table border=0 class=\'mystyle\'>"+
													"<tr>"+
														"<td height=\'1px\'></td>"+
													"</tr>"+
													"<tr>"+
														"<td>'.$nbsp.'"+
															"<a href=javascript:'.$js_function_name.'(\'src/php/graph_daily_speed.php\',\'Speed%20Graph\'); class=\'menuitem\'>"+
																"'.$nbsp.'<img src=\'images/report_icons/speed_graph.jpeg\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
															"</a>"+
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td valign=\'top\'>"+
												"<table border=0 class=\'mystyle\'>"+
													"<tr>"+
														"<td height=\'1px\'></td>"+
													"</tr>"+
													"<tr>"+
														"<td>"+ 	
															"<a href=javascript:'.$js_function_name.'(\'src/php/graph_daily_speed.php\',\'Speed%20Graph\'); class=\'menuitem\'>"+
																"'.$nbsp.'Speed Graph"+
															"</a>"+
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
										"</tr>"+
									"</table>",';
									echo'"<table border=0 class=\'mystyle\'>"+
										"<tr>"+ 
											"<td valign=\'top\'>"+
												"<table border=0 class=\'mystyle\'>"+
													"<tr>"+
														"<td height=\'1px\'></td>"+
													"</tr>"+
													"<tr>"+
														"<td>'.$nbsp.'"+
															"<a href=javascript:'.$js_function_name.'(\'src/php/graph_daily_fuel.php\',\'Fuel%20Graph\'); class=\'menuitem\'>"+
																"'.$nbsp.'<img src=\'images/report_icons/fuel_graph.jpeg\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
															"</a>"+
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td valign=\'top\'>"+
												"<table border=0 class=\'mystyle\'>"+
													"<tr>"+
														"<td height=\'1px\'></td>"+
													"</tr>"+
													"<tr>"+
														"<td>"+ 	
															"<a href=javascript:'.$js_function_name.'(\'src/php/graph_daily_fuel.php\',\'Fuel%20Graph\'); class=\'menuitem\'>"+
																"'.$nbsp.'Fuel Graph"+
															"</a>"+
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
										"</tr>"+
									"</table>",';							
								
							  //if( ($flag_sector) && ($_SERVER["REMOTE_ADDR"] == "115.248.114.51") )
							  
                if($flag_sector)
							  {
									echo'"<table border=0 class=\'mystyle\'>"+
										"<tr>"+ 
											"<td valign=\'top\'>"+
												"<table border=0 class=\'mystyle\'>"+
													"<tr>"+
														"<td height=\'1px\'></td>"+
													"</tr>"+
													"<tr>"+
														"<td>'.$nbsp.'"+
															"<a href=javascript:'.$js_function_name.'(\'src/php/graph_sector.php\',\'Sector%20Graph\'); class=\'menuitem\'>"+
																"'.$nbsp.'<img src=\'images/report_icons/sector_graph.jpeg\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
															"</a>"+
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td valign=\'top\'>"+
												"<table border=0 class=\'mystyle\'>"+
													"<tr>"+
														"<td height=\'1px\'></td>"+
													"</tr>"+
													"<tr>"+
														"<td>"+ 	
															"<a href=javascript:'.$js_function_name.'(\'src/php/graph_sector.php\',\'Sector%20Graph\'); class=\'menuitem\'>"+
																"'.$nbsp.'Sector Graph"+
															"</a>"+
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
										"</tr>"+
									"</table>",';          		
                }												
								
							echo ']},';
							}
							echo'
							{type:"Text", label:"DataLog", editable:false, children:
							[';
								echo'"<table border=0 class=\'mystyle\'>"+
										"<tr>"+ 
											"<td valign=\'top\'>"+
												"<table border=0 class=\'mystyle\'>"+
													"<tr>"+
														"<td height=\'1px\'></td>"+
													"</tr>"+
													"<tr>"+
														"<td>'.$nbsp.'"+
															"<a href=javascript:'.$js_function_name.'(\'src/php/datalog_today_records.php\',\'Today%20Datalog\'); class=\'menuitem\'>"+
																"'.$nbsp.'<img src=\'images/report_icons/data_export.gif\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
															"</a>"+
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td valign=\'top\'>"+
												"<table border=0 class=\'mystyle\'>"+
													"<tr>"+
														"<td height=\'1px\'></td>"+
													"</tr>"+
													"<tr>"+
														"<td>"+ 	
															"<a href=javascript:'.$js_function_name.'(\'src/php/datalog_today_records.php\',\'Today%20Datalog\'); class=\'menuitem\'>"+
																"'.$nbsp.'Today Datalog"+
															"</a>"+
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
										"</tr>"+
									"</table>",'; 
								echo'"<table border=0 class=\'mystyle\'>"+
										"<tr>"+ 
											"<td valign=\'top\'>"+
												"<table border=0 class=\'mystyle\'>"+
													"<tr>"+
														"<td height=\'1px\'></td>"+
													"</tr>"+
													"<tr>"+
														"<td>'.$nbsp.'"+
															"<a href=javascript:'.$js_function_name.'(\'src/php/datalog_between_dates.php\',\'Date%20Wise%20Report\'); class=\'menuitem\'>"+
																"'.$nbsp.'<img src=\'images/report_icons/data_export.gif\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
															"</a>"+
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td valign=\'top\'>"+
												"<table border=0 class=\'mystyle\'>"+
													"<tr>"+
														"<td height=\'1px\'></td>"+
													"</tr>"+
													"<tr>"+
														"<td>"+ 	
															"<a href=javascript:'.$js_function_name.'(\'src/php/datalog_between_dates.php\',\'Date%20Wise%20Report\'); class=\'menuitem\'>"+
																"'.$nbsp.'Between dates"+
															"</a>"+
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
										"</tr>"+
									"</table>",';  
									echo'"<table border=0 class=\'mystyle\'>"+
										"<tr>"+ 
											"<td valign=\'top\'>"+
												"<table border=0 class=\'mystyle\'>"+
													"<tr>"+
														"<td height=\'1px\'></td>"+
													"</tr>"+
													"<tr>"+
														"<td>'.$nbsp.'"+
															"<a href=javascript:'.$js_function_name.'(\'src/php/datalog_by_vehicle.php\',\'Specify%20Vehicle%20Report\'); class=\'menuitem\'>"+
																"'.$nbsp.'<img src=\'images/report_icons/data_export.gif\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
															"</a>"+
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td valign=\'top\'>"+
												"<table border=0 class=\'mystyle\'>"+
													"<tr>"+
														"<td height=\'1px\'></td>"+
													"</tr>"+
													"<tr>"+
														"<td>"+ 	
															"<a href=javascript:'.$js_function_name.'(\'src/php/datalog_by_vehicle.php\',\'Specify%20Vehicle%20Report\'); class=\'menuitem\'>"+
																"'.$nbsp.'Specify '.$report_type.'"+
															"</a>"+
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
										"</tr>"+
									"</table>",'; 
									
									echo'"<table border=0 class=\'mystyle\'>"+
										"<tr>"+ 
											"<td valign=\'top\'>"+
												"<table border=0 class=\'mystyle\'>"+
													"<tr>"+
														"<td height=\'1px\'></td>"+
													"</tr>"+
													"<tr>"+
														"<td>'.$nbsp.'"+
															"<a href=javascript:'.$js_function_name.'(\'src/php/datalog_search.php\',\'Search%20Vehicle%20Report\'); class=\'menuitem\'>"+
																"'.$nbsp.'<img src=\'images/report_icons/data_export.gif\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
															"</a>"+
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td valign=\'top\'>"+
												"<table border=0 class=\'mystyle\'>"+
													"<tr>"+
														"<td height=\'1px\'></td>"+
													"</tr>"+
													"<tr>"+
														"<td>"+ 	
															"<a href=javascript:'.$js_function_name.'(\'src/php/datalog_search.php\',\'Search%20Vehicle%20Report\'); class=\'menuitem\'>"+
																"'.$nbsp.'Search '.$report_type.'"+
															"</a>"+
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
										"</tr>"+
									"</table>" 
							]},';
							
							if($school_user_type==1) //////school type user
							{								
						echo'{type:"Text", label:"School", editable:false, children:
    							[';
									echo'"<table border=0 class=\'mystyle\'>"+
										"<tr>"+ 
											"<td valign=\'top\'>"+
												"<table border=0 class=\'mystyle\'>"+
													"<tr>"+
														"<td height=\'1px\'></td>"+
													"</tr>"+
													"<tr>"+
														"<td>'.$nbsp.'"+
															"<a href=javascript:'.$js_function_name.'(\'src/php/report_bus.php\',\'Bus%20Report\'); class=\'menuitem\'>"+
																"'.$nbsp.'<img src=\'images/report_icons/area_violation.jpeg\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
															"</a>"+
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td valign=\'top\'>"+
												"<table border=0 class=\'mystyle\'>"+
													"<tr>"+
														"<td height=\'1px\'></td>"+
													"</tr>"+
													"<tr>"+
														"<td>"+ 	
															"<a href=javascript:'.$js_function_name.'(\'src/php/report_bus.php\',\'Bus%20Report\'); class=\'menuitem\'>"+
																"'.$nbsp.'Bus Report"+
															"</a>"+
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
										"</tr>"+
									"</table>",'; 
									echo'"<table border=0 class=\'mystyle\'>"+
										"<tr>"+ 
											"<td valign=\'top\'>"+
												"<table border=0 class=\'mystyle\'>"+
													"<tr>"+
														"<td height=\'1px\'></td>"+
													"</tr>"+
													"<tr>"+
														"<td>'.$nbsp.'"+
															"<a href=javascript:'.$js_function_name.'(\'src/php/report_student_prev.php\',\'Student%20Report\'); class=\'menuitem\'>"+
																"'.$nbsp.'<img src=\'images/report_icons/speed violation.jpeg\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
															"</a>"+
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td valign=\'top\'>"+
												"<table border=0 class=\'mystyle\'>"+
													"<tr>"+
														"<td height=\'1px\'></td>"+
													"</tr>"+
													"<tr>"+
														"<td>"+ 	
															"<a href=javascript:'.$js_function_name.'(\'src/php/report_student_prev.php\',\'Student%20Report\'); class=\'menuitem\'>"+
																"'.$nbsp.'Student Report"+
															"</a>"+
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
										"</tr>"+
									"</table>",   								
    							]},';
							}               
						echo'{type:"Text", label:"Data Gap", editable:false, children:
    							[';
									echo'"<table border=0 class=\'mystyle\'>"+
										"<tr>"+ 
											"<td valign=\'top\'>"+
												"<table border=0 class=\'mystyle\'>"+
													"<tr>"+
														"<td height=\'1px\'></td>"+
													"</tr>"+
													"<tr>"+
														"<td>'.$nbsp.'"+
															"<a href=javascript:'.$js_function_name.'(\'src/php/report_datagap.php\',\'No%20Data%20Report/%20%20No%20GPS%20Report\'); class=\'menuitem\'>"+
																"'.$nbsp.'<img src=\'images/report_icons/report_nodata.jpg\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
															"</a>"+
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td valign=\'top\'>"+
												"<table border=0 class=\'mystyle\'>"+
													"<tr>"+
														"<td height=\'1px\'></td>"+
													"</tr>"+
													"<tr>"+
														"<td>"+ 	
															"<a href=javascript:'.$js_function_name.'(\'src/php/report_datagap.php\',\'No%20Data%20Report/%20%20No%20GPS%20Report\'); class=\'menuitem\'>"+
																"'.$nbsp.'NoData/&nbsp;NoGPS"+
															"</a>"+
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
										"</tr>"+
									"</table>",   							   								
    							]},';              
					echo']);
						tree.render();
						tree.subscribe(\'dblClickEvent\',tree.onEventEditNode);
						};
						YAHOO.util.Event.onDOMReady(treeInit);
						})();
						</script>
				</div>
			</td>
		</tr>';		
?> 	

				