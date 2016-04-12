<?php
//error_reporting(-1);
//ini_set('display_errors', 'On');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
include("user_type_setting.php");
        
/*$pathInPieces = explode(DIRECTORY_SEPARATOR ,dirname(__FILE__));
//print_r($pathInPieces);
//$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2]."/".$pathInPieces[3];
$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2];
$filePathToS3Wrapper=$pathToRoot."/s3/S3Wrapper.php";
include_once($filePathToS3Wrapper);

$S3Path="gps_report/".$account_id."/upload";
$contents=listDir($S3Path);*/
/*$download_path="src/php/gps_report/".$account_id."/download";
$master_download_path="src/php/gps_report/".$account_id."/master";
$S3Path="gps_report/".$account_id."/upload";
$contents=listDir($S3Path);
 if(count($contents)>0)
{
   for($i=0;$i<sizeof($contents);$i++) 
   {
      echo "fileName=".$contents[$i]."<br>";
   }
}*/


$flag_sector = 0;
$flag_distance=0;
$flag_mdistance = 0;
$flag_performance=0;
$flag_summary = 0;
$flag_travel=0;
$flag_engine = 0;
$flag_ac=0;
$flag_load_cell = 0;
$flag_station=0;
$flag_substation = 0;
$flag_visit=0;
$flag_fuel = 0;
$consignment_info=0;
$door_open_info = 0;
$fuel_lead_info=0;
$flag_vehicle_reverse = 0;
$flag_io_trip=0;
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
		if($feature_name_session[$k] == "ac")
		{
			$flag_ac = 1;
		}		
		if($feature_name_session[$k] == "load_cell")
		{
			$flag_load_cell = 1;
		}
    
		if($feature_name_session[$k] == "station")
		{
			$flag_station = 1;
		} 
		
		if($feature_name_session[$k] == "substation")
		{
			$flag_substation = 1;
		} 
    
		if($feature_name_session[$k] == "visit_track")
		{
			$flag_visit = 1;
		}
		if($feature_name_session[$k] == "fuel")
		{
				$flag_fuel = 1;
		}
		if($feature_name_session[$k] == "consignment")
		{
				$consignment_info = 1;
		} 
		if($feature_name_session[$k] == "door_open")
		{
				$door_open_info = 1;
		}
		if($feature_name_session[$k] == "fuel_lead")
		{
				$fuel_lead_info = 1;
		}
		if($feature_name_session[$k] == "vehicle_reverse")
		{
				$flag_vehicle_reverse = 1;
		}
		if($feature_name_session[$k] == "io_trip")
		{
				$flag_io_trip = 1;
		}
		
		if($feature_name_session[$k] == "flowRate")
		{
				$flag_flowRate = 1;
		}
		if($feature_name_session[$k] == "dispensing1")
		{
				$flag_dispensing1 = 1;
		}
		if($feature_name_session[$k] == "dispensing2")
		{
				$flag_dispensing2 = 1;
		}
		if($feature_name_session[$k] == "dispensing3")
		{
				$flag_dispensing3 = 1;
		}
	}
	//$person_user_type=1;
	//$exp_download_path="src/php/exception_timing_files/download";
	//$upload_download_path="src/php/download_data_files";
	
	//$exp_download_path="src/php/gps_report/".$account_id;
	$download_path="src/php/gps_report/".$account_id."/download";
	$master_download_path="src/php/gps_report/".$account_id."/master";
        
       	$nbsp='&nbsp';
	//if ($flag_station) echo "Upload Path=".$upload_download_path;
	
	echo'<tr>
			<td align="left">
				<div style="overflow-x:hidden;overflow-y:auto;" id="leftMenu">';
					$js_function_name = "report_common_prev";    // FUNCTION NAME
					//$js_function_name_station = "report_common_prev_station1";    // FUNCTION NAME
					$js_function_name_station = "report_common_prev_station";    // FUNCTION NAME
					$js_function_name_mining = "report_common_prev_mining"; 	
					$js_function_name_person = "report_common_prev_person"; 
					$js_function_name_jquery = "report_common_prev_jquery";    // FUNCTION NAME						
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
								
								if($person_user_type==1 || $fleet_user_type==1 || $mining_user_type==1 || $courier_user_type==1 || $school_user_type ==1 || $pos_user_type==1) //for all user except person
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_vehicle.htm\',\'Vehicle%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_vehicle.htm\',\'Vehicle%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.$report_type.' Report"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_distance.htm\',\'Distance%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_distance.htm\',\'Distance%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_daily_distance.htm\',\'Daily%20Distance%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_daily_distance.htm\',\'Daily%20Distance%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_monthly_distance.htm\',\'Monthly%20Distance%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_monthly_distance.htm\',\'Monthly%20Distance%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Monthly Distance"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",'; 								
  								}
									
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_summary.htm\',\'Summary%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_summary.htm\',\'Summary%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Summary Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",'; 
  								}
									
								}          								
							
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
															"<a href=javascript:'.$js_function_name.'(\'src/php/alert_area_violation.htm\',\'Geofence%20Violation%20Report\'); class=\'menuitem\'>"+
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
															"<a href=javascript:'.$js_function_name.'(\'src/php/alert_area_violation.htm\',\'Geofence%20Violation%20Report\'); class=\'menuitem\'>"+
																"'.$nbsp.'Geofence violation"+
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
															"<a href=javascript:'.$js_function_name.'(\'src/php/alert_monthly_distance_geofence.htm\',\'Monthly%20Geofence%20Report\'); class=\'menuitem\'>"+
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
															"<a href=javascript:'.$js_function_name.'(\'src/php/alert_monthly_distance_geofence.htm\',\'Monthly%20Geofence%20Report\'); class=\'menuitem\'>"+
																"'.$nbsp.'Monthly Geofence Report"+
															"</a>"+
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
										"</tr>"+
									"</table>",';
								if($fleet_user_type==1) // for all user except person
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
															"<a href=javascript:'.$js_function_name.'(\'src/php/alert_speed_violation.htm\',\'Speed%20Violation%20Report\'); class=\'menuitem\'>"+
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
															"<a href=javascript:'.$js_function_name.'(\'src/php/alert_speed_violation.htm\',\'Speed%20Violation%20Report\'); class=\'menuitem\'>"+
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

				
