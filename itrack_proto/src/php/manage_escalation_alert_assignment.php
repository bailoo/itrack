<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	//include_once('manage_hierarchy_header1.php');
	$root=$_SESSION['root'];
	//$postPars = array('action_type' , 'common_id');
	$postPars = array('escalation_id' , 'action_type' , 'local_account_ids', 'alert_ids', 'vehicle_id', 'duration', 'common_id');
	include_once('action_post_data.php');
  $pd = new PostData();
	$common_id1=$pd->data[common_id];
	
  $flag_visit_track = 0;
  for($k=0;$k<$size_feature_session;$k++)
	{
		if($feature_name_session[$k] == "visit_track")
		{
			$flag_visit_track = 1;
		}
		//if($user_id=="demo")
    //echo "<br>feature=".$feature_name_session[$k];
	}	
  	
	echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';
  include_once('manage_scalation.php');
	echo"	<div style='height:10px;'></div>	
			<form name='manage1' method='post'>
				<center>			
				  <table border=0>
				    <tr>
				      <td colspan=3 align='center'>
				       <strong>Alert Vehicle Assignment</strong>
				       <div style='height:10px;'></div>
               </td>
            </tr>			        
				    <tr>
				      
				 <td valign='top'> 	
              <div style='width=300px;height:450px;overflow:auto;'>
              <fieldset class='manage_cal_alert'>
					       <legend><strong>Alert</strong></legend>";		
				                        
               if($flag_visit_track)
               {
                    echo"<table border=0 cellspacing=0 cellpadding=0 class='module_left_menu' align='center'>";												
          					$query="SELECT alert_id,alert_name FROM alert where status='1'";
          					//echo "query=".$query."<br>";
          					$result=mysql_query($query,$DbConnection);
          					$row_result=mysql_num_rows($result);		
          					if($row_result!=null)
          					{
          						$i=0;
                      while($row=mysql_fetch_object($result))
          						{									
          							$alert_id=$row->alert_id;
          							$alert_name=$row->alert_name;        							
          							
          						  $duration_val = 'duration'.$alert_id;
          						  
                        if($alert_name=="visit_violation" || $alert_name=="visit_report")
          						  {
                          echo"<tr>
            								<td>
            									&nbsp<INPUT TYPE='radio' name='alert_id' VALUE='$alert_id' onclick='javascript:select_mail()'>
            									<font color='blue' size='2'>".$alert_name."&nbsp;&nbsp;&nbsp;</font>"."												
            								</td>";
            							
            							if($alert_name=="landmark")
            							{                        
                           echo
                              "<td>
                              <input type='hidden' id='".$duration_val."' SIZE='6' MAXLENGTH='5' value='0'/>
                            </td>";
                          } 
            							else if($alert_name=="low_battery")
            							{                        
                           echo
                              "<td>
                              <input type='text' id='".$duration_val."' SIZE='6' MAXLENGTH='5'/>&nbsp;(v) 
                            </td>";                      
                          }
            							else if($alert_name=="report")
            							{                        
                           echo
                              "<td>
                              <input type='text' id='".$duration_val."' onclick='javascript:show_report_duration();' SIZE='6' MAXLENGTH='5' readonly/>&nbsp;(hrs) 
                            &nbsp;
                              <span id='select_report_duration' style='display:none;'>
                                <select id='select_duration' onchange='javascript:select_report_duration();'>
                                  <option value='select'>Select</option>
                                  <option value='6'>6 hrs</option>
                                  <option value='12'>12 hrs</option>
                                  <option value='24'>24 hrs</option>
                                  <option value='168'>1 week</option>
                                </select>
                              </span>                        
                            
                            </td>";                      
                          }                      
            			  else if(($alert_name=="visit_violation" || $alert_name=="visit_report") || ($alert_name=="electronic_report") || ($alert_name=="fuel") || ($alert_name=="sos") )
            			  {                        
                           echo
                              "<td>
                              <input type='hidden' id='".$duration_val."' SIZE='6' MAXLENGTH='5' value='0'/>
                            </td>";                      
                          }            						                                                                      
                          else
                          {
                           echo
                              "<td>
                              <input type='text' id='".$duration_val."' SIZE='6' MAXLENGTH='5'/>&nbsp;(mins)
                            </td>";                      
                          }
                          
                           echo"
                            <td>
                              &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
                            </td> 
                            
                            <td>
                              <input type='checkbox' name='mail_status[]'/>&nbsp;EMAIL
                            </td>";
                            
                            if($alert_name=="report" || $alert_name="visit_report")
                            {
                              echo"                          
                              <td>
                                <input type='hidden' name='sms_status[]'/>&nbsp;
                              </td>   
                              ";                        
                            }
                            else
                            {
                            echo"                          
                              <td>
                                <input type='checkbox' name='sms_status[]'/>&nbsp;SMS
                              </td>   
                              ";
                            }   
                                                    
                          echo"</tr>";
                        }
          						  $i++;
                      }
          					}
          					else
          					{
          						echo"<font color='blue' size='2'>NO SCALATION FOUND FOR THIS ACCOUNT</font>";
          					}
  						    echo"</div></td>
                  </tr>
                  
                  <tr>
                      <td colspan='13' align='center'>
                      
                      </td>
                  </tr>
                </table>";                
               } 
               else
               { 
                echo"<table border=0 cellspacing=0 cellpadding=0 class='module_left_menu' align='center'>";												
        					$query="SELECT alert_id,alert_name FROM alert where status='1'";
        					//echo "query=".$query."<br>";
        					$result=mysql_query($query,$DbConnection);
        					$row_result=mysql_num_rows($result);		
        					if($row_result!=null)
        					{
        						$i=0;
                    while($row=mysql_fetch_object($result))
        						{									
        							$alert_id=$row->alert_id;
        							$alert_name=$row->alert_name;        							
        							
        						  $duration_val = 'duration'.$alert_id;
        						  
                      if($i==0)
        						  {
        						      echo"<tr>
                                <td>&nbsp;<em>AlertName</em></td>
                                <td>&nbsp;<em>Parameters</em></td>
                                <td>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;</td>
                                <td><em>Mail</em></td>
                                <td>&nbsp;<em>SMS</em></td>                                
                          </tr>";
                      }
                      
                      if(($alert_name!="visit_violation") && ($alert_name!="visit_report"))
        			  {
                        echo"<tr>
							<td>
								&nbsp<INPUT TYPE='radio' name='alert_id' VALUE='$alert_id' onclick='javascript:select_mail()'>
								<font color='blue' size='2'>".$alert_name."&nbsp;&nbsp;&nbsp;</font>"."												
							</td>";
						
						if( ($alert_name=="landmark") || ($alert_name=="ignition_activated") || ($alert_name=="ignition_deactivated") || ($alert_name=="door1_open") || ($alert_name=="door1_close")|| ($alert_name=="door2_open") || ($alert_name=="door2_close") || ($alert_name=="ac_on") || ($alert_name=="ac_off") || ($alert_name=="battery_connected") || ($alert_name=="battery_disconnected") || ($alert_name=="battery_disconnected") || ($alert_name=="entered_region") || ($alert_name=="sos") || ($alert_name=="exited_region") || ($alert_name=="entered_region") || ($alert_name=="exited_region") || ($alert_name=="sector_change") || ($alert_name=="overspeed") || ($alert_name=="low_battery") || ($alert_name=="electronic_report") || ($alert_name=="fuel") || ($alert_name=="over_temperature"))
						{                        
                         echo
                            "<td>
                            <input type='hidden' id='".$duration_val."' SIZE='6' MAXLENGTH='5' value='0'/>&nbsp;
                          </td>";
                        }						
                                                                                             
          				else if($alert_name=="report")
          				{                        
                         echo
                            "<td>
                            <input type='text' id='".$duration_val."' id='report_duration' onclick='javascript:show_report_duration();' SIZE='6' MAXLENGTH='5' readonly/>&nbsp;(hrs) 
                          &nbsp;
                            <span id='select_report_duration' style='display:none;'>
                              <select id='select_duration' onchange='javascript:select_report_duration();'>
                                <option value='select'>Select</option>
                                <option value='6'>6 hrs</option>
                                <option value='12'>12 hrs</option>
                                <option value='24'>24 hrs</option>
                                <option value='168'>1 week</option>
                              </select>
                            </span>                        
                          
                          </td>";                      
                        }                      
     
                        else
                        {
                         echo
                            "<td>
                            <input type='text' id='".$duration_val."' SIZE='6' MAXLENGTH='5'/>&nbsp;(mins) 
                          </td>";                      
                        }
                        
                         echo"
                          <td>
                            &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                          </td> 
                          
                          <td>
                            <input type='checkbox' name='mail_status[]'/> <!--&nbsp;EMAIL-->
                          </td>";
                          
                          if($alert_name=="report")
                          {
                            echo"                          
                            <td>
                              <input type='hidden' name='sms_status[]'/>&nbsp;
                            </td>   
                            ";                        
                          }
                          else
                          {
                          echo"                          
                            <td>
                              <input type='checkbox' name='sms_status[]'/> <!--&nbsp;SMS-->
                            </td>   
                            ";
                          }   
                                                  
                        echo"</tr>";
                      }
        						  $i++;
                    }
        					}
        					else
        					{
        						echo"<font color='blue' size='2'>NO SCALATION FOUND FOR THIS ACCOUNT</font>";
        					}
						echo"</div></td>
              </tr>
              
              <tr>
                  <td colspan='13' align='center'>
                  
                  </td>
              </tr>
            </table>"; 
            }   //ELSE CLOSED        
            
            echo "</fieldset>
         </td>         
         <td>
			<div style='width=300px;height:450px;overflow:auto;'>
			<fieldset class='manage_cal_vehicle'>
				<legend>
					<strong>
						Vehicle
					</strong>
				</legend> 
				<table border=0 cellspacing=0 cellpadding=0 class='module_left_menu'>
					<tr>
						<td colspan='3'>
							&nbsp;<INPUT TYPE='checkbox' name='all_vehicle' onclick='javascript:select_all_assigned_vehicle(this.form);'>
							<font size='2'>
								Select All
							</font>"."												
						</td>																														
					</tr>";
					get_user_vehicle($root,$common_id1);
			echo'</table>	
			</fieldset>		
		    </div>
        </td>
			</tr>			
			</table>
			      
      <!--<fieldset style="width:100px">
        <legend><strong>Duration (mins)</strong></legend>
        <input type="text" name="duration" size="10"/> 
      </fieldset>-->
			
			<table align="center">
			<tr>
			   <td>
			     <div style="height:10px;"></div>
			      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
			    <input type="button" id="enter_button" name="enter_button" Onclick="javascript:return action_manage_escalation(\'assign_prev\')" value=">> NEXT">
         </td>
       </tr>
      <tr>
			   <td>
			     <div style="height:10px;"></div>			      
          </td>
      </tr>      
		</table>
			</form>';
   
    echo'<br><center><a href="javascript:manage_show_file(\'src/php/manage_escalation.php\');" style="text-decoration:none;"><strong><< Back</strong></a></center>';	
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
					     <font color="darkgreen">'.$vehicle_name.'('.$vehicle_id.')</font>                
					   </td>';
				}
				else
				{
					echo'<td align="left">&nbsp;<INPUT TYPE="checkbox"  name="vehicle_id[]" VALUE="'.$vehicle_id.'"></td>
  						<td class=\'text\'>
  						  <font color="grey">'.$vehicle_name.'('.$vehicle_id.')</font>
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
								$query="SELECT vehicle_id FROM escalation_alert_assignment WHERE vehicle_id='$vehicle_id' AND status=1";
								//echo "query=".$query;
								$result=mysql_query($query,$DbConnection);
								$num_rows=mysql_num_rows($result);
								//echo "numrow=".$num_rows."<br>";
								if($num_rows=="")
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
