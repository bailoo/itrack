<table border='0' width="100%" height="100%" cellspacing="0" cellpadding="0">
    <tr class="help_left_tr1">
      <td>
          <?php 
            include('module_logo.php');	//echo "user_type=".$user_type;
			$nbsp="";
          ?> 
      </td>
    </tr>
	
  <tr class="help_left_tr2">
    <td valign="top">       
          <table border='0' width="100%" class="menu" cellspacing="0" cellpadding="0">		           
              <?php
                echo'<tr>
						<td> 
						<table width="100%" border="0" bgcolor="grey" cellspacing="1" cellpadding="1">
							<tr>
								<td><strong>&nbsp;Help</strong></td>
							</tr>
						</table>
					  </td>
					</tr> 
                      
					<tr>
						<td>
							<div style="overflow-x:hidden;overflow-y:auto;height:520px;" id="rightMenu">';
							$js_function_name = "display_page";    // FUNCTION NAME             		
						echo'<div id="treeView" style="background-color:white;margin:7px;"> </div>
								<div id="msg">&nbsp;</div>
									<script type="text/javascript">
										var tree;
										(function(){
												var treeInit = function()
												{
													tree = new YAHOO.widget.TreeView("treeView",
													[					
														//////// ***************** HOME INTERFACE ****************//////////														
														{type:"Text",
																label:"<table border=0 class=mystyle>"+
																		"<tr>"+ 
																			"<td>"+
																				"<table border=0 class=\'mystyle\'>"+
																					"<tr>"+
																						"<td height=\'3px\'></td>"+
																					"</tr>"+
																					"<tr>"+
																						"<td>&nbsp;"+ 																
																							"<img src=\'images/help/book.jpg\' class=\'help_img_css\'>"+ 															
																						"</td>"+
																					"</tr>"+
																				"</table>"+
																			"</td>"+
																			"<td>"+
																				"<table border=0 class=mystyle>"+
																					"<tr>"+
																						"<td height=\'2px\'></td>"+
																					"</tr>"+
																					"<tr>"+
																						"<td valign=\'top\'>&nbsp;"+ 																				
																							"About Home Interface"+ 																	
																						"</td>"+
																					"</tr>"+
																				"</table>"+
																			"</td>"+
																		"</tr>"+
																	"</table>",editable:false, children:
															[
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_input_selection.htm\',\'Input%20Selection\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_input_selection.htm\',\'Input%20Selection\'); class=\'menuitem\'>"+
																									"'.$nbsp.'Input Selection"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:																	
																[]},
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_last_position.htm\',\'Last%20Position\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_last_position.htm\',\'Last%20Position\'); class=\'menuitem\'>"+
																									"'.$nbsp.'Last Position"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:	
																[]},
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_track.htm\',\'Track\'); class=\'menuitem\'>"+
																									"<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																							"<td>'.$nbsp.'"+ 
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_track.htm\',\'Track\'); class=\'menuitem\'>"+
																									"'.$nbsp.'Track"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																[]},
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_map_controls.htm\',\'Map%20Controls\'); class=\'menuitem\'>"+
																									"<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																							"<td>'.$nbsp.'"+ 
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_map_controls.htm\',\'Map%20Controls\'); class=\'menuitem\'>"+
																									"'.$nbsp.'Map Controls"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		[]},  																						
															
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_last_position_report.htm\',\'Last%20Position%20Report\'); class=\'menuitem\'>"+
																									"<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																							"<td>'.$nbsp.'"+ 
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_last_position_report.htm\',\'Last%20Position%20Report\'); class=\'menuitem\'>"+
																									"'.$nbsp.'Last Position Report"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:																
																		[]},  
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_track_report.htm\',\'Track%20Report\'); class=\'menuitem\'>"+
																									"<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																							"<td>'.$nbsp.'"+ 
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_track_report.htm\',\'Track%20Report\'); class=\'menuitem\'>"+
																									"'.$nbsp.'Track Report"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:																
																		[]},   																
															]
														},														
														/////////////// *********** LIVE INTERFACE *************////////////
														{type:"Text",
															label:"<table border=0 class=mystyle>"+
																		"<tr>"+ 
																			"<td>"+
																				"<table border=0 class=\'mystyle\'>"+
																					"<tr>"+
																						"<td height=\'3px\'></td>"+
																					"</tr>"+
																					"<tr>"+
																						"<td>&nbsp;"+ 																
																							"<img src=\'images/help/book.jpg\' class=\'help_img_css\'>"+ 															
																						"</td>"+
																					"</tr>"+
																				"</table>"+
																			"</td>"+
																			"<td>"+
																				"<table border=0 class=mystyle>"+
																					"<tr>"+
																						"<td height=\'2px\'></td>"+
																					"</tr>"+
																					"<tr>"+
																						"<td valign=\'top\'>&nbsp;"+ 																				
																							"About Live Interface"+ 																	
																						"</td>"+
																					"</tr>"+
																				"</table>"+
																			"</td>"+
																		"</tr>"+
																	"</table>",editable:false, children:															
															[  														  																										    													   													

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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_live_vehicle_status.htm\',\'Last%20Position\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_live_vehicle_status.htm\',\'Last%20Position\'); class=\'menuitem\'>"+
																									"'.$nbsp.'Vehicle Status"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:																
																[]},
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_live_refresh_rate.htm\',\'Last%20Position\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_live_refresh_rate.htm\',\'Last%20Position\'); class=\'menuitem\'>"+
																									"'.$nbsp.'Refresh Rate"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:																
																[]},
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_live_position_on_map.htm\',\'Last%20Position\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_live_position_on_map.htm\',\'Last%20Position\'); class=\'menuitem\'>"+
																									"'.$nbsp.'Position on Map"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:															
																[]},																																
															]
														},																					
														
														//////// ***************** MANAGE MENU *******************//////////
														
														{type:"Text",
															label:"<table border=0 class=mystyle>"+
																		"<tr>"+ 
																			"<td>"+
																				"<table border=0 class=\'mystyle\'>"+
																					"<tr>"+
																						"<td height=\'3px\'></td>"+
																					"</tr>"+
																					"<tr>"+
																						"<td>&nbsp;"+ 																
																							"<img src=\'images/help/book.jpg\' class=\'help_img_css\'>"+ 															
																						"</td>"+
																					"</tr>"+
																				"</table>"+
																			"</td>"+
																			"<td>"+
																				"<table border=0 class=mystyle>"+
																					"<tr>"+
																						"<td height=\'2px\'></td>"+
																					"</tr>"+
																					"<tr>"+
																						"<td valign=\'top\'>&nbsp;"+ 																				
																							"About Manage Menu"+ 																	
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
																				"<table border=0 class=\'mystyle\'>"+
																					"<tr>"+
																						"<td height=\'3px\'></td>"+
																					"</tr>"+
																					"<tr>"+
																						"<td>&nbsp;"+ 																
																							"<img src=\'images/help/book.jpg\' class=\'help_img_css\'>"+ 															
																						"</td>"+
																					"</tr>"+
																				"</table>"+
																			"</td>"+
																			"<td>"+
																				"<table border=0 class=mystyle>"+
																					"<tr>"+
																						"<td height=\'2px\'></td>"+
																					"</tr>"+
																					"<tr>"+
																						"<td valign=\'top\'>&nbsp;"+ 																				
																							"Handling Group"+ 																	
																						"</td>"+
																					"</tr>"+
																				"</table>"+
																			"</td>"+
																		"</tr>"+
																	"</table>",editable:false, children:															
																	[
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_group_add.htm\',\'Last%20Position\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_group_add.htm\',\'Last%20Position\'); class=\'menuitem\'>"+
																									"'.$nbsp.'How to add group"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		[]},
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_group_edit_delete.htm\',\'Last%20Position\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_group_edit_delete.htm\',\'Last%20Position\'); class=\'menuitem\'>"+
																									"'.$nbsp.'How to edit/Delete group"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		[]},     													    													   													
																	]
																},
															
																{type:"Text",
																	label:"<table border=0 class=mystyle>"+
																		"<tr>"+ 
																			"<td>"+
																				"<table border=0 class=\'mystyle\'>"+
																					"<tr>"+
																						"<td height=\'3px\'></td>"+
																					"</tr>"+
																					"<tr>"+
																						"<td>&nbsp;"+ 																
																							"<img src=\'images/help/book.jpg\' class=\'help_img_css\'>"+ 															
																						"</td>"+
																					"</tr>"+
																				"</table>"+
																			"</td>"+
																			"<td>"+
																				"<table border=0 class=mystyle>"+
																					"<tr>"+
																						"<td height=\'2px\'></td>"+
																					"</tr>"+
																					"<tr>"+
																						"<td valign=\'top\'>&nbsp;"+ 																				
																							"Handling Account"+ 																	
																						"</td>"+
																					"</tr>"+
																				"</table>"+
																			"</td>"+
																		"</tr>"+
																	"</table>",editable:false, children:																	
																	[
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_account_add.htm\',\'Last%20Position\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_account_add.htm\',\'Last%20Position\'); class=\'menuitem\'>"+
																									"'.$nbsp.'How to add account"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		 []},
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_account_edit.htm\',\'Last%20Position\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_account_edit.htm\',\'Last%20Position\'); class=\'menuitem\'>"+
																									"'.$nbsp.'How to edit account"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		[]},
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_account_delete.htm\',\'Last%20Position\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_account_delete.htm\',\'Last%20Position\'); class=\'menuitem\'>"+
																									"'.$nbsp.'How to delete account"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		[]},
																	]
																},
																{type:"Text",
																label:"<table border=0 class=mystyle>"+
																		"<tr>"+ 
																			"<td>"+
																				"<table border=0 class=\'mystyle\'>"+
																					"<tr>"+
																						"<td height=\'3px\'></td>"+
																					"</tr>"+
																					"<tr>"+
																						"<td>&nbsp;"+ 																
																							"<img src=\'images/help/book.jpg\' class=\'help_img_css\'>"+ 															
																						"</td>"+
																					"</tr>"+
																				"</table>"+
																			"</td>"+
																			"<td>"+
																				"<table border=0 class=mystyle>"+
																					"<tr>"+
																						"<td height=\'2px\'></td>"+
																					"</tr>"+
																					"<tr>"+
																						"<td valign=\'top\'>&nbsp;"+ 																				
																							"Handling Vehicle"+ 																	
																						"</td>"+
																					"</tr>"+
																				"</table>"+
																			"</td>"+
																		"</tr>"+
																	"</table>",editable:false, children:															
																	[
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_vehicle_add.htm\',\'Add%20Vehicle%20Steps\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_vehicle_add.htm\',\'Add%20Vehicle%20Steps\'); class=\'menuitem\'>"+
																									"'.$nbsp.'How to add vehicle"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		 []},
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_vehicle_edit.htm\',\'Edit%20Vehicle%20Steps\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_vehicle_edit.htm\',\'Edit%20Vehicle%20Steps\'); class=\'menuitem\'>"+
																									"'.$nbsp.'How to edit vehicle"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		[]},
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_vehicle_register.htm\',\'Register%20Vehicle%20Steps\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_vehicle_register.htm\',\'Register%20Vehicle%20Steps\'); class=\'menuitem\'>"+
																									"'.$nbsp.'How to register vehicle"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		[]},
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_vehicle_deregister.htm\',\'De-Register%20Vehicle%20Steps\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_vehicle_deregister.htm\',\'De-Register%20Vehicle%20Steps\'); class=\'menuitem\'>"+
																									"'.$nbsp.'How to de-register vehicle"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		[]},	
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_vehicle_assign_deassign.htm\',\'Assign/Deassign%20Vehicle%20Steps\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_vehicle_assign_deassign.htm\',\'Assign/Deassign%20Vehicle%20Steps\'); class=\'menuitem\'>"+
																									"'.$nbsp.'How to assign/de-assign vehicle"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		[]},							
																	]
																}, 
																{type:"Text",
																label:"<table border=0 class=mystyle>"+
																		"<tr>"+ 
																			"<td>"+
																				"<table border=0 class=\'mystyle\'>"+
																					"<tr>"+
																						"<td height=\'3px\'></td>"+
																					"</tr>"+
																					"<tr>"+
																						"<td>&nbsp;"+ 																
																							"<img src=\'images/help/book.jpg\' class=\'help_img_css\'>"+ 															
																						"</td>"+
																					"</tr>"+
																				"</table>"+
																			"</td>"+
																			"<td>"+
																				"<table border=0 class=mystyle>"+
																					"<tr>"+
																						"<td height=\'2px\'></td>"+
																					"</tr>"+
																					"<tr>"+
																						"<td valign=\'top\'>&nbsp;"+ 																				
																							"Handling Device"+ 																	
																						"</td>"+
																					"</tr>"+
																				"</table>"+
																			"</td>"+
																		"</tr>"+
																	"</table>",editable:false, children:	
															
																	[
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_assign_deassign_device.htm\',\'Assignment%20De-Assignment%20Device\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_assign_deassign_device.htm\',\'Assignment%20De-Assignment%20Device\'); class=\'menuitem\'>"+
																									"'.$nbsp.'Assign/De-Assign steps"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		[]},
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_edit_device.htm\',\'Edit%20/%20Device\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_edit_device.htm\',\'Edit%20/%20Device\'); class=\'menuitem\'>"+
																									"'.$nbsp.'How to edit/Delete group"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		[]},     													    													   													
																	]
																}, 
																{type:"Text",
																label:"<table border=0 class=mystyle>"+
																		"<tr>"+ 
																			"<td>"+
																				"<table border=0 class=\'mystyle\'>"+
																					"<tr>"+
																						"<td height=\'3px\'></td>"+
																					"</tr>"+
																					"<tr>"+
																						"<td>&nbsp;"+ 																
																							"<img src=\'images/help/book.jpg\' class=\'help_img_css\'>"+ 															
																						"</td>"+
																					"</tr>"+
																				"</table>"+
																			"</td>"+
																			"<td>"+
																				"<table border=0 class=mystyle>"+
																					"<tr>"+
																						"<td height=\'2px\'></td>"+
																					"</tr>"+
																					"<tr>"+
																						"<td valign=\'top\'>&nbsp;"+ 																				
																							"Handling Geofence"+ 																	
																						"</td>"+
																					"</tr>"+
																				"</table>"+
																			"</td>"+
																		"</tr>"+
																	"</table>",editable:false, children:	
															
																	[
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_geofence_add.htm\',\'Add%20Geofence\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_geofence_add.htm\',\'Add%20Geofence\'); class=\'menuitem\'>"+
																									"'.$nbsp.'Add Steps"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		[]},
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_geofence_edit_delete.htm\',\'Edit%20Delete%20Geofence\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_geofence_edit_delete.htm\',\'Edit%20Delete%20Geofence\'); class=\'menuitem\'>"+
																									"'.$nbsp.'Edit Delete Steps"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		[]},
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_geofence_assign.htm\',\'Assign%20Geofence\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_geofence_assign.htm\',\'Assign%20Geofence\'); class=\'menuitem\'>"+
																									"'.$nbsp.'Assign Steps"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		[]},
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_geofence_deassign.htm\',\'De-Assign%20Vehicle\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_geofence_deassign.htm\',\'De-Assign%20Vehicle\'); class=\'menuitem\'>"+
																									"'.$nbsp.'De Assign Steps"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		[]},  														
																	]
																},								
															]
														},
														
														
														//////// ***************** REPORT MENU *******************//////////														
														{type:"Text",
															label:"<table border=0 class=mystyle>"+
																		"<tr>"+ 
																			"<td>"+
																				"<table border=0 class=\'mystyle\'>"+
																					"<tr>"+
																						"<td height=\'3px\'></td>"+
																					"</tr>"+
																					"<tr>"+
																						"<td>&nbsp;"+ 																
																							"<img src=\'images/help/book.jpg\' class=\'help_img_css\'>"+ 															
																						"</td>"+
																					"</tr>"+
																				"</table>"+
																			"</td>"+
																			"<td>"+
																				"<table border=0 class=mystyle>"+
																					"<tr>"+
																						"<td height=\'2px\'></td>"+
																					"</tr>"+
																					"<tr>"+
																						"<td valign=\'top\'>&nbsp;"+ 																				
																							"About Report Menu"+ 																	
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
																				"<table border=0 class=\'mystyle\'>"+
																					"<tr>"+
																						"<td height=\'3px\'></td>"+
																					"</tr>"+
																					"<tr>"+
																						"<td>&nbsp;"+ 																
																							"<img src=\'images/help/book.jpg\' class=\'help_img_css\'>"+ 															
																						"</td>"+
																					"</tr>"+
																				"</table>"+
																			"</td>"+
																			"<td>"+
																				"<table border=0 class=mystyle>"+
																					"<tr>"+
																						"<td height=\'2px\'></td>"+
																					"</tr>"+
																					"<tr>"+
																						"<td valign=\'top\'>&nbsp;"+ 																				
																							"Regular"+ 																	
																						"</td>"+
																					"</tr>"+
																				"</table>"+
																			"</td>"+
																		"</tr>"+
																	"</table>",editable:false, children:	
																	[
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_report_vehicle.htm\',\'Vehicle%20Report\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_report_vehicle.htm\',\'Vehicle%20Report\'); class=\'menuitem\'>"+
																									"'.$nbsp.'Vehicle Report"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		[]},
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_report_speed.htm\',\'Speed%20Report\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_report_speed.htm\',\'Speed%20Report\'); class=\'menuitem\'>"+
																									"'.$nbsp.'Speed Report"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		[]},
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_report_distance.htm\',\'Distance%20Report\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_report_distance.htm\',\'Distance%20Report\'); class=\'menuitem\'>"+
																									"'.$nbsp.'Distance Report"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		[]}, 
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_report_monthlydist.htm\',\'Monthly%20Report\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_report_monthlydist.htm\',\'Monthly%20Report\'); class=\'menuitem\'>"+
																									"'.$nbsp.'Monthly Report"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		[]}, 
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_report_fuel.htm\',\'Fuel%20Report\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_report_fuel.htm\',\'Fuel%20Report\'); class=\'menuitem\'>"+
																									"'.$nbsp.'Fuel Report"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		[]}, 
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_report_summary.htm\',\'Summary%20Report\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_report_summary.htm\',\'Summary%20Report\'); class=\'menuitem\'>"+
																									"'.$nbsp.'Summary Report"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		[]}, 
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_report_engine_runhr.htm\',\'Engine%20Runhr%20Report\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_report_engine_runhr.htm\',\'Engine%20Runhr%20Report\'); class=\'menuitem\'>"+
																									"'.$nbsp.'Engine Runhr Report"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		[]}, 
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_report_halt.htm\',\'Halt%20Report\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_report_halt.htm\',\'Halt%20Report\'); class=\'menuitem\'>"+
																									"'.$nbsp.'Halt Report"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		[]}, 
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_report_travel.htm\',\'Travel%20Report\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_report_travel.htm\',\'Travel%20Report\'); class=\'menuitem\'>"+
																									"'.$nbsp.'Travel Report"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		[]}, 
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_report_performance.htm\',\'Performance%20Report\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_report_performance.htm\',\'Performance%20Report\'); class=\'menuitem\'>"+
																									"'.$nbsp.'Performance Report"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		[]}, 															
																	]
																},  														
																{type:"Text",
																	label:"<table border=0 class=mystyle>"+
																		"<tr>"+ 
																			"<td>"+
																				"<table border=0 class=\'mystyle\'>"+
																					"<tr>"+
																						"<td height=\'3px\'></td>"+
																					"</tr>"+
																					"<tr>"+
																						"<td>&nbsp;"+ 																
																							"<img src=\'images/help/book.jpg\' class=\'help_img_css\'>"+ 															
																						"</td>"+
																					"</tr>"+
																				"</table>"+
																			"</td>"+
																			"<td>"+
																				"<table border=0 class=mystyle>"+
																					"<tr>"+
																						"<td height=\'2px\'></td>"+
																					"</tr>"+
																					"<tr>"+
																						"<td valign=\'top\'>&nbsp;"+ 																				
																							"Alert"+ 																	
																						"</td>"+
																					"</tr>"+
																				"</table>"+
																			"</td>"+
																		"</tr>"+
																	"</table>",editable:false, children:																
																	[
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_alert_geofence_violation.htm\',\'Geofence%20Violation\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_alert_geofence_violation.htm\',\'Geofence%20Violation\'); class=\'menuitem\'>"+
																									"'.$nbsp.'Geofence Violation"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		[]},
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_alert_speed_violation.htm\',\'Speed%20Violation\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_alert_speed_violation.htm\',\'Speed%20Violation\'); class=\'menuitem\'>"+
																									"'.$nbsp.'Speed Violation"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		[]},    														
																	]
																},
																{type:"Text",															
																	label:"<table border=0 class=mystyle>"+
																		"<tr>"+ 
																			"<td>"+
																				"<table border=0 class=\'mystyle\'>"+
																					"<tr>"+
																						"<td height=\'3px\'></td>"+
																					"</tr>"+
																					"<tr>"+
																						"<td>&nbsp;"+ 																
																							"<img src=\'images/help/book.jpg\' class=\'help_img_css\'>"+ 															
																						"</td>"+
																					"</tr>"+
																				"</table>"+
																			"</td>"+
																			"<td>"+
																				"<table border=0 class=mystyle>"+
																					"<tr>"+
																						"<td height=\'2px\'></td>"+
																					"</tr>"+
																					"<tr>"+
																						"<td valign=\'top\'>&nbsp;"+ 																				
																							"Graph"+ 																	
																						"</td>"+
																					"</tr>"+
																				"</table>"+
																			"</td>"+
																		"</tr>"+
																	"</table>",editable:false, children:																
																	[
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_graph_speed.htm\',\'Speed%20Graph\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_graph_speed.htm\',\'Speed%20Graph\'); class=\'menuitem\'>"+
																									"'.$nbsp.'Speed Graph"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		[]},
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_graph_fuel.htm\',\'Fuel%20Graph\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_graph_fuel.htm\',\'Fuel%20Graph\'); class=\'menuitem\'>"+
																									"'.$nbsp.'Fuel Graph"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		[]},    																		
																	]
																}, 
																{type:"Text",
																label:"<table border=0 class=mystyle>"+
																		"<tr>"+ 
																			"<td>"+
																				"<table border=0 class=\'mystyle\'>"+
																					"<tr>"+
																						"<td height=\'3px\'></td>"+
																					"</tr>"+
																					"<tr>"+
																						"<td>&nbsp;"+ 																
																							"<img src=\'images/help/book.jpg\' class=\'help_img_css\'>"+ 															
																						"</td>"+
																					"</tr>"+
																				"</table>"+
																			"</td>"+
																			"<td>"+
																				"<table border=0 class=mystyle>"+
																					"<tr>"+
																						"<td height=\'2px\'></td>"+
																					"</tr>"+
																					"<tr>"+
																						"<td valign=\'top\'>&nbsp;"+ 																				
																							"Data Log"+ 																	
																						"</td>"+
																					"</tr>"+
																				"</table>"+
																			"</td>"+
																		"</tr>"+
																	"</table>",editable:false, children:																
																	[
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_datalog_today_record.htm\',\'Today%20Record\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_datalog_today_record.htm\',\'Today%20Record\'); class=\'menuitem\'>"+
																									"'.$nbsp.'Today Record"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		[]},
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_datalog_between_dates.htm\',\'Between%20Date\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_datalog_between_dates.htm\',\'Between%20Date\'); class=\'menuitem\'>"+
																									"'.$nbsp.'Between Dates"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		[]},

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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_datalog_specify_vehicle.htm\',\'Specify%20Vehicle\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_datalog_specify_vehicle.htm\',\'Specify%20Vehicle\'); class=\'menuitem\'>"+
																									"'.$nbsp.'Specify Vehicle"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		[]},     			

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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_datalog_search_vehicle.htm\',\'Search%20Vehicle\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_datalog_search_vehicle.htm\',\'Search%20Vehicle\'); class=\'menuitem\'>"+
																									"'.$nbsp.'Search Vehicle"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		[]},     																		
																	]
																}, 
																{type:"Text",
																	label:"<table border=0 class=mystyle>"+
																		"<tr>"+ 
																			"<td>"+
																				"<table border=0 class=\'mystyle\'>"+
																					"<tr>"+
																						"<td height=\'3px\'></td>"+
																					"</tr>"+
																					"<tr>"+
																						"<td>&nbsp;"+ 																
																							"<img src=\'images/help/book.jpg\' class=\'help_img_css\'>"+ 															
																						"</td>"+
																					"</tr>"+
																				"</table>"+
																			"</td>"+
																			"<td>"+
																				"<table border=0 class=mystyle>"+
																					"<tr>"+
																						"<td height=\'2px\'></td>"+
																					"</tr>"+
																					"<tr>"+
																						"<td valign=\'top\'>&nbsp;"+ 																				
																							"Data Gap"+ 																	
																						"</td>"+
																					"</tr>"+
																				"</table>"+
																			"</td>"+
																		"</tr>"+
																	"</table>",editable:false, children:															
																	[
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_datagap_nodata_nogps.htm\',\'NoData%20NoGPS\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_datagap_nodata_nogps.htm\',\'NoData%20NoGPS\'); class=\'menuitem\'>"+
																									"'.$nbsp.'NoData/NoGPS"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		[]},    																			
																	]
																},							
															]
														},															
															
														
														//////// ***************** SETTING MENU *******************//////////														
														{type:"Text",
															label:"<table border=0 class=mystyle>"+
																		"<tr>"+ 
																			"<td>"+
																				"<table border=0 class=\'mystyle\'>"+
																					"<tr>"+
																						"<td height=\'3px\'></td>"+
																					"</tr>"+
																					"<tr>"+
																						"<td>&nbsp;"+ 																
																							"<img src=\'images/help/book.jpg\' class=\'help_img_css\'>"+ 															
																						"</td>"+
																					"</tr>"+
																				"</table>"+
																			"</td>"+
																			"<td>"+
																				"<table border=0 class=mystyle>"+
																					"<tr>"+
																						"<td height=\'2px\'></td>"+
																					"</tr>"+
																					"<tr>"+
																						"<td valign=\'top\'>&nbsp;"+ 																				
																							"About Setting Interface"+ 																	
																						"</td>"+
																					"</tr>"+
																				"</table>"+
																			"</td>"+
																		"</tr>"+
																	"</table>",editable:false, children:															
															[  														  																										    													   													
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_setting_account_detail.htm\',\'Account%20Detail\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_setting_account_detail.htm\',\'Account%20Detail\'); class=\'menuitem\'>"+
																									"'.$nbsp.'Account Detail"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		[]},
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_setting_account_preference.htm\',\'Account%20Preference\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_setting_account_preference.htm\',\'Account%20Preference\'); class=\'menuitem\'>"+
																									"'.$nbsp.'Account Preference"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																	[]},
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_setting_change_password.htm\',\'Change%20Password\'); class=\'menuitem\'>"+
																									"'.$nbsp.'<img src=\'images/help/file.jpg\' class=\'help_img_css\'>"+
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
																								"<a href=javascript:'.$js_function_name.'(\'src/php/help_setting_change_password.htm\',\'Change%20Password\'); class=\'menuitem\'>"+
																									"'.$nbsp.'Change Password"+
																								"</a>"+
																							"</td>"+
																						"</tr>"+
																					"</table>"+
																				"</td>"+
																			"</tr>"+
																		"</table>",editable:false, children:
																		[]},																															
															]
														},																																			
													'; 																			
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
  <tr class="help_eft_tr3">
      <td>
		<?php 
			if(file_exists('module_copyright.htm'))
			{
			include('module_copyright.htm');
			}
		?>
      </td>
  </tr>
</table>					
  	    

		  				
  	    

		  