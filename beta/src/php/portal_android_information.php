<?php 	
	include_once('util_session_variable.php');
	
	$DEBUG = 0;    
	$process_type1 = $_POST['process_type'];
	//echo "vehicle_id=".$vehicle_id1."account_name=".$tmp;
	echo "portal_android_information##";

	
	
	echo "<form name='download_files' action='src/php/report_download_android_files.php' target='_blank' method='POST'>
		<input type='hidden' id='download_file_name' name='download_file_name'>
	<input type='hidden' id='download_process_type' name='download_process_type'>
	<table border=0 width='100%' cellpadding='0' cellpadding='0'>
		<tr>
			<td align='center'>
								<table border=1 bordercolor='#A1BBDA' class='menu' align='center' rules='all' cellpadding='2' cellspacing='2'>
									<tr bgcolor='#0E2C52'>
										<td><font color='white'><b>&nbsp;Serial</font></td>
										<td><font color='white'><b>&nbsp;File Name</font></td>
										<!--<td><font color='white'><b>&nbsp;Uploaded Date Time</font></td>-->
										<td><font color='white'><b>&nbsp;Map</font></td>
										<td><font color='white'><b>&nbsp;Download</font></td>
										<!--<td><font color='white'><b>&nbsp;Delete</font></td>-->
									</tr>";
								
									$filePath='demo_group/raw_data/*.*'; /////// file name is common for raw,filtered and compare data
									foreach(glob($filePath) as $filename)
									//foreach(glob('gps_report/'.$account_id."/download/"."*.*") as $filename)
									{
										$stat = stat($filename);										
										$date = date_create();
										date_timestamp_set($date, $stat['mtime']);	
										//echo "<br>filename=".$filename1;
										$filename1=explode("/",$filename);										
										$i++;
										if($i%2==0)
										{
								echo'<tr bgcolor="#D1E8FF" id="delete_file_tr_'.$i.'">';
										}
										else
										{
								echo'<tr bgcolor="#F9FBFD" id="delete_file_tr_'.$i.'">';
										}
										
										$download_file=$filename1[2];
									echo'<td>
											&nbsp;'.$i.'
										</td>
										<td>
											&nbsp;'.$filename1[2].'
										</td>
										<!--<td>&nbsp;
											'.date_format($date, 'Y-m-d H:i:s').'
										</td>-->
										<td>
											<input type="button" value="Show On Map" onclick="javascript:showOnMap(\''.$download_file.'\',\''.$process_type.'\');">
										
										</td>
										<td>
											<input type="button" value="Download" onclick="javascript:download_this_file(\''.$download_file.'\',\''.$process_type.'\');">
									
										</td>							
									</tr>';	
										
									}	
							echo"</table>
							</td>
							</tr>
							</table>
							
	</form>";


?>