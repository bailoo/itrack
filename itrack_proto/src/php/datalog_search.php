<?php
	include("user_type_setting.php");
  //date_default_timezone_set('Asia/Calcutta');
	$StartDate=date('Y/m/d 00:00:00');
	$EndDate=date('Y/m/d H:i:s');
	$local_account_id1=$_POST['account_id_local'];
	echo'
	<input type="hidden" id="local_account_id" value='.$local_account_id1.'>
	<br><br><center>
	
    <table border=0 width = 100% cellspacing=2 cellpadding=0>
  		<tr>
  			<td height=10 class="report_heading" align="center">Datalog By Search</td>
  		</tr>
  	</table>  
    	
		<form method="post" action="vtsDataLogAction.php">
		<input type="hidden" name="id" value="4"/>
		<table align="center">
			<tr>
				<td>
					<fieldset style="width:650px;">
						<legend>
							<font color="darkbrown" size="1">
								Search Record By '.$report_type.' Name
							</font>
						</legend>
		<center>
		<div class="ajax-div">
			<div class="input-div">
				Enter '.$report_type.' Name :
					<input type="text" onKeyUp="getScriptPage(\'box\',\'text_content\')" id="text_content" size="20" name="veh_name">
					<input type="hidden" id="device_imei_no">
			</div>
			<div id="box"></div>							
		</div>
		</center>';																							
										
		//date_default_timezone_set('Asia/Calcutta');
		$StartDate=date('Y/m/d 00:00:00');
		$EndDate=date('Y/m/d H:i:s');						
	  echo'
			<br>
		 <table width="70%" align="center" border="0">
					<TR valign="top" cellspacing="0" cellpadding="0" align="left"> 
						<TD  height="24" width="200">
							<font size="2">Date From</font></td>
							<td align="center"><input type="text" id="date1" name="StartDate" value="'.$StartDate.'" size="16" maxlength="19">
							</td>
							<td><a href=javascript:NewCal_SD("date1","yyyymmdd",true,24)><img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a>
						</TD>

						<TD  height="24" width="200" align="right">
							<font size="2">Date To</font></td>
							<td align="center"><input type="text" id="date2" name="EndDate" value="'.$EndDate.'" size="16" maxlength="19">
							</td>
							<td>
							<a href=javascript:NewCal("date2","yyyymmdd",true,24)><img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a>
						</TD>
					<input type="hidden" name="date_id" value="1">		
					</TR>																	
				</table>

        <br><table align="center"><tr>
				<td class="text"><input type="radio" name="rec" id="rec" value="10" >last 10</td> 
				<td class="text"><input type="radio" name="rec" value="30">last 30</td>                                                
				<td class="text">&nbsp;&nbsp;<input type="radio" name="rec" value="100">last 100</td>                                              
				<td class="text">&nbsp;&nbsp;<input type="radio" name="rec" value="all">all</td>
        </tr>
        </table></center> 					          					            
        <br>
				<table border=0 align="center">						
				<tr>
					<input type="hidden" id="account_id_local1" value="'.$local_account_id1.'">
          <td class="text" align="left"><input type="button" value="Submit" onclick="javascript:return action_report_datalog(this.form,\'search\');"></td>
				</tr>
				</table>	              						
				</tr>
			</table>
			<div align="center" id="loading_msg" style="display:none;"><br><font color="green">loading...</font></div>
			</fieldset>
				</td>
			</tr>
		</table>
		<center><a href="javascript:show_option(\'report\',\'search_vehicle_prev\');" class="back_css">&nbsp;<b>Back</b></a></center>
</form>';					
?>
													
				
