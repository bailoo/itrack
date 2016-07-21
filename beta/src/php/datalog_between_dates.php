<?php
	echo"reportPrevPage##";
	include_once("report_hierarchy_header_datalog.php");
	include("user_type_setting.php");
	
	$account_id_local1 = $_POST['account_id_local'];
	$vehicle_display_option1 = $_POST['vehicle_display_option'];	
	$options_value1 = $_POST['options_value'];
  
	$options_value2=explode(",",$options_value1);			
	$option_size=sizeof($options_value2);
	$option_string="";  
  
	$function_string='get_'.$vehicle_display_option1.'_vehicle'; 		
		
  echo '		
  <center>
    <table border=0 width = 100% cellspacing=2 cellpadding=0>
  		<tr>
  			<td height=10 class="report_heading" align="center">Datalog Between Dates</td>
  		</tr>
  	</table>    
    
  <form  method="post" name="thisform">		
	<br>			
	<fieldset class="report_fieldset">
		<legend>Select '.$report_type.'</legend>		
		
		<table border=0  cellspacing=0 cellpadding=0  width="100%">
			<tr>
				<td align="center">							
					<div style="overflow: auto;height: 150px; width: 650px;" align="center">
						<table border=0 cellspacing=0 cellpadding=0 align="center" width="100%">';								
							
						  echo'<tr><td height="5px" align="center" colspan="6" class=\'text\'>&nbsp;<input type=\'checkbox\' name=\'all\' value=\'1\' onClick=\'javascript:select_all_vehicle(this.form);\'>&nbsp;&nbsp;Select All</td></tr>';                 
              $function_string($account_id_local1,$options_value1);

						echo'</table>
					</div>
				</td>
			</tr>
		</table>
		</fieldset>
		<br>				
    		<br>
    		<table align="center">
    			<tr>
    				<td>
    				<fieldset style="width:50%;height:150px;">
    					<legend>
    						<font color="darkbrown" size="1">
    							Specify Date
    						</font>
    					</legend>
    			<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0" width="100%">
    				<TR>
    					<TD>
    						<table border=0 width = 100% cellspacing=2 cellpadding=0>
    							<tr>
    								<td height=10 class="text" align="center"><strong>'.$report_type.'
    								Data Log Between Dates</strong></td>
    							</tr>
    						</table>

						<div STYLE=" height:400px; overflow:auto">';

							//date_default_timezone_set('Asia/Calcutta');
                                                if($dataLogImei!='') ////// this is for person data log coming from track on map and last data report
                                                {
                                                    $StartDate=$start_date_map;
                                                    $EndDate=$end_date_map;
							
                                                }
                                                else 
                                                {
                                                    $StartDate=date('Y/m/d 00:00:00');
                                                    $EndDate=date('Y/m/d H:i:s');	
                                                }
						  
              echo'
								<br>
							 <table width="470" align="center" border="0">
										<TR valign="top" cellspacing="0" cellpadding="0" align="left"> 
											<TD  height="24" width="70">
												<font size="2">Date From</font></td>
												<td align="center"><input type="text" id="date1" name="StartDate" value="'.$StartDate.'" size="16" maxlength="19">
												</td>
												<td><a href=javascript:NewCal_SD("date1","yyyymmdd",true,24)><img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a>
											</TD>

											<TD  height="24" width="90" align="right">
												<font size="2">Date To</font></td>
												<td align="center"><input type="text" id="date2" name="EndDate" value="'.$EndDate.'" size="16" maxlength="19">
												</td>
												<td>
												<a href=javascript:NewCal("date2","yyyymmdd",true,24)><img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a>
											</TD>
										<input type="hidden" name="date_id" value="1">		
										</TR>																	
									</table>

                <br><table align="center">';
              if($dataLogImei!='') ////// this is for person data log coming from track on map and last data report
              {
              echo'<tr>
							<td class="text"><input type="radio" name="rec" id="rec" value="10" >last 10</td> 
							<td class="text"><input type="radio" name="rec" id="rec" value="30">last 30</td>                                                
    						<td class="text">&nbsp;<input type="radio" name="rec" id="rec" value="100">last 100</td>                                              
    						<td class="text">&nbsp;<input type="radio" name="rec" id="rec" value="all" checked>all</td>
                </tr>';
              }
              else
              {
                  echo'<tr>
							<td class="text"><input type="radio" name="rec" id="rec" value="10" >last 10</td> 
							<td class="text"><input type="radio" name="rec" id="rec" value="30" checked>last 30</td>                                                
    						<td class="text">&nbsp;<input type="radio" name="rec" id="rec" value="100">last 100</td>                                              
    						<td class="text">&nbsp;<input type="radio" name="rec" id="rec" value="all">all</td>
                </tr>'; 
              }
              echo'  </table></center>					
					
							<br>
							<table border=0 align="center">						
							<tr>
								<input type="hidden" id="account_id_local1" value="'.$account_id_local1.'">
                <td class="text" align="left"><input type="button" value="Submit" onclick="javascript:return action_report_datalog(this.form, \'date\');"></td>
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

<div align="center" id="loading_msg" style="display:none;"><br><font color="green">loading...</font></div>';

?>
