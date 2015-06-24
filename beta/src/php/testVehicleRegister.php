<?php
	error_reporting(-1);
	ini_set('display_errors', 'On');
	$HOST = "localhost";
	$DBASE = "iespl_vts_beta";
	$USER = "root";
	$PASSWD = "mysql";

	$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("Connection to server is down. Please try after few minutes.");
	mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");
	
	$Query="Select account.user_id,account.account_id,account.status,account.group_id,account.password,account_detail.name FROM account,account_detail WHERE account.account_id=account_detail.account_id AND account.account_id='95'";
	echo "Query=".$Query."<br>";
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
					<td>
			Group Id
				</td>
					<td>
				Name
				</td>
			</tr>
			<tr>
				<td>".$Row[0]."</td>
				<td>".$Row[1]."</td>
				<td>".$Row[2]."</td>
				<td>".$Row[3]."</td>
				<td>".$Row[4]."</td>
				<td>".$Row[5]."</td>
		</table>";
		
		$Query="Select user_id,account_id,status FROM account WHERE account_id='95'";
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
			</tr>";
			while($Row=mysql_fetch_object($Result))
			{
			echo"<tr>
				<td>".$Row->vehicle_id."</td>
				<td>".$Row->device_imei_no."</td>
				<td>".$Row->status."</td>
				</tr>";
			}
			echo "</table>";
		
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
		
		
			
	$Query="Select account_id,vehicle_id,status FROM vehicle_grouping WHERE vehicle_id='1038'";
	//echo "Query=".$Query."<br>";
	$Result1=mysql_query($Query,$DbConnection);
	//$Row=mysql_fetch_row($Result);
	
	
	echo "<table>
			<tr>
				<td>
					account_id
				</td>
				<td>
					vehicle_id
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
	
	$Query="Insert INTO vehicle_grouping (account_id,vehicle_id,status,create_id,create_date) VALUE(1084,1038,1,1,'2015-06-2015 18:38:45')";
	$Result=mysql_query($Query,$DbConnection);
	echo "result=".$Result."<br>";
	/*$Query="update vehicle_assignment set status=0 WHERE device_imei_no='358631040862203' AND status=1";
	$Result=mysql_query($Query,$DbConnection);
	echo "result=".$Result."<br>";*/
?>