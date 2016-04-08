<?php
	include_once('Hierarchy.php');		
	include_once('util_session_variable.php');	
	include_once('util_php_mysql_connectivity.php'); 
	include_once('user_type_setting.php');
	$root=$_SESSION['root'];	$DEBUG=0;
	$postPars = array('calibration_id' , 'action_type' , 'local_account_ids' , 'calibration_name' , 'calibration_data');
	include_once('action_post_data.php');
	$pd = new PostData();
	echo'<div style="height:10px"> </div>';
	include_once('manage_calibration.php');
	$local_account_ids=explode(",",$pd->data[local_account_ids]);
	$account_size=sizeof($local_account_ids); 
	//include_once('tree_hierarchy_information.php');
	echo"<form name='manage1'>";
	echo'<div id="portal_vehicle_information">';
	include_once('manage_checkbox_account_new.php'); 
	echo "</div>";  
	echo'<div style="height:10px"> </div>
				<table border="0" class="manage_interface" align="center">
					<tr>
						<td valign="top">Callibartion Name</td>
            <td>:</td>
            <td colspan=2>';
          if($pd->data[calibration_name]!="")
          {
						  echo'<input type="text" value="'.$pd->data[calibration_name].'" id="calibration_name" onkeyup="manage_availability(this.value, \'calibration\')" onmouseup="manage_availability(this.value,\'callibration\')" onchange="manage_availability(this.value, \'calibration\')">';
					 }
					 else
					 {
					   echo'<input type="text" id="calibration_name" onkeyup="manage_availability(this.value, \'calibration\')" onmouseup="manage_availability(this.value,\'callibration\')" onchange="manage_availability(this.value, \'calibration\')">';
           }
           echo'</td>
          </tr>
					<tr>
						<td valign="top">Callibartion Data</td>
            <td valign="top">:</td>
						<td valign="top">';
            if($pd->data[calibration_data]!="")
            {
              echo'<textarea  style="width:350px;height:60px" id="calibration_data">'.$pd->data[calibration_data].'</textarea>';
            }
            else
            {
              echo'<textarea  style="width:350px;height:60px" id="calibration_data"></textarea>';
            }                               
            echo'</td>
            <td valign="top">&nbsp;<b>(Format : fuel,io;fuel,io;)</td>	
					</tr>
					<tr>
						<td colspan="4" align="center">
							<input type="button" id="enter_button" value="Save" onclick="javascript:action_manage_calibration(\'add\')"/>
							&nbsp;<input type="reset"" value="Clear" />
						</td>
					</tr>
				</table>
			</form>';
	include_once('availability_message_div.php'); 	
	include_once('manage_loading_message.php');
?>
