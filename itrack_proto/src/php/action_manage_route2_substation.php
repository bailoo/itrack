<?php
	
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include('manage_route_vehicle_substation_inherit.php');	
	
	$root=$_SESSION['root'];
	//$common_id1=$_POST['common_id'];
	$common_id1=$account_id;
	
	set_time_limit(3600);
	
	$DEBUG=0;
	
	$action_type1 = $_POST['action_type'];
	$old_value= Array();		
	$new_value=Array();			
	$field_name=Array();		
	$table_name="route_assignment2"; 

	$local_account_id1 = $_POST['local_account_id'];
	$parent_admin_id = $_POST['parent_admin_id'];
	//echo $parent_admin_id;
	//echo $action_type1;
	$current_time = date('Y-m-d H:i:s');
	if($action_type1=="assign")
	{
		$result_ev1 = substr($_POST['result_ev'], 0, -1);
		$result_ev=explode(',',$result_ev1);
		//echo $result_ev;
		for($i=0;$i<sizeof($result_ev);$i++)
		{
			//echo"R1=".$result_ev[$i]."<br>";
			$str1 = explode(':',$result_ev[$i]);
			$route_ev_arr[] = $str1[0];
			$vehicle_ev[] = $str1[1];
			$time_ev_arr[] = $str1[2];
			//echo"str0=".$str1[0]."<br>";			
		}
		//print_r($route_ev_arr);
		$result_mor1 = substr($_POST['result_mor'], 0, -1);
		$result_mor=explode(',',$result_mor1);
		for($i=0;$i<sizeof($result_mor);$i++)
		{
			$str1 = explode(':',$result_mor[$i]);
			$route_mor_arr[] = trim($str1[0]);
			$vehicle_mor[] = trim($str1[1]);
			$time_mor_arr[] = trim($str1[2]);	
		}
		
		//class called
		
		$get_final_vehicle=new manage_route_vehicle_substation_inherit();
		$vehicle_main_id = $get_final_vehicle->get_user_vehicle_A($root,$account_id);
		$vehicle_main = $get_final_vehicle->get_user_vehicle_B();
		//print_r($vehicle_main);
		$line_ev = array();
		for($i=0;$i<sizeof($route_ev_arr);$i++)
		{
			$line_ev[]=array('LINEDATA'=>$route_ev_arr[$i].':'.$vehicle_ev[$i].':'.$time_ev_arr[$i]);
		}
		$get_final_data_ev=new manage_route_vehicle_substation_inherit();
		$final_data_ev = $get_final_data_ev->get_db_route_vehicle_reconverted($line_ev,$vehicle_main);
		$route_vehicle_ev=array();
		foreach($final_data_ev as $data_final){
			$route_vehicle_ev[]=$data_final['VEHICLE'].":".$data_final['ROUTE'].":".$data_final['UPDATETIME'];		
		}
		//print_r($final_data_ev);
		//print_r($final_data);
		/*echo"<table border=1 width=600px><th>Vechicle</th><th>Route</th><th>Update</th></tr>";
		foreach($final_data_ev as $data_final){
		echo"<tr>
		<td>".$data_final['VEHICLE']."</td>
		<td>".$data_final['ROUTE']."</td>
		<td>".$data_final['UPDATETIME']."</td>
		</tr>";
		}
		echo"</table>";*/
		
		
		$line_mor = array();
		for($i=0;$i<sizeof($route_mor_arr);$i++)
		{
			$line_mor[]=array('LINEDATA'=>$route_mor_arr[$i].':'.$vehicle_mor[$i].':'.$time_mor_arr[$i]);
		}
		$get_final_data_mor=new manage_route_vehicle_substation_inherit();
		$final_data_mor = $get_final_data_mor->get_db_route_vehicle_reconverted($line_mor,$vehicle_main);
		$route_vehicle_mor=array();
		foreach($final_data_mor as $data_final){
			$route_vehicle_mor[]=$data_final['VEHICLE'].":".$data_final['ROUTE'].":".$data_final['UPDATETIME'];		
		}
		/*echo"<table border=1 width=600px><th>Vechicle</th><th>Route</th><th>Update</th></tr>";
		foreach($final_data_mor as $data_final){
		echo"<tr>
		<td>".$data_final['VEHICLE']."</td>
		<td>".$data_final['ROUTE']."</td>
		<td>".$data_final['UPDATETIME']."</td>
		</tr>";
		}
		echo"</table>";*/
		//print_r($final_data_mor);
		

		/*foreach($vehicle_main as $vm){
				//in case of eving
				foreach($route_vehicle_ev as $rvm){
					$str1 = explode(':',$route_vehicle_ev[$i]);
					$vehicle_db = trim($str1[0]);
					$route_name = trim($str1[1]);
					if($vehicle_db==$vm){
						//UPDATE QUERY FOR STATUS=0
						$query = "UPDATE route_assignment2 SET status=0 WHERE vehicle_name IN() and vehicle_name NOT IN() 
					}
				}
				//in case of morning
				foreach($route_vehicle_mor as $rvm){
					$str1 = explode(':',$route_vehicle_mor[$i]);
					$vehicle_db = trim($str1[0]);
					$route_name = trim($str1[1]);
					if($vehicle_db==$vm){
						//UPDATE QUERY FOR STATUS=0
					}
				}
		}*/

		for($i=0;$i<sizeof($vehicle_main_id);$i++)
		{
			$vehicle_db=getVehicleNameByVid($vehicle_main_id[$i],$status,$DbConnection);

			$numrows=getNumRowRouteAssignment2($parent_admin_id,$vehicle_db,1,$DbConnection);
			//echo "<br>numrows=".$numrows;
			if($numrows == 0)
			{
				insertRoutAssignment2Detail($vehicle_db,$parent_admin_id,$account_id,$date,1,$DbConnection);	
			}	
		}
		
		//$local_route_vehicle_ev = substr($_POST['route_vehicle_ev'], 0, -1);
		//$route_vehicle_ev = explode(',',$local_route_vehicle_ev);	
				
		if(sizeof($route_vehicle_ev) > 0)
		{		
			//echo "<br>SHIFT EVENING";				
			//echo "\nSizeRoute=".sizeof($route_vehicle);
			for($i=0;$i<sizeof($route_vehicle_ev);$i++)
			{
				$str1 = explode(':',$route_vehicle_ev[$i]);
				$vehicle_db = trim($str1[0]);
				$route_name = trim($str1[1]);
				$time_flag = trim($str1[2]);		
							
				/*$query_vname = "SELECT vehicle_name FROM vehicle WHERE vehicle_id='$vehicle_name' AND status=1";
				$result_vname = mysql_query($query_vname,$DbConnection);
				if($row1 = mysql_fetch_object($result_vname))
				{
					$vehicle_db = $row1->vehicle_name;
				}	*/
				$numrows_check=getNumRowESSRouteAssignment2($parent_admin_id,$vehicle_db,$route_name,1,$DbConnection);
				if($numrows_check == 0)
				{								
					if($time_flag == "1")
					{	
						updateESSRouteAssignment2_a($route_name,$current_time,$account_id,$date,$parent_admin_id,$vehicle_db,1,$DbConnection);						
					}
					else
					{
						updateESSRouteAssignment2_b($route_name,$account_id,$date,$parent_admin_id,$vehicle_db,1,$DbConnection);						
					}
					
					//echo "UPDATE_EV=".$query_updatea."<br>";
					
				}
				else if($time_flag == "1")
				{
					updateESSRouteAssignment2_c($current_time,$account_id,$date,$parent_admin_id,$vehicle_db,1,$DbConnection);					
				}				
			}				
		}
		
		//$local_route_vehicle_mor = substr($_POST['route_vehicle_mor'], 0, -1);
		//$route_vehicle_mor = explode(',',$local_route_vehicle_mor);
		
		if(sizeof($route_vehicle_mor) > 0)
		{		
			//echo "<br>SHIFT EVENING";				
			//echo "\nSizeRoute=".sizeof($route_vehicle);
			for($i=0;$i<sizeof($route_vehicle_mor);$i++)
			{
				$str1 = explode(':',$route_vehicle_mor[$i]);
				$vehicle_db = trim($str1[0]);
				$route_name = trim($str1[1]);
				$time_flag = trim($str1[2]);		
							
				/*$query_vname = "SELECT vehicle_name FROM vehicle WHERE vehicle_id='$vehicle_name' AND status=1";
				$result_vname = mysql_query($query_vname,$DbConnection);
				if($row1 = mysql_fetch_object($result_vname))
				{
					$vehicle_db = $row1->vehicle_name;
				}*/			
				$numrows_check=getNumRowMSSRouteAssignment2($parent_admin_id,$vehicle_db,$route_name,1,$DbConnection);		
				//echo "<br>numrows=".$numrows;
				if($numrows_check == 0)
				{									
					if($time_flag == "1")
					{
						updateMSSRouteAssignment2_a($route_name,$current_time,$account_id,$date,$parent_admin_id,$vehicle_db,1,$DbConnection);						
					}
					else
					{
						updateMSSRouteAssignment2_b($route_name,$account_id,$date,$parent_admin_id,$vehicle_db,1,$DbConnection);	
					}					
					
					$result_updatea = mysql_query($query_updatea,$DbConnection);
				}
				else if($time_flag == "1")
				{
					updateMSSRouteAssignment2_c($current_time,$account_id,$date,$parent_admin_id,$vehicle_db,1,$DbConnection);
				}						
			}			
		}
		
		$flag=1;$action_perform="Assigned";
	}
 
	if($flag==1)
	{
		$msg = "Route ".$action_perform." Successfully";
		$msg_color = "green";				
	}	
  	else if($flag==3)
	{
		$msg = $action_perform;
		$msg_color = "green";		
	}
	else
	{
		$msg = "Sorry! Unable to process request.";
		$msg_color = "red";		
	}
  
  echo "<center><br><br><FONT color=\"".$msg_color."\" size=\"2\">".$msg."<br><br></strong></font></center>";
  echo'<center><a href="javascript:show_option(\'manage\',\'route_vehicle_substation_assignment\');" class="back_css">&nbsp;<b>Back</b></a></center>';                
  
?>
        
