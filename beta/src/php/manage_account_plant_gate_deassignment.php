<?php
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
include("user_type_setting.php");

$root=$_SESSION['root'];  
$js_function_name="manage_select_by_entity";	
echo "deassign_gate_plant##"; 

$DEBUG=0;
//$common_id1 = $account_id;	
$common_id1 = $_POST['local_account_id'];
/*
  $query="SELECT * FROM plant_user_assignment WHERE account_id='$common_id1' AND status=1";
  //echo $query;
  if($DEBUG==1){echo "query=".$query;}
  
	$result=mysql_query($query,$DbConnection);
	$numrows = mysql_num_rows($result);
	
	if($numrows > 0)
	{*/
   $numdata=getDetailAllPGAD($common_id1,$DbConnection);
   if(count($numdata)>0)
   {
	echo'<br>
		<input type="hidden" id="common_id">
		<input type="hidden" id="action_name" value="plant">
	<center>
		<table width="70%" align="center">
			<tr>
				<td>
					<fieldset class=\'assignment_manage_fieldset\'>
						<legend>';						
            	 echo'<strong>Select Plant</strong>';
						
            echo'</legend>';
			echo "<div style='width=400px;height:300px;overflow:auto;'>
			<fieldset class='manage_cal_vehicle'>
				<legend>
					<strong>
						Plant
					</strong>
				</legend> 
				<table border=0 cellspacing=0 cellpadding=0 class='module_left_menu'>
					<tr>
						<td colspan='3'>
							&nbsp;<INPUT TYPE='checkbox' name='all_plant' onclick='javascript:select_all_assigned_plant(this.form);'>
							<font size='2'>
								Select All
							</font>"."																				</td>																														
					</tr>";
					$final_plant_list=array();
					$final_plant_name_list=array();
					//while($row=mysql_fetch_object($result))
                                        foreach($numdata as $row)
					{
						//echo $row->customer_no;
						/*$final_plant_list[]=$row->plant_customer_no;
						$final_plant_sno[]=$row->sno;
						$plantcust=$row->plant_customer_no;*/
                                                $final_plant_list[]=$row['plant_customer_no'];
						$final_plant_sno[]=$row['sno'];
						$plantcust=$row['plant_customer_no'];
						/*$query_plant = "SELECT station_name FROM station WHERE type=1 AND user_account_id='$account_id' AND status=1 AND customer_no='$plantcust'";
						//echo $query_plant;
						$result_query = mysql_query($query_plant,$DbConnection);
						$row_plant=mysql_fetch_row($result_query);
						$final_plant_name_list[]=$row_plant[0];
                                                */
                                                $final_plant_name_list[]=getStationAr($account_id,$plantcust,$DbConnection);
						echo $row_plant->station_name;
					}
					$td_cnt=1;
					$i=0;
					foreach($final_plant_list as $fpl){
						
						if($td_cnt==1)
						{
							echo'<tr>';
						}
						echo'<td align="left"><INPUT TYPE="checkbox"  name="plant_id[]" VALUE="'.$final_plant_sno[$i].'"></td>
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
		
		echo '<br><br><input type="button" id="enter_button" name="enter_button" Onclick="javascript:return action_manage_plant_gatekeeper(manage1,\'deassign\')" value="DeAssign">';
		echo '</div>
		
		<div align="center" id="portal_vehicle_information" style="display:none;"></div><br>			
	</center>';
	}
	

			
	
?>	