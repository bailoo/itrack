<?php
class manage_route_vehicle_substation_inherit{
	
	function display_shift_table($tableid,$routedetails,$type,$parent_admin_id,$vehicle_list1){
            $evening_data_first = array();
            $morning_data_first = array();
		/*$h1="
			<table width='100%'>						
						<tr>
							<td align='center'>			
							<div style='overflow:auto;height:400px;width:420px;'> 	
							<table border=1 cellspacing=4 cellpadding=4 class='module_left_menu' rules='all' bordercolor='gray' id=".$tableid."
								<tr bgcolor='#d3d3d3' align='left'>								
									<td colspan=4 align=left><font color=red><strong>".$type."</strong></font></td>	
								</tr>
								<tr bgcolor='#d3d3d3'>									
									<td><strong>Route</strong></td>
									<td><strong>Vehicle</strong></td>
									<td><strong>Remark</strong></td>	
									<td><strong>Updation</strong></td>
								</tr>";								
		$h2=$this->get_user_route($routedetails,$tableid);
								
		$h=$h1.$h2."</table>
						</div>
						</td>
					</tr>
				</table>
		";
		echo $h;*/
		
		$shiftdata=$this->get_db_route_vehicle($type,$parent_admin_id,$vehicle_list1);
		//print_r($shiftdata);
		$shiftdataconverted=$this->get_db_route_vehicle_converted($shiftdata,$routedetails,$tableid,$type);
		return $shiftdataconverted;
	}
	
	public function get_user_route($routedetails,$tableid){
		$h3="";
		foreach($routedetails as $rd){
			$h3=$h3."<tr>					
					<td>$rd</td>
					<td><input type=text value=''></td>
					<td><input type=text value=''></td>
					<td><input type=checkbox id=chkupdate name=chkupdate></td>
				</tr>";
		}
		return $h3;
	}
	
	//############# DATABASE VEHICLE ROUTE ###################
	

	public function get_db_route_vehicle($type,$parent_admin_id,$vehicle_list1)
	{
		global $account_id;
		global $DbConnection;
		global $evening_data_first;	//array
		global $morning_data_first;	//array
		 if($type=="EveningShift"){			
			$dataEV= getDetailAllRouteAssignment2InheritEV($parent_admin_id,$DbConnection);			
			foreach($dataEV as $dt)
			{
				$vehicle_name=$dt['vehicle_name'];
				$evening_update_time=$dt['evening_update_time'];
				$route_name_ev=$dt['route_name_ev'];
				
				foreach($vehicle_list1 as $vlist){
					if(trim($vlist)==trim($vehicle_name)){
						$updt_ev=str_replace(':','#',$evening_update_time);
					//echo $updt_ev;
					$evening_data_first[] = array("LINEDATA"=>$vehicle_name.":".$route_name_ev.":".$updt_ev);
					}
				}
					
				
			}
		}
		if($type=="MorningShift"){
		
			$dataMR = getDetailAllRouteAssignment2InheritMR($parent_admin_id,$DbConnection);			
			foreach($dataEV as $dt)
			{
				$vehicle_name=$dt['vehicle_name'];
				$morning_update_time=$dt['morning_update_time'];
				$route_name_mor=$dt['route_name_mor'];
				
				foreach($vehicle_list1 as $vlist){
					if(trim($vlist)==trim($vehicle_name)){
						$updt_mor=str_replace(':','#',$morning_update_time);						
						$morning_data_first[] = array("LINEDATA"=>$vehicle_name.":".$route_name_mor.":".$updt_mor);
					}
				}
				//}
			}
		}
		//print_r($morning_data_first);
		if($type=="MorningShift"){
			//print_r($morning_data_first);
			return $morning_data_first;
		}
		if($type=="EveningShift"){
			//print_r($evening_data_first);
			return $evening_data_first;
		}
		return null;
	}
	
	public function get_db_route_vehicle_converted($line,$route_main1,$tableid,$type){
		
		$route_main=array();
		foreach($route_main1 as $rm){
			$route_main[]=$rm;
		}
		
		$dataarray=array();
		foreach($line as $dataline){	
			$route_vehicle= explode(":",$dataline['LINEDATA']);			
			$vehicle1=$route_vehicle[0];
			$updatetime1=$route_vehicle[2];
			$routearray=explode("/",$route_vehicle[1]);
			$cnt=0;
			foreach($route_main as $rm1){
				
				foreach($routearray as $routeall){
					$routeall=  str_replace('@', '',$routeall);
					if($rm1==$routeall){  //checking if route is avaialable in file is same as in db
					
						if($cnt==0){
							$dataarray[]=array('VEHICLE'=>$vehicle1,'ROUTE'=>$routeall,'ATR'=>'','UPDATETIME'=>$updatetime1);
						}
						else{						
							$dataarray[]=array('VEHICLE'=>$vehicle1,'ROUTE'=>$routeall,'ATR'=>'@','UPDATETIME'=>$updatetime1);
						}
						$cnt++;	
						break;
					}							
				}
			}
			
		}
		
		//$route_main=array('228001','228002','228003'); //from database
		$ctime = date('Y-m-d H:i:s');
		//print_r($route_main);
		$final_data=array();
		$final_data1=array();
		for($i=0;$i<sizeof($route_main);$i++){	
			$vehicle_get="";
			$updatetime_get="";
			$counter=0;
			$tmp_time="0000-00-00 00:00:00";
			$tmp_time_high="0000-00-00 00:00:00";
			foreach($dataarray as $data){
						
				if($route_main[$i]==$data['ROUTE']){
					/*if($data['ROUTE']=="228805"){
						echo $data['VEHICLE']."--".$data['ATR']."<br>";
					}*/
					if($data['ATR']=='@'){
						if($counter==0){
							$vehicle_get='@'.$data['VEHICLE'];
						}
						else{
							$vehicle_get.='/@'.$data['VEHICLE'];
						}
						//tmp
						$tmp_time=$data['UPDATETIME'];
					}
					else{
						if($counter==0){
							$vehicle_get=$data['VEHICLE'];
						}
						else{
							$vehicle_get.='/'.$data['VEHICLE'];
						}
						//tmp
						$tmp_time=$data['UPDATETIME'];
					}
					
					if(str_replace('#',':',$tmp_time) > str_replace('#',':',$tmp_time_high)){
						$tmp_time_high = $tmp_time;
						//echo "IN".$tmp_time_high."<br>";
					}

					if($counter==0)
					{
						//echo "<br>UPTIME1=".$tmp_time;
						$tmp_time_high=$tmp_time;
					}
					//$updatetime_get=$data['UPDATETIME'];
					if($tmp_time_high!="0000-00-00 00:00:00" && $tmp_time_high!="")
					{
						//echo "<br>UPTIME2=".$tmp_time;
						$updatetime_get= $tmp_time_high;
					}
					//echo "<br>UPTIME3=".$updatetime_get;
					$counter++;
				}	
				
			}
			
			$final_data[]=array('ROUTE'=>$route_main[$i],'VEHICLE'=>$vehicle_get,'UPDATETIME'=>$updatetime_get);			
		}

		
		return $final_data;
	}
	
	
	public function get_db_route_vehicle_converted_admin($line,$route_main1,$tableid,$type){
		
		$route_main=array();
		foreach($route_main1 as $rm){
			$route_main[]=$rm;
		}
		
		$dataarray=array();
		foreach($line as $dataline){	
			$route_vehicle= explode(":",$dataline['LINEDATA']);			
			$vehicle1=$route_vehicle[0];
			$updatetime1=$route_vehicle[2];
			$routearray=explode("/",$route_vehicle[1]);
			$userid=$route_vehicle[3];
			$cnt=0;
			foreach($route_main as $rm1){
				
				foreach($routearray as $routeall){
					$routeall=  str_replace('@', '',$routeall);
					if($rm1==$routeall){  //checking if route is avaialable in file is same as in db
					
						if($cnt==0){
							$dataarray[]=array('VEHICLE'=>$vehicle1,'ROUTE'=>$routeall,'ATR'=>'','UPDATETIME'=>$updatetime1,'USERID'=>$userid);
						}
						else{						
							$dataarray[]=array('VEHICLE'=>$vehicle1,'ROUTE'=>$routeall,'ATR'=>'@','UPDATETIME'=>$updatetime1,'USERID'=>$userid);
						}
						$cnt++;	
						break;
					}							
				}
			}
			
		}
		
		//$route_main=array('228001','228002','228003'); //from database
		$ctime = date('Y-m-d H:i:s');
		//print_r($route_main);
		$final_data=array();
		$final_data1=array();
		for($i=0;$i<sizeof($route_main);$i++){	
			$vehicle_get="";
			$updatetime_get="";
			$userid_get="";
			$counter=0;
			$tmp_time="0000-00-00 00:00:00";
			$tmp_time_high="0000-00-00 00:00:00";
			foreach($dataarray as $data){
						
				if($route_main[$i]==$data['ROUTE']){
					$userid_get = $data['USERID'];
					if($data['ATR']=='@'){
						if($counter==0){
							$vehicle_get='@'.$data['VEHICLE'];
						}
						else{
							$vehicle_get.='/@'.$data['VEHICLE'];
						}
						//tmp
						$tmp_time=$data['UPDATETIME'];
					}
					else{
						if($counter==0){
							$vehicle_get=$data['VEHICLE'];
						}
						else{
							$vehicle_get.='/'.$data['VEHICLE'];
						}
						//tmp
						$tmp_time=$data['UPDATETIME'];
					}
					
					if(str_replace('#',':',$tmp_time) > str_replace('#',':',$tmp_time_high)){
						$tmp_time_high = $tmp_time;
						//echo "IN".$tmp_time_high."<br>";
					}

					if($counter==0)
					{
						//echo "<br>UPTIME1=".$tmp_time;
						$tmp_time_high=$tmp_time;
					}
					//$updatetime_get=$data['UPDATETIME'];
					if($tmp_time_high!="0000-00-00 00:00:00" && $tmp_time_high!="")
					{
						//echo "<br>UPTIME2=".$tmp_time;
						$updatetime_get= $tmp_time_high;
					}
					//echo "<br>UPTIME3=".$updatetime_get;
					$counter++;
				}	
				
			}
			
			$final_data[]=array('ROUTE'=>$route_main[$i],'VEHICLE'=>$vehicle_get,'UPDATETIME'=>$updatetime_get,'USERID'=>$userid_get);			
		}

		
		return $final_data;
	}	
	
	public function get_db_route_vehicle_reconverted($line,$vehicle_main){
	///////////////
		//print_r($vehicle_main);
		$dataarray=array();
		foreach($line as $dataline){
			$route_vehicle= explode(":",$dataline['LINEDATA']);
			//echo"PART0=".$route_vehicle[0];
			$route1=$route_vehicle[0];
			$vehiclearray=explode("/",$route_vehicle[1]);
			$updatetime=$route_vehicle[2];
			$cnt=0;
			foreach($vehicle_main as $vm){
				foreach($vehiclearray as $vechicleall){
				//New code Modified
					$vechicleall_A=$vechicleall;
					$vechicleall= str_replace('@', '',$vechicleall);
					if($vm==$vechicleall){
						if(substr($vechicleall_A, 0, 1) == '@'){
						
							$dataarray[]=array('VEHICLE'=>$vechicleall,'ROUTE'=>$route1,'ATR'=>'@','UPDATETIME'=>$updatetime);
						}
						else{
							$dataarray[]=array('VEHICLE'=>$vechicleall,'ROUTE'=>$route1,'ATR'=>'','UPDATETIME'=>$updatetime);
						}
						
						$cnt++;
						break;
					}
					
					/*$vechicleall= str_replace('@', '',$vechicleall);
					if($vm==$vechicleall){
						if($cnt==0){
						$dataarray[]=array('VEHICLE'=>$vechicleall,'ROUTE'=>$route1,'ATR'=>'','UPDATETIME'=>$updatetime);
						}
						else{
						$dataarray[]=array('VEHICLE'=>$vechicleall,'ROUTE'=>$route1,'ATR'=>'@','UPDATETIME'=>$updatetime);
						}
						$cnt++;
						break;
					}*/
				}
			}
		}
		//print_r($dataarray);
		//$vehicle_main=array('DL1001','DL1002','DL1003'); //from database

		$final_data=array();

		for($i=0;$i<sizeof($vehicle_main);$i++){
			$route_get="";
			$updatetime_get="";
			$counter=0;
			$flag_vehicle=0;
			foreach($dataarray as $data){
				
				if($vehicle_main[$i]==$data['VEHICLE']){
					$flag_vehicle=1;
					if($data['ATR']=='@'){
					if($counter==0){
						$route_get='@'.$data['ROUTE'];
						}
						else{
							$route_get.='/@'.$data['ROUTE'];
						}
					}
					else{
						if($counter==0){
							$route_get=$data['ROUTE'];
						}
						else{
							$route_get.='/'.$data['ROUTE'];
						}
					}
					$counter++;
					$updatetime_get=$data['UPDATETIME'];
				}
			}
			if($flag_vehicle==1){
				$final_data[]=array('VEHICLE'=>$vehicle_main[$i],'ROUTE'=>$route_get,'UPDATETIME'=>$updatetime_get);
			}
		}

		//print_r($final_data);
		/*echo"<table border=1 width=600px><th>Vechicle</th><th>Route</th><th>Update</th></tr>";
		foreach($final_data as $data_final){
		echo"<tr>
		<td>".$data_final['VEHICLE']."</td>
		<td>".$data_final['ROUTE']."</td>
		<td>".$data_final['UPDATETIME']."</td>
		</tr>";
		}
		echo"</table>";*/
		return $final_data;
	}
	
	function get_user_vehicle_B($account_id){
		global $DbConnection;
		$vehicle_list=array();
			$query_vehicle = "SELECT DISTINCT vehicle_name FROM route_assignment2 WHERE status=1 ";
			//echo $query_vehicle;
			$result1 = mysql_query($query_vehicle, $DbConnection);
			//echo "DBCONNECTION".$DbConnection;
			while($row1=mysql_fetch_object($result1))
			{
				$vehicle_name=$row1->vehicle_name;
				$vehicle_list[]=$vehicle_name;
				
				
			}
			return $vehicle_list;
	}
	
	function get_user_vehicle_A($AccountNode,$account_id)
	{
		//echo "hi".$account_id;
		//print_r($AccountNode);
		$vehicle_list=array();
		global $vehicleid;
		global $vehicle_cnt;
		global $td_cnt;
		global $DbConnection;
		global $vehicle_list;
		if($AccountNode->data->AccountID==$account_id)
		{
			$td_cnt =0;
			//echo "AC=".$AccountNode->data->VehicleCnt;
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
				
						$num_rows=getNumRowVehicleStation($vehicle_id,$DbConnection);
						//if($num_rows==0)
						{							
							//common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$AccountNode->data->AccountGroupName);
							$vehicle_list[]=$vehicle_id;
						}
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
		
		return $vehicle_list;
	}		
	//######### FUNCTION END ###############################
	
	
}
?>
