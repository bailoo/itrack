<?php
  include_once('util_session_variable.php');
  include_once("util_php_mysql_connectivity.php");
?>

<?php

$DEBUG = 1;

$data=Array();
$data[]="1,UP-78-BT-6369,Truck,Chubeypur";
$data[]="2,UP-78-BT-6427,Truck,Chubeypur";
$data[]="15,UP-17-N-1782,Truck,Rania";
$data[]="16,UP-77-N-1754,Truck,Rania";
$data[]="54,UP-77-E-9656,MAX,CAR";
$data[]="55,UP-77-F-6400,MAX,CAR";

if($DEBUG) echo print_array("Data",$data);

foreach ($data as $key=>$value)
{
  if($DEBUG) echo "Key: ".$key." ; Value: ".$value."<br>";
  $raw = explode(",",$value);
  $SN[$key]=$raw[0];
  $NAME[$key]=$raw[1];
  $TYPE[$key]=$raw[2];
  $LOGIN[$key]=$raw[3];
  
  $query="SELECT VehicleID,VehicleSerial,UserAccess,VehicleName,VehicleType,UserID,Status FROM vehicle WHERE VehicleName LIKE '%$NAME[$key]%'";
  if($DEBUG) print_query($query);
}
$i=0;

$i++; $names[$i]="SN"; $datas[$i]=$SN;
$i++; $names[$i]="NAME"; $datas[$i]=$NAME;
$i++; $names[$i]="TYPE"; $datas[$i]=$TYPE;
$i++; $names[$i]="LOGIN"; $datas[$i]=$LOGIN;

if($DEBUG) echo print_arrays($names,$datas);
 

?>
