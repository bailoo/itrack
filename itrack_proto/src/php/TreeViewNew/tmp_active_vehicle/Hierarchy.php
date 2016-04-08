<?php 
include("tree.php");  
class Hierarchy 
{ 
	public function GetHierarchy($groupid,$account_id,$admin_id,$user_type,$DbConnection) 
	{
		$Root = null;  
		$Root = $this->CreateTree($account_id,$DbConnection);
		return $Root;
	}

	public function CreateTree($account_id,$DbConnection)
	{ 
		//echo "test";
		$count=0;
		//echo 'A1'.'<BR>';
		$AccountInfo = $this->getaccountinfo($account_id,$DbConnection);
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
		$query1 = "SELECT account_id FROM account_detail WHERE account_admin_id='$tmp_admin_id'";
		//echo $query1.'<BR>';
		$result1=mysql_query($query1,$DbConnection);
		while($row1=mysql_fetch_object($result1))
		{
			$getaccountid=$row1->account_id;  
			
			$query2 = "SELECT status FROM account WHERE account_id='$getaccountid'";
			$result2=mysql_query($query2,$DbConnection);
			$row2=mysql_fetch_object($result2);
			
			if($row2->status==1)
			{
				$tmp = $getaccountid;  
				$tree_obj -> child[$tree_obj -> ChildCnt] = null;         
				$tree_obj -> child[$tree_obj -> ChildCnt] = $this->CreateTree($tmp,$DbConnection);
				$tree_obj -> child[$tree_obj -> ChildCnt]->parents = $tree_obj;  	
				$tree_obj -> ChildCnt++;
			}
		}		
		return $tree_obj;
	}
	
	public function getaccountinfo($account_id,$DbConnection)
	{
		//echo 'A52'.'<BR>';
		$AccountInfo = new Info();
		//echo 'A4'.'<BR>';
		$AccountInfo -> AccountHierarchyLevel = 0;
		//echo 'A3'.'<BR>';
		$query= "SELECT * FROM `group` WHERE group_id IN (SELECT group_id FROM account where account_id='$account_id') AND STATUS =1 ";
	//	echo $query.'<BR>';
		$result=mysql_query($query,$DbConnection);
		$row=mysql_fetch_object($result);
		$group_id_local=$row->group_id;
		/*if($group_id_local!=null)
		{
			$query= "SELECT * FROM account WHERE group_id = '$group_id_local' AND STATUS =1";
			$result=mysql_query($query,$DbConnection);
			$row=mysql_fetch_object($result);
			$account_size=0;
			
			while($row)
			{
				if($account_size==0)
				
				$account_id[$account_size]=$row->account_id;
			}
			
		}*/
		
		$group_name_local=$row->group_name;
		$AccountInfo -> AccountGroupID = $group_id_local;
		$AccountInfo -> AccountGroupName = $group_name_local;
		
		
		//$query= "SELECT * FROM account WHERE account_id = '$account_id' AND STATUS =1";
		$query= "SELECT * FROM account_feature WHERE account_id = '$account_id'";
		//echo $query.'<BR>';
		$result=mysql_query($query,$DbConnection);
		$row=mysql_fetch_object($result);
		$user_type_id_local=$row->user_type_id;
		$AccountInfo -> AccountType = $user_type_id_local;
		
		
		$query= "select * from account_detail where account_id = '$account_id'";
		//echo $query.'<BR>';
		$result=mysql_query($query,$DbConnection);
		$num_rows=mysql_num_rows($result);
		$row=mysql_fetch_object($result);
		$name_local=$row->name;
		$admin_id_local=$row->admin_id;
		$create_id_local=$row->create_id;
		$vehicle_group_id_local=$row->vehicle_group_id;

		$AccountInfo -> AccountID = $account_id;
		$AccountInfo -> AccountName = $name_local;
		$AccountInfo -> AdminID = $admin_id_local;
		$AccountInfo -> AccountCreateID = $create_id_local;		
		//$AccountInfo -> VehicleGroupID = $vehicle_group_id_local; 

		while($num_rows1!=0)
		{ 
		//	echo 'A60'.'<BR>';
			$AccountInfo -> AccountHierarchyLevel++;
			$accountid = $row->account_admin_id;
			$query_2 = "select * from account_detail where admin_id ='$accountid'";
			$result_2=mysql_query($query_2,$DbConnection);
			$num_rows1=mysql_num_rows($result_2);
		//echo"query_result=".
			if($num_rows1==0)
			{
				//echo 'A61'.'<BR>';
				break;
			}
			$row_1=mysql_fetch_object($result_2); 
			$accountid = $row_1->account_id;
			//	result = select * from account_details where account_id = accountid;
			$query = "SELECT * FROM account_detail WHERE  account_id='$accountid'";
			$result=mysql_query($query,$DbConnection);
			$num_rows1=mysql_num_rows($result);
			if($num_rows1!=0)
			{
			$row=mysql_fetch_object($result); 
			}
		}

		//$query5 = "SELECT vehicle_id FROM vehicle_grouping WHERE vehicle_group_id = '$vehicle_group_id_local' AND status=1";
		$query5 = "SELECT vehicle_id FROM vehicle_grouping WHERE account_id = '$account_id' AND status=1";
		$result5=mysql_query($query5,$DbConnection);
		$num_rows1=mysql_num_rows($result5);
		$j=0;
		if($num_rows1!=0)
		{
			while ($row5=mysql_fetch_object($result5))
			{
				$vehicle_id[$j]=$row5 ->vehicle_id;
				$j++;   
			}
		}
		
	//	echo 'A62'.'<BR>';
		$query_test1 = "SELECT vehicle.vehicle_id,vehicle.vehicle_name,vehicle.vehicle_type,vehicle.category,vehicle.vehicle_tag,vehicle.vehicle_number,".
		"vehicle.max_speed,vehicle.fuel_voltage,vehicle.tank_capacity,vehicle_assignment.device_imei_no FROM vehicle,vehicle_assignment WHERE".
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
				$query_io = "SELECT io FROM device_manufacturing_info WHERE device_imei_no='$device_imei_no_local' AND status=1";
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
					$query_iovalue = "SELECT ".$feature_names." FROM io_assignment,vehicle_assignment WHERE io_assignment.vehicle_id=vehicle_assignment.".
								"vehicle_id AND vehicle_assignment.device_imei_no='$device_imei_no_local' AND vehicle_assignment.status=1 AND".
								" io_assignment.status=1";
					//echo "query=".$query_iovalue."<br>";
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
	//	echo 'A63'.'<BR>';
		return $AccountInfo;
	}		
}

?>