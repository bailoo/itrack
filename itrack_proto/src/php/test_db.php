<?php

echo "<br>This is 111.118.181.156<br>";

/*$connection = ssh2_connect('111.118.182.147', 22);
ssh2_auth_password($connection, 'root', 'HHYDHN@146#53');
$tunnel = ssh2_tunnel($connection, '111.118.182.147', 3306);
$DbConnection = new mysqli_connect('111.118.182.147', 'root', 'mysql',  'test_db', 3306, $tunnel) or die ('Fail: '.mysql_error());

echo "<br>DbCon=".$DbConnection;
*/


$HOST = "111.118.182.147";
$DBASE = "test_db";
$USER = "root";
$PASSWD = "mysql";
echo "<br>HOST=".$HOST." ,USER=".$USER." ,PASSWD=".$PASSWD."\n";

$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("Connection to server is down. Please try after few minutes.");
mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");

$query = "select * from record";
$result = mysql_query($query,$DbConnection);


while($row = mysql_fetch_object($result))
{
	echo "<br>Name=".$name = $row->name." ,Age=".$row->age;
}


?>

