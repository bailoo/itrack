<?php 
include("tree.php");
include("coreDb.php"); 
//if($account_id==2)
{ 
    //error_reporting(-1);
    //ini_set('display_errors', 'On');
    include_once("/mnt/itrack/phpApi/Cassandra/Cassandra.php");     //##### INCLUDE CASSANDRA API
include_once("/mnt/itrack/phpApi/libLog.php");     //##### INCLUDE CASSANDRA API*/    //##### INCLUDE CASSANDRA API*/ 
$o_cassandra = new Cassandra();	
$o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);
}
global $imeiCheckArr;
$imeiCheckArr=array();
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
		$this->AddThirdParty($Root,$account_id,$accountUserType,$DbConnection);		
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
                        //if($account_id==2)
                        {
                            global $o_cassandra;
                        $o_cassandra->close;
                            
                        }
			return $tree_obj;
		}		
	}
	
	public function AddThirdParty($Root,$account_id,$accountUserType,$DbConnection)	
	{		
		//==Query to get unique source account ===//
		
		$tpaaData=getHierarchythirdpartyaccountassignmentaccountid($account_id,$DbConnection);
		
		if(count($tpaaData)>0)
		{
			//while ($rowSourceAccount=mysql_fetch_object($resultSourceAccount))
			foreach($tpaaData as $tpk)
			{
				//$admin_account_id_source=$rowSourceAccount->admin_account_id;
				$admin_account_id_source=$tpk['admin_account_id'];
				$status_account=1;
				$status_account = $this->checkaccountIDTree($admin_account_id_source);
				//echo "chk=".$status_account;
				if($status_account==0)
				{
				
					/////
					$accSingleRowData=getHierarchyStatusUsertype($admin_account_id_source,$DbConnection);
				
					if($accSingleRowData[0]==1) /// for account status
					{
						//echo"<br>".$row2->user_type;
						$admin_account_id_destination=$account_id;
						$accountUserType=$accSingleRowData[1]; // for account user type
						//$accountUserType=$row2->user_type; 
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
                        global $o_cassandra;
                        $o_cassandra->close;
                        
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
             global $o_cassandra;
             global $imeiCheckArr;
            $currentFilePath="/mnt/itrack/beta/src/php/vehicleStatus";
		$AccountInfo = new Info();
		$AccountInfo -> AccountTypeThirdParty = 1;
		$AccountInfo -> AccountHierarchyLevel = 0;
		$AccountInfo -> AccountUserType = $accountUserType;
		$AccountInfo -> AccountGroupID = '';
		$AccountInfo -> AccountGroupName = '';
		$AccountInfo -> AccountType = '';
		
		
		$rowAD=getHierarchyAccountDetailNumRows($account_id,$DbConnection);
		foreach($rowAD as $row)
		{
			$name_local=$row['name_local'];
			//echo "Name_local=".$name_local;
			$admin_id_local=$row['admin_id_local'];
			$create_id_local=$row['create_id_local'];
			$vehicle_group_id_local=$row['vehicle_group_id_local'];
			$AccountInfo -> AccountID = $account_id;
			$AccountInfo -> AccountName = $name_local;
			$AccountInfo -> AdminID = $admin_id_local;
			$AccountInfo ->AccountAdminID=$row['account_admin_id'];
			$AccountInfo -> AccountCreateID = $create_id_local;	
			
		}
		
				
		$rowThirdPartyA=getHierarchythirdpartyaccountassignment($admin_account_id_destination,$account_id,$DbConnection);
		$j=0;
		foreach($rowThirdPartyA as $rowThirdParty)
		{
			$vehicle_id[$j]=$rowThirdParty['vehicle_id'];
			//echo"<br>".$vehicle_id[$j];
			$vehicle_date_from_third_party[$vehicle_id[$j]]=$rowThirdParty['create_date'];
			$j++;
		}
		if($j!=0)
		{                    
			$vehicleDataArr=getVehicleTableData($vehicle_id,$DbConnection,$j); 
			if(count($vehicleDataArr)>0)
			{
				foreach($vehicleDataArr as $vehicleData)
				{
					//$device_imei_no_local=$vehicleData['vehicle_id'];
                                        $device_imei_no_local=$vehicleData['device_imei_no'];
					$vehicle_id_local=$row_1->vehicle_id;
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
					
                                        $iterator = new FilesystemIterator($currentFilePath);                       
                                        //echo "imeiNo11=".count($iterator)."<br>";
                                        $fileFoundFlag=0;
                                        $dateObject=new DateTime();
                                        $todayDateOnly=$dateObject->format('Y-m-d');
                                        //echo"todayDate=".$todayDateOnly."<br>";
                                        $exactFilePath=$currentFilePath."/".$device_imei_no_local.".txt";
                                        //echo "exactFilePath=".$exactFilePath."<br>";
                                        if(file_exists($exactFilePath))
                                        {
                                            $fileFoundFlag=1;
                                            if($imeiCheckArr[$device_imei_no_local]=="")
                                            { 
                                                if(date("Y-m-d", filectime($exactFilePath))!=$todayDateOnly)
                                                { 
                                                    $todayDataLog=hasImeiLogged($o_cassandra, $device_imei_no_local, $todayDateOnly);
                                                    if($todayDataLog!='')
                                                    {
                                                        touch($exactFilePath);
                                                        $AccountInfo -> DeviceRunningStatus[$AccountInfo -> VehicleCnt]="1";                                       
                                                    }
                                                    else
                                                    {
                                                       $AccountInfo -> DeviceRunningStatus[$AccountInfo -> VehicleCnt]="0";  
                                                    }
                                                }
                                                else if(date("Y-m-d", filectime($exactFilePath))==$todayDateOnly)
                                                {
                                                    //echo "in else 0<br>";
                                                   $AccountInfo -> DeviceRunningStatus[$AccountInfo -> VehicleCnt]="1";  
                                                }
                                            }
                                        }
                                        if($fileFoundFlag==0)
                                        {
                                            if($imeiCheckArr[$device_imei_no_local]=="")
                                            { 
                                                $todayDataLog=hasImeiLogged($o_cassandra, $device_imei_no_local, $todayDateOnly);
                                                if($todayDataLog!='')
                                                {
                                                    //echo "in if";
                                                    touch($currentFilePath."/".$device_imei_no_local.".txt");
                                                    $AccountInfo -> DeviceRunningStatus[$AccountInfo -> VehicleCnt]="1"; 
                                                }
                                                else
                                                {
                                                    //echo "in else 1<br>";
                                                    $AccountInfo -> DeviceRunningStatus[$AccountInfo -> VehicleCnt]="0"; 
                                                }  
                                            }
                                        }							
                                        $imeiCheckArr[$device_imei_no_local]=$device_imei_no_local;
					$AccountInfo -> VehicleTypeThirdParty[$AccountInfo -> VehicleCnt] = 1;
					$AccountInfo -> VehicleActiveDate[$AccountInfo -> VehicleCnt] = $vehicle_date_from_third_party[device_imei_no_local];
				 
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
		
		return $AccountInfo;
	}
	
	
	public function getaccountinfo($account_id,$accountUserType,$DbConnection)
	{
            //if($account_id==2)
            {
                global $imeiCheckArr;
                global $o_cassandra;
                $currentFilePath="/mnt/itrack/beta/src/php/vehicleStatus";
            }
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
                                                        $AccountInfo -> MobileNumber[$AccountInfo -> VehicleCnt] =$vehicleData['mobile_number'];
							$AccountInfo -> VehicleMaxSpeed[$AccountInfo -> VehicleCnt] =$vehicleData['max_speed'];
							$AccountInfo -> VehicleFuelVoltage[$AccountInfo -> VehicleCnt] =$vehicleData['fuel_voltage'];
							$AccountInfo -> VehicleTankCapacity[$AccountInfo -> VehicleCnt] = $vehicleData['tank_capacity'];
							$AccountInfo -> DeviceIMEINo[$AccountInfo -> VehicleCnt] =$device_imei_no_local; 
							
                                                        
                                                        $iterator = new FilesystemIterator($currentFilePath);                       
                                                        //echo "imeiNo11=".count($iterator)."<br>";
                                                        $fileFoundFlag=0;
                                                        $dateObject=new DateTime();
                                                        $todayDateOnly=$dateObject->format('Y-m-d');
                                                        //echo"todayDate=".$todayDateOnly."<br>";
                                                        $exactFilePath=$currentFilePath."/".$device_imei_no_local.".txt";
                                                        //echo "exactFilePath=".$exactFilePath."<br>";
                                                        if(file_exists($exactFilePath))
                                                        {  
                                                            $fileFoundFlag=1;
                                                            if($imeiCheckArr[$device_imei_no_local]=="")
                                                            { 
                                                                if(date("Y-m-d", filectime($exactFilePath))!=$todayDateOnly)
                                                                { 
                                                                    $todayDataLog=hasImeiLogged($o_cassandra, $device_imei_no_local, $todayDateOnly);
                                                                    if($todayDataLog!='')
                                                                    {
                                                                        touch($exactFilePath);
                                                                        $AccountInfo -> DeviceRunningStatus[$AccountInfo -> VehicleCnt]="1";                                       
                                                                    }
                                                                    else
                                                                    {
                                                                       $AccountInfo -> DeviceRunningStatus[$AccountInfo -> VehicleCnt]="0";  
                                                                    }
                                                                }
                                                                else if(date("Y-m-d", filectime($exactFilePath))==$todayDateOnly)
                                                                {
                                                                   //echo "in else 0<br>";
                                                                   $AccountInfo -> DeviceRunningStatus[$AccountInfo -> VehicleCnt]="1";  
                                                                }
                                                            }
                                                        }
                                                        if($fileFoundFlag==0)
                                                        {
                                                            if($imeiCheckArr[$device_imei_no_local]=="")
                                                            { 
                                                                $todayDataLog=hasImeiLogged($o_cassandra, $device_imei_no_local, $todayDateOnly);
                                                                if($todayDataLog!='')
                                                                {
                                                                    //echo "in if";
                                                                    touch($currentFilePath."/".$device_imei_no_local.".txt");
                                                                    $AccountInfo -> DeviceRunningStatus[$AccountInfo -> VehicleCnt]="1"; 
                                                                }
                                                                else
                                                                {
                                                                    //echo "in else 1<br>";
                                                                    $AccountInfo -> DeviceRunningStatus[$AccountInfo -> VehicleCnt]="0"; 
                                                                }  
                                                            }
                                                        }
							
                                                         $imeiCheckArr[$device_imei_no_local]=$device_imei_no_local;
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