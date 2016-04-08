<?php 
	include_once("report_hierarchy_header.php");
	$account_id_local1 = $_POST['account_id_local'];	
	$vehicle_display_option1 = $_POST['vehicle_display_option'];	
	$options_value1 = $_POST['options_value'];

	$account_id_local1 = $_POST['account_id_local'];	
	echo "<input type='hidden' id='selected_account_id' value=".$account_id_local1.">";
	$vehicle_display_option1 = $_POST['vehicle_display_option'];	
	echo "<input type='hidden' id='s_vehicle_display_option' value=".$vehicle_display_option1.">";
	$options_value1 = $_POST['options_value'];
	echo "<input type='hidden' id='selected_options_value' value='".$options_value1."'>";  
  
	$options_value2=explode(",",$options_value1);			
	$option_size=sizeof($options_value2);
	$option_string="";  
  
	$function_string='get_'.$vehicle_display_option1.'_vehicle';    
 
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

		<table border=0  cellspacing=0 cellpadding=0  width="100%">
			<tr>
				<td align="center">							
					<div style="overflow: auto;height: 150px; width: 650px;" align="center">
						<table border=0 cellspacing=0 cellpadding=0 align="center" width="100%">';																	
						
								  echo'<tr><td height="10px" align="center" colspan="6" class=\'text\'>&nbsp;<input type=\'checkbox\' name=\'all\' value=\'1\' onClick=\'javascript:select_all_vehicle(this.form);\'>&nbsp;&nbsp;Select All</td></tr>';                 
                 $function_string($account_id_local1,$options_value1);
														
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
		    						
		<table border=0 width = 100% cellpadding=3 align="center">						
			
			<tr>			   
				<td class="text" width=30% align="center" colspan="4">
					<input type="checkbox" name="selectall" value="1" onClick="javascript:updateFields(this.form)">Select All
				</td>
			</tr>
			<tr><td colspan="3"></td></tr>
			
			<tr>	
			  <td class="text" width=10%></td>
				<td class="text" width=30%>
					<input type="checkbox" name="voption[]" id="option1" value="1">Vehicle Name
				</td>
				<td class="text" width=30%>
					<input type="checkbox" name="voption[]" id="option2" value="2">IMEI No
				</td>
				<td class="text" width=30%>				
					<input type="checkbox" name="voption[]" id="option3" value="3">Vehicle Number
				</td>																	
			</tr>
				
			<tr>
				<td class="text" width=10%></td>
				<td class="text" width=30%>
					 <input type="checkbox" name="voption[]" id="option4" value="4">MaxSpeed
				</td>				
        <td class="text" width=30%>
					 <input type="checkbox" name="voption[]" id="option5" value="5">Vehicle Type
				</td>															
				<td class="text">
					 <input type="checkbox" name="voption[]" id="option6" value="6">Vehicle Tag
				</td>
      </tr>		
       
       <!-- <tr>	       				
				<td class="text" width=10%></td>
				<td class="text">
					 <input type="checkbox" name="voption[]" id="option7" value="7">Geofence
				</td>        
        <td class="text" colspan="3">
					 <input type="checkbox" name="voption[]" id="option8" value="8">Route
				</td>									
			</tr>-->					
            				
		</table>
	</fieldset>
		 <br>
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
  <div align="center" id="loading_msg" style="display:none;"><br><font color="green">loading...</font></div>
	</form>
  </center>
  ';	 
?>