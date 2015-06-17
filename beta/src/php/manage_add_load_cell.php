<?php 
  include_once('Hierarchy.php');
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  $root=$_SESSION['root'];
    
  echo "add##"; 
  
  include_once('tree_hierarchy_information.php');
  include_once('manage_checkbox_account.php'); 
  	
  //date_default_timezone_set('Asia/Calcutta');
  $current_date=date("Y/m/d H:i:s");	
                          	
  echo'
	<center>  
		<table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
			<tr>
				<td>DateTime</td>
				<td>&nbsp;:&nbsp;</td>
				<td> <input type="text" id="date1" name="date1" value="'.$current_date.'" size="18" maxlength="19">      
					<a href=javascript:NewCal("date1","yyyymmdd",true,24)>
						<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
					</a>        
        </td>
			</tr>	
			<tr>
				<td>IMEI</td>
				<td>&nbsp;:&nbsp;</td>
				<td> <input type="text" name="imei" id="imei"> </td>
			</tr>
			<!--<tr>
				<td>Time</td>
				<td>&nbsp;:&nbsp;</td>
				<td> <input type="text" name="time" id="time"> </td>
			</tr>	      				
			<tr>
				<td>Date</td>
				<td>&nbsp;:&nbsp;</td>
				<td> <input type="text" name="date1" id="date1"> </td>
			</tr>-->
			<tr>
				<td>Load Status1</td>
				<td>&nbsp;:&nbsp;</td>
				<td> <input type="text" name="load_status1" id="load_status1"> </td>
			</tr>
			<tr>
				<td>Location</td>
				<td>&nbsp;:&nbsp;</td>
				<td> <input type="text" name="location" id="location"> </td>
			</tr>
			<tr>
				<td>Load</td>
				<td>&nbsp;:&nbsp;</td>
				<td> <input type="text" name="load" id="load"> </td>
			</tr>
			<tr>
				<td>Load Status2</td>
				<td>&nbsp;:&nbsp;</td>
				<td> <input type="text" name="load_status2" id="load_status2"> </td>
			</tr>      	      	       	      	
			<tr>
				<td>Remark</td>
				<td>&nbsp;:&nbsp;</td>
				<td><textarea name="remark" id="remark"></textarea></td>
			</tr>    								
			<tr>                    									
				<td align="center"  colspan="3">
				<div style="height:10px"></div>
				<input type="button" Onclick="javascript:action_manage_load_cell(\'add\')" value="Enter" id="enter_button">
				&nbsp;
				<input type="reset" value="Clear">
				</td>
			</tr>
		</table>
	</center>';
?>
	
