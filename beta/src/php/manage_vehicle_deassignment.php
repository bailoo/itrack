<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include("user_type_setting.php");
	
	$local_account_id=$_POST['common_id'];
	
	$query="SELECT vehicle_id,vehicle_name from vehicle where account_id=$local_account_id AND status=1 AND vehicle_id IN(SELECT vehicle_id from vehicle_grouping where status=1)";
	//echo "query=".$query;
	$result=mysql_query($query,$DbConnection);
	$vehicle_size=0;
	while($row=mysql_fetch_object($result))
	{
		$vehicle_id[$vehicle_size]=$row->vehicle_id;
		$vehicle_name[$vehicle_size]=$row->vehicle_name;
		$vehicle_size++;		
	}
        
	echo'deassign##
			<center>
				<fieldset class="manage_fieldset">				        	
          <legend><strong>';
        
          if($report_type=='Person')
            echo'Person DeGrouping';
          else
            echo'Vehicle DeGrouping';
        
          echo'<strong></legend>				        	
          <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
						<tr>
							<td>
								<fieldset class="manage_fieldset">
									<legend>';
                  
                  if($report_type=='Person')
                    echo'Person Account Pair';
                  else
                    echo'Vehicle Account Pair';
                  
                  echo'</legend>
									<table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
										<tr valign="top">
											<td></td>
											<td>Select</td>
											<td>&nbsp;:&nbsp;</td>
											<td>'; 
											if($vehicle_size==0)
											{
												echo '<font color=red>No Grouping information Found</font>';
											}
											else
											{             
												echo'<select name="vehicle_ids" id="vehicle_ids" size="6" multiple="multiple">';		
												for($i=0; $i<$vehicle_size; $i++)
												{
													echo'<option value="'.$vehicle_id[$i].'" onclick="document.getElementById(\'enter_button\').disabled=false;">'.$vehicle_name[$i].'</option>';
												}                                                            
												echo'</select>';
											}          
										echo'</td>
										</tr>
									</table>
								</fieldset>  
							</td>
						</tr>
						<tr>                    									
							<td align="center" colspan="3"><br><input type="button" id="enter_button" name="enter_button" disabled="true" Onclick="javascript:return action_manage_vehicle(manage1,\'deassign\')" value="De Group">&nbsp;<input type="reset" value="Cancel" onclick="javascript:thisform.enter_button.disabled=true;"></td>
						</tr>
					</table>
				</fieldset>  
			</center>'; 
include_once('manage_loading_message.php');			
?>  