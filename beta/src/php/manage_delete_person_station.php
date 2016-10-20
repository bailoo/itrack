<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	//include_once('manage_hierarchy_header1.php');
	$root=$_SESSION['root'];
	$common_id1=$_POST['common_id'];
	//echo "common_id=".$common_id1;
	echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';
	echo"<br>			
			<form name='manage1' method='post'>
				<center><strong> DELETE PERSON STATION </strong>
				<br>			
				<div style='overflow:auto;height:400px'> 
				<table border=0 cellspacing=0 cellpadding=0 class='module_left_menu' align='center'>					
					<tr>
						<td colspan='3'>
							&nbsp;&nbsp;<INPUT TYPE='checkbox' name='all_station' onclick='javascript:select_all_stations(this.form);'>
							<font size='2'>Select All</font>"."												
						</td>																														
					</tr>";	          
				
					$data=getPersonStationIDCustomerNoStation($common_id1,$DbConnection);					
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
								&nbsp<INPUT TYPE='checkbox' name='station_id[]' VALUE='$geo_id'>
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
				<br>
					<input type="button" id="enter_button" name="enter_button" Onclick="javascript:return action_manage_person_station(\'delete\')" value="Delete">&nbsp;<input type="reset" value="Cancel">
				<br><a href="javascript:show_option(\'manage\',\'visit\');" class="back_css">&nbsp;<b>Back</b></a>
				</center>
			</form>';	
			
?>  
