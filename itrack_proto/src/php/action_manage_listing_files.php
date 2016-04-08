<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
?>
<html>
	<head>
		<title>Listing Files</title>
		<script type="text/javascript" src="../js/tree_view/yahoo-dom-event.js"></script>
		<script type="text/javascript" src="../js/tree_view/treeview-min.js"></script>
		<link rel="stylesheet" type="text/css" href="../css/tree_view/fonts-min.css" />
		<link rel="stylesheet" type="text/css" href="../css/tree_view/treeview.css" />
		<link rel="stylesheet" type="text/css" href="../css/menu.css" />
	</head>
<body class="yui-skin-sam">
<?php
	  $dir_tmp="download_data_files";
	  echo "acc=".$account_id11."<br>";
	  $account_id11=$_POST['listing_account_id'];
		echo '<table>
					<tr>
						<td>
							<table>
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
																[';									
																	 
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
																											"'.$dir_tmp.'"+ 																	
																										"</td>"+
																									"</tr>"+
																								"</table>"+
																							"</td>"+
																						"</tr>"+
																					"</table>",editable:false, children:
																				[';
																					if($handle = opendir($dir_tmp."/".$account_id)) 
																					{
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
																														"'.$account_id.'"+ 																	
																													"</td>"+
																												"</tr>"+
																											"</table>"+
																										"</td>"+
																									"</tr>"+
																								"</table>",editable:false, children:
																							[';
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
																																		"<a href=javascript:'.$js_function_name.'(\'src/php/help_input_selection.php\',\'Input%20Selection\'); class=\'menuitem\'>&nbsp;"+
																																			"'.$file.'"+
																																		"</a>"+
																																	"</td>"+
																																"</tr>"+
																															"</table>"+
																														"</td>"+
																													"</tr>"+
																												"</table>",editable:false, children:																	
																										[';
																										//echo $file."<br>";				
																										foreach(glob($dir_tmp.'/1/'.$file."/"."*.*") as $filename)
																										{
																											$filename1=explode("/",$filename);
																									echo'{type:"Text",
																										label:"<table border=0 class=\'mystyle\'>"+
																													"<tr>"+ 																				
																														"<td valign=\'top\'>"+
																															"<table border=0 class=\'mystyle\'>"+																					
																																"<tr>"+
																																	"<td>"+ 
																																		"<a href=\'#\' >&nbsp;"+
																																			"'.$filename1[3].'"+
																																		"</a>"+
																																	"</td>"+
																																"</tr>"+
																															"</table>"+
																														"</td>"+
																													"</tr>"+
																												"</table>",editable:false, children:																	
																												[]},';
																											//echo "&nbsp;&nbsp;.".$filename."<br>";
																										}	
																										echo']},';
																									}
																								}
																					}
																				echo']},';
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
								</tr>
							</td>
						</tr>
					</table>';
				closedir($handle);
																	

	?>
</body>

</html>