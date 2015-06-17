<?php
ini_set('max_execution_time', 1200); //300 seconds = 5 minutes
/**********************************/
/*
1-Exting_io_status.php
	2-manage_account_plant_assignment_pre.php
	3-manage_account_plant_deassignment_prev.php
	4-manage_assign_vehicle_thirdparty
	5-manage_default_chilling_plant_assignment_prev
	6-manage_delete_station
	7-manage_device_assignment
	8-manage_device_vehicle_deregister
	9-manage_device_vehicle_register
	10-manage_edit_account
	11-manage_edit_busdriver
	12-manage_edit_delete_calibration
	13-manage_edit_delete_geofence
	14-manage_edit_delete_invoice
	15-manage_edit_delete_landmark
	16-manage_edit_delete_load_cell
	17-manage_edit_delete_milestone
	18-manage_edit_delete_order
	19-manage_edit_delete_raw_milk
	20-manage_edit_delete_polyline
	21-manage_edit_delete_raw_milk_admin
	22-manage_edit_delete_raw_milk_invoice
	23-manage_edit_delete_route
	24-manage_edit_delete_schedule
	25-manage_edit_delete_schedule_upl
	26-manage_edit_delete_sector
	27-manage_edit_delete_shift
	28-manage_edit_delete_station
	29-manage_edit_delete_vehicle
	30-manage_edit_delete_visit_area
	31-manage_edit_device
	33-manage_edit_device_sale
	34-manage_edit_distance_variable
35-manage_edit_hindalco_invoice_admin *** ****
	36-manage_edit_person
37-manage_edit_raw_milk_admin ***** *****
	38-manage_edit_vehicle
	39-manage_edit_vehicle_1
	40-manage_edit_visit_person
	41-manage_location_assignment
	42-manage_route_vehicle_assignment
	43-manage_route_vehicle_assignment1
	44-manage_route_vehicle_substation_assignment
	45-manage_route_vehicle_substation_inherit
	46-manage_station_vehicle_assignment
	47-manage_substation_vehicle_assignment_prev
	48-manage_substation_vehicle_deassignment
	49-manage_substation_vehicle_deassignment_prev
	50-manage_account_plant_assignment_prev

	#manage_add_raw_milk_admintype **** *****
	#manage_add_raw_milk_usertype_approve **** ****	
	#manage_add_raw_milk_usertype_approve_1 **** ***

/**********************************/
function getTimeZone($accountId,$DbC)
{
	$query="SELECT time_zone FROM account_preference USE INDEX(acc_id) WHERE account_id=$accountId";
	$result=mysql_query($query,$DbC);
	$row=mysql_fetch_row($result);
	return $row[0];	
}

function getFieldName($featureName,$status,$DbC)
{
	$query="SELECT field_name from feature_mapping USE INDEX(featurename_status) WHERE feature_name='$featureName' AND status=$status";
	$result=mysql_query($query,$DbC);
	$row=mysql_fetch_row($result);
	return $row[0];	
}

////////////////////////// start account table /////////////////////
function getUserID($accountId,$status,$DbC)
{
	$query="SELECT user_id FROM account USE INDEX(accountid_status) WHERE account_id='$accountId' AND status=$status";
	$result=mysql_query($query,$DbC);
	$row=mysql_fetch_row($result);
	return $row[0];	
}


function getGroupID($account_id_local,$status,$DbC)
{
	$query="SELECT group_id FROM account USE INDEX(accountid_status) WHERE account_id='$account_id_local' AND status=$status";
	$result=mysql_query($query,$DbC);
	$row=mysql_fetch_row($result);
	return $row[0];	
}

function getAccountID($groupId,$userId,$status,$DbC)
{
	$query="SELECT account_id FROM account USE INDEX(group_userid_status) WHERE group_id='$groupId' AND user_id='$userId' AND status=$status";
	$result=mysql_query($query,$DbC);
	$row=mysql_fetch_row($result);
	return $row[0];	
}

function getAccountIDByGroupId($group_id_local1,$status,$DbC)
{
	$query="SELECT account_id FROM account WHERE group_id='$group_id_local1' AND status=$status";
	$result=mysql_query($query,$DbC);
	$row=mysql_fetch_row($result);
	return $row[0];	
}

function GetAccountInfo($accountID,$status,$DbC)
{
	$query="SELECT user_type,status FROM account USE INDEX(accountid_status) WHERE account_id='$accountID' AND status=$status";
	$result=mysql_query($query,$DbC);
	$row=mysql_fetch_row($result);
	return $row;	
}
/////////////////// end account table ///////////////////
 
 /////////////// start account detail ////////// 
 function getCurrentVGIDAdminId($accountId,$DbC)
{
	$query="SELECT vehicle_group_id,admin_id FROM account_detail USE INDEX(ad_account_id) WHERE account_id='$accountId'";	
	$result=mysql_query($query,$DbC);
	$row=mysql_fetch_row($result);
	return $row;	
}
function getAcccountAdminIdAdminId($account_id,$DbConnection)
{
    $query_account_admin_id="SELECT account_admin_id,admin_id FROM account_detail USE INDEX(ad_account_id) WHERE account_id='$account_id'";
	//echo $query_account_admin_id;
	$result_account_admin_id = mysql_query($query_account_admin_id, $DbConnection);
	$row=mysql_fetch_row($result_account_admin_id);
	return $row;
}

function getPreviousVGIDAdminId($DbC)
{
	$query="SELECT MAX(vehicle_group_id)+1 as vehicle_group_id,MAX(admin_id)+1 as admin_id FROM account_detail";	
	$result=mysql_query($query,$DbC);
	$row=mysql_fetch_row($result);
	//print_r($row);
	return $row;	
}

function getAccountAdminId($accountId,$DbConnection)
{
	$query="SELECT account_admin_id as admin_id FROM account_detail USE INDEX(ad_account_id) WHERE account_id='$accountId'";	
	$result=mysql_query($query,$DbConnection);
	$num_rows=mysql_num_rows($result);
	if($num_rows>0)
	{
	   $row=mysql_fetch_row($result);
	   $rowResult= $row[0];
	}
	else
	{
	  $rowResult=0;   
	}
	return $rowResult;	
}
function getAccountDetailNumRows($accountId,$DbConnection)
{
	$query="SELECT * FROM account_detail USE INDEX (ad_account_id) WHERE  account_id='$accountid'";
	$result=mysql_query($query,$DbConnection);
	$num_rows=mysql_num_rows($result);	
	return $num_rows;	
}

function getAccountIdByAdminId($admin_id,$DbConnection)
{
	$query="SELECT account_id FROM account_detail USE INDEX(admin_id) WHERE admin_id='$admin_id'";
	$result=mysql_query($query,$DbConnection);
        $num_rows=mysql_num_rows($result);
        if($num_rows>0)
        {
           $row=mysql_fetch_row($result);
           $rowResult= $row[0];
        }
        else
        {
          $rowResult=0;   
        }	
	return $rowResult;
}
function getUserTypeAccount($function_account_id,$DbConnection)
{
	$queryType="SELECT user_type from account WHERE account_id='$function_account_id'";
	$resultType=mysql_query($queryType,$DbConnection);
	$rowType=mysql_fetch_row($resultType);
	$function_account_type=$rowType[0];
	return $function_account_type;
}
function getAdminId($accountId,$DbC)
{
	$query="SELECT admin_id FROM account_detail WHERE account_id='$accountId'";	
	$result=mysql_query($query,$DbC);
	$row=mysql_fetch_row($result);
	return $row[0];	
}

function getAccountName($accountId,$DbC)
{
	$query="SELECT name FROM account_detail USE INDEX(ad_account_id) WHERE account_id='$accountId'";	
	$result=mysql_query($query,$DbC);
	$row=mysql_fetch_row($result);
	return $row;	
}

function getDeviceImeiNoByAccId($accountId,$DbC)
{
	$query="SELECT device_assignment.device_imei_no FROM device_assignment USE INDEX(da_accid) LEFT JOIN vehicle_assignment ON device_assignment.device_imei_no =".
		   "vehicle_assignment.device_imei_no AND vehicle_assignment.status=1 WHERE vehicle_assignment.device_imei_no IS NULL AND ".
		   "device_assignment.account_id='$accountId'";	
	$result=mysql_query($query,$DbC);
	$device_row_result=mysql_num_rows($result);
	if($device_row_result==0)
	{
		return $device_row_result;
	}
	else
	{
		while($row=mysql_fetch_object($result))
		{
			$data[]=array('device_imei_no'=>$row->device_imei_no);
		}
		return $data;
	}
	
}

function getCompanyId($accountId,$DbConnection)
{
	$query="SELECT company_id FROM account_detail WHERE account_id='$accountId'";
	$result=mysql_query($query,$DbConnection);
	$row=mysql_fetch_row($result);
	return $row[0];	
}

function getTimeZoneId($accountId,$DbC)
{
	$query="SELECT time_zone FROM account_preference WHERE account_id='$accountId'";
	$result=mysql_query($query,$DbC);
	$row=mysql_fetch_row($result);
	return $row[0];	
}
//////////////// end account detail //////////////
function getMaxDocketNoConsignmentInfo($account_id_local,$DbC)
{
	$query1="SELECT MAX(docket_no) as max_count FROM consignment_info WHERE account_id=$account_id_local";
	$result1=mysql_query($query1,$DbC);
	$max_no = mysql_fetch_object($result1);
	$max_no1=$max_no->max_count;
	return $max_no1;
}

function insertConsignmentInfo($account_id_local,$vehicle_imei_name0,$vehicle_imei_name1,$from_place,$to_place,$consignee_name,$start_date,$end_date,$dock_no,$account_id,$date,$email,$status,$remark,$DbC)
{
	$Query="INSERT INTO `consignment_info`(account_id,device_imei_no,vehicle_name,from_place,to_place,".
			"consignee_name,start_date,end_date,docket_no,create_id,create_date,email_id,status,remark) VALUES('".
			"$account_id_local','$vehicle_imei_name0','$vehicle_imei_name1','$from_place',".
			"'$to_place','$consignee_name','$start_date','$end_date','$dock_no','$account_id','$date','$email',$status,".
			"'$remark')";  
	//echo "Query=".$Query."<br>";	
	$Result=mysql_query($Query,$DbC);
	return $Result;
}
////////////////// start vehicle /////////////////////

function getVehicleNumRow($post_vehicle_name,$status,$DbConnection)
{
	$query = "SELECT vehicle_name FROM vehicle USE INDEX(vname_status) WHERE vehicle_name='$post_vehicle_name' and status=$status limit 1";
	$result=mysql_query($query,$DbConnection);
	$count = mysql_num_rows($result);
	return $count;
}

function getVehicleNumRowNew($post_vehicle_name,$status,$DbConnection)
{
	$query = "SELECT vehicle_name FROM vehicle USE INDEX(vname_status) WHERE vehicle_name='$post_vehicle_name' and status=$status limit 1";
	$result=mysql_query($query,$DbConnection);
	$count = mysql_num_rows($result);
	$vehcileName="";
	if($count>0)
	{
		$row=mysql_fetch_row($result);
		$vehcileName=$row[0];
	}
	return $vehcileName;
}

function getVehicleId($post_vehicle_name,$status,$DbConnection)
{
	$query="SELECT vehicle_id FROM vehicle USE INDEX(vname_status) WHERE vehicle_name='$post_vehicle_name' AND status=$status";
	$result=mysql_query($query,$DbConnection);
	$row=mysql_fetch_row($result);
	return $row[0];
}

function getVehileIdByVehicleName($vname,$status,$DbConnection)
{
	$query ="SELECT vehicle.vehicle_id FROM vehicle USE INDEX(vname_status) INNER JOIN vehicle_grouping ON vehicle.vehicle_id=".
			"vehicle_grouping.vehicle_id AND vehicle_grouping.status=1 AND vehicle.vehicle_name='$vname'".
			" AND vehicle.status=1";     
	//echo "query=".$query."<br>";
	$result=mysql_query($query,$DbConnection);
	$row=mysql_fetch_row($result);
	return $row[0];
}

function getVAVehicleId($vehicleIdLocal,$status,$DbConnection) 
{
	$query="SELECT vehicle_id FROM vehicle_assignment USE INDEX(va_vehicleid_status) WHERE vehicle_id='$vehicleIdLocal' AND status=$status limit 1";
	$result=mysql_query($query,$DbConnection);
	$NumRowCount = mysql_num_rows($result);
	if($NumRowCount>0)
	{
		$row=mysql_fetch_row($result);
		$vehicleId=$row[0];
	}
	else
	{
		$vehicleId="";
	}
	return $vehicleId;
}

function getVGVehicleId($vehicleID,$accountID,$DbConnection)
{
    $query="SELECT vehicle_id FROM vehicle_grouping USE INDEX (vehicleId_accountID) WHERE vehicle_id".
            "=$vehicleID AND account_id=$accountID";
	$result = mysql_query($query, $DbConnection);
	$numrows = mysql_num_rows($result);
	if($numrows>0)
	{
		$row=mysql_fetch_row($result);
		$vehicleId=$row[0];
	}
	else
	{
		$vehicleId="";
	}
	return $vehicleId;
}

function getVAVehicleIdByImeiNo($ImeiNo,$status,$DbConnection) 
{
	$query="SELECT vehicle_id FROM vehicle_assignment USE INDEX (va_imei_status) WHERE device_imei_no ='$ImeiNo'".
		   " AND status='$status'";
	//echo "query=".$query."<br>";
	$result=mysql_query($query,$DbConnection);
	$NumRowCount = mysql_num_rows($result);
	if($NumRowCount>0)
	{
		$row=mysql_fetch_row($result);
		$vehicleId=$row[0];
	}
	else
	{
		$vehicleId="";
	}
	return $vehicleId;
}

function getVehicleGroupingStatus($vehicleID,$accountId,$DbConnection)
{
	$query="SELECT	status FROM vehicle_grouping USE INDEX(vehicleId_accountID) WHERE vehicle_id=".
			"'$vehicleID' AND account_id='$accountId'";
	// echo "query=".$query."<br>";
	$result=mysql_query($query, $DbConnection);
	$numrow = mysql_num_rows($result);
	if($numrow!=0)
	{
		$row=mysql_fetch_row($result);	
		$status_local=$row[0];
	}
	else 
	{
		$status_local='NDF';
	}
	return $status_local;
}

function getVADeviceImeiNo($vehicle_ids,$status,$DbConnection) 
{
	$query = "SELECT device_imei_no FROM vehicle_assignment USE INDEX(va_vehicleid_status) WHERE vehicle_id =$vehicle_ids AND status=$status limit 1";
	//echo" query=".$query."<br>";
	$result = mysql_query($query, $DbConnection);
	$NumRowCount = mysql_num_rows($result);
	if($count>0)
	{
		$NumRowCount=mysql_fetch_row($result);
		$deviceImeiNo=$row[0];
	}
	else
	{
		$deviceImeiNo="";
	}
	return $deviceImeiNo;
}

////////////////// end vehicle table ////////////////


//// start device related table ///////////////////
function getDeviceImeiNo($imeiNo,$status,$DbConnection)
{
	$query="SELECT device_imei_no FROM device_manufacturing_info USE INDEX(dmi_device_imei_status) WHERE ".
		   "device_imei_no='$imeiNo' AND status=$status";
	$result=mysql_query($query,$DbConnection);
	$numrow=mysql_num_rows($result);
	if($numrow>0)
	{
		$deviceImeiNo=mysql_fetch_row($result);
		$row=mysql_fetch_row($result);
		$deviceImeiNo=$row[0];	
	}
	else
	{
		$deviceImeiNo="";
	}
}
function getDANumRowRecord($deviceImeiNo,$accountId,$DbConnection)
{
	$query="SELECT device_imei_no FROM device_assignment USE INDEX (DeviceImeiNo_accountID) WHERE ".
			"device_imei_no = $deviceImeiNo AND account_id=$accountId";
	//echo "query3=".$query."<br>";
	$result = mysql_query($query, $DbConnection);
	$numrows = mysql_num_rows($result);	
	return $numrows;
}

function getDANumRowRecordNew($deviceImeiNo,$accountId,$DbConnection)
{

	$query="SELECT status FROM device_assignment USE INDEX (DeviceImeiNo_accountID) WHERE ".
			"device_imei_no = $deviceImeiNo AND account_id=$accountId";
	$result = mysql_query($query, $DbConnection);
	$numrows = mysql_num_rows($result);
	if($numrows==0)
	{
		return $numrows;
	}
	else
	{
		$row=mysql_fetch_object($result);	
		$device_status=$row->status;
		return $device_status;
	}
}

function getDeviceAssignmentStatus($deviceImeiNo,$accountId,$DbC)
{
    $query ="SELECT	status FROM device_assignment USE INDEX (DeviceImeiNo_accountID) WHERE ".
	"device_imei_no='$deviceImeiNo' AND account_id='$accountId'";
	// echo "query=".$query."<br>";
	$result=mysql_query($query, $DbConnection);
	$numrow = mysql_num_rows($result);
	if($numrow!=0)
	{
		$row=mysql_fetch_row($result);	
		$status_local=$row[0];
	}
	else 
	{
		$status_local='NDF';  // NDF => No Data Found
	}
	return $status_local;
}
/////////////// device assignment table //////////

///////////////// start group table ///////////////////
function getGroupName($post_account_id,$post_group_name,$DbConnection)
{
	$query="SELECT group_name FROM `group` USE INDEX(gname_paname_status) WHERE group_name='$post_group_name' AND ".
                "parent_account_id='$post_account_id' and status!='0'";
	$result=mysql_query($query,$DbConnection);
	$row=mysql_fetch_row($result);
	return $row[0];	
}

function getGroupDetail($post_account_id,$post_group_name,$DbConnection)
{
	$query="SELECT group_id,group_name FROM `group` USE INDEX(gpaid_status) WHERE parent_account_id='$post_account_id' and status!='0'";
	$result=mysql_query($query,$DbConnection);	
	while($row = mysql_fetch_object($result))
	{	
		$groupDetailArr[]=array('group_id'=>$row->group_id,'group_name'=>$row->group_name);
	}
	return $groupDetailArr;	
}

function getGroupMaxSerial($DbConnection)
{
	$query="SELECT MAX(serial_no) as max_count from `group`";
	$result=mysql_query($query,$DbConnection);
	$row=mysql_fetch_row($result);
	return $row[0];	
}
function getGroupSingleRowResult($group_id_local1,$DbConnection)
{
	$query="SELECT group_name,remark FROM `group` USE INDEX (group_id) WHERE group_id='$group_id_local1'";
	$result=mysql_query($query,$DbConnection);
	$row=mysql_fetch_row($result);
	return $row;	
}
////////////////// end group table /////////////

///////////// start calibration ///////////////
function getCalibrationMaxSerial($DbConnection)
{
	$query ="select Max(sno)+1 as seiral_no from calibration";  
	$result=mysql_query($query,$DbConnection);
	$row=mysql_fetch_row($result);
	return $row[0];
}
function getCVACalibrationId($calibration_id,$status,$DbConnection)  ///// CVA=>Calibration Vehicle Assignment
{
	$query="SELECT calibration_id FROM calibration_vehicle_assignment USE INDEX (calibration_status) WHERE ".
	"calibration_id='$calibration_id' AND status='$status'";
	$result=mysql_query($query,$DbConnection); 
	$numrows = mysql_num_rows($result);
	if($numrows!=0)
	{
		$row=mysql_fetch_row($result);
		$calibrationId=$row[0];
	}
	else
	{
		$calibrationId="";
	}
	return $calibrationId;
}

function insertCalibration($maxNo,$calibrationName,$finalCalibrationData,$status,$accountId,$date,$DbConnection)
{
	$query="INSERT INTO calibration(calibration_id,calibration_name,calibration_data,status,edit_id,edit_date)".
	" VALUES ('$maxNo','$calibrationName','$finalCalibrationData',$status,'$accountId','$date');";
	$result = mysql_query($query, $DbConnection);
	return $result;
}
function insertCalibrationGrouping($accountSize,$maxNo,$accountId,$editId,$date,$DbConnection)
{
	$query_string1="INSERT INTO calibration_grouping(calibration_id,account_id,status,edit_id,edit_date) VALUES";
	for($i=0;$i<$accountSize;$i++)
	{
		if($i==$accountSize-1)
		{
			$query_string2.="('$maxNo','$accountId[$i]','1','$editId','$date');";
		}
		else
		{
			$query_string2.="('$maxNo','$accountId[$i]','1','$editId','$date'),";
		}
	}
	$query=$query_string1.$query_string2;	
	$result=mysql_query($query,$DbConnection);  
	return $result;
}
function insertCVA($vehicleSize,$calibrationId,$vehicleIdArr,$accountId,$date,$status,$DbConnection) ///// CVA=>Calibration Vehicle Assignment
{
	$query_string1="INSERT INTO calibration_vehicle_assignment(calibration_id,vehicle_id,edit_id,edit_date,status) VALUES";
	for($i=0;$i<$vehicleSize;$i++)
	{
		if($i==$vehicleSize-1)
		{
			$query_string2.="($calibrationId,$vehicleIdArr[$i],$accountId,'$date',$status);";
		}
		else
		{
			$query_string2.="($calibrationId,$vehicleIdArr[$i],$accountId,'$date',$status),";
		}
	}
	$query=$query_string1.$query_string2; 	     
	$result=mysql_query($query,$DbConnection);
	return $result;	
}

function updateCalibration($calibrationName,$finalCalibrationData,$accountId,$date,$calibrationId,$DbConnection)
{
	$query="UPDATE calibration SET calibration_name='$calibrationName',calibration_data='$finalCalibrationData',".
		   "edit_id='$accountId',edit_date='$date' WHERE calibration_id='$calibrationId'";
	$result = mysql_query($query, $DbConnection);
    return $result;	
}
function updateCVA($accountId,$date,$vehicleId,$status,$DbC) ///// CVA=>Calibration Vehicle Assignment
{
	$query="UPDATE calibration_vehicle_assignment SET status=0,edit_id='$accountId',edit_date='$date'".
	" WHERE vehicle_id='$vehicleId' AND status=$status";
	$result=mysql_query($query,$DbC); 
	return $result;
}

function deleteCalibration($accountId,$date,$status,$calibrationId,$status,$DbC)
{
	 $query="UPDATE calibration SET edit_id='$accountId',edit_date='$date',status=0 WHERE ".
	 "calibration_id='$calibrationId' AND status=$status"; 
	 $result=mysql_query($query,$DbC);
     return $result;		 
}

///////////////// end calibration ///////////////


///////////// start consignment_info /////////////////
function getCIMaxcnt($account_id_local,$DbConnection)
{
	$query="SELECT MAX(docket_no) as max_count from consignment_info USE INDEX(accountId_status) WHERE".
		   " account_id=$account_id_local AND status=1";
	$result=mysql_query($query,$DbConnection);
	$max_no = mysql_fetch_row($result1);
	return $row[0];
}

function insertConsignmetInfo($accountId,$vehicleImei1,$vehicleImei2,$fromPlace,$toPlace,$consigneeName,$startDate,$endDate,$dockNo,$createId,$date,$email,$status,$remark,$DbConnection)
{
	$Query="INSERT INTO `consignment_info`(account_id,device_imei_no,vehicle_name,from_place,to_place,".
	"consignee_name,start_date,end_date,docket_no,create_id,create_date,email_id,status,remark) ".
	"VALUES('$accountId','$vehicleImei1','$vehicleImei2','$fromPlace','$toPlace','$consigneeName',".
	"'$startDate','$endDate','$dockNo','$createId','$date','$email',1,'$remark')"; 
	$Result=mysql_query($Query,$DbConnection);
	return $Result;
}
		
//////////////////end consignment info ///////////////

function insertAccount($userId,$groupId,$password,$userType,$status,$createId,$createDate,$DbC)
{
	$query="INSERT INTO account(user_id,group_id,password,user_type,status,create_id,create_date) VALUES ". 
		   "('$userId','$groupId','$password','$userType',$status,'$createId','$createDate')";
	//echo "query=".$query."<br>";
	//echo "DBC=".$DbC."<br>";
	$result=mysql_query($query,$DbC);
	//echo "result=".$result."<br>";
	return $result;	
}

function insertGroup($group_id_local,$post_group_name,$post_account_id,$account_id,$date,$status,$post_remark,$DbC)
{
	$query="INSERT INTO `group`(group_id,group_name,parent_account_id,create_id,create_date,status,".
                "remark) VALUES('$group_id_local','$post_group_name','$post_account_id','$account_id',".
                "'$date',$status,'$post_remark')";
	//echo "query=".$query."<br>";
	//echo "DBC=".$DbC."<br>";
	$result=mysql_query($query,$DbC);
	//echo "result=".$result."<br>";
	return $result;	
}

function insertAccountDetail($account_id1,$post_user_name,$distance_variable,$vehicle_group_id1,$company_id1,$admin_id1,$admin_id2,$post_ac_type,$account_id,$date,$DbC)
{
	$query="INSERT INTO account_detail(account_id,name,distance_variable,vehicle_group_id,company_id,".
			"admin_id,account_admin_id,permission,create_id,create_date) VALUES".
			"('$account_id1','$post_user_name',$distance_variable,'$vehicle_group_id1','$company_id1',".
			"'$admin_id1','$admin_id2','$post_ac_type','$account_id','$date')";
	$result=mysql_query($query,$DbC);
	return $result;	
}

function insertAccountFeature($list_fname,$account_id1,$list_fvalue,$post_user_type,$account_id,$date,$DbC)
{
	$query="INSERT INTO account_feature(account_id".$list_fname.",user_type_id,create_id,create_date)".
		   " VALUES('$account_id1'$list_fvalue,'$post_user_type','$account_id','$date')";
	$result=mysql_query($query,$DbC);
	return $result;	
}

function insertAccountPreference($account_id1,$time_zone1,$latlng1,$refresh_rate1,$account_id,$date,$DbC)
{
	$query="INSERT INTO account_preference(account_id,time_zone,latlng,refresh_rate,create_id,create_date)".
		   " VALUES('$account_id1','$time_zone1','$latlng1','$refresh_rate1','$account_id','$date')";
	$result=mysql_query($query,$DbC);
	return $result;	
}

function insertDeviceMInfo($imei_no_1,$io_ids_1,$manufacturing_date_1,$make_1,$account_id,$date,$status,$DbC)
{
	$query="INSERT INTO device_manufacturing_info(device_imei_no,io,manufacture_date,make,create_id,".
		   "create_date,status)VALUES('$imei_no_1','$io_ids_1','$manufacturing_date_1','$make_1',".
		   "'$account_id','$date','1')";
	$result=mysql_query($query,$DbC);
	return $result;
}

function insertVehicleRecord($post_vehicle_name,$post_vehicle_type,$post_vehicle_tag,$post_max_speed,$post_category,$post_vehicle_number,$post_sim_number,$post_mobile_number,$status,$account_id,$date,$DbConnection)
{
	$query="INSERT INTO vehicle(vehicle_name,vehicle_type,vehicle_tag,max_speed,category,vehicle_number,".
		   "sim_number,mobile_number,status,create_id,create_date) VALUES('$post_vehicle_name',".
		   "'$post_vehicle_type','$post_vehicle_tag','$post_max_speed','$post_category',".
		   "'$post_vehicle_number','$post_sim_number','$post_mobile_number','$status','$account_id','$date')";
			//echo "query=".$query."<br>";
	$result=mysql_query($query,$DbConnection);
	return $result;
}

function insertVGSingleRecord($vehicleId,$addAccountId,$status,$createId,$date,$DbConnection)
{
	$query="INSERT INTO vehicle_grouping (vehicle_id,account_id,status,create_id,create_date)".
		   " VALUES('$vehicleId',$addAccountId,$status,$createId,'$date')";
	//echo "query=".$query."<br>";
	$result = mysql_query($query, $DbConnection);
	return $result;
}
			
function insertVehicleGrouping($vehicle_id,$account_size,$local_account_ids1,$account_id,$date,$status,$DbConnection)
{
	$query_string1="INSERT INTO vehicle_grouping(vehicle_id,account_id,create_id,create_date,status) VALUES";				
	for($i=0;$i<$account_size;$i++)
	{
		if($i==$account_size-1)
		{
			$query_string2.="($vehicle_id,$local_account_ids1[$i],$account_id,'$date',$status);";
		}
		else
		{
			$query_string2.="($vehicle_id,$local_account_ids1[$i],$account_id,'$date',$status),";
		}
	}				
	$query=$query_string1.$query_string2; 
	$result=mysql_query($query,$DbConnection);
	return $result;
}

function insertVehicleAssignment($vid,$device,$account_id,$date,$status,$DbConnection)
{
	$query="INSERT INTO vehicle_assignment(vehicle_id, device_imei_no, create_id, create_date, status)".
		   " VALUES('$vid','$device','$account_id','$date','$status')";
	$result=mysql_query($query,$DbConnection); 
	return $result;
}


function insertDeviceAssignment($deviceImeiNo,$accountIdLocal,$status,$accountId,$date,$DbConnection)
{
	$query="INSERT INTO device_assignment(device_imei_no,account_id,status,create_id,create_date) VALUES".
		 "('$deviceImeiNo',$accountIdLocal,$status,$accountId,'$date')";
	$result=mysql_query($query,$DbConnection);
	return $result;
}

/*function insertDeviceMInfo($imeiNo,$ioIds,$manufacturingDate,$make,$accountId,$date,$status,$DbConnection)
{
	$query="INSERT INTO device_manufacturing_info(device_imei_no,io,manufacture_date,make,create_id,".
		   "create_date,status)VALUES('$imeiNo','$ioIds','$manufacturingDate','$make',".
		   "'$accountId','$date',$status)";
	$result=mysql_query($query,$DbC);
	return $result;
}*/

function updateAccount($account_id_local,$account_id,$date,$status,$DbC)
{
	$query="UPDATE account SET status='0',edit_id='$account_id',edit_date='$date' WHERE ".
			"account_id='$account_id_local'";
	$result=mysql_query($query,$DbC);
	return $result;	
}

function updateAccountDetail($editAccountId,$post_user_name,$distance_variable,$post_perm_type,$DbC)
{
	$query="UPDATE account_detail SET name='$post_user_name',distance_variable='$distance_variable',".
		   "permission=$post_perm_type WHERE account_id='$editAccountId'";
	//echo "query=".$query."<br>";
	$result=mysql_query($query,$DbC);
	
	return $result;	
}

function updateAccountFeature($update_string,$post_user_type,$postEditAccountId,$DbC)
{
	$query="UPDATE account_feature SET ".$update_string.",user_type_id='$post_user_type' WHERE ".
		   "account_id='$postEditAccountId'";
	//echo "query=".$query."<br>";
	$result=mysql_query($query,$DbC);
	return $result;	
}

function updateGroup($group_name,$remark,$group_id_local1,$DbC)
{
	$query="UPDATE `group` SET group_name='$group_name',remark='$remark' WHERE group_id=".
		   "'$group_id_local1'";
	//echo "query=".$query."<br>";
	$result=mysql_query($query,$DbC);
	return $result;	
}

function updateVehicleAssignment($account_id,$date,$deviceImei,$DbConnection)
{
	$query="UPDATE vehicle_assignment SET status='0',edit_id='$account_id',edit_date='$date' WHERE ".
				   "device_imei_no='$deviceImei'";
	$result=mysql_query($query,$DbConnection); 
	return $result;
}

function updateVehicleDetail($vehicle_name_edit,$vehicle_number_edit,$post_sim_number,$post_mobile_number,$max_speed_edit,$vehicle_tag_edit,$vehicle_type_edit,$category,$account_id,$date,$vehicle_id_edit,$status,$DbConnection)
{
	$query="UPDATE vehicle SET vehicle_name='$vehicle_name_edit',vehicle_number='$vehicle_number_edit',".
		"sim_number='$post_sim_number',mobile_number='$post_mobile_number',max_speed='$max_speed_edit',".
		"vehicle_tag='$vehicle_tag_edit',vehicle_type='$vehicle_type_edit',category='$category',".
		"edit_id='$account_id', edit_date='$date' WHERE vehicle_id='$vehicle_id_edit' AND status=$status"; 
    //echo "query=".$query; 
	$result = mysql_query($query, $DbConnection); 
	return $result;
}

function updateVehicleGrouping($date,$vehicleId,$status,$accountId,$DbConnection)
{
	$query ="UPDATE vehicle_grouping SET status=$status,edit_date='$date' WHERE vehicle_id=".
			"'$vehicleId' AND account_id=$accountId";
	$result = mysql_query($query, $DbConnection);
	return $result;
}

function updateDeviceAssignment($deviceImeiNo,$status,$accountIdLocal,$date,$DbConnection)
{
	$query ="UPDATE device_assignment SET status=$status,edit_date='$date' WHERE device_imei_no='$deviceImeiNo'".
			" AND account_id=$accountIdLocal";
	$result = mysql_query($query, $DbConnection);
	return $result;
}


function updateDeviceManufacturingInfo($ioId,$ImeiNo,$status,$DbConnection)
{
	$query ="UPDATE device_manufacturing_info SET io='$ioId' WHERE device_imei_no='$ImeiNo' AND status='$status'";
	//echo "query=".$query;
	$result = mysql_query($query, $DbConnection);
    return $result;	
}

function deleteGroup($account_id,$date,$group_id_local1,$DbC)
{
	$query="UPDATE `group` SET status='0',edit_id='$account_id',edit_date='$date' WHERE group_id='$group_id_local1'";
	//echo "query=".$query."<br>";
	$result=mysql_query($query,$DbC);
	return $result;	
}

function deleteVehicle($account_id,$date,$status,$vehicle_id_local,$DbConnection)
{
	$query="UPDATE vehicle SET status=0,edit_id='$account_id',edit_date='$date' WHERE ".
		   "vehicle_id='$vehicle_id_local' AND status=$status";			
	$result = mysql_query($query, $DbConnection);  
	return $result;
}

function deleteVehicleGrouping($accountId,$date,$status,$vehicleId,$DbConnection)
{
	$query = "UPDATE vehicle_grouping SET status=0,edit_id='$accountId',edit_date='$date' WHERE ".
			"vehicle_id='$vehicleId' AND status=$status";			
	$result = mysql_query($query, $DbConnection); 
	return $result;
}

///////////// start of escalation ////////////

function getEscalatinMaxCnt($DbConnection)
{
	$query ="SELECT Max(serial)+1 as seiral_no FROM escalation";  ///// for auto increament of geo_id ///////////   
	$result=mysql_query($query,$DbConnection);
	$row=mysql_fetch_row($result);
	return $row[0];
}

function getAAEscalationId($escalationId,$status,$DbConnection) // AA=> Alert Assignment
{
	$query="SELECT escalation_id  FROM alert_assignment USE INDEX(escalationId_status) WHERE escalation_id=".
		"'$escalationId' and status=$status";
		
	//echo "query=".$query."<br>";
	$result=mysql_query($query,$DbConnection); 
	$numrows = mysql_num_rows($result);
	if($numrows>0)
	{
		$row=mysql_fetch_row($result);
	}
	else
	{
		$row=0;
	}
	return $row;
}

function getAlertAlertName($alertId,$status,$DbConnection)
{
	$query="SELECT alert_name FROM alert USE INDEX(alertId_status) WHERE alert_id='$alertId'".
			  " AND status=$status"; 
	$result = mysql_query($query,$DbConnection);    
	$row = mysql_fetch_row($result);
	return $row[0];
}

function getEscalationPersonName($escalationId,$status,$DbConnection)
{
	$query="SELECT person_name FROM escalation WHERE escalation_id='$escalationId' AND status=$status";
	$result = mysql_query($query,$DbConnection);    
	$row = mysql_fetch_row($result);
	return $row[0];
}

 
 function getLandmarkName($local_landmark_id,$status,$DbConnection)
{
	$query="SELECT landmark_name FROM landmark WHERE landmark_id='$local_landmark_id' AND status=$status";
	$result=mysql_query($query,$DbConnection);
	$row=mysql_fetch_object($result);
	return $row->landmark_name;
}

function insertLandmark($account_size,$local_account_ids,$max_no,$landmark_name1,$landmark_point1,$zoom_level1,$status,$account_id,$date,$DbConnection)
{
	$query_string1="INSERT INTO landmark(account_id,landmark_id,landmark_name,landmark_coord,zoom_level,status,create_id,create_date) VALUES";
	for($i=0;$i<$account_size;$i++)
	{
		//echo "accout_id=".$local_account_ids[$i]."<br>";
		if($i==$account_size-1)
		{
			$query_string2.="('$local_account_ids[$i]','$max_no','$landmark_name1','$landmark_point1','$zoom_level1','1','$account_id','$date');";
		}
		else
		{
			$query_string2.="('$local_account_ids[$i]','$max_no','$landmark_name1','$landmark_point1','$zoom_level1','1','$account_id','$date'),";
		}
	}
	$query=$query_string1.$query_string2; 
	//echo "query=".$query;
	//}
	$result=mysql_query($query,$DbConnection);  
	return $result;
}

function getLandmarkMaxSerial($DbConnection)
{
	$query ="select Max(sno)+1 as seiral_no from landmark";  ///// for auto increament of landmark_id ///////////   
	$result=mysql_query($query,$DbConnection);
	$row=mysql_fetch_object($result);
	$max_no= $row->seiral_no;
	return $max_no;
}

function getAlertAssignment($query_exist,$DbConnection)
{
	//echo "<br>query1=".$query_exist;    		
	$result_exist=mysql_query($query_exist,$DbConnection);
	//$row_db = mysql_fetch_object($result_exist);
	//$alert_dur_db = $row_db->alert_duration;
	$numrows = mysql_num_rows($result_exist);
	return $numrows;
}

function insertAlertAssignment($query2,$DbConnection)
{
	$result=mysql_query($query2,$DbConnection);
	return $result;
}

function getVehicleNameByVid($vehicleId,$status,$DbConnection)
{
	$query="SELECT vehicle_name FROM vehicle WHERE vehicle_id='$vehicleId' AND status=$status";
	//echo "query=".$query."<br>";
	$result = mysql_query($query,$DbConnection);    
	$row = mysql_fetch_row($result);
	
	$vehicle_name="";
	if(count($row)>0)
	{
		$vehicle_name=$row[0];
	}
	//echo "vehicle_name=".$vehicle_name."<br>";
	return $vehicle_name;	
}

function getVehicleDetailForAlert($vehicle_id_str,$status,$DbConnection)
{
	$query_vname ="SELECT vehicle_name FROM vehicle WHERE vehicle_id IN ($vehicle_id_str) AND status=$status"; 
	//echo "<br>qvname=".$query_vname;
	$result_vname = mysql_query($query_vname,$DbConnection); 
	while($row_vname = mysql_fetch_object($result_vname))
	{
		$data[]=array('vehicle_name'=>$row_vname->vehicle_name);
	}
	return $data;	
}

function getLandmarkDetail($post_account_id,$status,$DbConnection)
{
	$query_landmark="SELECT landmark_id, landmark_name FROM landmark WHERE ".
				"account_id='$post_account_id' AND status=$status";
	//echo "<br>query_landmark=".$query_landmark;
	$result_landmark = mysql_query($query_landmark,$DbConnection); 
	while($row_landmark = mysql_fetch_object($result_landmark))
	{
		$data[]=array('landmark_id'=>$row_landmark->landmark_id,'landmark_name'=>$row_landmark->landmark_name);          
	}
	return $data;
}

function getEscalationDetail($post_account_id,$status,$DbConnection)
{
	$query="SELECT * FROM escalation where escalation_id IN (SELECT escalation_id from ".
	   "escalation_grouping where account_id='$post_account_id' AND status=$status) AND ".
	   "status=$status";
	//echo "query=".$query."<br>";
	$result=mysql_query($query,$DbConnection);
	$row_result=mysql_num_rows($result);
	if($row_result==0)
	{
		return $row_result;
	}
	else
	{
		while($row=mysql_fetch_object($result))  
		{
			$data[]=array('escalation_id'=>$row->escalation_id,'person_name'=>$row->person_name);          
		}
		return $data;
	}
}
										
function getEscalationSingleRow($personMob,$personName,$status,$DbConnection)
{
	$query_select ="SELECT person_name,person_mobile FROM escalation USE INDEX(pmno_pname_status) WHERE person_mobile='$personMob'".
	" AND person_name='$personName' AND status=$status";
	$result_select = mysql_query($query_select,$DbConnection);
	$numrows_select = mysql_num_rows($result_select);
	if($numrows_select>0)
	{
		$row=mysql_fetch_row($result_select);
	}
	else
	{
		$row="";
	}
	return $row;
}
function insertEscalation($maxNo,$personName,$personMob,$personEmail,$otherDetail,$status,$createId,$date,$DbConnection)
{
	$query="INSERT INTO escalation(escalation_id,person_name,person_mobile,person_email,other_detail,status,".
		"create_id,create_date) VALUES ('$maxNo','$personName','$personMob','$personEmail',".
		"'$otherDetail',$status,$createId,'$date');";
	//echo $query."<br>";
	$result=mysql_query($query,$DbConnection);
	return $result;
}

function insertEscalationGrouping($maxNo,$accountSize,$accountIds,$status,$createId,$date,$DbConnection)
{
	$query_string1="INSERT INTO escalation_grouping(escalation_id,account_id,status,edit_id,edit_date) VALUES";
	for($i=0;$i<$accountSize;$i++)
	{
		if($i==$accountSize-1)
		{
			$query_string2.="('$maxNo','$accountIds[$i]','$status','$createId','$date');";
		}
		else
		{
			$query_string2.="('$maxNo','$accountIds[$i]','$status','$createId','$date'),";
		}
	}
	$query=$query_string1.$query_string2;	
	//echo "query=".$query."<br>";	
	$result=mysql_query($query,$DbConnection);   
	return $result;
}

function updateEscalation($personName,$personMob,$personEmail,$otherDetail,$createId,$date,$escalationId,$DbConnection)
{
	$query="UPDATE escalation SET person_name='$personName',person_mobile='$personMob',person_email=".
			"'$personEmail',other_detail='$otherDetail',edit_id='$createId',edit_date='$date' WHERE ".
			"escalation_id='$escalationId'";
	//echo "query=".$query;
	$result=mysql_query($query,$DbConnection);		
	return $result;
}

function deleteEscalation($createId,$date,$escalationId,$status,$DbConnection)
{
	$query="UPDATE escalation SET edit_id='$createId',edit_date='$date',status='0' WHERE ".
		   "escalation_id='$escalationId' AND status=1"; 
	$result=mysql_query($query,$DbConnection); 
	return $result;
}
///////////////////////////////////////Date 14-04-2015/////////////////////////////////
/**********************************IO Assignment*********************************/
function getIoAssignmentFnameList($DbConnection)
{
    $query="SELECT column_name FROM information_schema.columns WHERE table_name='io_assignment' AND ordinal_position>'2' AND ordinal_position<'8' AND table_schema='iespl_vts_beta'";
	//echo "query=".$query."<br>";
	$result=mysql_query($query,$DbConnection);
	$feature_count=mysql_num_rows($result);
	$fi=0;
	$list_fname="";
	while ($row=mysql_fetch_object($result))
	{
		$fi++;
		$fname[$fi]=$row->column_name;
		if ($fi<$feature_count) $list_fname.=$fname[$fi].",";
	}
	$list_fname.=$fname[$fi];
	return $list_fname;
}


function getFeatureCount($DbConnection)
{
   $query="SELECT DISTINCT column_name FROM information_schema.columns WHERE table_name='io_assignment' AND".
	" ordinal_position>'2' AND ordinal_position<'17' AND table_schema='iespl_vts_beta'";
	//echo "Query=".$query."<br>";
	$result=mysql_query($query,$DbConnection);
	$feature_count=mysql_num_rows($result);
	return $feature_count;
}

function getIoAssignmentFnameListNextNew($feature_count,$DbConnection)
{
   $query="SELECT DISTINCT column_name FROM information_schema.columns WHERE table_name='io_assignment' AND".
	" ordinal_position>'2' AND ordinal_position<'17' AND table_schema='iespl_vts_beta'";
	//echo "Query=".$query."<br>";
	$result=mysql_query($query,$DbConnection);
	$fi=0;
	$list_fname="";
	while ($row=mysql_fetch_object($result))
	{
		$fi++;
		$fname[$fi]=$row->column_name;
		if ($fi<$feature_count)
		{
			$list_fname.=$fname[$fi].",";
		}
	}
	$list_fname.=$fname[$fi];
	return $list_fname;
}

function getFeatureNameArr($DbConnection)
{
   $query="SELECT DISTINCT column_name FROM information_schema.columns WHERE table_name='io_assignment' AND".
	" ordinal_position>'2' AND ordinal_position<'17' AND table_schema='iespl_vts_beta'";
	//echo "Query=".$query."<br>";
	$result=mysql_query($query,$DbConnection);	
	$fi=0;
	while ($row=mysql_fetch_object($result))
	{
		$fi++;
		$fname[$fi]=$row->column_name;		
	}
	return $fname;
}

function getIoAssignmentFnameListNext($DbConnection)
{
   $query="SELECT DISTINCT column_name FROM information_schema.columns WHERE table_name='io_assignment' AND".
	" ordinal_position>'2' AND ordinal_position<'17' AND table_schema='iespl_vts_beta'";
	//echo "Query=".$query."<br>";
	$result=mysql_query($query,$DbConnection);
	$feature_count=mysql_num_rows($result);
	$fi=0;
	$list_fname="";
	while ($row=mysql_fetch_object($result))
	{
		$fi++;
		$fname[$fi]=$row->column_name;
		if ($fi<$feature_count)
		{
			$list_fname.=$fname[$fi].",";
		}
	}
	$list_fname.=$fname[$fi];
	return $list_fname;
}

function getIoAssignmentNumRow($list_fname,$vehicle_ids_1,$DbConnection)
{
	$query="select '".$list_fname."' FROM io_assignment USE INDEX(vehicle_id) where vehicle_id='$vehicle_ids_1'";
	//echo "query=".$query."<br>";
	$result=mysql_query($query,$DbConnection);
	$num_rows=mysql_num_rows($result);
	return $num_rows;
}
function insertIoAssignmentStr($io_field_name,$io_field_value,$vehicle_ids_1,$status1,$DbConnection)
{
	$query="insert into io_assignment(".$io_field_name.",vehicle_id,status) values(".$io_field_value.",'$vehicle_ids_1','$status1')";
		echo "query2=".$query."<br>";
	$result=mysql_query($query,$DbConnection);
	return $result;
}
function updateIoAssignmentStr($query_string,$vehicle_ids_1,$DbConnection)
{
	$query="UPDATE io_assignment SET ".$query_string."vehicle_id=$vehicle_ids_1 WHERE vehicle_id='$vehicle_ids_1'";
	echo "query2=".$query."<br>";
	$result=mysql_query($query,$DbConnection);
        Return $result;	
}
/*******************************************************************************/

/********************************Third Party Account Assignment****************************/
 function getThirdPartyIDThirdPartyAccountAssignment($post_source_account,$status1,$DbConnection)
 {
    $third_party_account_id=array();
    $querydesaid="SELECT third_party_account_id from third_party_account_assignment WHERE admin_account_id='$post_source_account' and status='$status1'";
    $resultdesaid=mysql_query($querydesaid,$DbConnection);
    while($row=mysql_fetch_object($resultdesaid))
    {
        $third_party_account_id[]=$row->third_party_account_id;
    }
    return $third_party_account_id; 
 }
 function updateThirdPartyAccountAssignment($status0,$date,$account_id,$post_source_account,$status1,$DbConnection)
 {
	$queryUpdate="UPDATE third_party_account_assignment SET status='$status0',edit_date='$date',edit_id='$account_id' where admin_account_id='$post_source_account' and status=1";
	$resultUpdate=mysql_query($queryUpdate,$DbConnection);
	return $resultUpdate; 
 }
/******************************************************************************************/
/***********************************Trasporter Chilling Plant Assignment*********************************/
function getDefaultSnoTransChillingPlantAssign($transporter,$status1,$DbConnection)
{
	$query ="SELECT	sno FROM transporter_chilling_plant_assignment WHERE account_id=$transporter  AND status='$status1'";
	$result = mysql_query($query, $DbConnection);	
    $NumRowCount = mysql_num_rows($result);
	if($NumRowCount>0)
	{
		$row=mysql_fetch_row($result);
		$default_sno=$row[0];
	}
	else
	{
		$default_sno="";
	}
	return $default_sno;	
}
function updateTransChillingPlantAssign($status0,$transporter,$status1,$DbConnection)
{
	$query ="UPDATE transporter_chilling_plant_assignment SET status='$status0'  WHERE  account_id=$transporter and status='$status1'";		
	//echo $query;
	$result = mysql_query($query, $DbConnection);
    return $result;
}
/********************************************************************************************************/
/*********************************************Invoice Hindalco*****************************************/
function getEmailIDAccountDetail($acc_email_id,$DbConnection)
{
	$query_email2 = "SELECT email FROM account_detail USE INDEX (ad_account_id) WHERE account_id='$acc_email_id' ";
	//echo $query_email2."<br>";
	$result_query_email2 = mysql_query($query_email2,$DbConnection);
	$row_all_account_email2 =mysql_fetch_object($result_query_email2);
	$tmp_acc_email=$row_all_account_email2->email;
	return $tmp_acc_email;
}
function getAccountIdVehicleGrouping($vehicle_no,$DbConnection)
{
	$query_email1 = "SELECT vehicle_grouping.account_id as aid FROM vehicle USE INDEX(v_vehicleid_status),vehicle_grouping WHERE vehicle.vehicle_name='$vehicle_no' AND vehicle.status=1  and vehicle_grouping.vehicle_id=vehicle.vehicle_id and vehicle_grouping.status=1";
	//echo $query_email1."<br>";
	$result_query_email1 = mysql_query($query_email1,$DbConnection);
	while($row_all_account_email1=mysql_fetch_object($result_query_email1))
	{
		$acc_email_id=$row_all_account_email1->aid;
	}
	return $acc_email_id; 

}
function getMaxCountInvoiceHindalco($account_id,$DbConnection)
{
	$query1="SELECT MAX(sno) as max_count from invoice_hindalco USE INDEX (inhi_paid) WHERE parent_account_id=$account_id";
	//echo"query=".$query1."<br>";
	if($DEBUG) print_query($query1);
	$result1=mysql_query($query1,$DbConnection);
	$max_no = mysql_fetch_object($result1);
	$max_no1=$max_no->max_count;
	return $max_no1;
}
function insertInvoiceHindalco($lorry_no,$vehicle_no,$email,$mobile,$qty_kg,$customer,$dock_no,$dispatch_time,$target_time,$validity_time,$product_type,$product_type,$driver_name,$driver_mobile,$account_id,$date,$DbConnection)
{
	$Query="INSERT INTO `invoice_hindalco`(lorry_no,vehicle_no,email,mobile,qty_kg,customer,docket_no,dispatch_time,target_time,validity_time,product_type,driver_name,driver_mobile,parent_account_id,create_id,create_date,invoice_status,status) VALUES('".
			"$lorry_no','$vehicle_no','$email','$mobile',".
			"'$qty_kg','$customer','$dock_no','$dispatch_time','$target_time','$validity_time','$product_type','$driver_name','$driver_mobile','$account_id','$account_id','$date',1,1)";  
	//echo "Query=".$Query."<br>";
	if($DEBUG) print_query($Query);
	$Result=mysql_query($Query,$DbConnection);
	return $Result;
 
}
function insertConsignmetInfoDoct($account_id,$vehicle_IMEI,$vehicle_no,$customer,$dispatch_time,$validity_time,$dock_no,$account_id,$date,$DbConnection)
{
	$QueryDocket="INSERT INTO consignment_info (account_id,device_imei_no,vehicle_name,consignee_name,".
				"start_date,end_date,docket_no,create_id,create_date,status) VALUES ".
				"('$account_id','$vehicle_IMEI','$vehicle_no','$customer','$dispatch_time','$validity_time','$dock_no','$account_id','$date',1)";
				$ResultDOCKET=mysql_query($QueryDocket,$DbConnection);
				return $ResultDOCKET;
}
function updateInvoiceHindalco($product_type_serials,$account_id,$date,$sno,$DbConnection)
{
	$query_update = "UPDATE invoice_hindalco SET product_type='$product_type_serials',edit_id='$account_id',edit_date='$date' WHERE sno='$sno'";
	$result_update = mysql_query($query_update,$DbConnection);	
	return $result_update; 
}
function updateInvoiceHindalcoAccept($product_type_serials,$account_id,$date,$sno,$DbConnection)
{
	$query_update = "UPDATE invoice_hindalco SET product_type='$product_type_serials',edit_id='$account_id',edit_date='$date' ,acceptance_time='$date' WHERE sno='$sno'";
	$result_update = mysql_query($query_update,$DbConnection);	
	return $result_update; 
}
function updateInvoiceHindalcoClose($account_id,$date,$closetime,$sno,$DbConnection)
{
	$query_update = "UPDATE invoice_hindalco SET invoice_status=2,edit_id='$account_id',edit_date='$date',system_time='$date',close_time='$closetime' WHERE sno='$sno'";
	//echo $query_update;
	$result_update = mysql_query($query_update,$DbConnection);
	return $result_update;	
}
function deleteInvoiceHindalco($account_id,$date,$sno,$DbConnection)
{
	$query_update = "UPDATE invoice_hindalco SET invoice_status=0,edit_id='$account_id',edit_date='$date' ,system_time='$date' WHERE sno='$sno'";
	//echo $query_update;
	$result_update = mysql_query($query_update,$DbConnection);
   	return $result_update;
}
/******************************************************************************************************/
/***********************************Invoice Mdrm********************************************************/
function getMaxCountInvoiceMdrm($account_id,$DbConnection)
{
	$query1="SELECT MAX(sno) as max_count from invoice_mdrm USE INDEX(inmd_paid) where parent_account_id=$account_id";
	$result1=mysql_query($query1,$DbConnection);
	$max_no = mysql_fetch_object($result1);
	$max_no1=$max_no->max_count;
	return $max_no1;
}

function updateInvoiceMdrm($lorry_no,$transporter,$email,$mobile,$qty_kg,$fat_per,$snf_per,$fat_kg,$snf_kg,$milk_age,$dispatch_time,$target_time,$validity_time,$plant,$chillplant,$tanker_type,$driver_name,$driver_mobile,$account_id,$date,$sno_id1,$DbConnection)
{
	$QueryUpdate="UPDATE `invoice_mdrm` SET lorry_no= '$lorry_no',transporter_account_id='$transporter',email='$email',mobile='$mobile',qty_kg='$qty_kg',fat_percentage='$fat_per',snf_percentage='$snf_per',
				fat_kg = '$fat_kg',snf_kg='$snf_kg',milk_age='$milk_age',dispatch_time='$dispatch_time',target_time='$target_time',validity_time='$validity_time',plant='$plant',chilling_plant='$chillplant',
				tanker_type='$tanker_type',driver_name='$driver_name',driver_mobile='$driver_mobile',edit_id='$account_id',parent_account_id='$account_id',edit_date='$date',invoice_status=5,status=1 WHERE sno='$sno_id1' ";  
				$ResultUpdate=mysql_query($QueryUpdate,$DbConnection);
				return $ResultUpdate;
}
function insertInvoiceMdrm($lorry_no,$transporter,$email,$mobile,$qty_kg,$fat_per,$snf_per,$fat_kg,$snf_kg,$milk_age,$dispatch_time,$target_time,$validity_time,$plant,$chillplant,$tanker_type,$driver_name,$driver_mobile,$account_id,$date,$DbConnection)
{
	$Query="INSERT INTO `invoice_mdrm`(lorry_no,transporter_account_id,email,mobile,qty_kg,fat_percentage,snf_percentage,fat_kg,snf_kg,milk_age,dispatch_time,target_time,validity_time,plant,chilling_plant,tanker_type,driver_name,driver_mobile,parent_account_id,create_id,create_date,invoice_status,status) VALUES('".
			"$lorry_no','$transporter','$email','$mobile',".
			"'$qty_kg','$fat_per','$snf_per','$fat_kg','$snf_kg','$milk_age','$dispatch_time','$target_time','$validity_time','$plant','$chillplant','$tanker_type','$driver_name','$driver_mobile','$account_id','$account_id','$date',5,1)";  
			$Result=mysql_query($Query,$DbConnection);
			return $Result;
}
function insertInvoiceMdrmVehicle($lorry_no,$vehicle_no,$transporter,$email,$mobile,$qty_kg,$fat_per,$snf_per,$fat_kg,$snf_kg,$milk_age,$dock_no,$dispatch_time,$target_time,$validity_time,$plant,$chillplant,$tanker_type,$driver_name,$driver_mobile,$account_id,$date,$DbConnection)
{
 $Query="INSERT INTO `invoice_mdrm`(lorry_no,vehicle_no,transporter_account_id,email,mobile,qty_kg,fat_percentage,snf_percentage,fat_kg,snf_kg,milk_age,docket_no,dispatch_time,target_time,validity_time,plant,chilling_plant,tanker_type,driver_name,driver_mobile,parent_account_id,create_id,create_date,invoice_status,status) VALUES('".
		"$lorry_no','$vehicle_no','$transporter','$email','$mobile',".
		"'$qty_kg','$fat_per','$snf_per','$fat_kg','$snf_kg','$milk_age','$dock_no','$dispatch_time','$target_time','$validity_time','$plant','$chillplant','$tanker_type','$driver_name','$driver_mobile','$account_id','$account_id','$date',1,1)";  
		$Result=mysql_query($Query,$DbConnection);
		return $Result;
}
function updateInvoiceMdrmVehicle($lorry_no,$vehicle_no,$transporter,$dock_no,$email,$mobile,$qty_kg,$fat_per,$snf_per,$fat_kg,$snf_kg,$milk_age,$dispatch_time,$target_time,$validity_time,$plant,$chillplant,$tanker_type,$driver_name,$driver_mobile,$account_id,$date,$sno_id1,$DbConnection)
{
	$QueryUpdate="UPDATE `invoice_mdrm` SET lorry_no= '$lorry_no',vehicle_no='$vehicle_no',transporter_account_id='$transporter',docket_no='$dock_no',email='$email',mobile='$mobile',qty_kg='$qty_kg',fat_percentage='$fat_per',snf_percentage='$snf_per',
				fat_kg = '$fat_kg',snf_kg='$snf_kg',milk_age='$milk_age',dispatch_time='$dispatch_time',target_time='$target_time',validity_time='$validity_time',plant='$plant',chilling_plant='$chillplant',
				tanker_type='$tanker_type',driver_name='$driver_name',driver_mobile='$driver_mobile',parent_account_id='$account_id',edit_id='$account_id',edit_date='$date',invoice_status=1,status=1 WHERE sno='$sno_id1' ";  
				$ResultUpdate=mysql_query($QueryUpdate,$DbConnection);
				return $ResultUpdate;
}
function updateInvoiceMdrmAccept($plant_serials,$account_id,$date,$unload_estimatetime_serials,$unload_estimatedatetime_serials,$fat_per_ft_serials,$snf_per_ft_serials,$testing_status_serials,$qty_ct_serials,$temp_ct_serials,$acidity_ct_serials,$mbrt_min_ct_serials,$mbrt_br_ct_serials,$mbrt_rm_ct_serials,$protien_per_ct_serials,$sodium_ct_serials,$fat_per_rt_serials,$snf_per_rt_serials,$adultration_ct_serials,$otheradultration_ct_serials,$sno,$DbConnection)
{
  $query_update = "UPDATE invoice_mdrm SET plant='$plant_serials',edit_id='$account_id',edit_date='$date' ,plant_acceptance_time='$date',unload_estimated_time='$unload_estimatetime_serials',unload_estimated_datetime='$unload_estimatedatetime_serials',
					fat_per_ft='$fat_per_ft_serials',snf_per_ft='$snf_per_ft_serials',testing_status='$testing_status_serials',qty_ct='$qty_ct_serials',
					temp_ct='$temp_ct_serials',acidity_ct='$acidity_ct_serials',mbrt_min_ct='$mbrt_min_ct_serials',mbrt_br_ct='$mbrt_br_ct_serials',
					mbrt_rm_ct='$mbrt_rm_ct_serials',protien_per_ct='$protien_per_ct_serials',sodium_ct='$sodium_ct_serials',fat_per_rt='$fat_per_rt_serials',
					snf_per_rt='$snf_per_rt_serials' , adultration_ct='$adultration_ct_serials' , otheradultration_ct='$otheradultration_ct_serials' ,edit_date='$date' WHERE sno='$sno'";
					$result_update = mysql_query($query_update,$DbConnection);
                    return $result_update;					
}
function updateInvoiceMdrmNoAccept($plant_serials,$account_id,$date,$unload_estimatetime_serials,$unload_estimatedatetime_serials,$fat_per_ft_serials,$snf_per_ft_serials,$testing_status_serials,$qty_ct_serials,$temp_ct_serials,$acidity_ct_serials,$mbrt_min_ct_serials,$mbrt_br_ct_serials,$mbrt_rm_ct_serials,$protien_per_ct_serials,$sodium_ct_serials,$fat_per_rt_serials,$snf_per_rt_serials,$adultration_ct_serials,$otheradultration_ct_serials,$sno,$DbConnection)
{
  $query_update = "UPDATE invoice_mdrm SET plant='$plant_serials',edit_id='$account_id',edit_date='$date' ,unload_estimated_time='$unload_estimatetime_serials',unload_estimated_datetime='$unload_estimatedatetime_serials',
					fat_per_ft='$fat_per_ft_serials',snf_per_ft='$snf_per_ft_serials',testing_status='$testing_status_serials',qty_ct='$qty_ct_serials',
					temp_ct='$temp_ct_serials',acidity_ct='$acidity_ct_serials',mbrt_min_ct='$mbrt_min_ct_serials',mbrt_br_ct='$mbrt_br_ct_serials',
					mbrt_rm_ct='$mbrt_rm_ct_serials',protien_per_ct='$protien_per_ct_serials',sodium_ct='$sodium_ct_serials',fat_per_rt='$fat_per_rt_serials',
					snf_per_rt='$snf_per_rt_serials' , adultration_ct='$adultration_ct_serials' , otheradultration_ct='$otheradultration_ct_serials' ,edit_date='$date' WHERE sno='$sno'";
					$result_update = mysql_query($query_update,$DbConnection);
                    return $result_update;					
}
function updateInvoiceMdrmAcceptUnload($plant_serials,$account_id,$date,$unload_estimatetime_serials,$unload_estimatedatetime_serials,$unload_accepttime_serials,$fat_per_ft_serials,$snf_per_ft_serials,$testing_status_serials,$qty_ct_serials,$temp_ct_serials,$acidity_ct_serials,$mbrt_min_ct_serials,$mbrt_br_ct_serials,$mbrt_rm_ct_serials,$protien_per_ct_serials,$sodium_ct_serials,$fat_per_rt_serials,$snf_per_rt_serials,$adultration_ct_serials,$otheradultration_ct_serials,$sno,$DbConnection)
{
  $query_update = "UPDATE invoice_mdrm SET plant='$plant_serials',edit_id='$account_id',edit_date='$date' ,plant_acceptance_time='$date',unload_estimated_time='$unload_estimatetime_serials',unload_estimated_datetime='$unload_estimatedatetime_serials',unload_accept_time='$unload_accepttime_serials' ,
				fat_per_ft='$fat_per_ft_serials',snf_per_ft='$snf_per_ft_serials',testing_status='$testing_status_serials',qty_ct='$qty_ct_serials',
				temp_ct='$temp_ct_serials',acidity_ct='$acidity_ct_serials',mbrt_min_ct='$mbrt_min_ct_serials',mbrt_br_ct='$mbrt_br_ct_serials',
				mbrt_rm_ct='$mbrt_rm_ct_serials',protien_per_ct='$protien_per_ct_serials',sodium_ct='$sodium_ct_serials,fat_per_rt='$fat_per_rt_serials',
				snf_per_rt='$snf_per_rt_serials',adultration_ct='$adultration_ct_serials' , otheradultration_ct='$otheradultration_ct_serials' ,edit_date='$date' WHERE sno='$sno'";
				$result_update = mysql_query($query_update,$DbConnection);
                return $result_update;
}
function updateInvoiceMdrmNoAcceptUnload($plant_serials,$account_id,$date,$unload_estimatetime_serials,$unload_estimatedatetime_serials,$unload_accepttime_serials,$fat_per_ft_serials,$snf_per_ft_serials,$testing_status_serials,$qty_ct_serials,$temp_ct_serials,$acidity_ct_serials,$mbrt_min_ct_serials,$mbrt_br_ct_serials,$mbrt_rm_ct_serials,$protien_per_ct_serials,$sodium_ct_serials,$fat_per_rt_serials,$snf_per_rt_serials,$adultration_ct_serials,$otheradultration_ct_serials,$sno,$DbConnection)
{
  $query_update = "UPDATE invoice_mdrm SET plant='$plant_serials',edit_id='$account_id',edit_date='$date' ,unload_estimated_time='$unload_estimatetime_serials',unload_estimated_datetime='$unload_estimatedatetime_serials',unload_accept_time='$unload_accepttime_serials' ,
				fat_per_ft='$fat_per_ft_serials',snf_per_ft='$snf_per_ft_serials',testing_status='$testing_status_serials',qty_ct='$qty_ct_serials',
				temp_ct='$temp_ct_serials',acidity_ct='$acidity_ct_serials',mbrt_min_ct='$mbrt_min_ct_serials',mbrt_br_ct='$mbrt_br_ct_serials',
				mbrt_rm_ct='$mbrt_rm_ct_serials',protien_per_ct='$protien_per_ct_serials',sodium_ct='$sodium_ct_serials,fat_per_rt='$fat_per_rt_serials',
				snf_per_rt='$snf_per_rt_serials',adultration_ct='$adultration_ct_serials' , otheradultration_ct='$otheradultration_ct_serials' ,edit_date='$date' WHERE sno='$sno'";
				$result_update = mysql_query($query_update,$DbConnection);
                return $result_update;
}
function updateInvoiceMdrmTest($testing_status_serials,$qty_ct_serials,$temp_ct_serials,$acidity_ct_serials,$mbrt_min_ct_serials,$mbrt_br_ct_serials,$mbrt_rm_ct_serials,$protien_per_ct_serials,$sodium_ct_serials,$fat_per_rt_serials,$snf_per_rt_serials,$adultration_ct_serials,$otheradultration_ct_serials,$date,$sno,$DbConnection)
{
  $query_update = "UPDATE invoice_mdrm SET testing_status='$testing_status_serials',qty_ct='$qty_ct_serials',
				temp_ct='$temp_ct_serials',acidity_ct='$acidity_ct_serials',mbrt_min_ct='$mbrt_min_ct_serials',mbrt_br_ct='$mbrt_br_ct_serials',
				mbrt_rm_ct='$mbrt_rm_ct_serials',protien_per_ct='$protien_per_ct_serials',sodium_ct='$sodium_ct_serials',
				fat_per_rt='$fat_per_rt_serials',snf_per_rt='$snf_per_rt_serials' ,adultration_ct='$adultration_ct_serials' , otheradultration_ct='$otheradultration_ct_serials' ,edit_date='$date' WHERE sno='$sno'";
				$result_update = mysql_query($query_update,$DbConnection);
				return $result_update;
}
function updateInvoiceMdrmNext($plant_serials,$account_id,$date,$sno,$DbConnection)
{
	 $query_update_plant = "UPDATE invoice_mdrm SET plant='$plant_serials',edit_id='$account_id',edit_date='$date'  WHERE sno='$sno'";
	 $result_update_plant = mysql_query($query_update_plant,$DbConnection);	
	 return $result_update_plant;
}
function updateInvoiceMdrmClose($account_id,$date,$closetime,$sno,$DbConnection)
{
	$query_update = "UPDATE invoice_mdrm SET invoice_status=2,edit_id='$account_id',edit_date='$date',system_time='$date',close_time='$closetime',close_type='m'  WHERE sno='$sno'";
	//echo $query_update;		
	$result_update = mysql_query($query_update,$DbConnection);	
	return $result_update; 
}
function deleteInvoiceMdrm($account_id,$date,$sno,$DbConnection)
{
	$query_update = "UPDATE invoice_mdrm SET invoice_status=0,edit_id='$account_id',edit_date='$date' ,system_time='$date' WHERE sno='$sno'";
	$result_update = mysql_query($query_update,$DbConnection);	
}
/*******************************************************************************************************/
/******************************************Mile Stone Assignment***************************************/
 function getMaxCountMilestoneAssign($DbConnection)
 {
	$query ="select Max(serial)+1 as seiral_no from milestone_assignment";    
	$result=mysql_query($query,$DbConnection);
	$row=mysql_fetch_object($result);
	$max_no= $row->seiral_no;
	return $max_no;
 }
 function insertMilestoneAssign($max_no,$local_group_id1,$ms_name,$ms_type,$points1,$account_id,$date,$DbConnection)
 {
	$query="INSERT INTO milestone_assignment (milestone_id,group_id, milestone_name, milestone_type, coordinates, create_id, create_date, status)".
	" VALUES($max_no,'$local_group_id1','$ms_name','$ms_type','$points1','$account_id','$date',1)";
	$result = mysql_query($query, $DbConnection);
	return $result;
 }
 function editMilestoneAssign($new_ms_name,$new_ms_type,$new_points,$ms_id,$DbConnection)
 {
	$query = "UPDATE milestone_assignment SET milestone_name='$new_ms_name',milestone_type='$new_ms_type',coordinates='$new_points' WHERE milestone_id='$ms_id'";
	$result = mysql_query($query, $DbConnection);
	return $result;
 }
 function deleteMilestoneAssign($ms_id,$DbConnection)
 {
	$query = "UPDATE milestone_assignment SET status=0 WHERE milestone_id='$ms_id'";	 
	$result = mysql_query($query, $DbConnection); 
    return $result;	
 }
/******************************************************************************************************/
/*****************************************Plant User Assignment***************************************/
 function getNumRowPlanUserAssingment($plant_id_main,$account_id_substation,$DbConnection)
 {
	$query ="SELECT	* FROM plant_user_assignment USE INDEX(pcno_accid_status) WHERE plant_customer_no='$plant_id_main' AND account_id='$account_id_substation' AND status=1";
	//echo "query=".$query."<br>";
	$result = mysql_query($query, $DbConnection);
	$numrows = mysql_num_rows($result);	
	return $numrows;
 }
 function insertPlantUserAssingment($plant_id_main,$account_id_substation,$account_id,$date,$DbConnection)
 {
	$query = "INSERT INTO plant_user_assignment (plant_customer_no,account_id,status,create_id,create_date) VALUES('$plant_id_main',$account_id_substation,1,$account_id,'$date')";
	//echo "query_insert=".$query."<br>";
	$result = mysql_query($query, $DbConnection);
	return $result;
 }
 function updatePlantUserAssingmnet($date,$plant_id_main,$account_id_substation,$DbConnection)
 {
	$query = "UPDATE plant_user_assignment SET status=0,edit_date='$date' WHERE sno='$plant_id_main' AND account_id='$account_id_substation'";			
	//echo $query;
	$result = mysql_query($query, $DbConnection);
	return $result;
 }
/*****************************************************************************************************/
/***************************************PolyLine**************************************************************/
function getMaxSerialPolyline($DbConnection)
{
	$query ="select Max(sno)+1 as seiral_no from polyline";  ///// for auto increament of geo_id ///////////   
	$result=mysql_query($query,$DbConnection);
	$row=mysql_fetch_object($result);
	$max_no= $row->seiral_no;
	return $max_no; 
}
function getMaxCountPolylineAssign($polyline_id1,$DbConnection)
{
	$queryChk="SELECT count(*) as cnt FROM polyline_assignment USE INDEX (plass_polylineid_status) WHERE polyline_id='$polyline_id1' AND status='1'";
	$resultChk=mysql_query($queryChk,$DbConnection);
	$rowChk=mysql_fetch_object($resultChk);
	$cnt= $rowChk->cnt;
	return $cnt;
	
}
function insertPolyline($account_size,$local_account_ids,$max_no,$polyline_name1,$polyline_coord1,$account_id,$date,$DbConnection)
{
	$query_string1="INSERT INTO polyline(user_account_id,polyline_id,polyline_name,polyline_coord,status,create_id,create_date) VALUES";
	for($i=0;$i<$account_size;$i++)
	{		
		if($i==$account_size-1)
		{
			$query_string2.="('$local_account_ids[$i]','$max_no','$polyline_name1','$polyline_coord1','1','$account_id','$date');";
		}
		else
		{
			$query_string2.="('$local_account_ids[$i]','$max_no','$polyline_name1','$polyline_coord1','1','$account_id','$date'),";
		}
	}
	$query=$query_string1.$query_string2; 	 
	$result=mysql_query($query,$DbConnection); 
	return $result;
}
function editPolyline($polyline_name1,$polyline_coord1,$account_id,$date,$polyline_id1)
{
	$query="UPDATE polyline SET polyline_name='$polyline_name1',polyline_coord='$polyline_coord1',edit_id='$account_id',edit_date='$date' WHERE polyline_id='$polyline_id1'";
	//echo "query=".$query;
	$result=mysql_query($query,$DbConnection); 
	return $result;
}
function updatePolyline($account_id,$date,$polyline_id1,$DbConnection)
{
	$query="UPDATE polyline SET edit_id='$account_id',edit_date='$date',status='0' WHERE polyline_id='$polyline_id1' AND status='1'"; 
	$result=mysql_query($query,$DbConnection);
    return $result;	
}
function insertPolylineAssign($vehicle_size,$local_polyline_id,$local_vehicle_ids,$account_id,$date,$DbConnection)
{
	$query_string1="INSERT INTO polyline_assignment(polyline_id,vehicle_id,create_id,create_date,status) VALUES";
	for($i=0;$i<$vehicle_size;$i++)
	{
		//echo "local_geofenc_id=".$local_geofenc_id[$i]."<br>";
		if($i==$vehicle_size-1)
		{
			$query_string2.="($local_polyline_id,$local_vehicle_ids[$i],$account_id,'$date',1);";
		}
		else
		{
			$query_string2.="($local_polyline_id,$local_vehicle_ids[$i],$account_id,'$date',1),";
		}
	}
	$query=$query_string1.$query_string2;	     
	$result=mysql_query($query,$DbConnection); 
	return $result;
}
function deletePolylineAssign($account_id,$date,$local_vehicle_ids,$DbConnection)
{
	$query="UPDATE polyline_assignment SET status=0,edit_id='$account_id',edit_date='$date' WHERE vehicle_id='$local_vehicle_ids' AND status=1";
	$result=mysql_query($query,$DbConnection);
	return $result;
}
/*************************************************************************************************************/
/******************************************Schedule Location**************************************************/
function getMaxSerialScheduleLocation($DbConnection)
{
	$query ="select Max(sno)+1 as seiral_no from schedule_location";  ///// for auto increament of landmark_id ///////////   
	$result=mysql_query($query,$DbConnection);
	$row=mysql_fetch_object($result);
	$max_no= $row->seiral_no;
	return $max_no;
}

function updateScheduleLocation($location_name1,$geo_point1,$account_id,$date,$location_id1,$DbConnection)
{
	$query="UPDATE schedule_location SET location_name='$location_name1',geo_point='$geo_point1',edit_id='$account_id',edit_date='$date' WHERE location_id='$location_id1'";
	//echo "query=".$query."<br>";
	$result=mysql_query($query,$DbConnection); 
	return $result;
}
function insertScheduleLocation($account_size,$local_account_ids,$max_no,$location_name1,$geo_point1,$status,$account_id,$date,$DbConnection)
{
	$query_string1="INSERT INTO schedule_location(account_id,location_id,location_name,geo_point,status,create_id,create_date) VALUES";
	for($i=0;$i<$account_size;$i++)
	{
		//echo "accout_id=".$local_account_ids[$i]."<br>";
		if($i==$account_size-1)
		{
			$query_string2.="('$local_account_ids[$i]','$max_no','$location_name1','$geo_point1',$status,'$account_id','$date');";
		}
		else
		{
			$query_string2.="('$local_account_ids[$i]','$max_no','$location_name1','$geo_point1',$status,'$account_id','$date'),";
		}
	}
	$query=$query_string1.$query_string2; 	 
	$result=mysql_query($query,$DbConnection);
	return $result;
}
function editScheduleLocation($location_name1,$geo_point1,$account_id,$date,$location_id1,$DbConnection)
{
	$query="UPDATE schedule_location SET location_name='$location_name1',geo_point='$geo_point1',edit_id='$account_id',edit_date='$date' WHERE location_id='$location_id1'";
	//echo "query=".$query."<br>";
	$result=mysql_query($query,$DbConnection);
	return $result;
}

function getDataCheckInScheduleAssignment3($vehicle_id,$date_from,$date_to,$DbConnection)
{
   $Query="SELECT * FROM schedule_assignment WHERE by_day='1' AND vehicle_id='$vehicle_id' AND (((date_from<='$date_from') AND (date_to>=".
	"'$date_from')) OR ((date_from<='$date_to') AND (date_to>='$date_to'))) AND status=1";
	$Result=mysql_query($Query,$DbConnection);
	$NumRows=mysql_num_rows($Result);
	return $numRows;	
}

function getDataCheckInScheduleAssignment($vehicle_id,$date_from,$date_to,$DbConnection)
{
   $Query="SELECT * FROM schedule_assignment WHERE by_day='0' AND vehicle_id='$vehicle_id' AND status=1 AND (((date_from<='$date_from') AND (date_to>='$date_from'))".
	" || ((date_from<='$date_to') AND (date_to>='$date_to'))) AND status=1";
	//echo "query=".$Query."<br>";
	$Result=mysql_query($Query,$DbConnection);
	$NumRows=mysql_num_rows($Result);
	return $numRows;	
}

function getDataCheckInScheduleAssignment2($vehicle_id,$date_from,$date_to,$DbConnection)
{
   $Query="SELECT * FROM schedule_assignment WHERE vehicle_id='$vehicle_id' AND (((date_from<='$date_from') AND (date_to>='$date_from')) OR ".
	"((date_from<='$date_to') AND (date_to>='$date_to'))) AND status=1";
	$Result=mysql_query($Query,$DbConnection);
	$NumRows=mysql_num_rows($Result);
	return $numRows;	
}

function deleteScheduleLocation($account_id,$date,$upstatus,$location_id1,$constatus,$DbConnection)
{
	$query="UPDATE schedule_location SET edit_id='$account_id',edit_date='$date',status=$upstatus WHERE location_id='$location_id1' AND status=$constatus"; 
	$result=mysql_query($query,$DbConnection); 
	return $result;
}

function getNumRowScheduleLocation($day_by,$vehicle_id,$date_from,$date_to,$DbConnection)
{
 $Query="SELECT * FROM schedule_assignment USE INDEX(scass_vid_status) WHERE by_day='$day_by' AND vehicle_id='$vehicle_id' AND status=1 AND (((date_from<='$date_from') AND (date_to>='$date_from'))".
		" || ((date_from<='$date_to') AND (date_to>='$date_to'))) AND status=1";
		//echo "query=".$Query."<br>";
		$Result=mysql_query($Query,$DbConnection);
		$NumRows=mysql_num_rows($Result);
		return $NumRows;
}
function getNumRowScheduleLocationNext($vehicle_id,$date_from,$date_to,$DbConnection)
{
  $Query="SELECT * FROM schedule_assignment USE INDEX(scass_vid_status) WHERE vehicle_id='$vehicle_id' AND (((date_from<='$date_from') AND (date_to>='$date_from')) OR ".
		"((date_from<='$date_to') AND (date_to>='$date_to'))) AND status=1";
		$Result=mysql_query($Query,$DbConnection);
		$NumRows=mysql_num_rows($Result);
		return $NumRows;
}
function getDayScheduleLocation($vehicle_id,$date_from,$date_to)
{
  $Query="SELECT * FROM schedule_assignment USE INDEX(scass_vid_status) WHERE by_day='1' AND vehicle_id='$vehicle_id' AND (((date_from<='$date_from') AND (date_to>=".
		"'$date_from')) OR ((date_from<='$date_to') AND (date_to>='$date_to'))) AND status=1";
		$Result=mysql_query($Query,$DbConnection);
		$NumRows=mysql_num_rows($Result);
		if($NumRows>0)
		{
			while($Row=mysql_fetch_object($Result))
			{
				$days[]=$Row->day;
			}				
		}
	    return $days;
}
function insertScheduleAssignment($vehicle_id,$min_max_halt_locations_1,$base_station_id,$date_from,$date_to,$by_day,$day_str_1,$min_operation_time,$max_operation_time,$min_halt_time,$max_halt_time,$minimum_distance,$maximum_distance,$intermediate_time_1,$nonpoi_halt_time,$account_id,$date,$status,$DbConnection)
{
$Query="INSERT INTO schedule_assignment(vehicle_id,location_id,base_station_id,date_from,date_to,by_day,day,min_operation_time,max_operation_time,".
	   "min_halt_time,max_halt_time,min_distance_travelled,max_distance_travelled,Intermediate_halt_time,nonpoi_halt_time,create_id,create_date,status) VALUES(".
	   "'$vehicle_id','$min_max_halt_locations_1','$base_station_id','$date_from','$date_to','$by_day','$day_str_1','$min_operation_time','$max_operation_time',".
	   "'$min_halt_time','$max_halt_time','$minimum_distance','$maximum_distance','$intermediate_time_1','$nonpoi_halt_time','$account_id','$date',$status)";
$Result=mysql_query($Query,$DbConnection);
return $Result;
}
/*************************************************************************************************************/
/*********************************************Schedule Location UPL**************************************************/
function getMaxSerialScheduleLocationUpl($DbConnection)
{
	$query ="select Max(sno)+1 as seiral_no from schedule_location_upl";  ///// for auto increament of landmark_id ///////////   
	$result=mysql_query($query,$DbConnection);
	$row=mysql_fetch_object($result);
	$max_no= $row->seiral_no;
	return $max_no;
}
function insertScheduleLocationUpl($account_size,$local_account_ids,$max_no,$location_name1,$geo_point1,$account_id,$date,$DbConnection)
{
	$query_string1="INSERT INTO schedule_location_upl(account_id,location_id,location_name,geo_point,status,create_id,create_date) VALUES";
	for($i=0;$i<$account_size;$i++)
	{
		//echo "accout_id=".$local_account_ids[$i]."<br>";
		if($i==$account_size-1)
		{
			$query_string2.="('$local_account_ids[$i]','$max_no','$location_name1','$geo_point1','1','$account_id','$date');";
		}
		else
		{
			$query_string2.="('$local_account_ids[$i]','$max_no','$location_name1','$geo_point1','1','$account_id','$date'),";
		}
	}
	$query=$query_string1.$query_string2; 	 
	$result=mysql_query($query,$DbConnection);
	return $result;
}
function editScheduleLocationUpl($location_name1,$geo_point1,$account_id,$date,$location_id1,$DbConnection)
{
	$query="UPDATE schedule_location_upl SET location_name='$location_name1',geo_point='$geo_point1',edit_id='$account_id',edit_date='$date' WHERE location_id='$location_id1'";
	//echo "query=".$query."<br>";
	$result=mysql_query($query,$DbConnection);
	return $result;
}
function deleteScheduleLocationUpl($account_id,$date,$location_id1,$DbConnection)
{
	$query="UPDATE schedule_location_upl SET edit_id='$account_id',edit_date='$date',status='0' WHERE location_id='$location_id1' AND status='1'"; 
	$result=mysql_query($query,$DbConnection); 
	return $result;
}
function getNumRowScheduleLocationUpl($day_by,$vehicle_id,$date_from,$date_to,$DbConnection)
{
 $Query="SELECT * FROM schedule_assignment_upl USE INDEX(scassupl_vid_status) WHERE by_day='$day_by' AND vehicle_id='$vehicle_id' AND status=1 AND (((date_from<='$date_from') AND (date_to>='$date_from'))".
		" || ((date_from<='$date_to') AND (date_to>='$date_to'))) AND status=1";
		//echo "query=".$Query."<br>";
		$Result=mysql_query($Query,$DbConnection);
		$NumRows=mysql_num_rows($Result);
		return $NumRows;
}
function getNumRowScheduleLocationNext_upl($vehicle_id,$date_from,$date_to,$DbConnection)
{
  $Query="SELECT * FROM schedule_assignment_upl USE INDEX(scassupl_vid_status) WHERE vehicle_id='$vehicle_id' AND (((date_from<='$date_from') AND (date_to>='$date_from')) OR ".
		"((date_from<='$date_to') AND (date_to>='$date_to'))) AND status=1";
		$Result=mysql_query($Query,$DbConnection);
		$NumRows=mysql_num_rows($Result);
		return $NumRows;
}
function getDayScheduleLocationUpl($vehicle_id,$date_from,$date_to)
{
  $Query="SELECT * FROM schedule_assignment_upl USE INDEX(scassupl_vid_status) WHERE by_day='1' AND vehicle_id='$vehicle_id' AND (((date_from<='$date_from') AND (date_to>=".
		"'$date_from')) OR ((date_from<='$date_to') AND (date_to>='$date_to'))) AND status=1";
		$Result=mysql_query($Query,$DbConnection);
		$NumRows=mysql_num_rows($Result);
		if($NumRows>0)
		{
			while($Row=mysql_fetch_object($Result))
			{
				$days[]=$Row->day;
			}				
		}
	    return $days;
}
function insertScheduleAssignmentUpl($vehicle_id,$min_max_halt_locations_1,$base_station_id,$date_from,$date_to,$by_day,$day_str_1,$min_operation_time,$max_operation_time,$min_halt_time,$max_halt_time,$minimum_distance,$maximum_distance,$intermediate_time_1,$nonpoi_halt_time,$account_id,$date,$DbConnection)
{
 $Query="INSERT INTO schedule_assignment_upl(vehicle_id,location_id,base_station_id,date_from,date_to,by_day,day,min_operation_time,max_operation_time,".
	   "min_halt_time,max_halt_time,min_distance_travelled,max_distance_travelled,Intermediate_halt_time,nonpoi_halt_time,create_id,create_date,status) VALUES(".
	   "'$vehicle_id','$min_max_halt_locations_1','$base_station_id','$date_from','$date_to','$by_day','$day_str_1','$min_operation_time','$max_operation_time',".
	   "'$min_halt_time','$max_halt_time','$minimum_distance','$maximum_distance','$intermediate_time_1','$nonpoi_halt_time','$account_id','$date','1')";
	   $Result=mysql_query($Query,$DbConnection);
	   return $Result;
}
/*******************************************************************************************************************/
/*********************************************Shift*****************************************************************/
function getMaxSerialShift($DbConnection)
{
 $query2 ="select Max(sno)+1 as seiral_no from shift";   
		$result2=mysql_query($query2,$DbConnection);
		$row1=mysql_fetch_object($result2);
		$max_no= $row1->seiral_no;
		return $max_no;
}
function insertShift($account_size,$local_account_ids,$max_no,$shift_name1,$shift_starttime1,$shift_stoptime1,$account_id,$date,$DbConnection)
{
	$query_string1="INSERT INTO shift(group_id,shift_id,shift_name,shift_starttime,shift_stoptime,status,create_id,create_date) VALUES";
	for($i=0;$i<$account_size;$i++)
	{
		if($i==$account_size-1)
		{
			$query_string2.="('$local_account_ids[$i]','$max_no','$shift_name1','$shift_starttime1','$shift_stoptime1','1','$account_id','$date');";
		}
		else
		{
			$query_string2.="('$local_account_ids[$i]','$max_no','$shift_name1','$shift_starttime1','$shift_stoptime1','1','$account_id','$date'),";
		}
	}
	$query=$query_string1.$query_string2; 
	//echo $query;
	if($DEBUG ==1 )print_query($query);     
	$result=mysql_query($query,$DbConnection); 
    return $result;	
}
function editShift($shift_name1,$shift_starttime1,$shift_stoptime1,$account_id,$date,$shift_id1,$DbConnection)
{
	$query="UPDATE shift SET shift_name='$shift_name1',shift_starttime='$shift_starttime1',shift_stoptime='$shift_stoptime1',edit_id='$account_id',edit_date='$date' WHERE shift_id='$shift_id1'";
	$result=mysql_query($query,$DbConnection); 
	return $result;
}
function deleteShift($account_id,$date,$shift_id1,$DbConnection)
{
  $query="UPDATE shift SET edit_id='$account_id',edit_date='$date',status='0' WHERE shift_id='$shift_id1' AND status='1'"; 
  $result=mysql_query($query,$DbConnection);
  return $result;  
}
function insertRouteAssign($vehicle_size,$local_route_id,$local_vehicle_ids,$account_id,$date,$DbConnection)
{
	$query_string1="INSERT INTO route_assignment(route_id,vehicle_id,create_id,create_date,status) VALUES";
	for($i=0;$i<$vehicle_size;$i++)
	{
		if($i==$vehicle_size-1)
		{
			$query_string2.="($local_route_id,$local_vehicle_ids[$i],$account_id,'$date',1);";
		}
		else
		{
			$query_string2.="($local_route_id,$local_vehicle_ids[$i],$account_id,'$date',1),";
		}
	}
	$query=$query_string1.$query_string2; 
	if($DEBUG ==1 )print_query($query);     
	$result=mysql_query($query,$DbConnection); 
    return $result;	
}
function deleteRouteAssign($vehicle_size,$account_id,$date,$local_vehicle_ids,$DbConnection)
{
  for($i=0;$i<$vehicle_size;$i++)
	{	
		$query="UPDATE route_assignment SET status=0,edit_id='$account_id',edit_date='$date' WHERE vehicle_id='$local_vehicle_ids[$i]' AND status=1";
		$result=mysql_query($query,$DbConnection); 
		
	}
  return $result;
}
/*******************************************************************************************************************/
/******************************************Station******************************************************************/
 function getMaxSerialStation($DbConnection)
 {
	$query ="select Max(sno)+1 as serial_no from station";  ///// for auto increament of geo_id ///////////   
	$result=mysql_query($query,$DbConnection);
	$row=mysql_fetch_object($result);
	$max_no= $row->serial_no;
	return $max_no; 
 }
 function insertStation($account_size,$local_account_ids,$max_no,$station_no1,$station_name1,$station_coord1,$type1,$account_id,$status,$date,$DbConnection)
 {
	$query_string1="INSERT INTO station(user_account_id,station_id,customer_no,station_name,station_coord,type,status,create_id,create_date) VALUES";
	for($i=0;$i<$account_size;$i++)
	{
		//echo "accout_id=".$local_account_ids[$i]."<br>";
		if($i==$account_size-1)
		{
			$query_string2.="('$local_account_ids[$i]',$max_no,'$station_no1','$station_name1','$station_coord1','$type1',$status,'$account_id','$date');";
		}
		else
		{
			$query_string2.="('$local_account_ids[$i]',$max_no,'$station_no1','$station_name1','$station_coord1','$type1',$status,'$account_id','$date'),";
		}
	}
	$query=$query_string1.$query_string2; 
	//echo "query=".$query;
	
	$result=mysql_query($query,$DbConnection); 
	return $result;
 }
 
 function updateStation($geo_name1,$geo_coord1,$customer_no1,$distance_variable1,$account_id,$date,$geo_id1,$DbConnection)
{
	$query="UPDATE station SET station_name='$geo_name1',station_coord='$geo_coord1',customer_no='$customer_no1',distance_variable='$distance_variable1',edit_id='$account_id',edit_date='$date' WHERE station_id='$geo_id1'";
	//echo "query=".$query;
	$result=mysql_query($query,$DbConnection);
	return $result;		
}
 function editStation($geo_name1,$geo_coord1,$customer_no1,$distance_variable1,$account_id,$date,$geo_id1,$DbConnection)
 {
	$query="UPDATE station SET station_name='$geo_name1',station_coord='$geo_coord1',customer_no='$customer_no1',distance_variable='$distance_variable1',edit_id='$account_id',edit_date='$date' WHERE station_id='$geo_id1'";
	//echo "query=".$query;
	$result=mysql_query($query,$DbConnection); 
 }
 function editDistVarStation($station_size,$distance_variable1,$date,$local_station_ids,$DbConnection)
 {
	$query_string1="UPDATE station SET distance_variable='$distance_variable1',edit_date='$date' WHERE station_id IN(";
	$update_str = "";
	for($i=0;$i<$station_size;$i++)
	{
		//echo "local_geofenc_id=".$local_geofenc_id[$i]."<br>";
		if($i==0)
		{
			$update_str= $update_str.$local_station_ids[$i];
		}
		else
		{
			$update_str= $update_str.",'".$local_station_ids[$i]."'";        
		}
	}
	$query=$query_string1.$update_str.")"; 
			
	$result=mysql_query($query,$DbConnection); 
    return $result ;	
 }
 function deleteStation ($station_size,$local_station_ids,$DbConnection)
 {
	$query_string1="UPDATE station SET status=0 WHERE station_id IN(";
	$del_str = "";
	for($i=0;$i<$station_size;$i++)
	{
		//echo "local_geofenc_id=".$local_geofenc_id[$i]."<br>";
		if($i==0)
		{
			$del_str= $del_str.$local_station_ids[$i];
		}
		else
		{
			$del_str= $del_str.",'".$local_station_ids[$i]."'";        
		}
	}
	$query=$query_string1.$del_str.")"; 
	
	//echo $query;		
	$result=mysql_query($query,$DbConnection);
	return $result;
 }
 function insertStationAssign($station_size,$local_station_ids,$local_vehicle_id,$account_id,$date,$DbConnection)
 {
	$query_string1="INSERT INTO station_assignment(station_id,vehicle_id,create_id,create_date,status) VALUES";
	for($i=0;$i<$station_size;$i++)
	{
		//echo "local_geofenc_id=".$local_geofenc_id[$i]."<br>";
		if($i==$station_size-1)
		{
			$query_string2.="($local_station_ids[$i],$local_vehicle_id,$account_id,'$date',1);";
		}
		else
		{
			$query_string2.="($local_station_ids[$i],$local_vehicle_id,$account_id,'$date',1),";
		}
	}
	$query=$query_string1.$query_string2; 
	if($DEBUG ==1 )print_query($query);     
	$result=mysql_query($query,$DbConnection);     
    return $result;	
 }
 function deleteStationAssign($vehicle_size,$local_vehicle_ids,$account_id,$date,$vehicle_id,$station_id,$DbConnection)
 {
	for($i=0;$i<$vehicle_size;$i++)
	{	
		$local_all_ids=explode(":",$local_vehicle_ids[$i]);
		$vehicle_id = $local_all_ids[0];
		$station_id = $local_all_ids[1];

		$query="UPDATE station_assignment SET status=0,edit_id='$account_id',edit_date='$date' WHERE vehicle_id='$vehicle_id' AND station_id='$station_id' AND status=1";
		//echo $query;
		$result=mysql_query($query,$DbConnection); 
		
	}
	return $result;
		
 }
 
/*******************************************************************************************************************************/
/**********************************************File Farmat *********************************************************************/
 function getNumRowFileFormat($account_id_local,$upload_format_id,$DbConnection)
 {
   $QuerySelect="SELECT upload_format_id FROM master_file USE INDEX(mf_accid_upfid_status) WHERE account_id=$account_id_local AND upload_format_id=".
                "'$upload_format_id' AND status=1";
                $ResultSelect=mysql_query($QuerySelect, $DbConnection);
                $NumRows=mysql_num_rows($ResultSelect);
                return $NumRows;
 }
 function insertMasterFile($account_id_local,$upload_format_id,$file_format_names,$no_of_files,$file_names,$file_ids,$remark,$account_id,$date,$DbConnection)
 {
   $Query="INSERT INTO master_file(account_id,upload_format_id,format_name,no_of_files,file_names,file_ids,remark,create_id,".
		   "create_date,status) VALUES ($account_id_local,'$upload_format_id','$file_format_names','$no_of_files','$file_names',".
		   "'$file_ids','$remark','$account_id','$date',1)";
		   $result = mysql_query($Query, $DbConnection);
		   return $result;
 }
 function getNumRowGetReportFile($account_id_local,$upload_format_id,$DbConnection)
 {
  $QuerySelect="SELECT upload_format_id FROM get_report_file USE INDEX(grf_accid_upfid_status) WHERE account_id=$account_id_local AND upload_format_id=".
                "'$upload_format_id' AND status=1";
                //echo "query=".$QuerySelect."<br>";
                $ResultSelect=mysql_query($QuerySelect, $DbConnection);
                $NumRows=getNumRowFileFormat($account_id_local,$upload_format_id,$DbConnection);
                return $NumRows;
 }
 function insertGetReportFile($account_id_local,$upload_format_id,$file_format_names,$no_of_files,$file_names,$file_ids,$remark,$account_id,$date,$DbConnection)
 {
   $Query="INSERT INTO get_report_file(account_id,upload_format_id,format_name,no_of_files,file_names,file_ids,remark,create_id,".
				   "create_date,status) VALUES ($account_id_local,'$upload_format_id','$file_format_names','$no_of_files','$file_names',".
				   "'$file_ids','$remark','$account_id','$date',1)";
				    $result = mysql_query($Query, $DbConnection);
		            return $result;
				   
 }
 function updateMasterFile($file_format_names,$no_of_files,$file_names,$file_ids,$account_id,$date,$upload_format_id,$account_id_local,$DbConnection)
 {
  $QueryUpdate="UPDATE master_file SET format_name='$file_format_names',no_of_files='$no_of_files',file_names='$file_names',".
			   "file_ids='$file_ids',edit_id='$account_id',edit_date='$date' WHERE upload_format_id='$upload_format_id' AND status=1".
			   " AND account_id=$account_id_local";	
			   $ResultUpdate=mysql_query($QueryUpdate, $DbConnection);
			   return $ResultUpdate; 
 }
 function updateGetReportFile($file_format_names,$no_of_files,$file_names,$file_ids,$account_id,$date,$upload_format_id,$account_id_local,$DbConnection)
 {
   $QueryUpdate="UPDATE get_report_file SET format_name='$file_format_names',no_of_files='$no_of_files',file_names='$file_names',".
			   "file_ids='$file_ids',edit_id='$account_id',edit_date='$date' WHERE upload_format_id='$upload_format_id' AND status=1".
			   " AND account_id=$account_id_local";
			   $ResultUpdate=mysql_query($QueryUpdate, $DbConnection);
			   return $ResultUpdate; 
 }
 function deleteMasterFile($upload_format_id,$account_id_local,$DbConnection)
 {
   $QueryUpdate="UPDATE master_file SET status='0' WHERE upload_format_id='$upload_format_id' AND status=1".
				 " AND account_id=$account_id_local";
				 $ResultUpdate=mysql_query($QueryUpdate, $DbConnection);
				 return $ResultUpdate;
 }
 function deleteGetReportFile($upload_format_id,$account_id_local,$DbConnection)
 {
   $QueryUpdate="UPDATE get_report_file SET status='0' WHERE upload_format_id='$upload_format_id' AND status=1".
				 " AND account_id=$account_id_local";
				 $ResultUpdate=mysql_query($QueryUpdate, $DbConnection);
				 return $ResultUpdate;
 }
 
/*******************************************************************************************************************************/
/*************************************Vehicle Sub Station******************************************************************/
 function getNumRowVehicleGroupping ($vehicle_id_main,$account_id_substation,$DbConnection)
 {
   $query ="SELECT * FROM vehicle_grouping USE INDEX(vg_vid_accid_status) WHERE vehicle_id='$vehicle_id_main' AND account_id='$account_id_substation' AND status=1";
			//echo "query=".$query."<br>";
			$result = mysql_query($query, $DbConnection);
			$numrows = mysql_num_rows($result);
			return $numrows; 			
 }
 function insertVehicleGroupingSingle($vehicle_id_main,$account_id_substation,$account_id,$date,$DbConnection)
 {
   $query = "INSERT INTO vehicle_grouping (vehicle_id,account_id,status,create_id,create_date) ".
			"VALUES('$vehicle_id_main',$account_id_substation,1,$account_id,'$date')";
			//echo "query_insert=".$query."<br>";
			$result = mysql_query($query, $DbConnection);
			return $result;
 }
 

 function getNumRowDeviceAssign($device_imeino_local,$account_id_substation,$DbConnection)
 {
   $query = "SELECT * FROM device_assignment USE INDEX(da_dimeino_accid_status) WHERE device_imei_no = $device_imeino_local AND account_id=$account_id_substation AND status=1";
            //echo "query3=".$query."<br>";
            $result = mysql_query($query, $DbConnection);
            $numrows = mysql_num_rows($result);
            return $numrows;
 }

/**************************************************************************************************************************/
/*************************************************Third Party Vehicle Assignment**********************************************/
 function getVehicleIdThirdPartyVehicleAssign($account_id,$post_destination_account1,$DbConnection)
 {
	$third_party_vehicle_id=array();
	$querydesaid="SELECT vehicle_id from third_party_vehicle_assignment USE INDEX(tpvass_admid_tpaccid_status) WHERE admin_account_id='$account_id' AND third_party_account_id='$post_destination_account1' and status=1";	
	$resultdesaid=mysql_query($querydesaid,$DbConnection);
	while($row=mysql_fetch_object($resultdesaid))
	{
		$third_party_vehicle_id[]=$row->vehicle_id;
	}
	return $third_party_vehicle_id; 
 }
 function updateThirdPartyVehicleAssignment($date,$account_id,$account_id,$post_destination_account1,$DbConnection)
 {
	$queryUpdate="UPDATE third_party_vehicle_assignment SET status=0,edit_date='$date',edit_id='$account_id' where admin_account_id='$account_id' and third_party_account_id='$post_destination_account1' and status=1";
	$resultUpdate=mysql_query($queryUpdate,$DbConnection);
	return $resultUpdate;
 }
 function insertThirdPartyVehicleAssignment($tmp_post_vehicle_id1,$account_id,$post_destination_account1,$vid,$date,$account_id,$DbConnection)
 {
   foreach($tmp_post_vehicle_id1 as $vid)
	{
		$queryInsert="INSERT into third_party_vehicle_assignment (admin_account_id,third_party_account_id,vehicle_id,create_date,create_id,status) ".
		"values($account_id,$post_destination_account1,$vid,'$date','$account_id',1)";
		//echo $queryInsert; 
		$resultInsert=mysql_query($queryInsert,$DbConnection);
	}
	return $resultInsert;
 }
/*****************************************************************************************************************************/
/***********************************************Transport Chilling Plant Assignment *************************************************************/
 function getCustomerNoTCPA($account_id,$DbConnection)
 {
    $default_customer_no="";
	$query_default_chillplant = "SELECT customer_no FROM transporter_chilling_plant_assignment USE INDEX(tcpass_accid_status) WHERE account_id='$account_id' AND status=1";
	//echo $query_default_chillplant."<br>";
	$result_default_chillquery = mysql_query($query_default_chillplant,$DbConnection);
	$numrows_default_chillquery  = mysql_num_rows($result_default_chillquery);	
	if($numrows_default_chillquery!=0)
	{
		$row=mysql_fetch_object($result_default_chillquery);
		$default_customer_no = $row->customer_no;
		//echo "RM=".$default_customer_no;
	}
	else{
		$default_customer_no ="";
	}
	return $default_customer_no;
 }
/******************************************************************************************************************************/
/********************************************manage add raw milk usertype approve1*********************************************/
function getVehicleListVehicleGroupingVehicle($parent_admin_id,$DbConnection)
{
	$vehicle_list=array();	
	$query_admin_vehicle="SELECT vehicle_grouping.vehicle_id,vehicle.vehicle_name FROM vehicle_grouping USE INDEX(vg_vid_accid_status),vehicle USE INDEX(v_vehicleid_status) WHERE ".
	"vehicle_grouping.account_id = $parent_admin_id AND vehicle_grouping.status=1 AND vehicle.vehicle_id=vehicle_grouping.vehicle_id AND ".
	" vehicle.status=1";
	//echo $query_admin_vehicle;
	$result_admin_vehicle = mysql_query($query_admin_vehicle,$DbConnection);
	while($row=mysql_fetch_object($result_admin_vehicle))
	{
		//echo $row->customer_no;
		$vehicle_list[]=$row->vehicle_name;		
	}
	return $vehicle_list;
}
/******************************************************************************************************************************/
/**********************************************Manage Avalability*************************************************************/
 function getNumRowDeviceManfInfo($field_value,$DbConnection)
 {
	$query = "SELECT device_imei_no FROM device_manufacturing_info USE INDEX(dmi_device_imei_status) WHERE device_imei_no='$field_value' and status='1'";
	if($DEBUG==1){print_query($query);}
	$result = @mysql_query($query, $DbConnection);
	$row_result=@mysql_num_rows($result);
	return $row_result;
 }
 function getNumRowVehicleByNameAccount($field_value,$local_account_id,$DbConnection)
 {
    $query = "SELECT vehicle_name FROM vehicle USE INDEX (v_vname_accid_status) WHERE vehicle_name='$field_value' AND account_id=$local_account_id AND status='1'";
	//echo "query=".$query;
	if($DEBUG==1){print_query($query);}
	$result = @mysql_query($query, $DbConnection);
	$row_result=@mysql_num_rows($result);
	return $row_result;
 }
 function getNumRowCallibration($field_value,$DbConnection)
 {
	$query = "SELECT calibration_name FROM calibration USE INDEX(clb_clbname_status) WHERE calibration_name='$field_value' and status='1'";
	if($DEBUG==1){print_query($query);}
	$result = @mysql_query($query, $DbConnection);
	$row_result=@mysql_num_rows($result);
	return $row_result;
 }
 function getNumRowConsignment($field_value,$DbConnection)
 {
	$query = "SELECT consignment_code FROM consignment USE INDEX(cns_cnscode_status) WHERE consignment_code='$field_value' and status='1'";
	if($DEBUG==1){print_query($query);}
	$result = @mysql_query($query, $DbConnection);
	$row_result=@mysql_num_rows($result);
	return $row_result;
 }
 function getNumRowVendorType($vendor_group_id,$field_value,$DbConnection)
 {
	$query="SELECT vendor_type_name from vendor_type WHERE group_id='$vendor_group_id' AND vendor_type_name='$field_value' AND status=1";  		
	if($DEBUG==1){print_query($query);}
	$result = @mysql_query($query, $DbConnection);
	$row_result=@mysql_num_rows($result);
	return $row_result;
 }
 function getNumRowVendor($field_value,$DbConnection)
 {
    $query="SELECT login_id from vendor WHERE login_id='$field_value' AND status=1";		
    if($DEBUG==1)
    {
        print_query($query);        
    }
    $result = @mysql_query($query, $DbConnection);
    $row_result=@mysql_num_rows($result);
    return $row_result;	
 }
 function getNumRowLandmark($field_value,$DbConnection)
 {
	$query = "SELECT landmark_id,landmark_name FROM landmark USE INDEX(lnk_lnkname_status) WHERE landmark_name='$field_value' and status='1'";  
	if($DEBUG==1)
        {
            print_query($query);        
        }
	$result = @mysql_query($query, $DbConnection);     	     	                       
	$row_result=@mysql_num_rows($result);
	return $row_result;
 }
 function getNumRowScheduleLocationSingle($field_value,$DbConnection)
 {
	$query = "SELECT location_id,location_name FROM schedule_location USE INDEX(schl_schlname_status) WHERE location_name='$field_value' AND status='1'";  
	//echo "Query=".$query;
	if($DEBUG==1)
        {
            print_query($query);        
        }
	$result = mysql_query($query, $DbConnection);     	     	                       
	$row_result=mysql_num_rows($result);
	return $row_result;
 }
 function getNumRowGeofence($field_value,$DbConnection)
 {
    $query = "SELECT geo_name FROM geofence USE INDEX(gef_gefname_status) WHERE geo_name='$field_value' and status='1'";  
    if($DEBUG==1)
    {
        print_query($query);
    }
    $result = @mysql_query($query, $DbConnection);     	     	                       
    $row_result=@mysql_num_rows($result);
    return $row_result;
 }
 function getNumRowPolyline($field_value,$DbConnection)
 {
    $query = "SELECT polyline_name FROM polyline USE INDEX(polyline_polyname_status) WHERE polyline_name='$field_value' and status='1'";  
    if($DEBUG==1){print_query($query);}
    $result = @mysql_query($query, $DbConnection);     	     	                       
    $row_result=@mysql_num_rows($result);
    return $row_result;
 }
 function getNumRowVisitArea($field_value,$DbConnection)
 {
    $query = "SELECT visit_area_name FROM visit_area USE INDEX(visar_visarname_status) WHERE visit_area_name='$field_value' and status='1'";  
    if($DEBUG==1){print_query($query);}
    $result = @mysql_query($query, $DbConnection);     	     	                       
    $row_result=@mysql_num_rows($result);
    return $row_result;
 }
 function getCustomerNoStation($field_value,$account_id,$DbConnection)
 {
    $query = "SELECT customer_no FROM station USE INDEX(stn_stncno_uaid_status) WHERE customer_no='$field_value' and user_account_id='$account_id' and status='1'";
    if($DEBUG==1){print_query($query);}
    $result = @mysql_query($query, $DbConnection);
    $row_result=@mysql_num_rows($result);
    return $row_result;
 }
 function getNumRowStation($field_value,$local_account_id,$DbConnection)
 {
    $query = "SELECT station_name FROM station USE INDEX(stn_stnname_uaid_status) WHERE station_name='$field_value' and user_account_id='$local_account_id' and status='1'";  
    if($DEBUG==1)
    {
        print_query($query);            
    }
    $result = @mysql_query($query, $DbConnection);     	     	                       
    $row_result=@mysql_num_rows($result);
    return $row_result;
 }
 function getNumRowSectorName($field_value,$DbConnection)
 {
    $query = "SELECT sector_name FROM sector USE INDEX(sector_sname_status) WHERE sector_name='$field_value' and status='1'";  
    if($DEBUG==1)
    {
        print_query($query);            
    }
    $result = @mysql_query($query, $DbConnection);     	     	                       
    $row_result=@mysql_num_rows($result);
    return $row_result;
 }
 function getNumRowRoute($field_value,$DbConnection)
 {
	$query = "SELECT route_name FROM route USE INDEX(route_rname_status) WHERE route_name='$field_value' and status='1'";  
	if($DEBUG==1){print_query($query);}
	$result = @mysql_query($query, $DbConnection);     	     	                       
	$row_result=@mysql_num_rows($result);
	return $row_result;
 }
 function getNumRowTranspoter($field_value,$DbConnection)
 {
	$query = "SELECT name FROM transporters USE INDEX(transp_name_status) WHERE name='$field_value' and status='1'";  
	if($DEBUG==1){print_query($query);}
	$result = @mysql_query($query, $DbConnection);     	     	                       
	$row_result=@mysql_num_rows($result);
	return $row_result;
 }

 
/*****************************************************************************************************************************/
/******************************************Device Sales Info*****************************************************************/
 function getDeviceIMEINoDeviceSalesInfo($account_id,$DbConnection)
 {
	$query = "SELECT device_imei_no FROM device_sales_info USE INDEX(dslsinfo_cid_status) WHERE create_id='$account_id' AND status='1'";	
	$result = mysql_query($query,$DbConnection);
	$j=0;                   
	while($row = mysql_fetch_object($result))
	{
		$device_imei[$j] = $row->device_imei_no;	
		$j++;
	}
	return $device_imei;
 }
/****************************************************************************************************************************/
/*************************************************Manage Edit Account ******************************************************/
function getUserTypeIdAccountFeature($accountId,$DbConnection)
{
    $query="SELECT user_type_id from account_feature USE INDEX(acc_id) WHERE account_id=$accountId";
	//echo "query=". $query."<br>";
    $result = mysql_query($query,$DbConnection);   
    $row=mysql_fetch_object($result);	
    $user_type_id_local=$row->user_type_id;
    return $user_type_id_local;	
}
function getGroupNameRemarkGroup($group_id1,$DbConnection)
{
	$query = "SELECT group_name,remark FROM `group` USE INDEX(grp_gid_status) WHERE group_id='$group_id1' AND status='1';";
	//echo "query=".$query;
	$result = mysql_query($query,$DbConnection); 	
	$row = mysql_fetch_row($result);
	//$dataGroupTable[]=array('group_name'=>$row->group_name,'remark'=>$row->remark);
	return $row;
}
/***************************************************************************************************************************/
/*************************************************Manage Edit Hindalco Invoice Admin*****************************************/
 function getProductNameProductType($parent_admin_id,$DbConnection)
 {
	$final_product_type_list=array();
	$query_product_type = "SELECT name FROM product_type USE INDEX(pte_aid_status) WHERE account_id='$parent_admin_id' AND status=1";
	$result_query = mysql_query($query_product_type,$DbConnection);
	while($row=mysql_fetch_object($result_query))
	{		
		$final_product_type_list[]=$row->name;
		
	}
	return $final_product_type_list;
 }
/*****************************************************************************************************************************/
/***********************************************manage Edit Vehicle***********************************************************/
 function getUserIdUserTypeNameUserType($cat,$DbConnection)
 {
    $query="SELECT user_type_id,user_type_name FROM user_type USE INDEX(ute_uttype_status) WHERE user_type_id in($cat) and status=1"; 
	$result=mysql_query($query,$DbConnection);
	$row=mysql_fetch_row($result);
	return $row;
 }
 function getCategoryVehicle($vehicle_id,$DbConnection)
 {
    $query="SELECT category from vehicle USE INDEX(v_vehicleid_status) WHERE vehicle_id='$vehicle_id' and status=1"; 
	$result=mysql_query($query,$DbConnection);
	$row=mysql_fetch_row($result);
	return $row;
 }

/*****************************************************************************************************************************/
/**************************************************Exiting IO Status********************************************************/
function getIoDeviceManfInfo($deviceImei,$DbConnection)
{
	$query="SELECT io FROM device_manufacturing_info USE INDEX(dmi_device_imei_status) WHERE device_imei_no='$deviceImei' and status=1";  
	//print_query($query);
	$result=@mysql_query($query,$DbConnection);
	$row=mysql_fetch_row($result);	
	return $row[0]; 
}
function getFeatureNameFeatureGrouping($io_id1,$DbConnection)
{
	$query="SELECT feature_name FROM feature_mapping WHERE feature_id='$io_id1'";
	//echo"query=".$query;
	$result=@mysql_query($query,$DbConnection);
	$row=mysql_fetch_row($result);
	return $row[0]; 
}

function getIoAssignmentDetail($list_fname,$vehicle_id,$DbConnection)
{
	$query="SELECT ".$list_fname." FROM io_assignment WHERE vehicle_id='$vehicle_id'";  ///get parent account feature
	$result=@mysql_query($query,$DbConnection);
	$row=@mysql_fetch_object($result);
	return $row;
}

function getColumnNameInfoSchema($DbConnection)
{
    $query="SELECT DISTINCT column_name FROM information_schema.columns WHERE table_name='io_assignment' AND ordinal_position>'2' AND ordinal_position<'17' and table_schema='iespl_vts_beta'";
	$result=mysql_query($query,$DbConnection);
	$feature_count=mysql_num_rows($result);
	$fi=0;
	while ($row=mysql_fetch_object($result))
	{
		$fi++;
		$fname[$fi]=$row->column_name;
			
	}
	return $fname;
}
/***************************************************************************************************************************/
/****************************************************Manage Account Plant Assignment pre**************************************/
function getCustomerNoStationAr($account_id,$DbConnection)
{
    $query_plant = "SELECT customer_no ,station_name FROM station USE INDEX(stn_type_uaid_status) WHERE type=1 AND user_account_id='$account_id' AND status=1";
    $result_query = mysql_query($query_plant,$DbConnection);
    while($row=mysql_fetch_object($result_query))
    {
            //echo $row->customer_no;
            $final_plant_list[]=$row->customer_no;
            $final_plant_name_list[]=$row->station_name;
            $dataCNS[]=array('final_plant_list'=>$row->customer_no,'final_plant_name_list'=>$row->station_name);
    }
    return $dataCNS;
}
function getAccountIDUserID($DbConnection)
{
	$query = "SELECT account_id,user_id FROM account USE INDEX(acc_ut_status) WHERE user_type='plant_raw_milk' AND status=1";		
	//echo $query;
	$result = mysql_query($query,$DbConnection);
	while($row = mysql_fetch_object($result))
	{
		$account_id_sub = $row->account_id;
		$user_id_sub = $row->user_id;
		$dataAU[]=array('account_id_sub'=>$row->account_id,'user_id_sub'=>$row->user_id);
	}
	return $dataAU;
	
}
/*****************************************************************************************************************************/
/*********************************************manage assign vehicle thirdparty*************************************************/
function getTpaUidNameAr($account_id,$DbConnection)
{
	$querydesaid="SELECT third_party_account_assignment.third_party_account_id,account.user_id,account_detail.name FROM third_party_account_assignment USE INDEX(tpaa_aaid_status),account,account_detail 
	WHERE third_party_account_assignment.admin_account_id='$account_id' and third_party_account_assignment.status=1 
	 AND account.account_id=third_party_account_assignment.third_party_account_id AND account_detail.account_id=account.account_id and account.status=1";
	$resultdesaid=mysql_query($querydesaid,$DbConnection);
	while($row=mysql_fetch_object($resultdesaid))
	{		
		$tid=$row->third_party_account_id;
		$tuser_id=$row->user_id;
		$tname=$row->name;
		$selectionbox.="<option value='$tid'>$tname($tuser_id)</option>";
		$data[]=array('tid'=>$row->third_party_account_id,'tuser_id'=>$row->user_id,'tname'=>$row->name);
	}
}
/****************************************************************************************************************************/
/***********************************Defautl Chilling PlantAssignment********************************************************/
 function getCustomerNoStationNext($account_id,$DbConnection)  
 { 
    $query_chillplant = "SELECT customer_no,station_name FROM station USE INDEX(stn_type_uaid_status) WHERE type=2 AND user_account_id='$account_id' AND status=1";
	$result_chillquery = mysql_query($query_chillplant,$DbConnection);
	while($rowchill=mysql_fetch_object($result_chillquery))
	{
		//echo $row->customer_no;
		$final_chillplant_list[]=$rowchill->customer_no;
		$final_chillplant_name_list[]=$rowchill->station_name;
		$dataCNS[]=array('final_chillplant_list'=>$row->customer_no,'final_chillplant_name_list'=>$row->station_name);
	}
	return $dataCNS;
 }
 
	
/***************************************************************************************************************************/
/*********************************************manage Delete Station**********************************************************/
 function getStationIDCustomerNoStation($common_id1,$DbConnection)
 {
  $query="SELECT station_id,customer_no FROM station USE INDEX(stn_uaid_status) WHERE user_account_id='$common_id1' AND status='1'";
		//echo "query=".$query."<br>";
		$result=mysql_query($query,$DbConnection);
		$row_result=mysql_num_rows($result);
        while($row=mysql_fetch_object($result))
        {									
            $geo_id=$row->station_id;
            $customer_no=$row->customer_no;	

            $data[]=array('geo_id'=>$row->station_id,'customer_no'=>$row->customer_no);
        }
        return $data;	
 }
/*****************************************************************************************************************************/
/*********************************************manage Device Assignment*******************************************************/
function getDeviceImeiNoAr($account_id,$DbConnection)
{
	$query="SELECT DISTINCT device_imei_no FROM device_assignment USE INDEX(dass_aid_status) WHERE account_id='$account_id' and status='1'";
	$result=mysql_query($query,$DbConnection); 
	while($row=mysql_fetch_object($result))
	{
		$data[]=array('device_imei_no'=>$row->device_imei_no);
	}
	return $data;	
}
/***************************************************************************************************************************/
/*********************************************manage Device Vehicle Deregisterd***********************************************/
 function getVIDINVnameAr($local_account_id,$DbConnection)
 {
  $query ="SELECT DISTINCT vehicle.vehicle_id, vehicle.vehicle_name, vehicle_assignment.device_imei_no FROM vehicle,vehicle_assignment,".
            "vehicle_grouping USE INDEX(vg_accountid_status) WHERE vehicle.vehicle_id = vehicle_assignment.vehicle_id AND vehicle.status='1' AND ".
            " vehicle_assignment.status = '1' AND vehicle.vehicle_id=vehicle_grouping.vehicle_id AND vehicle_grouping.account_id".
            "=$local_account_id AND vehicle_grouping.status=1";       
      $result = @mysql_query($query, $DbConnection);		 
      while($row = mysql_fetch_object($result))
      {
            /*$vid[]= $row->vehicle_id;
            $device[] = $row->device_imei_no;
            $vname[] = $row->vehicle_name;*/
            $data[]=array('vid'=>$row->vehicle_id,'device'=>$row->device_imei_no,'vname'=>$row->vehicle_name);
      } 
      return $data;
 }
/***************************************************************************************************************************/
/*********************************************manage Device Vehicle Register***********************************************/
function getDeviceImeiNoDAVAArray($account_id_local1,$DbConnection)
{
 $query="SELECT  device_assignment.device_imei_no FROM device_assignment USE INDEX(dass_aid_status) LEFT JOIN vehicle_assignment ON vehicle_assignment.device_imei_no".
		"=device_assignment.device_imei_no AND vehicle_assignment.status=1 WHERE vehicle_assignment.device_imei_no IS NULL AND ".
		"device_assignment.account_id = '$account_id_local1' AND device_assignment.status=1";	
		$device_result=mysql_query($query,$DbConnection);
		while($row=mysql_fetch_object($device_result))
		{
			$dataDIN[]=array('device_imei_no'=>$row->device_imei_no);
		}
		return $dataDIN;		
} 
function getVehicleIDVehicleNameVVG($account_id_local1,$DbConnection)
{
    $query="SELECT vehicle.vehicle_id,vehicle.vehicle_name FROM vehicle INNER JOIN vehicle_grouping USE INDEX(vg_accountid_status) ON vehicle_grouping.vehicle_id = vehicle.vehicle_id".
            " AND vehicle_grouping.account_id='$account_id_local1' AND vehicle_grouping.status=1 AND vehicle.status='1' LEFT JOIN vehicle_assignment ON ".
            "vehicle_grouping.vehicle_id = vehicle_assignment.vehicle_id AND vehicle_assignment.status=1 WHERE vehicle_assignment.vehicle_id IS NULL";
    $all_result=mysql_query($query,$DbConnection);
    while($row=mysql_fetch_object($all_result))
    {
            $dataVIVN[]=array('vehicle_id'=>$row->vehicle_id,'vehicle_name'=>$row->vehicle_name);
    }
    return $dataVIVN;	
}
function getVehicleIDVehicleNameVVGNext($vehicle_display_option1,$options_value2,$DbConnection)
{
    $query="SELECT vehicle.vehicle_id,vehicle.vehicle_name FROM vehicle INNER JOIN vehicle_grouping ON vehicle_grouping.vehicle_id=vehicle.vehicle_id AND ".
    "vehicle_grouping.account_id=2 AND vehicle_grouping.status=1 AND vehicle.$vehicle_display_option1='$options_value2' LEFT JOIN  vehicle_assignment ON ".
    "vehicle_grouping.vehicle_id = vehicle_assignment.vehicle_id AND vehicle_assignment.status=1 WHERE vehicle_assignment.vehicle_id IS NULL;";
    //echo "query=".$query;
    $all_result=mysql_query($query,$DbConnection);
    while($row=mysql_fetch_object($all_result))
    {
            $dataVIVNN[]=array('vehicle_id'=>$row->vehicle_id,'vehicle_name'=>$row->vehicle_name);
    }
    return $dataVIVNN;	
}
 
/***************************************************************************************************************************/
/******************************************manage Edit Account***************************************************************/
function getNameDistanceVarAccountDetail($edit_account_id,$DbConnection)
{
    $query="SELECT name,distance_variable FROM account_detail USE INDEX(ad_account_id) WHERE account_id='$edit_account_id' ORDER BY account_id";     
    $result = mysql_query($query,$DbConnection);
    $row=mysql_fetch_row($result);
    return $row;
}
function getFieldStringAccountFeature($edit_account_id,$DbConnection)
{
    $query="SELECT * FROM account_feature USE INDEX(acc_id) WHERE account_id=$edit_account_id";   
    $result = mysql_query($query,$DbConnection);
    $row=mysql_fetch_object($result);
    $field_string="";
    for($i=1;$i<=44;$i++)   //////for getting the field name having value 1 
    {
        $field="field".$i;
        $field_value=$row->$field;
        if($field_value==1)
        {  
            $field_string.="field_name='field".$i."' OR ";
        }
    }
    return $field_string; 
}
function getFeatureNameFeatureGroupingArray($field_string,$DbConnection)
{ 
    $query="SELECT feature_name FROM feature_mapping WHERE $field_string AND status=1"; 
    $result = mysql_query($query,$DbConnection);
    while($row=mysql_fetch_object($result))
    {
        $feature_name[]= $row->feature_name;
    }
    return $feature_name;	
}
function getPermissionAccountDetail($edit_account_id,$DbConnection)
{
    $query="SELECT permission FROM account_detail USE INDEX(ad_account_id) WHERE account_id='$edit_account_id'";		
    $result=mysql_query($query,$DbConnection);
    $row=mysql_fetch_object($result);		
    $user_permission=$row->permission;
    return $user_permission;	
}
function getFeatureIDUserTypeMap($user_type_id_session,$DbConnection)
{
    $query = "SELECT feature_id FROM usertype_mapping USE INDEX(utpe_utid) WHERE user_type_id='$user_type_id_session'";
    $result = mysql_query($query,$DbConnection);
    $row = mysql_fetch_object($result);
    $feature_id_str_db = $row->feature_id;
    return $feature_id_str_db;
}

 /********************************************manage Edit Delete Calibration*******************************************************/
 function getClCNameCalibration($common_id1,$DbConnection)
 {
    //$query="select * FROM calibration WHERE calibration_id IN (SELECT calibration_id from calibration_grouping where account_id='$common_id1' and status=1) and status='1'";
    $query="select calibration.calibration_id,calibration.calibration_name FROM calibration,".
            "calibration_grouping USE INDEX(cg_accountid_status) WHERE calibration.status='1' AND calibration.calibration_id=".
            "calibration_grouping.calibration_id AND calibration_grouping.account_id='$common_id1'".
            " AND calibration_grouping.status=1";
	//echo "Query=".$query."<br>";
    $result=mysql_query($query,$DbConnection);                							
    while($row=mysql_fetch_object($result))
    {
       /* $calibration_id=$row->calibration_id; 
        $calibration_name=$row->calibration_name; */          								 
        $data[]=array('calibration_id'=>$row->calibration_id,'calibration_name'=>$row->calibration_name);
    }
    return $data;
 }
/*****************************************************************************************************************************/
/********************************************manage Edit Delete GEOFENCE*******************************************************/
function getGeoIDGeoNameGeofence($common_id1,$DbConnection)
{
    $query="select * from geofence USE INDEX(geo_uaid_status) WHERE user_account_id='$common_id1' and status='1'";		
    $result=mysql_query($query,$DbConnection);            							
    while($row=mysql_fetch_object($result))
    {
        /*$geo_id=$row->geo_id; 
        $geo_name=$row->geo_name;*/			
        $data[]=array('geo_id'=>$row->geo_id,'geo_name'=>$row->geo_name);	
    }
    return $data;
}
/*****************************************************************************************************************************/
/********************************************manage Edit Delete Invoice*******************************************************/
function getDetailAllInvoice($account_id_local,$DbConnection)
{
    $query = "SELECT * FROM invoice USE INDEX(inve_aid_status) WHERE account_id='$account_id_local' AND status=1";	
    $result = mysql_query($query,$DbConnection);
    while($row = mysql_fetch_object($result))
    {
        /*$sno = $row->sno;
        $vehicle_no = $row->vehicle_no;
        $customer_code = $row->customer_code;
        $customer_name = $row->customer_name;
        $invoice_no = $row->invoice_no;
        $invoice_amount = $row->invoice_amount;
        $email_id = $row->email_id;
        $remarks = $row->remarks;
        $driver_name = $row->driver_name;
        $driver_phone_no = $row->driver_phone_no;
        $target_time = $row->target_time;		
        $tracking_no = $row->tracking_no;		
        $status = $row->status;*/		
        $data[]=array('sno'=>$row->sno,'vehicle_no'=>$row->vehicle_no,'customer_code'=>$row->customer_code,'customer_name'=>$row->customer_name,'invoice_no'=>$row->invoice_no,'invoice_amount'=>$row->invoice_amount,'email_id'=>$row->email_id,'remarks'=>$row->remarks,'driver_name'=>$row->driver_name,'driver_phone_no'=>$row->driver_phone_no,'target_time'=>$row->target_time,'tracking_no'=>$row->tracking_no,'status'=>$row->status);
    }				
}
/*****************************************************************************************************************************/
function getCalibrationIdCalibrationVehicleAssignment($calibration_id,$status,$DbC)
{
	$query="SELECT calibration_id FROM calibration_vehicle_assignment WHERE calibration_id='$calibration_id' AND status='$status'";
	$result=mysql_query($query,$DbC); 
	$numrows = mysql_num_rows($result);
	return $numrows;
}
function updateCalibration1($account_id,$date,$status1,$calibration_id,$status2,$DbC)
{
	 $query="UPDATE calibration SET edit_id='$account_id',edit_date='$date',status='$status1' WHERE calibration_id='$calibration_id' AND status='$status2'"; 
	 $result=mysql_query($query,$DbC);
     return $result;		 
}
function insertCalibrationVehicleAssignment($vehicle_size,$local_calibration_id,$local_vehicle_ids,$account_id,$date,$status,$DbC)
{
	$query_string1="INSERT INTO calibration_vehicle_assignment(calibration_id,vehicle_id,edit_id,edit_date,status) VALUES";
	for($i=0;$i<$vehicle_size;$i++)
	{
		if($i==$vehicle_size-1)
		{
			$query_string2.="($local_calibration_id,$local_vehicle_ids[$i],$account_id,'$date','$status');";
		}
		else
		{
			$query_string2.="($local_calibration_id,$local_vehicle_ids[$i],$account_id,'$date','$status'),";
		}
	}
	$query=$query_string1.$query_string2; 
	//echo "Query=".$query."<br>";	
	$result=mysql_query($query,$DbC);
	return $result;
	
}
function updateCalibrationVehicleAssignment($status1,$account_id,$date,$local_vehicle_ids,$status2,$DbC)
{
	$query="UPDATE calibration_vehicle_assignment SET status='$status1',edit_id='$account_id',edit_date='$date' WHERE vehicle_id='$local_vehicle_ids' AND status='$status2'";
	$result=mysql_query($query,$DbC); 
	return $result;
}
function getStationMaxSerial($DbConnection)
{
	$query ="select Max(sno)+1 as serial_no from station";  ///// for auto increament of geo_id ///////////   
	$result=mysql_query($query,$DbConnection);
	$row=mysql_fetch_object($result);
	$max_no= $row->serial_no;
	return $max_no;
}
/********************************************manage Edit Delete landmark*******************************************************/
function getDetailAllLandmark($account_id_local,$DbConnection)
{
	$query="SELECT * FROM landmark USE INDEX(lnk_lnkaid_status) WHERE account_id='$account_id_local' and status='1'";
	$result=mysql_query($query,$DbConnection);            							
	while($row=mysql_fetch_object($result))
	{
            /*$landmark_id=$row->landmark_id;
            $landmark_name=$row->landmark_name;
            $zoom_level=$row->zoom_level;
            $distance_variable=$row->distance_variable;*/		
            $data[]=array('landmark_id'=>$row->landmark_id,'landmark_name'=>$row->landmark_name,'zoom_level'=>$row->zoom_level,'distance_variable'=>$row->distance_variable);	
        }
	return $data;
}
function deleteGeofence($account_id,$date,$upstatus,$geo_id1,$constatus,$DbConnection)
{
	$query="UPDATE geofence SET edit_id='$account_id',edit_date='$date',status=$upstatus WHERE geo_id='$geo_id1' AND status=$constatus";
	//echo "Query=".$query."<br>";
	$result=mysql_query($query,$DbConnection);
	return $result;
} 



function updateGeofence($geo_name1,$geo_coord1,$account_id,$date,$geo_id1,$DbConnection)
{
	$query="UPDATE geofence SET geo_name='$geo_name1',geo_coord='$geo_coord1',edit_id='$account_id',edit_date='$date' WHERE geo_id='$geo_id1'";
	$result=mysql_query($query,$DbConnection); 
	return $result;
}

function getGeofenceMaxSerial($DbConnection)
{
	$query ="select Max(sno)+1 as seiral_no from geofence";  ///// for auto increament of geo_id ///////////   
	$result=mysql_query($query,$DbConnection);
	$row=mysql_fetch_object($result);
	$max_no= $row->seiral_no;
	return $max_no;
}
function assignGeofence($vehicle_size,$local_geofence_id,$local_vehicle_ids,$account_id,$date,$status,$DbConnection)
{
	$query_string1="INSERT INTO geo_assignment(geo_id,vehicle_id,create_id,create_date,status) VALUES";

	for($i=0;$i<$vehicle_size;$i++)
	{
		//echo "local_geofenc_id=".$local_geofenc_id[$i]."<br>";
		if($i==$vehicle_size-1)
		{
			$query_string2.="($local_geofence_id,$local_vehicle_ids[$i],$account_id,'$date',$status);";
		}
		else
		{
			$query_string2.="($local_geofence_id,$local_vehicle_ids[$i],$account_id,'$date',$status),";
		}
	}
	$query=$query_string1.$query_string2;     
	$result=mysql_query($query,$DbConnection); 
	return $result;
}

function deassignGeofence($vehicle_size,$upstatus,$account_id,$date,$local_vehicle_ids,$constatus,$DbConnection)
{
	for($i=0;$i<$vehicle_size;$i++)
	{	
		$query="UPDATE geo_assignment SET status=$upstatus,edit_id='$account_id',edit_date='$date' WHERE vehicle_id='$local_vehicle_ids[$i]' AND status=$constatus";
		$result=mysql_query($query,$DbConnection); 
		//$geo_id1= $veh_geo_id2[1];
		/*$old_value[]= "1";
		$new_value[]="0";
		$field_name[]= "status";         
		$ret_result=track_table($local_vehicle_ids[$i],$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);*/
	}
	return $result;
}

	function insertGeofence($account_size,$local_account_ids,$max_no,$geo_name1,$geo_coord1,$status,$account_id,$date,$DbConnection)
	{
		$query_string1="INSERT INTO geofence(user_account_id,geo_id,geo_name,geo_coord,status,create_id,create_date) VALUES";
		for($i=0;$i<$account_size;$i++)
		{
			//echo "accout_id=".$local_account_ids[$i]."<br>";
			if($i==$account_size-1)
			{
				$query_string2.="('$local_account_ids[$i]','$max_no','$geo_name1','$geo_coord1',$status,'$account_id','$date');";
			}
			else
			{
				$query_string2.="('$local_account_ids[$i]','$max_no','$geo_name1','$geo_coord1',$status,'$account_id','$date'),";
			}
		}
		$query=$query_string1.$query_string2;
		$result=mysql_query($query,$DbConnection); 
		return $result;
	}

function updateLandmark($landmark_name1,$landmark_point1,$zoom_level1,$account_id,$date,$landmark_id1,$DbConnection)
{
	$query="UPDATE landmark SET landmark_name='$landmark_name1',landmark_coord='$landmark_point1',zoom_level='$zoom_level1',edit_id='$account_id',edit_date='$date' WHERE landmark_id='$landmark_id1'";
	//echo "update_query=".$query."<br>";;
	$result=mysql_query($query,$DbConnection); 
	return $result;
}

function deleteLandmark($account_id,$date,$upstatus,$landmark_id1,$constatus,$DbConnection)
{
	$query="UPDATE landmark SET edit_id='$account_id',edit_date='$date',status=$upstatus WHERE landmark_id='$landmark_id1' AND status=$constatus"; 
	$result=mysql_query($query,$DbConnection);  
	return $result;
}
/*****************************************************************************************************************************/
/********************************************manage Edit Delete Load Cell*******************************************************/
function getDelailAllLoadCell($account_id_local,$DbConnection)
{
    $query="SELECT load_cell_id,datetime,imei,load FROM load_cell USE INDEX(ldcell_uaid_status) WHERE user_account_id='$account_id_local' AND status='1'";				
    $result=mysql_query($query,$DbConnection);            							
    while($row=mysql_fetch_object($result))
    {
        /*$load_cell_id=$row->load_cell_id;
        $date=$row->datetime;				
        $imei=$row->imei;
        $load=$row->load;*/		
        $data[]=array('load_cell_id'=>$row->load_cell_id,'date'=>$row->datetime,'imei'=>$row->imei,'load'=>$row->load);	
    }
    return $data;
}
/*****************************************************************************************************************************/
/********************************************manage Edit Delete Mile Stone*******************************************************/
function getDetailAllMileStone($groupo_id_local,$DbConnection)
{
    $query="SELECT milestone_id,milestone_name,milestone_type FROM milestone_assignment USE INDEX(mass_gid_status) WHERE group_id='$groupo_id_local' AND status=1";    				
    if($DEBUG==1){print $query;}
    $result=mysql_query($query,$DbConnection);            							
    while($row=mysql_fetch_object($result))
    {
        /*$ms_id=$row->milestone_id; 
        $ms_name=$row->milestone_name;
        $ms_type=$row->milestone_type;*/
        $data[]=array('ms_id'=>$row->milestone_id,'ms_name'=>$row->milestone_name,'ms_type'=>$row->milestone_type);	
    }
    return $data;
}
/*****************************************************************************************************************************/

/********************************************manage Edit Delete PolyLine*******************************************************/
 function getDetailAllPolyline($common_id1,$DbConnection)
 {
    $query="select * FROM polyline USE INDEX(polyline_uaid_status) WHERE user_account_id='$common_id1' and status='1'";	
    $result=mysql_query($query,$DbConnection);            							
    while($row=mysql_fetch_object($result))
    {
       /*$polyline_id=$row->polyline_id; 
        $polyline_name=$row->polyline_name;*/
        $data[]=array('polyline_id'=>$row->polyline_id,'polyline_name'=>$row->polyline_name);
    }
    return $data;
 }
/*****************************************************************************************************************************/
/********************************************manage Edit Delete Route*******************************************************/
function getDetailAllRoute($common_id1,$DbConnection)
{
  $query="select * FROM route USE INDEX(route_uaid_status) WHERE user_account_id='$common_id1' and status='1'";
    $result=mysql_query($query,$DbConnection);            							
    while($row=mysql_fetch_object($result))
    {
      /*$route_id=$row->route_id; 
      $route_name=$row->route_name;*/               								 
      $data[]=array('route_id'=>$row->route_id,'route_name'=>$row->route_name);
    }
    return $data;
}
/*****************************************************************************************************************************/
/*********************************************manage Edit Delete Schedule************************************************/
function getDetailAllSchedule($account_id_local,$DbConnection)
{
    $query="select * FROM schedule_location USE INDEX(schl_schlaid_status) WHERE account_id='$account_id_local' and status='1'";		
    $result=mysql_query($query,$DbConnection);            							
    while($row=mysql_fetch_object($result))
    {
        /*$location_id=$row->location_id;
        $location_name=$row->location_name;*/				
        $data[]=array('location_id'=>$row->location_id,'location_name'=>$row->location_name);
    }
    return $data;	
}
/***********************************************************************************************************************/
/*********************************************manage Edit Delete Schedule Upl************************************************/
function getDetailAllScheduleUpl($account_id_local,$DbConnection)
{
    $query="select location_id,location_name FROM schedule_location_upl USE INDEX(schlupl_schluplaid_status) WHERE account_id='$account_id_local' and status='1'";		
    $result=mysql_query($query,$DbConnection);            							
    while($row=mysql_fetch_object($result))
    {
        /*$location_id=$row->location_id;
        $location_name=$row->location_name;*/				
        $data[]=array('location_id'=>$row->location_id,'location_name'=>$row->location_name);
    }
    return $data;
	
}
/***********************************************************************************************************************/
/*********************************************manage Edit Delete Sector************************************************/
function getDetailAllSector($common_id1,$DbConnection)
{
    $query="select sector_id,sector_name FROM sector USE INDEX(sector_uaid_status) WHERE user_account_id='$common_id1' and status='1'";	
    $result=mysql_query($query,$DbConnection);            							
    while($row=mysql_fetch_object($result))
    {
        /*$sector_id=$row->sector_id; 
        $sector_name=$row->sector_name;*/			
        $data[]=array('sector_id'=>$row->sector_id,'sector_name'=>$row->sector_name);
    }
    return $data;
	
}
/***********************************************************************************************************************/
function assignStationAssignment($station_size,$local_station_ids,$local_vehicle_id,$account_id,$date,$status,$DbConnection)
{
	$query_string1="INSERT INTO station_assignment(station_id,vehicle_id,create_id,create_date,status) VALUES";
	for($i=0;$i<$station_size;$i++)
	{
		//echo "local_geofenc_id=".$local_geofenc_id[$i]."<br>";
		if($i==$station_size-1)
		{
			$query_string2.="($local_station_ids[$i],$local_vehicle_id,$account_id,'$date','$status');";
		}
		else
		{
			$query_string2.="($local_station_ids[$i],$local_vehicle_id,$account_id,'$date','$status'),";
		}
	}
	$query=$query_string1.$query_string2; 
	//echo "query=".$query;
	if($DEBUG ==1 )print_query($query);     
	$result=mysql_query($query,$DbConnection); 
	return $result;
}
function getScheduleAssignmentMaxSerial($DbConnection)
{
	$query ="SELECT Max(sno)+1 as seiral_no FROM schedule_location";  ///// for auto increament of landmark_id ///////////   
	$result=mysql_query($query,$DbConnection);
	$row=mysql_fetch_object($result);
	$max_no= $row->seiral_no;
	return $max_no;
 
}
function deassignStationDeassignment($vehicle_size,$local_vehicle_ids,$status0,$account_id,$date,$vehicle_id,$station_id,$status1,$DbConnection)
{
  
	for($i=0;$i<$vehicle_size;$i++)
	{	
	  $local_all_ids=explode(":",$local_vehicle_ids[$i]);
	  $vehicle_id = $local_all_ids[0];
	  $station_id = $local_all_ids[1];					
	  $query="UPDATE station_assignment SET status='$status0',edit_id='$account_id',edit_date='$date' WHERE vehicle_id='$vehicle_id' AND station_id='$station_id' AND status='$status1'";
	  //echo $query;
	  $result=mysql_query($query,$DbConnection); 
	  return $result;
		
	}
 
}
/*********************************************manage Edit Delete Station************************************************/
function getDetailAllStation($account_id_local,$station_type1,$DbConnection)
{
    $query="select station_id,customer_no,station_name,distance_variable FROM station USE INDEX(stn_uaid_status) WHERE user_account_id=$account_id_local and status=1";
    //echo "query=".$query."<br>";
	//echo "DbConnection=".$DbConnection."<br>";
	$result=mysql_query($query,$DbConnection);            							
    while($row=mysql_fetch_object($result))
    {
		//echo "in while";
        /*$station_id=$row->station_id;
        $customer_no=$row->customer_no;	
        $station_name=$row->station_name;
        $distance_variable=$row->distance_variable;
        $type=$row->type;*/				
        $data[]=array('station_id'=>$row->station_id,'customer_no'=>$row->customer_no,'station_name'=>$row->station_name,'distance_variable'=>$row->distance_variable,'type'=>$row->type);
    }
	//print_r($data);
    return $data;
}
/***********************************************************************************************************************/
/*********************************************manage Edit Delete Vehicel************************************************/
 function getVehicleIdVehicleNameDetailVehicel($account_id_local1,$vehicle_display_option1,$options_value2,$DbConnection)
 {
    $query="SELECT vehicle_id,vehicle_name FROM vehicle WHERE account_id='$account_id_local1' AND $vehicle_display_option1='$options_value2' AND status='1'";
	//echo "query=".$query;
	$result=mysql_query($query,$DbConnection);
	$flag=0;
	$vehicle_cnt=0;
	while($row=mysql_fetch_object($result))
	{
	    /*$vehicle_id=$row->vehicle_id; 
            $vehicle_name=$row->vehicle_name;*/   		
            $data[]=array('vehicle_id'=>$row->vehicle_id,'vehicle_name'=>$row->vehicle_name);
	}
	return $data;
 }
 function getVehicleIdVehicleNameDetailVehicelNext($account_id_local1,$DbConnection)
 {
    $query="SELECT vehicle_id,vehicle_name FROM vehicle USE INDEX(v_aid_status) WHERE account_id='$account_id_local1' AND status='1'";
	//echo "query=".$query;
	$result=mysql_query($query,$DbConnection);
	$flag=0;
	$vehicle_cnt=0;
	while($row=mysql_fetch_object($result))
	{
	    /*$vehicle_id=$row->vehicle_id; 
            $vehicle_name=$row->vehicle_name;*/    		
            $dataNext[]=array('vehicle_id'=>$row->vehicle_id,'vehicle_name'=>$row->vehicle_name);
	}
	return $dataNext;
 }
/***********************************************************************************************************************/
/*********************************************manage Edit Delete Visit Area************************************************/
 function getDetailAllVisitArea($common_id1,$DbConnection)
 {
    $query="select visit_area_id,visit_area_name FROM visit_area USE INDEX(visar_uaid_status) WHERE user_account_id='$common_id1' and status='1'";	
    $result=mysql_query($query,$DbConnection);            							
    while($row=mysql_fetch_object($result))
    {
        /*$geo_id=$row->visit_area_id; 
        $geo_name=$row->visit_area_name;*/
        $data[]=array('geo_id'=>$row->visit_area_id,'geo_name'=>$row->visit_area_name);
    }
    return $data;
 }
/***********************************************************************************************************************/
/*********************************************manage Edit Delete Device************************************************/
function getDetailAllDevice($account_id_local,$DbConnection)
{
    $query="SELECT device_manufacturing_info.device_imei_no FROM device_manufacturing_info INNER JOIN device_assignment USE INDEX(dass_aid_status) ON device_manufacturing_info.device_imei_no=".
        "device_assignment.device_imei_no AND device_assignment.account_id='$account_id_local' AND device_assignment.status=1";
    $result=mysql_query($query,$DbConnection);
    while($row=mysql_fetch_object($result))
    {
        //$device_imei_no=$row->device_imei_no;		
        $data[]=array('device_imei_no'=>$row->device_imei_no);	
    }
    return $data;
}
/***********************************************************************************************************************/
/*********************************************manage Edit Delete Device Sales ************************************************/
function getDetailAllDeviceSales($edit_device_sale,$account_id,$DbConnection)
{
    $query = "SELECT device_sales_info.device_imei_no,account.superuser, account.user FROM ".
    "device_sales_info USE INDEX(dslsinfo_imeino_cid_status),account WHERE ".
    "account.account_id = device_sales_info.user_account_id AND ".
    "device_sales_info.device_imei_no='$edit_device_sale' AND ".
    "device_sales_info.create_id='$account_id' AND device_sales_info.status='1'";
    $result = mysql_query($query,$DbConnection);
    while($row=mysql_fetch_object($result))
    {	
        $imei_no_2 = $row->device_imei_no;
        $superuser_2 = $row->superuser; 
        $user_2 = $row->user; 
        $data[]=array('imei_no_2'=>$row->device_imei_no,'superuser_2'=>$row->superuser,'user_2'=>$row->user);	
    }
    return $data;
}
/***********************************************************************************************************************/
/*********************************************manage Edit Delete Distance Variable *************************************/
function getDetailAllStationDistVar($account_id_local1,$station_type1,$DbConnection)
{
    $query="SELECT station_id,customer_no FROM station USE INDEX(stn_type_uaid_status) WHERE user_account_id='$account_id_local1' AND type='$station_type1' AND status='1'";
    //echo "query=".$query."<br>";
    $result=mysql_query($query,$DbConnection);
    $row_result=mysql_num_rows($result);
    while($row=mysql_fetch_object($result))
    {									
        //$geo_id=$row->station_id;
        //$customer_no=$row->customer_no;
        $data[]=array('geo_id'=>$row->station_id,'customer_no'=>$row->customer_no);	
    }
    return $data;
}
/***********************************************************************************************************************/
/*********************************************manage Edit Person ******************************************************/
function getDetailAllPerson($common_id1,$DbConnection)
{
	$query="SELECT person_id,person_name from person USE INDEX(person_uaid_status) where user_account_id='$common_id1' and status='1'";
	$result=mysql_query($query,$DbConnection);            							
	while($row=mysql_fetch_object($result))
	{
	  //$person_id=$row->person_id; 
	  //$person_name=$row->person_name;                								 
	  $data[]=array('person_id'=>$row->person_id,'person_name'=>$row->person_name);	
	}
	return $data;
}
/******************************************************************************** *************************************/
/*********************************************manage Edit Vehicle ******************************************************/
function getDetailAllUserType($row_user_type_id,$DbConnection)
{
	$query="SELECT user_type_id,user_type_name from user_type USE INDEX(ute_uttype_status) WHERE user_type_id in($row_user_type_id) and status=1"; 
	//echo "query=".$query."<br>";
	$result=mysql_query($query,$DbConnection);		
	while($row=mysql_fetch_object($result))
	{
	    $user_type_id=$row->user_type_id;
		$user_type_name=$row->user_type_name;		
		$data[]=array('user_type_id'=>$row->user_type_id,'user_type_name'=>$row->user_type_name);	
	}
	return $data;
}
function getDetailAllVehicleArray($vehicle_id,$DbConnection)
{
$query = "SELECT * FROM vehicle USE INDEX(v_vehicleid_status) WHERE vehicle_id='$vehicle_id' AND status=1";
    //echo "query=".$query;
    $result = mysql_query($query,$DbConnection);
while($row=mysql_fetch_object($result))
    {
        /*$vehicle_id = $row->vehicle_id;
        $vehicle_name = $row->vehicle_name;
        $vehicle_number = $row->vehicle_number;
        $sim_number = $row->sim_number;
        $mobile_number = $row->mobile_number;
        $max_speed = $row->max_speed;
        $vehicle_tag = $row->vehicle_tag;
        $vehicle_type = $row->vehicle_type;*/

        $dataV[]=array('vehicle_id'=>$row->vehicle_id,'vehicle_name'=>$row->vehicle_name,'vehicle_number'=>$row->vehicle_number,'sim_number'=>$row->sim_number,'mobile_number'=>$row->mobile_number,'max_speed'=>$row->max_speed,'vehicle_tag'=>$row->vehicle_tag,'vehicle_type'=>$row->vehicle_type);	
    }
	return $dataV;
}

/***********************************************************************************************************************/

/***********************************************Manage Edit Visit Person*****************************************************/
function getDetailAllVisitPerson($common_id1,$DbConnection)
{  
	$query = "select vehicle_name,vehicle_id from vehicle where vehicle_id IN ".
	"(select vehicle_id from vehicle_grouping USE INDEX(vg_accountid_status) where account_id='$common_id1' and status='1') and status=1";    			
	$result=mysql_query($query,$DbConnection);            							
	while($row=mysql_fetch_object($result))
	{
	  /*$person_id=$row->vehicle_id; 
	  $person_name=$row->vehicle_name;*/
	  
	  $data[]=array('person_id'=>$row->vehicle_id,'person_name'=>$row->vehicle_name);	
	}
	return $data;
}
/***********************************************************************************************************************/
/***************************************************Manage Location Assignment***************************************/
function getDetailAllLandmarkNext($common_id1,$DbConnection)
{
	$Query="SELECT landmark_id,landmark_name FROM landmark USE INDEX(lnk_lnkaid_status) WHERE account_id='$common_id1' AND status=1";
	$Result=mysql_query($Query,$DbConnection);
	while($Row=mysql_fetch_object($Result))
	{
	    $landmark_id=$Row->landmark_id;
	    $landmark_name=$Row->landmark_name;
	   
		$data[]=array('landmark_id'=>$row->landmark_id,'landmark_name'=>$row->landmark_name);	
	}
	return $data;
}
function getDetailAllWockhardLocation($common_id1,$DbConnection)
{
	$query="SELECT location_id,location_name from wockhardt_locations USE INDEX(wctloc_aid_status) where account_id='$common_id1' AND status='1'";
	$result=mysql_query($query,$DbConnection);
	$row_result=mysql_num_rows($result);	
    while($row=mysql_fetch_object($result))
	{									
		/*$location_id=$row->location_id;
		$location_name=$row->location_name;*/
		
	    $data[]=array('location_id'=>$row->location_id,'location_name'=>$row->location_name);	
	}
	return $data;
}
/*******************************************************************************************************************/
/*******************************************************Vehicle Route Assignment**********************************************/
function getDetailAllRouteAssignment2($account_id,$DbConnection)
{
    $query_assigned_ev = "SELECT vehicle_name,route_name_ev,remark_ev,evening_update_time,edit_id FROM route_assignment2 USE INDEX(rass2_aid_status) WHERE ".
    " user_account_id='$account_id' AND status=1";	
    $result_assigned_ev = mysql_query($query_assigned_ev, $DbConnection);
    while($row_ev=mysql_fetch_object($result_assigned_ev))
    {
        /*$vname_assigned_ev = $row_ev->vehicle_name;
        $route_assigned_ev = $row_ev->route_name_ev;
        $update_time_ev = $row_ev->evening_update_time;
        $remark_assigned_ev = $row_ev->remark_ev;
        $edit_id = $row_ev->edit_id;*/		
        $data[]=array('vname_assigned_ev'=>$row_ev->vehicle_name,'route_assigned_ev'=>$row_ev->route_name_ev,'update_time_ev'=>$row_ev->evening_update_time,'remark_assigned_ev'=>$row_ev->remark_ev,'edit_id'=>$row_ev->edit_id);	
    }
    return $data;
}

function getNumRowRouteAssignment2($aId,$vName,$status,$DbConnection)
{
	$query_check ="SELECT vehicle_name FROM route_assignment2 WHERE user_account_id='$aId' AND ".
	"vehicle_name='$vName' AND status=$status";
	//echo "<br>".$query_check;
	$result_check = mysql_query($query_check,$DbConnection);
	$numrows = mysql_num_rows($result_check);				
	return $numrows;				
}

function insertRoutAssignment2Detail($vName,$aId,$cId,$date,$status,$DbConnection)
{
	$query_string1="INSERT INTO route_assignment2(vehicle_name,user_account_id,create_id,create_date,status) VALUES".
	"('$vName',$aId,$cId,'$date',$status)";
	echo "<br>Q1=".$query_string1;
	$result_insert = mysql_query($query_string1, $DbConnection);
}

function updateERouteAssignment2($route_name,$remark,$date,$account_id,$local_account_id1,$vehicle_db,$status,$DbConnection)
{
	$query_update = "UPDATE route_assignment2 SET route_name_ev='$route_name', remark_ev='$remark', evening_update_time='$date', edit_id='$account_id',edit_date='$date' WHERE user_account_id='$local_account_id1' AND vehicle_name='$vehicle_db' AND status=$status";					
	//echo "Query=".$query_update."<br>";
	$result_update = mysql_query($query_update,$DbConnection);
}

function updateMRouteAssignment2($route_name,$remark,$date,$account_id,$local_account_id1,$vehicle_db,$status,$DbConnection)
{
	$query_update = "UPDATE route_assignment2 SET route_name_mor='$route_name', morning_update_time='$date', remark_mor='$remark', edit_id='$account_id',edit_date='$date' WHERE user_account_id='$local_account_id1' AND vehicle_name='$vehicle_db' AND status=1";					
	//echo"query_update=".$query_update."<br>";
	$result_update = mysql_query($query_update,$DbConnection);
}

function getDetailAllRouteAssignment2Next($account_id,$DbConnection)
{
    $query_assigned_mor = "SELECT vehicle_name,route_name_mor,remark_mor,morning_update_time,edit_id FROM route_assignment2 USE INDEX(rass2_aid_status) WHERE user_account_id='$account_id' AND status=1";	
    $result_assigned_mor = mysql_query($query_assigned_mor, $DbConnection);
    while($row_mor=mysql_fetch_object($result_assigned_mor))
    {
        /*$vname_assigned_mor = $row_mor->vehicle_name;
        $route_assigned_mor = $row_mor->route_name_mor;
        $update_time_mor = $row_mor->morning_update_time;
        $remark_assigned_mor = $row_mor->remark_mor;
        $edit_id = $row_mor->edit_id;*/
        $data[]=array('vname_assigned_mor'=>$row_mor->vehicle_name,'route_assigned_mor'=>$row_mor->route_name_mor,'update_time_mor'=>$row_mor->morning_update_time,'remark_assigned_mor'=>$row_mor->remark_mor,'edit_id'=>$row_mor->edit_id);	
    }
    return $data;
}

function getNumRowERouteAssignment2($aId,$vName,$rName,$remark,$status,$DbConnection)
{
	$query_check ="SELECT vehicle_name FROM route_assignment2 WHERE user_account_id='$aId' AND ".
	"vehicle_name='$vName' AND route_name_ev='$rName' AND remark_ev='$remark' AND status=$status";
	//echo "<br>".$query_check;
	$result_check = mysql_query($query_check,$DbConnection);
	$numrows_check = mysql_num_rows($result_check);
	return $numrows_check;
}

function getNumRowESSRouteAssignment2($aId,$vName,$rName,$status,$DbConnection)
{
	$query_check ="SELECT vehicle_name FROM route_assignment2 WHERE user_account_id='$aId' AND ".
	"vehicle_name='$vName' AND route_name_ev='$rName' AND status=$status";
	//echo "<br>".$query_check;
	$result_check = mysql_query($query_check,$DbConnection);
	$numrows_check = mysql_num_rows($result_check);
	return $numrows_check;
}

function getNumRowMSSRouteAssignment2($aId,$vName,$rName,$status,$DbConnection)
{
	$query_check ="SELECT vehicle_name FROM route_assignment2 WHERE user_account_id='$aId' AND ".
	"vehicle_name='$vName' AND route_name_mor='$rName' AND status=$status";
	//echo "<br>".$query_check;
	$result_check = mysql_query($query_check,$DbConnection);
	$numrows_check = mysql_num_rows($result_check);
}

function updateESSRouteAssignment2_a($route_name,$current_time,$account_id,$date,$parent_admin_id,$vehicle_db,$status,$DbConnection)
{
	$query_updatea = "UPDATE route_assignment2 SET route_name_ev='$route_name', evening_update_time='$current_time', edit_id='$account_id',edit_date='$date' WHERE user_account_id='$parent_admin_id' AND vehicle_name='$vehicle_db' AND status=$status";
	$result_updatea = mysql_query($query_updatea,$DbConnection);
}
function updateESSRouteAssignment2_b($route_name,$account_id,$date,$parent_admin_id,$vehicle_db,$status,$DbConnection)
{
	$query_updatea = "UPDATE route_assignment2 SET route_name_ev='$route_name',edit_id='$account_id',edit_date='$date' WHERE user_account_id='$parent_admin_id' AND vehicle_name='$vehicle_db' AND status=$status";						
	$result_updatea = mysql_query($query_updatea,$DbConnection);
}
function updateESSRouteAssignment2_c($current_time,$account_id,$date,$parent_admin_id,$vehicle_db,$status,$DbConnection)
{						
	$query_updatea = "UPDATE route_assignment2 SET edit_id='$account_id',edit_date='$date',evening_update_time=".
	"'$current_time' WHERE user_account_id='$parent_admin_id' AND vehicle_name='$vehicle_db' AND status=$status";
	$result_updatea = mysql_query($query_updatea,$DbConnection);
}

function updateMSSRouteAssignment2_a($route_name,$current_time,$account_id,$date,$parent_admin_id,$vehicle_db,$status,$DbConnection)
{
	$query_updatea = "UPDATE route_assignment2 SET route_name_mor='$route_name', morning_update_time='$current_time', edit_id='$account_id',edit_date='$date' WHERE user_account_id='$parent_admin_id' AND vehicle_name='$vehicle_db' AND status=$status";
	$result_updatea = mysql_query($query_updatea,$DbConnection);
}

function updateMSSRouteAssignment2_b($route_name,$account_id,$date,$parent_admin_id,$vehicle_db,$status,$DbConnection)
{
	$query_updatea = "UPDATE route_assignment2 SET route_name_mor='$route_name',edit_id='$account_id',edit_date='$date' WHERE user_account_id='$parent_admin_id' AND vehicle_name='$vehicle_db' AND status=$status";						
	$result_updatea = mysql_query($query_updatea,$DbConnection);
}

function updateMSSRouteAssignment2_c($current_time,$account_id,$date,$parent_admin_id,$vehicle_db,$status,$DbConnection)
{						
	$query_updatea = "UPDATE route_assignment2 SET edit_id='$account_id',edit_date='$date',morning_update_time=".
	"'$current_time' WHERE user_account_id='$parent_admin_id' AND vehicle_name='$vehicle_db' AND status=$status";
	$result_updatea = mysql_query($query_updatea,$DbConnection);
}


function getNumRowMRouteAssignment2($aId,$vName,$rName,$remark,$status,$DbConnection)
{
	$query_check ="SELECT vehicle_name FROM route_assignment2 WHERE user_account_id='$aId' AND ".
	"vehicle_name='$vName' AND route_name_mor='$rName' AND remark_mor='$remark' AND status=$status";
	//echo "<br>".$query_check;
	$result_check = mysql_query($query_check,$DbConnection);
	$numrows_check = mysql_num_rows($result_check);
	return $numrows_check;
}

/****************************************************************************************************************************/
/*******************************************************Vehicle Route Assignment1**********************************************/

function getDetailAllRouteAssingment2($account_id,$DbConnection)
{
    $query_assigned = "SELECT vehicle_name,route_name,remark FROM route_assignment2 USE INDEX(rass2_aid_status) WHERE user_account_id='$account_id' AND status=1";	
    $result_assigned = mysql_query($query_assigned,$DbConnection);
    while($row=mysql_fetch_object($result_assigned))
    {
        /*$vname_assigned = $row->vehicle_name;
        $route_assigned = $row->route_name;
        $remark_assigned = $row->remark;*/

        $data[]=array('vname_assigned'=>$row->vehicle_name,'route_assigned'=>$row->route_name,'remark_assigned'=>$row->remark);	
    }
    return $data;
}

function getDetailAllRouteAssingment3($account_id,$DbConnection)
{
	$query_assigned = "SELECT vehicle_name,route_name,remark FROM route_assignment2 WHERE user_account_id='$account_id' AND status=1";	
	$result_assigned = mysql_query($query_assigned,$DbConnection);
	while($row=mysql_fetch_object($result_assigned))
	{
		/*$vname_assigned = $row->vehicle_name;
		$route_assigned = $row->route_name;
		$remark_assigned = $row->remark;*/
		
	    $data[]=array('vname_assigned'=>$row->vehicle_name,'route_assigned'=>$row->route_name,'remark_assigned'=>$row->remark);	
	}
	return $data;
}
/****************************************************************************************************************************/

/***********************************************Manage Route Vehicle Substation Assingnment *********************************/
 function getDetailAllRouteAssignment2VSA($parent_admin_id,$DbConnection)
 {
	$query_assigned_ev = "SELECT vehicle_name,route_name_ev,remark_ev FROM route_assignment2 USE INDEX(rass2_aid_status) WHERE user_account_id='$parent_admin_id' AND status=1";	
	$result_assigned_ev = mysql_query($query_assigned_ev, $DbConnection);
	while($row_ev=mysql_fetch_object($result_assigned_ev))
	{
		/*$vname_assigned_ev = $row_ev->vehicle_name;
		$route_assigned_ev = $row_ev->route_name_ev;
		$remark_assigned_ev = $row_ev->remark_ev;*/
		
	    $data[]=array('vname_assigned_ev'=>$row->vehicle_name,'route_assigned_ev'=>$row->route_name_ev,'remark_assigned_ev'=>$row->remark_ev);	
	}
	return $data;
 }
 function getDetailAllRouteAssingment2VRR($parent_admin_id,$DbConnection)
 {
	$query_assigned_mor = "SELECT vehicle_name,route_name_mor,remark_mor FROM route_assignment2 USE INDEX(rass2_aid_status) WHERE user_account_id='$parent_admin_id' AND status=1";	
	//echo $query_assigned_mor."<BR>";
	$result_assigned_mor = mysql_query($query_assigned_mor, $DbConnection);
	while($row_mor=mysql_fetch_object($result_assigned_mor))
	{
		/*$vname_assigned_mor = $row_mor->vehicle_name;
		$route_assigned_mor = $row_mor->route_name_mor;
		$remark_assigned_mor = $row_mor->remark_mor;*/
		
		$data[]=array('vname_assigned_mor'=>$row->vehicle_name,'route_assigned_mor'=>$row->route_name_mor,'remark_assigned_mor'=>$row->remark_mor);	
	}	
 }
 function getNumRowSecVechicle($vehicle_id,$DbConnection)
 {
    $query="SELECT vehicle_id FROM secondary_vehicle USE INDEX(secveh_aid_status) WHERE vehicle_id='$vehicle_id' AND status='1'";
    $result=mysql_query($query,$DbConnection);
    $num_rows=mysql_num_rows($result);
    return $num_rows;
 }

/***************************************************************************************************************************/

/***********************************************Manage Route Vehicle Substation Inherit**************************************/
 function getDetailAllRouteAssignment2InheritEV($parent_admin_id,$DbConnection)
 {
		$query_db_route_vehicle_ev = "SELECT DISTINCT vehicle_name,route_name_ev,evening_update_time,remark_ev FROM route_assignment2 USE INDEX(rass2_aid_status) WHERE user_account_id='$parent_admin_id' and status=1";
		$result1 = mysql_query($query_db_route_vehicle_ev, $DbConnection);
		while($row1=mysql_fetch_object($result1))
		{
		  /*$vehicle_name=$row1->vehicle_name;
		  $evening_update_time=$row1->evening_update_time;
		  $route_name_ev=$row1->route_name_ev;*/
		  
		  $data[]=array('vehicle_name'=>$row->vehicle_name,'evening_update_time'=>$row->evening_update_time,'route_name_ev'=>$row->route_name_ev);	
	    }
	   return $data;
 }
 function getDetailAllRouteAssignment2InheritMR($parent_admin_id,$DbConnection)
 {
	$query_db_route_vehicle_mor = "SELECT DISTINCT vehicle_name,route_name_mor,morning_update_time,remark_mor FROM route_assignment2 USE INDEX(rass2_aid_status) WHERE user_account_id='$parent_admin_id'  and status=1";
	$result = mysql_query($query_db_route_vehicle_mor, $DbConnection);			
	while($row2=mysql_fetch_object($result))
	{
       /*$vehicle_name=$row2->vehicle_name;
	   $morning_update_time=$row2->morning_update_time;
	   $route_name_mor=$row2->route_name_mor;*/
		$data[]=array('vehicle_name'=>$row->vehicle_name,'morning_update_time'=>$row->morning_update_time,'route_name_mor'=>$row->route_name_mor);	
	}
   return $data;
 }
 function getNumRowVehicleStation($vehicle_id,$DbConnection)
 {
	$query="SELECT vehicle_id FROM station_assignment USE INDEX(stnass_vid_status) WHERE vehicle_id='$vehicle_id' AND status='1'";
	//echo "query=".$query;
	$result=mysql_query($query,$DbConnection);
	$num_rows=mysql_num_rows($result);
	return $num_row;
	
 }
/***************************************************************************************************************************/

/**********************************************manage station vehicle assingment ********************************************/
 function getDetailAllStationNext($common_id1,$DbConnection)
 {
        $query="SELECT station_id,station_name from station USE INDEX(stn_uaid_status) where user_account_id='$common_id1' AND status='1'";
        //echo "query=".$query."<br>";
        $result=mysql_query($query,$DbConnection);		
        while($row=mysql_fetch_object($result))
        {									
            /*$geo_id=$row->station_id;
            $geo_name=$row->station_name;*/
			
            $data[]=array('geo_id'=>$row->station_id,'geo_name'=>$row->station_name);	
        }
        return $data;						
 }
/***************************************************************************************************************************/


/****************************************manage substation vehicle assingment pre*******************************************/
function getDetailAllVehicleVG($account_id,$DbConnection)
{
	$query="SELECT vehicle.vehicle_id,vehicle.vehicle_name FROM vehicle INNER JOIN ".
                " vehicle_grouping USE INDEX(vg_accountid_status) ON vehicle_grouping.vehicle_id = vehicle.vehicle_id".
                " AND vehicle_grouping.account_id='$account_id' AND vehicle.status='1' ";
  	$result=mysql_query($query,$DbConnection);	
	while($row=mysql_fetch_object($result))
	{		
		/*$vehicle_id=$row->vehicle_id;	
		$vehicle_name=$row->vehicle_name;*/
		$data[]=array('vehicle_id'=>$row->vehicle_id,'vehicle_name'=>$row->vehicle_name);	
	}
	return $data;	
	
}
function getDetailAllAcIDUID($admin_id1,$DbConnection)
{
	$query = "SELECT account.account_id,account.user_id FROM account,account_detail USE INDEX(ad_acamdid) WHERE account.user_type='substation' AND account.status=1 and account.account_id=account_detail.account_id AND account_detail.account_admin_id='$admin_id1'";
	$result = mysql_query($query,$DbConnection);
	while($row = mysql_fetch_object($result))
	{
		/*$account_id_sub = $row->account_id;
		$user_id_sub = $row->user_id;*/
		$data[]=array('account_id_sub'=>$row->account_id,'user_id_sub'=>$row->user_id);	
	}
	return $data;	
}
function getNumRowEasAlertAssign($vehicle_id,$DbConnection)
{
	$query="SELECT vehicle_id FROM escalation_alert_assignment USE INDEX(esaass_vid_status) WHERE vehicle_id='$vehicle_id' AND status='1'";
	//echo "query=".$query;
	$result=mysql_query($query,$DbConnection);
	$num_rows=mysql_num_rows($result);
	return $num_rows;
}
/***************************************************************************************************************************/
function updateAlertAssignment($escalationS,$account_id,$date,$status,$DbConnection)
{
	$query="UPDATE alert_assignment SET status=0,edit_id='$account_id',edit_date='$date' WHERE ".
	"serial='$escalationS' AND status=$status";
	//echo "query=".$query;
	$result=mysql_query($query,$DbConnection); 
	return $result;
}
/*************************************manage substation vehicle deassingmnet*********************************************/
function getDetailAllAcIDUIDNext($admin_id1,$DbConnection)
{
	$query ="SELECT account.account_id,account.user_id FROM account,account_detail USE INDEX(ad_acamdid) WHERE account.user_type='substation' AND account.status=1 and account.account_id=account_detail.account_id AND account_detail.account_admin_id='$admin_id1'";
	$result = mysql_query($query,$DbConnection);	
	while($row = mysql_fetch_object($result))
	{
		/*$account_id_sub = $row->account_id;
		$user_id_sub = $row->user_id;*/
		
		$data[]=array('account_id_sub'=>$row->account_id,'user_id_sub'=>$row->user_id);	
	}
	return $data;	
}
/***************************************************************************************************************************/
/*******************************************manage add raw milk admin type***************************************************/
function getDetailAllCustomerNoStationNameStation($account_id,$DbConnection)
{
	$query_plant = "SELECT customer_no,station_name FROM station USE INDEX(stn_type_uaid_status) WHERE type=1 AND user_account_id='$account_id' AND status=1";
	$result_query = mysql_query($query_plant,$DbConnection);
	while($row=mysql_fetch_object($result_query))
	{
		//$final_plant_list=$row->customer_no;
		//$final_plant_name_list=$row->station_name;
		
		$data[]=array('final_plant_list'=>$row->customer_no,'final_plant_name_list'=>$row->station_name);	
	}
	return $data;
}
function getUsertypeUserIDAccount($account_id_local,$DbConnection)
{
	$queryType="SELECT user_type,user_id from account WHERE account_id='$account_id_local'";
	$resultType=mysql_query($queryType,$DbConnection);
	$rowType=mysql_fetch_row($resultType);
	return $rowType;
}
function getDetailAllInvoiceMdrm($tid_p,$sno_p,$DbConnection)
{
	$queryPending = "SELECT * FROM invoice_mdrm USE INDEX(inmd_taid_sno) WHERE status=1 AND invoice_status=5 AND (vehicle_no is NULL OR vehicle_no='')".
	" AND transporter_account_id ='$tid_p' AND sno='$sno_p'";
	//echo $queryPending;
	$resultPending = mysql_query($queryPending,$DbConnection);
	while($rowPending = mysql_fetch_object($resultPending))
	{
		/*$LRNO = $rowPending->lorry_no;
		$Vehicle = $rowPending->vehicle_no;
		$Transporter =  $rowPending->transporter_account_id;
		$emailid = $rowPending->email;
		$mobileno = $rowPending->mobile;
		$drivername = $rowPending->driver_name;
		$drivermobile = $rowPending->driver_mobile;
		$qty = $rowPending->qty_kg;
		$fat_per = $rowPending->fat_percentage;
		$snf_per = $rowPending->snf_percentage;
		$fat_kg = $rowPending->fat_kg;
		$snf_kg = $rowPending->snf_kg;
		$milk_age = $rowPending->milk_age;
		$disp_time = $rowPending->dispatch_time;
		$target_time = $rowPending->target_time;
		$plant = $rowPending->plant;
		$chillplant = $rowPending->chilling_plant;
		$tankertype =  $rowPending->tanker_type;
		$sno_id = $rowPending->sno;*/
		
		$data[]=array('LRNO'=>$rowPending->lorry_no,'Vehicle'=>$rowPending->vehicle_no,'Transporter'=>$rowPending->transporter_account_id,'emailid'=>$rowPending->email,'mobileno'=>$rowPending->mobile,'drivername'=>$rowPending->driver_name,'drivermobile'=>$rowPending->driver_mobile,'qty'=>$rowPending->qty_kg,'fat_per'=>$rowPending->fat_percentage,'snf_per'=>$rowPending->snf_percentage,'fat_kg'=>$rowPending->fat_kg,'snf_kg'=>$rowPending->snf_kg,'milk_age'=>$rowPending->milk_age,'disp_time'=>$rowPending->dispatch_time,'target_time'=>$row->target_time, 'plant'=>$rowPending->plant,'chillplant'=>$row->chilling_plant,'tankertype'=>$rowPending->tanker_type,'sno_id'=>$rowPending->sno);	
	}
	return $data;
 
}
function getDetailAllInvoiceMdrmNext($DbConnection)
{
	$queryPending = "SELECT * FROM invoice_mdrm USE INDEX(inmd_status_istatus) WHERE status=1 AND invoice_status=5";
	$resultPending = mysql_query($queryPending,$DbConnection);
	while($rowPending = mysql_fetch_object($resultPending))
	{
		/*$LRNO = $rowPending->lorry_no;
		$Vehicle = $rowPending->vehicle_no;
		$Transporter =  $rowPending->transporter_account_id;
		$emailid = $rowPending->email;
		$mobileno = $rowPending->mobile;
		$drivername = $rowPending->driver_name;
		$drivermobile = $rowPending->driver_mobile;
		$qty = $rowPending->qty_kg;
		$fat_per = $rowPending->fat_percentage;
		$snf_per = $rowPending->snf_percentage;
		$fat_kg = $rowPending->fat_kg;
		$snf_kg = $rowPending->snf_kg;
		$milk_age = $rowPending->milk_age;
		$disp_time = $rowPending->dispatch_time;
		$target_time = $rowPending->target_time;
		$plant = $rowPending->plant;
		$chillplant = $rowPending->chilling_plant;
		$tankertype =  $rowPending->tanker_type;
		$sno_id = $rowPending->sno;*/
		
		$data[]=array('LRNO'=>$rowPending->lorry_no,'Vehicle'=>$rowPending->vehicle_no,'Transporter'=>$rowPending->transporter_account_id,'emailid'=>$rowPending->email,'mobileno'=>$rowPending->mobile,'drivername'=>$rowPending->driver_name,'drivermobile'=>$rowPending->driver_mobile,'qty'=>$rowPending->qty_kg,'fat_per'=>$rowPending->fat_percentage,'snf_per'=>$rowPending->snf_percentage,'fat_kg'=>$rowPending->fat_kg,'snf_kg'=>$rowPending->snf_kg,'milk_age'=>$rowPending->milk_age,'disp_time'=>$rowPending->dispatch_time,'target_time'=>$row->target_time, 'plant'=>$rowPending->plant,'chillplant'=>$row->chilling_plant,'tankertype'=>$rowPending->tanker_type,'sno_id'=>$rowPending->sno);	
	}
	return $data;
 
}
/***************************************************************************************************************************/
/**************************************************Manage Add Rwa Milk usertype Approve*************************************/
function getDetailAllCustomerNoTCPA($self_child_transporter_id,$DbConnection)
{
	$query_default_chillplant_supplier = "SELECT customer_no FROM transporter_chilling_plant_assignment USE INDEX(tcpass_accid_status) WHERE account_id IN($self_child_transporter_id) AND status=1";
	//echo $query_default_chillplant_supplier."<br>";
	$query_default_chillplant_supplier = mysql_query($query_default_chillplant_supplier,$DbConnection);
	while($rowchillA=mysql_fetch_object($query_default_chillplant_supplier))
	{
		//$default_customer_no_supplier[] = $rowchillA->customer_no;		
		$data[]=array('default_customer_no_supplier'=>$rowchillA->customer_no);	
	}
	return $data;
}
function getCustomerNoCustNameStation($conditionStr,$DbConnection)
{
	$query_chillplant = "SELECT customer_no,station_name FROM station USE INDEX(stn_type_uaid_status) WHERE type=2 AND ($conditionStr) AND status=1";
	//echo $query_chillplant;
	$result_chillquery = mysql_query($query_chillplant,$DbConnection);
	while($rowchill=mysql_fetch_object($result_chillquery))
	{
		//$final_chillplant_list[]=$rowchill->customer_no;
		//$final_chillplant_name_list[]=$rowchill->station_name;
		$data[]=array('final_chillplant_list'=>$rowchill->customer_no,'final_chillplant_name_list'=>$rowchill->station_name);	
	}
	return $data;
}
function getDetailAllInvoiceMdrmNextAP($account_id,$DbConnection)
{
	$queryPending = "SELECT * FROM invoice_mdrm USE INDEX(inmd_status_istatus) WHERE status=1 AND invoice_status=5 AND (vehicle_no is NULL OR vehicle_no='') ".
					"AND transporter_account_id IN ($account_id) ";
	$resultPending = mysql_query($queryPending,$DbConnection);
	while($rowPending = mysql_fetch_object($resultPending))
	{
		/*$LRNO = $rowPending->lorry_no;
		$Vehicle = $rowPending->vehicle_no;
		$Transporter =  $rowPending->transporter_account_id;
		$emailid = $rowPending->email;
		$mobileno = $rowPending->mobile;
		$drivername = $rowPending->driver_name;
		$drivermobile = $rowPending->driver_mobile;
		$qty = $rowPending->qty_kg;
		$fat_per = $rowPending->fat_percentage;
		$snf_per = $rowPending->snf_percentage;
		$fat_kg = $rowPending->fat_kg;
		$snf_kg = $rowPending->snf_kg;
		$milk_age = $rowPending->milk_age;
		$disp_time = $rowPending->dispatch_time;
		$target_time = $rowPending->target_time;
		$plant = $rowPending->plant;
		$chillplant = $rowPending->chilling_plant;
		$tankertype =  $rowPending->tanker_type;
		$sno_id = $rowPending->sno;*/
		
		$data[]=array('LRNO'=>$rowPending->lorry_no,'Vehicle'=>$rowPending->vehicle_no,'Transporter'=>$rowPending->transporter_account_id,'emailid'=>$rowPending->email,'mobileno'=>$rowPending->mobile,'drivername'=>$rowPending->driver_name,'drivermobile'=>$rowPending->driver_mobile,'qty'=>$rowPending->qty_kg,'fat_per'=>$rowPending->fat_percentage,'snf_per'=>$rowPending->snf_percentage,'fat_kg'=>$rowPending->fat_kg,'snf_kg'=>$rowPending->snf_kg,'milk_age'=>$rowPending->milk_age,'disp_time'=>$rowPending->dispatch_time,'target_time'=>$row->target_time, 'plant'=>$rowPending->plant,'chillplant'=>$row->chilling_plant,'tankertype'=>$rowPending->tanker_type,'sno_id'=>$rowPending->sno);	
	}
	return $data;
 
}
/***************************************************************************************************************************/
/***********************************************manage add raw milk usertype approve1*********************************************/
function getDetailAdminIDArray($child_acc,$DbConnection)
{
	$query2 = "SELECT account_detail.admin_id FROM account_detail,account WHERE account.account_id='$child_acc' AND account.status=1 AND account.account_id=account_detail.account_id";
	$result2 = mysql_query($query2,$DbConnection);
	if($row2 = mysql_fetch_object($result2))
	{
	   $admin_id_child = $row2->admin_id;	  
	   $data[]=array('admin_id_child '=>$row2->admin_id);	
	}
	return $data;
}
function getDetailAllPUA($account_id,$DbConnection)
{
	$query_plant="SELECT * FROM plant_user_assignment USE INDEX(puass_accid_status) WHERE status=1 AND  account_id='$account_id'";
	//echo "query=".$query_plant."<br>";
	$result_plant = mysql_query($query_plant,$DbConnection);
	while($row_plant = mysql_fetch_object($result_plant))
	{
		//$plant_customer_no=$row_plant->plant_customer_no;
		$data[]=array('plant_customer_no '=>$row_plant->plant_customer_no);	
	}
	return $data;
}
function getDetailAllInvoiceMdrmAP1($plant_in,$DbConnection)
{
		$queryPending = "SELECT * FROM invoice_mdrm INDEX(inmd_status_istatus) WHERE status=1 AND invoice_status=5 AND (vehicle_no is NULL OR vehicle_no='')".
		" AND ($plant_in)";
		//echo $queryPending;
		$resultPending = mysql_query($queryPending,$DbConnection);
		while($rowPending = mysql_fetch_object($resultPending))
		{
			/*$LRNO = $rowPending->lorry_no;
			$Vehicle = $rowPending->vehicle_no;
			$Transporter =  $rowPending->transporter_account_id;
			$emailid = $rowPending->email;
			$mobileno = $rowPending->mobile;
			$drivername = $rowPending->driver_name;
			$drivermobile = $rowPending->driver_mobile;
			$qty = $rowPending->qty_kg;
			$fat_per = $rowPending->fat_percentage;
			$snf_per = $rowPending->snf_percentage;
			$fat_kg = $rowPending->fat_kg;
			$snf_kg = $rowPending->snf_kg;
			$milk_age = $rowPending->milk_age;
			$disp_time = $rowPending->dispatch_time;
			$target_time = $rowPending->target_time;
			$plant = $rowPending->plant;
			$chillplant = $rowPending->chilling_plant;
			$tankertype =  $rowPending->tanker_type;
			$sno_id = $rowPending->sno;*/
			
			$data[]=array('LRNO'=>$rowPending->lorry_no,'Vehicle'=>$rowPending->vehicle_no,'Transporter'=>$rowPending->transporter_account_id,'emailid'=>$rowPending->email,'mobileno'=>$rowPending->mobile,'drivername'=>$rowPending->driver_name,'drivermobile'=>$rowPending->driver_mobile,'qty'=>$rowPending->qty_kg,'fat_per'=>$rowPending->fat_percentage,'snf_per'=>$rowPending->snf_percentage,'fat_kg'=>$rowPending->fat_kg,'snf_kg'=>$rowPending->snf_kg,'milk_age'=>$rowPending->milk_age,'disp_time'=>$rowPending->dispatch_time,'target_time'=>$row->target_time, 'plant'=>$rowPending->plant,'chillplant'=>$row->chilling_plant,'tankertype'=>$rowPending->tanker_type,'sno_id'=>$rowPending->sno);	
		}
		return $data;
		
}
function getDetailAllAccountIdAccountAdIdAccount($admin_id,$DbConnection)
{
    $query = "SELECT account_id FROM account_detail USE INDEX(ad_acamdid) where account_admin_id='$admin_id'";
    //echo "<br>CHILD_ACC=".$query;
    $result = mysql_query($query,$DbConnection);
    while($row = mysql_fetch_object($result))
    {
            //$child_acc = $row->account_id;
            $data[]=array('child_acc '=>$row->account_id);	
    }
    return $data;
}
/********************************************************************************************************************************/
////////////// hierarchy class/////////

function getHierarchyAccountId($tmp_admin_id,$DbConnection)
{
    $query = "SELECT account_id FROM account_detail WHERE account_admin_id='$tmp_admin_id'";
    $result=mysql_query($query,$DbConnection);
	$numRows=mysql_num_rows($result);
	if($numRows>0)
	{
		while($row=mysql_fetch_object($result))
		{									
			/*$location_id=$row->location_id;
			$location_name=$row->location_name;*/
			$data[]=array('account_id'=>$row->account_id);	
		}
	  return $data;
	}
	else
	{
		$data[]=array();
		return $data;
	}
  
}

function getHierarchyStatusUsertype($getaccountid,$DbConnection)
{
    $query="SELECT status,user_type FROM account WHERE account_id='$getaccountid'";
    $result=mysql_query($query,$DbConnection);
    $row=mysql_fetch_row($result); 
    return $row;
}

function getGroupGroupIdGroupName($account_id,$DbConnection)
{
    $query= "SELECT `group`.group_id,`group`.group_name FROM `group`,account USE INDEX(accountid_status) WHERE `group`.group_id=".
            "account.group_id AND `group`.status=1 AND account.account_id=".
            "$account_id AND account.status=1";
	//echo "Query=".$query."<br>";
    $result=mysql_query($query,$DbConnection);
    $row=mysql_fetch_row($result);
    return $row;
} 
 function getAccountDetailSingleRow($account_id,$DbConnection)
{
    $query= "select name,admin_id,account_admin_id,create_id from account_detail USE INDEX(ad_account_id) WHERE account_id = '$account_id'";
    //echo $query.'<BR>';
    $result=mysql_query($query,$DbConnection);               
    $row=mysql_fetch_row($result);
    return $row;
}

 function getVehicleIdByAccountId($account_id,$DbConnection)
{
    $query = "SELECT vehicle_id FROM vehicle_grouping USE INDEX (vg_accountid_status) WHERE account_id = '$account_id' AND status=1";
    $result=mysql_query($query,$DbConnection);
    $num_rows1=mysql_num_rows($result);
    if($num_rows1>0)
    {
        while ($row=mysql_fetch_object($result))
        { 
            $data[]=array('vehicle_id'=>$row->vehicle_id);
        }
    }
    else
    {
        $data=array();
    }
    return $data;
}
function getVehicleTableData($vehicle_id,$DbConnection,$j)
{
    $query_test1 = "SELECT vehicle.vehicle_id,vehicle.vehicle_name,vehicle.vehicle_type,vehicle.category,vehicle.vehicle_tag,vehicle.vehicle_number,".
    "vehicle.max_speed,vehicle.fuel_voltage,vehicle.tank_capacity,vehicle_assignment.device_imei_no FROM vehicle ".
    "USE INDEX (v_vehicleid_status),vehicle_assignment USE INDEX (va_vehicleid_status) WHERE".
    " vehicle.vehicle_id=vehicle_assignment.vehicle_id AND ( ";
    $join_query="";
    for($k=0;$k<$j;$k++)
    {
        if($k==($j-1))
        {        
            $join_query=$join_query." vehicle.vehicle_id='$vehicle_id[$k]'";
        }
        else
        { 
            $join_query=$join_query." vehicle.vehicle_id='$vehicle_id[$k]' OR";
        }  
    }
    $query_test=$query_test1.$join_query.") AND vehicle.status=1 AND vehicle_assignment.status=1";
    //echo "<br>".$query_test;
    $result_test=mysql_query($query_test,$DbConnection);
    while ($row_1=mysql_fetch_object($result_test))
    {
      $data[]=array('vehicle_id'=>$row_1->vehicle_id,
            'device_imei_no'=>$row_1->device_imei_no,
            'vehicle_name'=>$row_1->vehicle_name,
            'vehicle_type'=>$row_1->vehicle_type,
            'category'=>$row_1->category,
            'vehicle_tag'=>$row_1->vehicle_tag,
            'vehicle_number'=>$row_1->vehicle_number,
            'device_imei_no'=>$row_1->device_imei_no,
            'max_speed'=>$row_1->max_speed,
            'fuel_voltage'=>$row_1->fuel_voltage,
            'tank_capacity'=>$row_1->tank_capacity);
    }
    return $data;
}

function getFMFeatureName($ioStr,$DbConnection)
{
	//echo "ioSTr=".$ioStr."<br>";
    $ioStr1=explode(",",$ioStr);
    $feature_names="";
    $featureIdStr="";
    if(count($ioStr1)>0 && $ioStr!="")
    {
        $ioStrSize=sizeof($ioStr1);
        for($i=0;$i<$ioStrSize;$i++)
        {
            $featureIdStr=$featureIdStr."feature_id=$ioStr1[$i] OR ";
        }
        $featureIdStr=substr($featureIdStr,0,-3);
        $query_fm = "SELECT feature_name FROM feature_mapping USE INDEX(fm_featureId_status) WHERE ($featureIdStr) AND status=1";
        //echo "query_fm=".$query_fm."<br>";
        $result_fm=mysql_query($query_fm,$DbConnection);

        while($row_fm=mysql_fetch_object($result_fm))
        {
            $feature_names=$feature_names.$row_fm->feature_name.",";
        }
        $feature_names=substr($feature_names,0,-1);
        //echo "feature_name=".$feature_names."<br>"; 
    }
	else 
	{
		$feature_names="";
	}
    return $feature_names;
}
function getIoAssignmentData($feature_names,$device_imei_no_local,$DbConnection)
{
    $query_iovalue = "SELECT ".$feature_names." FROM io_assignment USE INDEX (ioa_vehicle_id_status),".
    "vehicle_assignment USE INDEX (va_vehicleid_imei_status) WHERE io_assignment.vehicle_id=vehicle_assignment".
    ".vehicle_id AND vehicle_assignment.device_imei_no='$device_imei_no_local' AND vehicle_assignment.status=1".
    " AND io_assignment.status=1";
    $result_iovalue=mysql_query($query_iovalue,$DbConnection);
    $feature_names1="";
    $tmpIoTypeArr=array();
    reset($tmpIoTypeArr);
    while($row_iovalue=mysql_fetch_object($result_iovalue))
    {
        //echo "feature_names=".$feature_names."<br>";
        $feature_names1=explode(",",$feature_names);
        $final_iotypevalue_str="";
        for($i=0;$i<sizeof($feature_names1);$i++)
        {
            if($row_iovalue->$feature_names1[$i]!="")
            {
                $final_iotypevalue_str=$final_iotypevalue_str.$row_iovalue->$feature_names1[$i]."^".$feature_names1[$i].":";								
            }						
        }
        $final_iotypevalue_str=substr($final_iotypevalue_str,0,-1);
        $tmpIoTypeArr[$device_imei_no_local]=$final_iotypevalue_str;                                    
    }
    return $tmpIoTypeArr;
}
//////////// end of hierarchy class ////////
///////////// end of excalation ////////////

function getMysqlBegin()
{
	mysql_query("BEGIN");
}
function getMysqlCommit()
{
	mysql_query("COMMIT");
}
function getMysqlRollback()
{
	mysql_query("ROLLBACK");
}
?>

