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
	$local_account_ids=explode(",",$common_id1);
	$account_size=sizeof($local_account_ids);
	$selected_account_id=$local_account_ids[$account_size-1];
 /*$flag_visit_track = 0;
  for($k=0;$k<$size_feature_session;$k++)
	{
		if($feature_name_session[$k] == "visit_track")
		{
			$flag_visit_track = 1;
		}
		//if($user_id=="demo")
    //echo "<br>feature=".$feature_name_session[$k];
	}	 */
  echo'<input type="hidden" id="selected_account_id" value='.$selected_account_id.'>';	
	echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';
  //include_once('manage_scalation.php');
	echo"	<div style='height:10px;'></div>	
			<form name='manage1' method='post'>
				<center>			
				  <table border=0>
				    <tr>
				      <td colspan=3 align='center'>
				       <strong>Escalation Alert Assignment for School</strong>
				       <div style='height:10px;'></div>
               </td>
            </tr>			        
				    <tr>
				      <td valign='top'> 	
              <div style='width=300px;height:450px;overflow:auto;'>
              <fieldset class='manage_cal_vehicle'>
					       <legend><strong>Escalation</strong></legend>		
				        <table border=0 cellspacing=0 cellpadding=0 class='module_left_menu' align='center'>";												
        					$query="SELECT * FROM escalation_school where escalation_school_id IN (SELECT escalation_school_id from escalation_school_grouping where account_id='$common_id1' AND status=1) AND status='1'";
        					//echo "query=".$query."<br>";
        					$result=mysql_query($query,$DbConnection);
        					$row_result=mysql_num_rows($result);		
        					if($row_result!=null)
        					{
        						while($row=mysql_fetch_object($result))
        						{									
        							$escalation_id=$row->escalation_school_id;
        							$person_name=$row->person_name;
        						  echo"<tr>
        								<td>
        									&nbsp<INPUT TYPE='radio' name='escalation_id' VALUE='$escalation_id'>
        									<font color='blue' size='2'>".$person_name."&nbsp;&nbsp;&nbsp;</font>"."												
        								</td>																														
        							</tr>";
        						}
        					}
        					else
        					{
        						echo"<font color='blue' size='2'>NO SCALATION FOUND</font>";
        					}
						echo"</div></td>
              </tr>
            </table>
            </fieldset>
         </td>
				 <td valign='top'> 	
              <div style='width=300px;height:450px;overflow:auto;'>
              <fieldset class='manage_cal_alert'>
					       <legend><strong>Alert</strong></legend>";		
			
                echo"<table border=0 cellspacing=0 cellpadding=0 class='module_left_menu' align='center'>";												
        													
          				$query="SELECT alert_school_id,alert_school_name FROM alert_school where status='1'";
        					//echo "query=".$query."<br>";
        					$result=mysql_query($query,$DbConnection);
        					$row_result=mysql_num_rows($result);		
        					if($row_result!=null)
        					{
        						$i=0;
                    while($row=mysql_fetch_object($result))
        						{									
        							$alert_id=$row->alert_school_id;
        							$alert_name=$row->alert_school_name;        							
        							
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
                      
                      if($alert_name!="Bus Near Stop")
        						  {
                        echo"<tr>
          								<td>
          									&nbsp<INPUT TYPE='checkbox' name='alert_id[]' VALUE='$alert_id' onclick='javascript:select_mail()'>
          									<font color='blue' size='2'>".$alert_name."&nbsp;&nbsp;&nbsp;</font>"."												
          								</td>";
          						
                         echo"
                          <td>
                            &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                          </td> 
                          
                          <td>
                            <input type='checkbox' name='mail_status[]'/> <!--&nbsp;EMAIL-->
                          </td>";
                          
                          
                         
                          echo"                          
                            <td>
                              <input type='checkbox' name='sms_status[]'/> <!--&nbsp;SMS-->
                            </td>   
                            ";
                        
                                                  
                        echo"</tr>";
                      }
                      
                       else if($alert_name=="Bus Near Stop")
          							{ 
                        echo"<tr>
          								<td>
          									&nbsp<INPUT TYPE='checkbox' name='alert_id[]' VALUE='$alert_id' onclick='javascript:select_mail()'>
          									<font color='blue' size='2'>".$alert_name."&nbsp;&nbsp;&nbsp;</font>"."												
          								</td>";                       
                         echo
                            "<td>
                            <input type='text' name='duration[]' SIZE='6' MAXLENGTH='5'/>&nbsp;(KM Range between 0.2 to 10.0 KM)
							<!--<select name='duration[]' id='duration[]'>
								<option value='select'>KM</option>
								<option value='0.5'>0.5</option> 
								<option value='1.0'>1.0</option> 
								<option value='1.5'>1.5</option> 
								<option value='2.0'>2.0</option> 
								<option value='3.0'>3.0</option> 
								<option value='4.0'>4.0</option> 
								<option value='5.0'>5.0</option> 
								<option value='6.0'>6.0</option> 
								<option value='7.0'>7.0</option> 
								<option value='8.0'>8.0</option> 
								<option value='9.0'>9.0</option> 
								<option value='10.0'>10.0</option>
							</select> KM-->
                          </td>"; 
                           echo"
                          <td>
                            &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                          </td> 
                          
                          <td>
                            <input type='checkbox' name='mail_status[]'/> <!--&nbsp;EMAIL-->
                          </td>";
                          
                          
                         
                          echo"                          
                            <td>
                              <input type='checkbox' name='sms_status[]'/> <!--&nbsp;SMS-->
                            </td>   
                            ";
                        
                                                  
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
           
            echo "</fieldset>
         </td>         
         <td>
           <div style='width=300px;height:450px;overflow:auto;'>
           <fieldset class='manage_cal_vehicle'>
					       <legend><strong>Vehicle</strong></legend> 
           	<table border=0 cellspacing=0 cellpadding=0 class='module_left_menu'>
    					<!--<tr>
    						<td colspan='3'>
    							&nbsp;<INPUT TYPE='checkbox' name='all_vehicle' onclick='javascript:select_all_assigned_vehicle(this.form);'>
    							<font size='2'>Select All</font>"."												
    						</td>																														
    					</tr>-->";
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
			    <input type="button" id="enter_button" name="enter_button" Onclick="javascript:return action_manage_escalation_school(\'assign\')" value="Assign">
          &nbsp;<input type="reset" value="Cancel">
         </td>
       </tr>
      <tr>
			   <td>
			     <div style="height:10px;"></div>			      
          </td>
      </tr>      
		</table>
			</form>';
    
    echo'<br><center><a href="javascript:manage_show_file(\'src/php/manage_escalation_school.php\');" style="text-decoration:none;"><strong><< Back</strong></a></center>';	

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
				echo'<td align="left">&nbsp;<INPUT TYPE="radio"  name="vehicle_id" VALUE="'.$vehicle_id.'"></td>
					   <td class=\'text\'>
					     <font color="darkgreen">'.$vehicle_name.'</font>                
					   </td>';
				}
				else
				{
					echo'<td align="left">&nbsp;<INPUT TYPE="radio"  name="vehicle_id" VALUE="'.$vehicle_id.'"></td>
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
								$query="SELECT vehicle_id FROM C_alert_assignment_school WHERE vehicle_id='$vehicle_id' AND status='1'";
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
