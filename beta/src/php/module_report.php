<?php
//error_reporting(-1);
//ini_set('display_errors', 'On');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
include("user_type_setting.php");
        
$pathInPieces = explode(DIRECTORY_SEPARATOR ,dirname(__FILE__));
//print_r($pathInPieces);
//$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2]."/".$pathInPieces[3];
$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2];
$filePathToS3Wrapper=$pathToRoot."/s3/S3Wrapper.php";
include_once($filePathToS3Wrapper);

$S3Path="gps_report/".$account_id."/upload";
$contents=listDir($S3Path);
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
	}
	//$person_user_type=1;
	//$exp_download_path="src/php/exception_timing_files/download";
	//$upload_download_path="src/php/download_data_files";
	
	//$exp_download_path="src/php/gps_report/".$account_id;
	$download_path="src/php/gps_report/".$account_id."/download";
	$master_download_path="src/php/gps_report/".$account_id."/master";
        
       	
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
								if($account_id=="696")
								{
									echo'{type:"Text", label:"Hero Moto Report", editable:false, children:
									[
										"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:report_moto_master_prev(\'src/php/report_moto_dispatch_master.htm\',\'Dispatch%20Master%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:report_moto_master_prev(\'src/php/report_moto_dispatch_master.htm\',\'Dispatch%20Master%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Master Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>", 
										"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:report_moto_monthly_prev(\'src/php/report_moto_monthly_comparison.htm\',\'Previous%20To%20Current%20Month%20Comparison\'); class=\'menuitem\'>"+
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
																"<a href=javascript:report_moto_monthly_prev(\'src/php/report_moto_monthly_comparison.htm\',\'Previous%20To%20Current%20Month%20Comparison\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Monthly Comparison"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",
										"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:report_moto_trip_performance_prev(\'src/php/report_moto_trip_performance.htm\',\'Trip%20Performance\'); class=\'menuitem\'>"+
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
																"<a href=javascript:report_moto_trip_performance_prev(\'src/php/report_moto_trip_performance.htm\',\'Trip%20Performance\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Trip Performance"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",
										"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:report_moto_prev(\'src/php/action_report_moto_stop_on_trip.htm\',\'Vehicle%20Stopped%20On%20Trip\'); class=\'menuitem\'>"+
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
																"<a href=javascript:report_moto_prev(\'src/php/action_report_moto_stop_on_trip.htm\',\'Vehicle%20Stopped%20On%20Trip\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Stop On Trip"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",								
										"<table border=0 class=\'mystyle\'>"+
												"<tr>"+ 
													"<td valign=\'top\'>"+
														"<table border=0 class=\'mystyle\'>"+
															"<tr>"+
																"<td height=\'1px\'></td>"+
															"</tr>"+
															"<tr>"+
																"<td>'.$nbsp.'"+
																	"<a href=javascript:report_moto_prev(\'src/php/action_report_moto_stop_trip.htm\',\'On%20Trip\'); class=\'menuitem\'>"+
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
																	"<a href=javascript:report_moto_prev(\'src/php/action_report_moto_stop_trip.htm\',\'On%20Trip\'); class=\'menuitem\'>"+
																		"'.$nbsp.'Vehicle On Trip"+
																	"</a>"+
																"</td>"+
															"</tr>"+
														"</table>"+
													"</td>"+
												"</tr>"+
											"</table>",
											"<table border=0 class=\'mystyle\'>"+
													"<tr>"+ 
														"<td valign=\'top\'>"+
															"<table border=0 class=\'mystyle\'>"+
																"<tr>"+
																	"<td height=\'1px\'></td>"+
																"</tr>"+
																"<tr>"+
																	"<td>'.$nbsp.'"+
																		"<a href=javascript:report_moto_prev(\'src/php/action_report_moto_vehicle_violation.htm\',\'Vehicle%20Violation\'); class=\'menuitem\'>"+
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
																		"<a href=javascript:report_moto_prev(\'src/php/action_report_moto_vehicle_violation.htm\',\'Vehicle%20Violation\'); class=\'menuitem\'>"+
																			"'.$nbsp.'Vehicle Violation"+
																		"</a>"+
																	"</td>"+
																"</tr>"+
															"</table>"+
														"</td>"+
													"</tr>"+
												"</table>",
												"<table border=0 class=\'mystyle\'>"+
													"<tr>"+ 
														"<td valign=\'top\'>"+
															"<table border=0 class=\'mystyle\'>"+
																"<tr>"+
																	"<td height=\'1px\'></td>"+
																"</tr>"+
																"<tr>"+
																	"<td>'.$nbsp.'"+
																		"<a href=javascript:'.$js_function_name.'(\'src/php/report_moto_load_planning.htm\',\'Vehicle%20Load%20Planning\'); class=\'menuitem\'>"+
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
																		"<a href=javascript:'.$js_function_name.'(\'src/php/report_moto_load_planning.htm\',\'Vehicle%20Load%20Planning\'); class=\'menuitem\'>"+
																			"'.$nbsp.'Vehicle Load Planning"+
																		"</a>"+
																	"</td>"+
																"</tr>"+
															"</table>"+
														"</td>"+
													"</tr>"+
												"</table>",
												"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:report_moto_prev(\'src/php/action_report_carrier_performance_rating.htm\',\'Carrier%20Performance%20On%20Rating\'); class=\'menuitem\'>"+
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
																"<a href=javascript:report_moto_prev(\'src/php/action_report_carrier_performance_rating.htm\',\'Carrier%20Performance%20On%20Rating\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Carrier Performance Rating"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",
										"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:report_moto_prev(\'src/php/action_report_moto_location_data.htm\',\'Location%20Data\'); class=\'menuitem\'>"+
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
																"<a href=javascript:report_moto_prev(\'src/php/action_report_moto_location_data.htm\',\'Location%20Data\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Location Data"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",
										"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:report_moto_prev(\'src/php/action_report_moto_viewing_trip_report.htm\',\'Viewing%20Trip%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:report_moto_prev(\'src/php/action_report_moto_viewing_trip_report.htm\',\'Viewing%20Trip%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Viewing Trip Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",
										"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:report_show_auto_manager(\'src/php/report_moto_auto_route_manager.htm\',\'Auto%20Route%20Manage\'); class=\'menuitem\'>"+
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
																"<a href=javascript:report_show_auto_manager(\'src/php/report_moto_auto_route_manager.htm\',\'Auto%20Route%20Manager\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Auto Route Manager"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",
										"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_moto_geocode_management.htm\',\'Viewing%20Trip%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_moto_geocode_management.htm\',\'Viewing%20Trip%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Geocode Management"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",'; 								
									echo']},';
								}
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_visitdetail.htm\',\'Visit%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_visitdetail.htm\',\'Visit%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Visit Detail"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",';								
								}
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
																	"'.$nbsp.'Vehicle Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",';
										if($person_user_type==1)
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_hourly_distance.htm\',\'Hourly%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_hourly_distance.htm\',\'Hourly%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Hourly Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",';
										}
										if($flag_station==1)
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
																"<a href=\'rhome.htm\' target=\'_blank\' class=\'menuitem\'>"+
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
																"<a href=\'rhome.htm\' target=\'_blank\' class=\'menuitem\'>"+
																	"'.$nbsp.'Route On Map"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/action_report_device.htm\',\'Device%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'<img src=\'images/report_icons/device_report.jpg\' style=\'border:none;width:20px;height:20px;\' class=\'help_img_css\'>"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/action_report_device.htm\',\'Device%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Device Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",';
										
									   if(($consignment_info==1) && (!$person_user_type))
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_consignment_info.htm\',\'Consignment%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_consignment_info.htm\',\'Consignment%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Consignment Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",';
                                        }
					if(!$person_user_type)
					{
									/*echo'"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_speed.htm\',\'Speed%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_speed.htm\',\'Speed%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Speed Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",';*/
				}

										if($account_id==715)
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
																	"<a href=javascript:klp_report_prev(\'src/php/klp_report_prev.htm\',\'Klp%20Input%20Report\'); class=\'menuitem\'>"+
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
																	"<a href=javascript:klp_report_prev(\'src/php/klp_report_prev.htm\',\'Klp%20Input%20Report\'); class=\'menuitem\'>"+
																		"'.$nbsp.'Klp Input Report"+
																	"</a>"+
																"</td>"+
															"</tr>"+
														"</table>"+
													"</td>"+
												"</tr>"+
											"</table>",';
										}
									
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

								if($flag_fuel)
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_fuel.htm\',\'Fuel%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_fuel.htm\',\'Fuel%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_fuel_halt.htm\',\'Fuel%20Halt%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_fuel_halt.htm\',\'Fuel%20Halt%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Fuel Halt Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",'; 
  									
									//} 
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_engine_runhr.htm\',\'Engine%20RunHr%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_engine_runhr.htm\',\'Engine%20RunHr%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Engine RunHr Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",'; 
  								}
								
									if($flag_io_trip)
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_io_trip.htm\',\'IO%20Trip%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_io_trip.htm\',\'IO%20Trip%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'IO Trip Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",'; 
  								}								
								
								if($flag_ac)
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_ac_runhr.htm\',\'AC%20RunHr%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_ac_runhr.htm\',\'AC%20RunHr%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'AC RunHr Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",'; 
  								}								
									if($door_open_info=="1")
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_door_open.htm\',\'Door%20Open\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_door_open.htm\',\'Door%20Open\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Door Open"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",'; 
									}
									if($fuel_lead_info=="1")
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_fule_lead.htm\',\'Door%20Open\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_fuel_lead.htm\',\'Door%20Open\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Fuel Lead"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",';
									}
											/*\'<table border=0>\'+
											\'<tr>\'+
												\'<td valign="top">&nbsp;\'+
															\'<a href=javascript:'.$js_function_name.'("src/php/report_sos.htm","Door%20Report");>\'+
																\'<img src="images/report_icons/fuel_detail.jpeg" style="border:none;width:20px;height:20px;">\'+
															\'</a>\'+
												\'</td>\'+
												\'<td valign="top">\'+
													\'<div style="height:2px"></div>\'+
													\'<a href=javascript:'.$js_function_name.'("src/php/report_sos.htm","SOS%20Report"); class="menuitem">\'+
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
																"<a href=javascript:'.$js_function_name_mining.'(\'src/php/report_trip.htm\',\'Trip%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name_mining.'(\'src/php/report_trip.htm\',\'Trip%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name_mining.'(\'src/php/report_trip_new.htm\',\'Trip%20Report%20New\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name_mining.'(\'src/php/report_trip_new.htm\',\'Trip%20Report%20New\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Trip Report New"+
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
																"<a href=javascript:'.$js_function_name_mining.'(\'src/php/report_trip_vehicle_movement.htm\',\'Vehicle%20Movement%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name_mining.'(\'src/php/report_trip_vehicle_movement.htm\',\'Vehicle%20Movement%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name_mining.'(\'src/php/report_trip_summary.htm\',\'Trip%20Summary%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name_mining.'(\'src/php/report_trip_summary.htm\',\'Trip%20Summary%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Trip Summary Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",';  								
							}
							if(!$person_user_type)
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_halt.htm\',\'Halt%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_halt.htm\',\'Halt%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Halt Report"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_track_interval.htm\',\'Track%20Interval%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_track_interval.htm\',\'Track%20Interval%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Track Interval Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",'; 
							}			
								if($flag_station)
								{
									/*echo'"<table border=0 class=\'mystyle\'>"+
											"<tr>"+ 
												"<td valign=\'top\'>"+
													"<table border=0 class=\'mystyle\'>"+
														"<tr>"+
															"<td height=\'1px\'></td>"+
														"</tr>"+
														"<tr>"+
															"<td>'.$nbsp.'"+
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_station_halt.htm\',\'Supply%20Timing%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_station_halt.htm\',\'Supply%20Timing%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Supply Timing Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",';*/
										if($_SERVER['HTTP_X_FORWARDED_FOR']=="172.26.48.195")
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_station_halt_1.htm\',\'Supply%20Timing%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_station_halt_1.htm\',\'Supply%20Timing%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Supply Timing Report 1"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/action_report_station_list.htm\',\'Station%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/action_report_station_list.htm\',\'Station%20Report\'); class=\'menuitem\'>"+
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
									[';
										
										/*if($handle = opendir($download_path)) 
										{
											$download_file_arr=array();
											while($file = readdir($handle)) 
											{
												if($file!="." && $file!="..")
												{
													$download_file_arr[]=$file;																					
												}
											}
											rsort($download_file_arr);
											for($i=0;$i<sizeof($download_file_arr);$i++)
											{
												if($i<7)
												{
											echo'{type:"Text",
												label:"<table border=0 class=\'mystyle\'>"+
															"<tr>"+ 																				
																"<td valign=\'top\'>"+
																	"<table border=0 class=\'mystyle\'>"+																					
																		"<tr>"+
																			"<td>"+ 
																				"<a href=javascript:report_show_download_file(\'src/php/report_download_files.htm\',\''.$download_file_arr[$i].'\'); class=\'menuitem\'>&nbsp;"+
																					"'.$download_file_arr[$i].'"+
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
										}*/
                                                                                if(count($contents)>0)
                                                                                for($i=0;$i<sizeof($contents);$i++) 
                                                                                {
                                                                                  echo'{type:"Text",
												label:"<table border=0 class=\'mystyle\'>"+
															"<tr>"+ 																				
																"<td valign=\'top\'>"+
																	"<table border=0 class=\'mystyle\'>"+																					
																		"<tr>"+
																			"<td>"+ 
																				"<a href=javascript:report_show_download_file(\'src/php/report_download_files.htm\',\''.$contents[$i].'\'); class=\'menuitem\'>&nbsp;"+
																					"'.$contents[$i].'"+
																				"</a>"+
																			"</td>"+
																		"</tr>"+
																	"</table>"+
																"</td>"+
															"</tr>"+
														"</table>",editable:false, children:																	
												[]},';
                                                                                }
									echo '{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td>"+
												"<table border=0 class=mystyle>"+
													"<tr>"+
														"<td height=0px></td>"+
													"</tr>"+
													"<tr>"+
													"<td>"+ 
														"<a href=javascript:report_show_download_file(\'src/php/report_master_download_files.htm\',\'master\'); class=\'menuitem\'>&nbsp;"+
															"Download Master File"+
														"</a>"+
													"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
										"</tr>"+
									"</table>",editable:false, children:
								[]},';										
																						
							/*echo'{type:"Text",
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
																					"<a href=javascript:report_show_download_file(\'src/php/report_download_files.htm\',\''.$file.'\'); class=\'menuitem\'>&nbsp;"+
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
								echo']},';*/
								
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
																"<a href=javascript:report_upload_file_1(\'src/php/report_upload_master_file.htm\',\'Master%20Report%20File\',\'master\'); class=\'menuitem\'>"+
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
																"<a href=javascript:report_upload_file_1(\'src/php/report_upload_master_file.htm\',\'Master%20Report%20File\',\'master\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Master Report File"+
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
																"<a href=javascript:report_upload_file_1(\'src/php/report_upload_master_file.htm\',\'Get%20Report%20File\',\'get_report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:report_upload_file_1(\'src/php/report_upload_master_file.htm\',\'Get%20Report%20File\',\'get_report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Get Report File"+
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
																				"<a href=javascript:report_show_download_file(\'src/php/report_download_files.htm\',\''.$file.'\'); class=\'menuitem\'>&nbsp;"+
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
          												"<a href=javascript:'.$js_function_name.'(\'src/php/report_travel.htm\',\'Travel%20Report\'); class=\'menuitem\'>"+
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
          												"<a href=javascript:'.$js_function_name.'(\'src/php/report_travel.htm\',\'Travel%20Report\'); class=\'menuitem\'>"+
          													"'.$nbsp.'Travel Report"+
          												"</a>"+
          											"</td>"+
          										"</tr>"+
          									"</table>"+
          								"</td>"+
          							"</tr>"+
          						"</table>",';
                    
            					if($account_id=="238" || $account_id == "615")
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
              												"<a href=javascript:'.$js_function_name.'(\'src/php/report_travel_summary.htm\',\'Travel%20Summary\'); class=\'menuitem\'>"+
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
              												"<a href=javascript:'.$js_function_name.'(\'src/php/report_travel_summary.htm\',\'Travel%20Summary\'); class=\'menuitem\'>"+
              													"'.$nbsp.'Travel Summary"+
              												"</a>"+
              											"</td>"+
              										"</tr>"+
              									"</table>"+
              								"</td>"+
              							"</tr>"+
              						"</table>",';
                        }                            
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
												"<a href=javascript:'.$js_function_name.'(\'src/php/report_load_cell.htm\',\'Load%20Cell%20Report\'); class=\'menuitem\'>"+
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
												"<a href=javascript:'.$js_function_name.'(\'src/php/report_load_cell.htm\',\'Load%20Cell%20Report\'); class=\'menuitem\'>"+
													"'.$nbsp.'Load Cell Report"+
												"</a>"+
											"</td>"+
										"</tr>"+
									"</table>"+
								"</td>"+
							"</tr>"+
						"</table>",';                    
                  }                                                     
									
								  //if($account_id==1)
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_version.htm\',\'Version%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_version.htm\',\'Version%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Version Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",';                
								  }							            
                  								
								if($person_user_type!=1 || $account_id==1)
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_battery_voltage.htm\',\'Battery%20Voltage%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_battery_voltage.htm\',\'Battery%20Voltage%20Report\'); class=\'menuitem\'>"+
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
								  /*if($flag_performance)
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_performance.htm\',\'Performance%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_performance.htm\',\'Performance%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Performance Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",';                  
								  }*/
								  
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_temperature.htm\',\'Temperature%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_temperature.htm\',\'Temperature%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Temperature Report"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_nearby.htm\',\'Near%20By%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_nearby.htm\',\'Near%20By%20Report\'); class=\'menuitem\'>"+
																	"'.$nbsp.'Near By Report"+
																"</a>"+
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
											"</tr>"+
										"</table>",';
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_schedule_assignment.htm\',\'Schedule%20Assignment%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_schedule_assignment.htm\',\'Schedule%20Assignment%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:schedule_location_prev(\'src/php/action_report_schedule_location.htm\',\'Schedule%20Location%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:schedule_location_prev(\'src/php/action_report_schedule_location.htm\',\'Schedule%20Location%20Report\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_sector_halt.htm\',\'Sector%20Halt\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_sector_halt.htm\',\'Sector%20Halt\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_sector_change.htm\',\'Sector%20Change\'); class=\'menuitem\'>"+
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
																"<a href=javascript:'.$js_function_name.'(\'src/php/report_sector_change.htm\',\'Sector%20Change\'); class=\'menuitem\'>"+
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
								
									if($flag_vehicle_reverse)
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
															"<a href=javascript:'.$js_function_name.'(\'src/php/alert_vehicle_reverse.htm\',\'Vehicle%20Reverse%20Alert\'); class=\'menuitem\'>"+
																"'.$nbsp.'Vehicle Reverse"+
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
									echo'"<table border=0 class=\'mystyle\'>"+
										"<tr>"+ 
											"<td valign=\'top\'>"+
												"<table border=0 class=\'mystyle\'>"+
													"<tr>"+
														"<td height=\'1px\'></td>"+
													"</tr>"+
													"<tr>"+
														"<td>'.$nbsp.'"+
															"<a href=javascript:'.$js_function_name_jquery.'(\'src/php/alert_polyline_route_violation.htm\',\'Route%20Violation%20Report\'); class=\'menuitem\'>"+
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
															"<a href=javascript:'.$js_function_name_jquery.'(\'src/php/alert_polyline_route_violation.htm\',\'Route%20Violation%20Report\'); class=\'menuitem\'>"+
																"'.$nbsp.'Route violation"+
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
							/*if($person_user_type!=1 || ($fleet_user_type==1 || $mining_user_type==1 || $courier_user_type==1 || $school_user_type ==1 || $pos_user_type==1)) // for all user except person
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
															"<a href=javascript:'.$js_function_name.'(\'src/php/graph_daily_speed.htm\',\'Speed%20Graph\'); class=\'menuitem\'>"+
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
															"<a href=javascript:'.$js_function_name.'(\'src/php/graph_daily_speed.htm\',\'Speed%20Graph\'); class=\'menuitem\'>"+
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
															"<a href=javascript:'.$js_function_name.'(\'src/php/graph_temperature.htm\',\'Temperature%20Graph\'); class=\'menuitem\'>"+
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
															"<a href=javascript:'.$js_function_name.'(\'src/php/graph_temperature.htm\',\'Temperature%20Graph\'); class=\'menuitem\'>"+
																"'.$nbsp.'Temperature Graph"+
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
															"<a href=javascript:'.$js_function_name.'(\'src/php/graph_daily_fuel.htm\',\'Fuel%20Graph\'); class=\'menuitem\'>"+
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
															"<a href=javascript:'.$js_function_name.'(\'src/php/graph_daily_fuel.htm\',\'Fuel%20Graph\'); class=\'menuitem\'>"+
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
															"<a href=javascript:'.$js_function_name.'(\'src/php/graph_sector.htm\',\'Sector%20Graph\'); class=\'menuitem\'>"+
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
															"<a href=javascript:'.$js_function_name.'(\'src/php/graph_sector.htm\',\'Sector%20Graph\'); class=\'menuitem\'>"+
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
							}*/
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
															"<a href=javascript:'.$js_function_name.'(\'src/php/datalog_today_records.htm\',\'Today%20Datalog\'); class=\'menuitem\'>"+
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
															"<a href=javascript:'.$js_function_name.'(\'src/php/datalog_today_records.htm\',\'Today%20Datalog\'); class=\'menuitem\'>"+
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
															"<a href=javascript:'.$js_function_name.'(\'src/php/datalog_between_dates.htm\',\'Date%20Wise%20Report\'); class=\'menuitem\'>"+
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
															"<a href=javascript:'.$js_function_name.'(\'src/php/datalog_between_dates.htm\',\'Date%20Wise%20Report\'); class=\'menuitem\'>"+
																"'.$nbsp.'Between dates"+
															"</a>"+
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
										"</tr>"+
									"</table>",';  
									/*echo'"<table border=0 class=\'mystyle\'>"+
										"<tr>"+ 
											"<td valign=\'top\'>"+
												"<table border=0 class=\'mystyle\'>"+
													"<tr>"+
														"<td height=\'1px\'></td>"+
													"</tr>"+
													"<tr>"+
														"<td>'.$nbsp.'"+
															"<a href=javascript:'.$js_function_name.'(\'src/php/datalog_by_vehicle.htm\',\'Specify%20Vehicle%20Report\'); class=\'menuitem\'>"+
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
															"<a href=javascript:'.$js_function_name.'(\'src/php/datalog_by_vehicle.htm\',\'Specify%20Vehicle%20Report\'); class=\'menuitem\'>"+
																"'.$nbsp.'Specify '.$report_type.'"+
															"</a>"+
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
										"</tr>"+
									"</table>",'; */
									
									echo'"<!--<table border=0 class=\'mystyle\'>"+
										"<tr>"+ 
											"<td valign=\'top\'>"+
												"<table border=0 class=\'mystyle\'>"+
													"<tr>"+
														"<td height=\'1px\'></td>"+
													"</tr>"+
													"<tr>"+
														"<td>'.$nbsp.'"+
															"<a href=javascript:'.$js_function_name.'(\'src/php/datalog_search.htm\',\'Search%20Vehicle%20Report\'); class=\'menuitem\'>"+
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
															"<a href=javascript:'.$js_function_name.'(\'src/php/datalog_search.htm\',\'Search%20Vehicle%20Report\'); class=\'menuitem\'>"+
																"'.$nbsp.'Search '.$report_type.'"+
															"</a>"+
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
										"</tr>"+
									"</table>-->" 
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
															"<a href=javascript:'.$js_function_name.'(\'src/php/report_bus.htm\',\'Bus%20Report\'); class=\'menuitem\'>"+
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
															"<a href=javascript:'.$js_function_name.'(\'src/php/report_bus.htm\',\'Bus%20Report\'); class=\'menuitem\'>"+
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
															"<a href=javascript:'.$js_function_name.'(\'src/php/report_student_prev.htm\',\'Student%20Report\'); class=\'menuitem\'>"+
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
															"<a href=javascript:'.$js_function_name.'(\'src/php/report_student_prev.htm\',\'Student%20Report\'); class=\'menuitem\'>"+
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
															"<a href=javascript:'.$js_function_name.'(\'src/php/report_datagap.htm\',\'No%20Data%20Report/%20%20No%20GPS%20Report\'); class=\'menuitem\'>"+
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
															"<a href=javascript:'.$js_function_name.'(\'src/php/report_datagap.htm\',\'No%20Data%20Report/%20%20No%20GPS%20Report\'); class=\'menuitem\'>"+
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

				
