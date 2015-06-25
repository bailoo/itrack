<?php 
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
echo "sssss";
for($k=0;$k<$size_feature_session;$k++)
{
	//$feature_id_session[$k];
	if($feature_name_session[$k] == "station")
	{
	  $flag_station = 1;		  
	  break;
	}
	  //echo "<br>feature_name=".$feature_name_session[$k];
}
?>

<div id="blackout_4"> </div>
	<div id='divpopup_4'>
	
		<table width="100%" cellspacing=0 cellpadding=0>
			 
			<tr id="sel_all_vehicle">
				<td>					
					<table align="center">
						<tr>
							<td valign="top">
								<input type='checkbox' name='all' id ='live_all_vehicle' value='1' onClick='javascript:select_all_vehicles();'>
							</td>
							<td valign="top">
								<table style='font-family: arial, sans-serif; font-size:10px;font-weight:bold' cellspacing=0 cellpadding=0>
									<tr>
										<td height="5px">
										</td>
										<td>
											Select All
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
								
			<tr valign="top">
				<td align="center">
					<div id="show_vehicle" style="height:200px;overflow:auto;">
						<br>
						<font color="#034A24">
							Vehicle filtering may take few minutes depending on number of vehicles.<br>processing please wait....
						</font>
					</div> 					
				</td>
			</tr>
			<TR>
				<TD align="center" style='font-family: arial, sans-serif; font-size:10px;font-weight:bold'><BR><BR><input type="radio" name="mode" value="map" CHECKED>Map Mode&nbsp;<input type="radio" name="mode" value="text">Text Mode</TD>
			</TR>
			<TR>
				<TD><BR></TD>
			</TR>
			<TR>			
				<TD align="center">
					<input type="button" Onclick="javascript:filter_live_vehicle(this.form,'js1')" value="Enter"> &nbsp;
					<input type="reset" value="Clear">		
				</td>
			</tr>
			<TR>
				<TD><BR></TD>
			</TR>			
		</table>



</div>
  
