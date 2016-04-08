<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	//include_once('manage_hierarchy_header1.php');
	$root=$_SESSION['root'];
	//$postPars = array('action_type' , 'common_id');
	$postPars = array('sector_id' , 'action_type' , 'local_account_ids', 'alert_ids', 'vehicle_id', 'duration', 'common_id');
	include_once('action_post_data.php');

	$common_id1=$_POST['common_id'];
	echo "edit##"; 
  //include_once('manage_route.php');                
  echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';
	echo"	<div style='height:10px;'></div>	
			<!--<form name='manage1' method='post'>-->
				<center>			
				  <table border=0>
				    <tr>
				      <td colspan=3 align='center'>
				       <strong>Sector Escalation Alert Mapping</strong>
				       <div style='height:10px;'></div>
               </td>
            </tr>			        
				    <tr>
				      <td valign='top'> 	
              <div style='width=300px;height:450px;overflow:auto;'> 
              <fieldset class='manage_cal_vehicle'>
					       <legend><strong>Escalation</strong></legend>		
				        <table border=0 cellspacing=0 cellpadding=0 class='module_left_menu' align='center'>";												
        					$query="SELECT * FROM escalation where escalation_id IN (SELECT escalation_id from escalation_grouping where account_id='$common_id1' AND status=1) AND status='1'";
        					//echo "query=".$query."<br>";
        					$result=mysql_query($query,$DbConnection);
        					$row_result=mysql_num_rows($result);		
        					if($row_result!=null)
        					{
        						while($row=mysql_fetch_object($result))
        						{									
        							$escalation_id=$row->escalation_id;
        							$person_name=$row->person_name;
        						  echo"<tr>
        								<td>
        									&nbsp<INPUT TYPE='checkbox' name='escalation_id[]' VALUE='$escalation_id'>
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
					       <legend><strong>Alert</strong></legend>		
				        <table border=0 cellspacing=0 cellpadding=0 class='module_left_menu' align='center'>";												
        					$query="SELECT alert_id,alert_name FROM alert where status='1'";
        					//echo "query=".$query."<br>";
        					$result=mysql_query($query,$DbConnection);
        					$row_result=mysql_num_rows($result);		
        					if($row_result!=null)
        					{
        						while($row=mysql_fetch_object($result))
        						{									
        							$alert_id=$row->alert_id;
        							$alert_name=$row->alert_name;        						  
        							
        							if($alert_name=="halt_start" || $alert_name=="halt_stop" || $alert_name=="sector_change")
        							{                                              
                        echo"<tr>
        								<td>
        									&nbsp<INPUT TYPE='checkbox' name='alert_id[]' VALUE='$alert_id'>
        									<font color='blue' size='2'>".$alert_name."&nbsp;</font>"."												
        								</td>";
                                               
                        echo
                          "<td>";
                          if($alert_name=="sector_change")
                          {
                            echo "<input type='hidden' name='duration[]' SIZE='6' MAXLENGTH='5' value='0'/>";
                          }
                          else 
                          {
                            echo "<input type='text' name='duration[]' SIZE='6' MAXLENGTH='5'/>&nbsp;(mins)";
                          }  
                        echo "</td>"; 
                        echo"</tr>";                     
                      }
                      
        						}
        					}
        					else
        					{
        						echo"<font color='blue' size='2'>NO SCALATION FOUND FOR THIS ACCOUNT</font>";
        					}
						echo"</div></td>
              </tr>
            </table>
            </fieldset>
         </td>         
         <td>
           <div style='width=300px;height:450px;overflow:auto;'>
           <fieldset class='manage_cal_vehicle'>
					       <legend><strong>Sector</strong></legend> 
           	<table border=0 cellspacing=0 cellpadding=0 class='module_left_menu'>
    					<!--<tr>
    						<td colspan='3'>
    							&nbsp;<INPUT TYPE='checkbox' name='all_vehicle' onclick='javascript:select_all_assigned_vehicle(this.form);'>
    							<font size='2'>Select All</font>"."												
    						</td>																														
    					</tr>-->";
					     get_user_sectors($common_id1,$DbConnection);
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
			    <input type="button" id="enter_button" name="enter_button" Onclick="javascript:return action_manage_sector(\'assign\')" value="Assign">
          &nbsp;<input type="reset" value="Cancel">
         </td>
         
			   <td>
			     <div style="height:10px;"></div>
			     <a href="javascript:manage_deassign_sector_escalation_prev(\'src/php/manage_sector_alert_deassignment.php\')"><strong>Existing Escalation</strong></a>
         </td>
                  
       </tr>
      <tr>
			   <td align="left" colspan="2">
			     <div style="height:10px;"></div>			      
          </td>
      </tr>      
		</table>
			<!--</form>-->';
    
    echo'<br><center><a href="javascript:manage_show_file(\'src/php/manage_route.php\');" style="text-decoration:none;"><strong><< Back</strong></a></center>';	

			function get_user_sectors($common_id1,$DbConnection)
			{	
			  $query = "SELECT sector_id,sector_name FROM sector WHERE user_account_id = '$common_id1' AND status=1";
			  //echo "<br>q=".$query;
        $result = mysql_query($query,$DbConnection);

			
				echo "<tr>";
        $i=0;
        while($row = mysql_fetch_object($result))
				{
				  $sector_id = $row->sector_id; 
				  $sector_name = $row->sector_name;
				  
          if($i>2)
          {            
            $i=0; 
            echo "</tr>";
          }
            
          echo'<td align="left">&nbsp;<INPUT TYPE="radio"  name="sector_id" VALUE="'.$sector_id.'"></td>
          <td class=\'text\'>
          <font color="darkgreen">'.$sector_name.'</font>                
          </td>';
          $i++;
         }
         echo "</tr>";			
			}

			
?>  