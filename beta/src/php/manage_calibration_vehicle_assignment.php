<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	//include_once('manage_hierarchy_header1.php');
	$root=$_SESSION['root'];
	$postPars = array('action_type' , 'common_id');
	include_once('action_post_data.php');
  $pd = new PostData();
	$common_id1=$pd->data[common_id];
	echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';
  include_once('manage_calibration.php');
	echo"	<div style='height:10px;'></div>	
			<form name='manage1' method='post'>
				<center>			
				  <table border=0>
				    <tr>
				      <td colspan=2>
				       <strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Calibration Vehicle Assignment</strong>
				       <div style='height:10px;'></div>
               </td>
            </tr>			        
				    <tr>
				      <td valign='top'> 	
              <fieldset class='manage_cal_vehicle'>
					       <legend><strong>Calibration</strong></legend>		
				        <table border=0 cellspacing=0 cellpadding=0 class='module_left_menu' align='center'>";												
        					$query="SELECT * FROM calibration where calibration_id IN (SELECT calibration_id from calibration_grouping where account_id='$common_id1' AND status=1) AND status='1'";
        					//echo "query=".$query."<br>";
        					$result=mysql_query($query,$DbConnection);
        					$row_result=mysql_num_rows($result);		
        					if($row_result!=null)
        					{
        						while($row=mysql_fetch_object($result))
        						{									
        							$calibration_id=$row->calibration_id;
        							$calibration_name=$row->calibration_name;
        						echo"<tr>
        								<td>
        									&nbsp<INPUT TYPE='radio' name='calibration_id' VALUE='$calibration_id'>
        									<font color='blue' size='2'>".$calibration_name."&nbsp;&nbsp;&nbsp;</font>"."												
        								</td>																														
        							</tr>";
        						}
        					}
        					else
        					{
        						echo"<font color='blue' size='2'>NO CALIBRATION FOUND IN THIS ACCOUNT</font>";
        					}
						echo"</td>
              </tr>
            </table>
            </fieldset>
         </td>
         <td>
           <fieldset class='manage_cal_vehicle'>
					       <legend><strong>Vehicle</strong></legend> 
           	<table border=0 cellspacing=0 cellpadding=0 class='module_left_menu'>
    					<tr>
    						<td colspan='3'>
    							&nbsp;<INPUT TYPE='checkbox' name='all_vehicle' onclick='javascript:select_all_assigned_vehicle(this.form);'>
    							<font size='2'>Select All</font>"."												
    						</td>																														
    					</tr>";
					     get_user_vehicle($root,$common_id1);
			echo'</table>	
      </fieldset>		
		    </td>
			</tr>
			<tr>
			   <td colspan="2">
			     <div style="height:10px;"></div>
			      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
			    <input type="button" id="enter_button" name="enter_button" Onclick="javascript:return action_manage_calibration(\'assign\')" value="Assign">
          &nbsp;<input type="reset" value="Cancel">
         </td>
       </tr>
      <tr>
			   <td colspan="2">
			     <div style="height:10px;"></div>			      
          </td>
      </tr>      
		</table>
			</form>';	
include_once('manage_loading_message.php');
			function common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$option_name)
			{	
				//$td_cnt++;
				global $td_cnt;
				if($td_cnt==1)
				{
					echo'<tr>';
				}
				
				//date_default_timezone_set('Asia/Calcutta');
				$current_date = date('Y-m-d');

				$xml_file = "../../../xml_vts/xml_data/".$current_date."/".$vehicle_imei.".xml";
				//echo "xml_file=".$xml_file."<br>";
			
				if(file_exists($xml_file))
				{
				echo'<td align="left">&nbsp;<INPUT TYPE="checkbox"  name="vehicle_id[]" VALUE="'.$vehicle_id.'"></td>
					   <td class=\'text\'>
					     <font color="darkgreen">'.$vehicle_name.'</font>                
					   </td>';
				}
				else
				{
					echo'<td align="left">&nbsp;<INPUT TYPE="checkbox"  name="vehicle_id[]" VALUE="'.$vehicle_id.'"></td>
  						<td class=\'text\'>
  						  <font color="grey">'.$vehicle_name.'</font>
  						</td>';
				}
				if($td_cnt==3)
				{ 
					echo'</tr>';
				}

			}

			function get_user_vehicle($AccountNode,$account_id)
			{
				global $vehicleid;
				global $vehicle_cnt;
				global $td_cnt;
				global $DbConnection;
				if($AccountNode->data->AccountID==$account_id)
				{
					$td_cnt =0;
					for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
					{			    
						$vehicle_id = $AccountNode->data->VehicleID[$j];
						$vehicle_name = $AccountNode->data->VehicleName[$j];
						$vehicle_imei = $AccountNode->data->DeviceIMEINo[$j];
						if($vehicle_id!=null)
						{
							for($i=0;$i<$vehicle_cnt;$i++)
							{
								if($vehicleid[$i]==$vehicle_id)
								{
									break;
								}
							}			
							if($i>=$vehicle_cnt)
							{
								$vehicleid[$vehicle_cnt]=$vehicle_id;
								$vehicle_cnt++;
								$td_cnt++;
								$query="SELECT vehicle_id FROM calibration_vehicle_assignment WHERE vehicle_id='$vehicle_id' AND status='1'";
								//echo "query=".$query;
								$result=mysql_query($query,$DbConnection);
								$num_rows=mysql_num_rows($result);
								if($num_rows==0)
								{							
									common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$AccountNode->data->AccountGroupName);
								}
								if($td_cnt==3)
								{
									$td_cnt=0;
								}
							}
						}
					}
				}
				$ChildCount=$AccountNode->ChildCnt;
				for($i=0;$i<$ChildCount;$i++)
				{ 
					get_user_vehicle($AccountNode->child[$i],$account_id);
				}
			}

			
?>  
