<?php  
	include_once("trip_hierarchy_header.php"); 
  echo'
	<style style type="text/css">
	<!--
	div.scroll {
	height: 200px;
	width: 650px;
	overflow: auto;
	border: 1px solid #666;
	padding: 8px;
	-->
	</style> ';
	

$case = "trip";
echo' 
		<center>
																				
			<form  method="post" name="thisform">
			<br>
    	<fieldset class="report_fieldset">
    		<legend>Select Vehicle</legend>	
			
			<input type="hidden" id="case" value="'.$case.'">							

			<!--<table border=1 rules=all bordercolor="#e5ecf5" cellspacing=0 cellpadding=0 align="center">		
				<tr>
					<td class="text"><b>&nbsp;Select&nbsp;Vehicle</b></td>					
				</tr>

				<tr>
					<td class="text" align="center">
						<input type="checkbox" name="all" value="1" onClick="javascript:All(this.form)">&nbsp;Select All
					</td>
				
				</tr>
			</table>-->

			<br>

			<table border=0  align="center" cellspacing=0 cellpadding=0  width="100%">
				<tr>
					<td align="center">							
						<div style="overflow: auto;height: 150px; width: 650px;" align="center">
							<table border=1 rules=rows bordercolor="lightblue" cellspacing=0 cellpadding=0 align="center" width="100%">	';						
								
 						  echo'<tr><td height="5px" align="center" colspan="6" class=\'text\'>&nbsp;<input type=\'checkbox\' name=\'all\' value=\'1\' onClick=\'javascript:select_all_vehicle(this.form);\'>&nbsp;&nbsp;Select All</td></tr>';                 
                 $category1=5;
               print_group_vehicle($root, $common_id_local, $category1);					

							echo'</table>
						</div>
					</td>
				</tr>
			</table>
			
   </fieldset>							
		<br>';	

	//date_default_timezone_set('Asia/Calcutta');
	$StartDate=date("Y/m/d 00:00:00");	
	$EndDate=date("Y/m/d H:i:s");	
		
   echo'<fieldset class="report_fieldset">
		<legend>Select display Option</legend>		
    						
			 <table width="90%" align="center" border="0" cellspacing="0" cellpadding="0">
				<TR valign="top" cellspacing="0" cellpadding="0" align="center"> 
					
					<td width="100">
						&nbsp;
					</td>
					
					<TD  height="24" width="400" class="text">
						<font size="2">
							Date From
						</font>
					</td>
						
					<td width="140" align="center">
						<input type="text" id="date1" name="StartDate" value="'.$StartDate.'" size="20" maxlength="19">
					</td>
					
					<td>
						<a href=javascript:NewCal_SD("date1","yyyymmdd",true,24)>
							<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
						</a>
					</TD>

					<td width="100"></td>
					
					<TD height="24" width="400" class="text">
						<font size="2">
							Date To
						</font>
					</td>
						
					<td width="140" align="center">
						<input type="text" id="date2" name="EndDate" value="'.$EndDate.'" size="20" maxlength="19">
					</td>
					
					<td>
						<a href=javascript:NewCal("date2","yyyymmdd",true,24)>
							<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
						</a>
					</TD>

					<input type="hidden" name="date_id" value="1">							
					<td width="250"></td>
				</TR>																				
			</table>			
		
		</fieldset>
		<br>				
		
			<table border=0 align="center">				
				<tr>
					<td class="text" align="center"><input type="button" onclick="javascript:action_report_trip_new(this.form);" value="Show Trip Report"></td>
				</tr>
			</table>			
	</form> 
  <div align="center" id="loading_msg" style="display:none;"><br><font color="green">loading...</font></div>	
	</center>';		
				
?>			
