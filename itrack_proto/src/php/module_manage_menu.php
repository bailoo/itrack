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
								 if($account_id==1)
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
          <?php include('module_copyright.php');?>
      </td>
  </tr>
</table>							  
