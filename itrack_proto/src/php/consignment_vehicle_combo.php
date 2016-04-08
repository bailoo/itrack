<?php
include_once('Hierarchy.php');		
include_once('util_session_variable.php');
 include_once('util_php_mysql_connectivity.php');
$root=$_SESSION['root'];
//var_dump($root);
//echo "account_id_to_vehicle=".$accountid_to_vehicle."<br>";
$accountid_to_vehicle=explode(",",$accountid_to_vehicle);
$result_string=single_account_vehicle_info($root,$accountid_to_vehicle[0]);
//echo "rs=".$result_string."<br>";
$result_string1=substr($result_string,0,-1);
//echo "rs1=".$result_string1."<br>";
echo"display_combo##vehicle_imei_name##".$result_string1;

function single_account_vehicle_info($AccountNode,$coming_account_id)
{ 
       // echo "account_id1=".$AccountNode->data->AccountID."account_id2=".$coming_account_id."<br>";
	if($AccountNode->data->AccountID==$coming_account_id)
	{	
                $vehicle_info_local="";                
		for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
		{			    
			$vehicle_id = $AccountNode->data->VehicleID[$j];
			$vehicle_name = $AccountNode->data->VehicleName[$j];
			$vehicle_imei = $AccountNode->data->DeviceIMEINo[$j];	
                        $vehicle_info_local=$vehicle_info_local.$vehicle_imei."@".$vehicle_name.":".$vehicle_name.",";
                       //echo "vehicle_info_local=".$vehicle_info_local."<br>";
                        //echo"VehicleCnt=".$AccountNode->data->VehicleCnt."j=".$j."<br>";
                        
                     if($j==($AccountNode->data->VehicleCnt-1))
                       {  
                         //echo "in if";
                           //echo "vehicle_info_local=".$vehicle_info_local."<br>";
                           return $vehicle_info_local;                       
                       }
		}
                
	}	
	$ChildCount=$AccountNode->ChildCnt;
        if($ChildCount==0)
	{
		return null;
	}
	for($i=0;$i<$ChildCount;$i++)
	{ 
	 $vehicle_info_local=single_account_vehicle_info($AccountNode->child[$i],$coming_account_id);
            if($vehicle_info_local!=null)
           {
                   return $vehicle_info_local;
           }
	}
}
?>
