<tr>
  <td align="left"> 
    <div style="overflow-x:hidden;overflow-y:auto;" id="rightMenu">
      <?php
							$js_function_name = "setting_account_detail";    // FUNCTION NAME 
              $js_function_name1 = "setting_show_file";
              $img_size="style='border:none;width:15px;height:14px;'";		
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
															label:"<table border=0>"+
																		"<tr>"+
																		  "<td valign=\'top\'><div style=\'height:2px\'></div>"+
																				"<a href=javascript:'.$js_function_name.'(\'src/php/setting_update_account_prev.php\',\'first_stage\'); class=\'menuitem\'>&nbsp;"+
																					"<img src=\'images/help/book.jpg\' \''.$img_size.'\'>"+
																				"</a>"+
																			"</td>"+
																			"<td valign=\'top\'><div style=\'height:2px\'></div>"+
																				"<a href=javascript:'.$js_function_name.'(\'src/php/setting_update_account_prev.php\',\'first_stage\'); class=\'menuitem\'>&nbsp;"+
																					"How To Manage Menu"+
																				"</a>"+
																			"</td>"+
																		 "</tr>"+
																	"</table>", editable:false, children:
														[
														  {type:"Text",
															label:"<table border=0>"+
																		"<tr>"+
																		  "<td valign=\'top\'><div style=\'height:2px\'></div>"+
																				"<a href=javascript:'.$js_function_name.'(\'src/php/setting_update_account_prev.php\',\'first_stage\'); class=\'menuitem\'>&nbsp;"+
																					"<img src=\'images/help/book.jpg\' \''.$img_size.'\'>"+
																				"</a>"+
																			"</td>"+
																			"<td valign=\'top\'><div style=\'height:2px\'></div>"+
																				"<a href=javascript:'.$js_function_name.'(\'src/php/setting_update_account_prev.php\',\'first_stage\'); class=\'menuitem\'>&nbsp;"+
																					"Manage Group"+
																				"</a>"+
																			"</td>"+
																		 "</tr>"+
																	"</table>", editable:false, children:
														[
														]},
														]},'; 								
																											
													echo']);
													tree.render();
													tree.subscribe(\'dblClickEvent\',tree.onEventEditNode);
												};
												YAHOO.util.Event.onDOMReady(treeInit);
											})();
									</script>			
					</div>';
    ?>
</td>
</tr>
	

				
