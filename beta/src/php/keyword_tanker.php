<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
include_once("util_account_detail.php");

$tanker=  array();
//$tanker[] = 'dfddfggf';
//$tanker[] = 'aaa';
$vehicle_like = $_GET['term'];
$customer_infos=getDetailAllPGAD($account_id,$DbConnection);
//print_r($customer_infos);
//exit();
$plant_in="";
foreach($customer_infos as $cif)
{
   // echo $cif['plant_customer_no'];
   $plant_in.=" invoice_mdrm.plant=".$cif['plant_customer_no']." OR ";
}
if($plant_in!=""){
        $plant_in = substr($plant_in, 0, -3);
}
                                
$tanker=getAllVehiclePlant($plant_in,$vehicle_like,$DbConnection);
//RETURN JSON ARRAY
echo json_encode($tanker);
//$json = json_encode($tanker);
//print_r($json);
    
    ?>