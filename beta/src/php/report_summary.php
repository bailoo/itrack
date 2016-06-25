<?php
	echo"reportPrevPage##";
	include_once("report_hierarchy_header.php");
	
	$account_id_local1 = $_POST['account_id_local'];
	$vehicle_display_option1 = $_POST['vehicle_display_option'];	
	$options_value1 = $_POST['options_value']; 

	$options_value2=explode(",",$options_value1);			
	$option_size=sizeof($options_value2);
	$option_string="";  
  
	$function_string='get_'.$vehicle_display_option1.'_vehicle';  
	
	echo'
	 <center>
		<table border=0 width = 100% cellspacing=2 cellpadding=0>
			<tr>
				<td height=10 class="report_heading" align="center">Summary Report</td>
			</tr>
		</table>
		
	<form method="post">
	 <br>
	 <fieldset class="report_fieldset">
		<legend>Select Vehicle</legend>	    		

			<br>
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
      </fieldset> <br>
     <fieldset class="report_fieldset">
  		<legend>Select display Option</legend>			      
     <br>';
     	
     	//date_default_timezone_set('Asia/Calcutta');
			$start_date=date("Y/m/d 00:00:00");	
			$end_date=date("Y/m/d H:i:s");	
			
     echo'											
				  <table width="427" align="center">
					<TR valign="top" cellspacing="0" cellpadding="0" align="left"> 
						<TD  height="24" width="80">
							<font size="2">Date From</font></td>
							<td width="140" align="center"><div align="left"><input type="text" id="date1" name="StartDate" value="'.$start_date.'" size="10" maxlength="19">
							<a href=javascript:NewCal_SD("date1","yyyymmdd",true,24)><img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a></div>
						</TD>

						<TD  height="24" width="60">
							<font size="2">Date To</font></td>
							<td width="140" align="center"><div align="left"><input type="text" id="date2" name="EndDate" value="'.$end_date.'" size="10" maxlength="19">
							<a href=javascript:NewCal("date2","yyyymmdd",true,24)><img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a></div>
						</TD>
					
					</TR>																						
					</table>											
    </fieldset>		
    
			<br>
			<table border=0 align="center">						
		  <tr>
			<td class="text" align="left"><input type="button" class="btn btn-default btn-sm" onclick="javascript:action_report_summary(this.form);" value="Enter">
      &nbsp;
				<input type="reset" class="btn btn-default btn-sm" value="Clear"></td>
		</tr>
		</table>				 
    
    <div align="center" id="loading_msg" style="display:none;"><br><font color="green">loading...</font></div>    		
		</form>
    </center>';   
  ?>
					
