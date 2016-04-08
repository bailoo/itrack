<?php
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');

$edit_date=date("Y-m-d H:i:s");

$type1 = $_REQUEST['type'];
$station_id1 = $_REQUEST['station_id'];

if($type1 =="1")
{
  $station_name1 = $_REQUEST['station_name'];
  $customer_no1 = $_REQUEST['customer_no'];
  
  $query = "UPDATE station SET station_name='$station_name1',customer_no='$customer_no1',edit_id='$account_id',edit_date='$edit_date' WHERE station_id='$station_id1'";
  //echo $query;
  $result = mysql_query($query,$DbConnection);
  
  if($result)
  {
    echo "success1";
  }
  else
  {
    echo "failure1";
  }
}
else if($type1 =="2")
{
  $query = "UPDATE station SET status=0,edit_id='$account_id',edit_date='$edit_date' WHERE station_id='$station_id1'";
  //echo $query;
  $result = mysql_query($query,$DbConnection);
  
  if($result)
  {
    echo "success2";
  }
  else
  {
    echo "failure2";
  }
}

?>
