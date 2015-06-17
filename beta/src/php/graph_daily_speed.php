<?php
	echo"reportPrevPage##";
	include_once("graph_hierarchy_header.php");
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
			<td height=10 class="text" align="center"><strong>Daily Speed Graph</strong></td>
		</tr>
	</table>
  		
    <br>
		<fieldset class="report_fieldset">
		<legend>Select display Option</legend>   
    <table border=0 width = 100% cellpadding="3" cellspacing="2">
			<tr>							
				<td class="text" align="center">
					<font color="red">
						In vehicle name combo box
					</font>
					<font color="blue">
						&#42 
					</font>									
					<font color="red">
						indicates today\'s running vehicle name
					</font>
				</td>
			</tr>
		</table>
		
		<br>
		<form method="post" name="thisform">
';


echo'		<table border=0 width = 87% cellpadding="3" cellspacing="2">
			<tr>
				<td width=22%></td>
				<td class="text" width="17%">
					Vehicle Name
				</td>
				<td>';							
					
          echo '<select name="vehicle_id_local" id="vehicle_id_local">
                  <option value="select">Select</option>';          
					$function_string($account_id_local1,$options_value1);
          //include('module_report_show_vehicle_combo.php');
				  echo '</select>';
				  
				echo'</td>
							</tr>
						</table>
					</table> </fieldset> ';
    include('graph_start_end_date.php');					
					echo'
          <br>
						<table border=0 width = 100% cellpadding="3" cellspacing="2">							
							<tr>
								<td width=25%></td>
								<td width=20%></td>
								<td align="left">
									<input type="button" onclick="javascript:action_graph_daily_speed(this.form);" value="Enter">&nbsp;<input type="reset" value="Clear">
								</td>
							</tr>
				  </table>
			   <div align="center" id="loading_msg" style="display:none;"><br><font color="green">loading...</font></div>	
		</form> 
    </center>';
?>									
