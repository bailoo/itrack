<?php
//error_reporting(-1);
//ini_set('display_errors', 'On');
$HOST = "itrackdb.c4pqfsdaiccz.us-east-1.rds.amazonaws.com";

$DBASE = "iespl_vts_beta";
//$USER = "root";
$USER = "bailoo";
$PASSWD = 'neon04$VTS';

$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("Connection to server is down. Please try after few minutes.");
mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");
$file = fopen("customer with lat_long.csv","r");
$i=1;
$current_datetime = date('Y-m-d H:i:s');
while (($data = fgetcsv($file, 8000, ",")) !== FALSE) 
{
	$Query="SELECT customer_no FROM station WHERE customer_no='$data[2]' AND user_account_id=715 AND status=1 AND type=0";	
	
	//echo "query=".$Query."<br>";
	$Result=mysql_query($Query,$DbConnection);
	$numRows=mysql_num_rows($Result);
	
	
	$maxStationIdQuery="SELECT max(sno) FROM station";
	$maxStationIdResult=mysql_query($maxStationIdQuery,$DbConnection);
	$maxIdValueRow=mysql_fetch_row($maxStationIdResult);
	$maxId=$maxIdValueRow[0];
	//echo "maxId1=".$maxId."<br>";
	//echo "numRows=".$numRows."<br>";
	if($numRows==0)
	{
		$maxId=$maxId+1;
		$stationCoord=$data[4].",".$data[5];
		$insertQuery="INSERT INTO station(station_id,customer_no,station_name,station_coord,user_account_id,type,status,create_id,create_date)". 
					 " VALUES($maxId,'$data[2]','$data[3]','$stationCoord',715,0,1,1,'$current_datetime')";
		//echo "Query=".$insertQuery."<br><br>";
		$Result=mysql_query($insertQuery,$DbConnection);
	}
	else
	{
		$updateQuery="UPDATE station set station_name='$data[3]' WHERE customer_no='$data[2]' AND account_id=715 AND status=1";
		//echo "updateQuery=".$updateQuery."<br><br>";
		$Result=mysql_query($updateQuery,$DbConnection);
	}
	
	//print_r($data);
	echo "<br>";	
	$i++;
}
//echo "count=".$i."<br>";
fclose($file);
?>