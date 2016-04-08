<?php 
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include_once('user_type_setting.php');
	$root=$_SESSION['root'];	
	echo "edit##";
		
	$account_id_local=$_POST['common_id'];
	
	$d_s=0;
	
echo' <br>
		<input type="hidden" id="common_id">
		<input type="hidden" id="action_name" value="device">
		<table width="100%">
			<tr>
				<td>
					<table align="center" width="52%">
				<tr>
					<td>
					<fieldset class=\'assignment_manage_fieldset\'>
						<legend>
							<strong>';
              if($report_type=="Person")
              {
                echo"Mobile IMEI No";
              }
              else
              {
                echo"Device IMEI No"; 
              }
              echo'</strong>
						</legend>
					<div style="height:310px;overflow:auto;">
						<table border="0" class="manage_interface">
							<tr>';
								$i=0;
								$data=getDetailAllDevice($account_id_local,$DbConnection);
								foreach($data as $dt)
								{
									$device_imei_no=$dt['device_imei_no'];						
									if($DEBUG==1)
									{
										echo "device_imei_no=".$device_imei_no."<br>"."vehicle_name=".$device_imei_no;
									}
									if($i==4)
									{
									echo'</tr><tr>';
									$i=0;
									}
									echo'<td>';
									if($report_type=="Person")
									{
										echo'<input type="radio" name="device_imei_no" value='.$device_imei_no.'>
										&nbsp;'.$device_imei_no.'';                            
									}
									else
									{              
									echo'<input type="radio" name="device_imei_no" value='.$device_imei_no.' onclick="javascript:manage_edit_prev_interface(\'src/php/manage_show_ios.php\',this.value)">
										&nbsp;'.$device_imei_no.'';
									}
									echo'</td>';
									$i++;								
								}
						echo'</tr>
						</table>
					</div>
					</fieldset>
					</td>
					</tr>
					</table>
				</td>
			</tr>
		</table>
		<div align="center" id="portal_vehicle_information" style="display:none;"></div>
		<br>
		<center><input type="button" id="enter_button" Onclick="javascript:return action_manage_device(manage1,\'edit\')" value="Enter"></center>';
		include_once('manage_loading_message.php');
?>
  