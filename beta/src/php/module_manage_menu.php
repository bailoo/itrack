<?php
//echo "<br>usertype=".$user_type;
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');	
include("user_type_setting.php");
include_once("util_account_detail.php");
//echo $user_type;
$flag_sector = 0;
for($k=0;$k<$size_feature_session;$k++)
{
	//$feature_id_session[$k];
	//echo "<br>feature_name=".$feature_name_session[$k];
	if($feature_name_session[$k] == "sector")
	{
		$flag_sector = 1;
	}
	
	/*if($_SERVER["HTTP_X_FORWARDED_FOR"]=="172.26.48.61")
	{
	 echo "<br>FNAME=".$feature_name_session[$k];
  } */
  
  if($feature_name_session[$k] == "station")
	{
		$flag_station = 1;
	}
	if($feature_name_session[$k] == "substation")
	{
		$flag_substation = 1;
	}
	if($feature_name_session[$k] == "invoice")
	{
		$flag_invoice = 1;
	}	
	if($feature_name_session[$k] == "raw_milk")
	{
		$flag_raw_milk = 1;
	}
        if($user_type == "proc_admin")
	{
		$flag_proc_admin = 1;
	}
	if($feature_name_session[$k] == "hindalco_invoice")
	{
		$flag_hindalco_invoice = 1;
	}
	if($feature_name_session[$k] == "visit_track")
	{
		$flag_visit = 1;
	}
	
	if($feature_name_session[$k] == "vehicle_trip")
	{
		$flag_vtrip = 1;
	}	
  
	if($feature_name_session[$k] == "load_cell")
	{
		$flag_load_cell = 1;
	}
	if($feature_name_session[$k] == "consignment")
	{
		$consignment_info = 1;
	}
	if($feature_name_session[$k] =="upl_flag")
	{
		$flag_upl = 1;
	}
}
$style1="\'border:none;width:20px;height:20px;\'";
$nbsp="&nbsp;&nbsp;";
$contetnbsp="&nbsp;";	
?>
<table border='0' width="100%" height="100%" cellspacing="0" cellpadding="0">
  <tr class="mb1">
      <td>
          <?php include('module_logo.php');?> 
      </td>
    </tr>
  <tr  class="mb2">
    <td valign="top">
       
          <table border='0' width="100%" cellspacing="0" cellpadding="0" class="menu">	
			  <?php                 
                echo'<tr>
                	<td> 
                    <table class="menu" width="100%" border="0" bgcolor="grey" cellspacing="1" cellpadding="1">
                    	<tr>
                    		<td><strong>&nbsp;Manage</strong></td>
                    	</tr>
                    </table>
                  </td>
                </tr> 
				<tr>
					<td>
						<div style="overflow-x:hidden;overflow-y:auto;" id="leftMenu"> ';
					?>
					<div id="treeView" style="background-color:white;margin:7px;"> </div>
					<div id="msg">&nbsp;</div>
						<script type="text/javascript">
						var tree;
						(function() {
						var treeInit = function() {					
						tree = new YAHOO.widget.TreeView("treeView",
						[
							<?php
                                                            if($flag_proc_admin!=1)
                                                            {
								echo'{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td valign=\'top\'>"+
												"<table border=0 class=mystyle>"+													
													"<tr>"+
														"<td>'.$nbsp.'"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_group.htm\');>"+
																"<img src=\'images/manage/group.png\' style='.$style1.'>"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td >"+
												"<table border=0 class=mystyle valign=\'top\'>"+
													"<tr>"+
														"<td height=2px></td>"+
													"</tr>"+
													"<tr>"+
														"<td valign=\'top\'>"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_group.htm\'); class=\'menuitem\' id=\'test_unique\'>"+
																"'.$contetnbsp.'Group"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+														
											"</td>"+
										"</tr>"+
									"</table>",editable:false, children:
									[]},';	
									echo'{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td valign=\'top\'>"+
												"<table border=0 class=mystyle>"+													
													"<tr>"+
														"<td>'.$nbsp.'"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_account.htm\');>"+
																"<img src=\'images/manage/account.png\' style='.$style1.'>"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td >"+
												"<table border=0 class=mystyle valign=\'top\'>"+
													"<tr>"+
														"<td height=2px></td>"+
													"</tr>"+
													"<tr>"+
														"<td valign=\'top\'>"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_account.htm\'); class=\'menuitem\'>"+
																"'.$contetnbsp.'Account"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+														
											"</td>"+
										"</tr>"+
									"</table>",editable:false, children:
									[]},';
									}
									if($account_id=='1')
									{
										echo'{type:"Text",
										label:"<table border=0 class=mystyle>"+
											"<tr>"+ 																	
												"<td valign=\'top\'>"+
													"<table border=0 class=mystyle>"+													
														"<tr>"+
															"<td>'.$nbsp.'"+ 																				
																"<a href=javascript:manage_show_file(\'src/php/manage_account_thirdparty.htm\');>"+
																	"<img src=\'images/manage/account.png\' style='.$style1.'>"+
																"</a>"+																
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
												"<td >"+
													"<table border=0 class=mystyle valign=\'top\'>"+
														"<tr>"+
															"<td height=2px></td>"+
														"</tr>"+
														"<tr>"+
															"<td valign=\'top\'>"+ 																				
																"<a href=javascript:manage_show_file(\'src/php/manage_account_thirdparty.htm\'); class=\'menuitem\'>"+
																	"'.$contetnbsp.'ThirdParty Account"+
																"</a>"+																
															"</td>"+
														"</tr>"+
													"</table>"+														
												"</td>"+
											"</tr>"+
										"</table>",editable:false, children:
										[]},';
									}
                                                                         if($flag_proc_admin!=1)
                                                                         {
									echo'{type:"Text",
										label:"<table border=0 class=mystyle>"+
											"<tr>"+ 																	
												"<td valign=\'top\'>"+
													"<table border=0 class=mystyle>"+													
														"<tr>"+
															"<td>'.$nbsp.'"+ 																				
																"<a href=javascript:manage_show_file(\'src/php/manage_vehicle_thirdparty.htm\');>"+
																	"<img src=\'images/manage/vehicle.png\' style='.$style1.'>"+
																"</a>"+																
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
												"<td >"+
													"<table border=0 class=mystyle valign=\'top\'>"+
														"<tr>"+
															"<td height=2px></td>"+
														"</tr>"+
														"<tr>"+
															"<td valign=\'top\'>"+ 																				
																"<a href=javascript:manage_show_file(\'src/php/manage_vehicle_thirdparty.htm\'); class=\'menuitem\'>"+
																	"'.$contetnbsp.'ThirdParty Vehicle"+
																"</a>"+																
															"</td>"+
														"</tr>"+
													"</table>"+														
												"</td>"+
											"</tr>"+
										"</table>",editable:false, children:
										[]},';
                                                                         }
									if($consignment_info==1)
                                   {
									echo'{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td valign=\'top\'>"+
												"<table border=0 class=mystyle>"+													
													"<tr>"+
														"<td>'.$nbsp.'"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_consignment_info.htm\');>"+
																"<img src=\'images/manage/vehicle.png\' style='.$style1.'>"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td >"+
												"<table border=0 class=mystyle valign=\'top\'>"+
													"<tr>"+
														"<td height=2px></td>"+
													"</tr>"+
													"<tr>"+
														"<td valign=\'top\'>"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_consignment_info.htm\'); class=\'menuitem\'>"+
																"Consignment Info"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+														
											"</td>"+
										"</tr>"+
									"</table>",editable:false, children:
									[]},';
                                  }
									
                                                                         if($flag_proc_admin!=1)
                                                                         {
                                                                        echo'{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td valign=\'top\'>"+
												"<table border=0 class=mystyle>"+													
													"<tr>"+
														"<td>'.$nbsp.'"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_vehicle.htm\');>"+
																"<img src=\'images/manage/vehicle.png\' style='.$style1.'>"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td >"+
												"<table border=0 class=mystyle valign=\'top\'>"+
													"<tr>"+
														"<td height=2px></td>"+
													"</tr>"+
													"<tr>"+
														"<td valign=\'top\'>"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_vehicle.htm\'); class=\'menuitem\'>"+
																"'.$contetnbsp.''.$report_type.'"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+														
											"</td>"+
										"</tr>"+
									"</table>",editable:false, children:
									[]},';
                                                                         }
								if($report_type=="Person")
								{
									echo'{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td valign=\'top\'>"+
												"<table border=0 class=mystyle>"+													
													"<tr>"+
														"<td>'.$nbsp.'"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_device.htm\');>"+
																"<img src=\'images/manage/device.png\' style='.$style1.'>"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td >"+
												"<table border=0 class=mystyle valign=\'top\'>"+
													"<tr>"+
														"<td height=2px></td>"+
													"</tr>"+
													"<tr>"+
														"<td valign=\'top\'>"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_device.htm\'); class=\'menuitem\'>"+
																"'.$contetnbsp.'Mobile"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+														
											"</td>"+
										"</tr>"+
									"</table>",editable:false, children:
									[]},';								
								}
								else
								{
								 if($flag_proc_admin!=1)
                                                                 {
                                                                    echo'{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td valign=\'top\'>"+
												"<table border=0 class=mystyle>"+													
													"<tr>"+
														"<td>'.$nbsp.'"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_device.htm\');>"+
																"<img src=\'images/manage/device.png\' style='.$style1.'>"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td >"+
												"<table border=0 class=mystyle valign=\'top\'>"+
													"<tr>"+
														"<td height=2px></td>"+
													"</tr>"+
													"<tr>"+
														"<td valign=\'top\'>"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_device.htm\'); class=\'menuitem\'>"+
																"'.$contetnbsp.'Device"+
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
								if($report_type!="Person" && $flag_proc_admin!=1)
								{
									echo'{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td valign=\'top\'>"+
												"<table border=0 class=mystyle>"+													
													"<tr>"+
														"<td>'.$nbsp.'"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_calibration.htm\');>"+
																"<img src=\'images/manage/device.png\' style='.$style1.'>"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td >"+
												"<table border=0 class=mystyle valign=\'top\'>"+
													"<tr>"+
														"<td height=2px></td>"+
													"</tr>"+
													"<tr>"+
														"<td valign=\'top\'>"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_calibration.htm\'); class=\'menuitem\'>"+
																"'.$contetnbsp.'Calibration"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+														
											"</td>"+
										"</tr>"+
									"</table>",editable:false, children:
									[]},';
									echo'{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td valign=\'top\'>"+
												"<table border=0 class=mystyle>"+													
													"<tr>"+
														"<td>'.$nbsp.'"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_escalation.htm\');>"+
																"<img src=\'images/manage/device.png\' style='.$style1.'>"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td >"+
												"<table border=0 class=mystyle valign=\'top\'>"+
													"<tr>"+
														"<td height=2px></td>"+
													"</tr>"+
													"<tr>"+
														"<td valign=\'top\'>"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_escalation.htm\'); class=\'menuitem\'>"+
																"'.$contetnbsp.'Escalation"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+														
											"</td>"+
										"</tr>"+
									"</table>",editable:false, children:
									[]},';
									echo'{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td valign=\'top\'>"+
												"<table border=0 class=mystyle>"+													
													"<tr>"+
														"<td>'.$nbsp.'"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_io_assignment.htm\');>"+
																"<img src=\'images/manage/assignment.png\' style='.$style1.'>"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td >"+
												"<table border=0 class=mystyle valign=\'top\'>"+
													"<tr>"+
														"<td height=2px></td>"+
													"</tr>"+
													"<tr>"+
														"<td valign=\'top\'>"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_io_assignment.htm\'); class=\'menuitem\'>"+
																"'.$contetnbsp.'IO Assignment"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+														
											"</td>"+
										"</tr>"+
									"</table>",editable:false, children:
									[]},';
								}
								if($account_id==715)
								{
								echo'{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td valign=\'top\'>"+
												"<table border=0 class=mystyle>"+													
													"<tr>"+
														"<td>'.$nbsp.'"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_klp_input.htm\');>"+
																"<img src=\'images/manage/device.png\' style='.$style1.'>"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td >"+
												"<table border=0 class=mystyle valign=\'top\'>"+
													"<tr>"+
														"<td height=2px></td>"+
													"</tr>"+
													"<tr>"+
														"<td valign=\'top\'>"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_klp_input.htm\'); class=\'menuitem\'>"+
																"'.$contetnbsp.'KLP INPUT"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+														
											"</td>"+
										"</tr>"+
									"</table>",editable:false, children:
									[]},';
								}
								echo'{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td valign=\'top\'>"+
												"<table border=0 class=mystyle>"+													
													"<tr>"+
														"<td>'.$nbsp.'"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_landmark.htm\');>"+
																"<img src=\'images/manage/landmark.png\' style='.$style1.'>"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td >"+
												"<table border=0 class=mystyle valign=\'top\'>"+
													"<tr>"+
														"<td height=2px></td>"+
													"</tr>"+
													"<tr>"+
														"<td valign=\'top\'>"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_landmark.htm\'); class=\'menuitem\'>"+
																"'.$contetnbsp.'Landmark"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+														
											"</td>"+
										"</tr>"+
									"</table>",editable:false, children:
									[]},';
                                                                
                                                                
								echo'{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td valign=\'top\'>"+
												"<table border=0 class=mystyle>"+													
													"<tr>"+
														"<td>'.$nbsp.'"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_trip.htm\');>"+
																"<img src=\'images/manage/trip.jpg\' style='.$style1.'>"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td >"+
												"<table border=0 class=mystyle valign=\'top\'>"+
													"<tr>"+
														"<td height=2px></td>"+
													"</tr>"+
													"<tr>"+
														"<td valign=\'top\'>"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_trip.htm\'); class=\'menuitem\'>"+
																"'.$contetnbsp.'Vehicle Trip"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+														
											"</td>"+
										"</tr>"+
									"</table>",editable:false, children:
									[]},';                                                                
									echo'{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td valign=\'top\'>"+
												"<table border=0 class=mystyle>"+													
													"<tr>"+
														"<td>'.$nbsp.'"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_geofence.htm\');>"+
																"<img src=\'images/manage/geofence.png\' style='.$style1.'>"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td >"+
												"<table border=0 class=mystyle valign=\'top\'>"+
													"<tr>"+
														"<td height=2px></td>"+
													"</tr>"+
													"<tr>"+
														"<td valign=\'top\'>"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_geofence.htm\'); class=\'menuitem\'>"+
																"'.$contetnbsp.'Geofence"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+														
											"</td>"+
										"</tr>"+
									"</table>",editable:false, children:
									[]},';
									echo'{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td valign=\'top\'>"+
												"<table border=0 class=mystyle>"+													
													"<tr>"+
														"<td>'.$nbsp.'"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_polyline.htm\');>"+
																"<img src=\'images/manage/geofence.png\' style='.$style1.'>"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td >"+
												"<table border=0 class=mystyle valign=\'top\'>"+
													"<tr>"+
														"<td height=2px></td>"+
													"</tr>"+
													"<tr>"+
														"<td valign=\'top\'>"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_polyline.htm\'); class=\'menuitem\'>"+
																"'.$contetnbsp.'Polyline"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+														
											"</td>"+
										"</tr>"+
									"</table>",editable:false, children:
									[]},';
									echo'{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td valign=\'top\'>"+
												"<table border=0 class=mystyle>"+													
													"<tr>"+
														"<td>'.$nbsp.'"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_polyline_history.htm\');>"+
																"<img src=\'images/manage/geofence.png\' style='.$style1.'>"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td >"+
												"<table border=0 class=mystyle valign=\'top\'>"+
													"<tr>"+
														"<td height=2px></td>"+
													"</tr>"+
													"<tr>"+
														"<td valign=\'top\'>"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_polyline_history.htm\'); class=\'menuitem\'>"+
																"'.$contetnbsp.'Route from History"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+														
											"</td>"+
										"</tr>"+
									"</table>",editable:false, children:
									[]},';
									if($flag_invoice)
									{
									echo'{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td valign=\'top\'>"+
												"<table border=0 class=mystyle>"+													
													"<tr>"+
														"<td>'.$nbsp.'"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_invoice.htm\');>"+
																"<img src=\'images/manage/invoice.png\' style='.$style1.'>"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td >"+
												"<table border=0 class=mystyle valign=\'top\'>"+
													"<tr>"+
														"<td height=2px></td>"+
													"</tr>"+
													"<tr>"+
														"<td valign=\'top\'>"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_invoice.htm\'); class=\'menuitem\'>"+
																"'.$contetnbsp.'Invoice"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+														
											"</td>"+
										"</tr>"+
									"</table>",editable:false, children:
									[]},';									
									}
									if($flag_raw_milk)
									{
									
                                                                        echo'{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td valign=\'top\'>"+
												"<table border=0 class=mystyle>"+													
													"<tr>"+
														"<td>'.$nbsp.'"+ 																				
															"<a href=javascript:manage_show_file_jquery(\'src/php/manage_invoice_raw_milk_material_prev.htm\');>"+
																"<img src=\'images/manage/invoice.png\' style='.$style1.'>"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td >"+
												"<table border=0 class=mystyle valign=\'top\'>"+
													"<tr>"+
														"<td height=2px></td>"+
													"</tr>"+
													"<tr>"+
														"<td valign=\'top\'>"+ 																				
															"<a href=javascript:manage_show_file_jquery(\'src/php/manage_invoice_raw_milk_material_prev.htm\'); class=\'menuitem\'>"+
																"'.$contetnbsp.'Raw Milk Material"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+														
											"</td>"+
										"</tr>"+
									"</table>",editable:false, children:
									[]},';	
                                                                                                                                                   
                                                                            
									echo'{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td valign=\'top\'>"+
												"<table border=0 class=mystyle>"+													
													"<tr>"+
														"<td>'.$nbsp.'"+ 																				
															"<a href=\'src/php/manage_invoice_milk_add_upload.htm\' target=\'_blank\' >"+
																"<img src=\'images/manage/invoice.png\' style='.$style1.'>"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td >"+
												"<table border=0 class=mystyle valign=\'top\'>"+
													"<tr>"+
														"<td height=2px></td>"+
													"</tr>"+
													"<tr>"+
														"<td valign=\'top\'>"+ 																				
															"<a href=\'src/php/manage_invoice_milk_add_upload.htm\' class=\'menuitem\' target=\'_blank\' >"+
																"'.$contetnbsp.'Create & Upload Invoice"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+														
											"</td>"+
										"</tr>"+
									"</table>",editable:false, children:
									[]},';
									
									echo'{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td valign=\'top\'>"+
												"<table border=0 class=mystyle>"+													
													"<tr>"+
														"<td>'.$nbsp.'"+ 																				
															"<a href=javascript:manage_show_file_jquery(\'src/php/manage_edit_invoice_raw_milk_admin_prev.htm\');>"+
																"<img src=\'images/manage/invoice.png\' style='.$style1.'>"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td >"+
												"<table border=0 class=mystyle valign=\'top\'>"+
													"<tr>"+
														"<td height=2px></td>"+
													"</tr>"+
													"<tr>"+
														"<td valign=\'top\'>"+ 																				
															"<a href=javascript:manage_show_file_jquery(\'src/php/manage_edit_invoice_raw_milk_admin_prev.htm\'); class=\'menuitem\'>"+
																"'.$contetnbsp.'Raw Milk Invoice"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+														
											"</td>"+
										"</tr>"+
									"</table>",editable:false, children:
									[]},';	
										
									echo'{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td valign=\'top\'>"+
												"<table border=0 class=mystyle>"+													
													"<tr>"+
														"<td>'.$nbsp.'"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_account_plant.htm\');>"+
																"<img src=\'images/manage/invoice.png\' style='.$style1.'>"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td >"+
												"<table border=0 class=mystyle valign=\'top\'>"+
													"<tr>"+
														"<td height=2px></td>"+
													"</tr>"+
													"<tr>"+
														"<td valign=\'top\'>"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_account_plant.htm\'); class=\'menuitem\'>"+
																"'.$contetnbsp.'Plant Account Assignment"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+														
											"</td>"+
										"</tr>"+
									"</table>",editable:false, children:
									[]},';
									
									echo'{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td valign=\'top\'>"+
												"<table border=0 class=mystyle>"+													
													"<tr>"+
														"<td>'.$nbsp.'"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_default_chilling_plant.htm\');>"+
																"<img src=\'images/manage/invoice.png\' style='.$style1.'>"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td >"+
												"<table border=0 class=mystyle valign=\'top\'>"+
													"<tr>"+
														"<td height=2px></td>"+
													"</tr>"+
													"<tr>"+
														"<td valign=\'top\'>"+ 																			
															"<a href=javascript:manage_show_file(\'src/php/manage_default_chilling_plant.htm\'); class=\'menuitem\'>"+
																"'.$contetnbsp.'Transporter Default Chilling Plant"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+														
											"</td>"+
										"</tr>"+
									"</table>",editable:false, children:
									[]},';
									
									}
								if($flag_hindalco_invoice)
									{
									echo'{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td valign=\'top\'>"+
												"<table border=0 class=mystyle>"+													
													"<tr>"+
														"<td>'.$nbsp.'"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_edit_hindalco_invoice_admin_prev.htm\');>"+
																"<img src=\'images/manage/invoice.png\' style='.$style1.'>"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td >"+
												"<table border=0 class=mystyle valign=\'top\'>"+
													"<tr>"+
														"<td height=2px></td>"+
													"</tr>"+
													"<tr>"+
														"<td valign=\'top\'>"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_edit_hindalco_invoice_admin_prev.htm\'); class=\'menuitem\'>"+
																"'.$contetnbsp.'Hindalco Invoice"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+														
											"</td>"+
										"</tr>"+
									"</table>",editable:false, children:
									[]},';	
									
									echo'{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td valign=\'top\'>"+
												"<table border=0 class=mystyle>"+													
													"<tr>"+
														"<td>'.$nbsp.'"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_substation_vehicle.htm\');>"+
																"<img src=\'images/manage/station.png\' style='.$style1.'>"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td >"+
												"<table border=0 class=mystyle valign=\'top\'>"+
													"<tr>"+
														"<td height=2px></td>"+
													"</tr>"+
													"<tr>"+
														"<td valign=\'top\'>"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_substation_vehicle.htm\'); class=\'menuitem\'>"+
																"Transporter'.$contetnbsp.''.$report_type.' Assignment"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+														
											"</td>"+
										"</tr>"+
									"</table>",editable:false, children:
									[]},';									
									
									}
								if($flag_vtrip)
								{	
								echo'{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td valign=\'top\'>"+
												"<table border=0 class=mystyle>"+													
													"<tr>"+
														"<td>'.$nbsp.'"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_vtrip.htm\');>"+
																"<img src=\'images/manage/station.png\' style='.$style1.'>"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td >"+
												"<table border=0 class=mystyle valign=\'top\'>"+
													"<tr>"+
														"<td height=2px></td>"+
													"</tr>"+
													"<tr>"+
														"<td valign=\'top\'>"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_vtrip.htm\'); class=\'menuitem\'>"+
																"'.$contetnbsp.'Vehicle Trip"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+														
											"</td>"+
										"</tr>"+
									"</table>",editable:false, children:
									[]},';            
								}							
						
								if($flag_station)
								{
									echo'{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td valign=\'top\'>"+
												"<table border=0 class=mystyle>"+													
													"<tr>"+
														"<td>'.$nbsp.'"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_station.htm\');>"+
																"<img src=\'images/manage/station.png\' style='.$style1.'>"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td >"+
												"<table border=0 class=mystyle valign=\'top\'>"+
													"<tr>"+
														"<td height=2px></td>"+
													"</tr>"+
													"<tr>"+
														"<td valign=\'top\'>"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_station.htm\'); class=\'menuitem\'>"+
																"'.$contetnbsp.'Station"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+														
											"</td>"+
										"</tr>"+
									"</table>",editable:false, children:
									[]},';
								
								if($flag_substation)
								{
									echo'{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td valign=\'top\'>"+
												"<table border=0 class=mystyle>"+													
													"<tr>"+
														"<td>'.$nbsp.'"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_route.htm\');>"+
																"<img src=\'images/manage/station.png\' style='.$style1.'>"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td >"+
												"<table border=0 class=mystyle valign=\'top\'>"+
													"<tr>"+
														"<td height=2px></td>"+
													"</tr>"+
													"<tr>"+
														"<td valign=\'top\'>"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_route_vehicle_assignment.htm\'); class=\'menuitem\'>"+
																"'.$contetnbsp.'Route Assignment"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+														
											"</td>"+
										"</tr>"+
									"</table>",editable:false, children:
									[]},';

									if($account_id=="231" || $account_id=="1115" || $account_id=="1100")
									{	
										echo'{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td valign=\'top\'>"+
												"<table border=0 class=mystyle>"+													
													"<tr>"+
														"<td>'.$nbsp.'"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_secondary_vehicle.htm\');>"+
																"<img src=\'images/manage/station.png\' style='.$style1.'>"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td >"+
												"<table border=0 class=mystyle valign=\'top\'>"+
													"<tr>"+
														"<td height=2px></td>"+
													"</tr>"+
													"<tr>"+
														"<td valign=\'top\'>"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_secondary_vehicle.htm\'); class=\'menuitem\'>"+
																"'.$contentnbsp.'Secondary Vehicle"+
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
								if($flag_raw_milk || $flag_substation)
								{
									echo'{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td valign=\'top\'>"+
												"<table border=0 class=mystyle>"+													
													"<tr>"+
														"<td>'.$nbsp.'"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_substation_vehicle.htm\');>"+
																"<img src=\'images/manage/station.png\' style='.$style1.'>"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td >"+
												"<table border=0 class=mystyle valign=\'top\'>"+
													"<tr>"+
														"<td height=2px></td>"+
													"</tr>"+
													"<tr>"+
														"<td valign=\'top\'>"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_substation_vehicle.htm\'); class=\'menuitem\'>"+
																"Substation'.$contetnbsp.''.$report_type.' Assignment"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+														
											"</td>"+
										"</tr>"+
									"</table>",editable:false, children:
									[]},';
								}	
									if($account_id=="1")
									{
										echo'{type:"Text",
										label:"<table border=0 class=mystyle>"+
											"<tr>"+ 																	
												"<td valign=\'top\'>"+
													"<table border=0 class=mystyle>"+													
														"<tr>"+
															"<td>'.$nbsp.'"+ 																				
																"<a href=javascript:manage_show_file(\'src/php/manage_upload_file_format.htm\');>"+
																	"<img src=\'images/manage/device.png\' style='.$style1.'>"+
																"</a>"+																
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
												"<td >"+
													"<table border=0 class=mystyle valign=\'top\'>"+
														"<tr>"+
															"<td height=2px></td>"+
														"</tr>"+
														"<tr>"+
															"<td valign=\'top\'>"+ 																				
																"<a href=javascript:manage_show_file(\'src/php/manage_upload_file_format.htm\'); class=\'menuitem\'>"+
																	"'.$contetnbsp.'Upload File"+
																"</a>"+																
															"</td>"+
														"</tr>"+
													"</table>"+														
												"</td>"+
											"</tr>"+
										"</table>",editable:false, children:
										[]},';
									}
									if($account_id=="696")
									{
										echo'{type:"Text",
											label:"<table border=0 class=mystyle>"+
												"<tr>"+ 																	
													"<td valign=\'top\'>"+
														"<table border=0 class=mystyle>"+													
															"<tr>"+
																"<td>'.$nbsp.'"+ 																				
																	"<a href=javascript:manage_show_file(\'src/php/manage_consignment.htm\');>"+
																		"<img src=\'images/manage/device.png\' style='.$style1.'>"+
																	"</a>"+																
																"</td>"+
															"</tr>"+
														"</table>"+
													"</td>"+
													"<td >"+
														"<table border=0 class=mystyle valign=\'top\'>"+
															"<tr>"+
																"<td height=2px></td>"+
															"</tr>"+
															"<tr>"+
																"<td valign=\'top\'>"+ 																				
																	"<a href=javascript:manage_show_file(\'src/php/manage_consignment.htm\'); class=\'menuitem\'>"+
																		"'.$contetnbsp.'Consignment"+
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
              					
								/*echo'{type:\'Text\', label:\'<table border=0><tr><td valign="top"><a href=javascript:manage_show_file("src/php/manage_visit_area.htm"); class="menuitem"><img src="images/manage/visit.png" style="border:none;width:20px;height:20px;"></a></td><td valign="top"><div style="height:2px"></div><a href=javascript:manage_show_file("src/php/manage_visit_area.htm"); class="menuitem">Visit</a></td></tr></table>\', editable:false, children:
								[							
								]},';*/
								if($flag_upl)
								{
									echo'{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td valign=\'top\'>"+
												"<table border=0 class=mystyle>"+													
													"<tr>"+
														"<td>'.$nbsp.'"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_schedule_upl.htm\');>"+
																"<img src=\'images/manage/visit.png\' style='.$style1.'>"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td >"+
												"<table border=0 class=mystyle valign=\'top\'>"+
													"<tr>"+
														"<td height=2px></td>"+
													"</tr>"+
													"<tr>"+
														"<td valign=\'top\'>"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_schedule_upl.htm\'); class=\'menuitem\'>"+
																"'.$contetnbsp.'ScheduleUPL"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+														
											"</td>"+
										"</tr>"+
									"</table>",editable:false, children:
									[]},';
								}
								if($flag_visit)
								{
								/*echo'{type:\'Text\', label:\'<table border=0><tr><td valign="top"><a href=javascript:manage_show_file("src/php/manage_visit_area.htm"); class="menuitem"><img src="images/manage/visit.png" style="border:none;width:20px;height:20px;"></a></td><td valign="top"><div style="height:2px"></div><a href=javascript:manage_show_file("src/php/manage_visit_area.htm"); class="menuitem">Visit</a></td></tr></table>\', editable:false, children:
								[							
								]},';*/
							echo'{type:"Text",
								label:"<table border=0 class=mystyle>"+
									"<tr>"+ 																	
										"<td valign=\'top\'>"+
											"<table border=0 class=mystyle>"+													
												"<tr>"+
													"<td>'.$nbsp.'"+ 																				
														"<a href=javascript:manage_show_file(\'src/php/manage_schedule.htm\');>"+
															"<img src=\'images/manage/visit.png\' style='.$style1.'>"+
														"</a>"+																
													"</td>"+
												"</tr>"+
											"</table>"+
										"</td>"+
										"<td >"+
											"<table border=0 class=mystyle valign=\'top\'>"+
												"<tr>"+
													"<td height=2px></td>"+
												"</tr>"+
												"<tr>"+
													"<td valign=\'top\'>"+ 																				
														"<a href=javascript:manage_show_file(\'src/php/manage_schedule.htm\'); class=\'menuitem\'>"+
															"'.$contetnbsp.'Schedule"+
														"</a>"+																
													"</td>"+
												"</tr>"+
											"</table>"+														
										"</td>"+
									"</tr>"+
								"</table>",editable:false, children:
								[]},';
								}								
						
								if($flag_sector)
								{
							echo'{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td valign=\'top\'>"+
												"<table border=0 class=mystyle>"+													
													"<tr>"+
														"<td>'.$nbsp.'"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_route.htm\');>"+
																"<img src=\'images/manage/route.png\' style='.$style1.'>"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td >"+
												"<table border=0 class=mystyle valign=\'top\'>"+
													"<tr>"+
														"<td height=2px></td>"+
													"</tr>"+
													"<tr>"+
														"<td valign=\'top\'>"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_route.htm\'); class=\'menuitem\'>"+
																"'.$contetnbsp.'Route"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+														
											"</td>"+
										"</tr>"+
									"</table>",editable:false, children:
									[]},';             
								}
						
								if($flag_load_cell)
								{
								echo'{type:"Text",
										label:"<table border=0 class=mystyle>"+
											"<tr>"+ 																	
												"<td valign=\'top\'>"+
													"<table border=0 class=mystyle>"+													
														"<tr>"+
															"<td>'.$nbsp.'"+ 																				
																"<a href=javascript:manage_show_file(\'src/php/manage_load_cell.htm\');>"+
																	"<img src=\'images/manage/load_cell.png\' style='.$style1.'>"+
																"</a>"+																
															"</td>"+
														"</tr>"+
													"</table>"+
												"</td>"+
												"<td >"+
													"<table border=0 class=mystyle valign=\'top\'>"+
														"<tr>"+
															"<td height=2px></td>"+
														"</tr>"+
														"<tr>"+
															"<td valign=\'top\'>"+ 																				
																"<a href=javascript:manage_show_file(\'src/php/manage_load_cell.htm\'); class=\'menuitem\'>"+
																	"'.$contetnbsp.'Load Cell"+
																"</a>"+																
															"</td>"+
														"</tr>"+
													"</table>"+														
												"</td>"+
											"</tr>"+
										"</table>",editable:false, children:
										[]},';
								}
              	
								/*echo'
									{type:'Text', label:'<table border=0><tr><td valign="top"><a href=javascript:manage_show_file("src/php/manage_route.htm"); class="menuitem"><img src="images/manage/route.png" style="border:none;width:20px;height:20px;"></a></td><td valign="top"><div style="height:2px"></div><a href=javascript:manage_show_file("src/php/manage_route.htm"); class="menuitem">Route</a></td></tr></table>', editable:false, children:
									[							
									]},
								'; */							
								/*if($report_type!="Person")
								{
									echo'{type:\'Text\', label:\'<table border=0><tr><td valign="top"><a href=javascript:manage_show_file("src/php/manage_transporters.htm"); class="menuitem"><img src="images/manage/publish.png" style="border:none;width:20px;height:20px;"></a></td><td valign="top"><div style="height:2px"></div><a href=javascript:manage_show_file("src/php/manage_transporters.htm"); class="menuitem">Publish Detail</a></td></tr></table>\', editable:false, children:
										[							
										]},';
								} */
						
							//{type:'Text', label:'<table border=0><tr><td valign="top"><a href=javascript:manage_show_file("src/php/manage_person.htm"); class="menuitem"><img src="images/manage/student.png" style="border:none;width:20px;height:20px;"></a></td><td valign="top"><div style="height:2px"></div><a href=javascript:manage_show_file("src/php/manage_person.htm"); class="menuitem">Person</a></td></tr></table>', editable:false, children:
							//[							
							//]},					
							if($report_type=="Person")
							{
							echo'{type:"Text",
									label:"<table border=0 class=mystyle>"+
										"<tr>"+ 																	
											"<td valign=\'top\'>"+
												"<table border=0 class=mystyle>"+													
													"<tr>"+
														"<td>'.$nbsp.'"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_visit.htm\');>"+
																"<img src=\'images/manage/student.png\' style='.$style1.'>"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+
											"</td>"+
											"<td >"+
												"<table border=0 class=mystyle valign=\'top\'>"+
													"<tr>"+
														"<td height=2px></td>"+
													"</tr>"+
													"<tr>"+
														"<td valign=\'top\'>"+ 																				
															"<a href=javascript:manage_show_file(\'src/php/manage_visit.htm\'); class=\'menuitem\'>"+
																"'.$contetnbsp.'Schedule Visit"+
															"</a>"+																
														"</td>"+
													"</tr>"+
												"</table>"+														
											"</td>"+
										"</tr>"+
									"</table>",editable:false, children:
									[]},';
  							}			
							if($mining_user_type==1)
							{
								echo'{type:"Text",
								label:"<table border=0 class=mystyle>"+
									"<tr>"+ 																	
										"<td valign=\'top\'>"+
											"<table border=0 class=mystyle>"+													
												"<tr>"+
													"<td>'.$nbsp.'"+ 																				
														"<a href=javascript:manage_show_file(\'src/php/manage_milestone.htm\');>"+
															"<img src=\'images/manage/student.png\' style='.$style1.'>"+
														"</a>"+																
													"</td>"+
												"</tr>"+
											"</table>"+
										"</td>"+
										"<td >"+
											"<table border=0 class=mystyle valign=\'top\'>"+
												"<tr>"+
													"<td height=2px></td>"+
												"</tr>"+
												"<tr>"+
													"<td valign=\'top\'>"+ 																				
														"<a href=javascript:manage_show_file(\'src/php/manage_milestone.htm\'); class=\'menuitem\'>"+
															"'.$contetnbsp.'Miles Stone"+
														"</a>"+																
													"</td>"+
												"</tr>"+
											"</table>"+														
										"</td>"+
									"</tr>"+
								"</table>",editable:false, children:
								[]},';								
							}
						?>
						
						]);
						tree.render();
						tree.subscribe('dblClickEvent',tree.onEventEditNode);
						};
						YAHOO.util.Event.onDOMReady(treeInit);
						})();
						</script>
					 </div>
					</td>
				</tr>            		          
          </table>
     	
   </td>
  </tr>
  <tr  class="mb3">
      <td>
          <?php include('module_copyright.htm');?>
      </td>
  </tr>
</table>							  
