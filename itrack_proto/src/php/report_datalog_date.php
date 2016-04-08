<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');

  echo'
		<table align="center">
			<tr>
				<td>
				<fieldset style="width:400px;height:230px;">
					<legend>
						<font color="DarkBrown" size="1">
							Specify Date
						</font>
					</legend>
			<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0" width="100%">
				<TR>
					<TD>
						<table border=0 width = 100% cellspacing=2 cellpadding=0>
							<tr>
								<td height=10 class="text" align="center"><strong>Vehicle 
								Data Log Between Dates</strong><br><br>Select DeviceName</td>
							</tr>
						</table>
						
					<form method="post">	
						<table border=0 width = 90% cellpadding=3 align="center">
							<tr>
							<td valign="top" width=25% align="center">
							<div style="border-right:1px solid blue;border-top:1px solid blue;scrollbar-face-color:red;font-size:7pt;scrollbar-highlight-color:white;overflow:auto;border-left:1px solid blue;width:250px;scrollbar-shadow-color:white;scrollbar-arrow-color:sand;border-bottom:1px solid blue;font-family: verdana, tahoma, arial, sans-serif;;scrollbar-darkshadow-color:black;height:160px;">
							<table>
							<tr>
								<td class="text" align="center"><input type="checkbox" name="all_1" value="1" onClick="javascript:Alln(this.form)"></td>
								<td class="text">Select All</font></td>
							</tr>
						';

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
										<td class="text" align="center"><input type="checkbox" name="vehiclename[]" value="'.$vehiclename.'"></td>
										<td class="text">'.$vehiclename.'</td>
									</tr>
								';
							}									
							echo'									
								</table>

								</div>
							</td>							
						</tr>
						</table><br><br>
            
            <div STYLE=" height:$height+20px; overflow:auto">';
						
						//date_default_timezone_set('Asia/Calcutta');
						$StartDate=date('Y/m/d 00:00:00');
						$EndDate=date('Y/m/d H:i:s');		
							
						echo'
							<br>
							   <table width="470" align="center" border="0">
								<TR valign="top" cellspacing="0" cellpadding="0" align="left"> 
									<TD height="24" width="70">
										<font size="2">
											Date From
										</font>
									</td>
									
									<td align="center">
										<input type="text" id="date1" name="StartDate" value="'.$StartDate.'" size="16" maxlength="19">
									</td>
										
									<td>
										<a href=javascript:NewCal_SD("date1","yyyymmdd",true,24)>
											<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
										</a>
									</TD>

									<TD height="24" width="90" align="right">
										<font size="2">
											Date To
										</font>
									</td>
										
									<td align="center">
										<input type="text" id="date2" name="EndDate" value="'.$EndDate.'" size="16" maxlength="19">
									</td>
									
									<td>
										<a href=javascript:NewCal("date2","yyyymmdd",true,24)>
											<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
										</a>
									</TD>
									<!--<input type="hidden" name="date_id" value="1">-->		
								</TR>																	
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
							<td class="text" align="left"><input type="button" onclick="javascript:action_report_datalog_date" value="Enter"></td>
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
