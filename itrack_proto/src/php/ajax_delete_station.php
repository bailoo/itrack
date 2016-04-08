<?php
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');

$station_id1 = $_REQUEST['station_id'];

$query = "UPDATE station SET status=0 WHERE station_id='$station_id1'";
$result = mysql_query($query,$DbConnection);

if($result)
{
  echo "success";
}
else
{
  echo "failure";
}

?>
