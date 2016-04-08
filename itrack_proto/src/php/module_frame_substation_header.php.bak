<?php  
	include_once('util_session_variable.php');	
        include("user_type_setting.php");	
	$path = $_SERVER['SCRIPT_NAME'];	
	$url_substr = explode('/',$path);		
	$size = sizeof($url_substr);
	$interface = $url_substr[$size-1];				
	$div_height="<div style='height:2px;'></div>";		
	$set_nbsp="&nbsp;";		
	$img_size='width="15px" hight="14px"';
	$query="SELECT name from account_detail where account_id='$account_id'";	$result=mysql_query($query,$DbConnection);	$row=mysql_fetch_object($result);
	$user_name=$row->name;
	//echo"interface=".$interface;
	echo "<input type='hidden' id='vehicle_milstone'>";
	echo "<table border='0' width='100%' cellpadding='0' cellspacing='0' height='100%' class='frame_header_table'>  
			<tr>
				<td width='2%'>&nbsp;&nbsp;<img src='images/icon/welcome.png'".$img_size." style='border:none;'></td>
				<td align='left' width='17%'><font color='blue'>Welcome </font><font color='green'>&nbsp;:&nbsp;".$user_name."</font></td>";
			
       
        
       
        
        echo'<td align="right">
					<table class="frame_header_table" border="0" cellspacing=0 cellpadding=0>
						<tr>';	           
							     						

												
									echo'<td>
											<table cellspacing=0 cellpadding=0>
												<tr>
													<td height="3px"></td>
												</tr>
												<tr>
													<td>
														<a href="logout.htm" style="text-decoration:none;">
															<img src="images/icon/logout.png" '.$img_size.' style="border:none;">'.$set_nbsp.'
														</a>
													</td>
												</tr>
											</table>
										</td>
										<td '.$v_align.'>';
										echo '<a href="logout.htm" class="hs2">
													Logout
												</a>&nbsp;
										</td>
									</tr>
								</table>
							</td>
						</tr>
				</table> '; 		
	?>  				  
    
