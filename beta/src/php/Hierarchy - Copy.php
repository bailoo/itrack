<?php 
include("tree.php");
include("coreDb.php");  
class Hierarchy 
{ 
	public function GetHierarchy($groupid,$account_id,$accountUserType,$admin_id,$user_type,$DbConnection) 
	{
		//--code updated on 30032015 for third party---//
		$admin_account_id_array=array();
		global $admin_account_id_array;
		//---------------------------------------------//
		$Root = null;  
		$Root = $this->CreateTree($account_id,$accountUserType,$DbConnection);
		//==Updated Code 28032015 for Third Party and not call recursive======//
		//$this->AddThirdParty(&$Root,$account_id,$accountUserType,$DbConnection);		
		//print_r($Root);
		//==xxxxxxxxxxxxxxxxxxxxx======//
		return $Root;
	}

	public function CreateTree($account_id,$accountUserType,$DbConnection)
	{ 
		//echo "test";
		$count=0;
		//echo 'A1'.'<BR>';
		//--code updated on 30032015 for third party---//
		global $admin_account_id_array;
		$admin_account_id_array[]=$account_id;
		//---------------------------------------------//
		$AccountInfo = $this->getaccountinfo($account_id,$accountUserType,$DbConnection);
		//echo 'A2'.'<BR>';
		$tree_obj= new Tree($AccountInfo);   
		$DEBUG=0;
		if($DEBUG==1)
		{	
			if($AccountInfo -> VehicleCnt==0)
			{
				echo"<table border=1 cellspacing=3 cellpadding=3>
				<tr> 
				<td>AccountID</td>
				<td>AccountName</td>
				<td>VehicleGroupID</td> 
				<td>AccountHierarchyLevel</td>            
				</tr>";
				echo"<tr>";  
				echo"<td>".$tree_obj->data->AccountID."</td>";
				echo"<td>".$tree_obj->data->AccountName."</td>";        
				echo"<td>".$tree_obj->data->VehicleGroupID."</td>";
				echo"<td>".$tree_obj->data->AccountHierarchyLevel."</td>";
				echo"</tr>
				</table><br>";
			}
			else if($AccountInfo -> VehicleCnt!=0)
			{
				$cnt=$AccountInfo -> VehicleCnt;
				echo"<table border=1 cellspacing=3 cellpadding=3>
				<tr> 
				<td>AccountID</td>
				<td>AccountName</td>
				<td>VehicleGroupID</td>
				<td>VehicleCount</td>
				<td>DeviceIMEINo</td>
				<td>VehicleID</td>
				<td>VehicleName</td>
				<td>VehicleType</td>
				<td>VehicleTag</td>
				</tr>";

				for($v_c=0;$v_c<$cnt;$v_c++)
				{      
					echo"<tr>";  
					echo"<td>".$tree_obj->data->AccountID."</td>";
					echo"<td>".$tree_obj->data->AccountName."</td>";        
					echo"<td>".$tree_obj->data->VehicleGroupID."</td>";
					echo"<td>".$tree_obj ->data->DeviceIMEINo[$v_c]."</td>";
					echo"<td>".$tree_obj ->data->VehicleCnt."</td>";          
					echo"<td>".$tree_obj ->data->VehicleID[$v_c]."</td>";
					echo"<td>".$tree_obj ->data->VehicleName[$v_c]."</td>";
					echo"<td>".$tree_obj ->data->VehicleType[$v_c]."</td>";
					echo"<td>".$tree_obj ->data->VehicleTag[$v_c]."</td>";        
					echo"</tr>";     
				}    
				echo "</table><br>";    
			}
		}  	
		$tmp_admin_id =   $AccountInfo->AdminID;
		$accountIdData=getHierarchyAccountId($tmp_admin_id,$DbConnection);	
        //if(count($accountIdData)>0)
		{		
			foreach($accountIdData as $accId)
			{
				$getaccountid=$accId['account_id'];  
				$accSingleRowData=getHierarchyStatusUsertype($getaccountid,$DbConnection);		
				if($accSingleRowData[0]==1) /// for account status
				{
					$accountUserType=$accSingleRowData[1]; // for account user type
					$tmp = $getaccountid;  
					$tree_obj -> child[$tree_obj -> ChildCnt] = null;         
					$tree_obj -> child[$tree_obj -> ChildCnt] = $this->CreateTree($tmp,$accountUserType,$DbConnection);					
					/*if (!is_object($tree_obj -> child[$tree_obj -> ChildCnt])) 
					{
						$tree_obj -> child[$tree_obj -> ChildCnt] = new stdClass;
					}*/
					$tree_obj -> child[$tree_obj -> ChildCnt]->parents = $tree_obj;  	
					$tree_obj -> ChildCnt++;
				
				}
			}
			return $tree_obj;
		}		
	}
	
	public function AddThirdParty($Root,$account_id,$accountUserType,$DbConnection)	
	{
		
		//==Query to get unique source account ===//
		$querySourceAccount = "SELECT distinct admin_account_id FROM third_party_account_assignment WHERE third_party_account_id='$account_id' AND status=1";
		//echo $querySourceAccount;
		$resultSourceAccount=mysql_query($querySourceAccount,$DbConnection);
		$num_rows_SourceAccount=mysql_num_rows($resultSourceAccount);
		if($num_rows_SourceAccount!=0)
		{
			while ($rowSourceAccount=mysql_fetch_object($resultSourceAccount))
			{
				$admin_account_id_source=$rowSourceAccount->admin_account_id;
				$status_account=1;
				$status_account = $this->checkaccountIDTree($admin_account_id_source);
				//echo "chk=".$status_account;
				if($status_account==0)
				{
				
					/////
					$query2 = "SELECT status,user_type FROM account WHERE account_id='$admin_account_id_source'";
					//echo"<br>".$query2;
					$result2=mysql_query($query2,$DbConnection);
					$row2=mysql_fetch_object($result2);
					if($row2->status==1)
					{
						//echo"<br>".$row2->user_type;
						$admin_account_id_destination=$account_id;
						$accountUserType=$row2->user_type; 
						$AccountInfo = $this->getaccountinfo_thirdparty($admin_account_id_source,$admin_account_id_destination,$accountUserType,$DbConnection);
						
						//$Root -> child[$Root->ChildCnt]->data = $AccountInfo;
						//$Root -> child[$Root->ChildCnt]->parents = $Root;
						//$Root -> child[$Root->ChildCnt]->ChildCnt = 0;
						
						
						
						$tree_obj= new Tree($AccountInfo);	
						$Root -> child[$Root->ChildCnt] = null;
						$Root -> child[$Root->ChildCnt] = $tree_obj;						
						$Root -> child[$Root -> ChildCnt]->parents = $Root; 
						$Root -> ChildCnt++;
						unset($tree_obj);
						
						//$c = (object)array_merge((array)$Root, (array)$Root1);
						//print_r($Root);
					}					
				}
			}
			//return $tree_obj;
			
		}
	}
	public function checkaccountIDTree($admin_account_id_source)
	{
		//echo $admin_account_id_source;
		global $admin_account_id_array;
		//print_r($admin_account_id_array);
		if(in_array($admin_account_id_source,$admin_account_id_array))
		{ 
			//echo "t";
			return 1; 
		}
		else
		{
			//echo "f";
			 return 0;
			
		 }
		
	}
	
	public function getaccountinfo_thirdparty($account_id,$admin_account_id_destination,$accountUserType,$DbConnection)
	{
		
		$AccountInfo = new Info();
		$AccountInfo -> AccountTypeThirdParty = 1;
		$AccountInfo -> AccountHierarchyLevel = 0;
		$AccountInfo -> AccountUserType = $accountUserType;
		$AccountInfo -> AccountGroupID = '';
		$AccountInfo -> AccountGroupName = '';
		$AccountInfo -> AccountType = '';
		$query= "select * from account_detail where account_id = '$account_id'";		
		//echo"<br>".$query;
		$result=mysql_query($query,$DbConnection);
		$num_rows=mysql_num_rows($result);
		$row=mysql_fetch_object($result);
		$name_local=$row->name."(3rd Party)";
		$admin_id_local=$row->admin_id;
		$create_id_local=$row->create_id;
		$vehicle_group_id_local=$row->vehicle_group_id;
		$AccountInfo -> AccountID = $account_id;
		$AccountInfo -> AccountName = $name_local;
		$AccountInfo -> AdminID = $admin_id_local;
		$AccountInfo ->AccountAdminID=$row->account_admin_id;
		$AccountInfo -> AccountCreateID = $create_id_local;	
		
		$QueryThirdParty="SELECT * FROM third_party_vehicle_assignment WHERE third_party_account_id='$admin_account_id_destination' AND admin_account_id='$account_id' AND status=1 ";
		//echo $QueryThirdParty;
		$ResultThirdParty=mysql_query($QueryThirdParty,$DbConnection);
		$num_rows_ThirdParty=mysql_num_rows($ResultThirdParty);
		if($num_rows_ThirdParty!=0)
		{			
			$j=0;		
			while ($rowThirdParty=mysql_fetch_object($ResultThirdParty))
			{
				$vehicle_id[$j]=$rowThirdParty ->vehicle_id;
				//echo"<br>".$vehicle_id[$j];
				$vehicle_date_from_third_party[$vehicle_id[$j]]=$rowThirdParty ->create_date;				
				$j++;   
			}
		}
		$query_test1 = "SELECT vehicle.vehicle_id,vehicle.vehicle_name,vehicle.vehicle_type,vehicle.category,vehicle.vehicle_tag,vehicle.vehicle_number,".
		"vehicle.max_speed,vehicle.fuel_voltage,vehicle.tank_capacity,vehicle_assignment.device_imei_no FROM vehicle ".
		"USE INDEX (v_vehicleid_status),vehicle_assignment USE INDEX (va_vehicleid_status) WHERE".
		" vehicle.vehicle_id=vehicle_assignment.vehicle_id AND ( ";
		$join_query="";
		if($j!=0)
		{
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
			// echo "<br>".$query_test;
			$result_test=mysql_query($query_test,$DbConnection);
			$v_count=0;			
			while ($row_1=mysql_fetch_object($result_test))
			{
				$vehicle_id_local=$row_1->vehicle_id;
				$vehicle_name_local=$row_1->vehicle_name;
				$vehicle_type_local=$row_1->vehicle_type;
				$vehicle_category=$row_1->category;
				$vehicle_tag=$row_1->vehicle_tag;
				$vehicle_number=$row_1->vehicle_number;
				$device_imei_no_local=$row_1->device_imei_no;
				// echo "vehicle_id=".$vehicle_id_local."vehicle_name=".$vehicle_name_local."vehicle_type=".$vehicle_type_local."vehicle_tag=".$vehicle_tag." device_imei_no=".$device_imei_no_local."<br>";
				$AccountInfo -> VehicleID[$AccountInfo -> VehicleCnt] = $vehicle_id_local;
				$AccountInfo -> VehicleName[$AccountInfo -> VehicleCnt] = $vehicle_name_local;
				$AccountInfo -> VehicleType[$AccountInfo -> VehicleCnt] = $vehicle_type_local;
				$AccountInfo -> VehicleCategory[$AccountInfo -> VehicleCnt] = $vehicle_category;
				$AccountInfo -> VehicleTag[$AccountInfo -> VehicleCnt] = $vehicle_tag;
				$AccountInfo -> VehicleNumber[$AccountInfo -> VehicleCnt] = $vehicle_number;
				$AccountInfo -> VehicleMaxSpeed[$AccountInfo -> VehicleCnt] = $row_1->max_speed;
				$AccountInfo -> VehicleFuelVoltage[$AccountInfo -> VehicleCnt] = $row_1->fuel_voltage;
				$AccountInfo -> VehicleTankCapacity[$AccountInfo -> VehicleCnt] = $row_1->tank_capacity;
				$AccountInfo -> DeviceIMEINo[$AccountInfo -> VehicleCnt] = $device_imei_no_local;   
				///updated code here 27032015///
				$AccountInfo -> VehicleTypeThirdParty[$AccountInfo -> VehicleCnt] = 1;
				$AccountInfo -> VehicleActiveDate[$AccountInfo -> VehicleCnt] = $vehicle_date_from_third_party[$vehicle_id_local];
								
				$query_io = "SELECT io FROM device_manufacturing_info USE INDEX (dmi_device_imei_status) WHERE device_imei_no='$device_imei_no_local' AND status=1";
				//echo "query_io=".$query_io."<br>";
				$result_io=mysql_query($query_io,$DbConnection);
				$row_io=mysql_fetch_row($result_io);
				//echo "row=".$row_io[0]."<br>";
				$query_fm = "SELECT feature_name FROM feature_mapping WHERE feature_id IN ($row_io[0]) AND status=1";
				//echo "query_fm=".$query_fm."<br>";
				$result_fm=mysql_query($query_fm,$DbConnection);
				$feature_names="";
				while($row_fm=mysql_fetch_object($result_fm))
				{
					$feature_names=$feature_names.$row_fm->feature_name.",";
				}
				$feature_names=substr($feature_names,0,-1);
				//echo "feature_name=".$feature_names."<br>";
				if($feature_names!="")
				{
					$query_iovalue="";
					$result_iovalue="";
					$row_iovalue="";
					$query_iovalue = "SELECT ".$feature_names." FROM io_assignment USE INDEX (ioa_vehicle_id_status),".
					"vehicle_assignment USE INDEX (va_vehicleid_imei_status) WHERE io_assignment.vehicle_id=vehicle_assignment".
					".vehicle_id AND vehicle_assignment.device_imei_no='$device_imei_no_local' AND vehicle_assignment.status=1".
					" AND io_assignment.status=1";
					/*if($account_id=='2' && $device_imei_no_local=='862170011629704')
					{
						echo "query=".$query_iovalue."<br>";
					}*/
					$result_iovalue=mysql_query($query_iovalue,$DbConnection);
					$feature_names1="";
					$tmp_arrrrr=array();
					reset($tmp_arrrrr);
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
							$tmp_arrrrr[$device_imei_no_local]=$final_iotypevalue_str;
							$AccountInfo -> DeviceIOTypeValue[$AccountInfo -> VehicleCnt]=$tmp_arrrrr;
					}
					/*foreach($AccountInfo -> DeviceIOTypeValue[$AccountInfo -> VehicleCnt] as $key=>$value)
					{
						echo "io=".$key."type11=".$tmp_arrrrr[$key]."<br>";
					}*/
					//print_r($AccountInfo -> DeviceIOTypeValue[$AccountInfo -> VehicleCnt]);
				}
				$AccountInfo -> VehicleCnt++;								
			}    
		}
	
		return $AccountInfo;
	}
	
	
	public function getaccountinfo($account_id,$accountUserType,$DbConnection)
	{
            $AccountInfo = new Info();		
            $AccountInfo -> AccountTypeThirdParty = 0;
            //--------------------------------------------//
            $AccountInfo -> AccountHierarchyLevel = 0;		
            $AccountInfo -> AccountUserType = $accountUserType;
            //echo 'A3'.'<BR>';
            $grpSingleRowData=getGroupGroupIdGroupName($account_id,$DbConnection);
            
                 
            $AccountInfo -> AccountGroupID = $grpSingleRowData[0];
            $AccountInfo -> AccountGroupName = $grpSingleRowData[1];
		
            //$query= "SELECT * FROM account WHERE account_id = '$account_id' AND STATUS =1";
           
            $user_type_id_local=getUserTypeIdAccountFeature($account_id,$DbConnection);
            $AccountInfo -> AccountType = $user_type_id_local;		
            $accountDetailDetailArr=getAccountDetailSingleRow($account_id,$DbConnection);
                      
            $AccountInfo -> AccountID = $account_id;
            $AccountInfo -> AccountName = $accountDetailDetailArr[0]; // column name
            $AccountInfo -> AdminID = $accountDetailDetailArr[1];     // colums admin_id
            $AccountInfo ->AccountAdminID=$accountDetailDetailArr[2];   // column account_admin_id
            $AccountInfo -> AccountCreateID = $accountDetailDetailArr[3]; // column create_id		
      
            while(@$num_rows1!=0)
            { 
                //echo 'A60'.'<BR>';
                $AccountInfo -> AccountHierarchyLevel++;
                $accountid = $accountDetailDetailArr[2];
                $num_rows1=getAccountIdByAdminId($accountid,$DbConnection);
                if($num_rows1==0)
                {
                    //echo 'A61'.'<BR>';
                    break;
                }                
                $accountid = $num_rows1;
                //result = select * from account_details where account_id = accountid;
                $num_rows1=getAccountDetailNumRows($accountid,$DbConnection);              
                if($num_rows1!=0)
                {
                //$row=mysql_fetch_object($result); 
                }
            }
            
            $vehicleIdData=getVehicleIdByAccountId($account_id,$DbConnection); 

			//print_r($vehicleIdData);
			
			//echo "<br><br>";
			
            $j=0;
            if(count($vehicleIdData)!=0)
            {
                foreach($vehicleIdData as $vehId)
		{
                    @$vehicle_id[$j]=$vehId['vehicle_id'];
                    $j++;   
                }
            }
            else
            {
                $num_rows1=0;
            }		
	//	echo 'A62'.'<BR>';
                
        //print_r($vehicle_id);
		
		if($j!=0)
		{                    
                    $vehicleDataArr=getVehicleTableData($vehicle_id,$DbConnection,$j); 
					if(count($vehicleDataArr)>0)
					{
						foreach($vehicleDataArr as $vehicleData)
						{
							$device_imei_no_local=$vehicleData['device_imei_no'];
							$AccountInfo -> VehicleID[$AccountInfo -> VehicleCnt] =$vehicleData['vehicle_id'];
							$AccountInfo -> VehicleName[$AccountInfo -> VehicleCnt] =$vehicleData['vehicle_name'];
							$AccountInfo -> VehicleType[$AccountInfo -> VehicleCnt] =$vehicleData['vehicle_type'];
							$AccountInfo -> VehicleCategory[$AccountInfo -> VehicleCnt] =$vehicleData['category'];
							$AccountInfo -> VehicleTag[$AccountInfo -> VehicleCnt] = $vehicleData['vehicle_tag'];
							$AccountInfo -> VehicleNumber[$AccountInfo -> VehicleCnt] =$vehicleData['vehicle_number'];
							$AccountInfo -> VehicleMaxSpeed[$AccountInfo -> VehicleCnt] =$vehicleData['max_speed'];
							$AccountInfo -> VehicleFuelVoltage[$AccountInfo -> VehicleCnt] =$vehicleData['fuel_voltage'];
							$AccountInfo -> VehicleTankCapacity[$AccountInfo -> VehicleCnt] = $vehicleData['tank_capacity'];
							$AccountInfo -> DeviceIMEINo[$AccountInfo -> VehicleCnt] =$device_imei_no_local; 
							
						  
							$AccountInfo -> VehicleTypeThirdParty[$AccountInfo -> VehicleCnt] = 0;
							$AccountInfo -> VehicleActiveDate[$AccountInfo -> VehicleCnt] = '';
						 
							$ioStr=getIoDeviceManfInfo($device_imei_no_local,$DbConnection);
							
							$feature_names=getFMFeatureName($ioStr,$DbConnection);
							if($feature_names!="")
							{                            
								$tmpIoTypeArr=getIoAssignmentData($feature_names,$device_imei_no_local,$DbConnection);
								$AccountInfo -> DeviceIOTypeValue[$AccountInfo -> VehicleCnt]=$tmpIoTypeArr;
							}
							$AccountInfo -> VehicleCnt++;								
						}
					}
		}
	//	echo 'A63'.'<BR>';
		return $AccountInfo;
	}		
}

?>