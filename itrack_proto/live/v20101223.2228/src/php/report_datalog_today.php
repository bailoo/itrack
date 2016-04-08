<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');

  echo'
		<table align="center">
			<tr>
				<td>
				<fieldset style="width:auto;height:200px;">
					<legend>
						<font color="DarkBrown" size="1" >
							Specify Today\'s Time
						</font>
					</legend>
			<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0" width="100%">
				<TR>
					<TD>
					<div>';

							date_default_timezone_set('Asia/Calcutta');
							$StartDate=date('Y/m/d 00:00:00');
							$StartDate=explode(" ",$StartDate);
							//$EndDate=date('Y/m/d H:i:s');						
							//$today_date=date('Y/m/d H:i:s');
						echo'
								
							<form method="post" action="today_datalog_action.php">';
							echo'<input type="hidden" name="today_date" id="today_date" value="'.$today_date.'">
							 <table width="470" align="center" border="0">
								<tr valign="top" cellspacing="0" cellpadding="0" align="left"> 
									<TD width="10%">
										<font size="2" color="blue">
											Date :
										</font>
									</td>
									<TD>
										<font size="1">
										<input type="text" id="date1" name="StartDate" value="'.$StartDate[0].'" size="8" maxlength="19" style="background-colour:yellow" readonly="true">
										</font>
									</td>
								</tr>
								<input type="hidden" name="date_id" id="date_id" value="1">
								</table>
							 <br>
							 <table width="470" align="center" border="0">
								<tr valign="top" cellspacing="0" cellpadding="0" align="left"> 
									<TD  height="24" width="70">
										<font size="2" color="blue">
											Time From:
										</font>
									</td>										
								
									<TD  height="24" width="70">
										<font size="2" color="blue">
											&nbsp;&nbsp; Time to:
										</font>
									</td>										
								</tr>
							</table>
							
					<table width="470" align="center" border="0">
							<tr valign="top" cellspacing="0" cellpadding="0" align="left"> 
								<td>
									<font color="navy" size="2">
										 
											Hr
										
									</font>									
								</td>
								<td class=list> <div align="center">
									<SELECT name="hrfrom" id="hrfrom">';											
										 for($i=0;$i<=23;$i++)
											{
												if($i<10)
													$i="0".$i;
												  print("
													<option value=\"$i\">$i</option>");
											}							
								echo'</SELECT>					
								</td>						
								<td>
									<font color="navy" size="2">
										
											Mi
									
									</font>
								</td>
								<td class=list> <div align="center">
									<SELECT name="mifrom" id="mifrom">';											
										 for($i=0;$i<60;$i++)
											{
												if($i<10)
													$i="0".$i;
												  print("
													<option value=\"$i\">$i</option>");
											}									
								echo'</SELECT>				
								</td>
									
								<td>
									<font color="navy" size="2">
										
											S
										
									</font>								
								</td>
								<td class=list> <div align="center">
									<SELECT name="ssfrom" id="ssfrom">';											
										 for($i=0;$i<60;$i++)
											{
												if($i<10)
													$i="0".$i;
												  print("
													<option value=\"$i\">$i</option>");
											}
									echo'</SELECT>
								</td>				
								<td>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								</td>';
								$EndDate=date('Y/m/d  H:i:s');
								$dts=date('H:i:s');
								$currentdate=date("Y-m-d");
								list($currentyear,$currentmonth,$currentday)=split("-",$currentdate);
								
								//list($currenttime)=explode(" ",$EndDate,1);
								//echo $currenttime;
								
								list($currenthr,$currentmi,$currents)=split(":",$dts);

								echo'
								<td>
									<font color="navy" size="2">
										 
											Hr
										
									</font>									
								</td>
								<td> 
									<SELECT name="hrto" id="hrto">
										<option value='.$currenthr.'>'.$currenthr.'</option>';										
											for($i=0;$i<=23;$i++)
											{
												if($i<10)
													$i="0".$i;
												  print("
													<option value=\"$i\">$i</option>");
											}
							  echo'</SELECT>
								</td>
								<td>
									<font color="navy" size="2">
										 
											Mi
										
									</font>									
								</td>

								<td>
									<SELECT name="mito" id="mito">
										<option value='.$currentmi.'>'.$currentmi.'</option>';
											 for($i=0;$i<60;$i++)
												{
													if($i<10)
													$i="0".$i;
													  print("
														<option value=\"$i\">$i</option>");
												}
								echo'</SELECT>
								</td>
								<td>
									<font color="navy" size="2">
										 
											S
										
									</font>									
								</td>

								<td>
									<SELECT name="ssto" id="ssto">
										<option value='.$currents.'>'.$currents.'</option>';		
											 for($i=0;$i<60;$i++)
												{
													if($i<10)
													$i="0".$i;
													print("
													 <option value=\"$i\">$i</option>");
												}								
								echo'</SELECT>
						</td>		
							
					 </tr>
					 </table>					 
							<br>
							<table align="center" BORDER="0" CELLPADDING="0" CELLSPACING="0">	
									<TR>
										<td valign="top" width="36%">
											&nbsp;
										</td>
										<td valign="top" width="1%">
											<input type="radio" name="rec" id="rec" value="ist"  checked>
										</td>
										<td width="10%">
												 <font STYLE="font-size:8pt" color="#0000FF" face="Verdana">
													&nbsp;IST
												 </font>							
										</td>                                                   
										<td valign="top" width="1%">
											<input type="radio" name="rec" id="rec" value="gmt">	
										</td>
										<td>
												 <font STYLE="font-size:8pt" color="#0000FF" face="Verdana">
													&nbsp;GMT+4
												 </font>
										</td>
									</TR>
								</table>
							<br>
							<table border=0 align="center">						
							<tr>
								<td class="text" align="left"><input type="button" value="Enter" onclick="javascript:action_report_datalog_today(this.form);"></td>
							</tr>
							</table>
							</form>
						</div>
					 </TD>
				 </TR>
			</TABLE>
		</td>
	</tr>
</TABLE>
</fieldset>
</td>
</tr>
</table>';

?>
