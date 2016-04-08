<?php   
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
 
  echo'  
	<center>
  <table border=0 width = 100% cellspacing=2 cellpadding=0>
		<tr>
			<td height=10 class="report_heading" align="center">Vehicle Report</td>
		</tr>
	</table>
						
	<form  method="post" name="thisform">
		
		<br>								

	<fieldset class="report_fieldset">
		<legend>Select Vehicle</legend>		
    <table border=1 rules=all bordercolor="#e5ecf5" cellspacing=0 cellpadding=0 align="center">		
			<tr>
				<td class="text"><b></b></td>					
			</tr>

			<tr>
				<td class="text" align="left">
					<input type="checkbox" name="all" value="1" onClick="javascript:SelectAll(this.form);">&nbsp;Select All				         
        </td>			
			</tr>
		</table>

		<br>

		<table border=0  cellspacing=0 cellpadding=0  width="100%">
			<tr>
				<td align="center">							
					<div style="overflow: auto;height: 150px; width: 650px;" align="center">
						<table border=1 rules=rows bordercolor="lightblue" cellspacing=0 cellpadding=0 align="center" width="100%">	';														
						
								include('module_show_vehicle_chk.php');
														
								echo'
						</table>
					</div>
				</td>
			</tr>
		</table>
	</fieldset>
	<br>	
	<fieldset class="report_fieldset">
		<legend>Select display Option</legend>		
		    						
		<table border=0 width = 100% cellpadding=3>						
			<tr>
				<td width=30%></td>	
				<td class="text" width=30%>
					<b>						
					</b>
				</td>
			</tr>
			
			<tr>
				<td width=30%></td>	
				<td class="text" width=30%>
					<input type="checkbox" name="selectall" value="1" onClick="javascript:updateFields(this.form)">Select All
				</td>
			</tr>
			
			<tr>
				<td width=30%></td>	
				<td class="text" width=30%>
					<input type="checkbox" name="voption[]" id="option1" value="1">Vehicle Name
				</td>
				<td class="text" width=30%>
					<input type="checkbox" name="voption[]" id="option2" value="2">Vehicle Number
				</td>											
			</tr>
			
			<tr>
				<td width=30%></td>	
				<td class="text" width=30%>
					 <input type="checkbox" name="voption[]" id="option3" value="3">MaxSpeed
				</td>
				<td class="text" width=30%>
					 <input type="checkbox" name="voption[]" id="option4" value="4">Vehicle Type
				</td>											
			</tr>
			
			<tr>
				<td width=30%></td>	
				<td class="text">
					 <input type="checkbox" name="voption[]" id="option5" value="5">Vehicle Tag
				</td>
				<td class="text">
					 <input type="checkbox" name="voption[]" id="option6" value="6">Geofence
				</td>										
			</tr>					
      
			<tr>
				<td width=30%></td>	
				<td class="text">
					 <input type="checkbox" name="voption[]" id="option7" value="7">Route
				</td>
				<td class="text">
					
				</td>										
			</tr>					
            				
		</table>
	</fieldset>
		
		<table align="center">
			<tr>
				<td>
					<input type="button" Onclick="javascript:action_report_vehicle(this.form)" value="Enter">
				</td>
				&nbsp;
				<td>
					<input type="reset" value="Clear">
				</td>
			</tr>
		</table>
	</form>
  </center>
  ';	   
?>