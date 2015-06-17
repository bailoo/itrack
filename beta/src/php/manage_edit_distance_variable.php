<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 
	include_once('coreDb.php'); 	
	echo "edit_div_station2##"; 
	
	$station_type1 = $_POST['station_type'];
	$account_id_local1 = $_POST['account_id_local'];
	//echo "<input type='hidden' id='account_id_local' value=".$account_id_local1.">";
	
	//echo "account_id_local=".$account_id_local;

	echo"<br>						  
        <div style='overflow:auto;height:200px'> 
        <table border=0 cellspacing=0 cellpadding=0 class='module_left_menu' align='center'>					
					<tr valign='top'>
						<td colspan='3'>
							&nbsp;&nbsp;<INPUT TYPE='checkbox' name='all_station2' onclick='javascript:select_all_stations2(this.form);'>
							<font size='2'>Select All</font>"."												
						</td>																														
					</tr>";	          
												
					//echo "aaa=".$DbConnection."<br>";
					$data=getDetailAllStation($account_id_local1,$station_type1,$DbConnection);						
					if(sizeof($data)>0)
					{  
						$k=0;
						foreach($data as $dt)
						{
							$geo_id=$dt['geo_id'];	
                            $customer_no=$dt['customer_no'];
							if($k==0)
							{
							   echo"<tr>";
							}           

								echo"
								<td>
								&nbsp<INPUT TYPE='checkbox' name='station_id2[]' VALUE='$geo_id'>
								<font color='blue' size='2'>".$customer_no."&nbsp;&nbsp;&nbsp;</font>"."												
								</td>";				
							$k++;																																	

							if($k==8)
							{
								echo "</tr>";
								$k=0;
							}
						}
					}
					else
					{
						echo"<font color='blue' size='2'>NO STATION FOUND IN THIS ACCOUNT</font>";
					}
						echo"</td>";
					echo"</tr>";
			echo'</table>
			   </div>
				<br><br>
				
				<table border="0" cellspacing="0" cellpadding="0" class="module_left_menu" align="center">
					<tr>                          
						<td><strong>Expected Distance variable (in kms)</td><td>&nbsp;:&nbsp;</strong></td>
						<td><input type="text" name="distance_variable" id="distance_variable">
            </td>                                
					</tr>
					<tr>                          
						<td></td><td></td><td></td>                                
					</tr>            
				</table>
				<br>				
					<input type="button" id="enter_button" name="enter_button" Onclick="javascript:return action_manage_station(\'edit_dist_var\')" value="Update Station">&nbsp;<input type="reset" value="Cancel">
				<br><br><a href="javascript:show_option(\'manage\',\'station\');" class="back_css">&nbsp;<b>Back</b></a>
				</center>
    ';
  ?>	