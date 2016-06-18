<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  include("user_type_setting.php");
  include("coreDb.php");
  
  echo "deassign##";  
  $DEBUG=0;   
 
  $local_account_id=$_POST['common_id'];

  $data=getVIDINVnameAr($local_account_id,$DbConnection);
  $d=0;
  foreach($data as $dt)
  {
    $vid[$d]= $dt['vid'];
    $device[$d] =$dt['device'];
    $vname[$d] = $dt['vname'];
    $d++;
  } 
      
echo'<br><center>
  	
  <fieldset class="manage_fieldset">
		<legend>
			<strong>';
		
				if($report_type=='Person')
				  echo 'Device And Person De-Registration';
				else
				  echo 'Device And Vehicle De-Registration';
				
				echo'
			<strong>
		</legend>		
			
	  <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
		  <tr>
				<td>
					<fieldset class="manage_fieldset">
						 <legend>';

						if($report_type=='Person')
						  echo 'Device Person Pair';
						else
						  echo 'Device Vehicle Pair';

						echo'</legend>		
							<table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">

								<tr valign="top">
									<td></td>
									<td>Serial</td>
									<td>&nbsp;:&nbsp;</td>
									<td>';
										for($i=0; $i<$d; $i++)
										{
										$sno=$i+1;
										echo '<strong><font color=red>'.$sno.'</font></strong>:'.$device[$i].'-'.$vname[$i].'<br>';
										}
									echo' 
									</td>
								</tr>

								<tr valign="top">
									<td></td>
									<td>Select</td>
									<td>&nbsp;:&nbsp;</td>
									<td>'; 
										if($d==0)
										{
											echo '<font color=red>No device Found</font>';
										}
										else
										{             
											echo'<select name="device" id="device" size="6" multiple="multiple">';              

											for($i=0; $i<$d; $i++)
											{
												$sno=$i+1;
												echo'<option value="'.$device[$i].'" onclick="document.getElementById(\'enter_button\').disabled=false;">'.$sno.":".$device[$i].'&nbsp; -  &nbsp;'.$vname[$i].'</option>';
												$sno++;
											}                                                            
											echo'</select>';
										}          
										echo'
									</td>
								</tr>
							</table>
					</fieldset>  
				</td>
			</tr>
			  
			<tr>                    									
				<td align="center" colspan="3"><br><input type="button"  class="btn btn-default" id="enter_button" name="enter_button" disabled="true" Onclick="javascript:return action_manage_vehicle(manage1,\'deregister\')" value="De-Register">
					&nbsp;<input type="reset"  class="btn btn-default" value="Cancel" onclick="javascript:thisform.enter_button.disabled=true;">
				</td>
			</tr>
		</table>
    
    </fieldset>  

  </center>
  <center><!--<a href="javascript:show_option(\'manage\',\'vehicle\');" class="back_css">&nbsp;<b>Back</b></a>--></center>
  ';
   	include_once('manage_loading_message.php');
?>  
