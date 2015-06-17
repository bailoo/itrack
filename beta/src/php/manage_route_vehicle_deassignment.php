<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	//include_once('manage_hierarchy_header1.php');
	$root=$_SESSION['root'];
	$common_id1=$_POST['common_id'];
	//echo "common_id=".$common_id1;	
	$query_assigned = "SELECT vehicle_name,route_name FROM route_assignment2 WHERE user_account_id='$common_id1' AND status=1";
	$result_assigned = mysql_query($query_assigned,$DbConnection);
	while($row=mysql_fetch_object($result_assigned))
	{
		$vname_assigned[] = $row->vehicle_name;
		$route_assigned[] = $row->route_name;
	}
	
	echo "<center><h3>DeAssign Route</h3><br></center>";
	echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';
	echo"			
			<form name='manage1' method='post'>
				<center>					
			
				<div style='overflow:auto;height:200px;width:600px'> 
					<fieldset class='manage_fieldset'>
						<legend><strong>Please Select Route</strong></legend>
			 
						<table border=0 cellspacing=0 cellpadding=0 class='module_left_menu' align='center'>";	          
																			 
							if(sizeof($route_assigned)>0)					
							{
								$b=0;
								echo "<tr>";
								for($i=0;$i<sizeof($route_assigned);$i++)
								{									
									$val = $vname_assigned[$i].":".$route_assigned[$i];
									echo'<td align="left"><INPUT TYPE="checkbox"  name="vehicle_route[]" VALUE="'.$val.'"></td>
									<td class=\'text\'><font color=blue>'.$vname_assigned[$i].'</font><font color=green>('.$route_assigned[$i].')</font>&nbsp;';
																			
									if($b==6)
									{
										echo "</tr><tr>";
										$b=0;
									}
									$b++;
								}
								echo "</tr>";
							}
							else
							{
								echo"<font color='blue' size='2'>NO ROUTE ASSIGNED IN THIS ACCOUNT</font>";
							}
								echo"</td>";
							echo"</tr>";
					echo'</table>
					</fieldset>
			   </div>
				<br><br>
				<br><br>
					<input type="button" id="enter_button" name="enter_button" Onclick="javascript:return action_manage_route2(\'deassign\')" value="DeAssign">&nbsp;<input type="reset" value="Cancel">
				<br><a href="javascript:show_option(\'manage\',\'route\');" class="back_css">&nbsp;<b>Back</b></a>
				</center>
			</form>';	

			
			//############ FUNTION ROUTE DETAIL ####################
			function get_route_detail($account_id, $shift_time)
			{
				//echo "\nAccount_id=".$account_id." ,shift_time=".$shift_time;			
				global $route_input;
			
				//$dir = "c:\\gps_report/231/master";
				//$dir = "c:\\halt2/test_master";
				$dir = "/var/www/html/vts/beta/src/php/gps_report/".$account_id."/master";
				$dh = opendir($dir);
				while (($file = readdir($dh)) !== false) {
					//echo "<A HREF=\"$file\">$file</A><BR>\n";
					$file_tmp = explode("#",$file);
					$file_ext = explode(".",$file_tmp[2]);
					if($file_ext[0]!="")
					{			
						//echo "\nfile_ext[0]=".$file_ext[0]." ,shift_time=".$shift_time;
						if( ($file_ext[0] == "4") && ($shift_time=="ZPME") )		//###### EVENING FILE
						{
							$path = $dir."/".$file;
			
							$row = 1;
							if (($handle = fopen($path, "r")) !== FALSE)
							{
								while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
								{
									$num = count($data);
									//echo "<p> $num fields in line $row: <br /></p>\n";
									$row++;
										
									if($num<10)
									{
										continue;
									}
									if($row > 2)
									{
										//$shift_input[] = $data[4];
										$route_input[] = $data[6];
										//$vehicle_input[] = $data[7];
										//$transporter_input[] = $data[8];
										//echo "\nEV:r=".$row." ,data[4]=".$data[4]." ,data[6]=".$data[6]." ,data[7]=".$data[7]." ,data[9]=".$data[9];
									}
								}
								fclose($handle);
								//echo "\nsizeof(route_input)=".sizeof($route_input);
							}
						}
						 
						if( ($file_ext[0] == "5") && ($shift_time=="ZPMM") )			//###### MORNING FILE
						{
							$path = $dir."/".$file;
							$row = 1;
							if (($handle = fopen($path, "r")) !== FALSE) {
									
								while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
									$num = count($data);
									//echo "<p> $num fields in line $row: <br /></p>\n";
									$row++;
										
									if($num<10)
									{
										continue;
									}
									if($row > 2)
									{
										//$shift_input[] = $data[4];
										$route_input[] = $data[6];
										//$vehicle_input[] = $data[7];
										//$customer_input[] = $data[9];
										//echo "\nMOR::r=".$row." ,data[4]=".$data[4]." ,data[6]=".$data[6]." ,data[7]=".$data[7]." ,data[9]=".$data[9];
									}
								}
								fclose($handle);
							}
						}								
					}  //
				}
				closedir($dh);
			} //function closed			
			
			//############ FUNCTION ROUTE CLOSED ########################
						
?>  
