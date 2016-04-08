<?php 
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include("user_type_setting.php");
	
	$root=$_SESSION['root'];  
	$js_function_name="manage_select_by_entity";	
	echo "assign##"; 
	
	$DEBUG=0;
	$common_id1 = $account_id;
		
	echo'<br>
		<input type="hidden" id="common_id">
		<input type="hidden" id="action_name" value="plant">
	<center>
		<table width="70%" align="center">
			<tr>
				<td>
					<fieldset class=\'assignment_manage_fieldset\'>
						';
			echo "<div style='width=400px;height:300px;overflow:auto;'>
			<fieldset class='manage_cal_vehicle'>
				<legend>
					<strong>
						Plants
					</strong>
				</legend> 
				<table border=0 cellspacing=0 cellpadding=0 class='module_left_menu'>
					<tr>
						<td colspan='3'>
							&nbsp;<INPUT TYPE='checkbox' name='all_plant' onclick='javascript:select_all_assigned_plant(this.form);'><!--select_all_assigned_vehicle-->
							<font size='2'>
								Select All
							</font>"."																				
							</td>																														
					</tr>";
					
					
					$final_plant_list=array();
					$final_plant_name_list=array();
					
					$dataCNS=getCustomerNoStationAr($account_id,$DbConnection);
					foreach($dataCNS as $dt)
					{
						$final_plant_list[]=$dt['final_plant_list'];
						$final_plant_name_list[]=$dt['final_plant_name_list'];
					}	
					
					$td_cnt=1;
					$i=0;
					foreach($final_plant_list as $fpl){
						
						if($td_cnt==1)
						{
							echo'<tr>';
						}
						echo'<td align="left"><INPUT TYPE="checkbox"  name="plant_id[]" VALUE="'.$fpl.'"></td>
						<td class=\'text\'>
						  <font color="grey">'.$final_plant_name_list[$i].'('.$fpl.')</font>
						</td>';
						if($td_cnt==4)
						{ 
							echo'</tr>';
						}
						$td_cnt++;
						$i++;
					}
				echo '</table>	
			</fieldset>		
		    </div>						
					</fieldset>
				</td>
			</tr>
		</table>';
		echo '<br><br><br>';
		$flag_substation = 0;
		$flag_raw_milk = 0;
		
		for($k=0;$k<$size_feature_session;$k++)
		{
			
			if($feature_name_session[$k] == "plant_raw_milk")
			{
				$flag_plant_raw_milk = 1;
			}	
		}		
		
		if($flag_plant_raw_milk)
		{	
			echo '<div align="center">';
			echo '<strong>Select Plant User &nbsp;:&nbsp;</strong> <select name="plant_user" id="plant_user">';
			$dataAU=getAccountIDUserID($DbConnection);
			foreach($dataAU as $dt) 
			{
				$account_id_sub = $dt['account_id_sub' ];
				$user_id_sub = $dt['user_id_sub' ];
				echo '<option value="'.$account_id_sub.'">'.$user_id_sub.'</option>';
			}
			echo '</select>';
		
			echo '<br><br><input type="button" id="enter_button" name="enter_button" Onclick="javascript:return action_manage_plant_substation(manage1,\'assign\')" value="Assign"> <!--action_manage_vehicle_substation-->'; 
			echo '</div>';
		}
		echo'<div align="center" id="portal_vehicle_information" style="display:none;"></div><br>			
	</center>';
	
	
		
	include_once('manage_loading_message.php');
?>
  