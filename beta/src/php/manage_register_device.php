<?php
	include_once('util_session_variable.php');
	include_once('user_type_setting.php');
	include_once('coreDb.php');
	include_once('util_php_mysql_connectivity.php');
echo "show_device##";	
	$accounts_local1=$_POST['accounts_local'];
	//echo"show_device_test##";
	//echo "acts=".$accounts_local1;
	$device_size=explode(",",$accounts_local1);
	echo"
			<div style='height:350px;overflow:auto'>
			<table border='1' class='manage_interface' align='center' rules='all' bordercolor='grey' width='100%'>
			<!--<table border='0' class='manage_interface' align='center' bordercolor='grey' width='100%'>-->";
	for($i=0;$i<sizeof($device_size);$i++)
	{
		$row=getAccountName($device_size[$i],$DbConnection);
		
		$device_row_result=getDeviceImeiNoByAccId($device_size[$i],$DbConnection);
		
		echo'
				<tr>
					<td colspan=4>&nbsp;<b> '.$row[0].'<b></td>
				</tr>';              
		if($device_row_result==0)
		{
			echo'<tr><td><font color=red>&nbsp; No device Found</font></center></td></tr>';
		}
		else
		{
			echo'<tr>';
			$cnt=0;
			foreach($device_row_result as $dAData)
			{				
				$device_imei_no=$dAData['device_imei_no'];
				if($cnt==4)
				{
					echo "<tr></tr>";
					$cnt=0;
				}
			echo'<td>
					<INPUT TYPE="radio" name="device_imei_no" value="'.$device_imei_no.'">
				
					'.$device_imei_no.'				
				</td>';
				$cnt++;
			}
				echo'</tr>';			
		}          
	}
		echo"</table>
		</div>";
		$temp_type="add_register,".$report_type;
	echo'<table align="center"><tr><td><input type="button" Onclick="javascript:action_manage_vehicle(manage1,\''.$temp_type.'\')" value="Enter"></td></tr></table>'

?>