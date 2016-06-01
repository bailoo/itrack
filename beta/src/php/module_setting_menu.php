<?php
  //echo "<br>usertype=".$user_type;
  include("user_type_setting.php");
?>
<table border='0' width="100%" height="100%" cellspacing="0" cellpadding="0">
<!--<tr class="setting_left_tr1">
      <td>
          <?php 
            include('module_logo.php');	//echo "user_type=".$user_type;            
          ?> 
      </td>
    </tr>-->
  <tr class="setting_left_tr2">
    <td valign="top">       
          <table border='0' width="100%" class="menu" cellspacing="0" cellpadding="0">		           
              <?php
				$nbsp="&nbsp;";
                echo'<tr>
						<td> 
						<table class="menu alert alert-warning" width="100%" border="0" bgcolor="" cellspacing="1" cellpadding="1">
							<tr>
								<td><strong>&nbsp;Settings</strong></td>
							</tr>
						</table>
					  </td>
					</tr>                
					<tr>
						<td>
							<div style="overflow-x:hidden;overflow-y:auto;" id="rightMenu">';
							$js_function_name = "setting_account_detail";    // FUNCTION NAME 
							$js_function_name1 = "setting_show_file"; 			
						echo'<div id="treeView" style="background-color:white;margin:7px;"> </div>
								<div id="msg">&nbsp;</div>
									<script type="text/javascript">
										var tree;
										(function(){
												var treeInit = function()
												{
													tree = new YAHOO.widget.TreeView("treeView",
													[					
														{type:"Text",
															label:"<table border=0 class=\'mystyle\'>"+
																"<tr>"+ 																				
																	"<td valign=\'top\'>"+
																		"<table border=0 class=\'mystyle\' valign=\'top\'>"+
																			"<tr>"+
																				"<td height=\'1px\'></td>"+
																			"</tr>"+
																			"<tr>"+
																				"<td>'.$nbsp.'"+ 
																					"<a href=javascript:'.$js_function_name.'(\'src/php/setting_update_account_prev.htm\',\'first_stage\',\'Account%20Detail\'); class=\'menuitem\'>"+
																						"'.$nbsp.'Account Detail"+
																					"</a>"+
																				"</td>"+
																			"</tr>"+
																		"</table>"+
																	"</td>"+
																"</tr>"+
															"</table>",editable:false, children:															
															[
															]},
														{type:"Text",
															label:"<table border=0 class=\'mystyle\'>"+
																"<tr>"+ 																				
																	"<td valign=\'top\'>"+
																		"<table border=0 class=\'mystyle\'>"+
																			"<tr>"+
																				"<td height=\'1px\'></td>"+
																			"</tr>"+
																			"<tr>"+
																				"<td>'.$nbsp.'"+ 
																					"<a href=javascript:'.$js_function_name.'(\'src/php/setting_account_preferrence_prev.htm\',\'first_stage\',\'Account%20Preferrence\'); class=\'menuitem\'>"+
																						"'.$nbsp.'Account Preferrence"+
																					"</a>"+
																				"</td>"+
																			"</tr>"+
																		"</table>"+
																	"</td>"+
																"</tr>"+
															"</table>",editable:false, children:													
															[
															]},
														{type:"Text",
															label:"<table border=0 class=\'mystyle\'>"+
																"<tr>"+ 																				
																	"<td valign=\'top\'>"+
																		"<table border=0 class=\'mystyle\'>"+
																			"<tr>"+
																				"<td height=\'1px\'></td>"+
																			"</tr>"+
																			"<tr>"+
																				"<td>'.$nbsp.'"+ 
																					"<a href=javascript:'.$js_function_name.'(\'src/php/setting_account_password_prev.htm\',\'first_stage\',\'Change%20Password\'); class=\'menuitem\'>"+
																						"'.$nbsp.'Change Password"+
																					"</a>"+
																				"</td>"+
																			"</tr>"+
																		"</table>"+
																	"</td>"+
																"</tr>"+
															"</table>",editable:false, children:															
															[
															]},
															
															{type:"Text",
															label:"<table border=0 class=\'mystyle\'>"+
																"<tr>"+ 																				
																	"<td valign=\'top\'>"+
																		"<table border=0 class=\'mystyle\'>"+
																			"<tr>"+
																				"<td height=\'1px\'></td>"+
																			"</tr>"+
																			"<tr>"+
																				"<td>'.$nbsp.'"+ 
																					"<a href=javascript:'.$js_function_name.'(\'src/php/setting_color_prev.htm\',\'first_stage\',\'Change%20Color\'); class=\'menuitem\'>"+
																						"'.$nbsp.'Change Color"+
																					"</a>"+
																				"</td>"+
																			"</tr>"+
																		"</table>"+
																	"</td>"+
																"</tr>"+
															"</table>",editable:false, children:															
															[
															]},															
															
															
															';
								
															if($school_user_type==1)
															{
														echo'{type:"Text", label:"School", editable:false, children:
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
																							"<a href=javascript:'.$js_function_name1.'(\'src/php/setting_student.htm\'); class=\'menuitem\'>"+
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
																							"<a href=javascript:'.$js_function_name1.'(\'src/php/setting_student.htm\'); class=\'menuitem\'>"+
																								"'.$nbsp.'Change Mobile"+
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
																							"<a href=javascript:'.$js_function_name1.'(\'src/php/setting_configure_alert_prev.htm\'); class=\'menuitem\'>"+
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
																							"<a href=javascript:'.$js_function_name1.'(\'src/php/setting_configure_alert_prev.htm\'); class=\'menuitem\'>"+
																								"'.$nbsp.'Configure Alerts"+
																							"</a>"+
																						"</td>"+
																					"</tr>"+
																				"</table>"+
																			"</td>"+
																		"</tr>"+
																	"</table>"
																]}, 
															 ';
														  } 														
									echo']);
									tree.render();
									tree.subscribe(\'dblClickEvent\',tree.onEventEditNode);
								};
								YAHOO.util.Event.onDOMReady(treeInit);
							})();
					</script>
				</td>
			</div>
		</tr> ';                            
              ?>  		          
       </table>      	
   </td>
  </tr>
  <tr class="setting_left_tr3">
      <td>
          <?php include('module_copyright.htm');?>
      </td>
  </tr>
</table>					
  	    

		  