<?php
include_once('Hierarchy.php');	
include_once('util_session_variable.php');	
include_once('util_php_mysql_connectivity.php');
include_once('coreDb.php');

$DEBUG=0;	
$root = $_SESSION['root'];
$post_action_type = $_POST['action_type'];

if($post_action_type == "update")
{
    //$account_id1 = $account_id;
    $post_imei=trim($_POST['imei']);			
    $post_driver_name= $_POST['driver_name'];				
    $post_driver_mobile = $_POST['driver_mobile']; 
    date_default_timezone_set('Asia/Calcutta'); 
    $create_date = date('Y-m-d H:i:s');

    echo "AccountID=".$account_id." ,Imei=".$post_imei." ,driverName=".$post_driver_name." ,Mobile=".$post_driver_mobile." ,CreateDate=".$create_date;
    
    $vehicle_id = get_vehicle_id($DbConnection, $imei);
    echo "\nVehicleID=".$vehicle_id;
    $result1=updateVehicle_Detail($account_id,$vehicle_id,$post_driver_name,$post_driver_mobile,$post_imei,$DbConnection);
    $result2=insertVehicleDriverHistory_Detail($account_id,$vehicle_id, $post_imei,$post_driver_name,$post_driver_mobile,$create_date,$DbConnection);
    
    if($result1 && $result2) {
        echo"driver_updated##Driver info updated Successfully!";
    } else {
        echo "Error updating Driver Info!";
    }
}
