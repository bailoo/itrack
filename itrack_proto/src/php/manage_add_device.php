<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  $DEBUG=0;  
  echo "add##";  
echo'<table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">        
		<tr>
			<td>Device IMEI No</td>
			<td>&nbsp;:&nbsp;</td>
			<td>
				<input type="text" name="imei_no" id="imei_no" onkeyup="manage_availability(this.value, \'device\')" onmouseup="manage_availability(this.value, \'device\')" onchange="manage_availability(this.value, \'device\')">
			</td>
		</tr> 
		<tr>
			<td>Manufacturing Date</td>
			<td>&nbsp;:&nbsp;</td>
			<td>
				<input type="text" name="manufacturing_date" id="manufacturing_date" size="17" maxlength="19" readonly="true" Onclick="javascript:NewCal(\'manufacturing_date\',\'yyyymmdd\',true,24)"> 
				<a href=javascript:NewCal("manufacturing_date","yyyymmdd",true,24)>
				<img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
			</a>
			</td> 
		</tr>
		<tr>
			<td>Make</td>
			<td>&nbsp;:&nbsp;</td>
			<td>
			<input type="text" name="make" id="make">
			</td>
		</tr>';
		/*$query="SELECT * FROM feature_mapping WHERE feature_type='hw' AND status=1";
		//echo "query=".$query;
		$result=mysql_query($query,$DbConnection);*/
	echo'<!--<tr>
			<td colspan="4" align="center">
				<table border="0" class="manage_interface">
					<tr>
						<td><input type="checkbox" name="io_all" onclick="javascript:io_all_check(this.form);">Select All</td>
					</tr>';
					
					while($row=mysql_fetch_object($result))
					{
						$feature_id=$row->feature_id;
						$feature_name=$row->feature_name;
						echo'<tr>
								<td><input type="checkbox" name="io_name[]" value="'.$feature_id.'"/>'.$feature_name.'</td>
							</tr>'; 							
					}		
		echo'
				</table>
			</td>			
		</tr>-->
		
		<tr>                    									
			<td align="center" colspan="3">
				<input type="button" id="enter_button" Onclick="javascript:return action_manage_device(manage1,\'add\')" value="Enter">
				&nbsp;<input type="reset" value="Clear">
			</td>
		</tr>
	</table>';
	include_once('manage_loading_message.php');
  
  ?>
