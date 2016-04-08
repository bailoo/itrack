<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');

  echo'
			<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0" width="100%">
				<TR>
					<TD>';									

							//date_default_timezone_set("Asia/Calcutta");
							$StartDate=date("Y/m/d 00:00:00");
							$EndDate=date("Y/m/d H:i:s");
						
						echo'
						<br><br><br>
							<form method="post" action="searchbyvehicleaction.php">
							<table align="center">
								<tr>
									<td>
										<fieldset style="width:auto;">
											<legend>
												<font color="DarkBrown" size="1">
													Search Record By Vehicle Name
												</font>
											</legend>
							<center>
							<div class="ajax-div">
								<div class="input-div">
									Enter Vehicle Name :
										<input type="text" onKeyUp="getScriptPage(\'box\',\'text_content\')" id="text_content" size="20" name="veh_name">
								</div>
								<div id="box"></div>
							
							</div>
							</center>																							
						
						<table width="410" align="center" border="0">						
							<tr>
								<td>
									<table border="0" cellpadding="3" cellspacing="3">
										<tr>					
											<td align="right" class="text" width="4%" valign="top" cellpadding="2" cellspacing="2">
												<input type="radio" name="rec" id="rec" value="30" checked>
											</td>
											
											<td align="left" class="text" valign="top" width="20%"cellpadding="2" cellspacing="2">
												last 30 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											</td>                                                   
											
											<td align="right" class="text" width="4%" valign="top" cellpadding="2" cellspacing="2">
												<input type="radio" name="rec" id="rec" value="100">
											</td>
											
											<td align="left" class="text" valign="top" width="15%" cellpadding="2" cellspacing="2">
												last 100 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											</td>                                                 
											
											<td align="right" class="text" width="4%" valign="top" cellpadding="2" cellspacing="2">
												<input type="radio" name="rec" id="rec" value="all">
											</td>
											
											<td align="left" class="text" valign="top" width="10%" cellpadding="2" cellspacing="2">
												all &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											</td>
										</tr>
									</table>
								</td>

								<td class="text" align="left" valign="top">
										&nbsp;&nbsp;<input type="button" onclick="javascript:action_report_datalog_search(this.form);" value="Enter">
								</td>								
					</tr>
				</table>
					</fieldset>
							</td>
						</tr>
					</table>
						  </form>					
					 </TD>
				 </TR>
			</TABLE>  	';
?>		