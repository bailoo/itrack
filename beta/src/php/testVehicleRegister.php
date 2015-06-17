<?php
	error_reporting(-1);
	ini_set('display_errors', 'On');
	$HOST = "localhost";
	$DBASE = "iespl_vts_beta";
	$USER = "root";
	$PASSWD = "mysql";

	$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("Connection to server is down. Please try after few minutes.");
	mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");
	
	$Query="Select user_id,account_id,status FROM account WHERE user_id='master'";
	///echo "Query=".$Query."<br>";
	$Result=mysql_query($Query,$DbConnection);
	$Row=mysql_fetch_row($Result);
	
	
	echo "<table>
			<tr>
				<td>
					UserID
				</td>
				<td>
					Account ID
				</td>
				<td>
				Status
				</td>
			</tr>
			<tr>
				<td>".$Row[0]."</td>
				<td>".$Row[1]."</td>
				<td>".$Row[2]."</td>
		</table>";
		
		$Query="Select vehicle_id,device_imei_no,status FROM vehicle_assignment WHERE device_imei_no='358631040862203'";
	//echo "Query=".$Query."<br>";
	$Result=mysql_query($Query,$DbConnection);
	$Row=mysql_fetch_row($Result);
	
	
	echo "<table>
			<tr>
				<td>
					vehicle_id
				</td>
				<td>
					device_imei_no
				</td>
				<td>
				status
				</td>
			</tr>
			<tr>
				<td>".$Row[0]."</td>
				<td>".$Row[1]."</td>
				<td>".$Row[2]."</td>
		</table>";
		
		$Query="Select account_id,device_imei_no,status FROM device_assignment WHERE device_imei_no='358631040862203' and account_id='1084'";
	//echo "Query=".$Query."<br>";
	$Result=mysql_query($Query,$DbConnection);
	$Row=mysql_fetch_row($Result);
	
	
	echo "<table>
			<tr>
				<td>
					account_id
				</td>
				<td>
					device_imei_no
				</td>
				<td>
				status
				</td>
			</tr>
			<tr>
				<td>".$Row[0]."</td>
				<td>".$Row[1]."</td>
				<td>".$Row[2]."</td>
		</table>";
		
		
			
	$Query="Select account_id,vehicle_id,status FROM vehicle_assignment WHERE vehicle_id='1038'";
	echo "Query=".$Query."<br>";
	$Result1=mysql_query($Query,$DbConnection);
	//$Row=mysql_fetch_row($Result);
	
	
	echo "<table>
			<tr>
				<td>
					vehicle_id
				</td>
				<td>
					device_imei_no
				</td>
				<td>
				status
				</td>
			</tr>";
			while($Row1=mysql_fetch_object($Result1))
			{
			echo"<tr>
				<td>".$Row1->account_id."</td>
				<td>".$Row1->vehicle_id."</td>
				<td>".$Row1->status."</td>
			</tr>";
			}
	echo"</table>";
?>