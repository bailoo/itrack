<?php 
  include_once('Hierarchy.php');   
  include_once('util_session_variable.php');    
  include_once('util_php_mysql_connectivity.php');
	$DEBUG;
	$root=$_SESSION['root'];   
	$edit_account_id = $_POST['edit'];    
	$edit_account_id=explode(",",$edit_account_id);
		
	$user_type_id_local=getUserTypeIdAccountFeature($edit_account_id[0],$DbConnection);  
	$size_local_utid=explode(",",$user_type_id_local);
  	  
	  $row = getNameDistanceVarAccountDetail($edit_account_id[0],$DbConnection);
	  $user_name = $row[0];
	  $distance_variable =$row[1];
  
	  if($DEBUG==1)
	  {
			"account_id_local=".$edit_account_id[0]."<br>user_type_id=".$user_type_id_local."<br>size of usertypeid=".$size_local_utid."<br";
	  }
	
	$field_string=getFieldStringAccountFeature($edit_account_id[0],$DbConnection);
	$len=strlen($field_string);
	$field_string=substr($field_string,0,($len-4));  ////////for detect the last four character;

  
	$feature_name= getFeatureNameFeatureGroupingArray($field_string,$DbConnection);
	


	echo"<input type='hidden' name='edit_account_id' id='edit_account_id' value='$edit_account_id[0]'>";	
	echo'<form name="manage1"><br>
			<center>
				<fieldset class="manage_fieldset">
					<legend>
						<strong>Edit Account</strong>
					</legend>
						<table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">   
							<tr> 
								<td>User Name</td> 
								<td>&nbsp;:&nbsp;</td>
								<td><input type="text" value="'.$user_name.'" name="user_name" id="user_name" class="tb1"></td> 
							</tr>
							<tr> 
								<td>Distance Variable</td> 
								<td>&nbsp;:&nbsp;</td>
								<td><input type="text" value="'.$distance_variable.'" name="distance_variable" id="distance_variable" class="tb1"></td> 
							</tr>
							<tr>
								<td> Permission </td> <td>&nbsp;:&nbsp;</td>
								<td class="text">';
								
									
									$user_permission=getPermissionAccountDetail($edit_account_id[0],$DbConnection);												
									if($user_permission==0)
									{	
										$checked_status='checked'; 
									}
									else
									{
										$checked_status=''; 
									}

									if($user_permission==1)
									{
										$checked_status1='checked';
									}
									else
									{	
										$checked_status1='';
									}
													
										echo'<input type="radio" value="0" name="perm_type" id="perm_type" '.$checked_status.'> View
										<input type="radio" value="1" name="perm_type" id="perm_type" '.$checked_status1.'> Edit
								</td>
							</tr>
						</table>';
										echo '<input type="hidden" value="same" name="company_type" id="company_type">';
					echo'<table>
					  <tr>
						<td>
							<fieldset class="manage_account_fieldset">
								<legend><strong>User Type</strong></legend>
								<table border=0>'; 
									for($i=0;$i<$size_utype_session;$i++)
									{
										$user_type_flag=0;
										for($uti=0;$uti<sizeof($size_local_utid);$uti++)
										{
										   ///echo "user_type_id_local=".$size_local_utid[$uti];
											 if($size_local_utid[$uti]==$user_type_id_session[$i])
											 {                 
												  $user_type_flag=1;
												  break;
											 }
										}
										if($user_type_flag==1)
										{
										  //echo "in if";
											  $user_checkbox_selection="checked";
										}
										else
										{
										  //echo "in else";
										   $user_checkbox_selection="";
										}
										
									  echo'<tr valign="top">
											  <td><input type="checkbox" value="'.$user_type_id_session[$i].'"  '.$user_checkbox_selection.'  name="user_type[]" id="user_type[]" onclick="javascript:check_usertypes(this.value);"/>
											   '.$user_type_name_session[$i].'</td>
										   </tr>';
									  if($user_type_flag==1)
									  {    
										  echo'<tr>
												<td>
												  <table>
													  <tr>
															<td></td>
															<td>';      
																
																$feature_id_str_db = getFeatureIDUserTypeMap($user_type_id_session[$i],$DbConnection);
																
																//echo "<br>".$feature_id_str_db;
																
																$feature_id_db = explode(",",$feature_id_str_db);    
																for($j=0;$j<sizeof($feature_id_db);$j++)
																{
																  //echo  "<br>".$size_feature_session;
																  for($k=0;$k<$size_feature_session;$k++)
																  {
																	//echo "feature_id="."feature".$i;
																	if($feature_id_db[$j]==$feature_id_session[$k])
																	{ 
																	  $feature_name_flag=0;
																	   for($fni=0;$fni<sizeof($feature_name);$fni++)
																	   {
																			//echo "fname_session=".$feature_name_session[$k]." ,fname==".$feature_name[$fni]."<br>";
																			
																			if($feature_name_session[$k]==$feature_name[$fni])
																			{
																				 $feature_name_flag=1;
																				 break;
																			}                               
																	   }
																	   if($feature_name_flag==1)
																	   {
																		  $feature_checkbox_selection="checked";
																	   }
																	   else
																	   {
																		  $feature_checkbox_selection="";
																	   }                            
																	  echo'<td><input type="checkbox" '.$feature_checkbox_selection.' value="'.$feature_id_session[$k].'" name="feature'.$i.'[]" id="feature'.$i.'[]" onclick="javascript:check_features(this,this.value);"/></td>';                      
																	  echo '<td>'.$feature_name_session[$k].'<input type="hidden" value="'.$feature_name_session[$k].'" id="post_feature'.$i.'[]"/></td>';                            
																	//echo "<br>feature_id_session=".$feature_id_session[$k]." ,feature_name_session=".$feature_name_session[$k];
																	}
																  }	   
																}           
														echo'
														</tr>
													</table>            
												 </td>
											</tr>';
									  }
									  else
									  {
										echo' 
										 <tr>
											 <td>
												<table>
												  <tr>
													  <td></td>
													  <td>';      
														$feature_id_str_db = getFeatureIDUserTypeMap($user_type_id_session[$i],$DbConnection);;
														$feature_id_db = explode(",",$feature_id_str_db);    
														for($j=0;$j<sizeof($feature_id_db);$j++)
														{
														  for($k=0;$k<$size_feature_session;$k++)
														  {
															//echo "feature_id="."feature".$i;
															if($feature_id_db[$j]==$feature_id_session[$k])
															{                                                         
															  echo'<td><input type="checkbox"  value="'.$feature_id_session[$k].'" name="feature'.$i.'[]" id="feature'.$i.'[]" onclick="javascript:check_features(this,this.value);"/></td>';                      
															  echo '<td>'.$feature_name_session[$k].'<input type="hidden" value="'.$feature_name_session[$k].'" id="post_feature'.$i.'[]"/></td>';                            
															//echo "<br>feature_id_session=".$feature_id_session[$k]." ,feature_name_session=".$feature_name_session[$k];
															}
														  }	   
														}           
													echo'
													</tr>
												</table>            
											</td>
										</tr>';       
									  }

									}
							echo'</table>            
						</fieldset>
					</td>                     
				</tr>
				<tr>                    									
					<td align="center"  colspan="3">
						<div style="height:10px"></div>        
						<input type="button" value="Update" Onclick="javascript:return action_manage_account(\'edit\')" id="enter_button">&nbsp;        
						<input type="reset" value="Clear">&nbsp;										
					</td>
				</tr>
			</table>
		</fieldset>
					<a href="javascript:show_option(\'manage\',\'account\');" class="back_css">&nbsp;<b><< Back</b></a>';
					include_once('manage_loading_message.php');
			echo'</center>
			</form>';
  ?>
