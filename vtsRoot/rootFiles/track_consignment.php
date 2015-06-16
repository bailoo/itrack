<html>
	<title>Track Consignment</title>
	<head>
	</head>
	<body>

<?php
		echo '<form name="track_consignment" method="POST" action="src/php/last_consignment_location.php" target="_blank">
					<table width=100% border="0">
						<tr>
							<td height="90px">						
							</td>
						</tr>
						<tr>
							<td align="center">
								<table>
								<tr>
								<td>
								<fieldset>
									<legend><b>Track Consignment</b></legend>
										<table border="0" class="manage_interface" align="center" cellspacing=5 cellpadding=5>
											<tr>
												<td valign="top">
													Enter Docket Numeber
												</td>
												<td>:</td>
												<td colspan=2>
													<input type="text" name="docket_no">						
												</td>
											</tr>
										</table>			
								<center>
									<input type="submit" Onclick="javascript:action_manage_consignment_info(\'add\',this.form)" value="Track Consignment" id="enter_button">
								</center>						
								</fieldset>
								</td>
								</tr>
								</table>
							</td>
						</tr>
					</table>
				</form>';
?>
</body>
</html>