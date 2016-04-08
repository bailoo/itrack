<?php 
  include_once('Hierarchy.php');
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  include_once('user_type_setting.php');
  $root=$_SESSION['root'];
  echo "add##";
  echo'<br>
      <input type="hidden" id="common_id">
      <input type="hidden" id="action_name" value="device">
      <table border=0 width="100%">
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
								 echo  '
							</strong>
						</legend>
							<div style="height:180px;overflow:auto">
									<table border="0" class="manage_interface" align="center">
										<tr>';
											$i=0;
											$data=getDeviceImeiNoAr($account_id,$DbConnection);
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
													echo'<td>
														<input type="radio" name="manage_id" value='.$device_imei_no.' onclick="javascript:manage_show_account(this.value,\'src/php/manage_show_account.php\')">
														&nbsp;'.$device_imei_no.'
													</td>';
												
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
		<div align="center" id="portal_vehicle_information" style="display:none;"></div>';
		include_once('manage_loading_message.php');
?>
  