<?php
	$HOST = "localhost";
	$DBASE = "iespl_vts_beta";
	$USER = "root";
	$PASSWD = "mysql";
	//$account_id = "231";
	//$account_id = "723";
	$account_id = "568";
	//echo "\nDBASE=".$DBASE." ,User=".$USER." ,PASS=".$PASSWD;
	$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("Connection to server is down. Please try after few minutes.");
	mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");	
	
	//$path = "/var/www/html/vts/beta/src/php/Delhi1.csv";
	//$path = "/var/www/html/vts/beta/src/php/icecream1.csv";
	$path = "/var/www/html/vts/beta/src/php/tanker1.csv";
	if(file_exists($path)) echo "\nFound"; else "\nNotFound";

	if (($handle = fopen($path, "r")) !== FALSE) {
	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			$num = count($data);
			echo "<p> $num fields in line $row: <br /></p>\n";
			//$row++;
			for ($c=0; $c < $num; $c++) {
				//echo $data[$c] . "<br />\n";
				if($c==0)
				{
					$customer[] = $data[$c];
				}
				else if($c==1)
				{
					$lat[] = $data[$c];
				}
				else if($c==2)
				{
					$lng[] = $data[$c];
				}                                  
			}
		}
		fclose($handle);
	}
			
	echo "\nsizeC=".sizeof($customer);
	for($i=0;$i<sizeof($customer);$i++)
	{
		$coord = $lat[$i].",".$lng[$i];
		$query = "UPDATE station set station_coord='$coord',edit_date='2013-11-19 19:12:00' WHERE customer_no='$customer[$i]' AND type='0' AND user_account_id='$account_id' AND create_date < '2013-11-18 00:00:00'";
		echo "\n".$i." completed";
		$result = mysql_query($query,$DbConnection);
	}	
?>
