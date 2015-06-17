<?php

include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');

$size_suid=sizeof($suid);

echo '<fieldset style="width:auto;height:480px;"><legend><font color="darkbrown" size="1">Specify Vehicle</font></legend>
			<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0" width="100%" align="center">
				<TR>
					<TD>
						<div STYLE=" height:$height+20px; overflow:auto" align="center">
						<BR>

					<center>
						<form method="POST" name="thisform">						
					
						<table border=0 width = 80% cellpadding=3 align="center">
						<tr>
							<td align="right" valign="top" width="8%"><input type="radio" name="option1" id="option1" value="1" onClick="javascript:opt1()">
							</td>
							<td width=25% class="text" valign="top"><font size="2">Select by DeviceName :</font></td>
							<td valign="top" width=25%>
							<div style="border-right:1px solid blue;border-top:1px solid blue;scrollbar-face-color:red;font-size:7pt;scrollbar-highlight-color:white;overflow:auto;border-left:1px solid blue;width:250px;scrollbar-shadow-color:white;scrollbar-arrow-color:sand;border-bottom:1px solid blue;font-family: verdana, tahoma, arial, sans-serif;;scrollbar-darkshadow-color:black;height:100px;">
							<table>
							<tr>
								<td class="text" align="center"><input type="checkbox" disabled="true" name="all_1" value="1" onClick="javascript:Alln(this.form)"></td>
								<td class="text">Select All</font></td>
							</tr>';
																	
							if($access=="1")
							{
							
								if($login=="demouser")
								{
									include("../custom_users.php");
									$Query="select DISTINCT VehicleName from vehicle where UserID='$user1' OR UserID='$user2'";
								}
								else
								{
									$Query="select DISTINCT VehicleName from vehicle where UserID='$login'";		
								}
								$Result=mysql_query($Query,$DbConnection);
							}
							else if($access=="-2")
							{	
								for($i=0;$i<$size_suid;$i++)
								{				
									if($i==0)
									$Query="select DISTINCT VehicleName,VehicleID,UserID from vehicle where UserID='$suid[$i]'";

									else
									$Query=$Query."  OR UserID='$suid[$i]'";
									//echo $Query;
								}	
								$Result=mysql_query($Query,$DbConnection);
							}										
							while($row=mysql_fetch_object($Result))
							{
								$vehiclename=$row->VehicleName;										
								$vehicleid=$row->VehicleID;
							echo'
								<tr>
									<td class="text" align="center"><input disabled="true" type="checkbox" name="vehiclename[]" value="'.$vehiclename.'"></td>
									<td class="text">'.$vehiclename.'</td>
								</tr>
					     ';							
							}									
						
            echo'									
									</table>
									</div>
									
								</td>
								<td align="right" class="text" valign="top" width="5%"><input type="radio" name="rec" value="30"></td>
								<td align="left" class="text" valign="top" width="8%"><font size="2">last 30</font></td>                                                   
								<td align="right" class="text" valign="top" width="5%">&nbsp;<input type="radio" name="rec" value="100">
								</td>
								<td align="left" class="text" valign="top" width="10%"><font size="2">last 100</font></td>                                                 
								<td align="right" class="text" valign="top" width="5%">&nbsp;<input type="radio" name="rec" value="all"></td>
								<td align="left" class="text" valign="top" width="8%"><font size="2">all</font></td>
								<td width="24%">&nbsp;</td>
						</tr>
						</table>
            
            <br><table border=0 width = 80% cellpadding=3 align="center">					
						
							<tr>
								<td align="right" valign="top" width="8%"><input type="radio" name="option1" id="option1" value="2" onClick="javascript:opt2()"></td>
								
								<td class="text" width=25% valign="top"><font size="2">Select by DeviceSerial  : </font></td>
								<td valign="top" width=25%>
								<div style="border-right:1px solid blue;border-top:1px solid blue;scrollbar-face-color:red;font-size:7pt;scrollbar-highlight-color:white;overflow:auto;border-left:1px solid blue;width:250px;scrollbar-shadow-color:white;scrollbar-arrow-color:sand;border-bottom:1px solid blue ;font-family: verdana, tahoma, arial, sans-serif;scrollbar-darkshadow-color:black;height:100px;">
								<table>
							<tr>
								<td class="text" align="center"><input type="checkbox" disabled="true" name="all_2" value="1" onClick="javascript:Alls(this.form)"></td>
								<td class="text">Select All</font></td>
							</tr>																
							';

							if($access=="1")
							{
								if($login=="demouser")
								{
									include("../custom_users.php");
									$Query="select DISTINCT VehicleSerial from vehicle where UserID='$user1' OR UserID='$user2'";
								}
								else
								{
									$Query="select DISTINCT VehicleSerial from vehicle where UserID='$login'";		
								}
								$Result=mysql_query($Query,$DbConnection);							
							}
							else if($access=="-2")
							{	
								for($i=0;$i<$size_suid;$i++)
								{				
									if($i==0)
									$Query="select DISTINCT VehicleSerial,UserID from vehicle where UserID='$suid[$i]'";

									else
									$Query=$Query."  OR UserID='$suid[$i]'";
									//echo $Query;
								}	
								$Result=mysql_query($Query,$DbConnection);
							}
							
							while($row=mysql_fetch_object($Result))
							{
								$vehicleserial=$row->VehicleSerial;
								echo'
								<tr>
									<td class="text" align="center"><input disabled="true" type="checkbox" name="vehicleserial[]" value="'.$vehicleserial.'"></td>
									<td class="text">'.$vehicleserial.'</td>								
								</tr>';					    
								}

						echo'									
									</table>
									</div>
								</td>
								<td align="right" class="text" valign="top" width="5%"><input type="radio" name="rec" value="30"></td>
								<td align="left" class="text" valign="top" width="8%"><font size="2">last 30</font></td>                                                   
								<td align="right" class="text" valign="top" width="5%">&nbsp;<input type="radio" name="rec" value="100">
								</td>
								<td align="left" class="text" valign="top" width="10%"><font size="2">last 100</font></td>                                                 
								<td align="right" class="text" valign="top" width="5%">&nbsp;<input type="radio" name="rec" value="all"></td>
								<td align="left" class="text" valign="top" width="8%"><font size="2">all</font></td>
								<td width="24%">&nbsp;</td>
						</tr>
						</table>
						
						<br>
						<table border=0 width = 80% cellpadding=3 align="center">
							<tr>
								<td align="right" valign="top" width="8%"><input type="radio" name="option1" id="option1" value="4" onClick="javascript:opt4()"></td>
								<td class="text" width=25% valign="top"><font size="2">Select by PhoneNo : </font></td>
								<td valign="top" width=25%>
								<div style="border-right:1px solid blue;border-top:1px solid blue;scrollbar-face-color:red;font-size:7pt;scrollbar-highlight-color:white;overflow:auto;border-left:1px solid blue;width:250px;scrollbar-shadow-color:white;scrollbar-arrow-color:sand;border-bottom:1px solid blue;font-family: verdana, tahoma, arial, sans-serif;scrollbar-darkshadow-color:black;height:100px;">
								<table>
							<tr>
								<td class="text" align="center"><input type="checkbox" disabled="true" name="all_3" value="1" onClick="javascript:Allp(this.form)"></td>
								<td class="text">Select All</font></td>
							</tr>
								';

							if($access=="1")
							{
								if($login=="demouser")
								{
									include("../custom_users.php");
									$Query="select DISTINCT PhoneNO from vehicle where UserID='$user1' OR UserID='$user2'";
								}
								else
								{
									$Query="select DISTINCT PhoneNO from vehicle where UserID='$login'";
								}
								$Result=mysql_query($Query,$DbConnection);
							}
							else if($access=="-2")
							{	
								for($i=0;$i<$size_suid;$i++)
								{				
									if($i==0)
									$Query="select DISTINCT PhoneNO,UserID from vehicle where UserID='$suid[$i]'";

									else
									$Query=$Query."  OR UserID='$suid[$i]'";
									//echo $Query;
								}	
								$Result=mysql_query($Query,$DbConnection);
							}
								
							while($row=mysql_fetch_object($Result))
							{
								$phone=$row->PhoneNO;
								echo'
								<tr>
									<td class="text" align="center"><input disabled="true" type="checkbox" name="phone[]" value="'.$phone.'"></td>
									<td class="text">'.$phone.'</td>
								</tr>';					
								}
						
            echo'									
									</table>
									</div>
								</td>
								<td align="right" class="text"  valign="top" width="5%"><input type="radio" name="rec" value="30"></td>
								<td align="left" class="text" valign="top" width="8%"><font size="2">last 30</font></td>                                                   
								<td align="right" class="text"  valign="top" width="5%">&nbsp;<input type="radio" name="rec" value="100">
								</td>
								<td align="left" class="text" valign="top" width="10%"><font size="2">last 100</font></td>                                                 
								<td align="right" class="text"  valign="top" width="5%">&nbsp;<input type="radio" name="rec" value="all"></td>
								<td align="left" class="text" valign="top" width="8%"><font size="2">all</font></td>
								<td width="24%">&nbsp;</td>
						</tr>
						</table>
						
						<table border=0 width = 80% cellpadding=3 align="center">
							<tr><td width="7%">&nbsp;</td>
							</tr>
							
							<tr>
							<td></td>
							<td width="21%"></td>
							  <td width="80%" align="" class="text"><input type="button" onclick="action_report_datalog_vehicle(this.form)" value="Enter"></td>
							</tr>
							</table>
						
							</center>
						</form>
					</center>
				
						</div>
					 </TD>
				 </TR>
			</TABLE>
		</td>

	</tr>
</TABLE>
</fieldset>';    
?>