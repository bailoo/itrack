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
  			<td height=10 class="report_heading" align="center">Datalog Today\'s Record</td>
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
   ';
          
	//date_default_timezone_set('Asia/Calcutta');
	$StartDate=date("Y-m-d");	  	
            
  echo'<br>               
			<fieldset style="width:50%;height:30%;">
				<legend>
					<font color="DarkBrown" size="1" >
						Specify Today\'s Time
					</font>
				</legend>
                   
         <table width="470" align="center" border="0">
					<tr valign="top" cellspacing="0" cellpadding="0" align="left"> 
						<TD width="10%">
							<font size="2" color="blue">
								Date :
							</font>
						</td>
						<TD>
							<font size="1">
							<input style="background-colour:#FEFFC4;" type="text" id="date1" name="StartDate" value="'.$StartDate.'" size="15" maxlength="19"  readonly="true">
							</font>
						</td>
					</tr>
					</table>';	            										
		   ?>
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
							
					<?php
						echo'<table width="470" align="center" border="0">
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
					 </table>';
					 
 						echo
            '<br><table align="center"><tr>
						<td class="text"><input type="radio" name="rec" id="rec" value="10" >last 10</td>  
						<td class="text" ><input type="radio" name="rec" id="rec" value="30" checked>last 30</td>                                                
						<td class="text" >&nbsp;<input type="radio" name="rec" id="rec" value="100">last 100</td>                                              
						<td class="text" >&nbsp;<input type="radio" name="rec" id="rec" value="all">all</td>
            </tr>
            </table></center>';   
           echo '</fieldset></center>    
  					 
						<br>
						<br>
						<table border=0 align="center">						
						<tr>
							<input type="hidden" id="account_id_local1" value="'.$account_id_local1.'">
              <td class="text" align="left"><input type="button" value="Submit" onclick="javascript:return action_report_datalog(thisform,\'today\');"></td>
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
