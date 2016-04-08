<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	//include('manage_route_vehicle_substation_inherit.php');
	include_once("util_account_detail.php");
	//echo "add##"; 
	$root=$_SESSION['root'];
	//$common_id1=$_POST['common_id'];
	$common_id1=$account_id;
	
	//*****************************************Getting Admin Account ID and Current UserID*******************************************//	
	global $parent_admin_id;
	/*$query_account_admin_id="SELECT account_admin_id FROM account_detail WHERE account_id='$account_id'";
	//echo $query_account_admin_id;
	$result_account_admin_id = mysql_query($query_account_admin_id, $DbConnection);
	$row_account_admin_id =mysql_fetch_object($result_account_admin_id);*/
        $row_account_admin_id=getAcccountAdminIdAdminId($account_id,$DbConnection);
        $accountadminid=$row_account_admin_id[0];
	/*
	$query_admin_id="SELECT account_id FROM account_detail WHERE admin_id='$row_account_admin_id->account_admin_id'";
	//echo $query_admin_id;
	$result_admin_id = mysql_query($query_admin_id, $DbConnection);
	$row_admin_id =mysql_fetch_object($result_admin_id);
	$parent_admin_id=$row_admin_id->account_id;
	*/
	$parent_admin_id=$getAccountIdByAdminId($accountadminid,$DbConnection);
	
	global $user_name;
	/*$query="SELECT user_id from account where account_id='$account_id'";
	$result=mysql_query($query,$DbConnection);
	$row=mysql_fetch_object($result);
	$user_name=$row->user_id;*/
	$row=uidgidAccount($account_id,$DbConnection);
        $user_name=$row[0];
        
	$vehicle_list=array();
	get_user_vehicle($root,$account_id);
	//print_r($vehicle_list);
	$vehicle_list1 = array_unique($vehicle_list);	
	$final_vehicle_list=array();
	$all_vehicles = "";
	//$v2 = " DL12345";
	//$v3 = trim($v2);
	//echo "v3=".$v3;
	foreach($vehicle_list1 as $vl){
		$final_vehicle_list[]=$vl;
		//echo "vl=".$vl."<br>"; 
		$all_vehicles.= trim($vl).",";
		//echo "all_vehicles=".$all_vehicles."<br>";
	}
	//$js_array=json_encode(final_vehicle_list);
	$all_vehicles = substr($all_vehicles, 0, -1);
	//$all_vehicles = trim($all_vehicles);
	$all_vehicles = str_replace(' ','%20',$all_vehicles);
	//echo  $user_name;	
	
	//===Getting product_type 
	$final_product_type_list=array();
    $final_other_vehicle_list1=array();
	/*$query_product_type = "SELECT name FROM product_type WHERE account_id='$parent_admin_id' AND status=1";
	$result_query = mysql_query($query_product_type,$DbConnection);
	while($row=mysql_fetch_object($result_query))
	{		
		$final_product_type_list[]=$row->name;
		
	}*/
	
	$hindalco_account = assign_to_till_root($account_id);
	if($hindalco_account){
		foreach($hindalco_account as $ha)
		{
			/*
			$query_product_type = "SELECT name FROM product_type WHERE account_id='$ha' AND status=1";
                        $result_query = mysql_query($query_product_type,$DbConnection);
                        while($row=mysql_fetch_object($result_query))
                        {		
                                $final_product_type_list[]=$row->name;

                        }*/
                        $final_product_type_list=getProductNameProductType($ha,$DbConnection);
				
			if($ha!=$account_id)
			{	/*$query_v1 = "SELECT vehicle_id FROM vehicle_grouping WHERE account_id='$ha' AND status=1";
				//echo $query_v1."<br>";
				$result_query1 = mysql_query($query_v1,$DbConnection);
				while($row1=mysql_fetch_object($result_query1))
				{
				
					$final_other_vehicle_list1[]=$row1->vehicle_id;
					
				}*/
                            $final_other_vehicle_list1=getVehicleIdByAccountId($ha,$DbConnection);
			}
		}
		
	}
	$final_other_vehicle_list=array();
	
	if(sizeof($final_other_vehicle_list1)>0){
		foreach($final_other_vehicle_list1 as $fl){
			/*$query_v1 = "SELECT vehicle_name FROM vehicle WHERE vehicle_id='$fl' AND status=1";
			//echo $query_v1."<br>";
			$result_query2 = mysql_query($query_v1,$DbConnection);
			$row_all_vehicle =mysql_fetch_object($result_query2);
			$final_other_vehicle_list[]=$row_all_vehicle->vehicle_name;
                        */
                        $final_other_vehicle_list[]=getVehicleNameByVid($fl,1,$DbConnection);
		}
		
	}
	//print_r($final_other_vehicle_list);
	$all_other__vehicles = "";	
	foreach($final_other_vehicle_list as $v2){		
		$all_other__vehicles.= trim($v2).",";	
	}	
	$all_other__vehicles = substr($all_other__vehicles, 0, -1);	
	$all_other__vehicles = str_replace(' ','%20',$all_other__vehicles);
	//echo  $all_other__vehicles;	
/*******************************************************************************************************************************/
	echo"
	
	<form name='manage1'>
	<input type='hidden' id='vehicle_list_hidden' value='".$all_vehicles."'>
	<input type='hidden' id='vehicle_list_other_hidden' value='".$all_other__vehicles."'>
	<table width=100%>
		<tr>
			<th>
				<u>HINDALCO INVOICE</u>
			</th>
		</tr>
		<tr>
			<td>		
				";
					
					include('manage_add_hindalco_invoice_usertype_interface.php');
					
				echo'
			</td>
		</tr>
		<tr>
			<td>
				<center><br>
						<input type="button" id="enter_button" name="enter_button" Onclick="javascript:return action_manage_invoice_hindalco(\'add\')" value="Add Now">
						<br>
						<div id="loading_status" name="loading_status" />	
			
				</center>
			</td>
		</tr>
	</table>
	
	<div id="blackout"> </div>
	<div id="divpopup_plant">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="skyblue">							
			<tr>
				<td class="manage_interfarce" align="right"><a href="#" onclick="javascript:return close_vehicle_list_hindalco()" class="hs3">Close</a></td> 													
			</tr> 
			<tr>
				<td colspan="5" valign="top" align="justify">ADD VEHICLE</td>
			</tr>							
		</table>
		<br>
		<table width="100%" border="0" cellpadding="0" cellspacing="0" rules="all" style="background-color:ghostwhite;">							
			<tr>
				<td><input type=radio id="radio_self_other" name="radio_self_other" value="0" checked >Self Vehicle :</td><td>
				<input type="text" id="vehicle_list_self" name="vehicle_list_self"  size="30" onKeyUp="getScriptPage_hindalco_self_vehicle(this.value,this.id,\'box2\')">
				<div id="box2" class="input-div-route" style="display:none"></div>
				</td>
				
			</tr>
			<tr>
				<td><input type=radio id="radio_self_other" name="radio_self_other" value="1" >Other Vehicle :</td><td>
				<input type="text" id="vehicle_list_other" name="vehicle_list_other"  size="30" onKeyUp="getScriptPage_hindalco_other_vehicle(this.value,this.id,\'box3\')">
				<div id="box3" class="input-div-route" style="display:none"></div>
				</td>
				
			</tr>
			<tr><td colspan="2">
					<input type="button" value="Add" onclick="javascript:close_vehicle_list_hindalco();">
				</td></tr>
		</table>
		
	</div>
    <input type="hidden" id="tmp_serial"/>
	
	</form>';
	
	function get_user_vehicle($AccountNode,$account_id)
	{
		//echo "hi".$account_id;
		global $vehicleid;
		global $vehicle_cnt;
		global $td_cnt;
		global $DbConnection;
		global $vehicle_list;
		if($AccountNode->data->AccountID==$account_id)
		{
			$td_cnt =0;
			for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
			{			    
				$vehicle_id = $AccountNode->data->VehicleID[$j];
				$vehicle_name = $AccountNode->data->VehicleName[$j];
				$vehicle_imei = $AccountNode->data->DeviceIMEINo[$j];
				if($vehicle_id!=null)
				{
					for($i=0;$i<$vehicle_cnt;$i++)
					{
						if($vehicleid[$i]==$vehicle_id)
						{
							break;
						}
					}			
					if($i>=$vehicle_cnt)
					{
						$vehicleid[$vehicle_cnt]=$vehicle_id;
						$vehicle_cnt++;
						$td_cnt++;
						
						
							$vehicle_list[]=$vehicle_name;
						
						if($td_cnt==3)
						{
							$td_cnt=0;
						}
					}
				}
			}
		}
		$ChildCount=$AccountNode->ChildCnt;
		for($i=0;$i<$ChildCount;$i++)
		{ 
			get_user_vehicle($AccountNode->child[$i],$account_id);
		}
	}
	function assign_to_till_root($account_id_local1)
	{	
		global $DbConnection;	
		global $parent_account_ids;	 
		global $acc_size;			
			
		/*$query = "SELECT account_admin_id FROM account_detail WHERE account_id='$account_id_local1'";	
		//echo $query;
		$result=mysql_query($query,$DbConnection);
		$row=mysql_fetch_row($result);
		$admin_id=$row[0];*/
		$row=  getAcccountAdminIdAdminId($account_id_local1,$DbConnection);
                $admin_id=$row[0];
                
		/*$query1 = "SELECT account_id FROM account_detail WHERE admin_id='$admin_id'";
		//echo "<br>".$query;	
		$result=mysql_query($query1,$DbConnection);
		$row1=mysql_fetch_row($result);
		$function_account_id=$row1[0];
		//echo "account_id=".$function_account_id.'<br>';*/
		$row1=getAccountIdByAdminId($admin_id,$DbConnection);
                $function_account_id=$row1;
                /*
		$queryType="SELECT user_type from account WHERE account_id='$function_account_id'";
		//echo "<br>".$queryType;
		$resultType=mysql_query($queryType,$DbConnection);
		$rowType=mysql_fetch_row($resultType);
		$function_account_type=$rowType[0];
		//echo "userType=".$function_account_type."<br>";*/
		$utype=getUserTypeAccount($function_account_id,$DbConnection);
                $function_account_type=$utype;
		if($function_account_type!='hindalco_invoice')
		{
			$parent_account_ids[]=$function_account_id;
			//print_r($parent_account_ids);
			return $parent_account_ids;
		}		
		
		else
		{			
			$final_account_id=assign_to_till_root($function_account_id);
			//query to check non transporter from account table  usertype='raw_milk'
			////////
			$parent_account_ids[]=$function_account_id;
			//echo"acc1=".$function_account_id."<br>"."acc1=".$function_account_id."<br>"."acc1=".$parent_account_ids."<br>";				
			return $parent_account_ids;					
		}
		//return $account_id_local1;
	}
?>  
