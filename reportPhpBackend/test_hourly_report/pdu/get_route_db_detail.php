<?php
function get_route_db_detail($shift)
{
	global $DbConnection;
	global $account_id;
	global $vehicle_name_rdb;		//VEHICLE ROUTE DETAIL
	global $customer_name_rdb;
	global $transporter_name_rdb;
	//global $remark_rdb;
	global $vehicle_imei_rdb;
	
	global $customer_sel;
	global $customer_name_sel;
	global $transporter_sel;					
	global $station_id;
	global $type;
	global $station_coord;
	global $distance_variable;
	
	//if($shift == "ZPME")
	{
		//$path="D:\\test_app/gps_report/".$account_id."/master/vehicle_customer#1#7.csv";
		$path = "/var/www/html/vts/beta/src/php/gps_report/".$account_id."/master/vehicle_customer#1#7.csv";

		$row = 1;
		if (($handle = fopen($path, "r")) !== FALSE) {
		
		$file_arr[] = $file;
		
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$num = count($data);
				//echo "<p> $num fields in line $row: <br /></p>\n";
				$row++;
				if($row>2)
				{
					for ($c=0; $c < $num; $c++) {
						//echo $data[$c] . "<br />\n";
						if($c==0)
						{
							$vehicle[] = trim($data[$c]);
						}
						else if($c==1)
						{
							$customer[] = trim($data[$c]);
						}
						else if($c==2)
						{
							$transporter[] = trim($data[$c]);
						}
					}
				}
			}
			fclose($handle);
		}		
				
		//echo "\nSizeVehicleA=".sizeof($vehicle);
		for($i=0;$i<sizeof($vehicle);$i++)
		{
			//$remark_rdb[] = $row->remark_ev;
			$query2 = "SELECT DISTINCT vehicle_assignment.device_imei_no FROM vehicle_assignment,vehicle,vehicle_grouping WHERE vehicle_assignment.vehicle_id = vehicle.vehicle_id AND ".
			" vehicle.vehicle_name ='$vehicle[$i]' AND vehicle_assignment.status=1 AND vehicle_grouping.vehicle_id=vehicle_assignment.vehicle_id AND vehicle_grouping.status=1 AND vehicle_grouping.account_id='$account_id'";				
			//echo "\n".$query2;
			$result2 = mysql_query($query2,$DbConnection); 			
			$numrows = mysql_num_rows($result2);
			//echo "\nNUM=".$numrows;
			if($numrows>0)
			{				
				//echo "\nVehicleFound=".$vehicle[$i];
				$row2 = mysql_fetch_object($result2);
				$vehicle_name_rdb[] = $vehicle[$i];
				$customer_name_rdb[$vehicle[$i]] = $customer[$i];
				$vehicle_imei_rdb[$vehicle[$i]] = $row2->device_imei_no;
				$transporter_name_rdb[$vehicle[$i]] = $transporter[$i];
			}
		} 
	}
	
	for($i=0;$i<sizeof($vehicle_name_rdb);$i++)
	{
		//######### CUSTOMER
		//$customer_input_string = $customer_name_rdb[$i];
		$customer_str = explode("/",$customer_name_rdb[$vehicle_name_rdb[$i]]);
		
		for($j=0;$j<sizeof($customer_str);$j++)
		{
			$customer_str1 = trim($customer_str[$j]);

			$flag_customer = false;
			$query2 = "SELECT DISTINCT station_id,type,customer_no,station_name,station_coord,distance_variable FROM station WHERE ".
			"user_account_id='$account_id' AND customer_no ='".$customer_str1."' AND type='0' AND status=1";
			//echo "\nQuery=".$query2." #customer_str1=".$customer_str1;
			$result2 = mysql_query($query2,$DbConnection); 
			//$num2=mysql_num_rows($result2);
			//echo "<br>Num=".$num2;			
			if($row2 = mysql_fetch_object($result2))
			{
				$cust_tmp1 = $row2->customer_no;
				$cust_name_tmp1 = $row2->station_name;
				$station_id1 = $row2->station_id;
				$type_id1 = $row2->type;
				$station_coord1 = $row2->station_coord;
				$dist_var1 = $row2->distance_variable;
				$flag_customer = true;
			}
			/*else
			{
                                $cust_tmp1 = $customer_str1;
                                $cust_name_tmp1 = "";
                                $station_id1 = "";
                                $type_id1 = "";
                                $station_coord1 = "";
                                $dist_var1 = "";
                                $flag_customer = true;

			}*/
			//echo "\nC1=".$customer_str1." ,C2=".$cust_tmp1;			
			if(($flag_customer) && ($customer_str1 == trim($cust_tmp1)))
			{				
				//echo "\nCUSTOMER";
				//echo "\nQuery Success ".$row2->station_id." ".$row2->type." ".$row2->station_coord." ".$row2->distance_variable;
				//$expected_time_sel[$route_sel][] = $expected_time_tmp;
				//echo "\nStoreC=".$cust_tmp1." ,vehicle_name_rdb[i]=".$vehicle_name_rdb[$i];
				$customer_sel[$vehicle_name_rdb[$i]][]= $cust_tmp1;
				$customer_name_sel[$vehicle_name_rdb[$i]][]= $cust_name_tmp1;
				$transporter_sel[$vehicle_name_rdb[$i]][]= $transporter_name_rdb[$vehicle_name_rdb[$i]];					
				$station_id[$vehicle_name_rdb[$i]][] = $station_id1;
				$type[$vehicle_name_rdb[$i]][] = $type_id1;
				$station_coord[$vehicle_name_rdb[$i]][] = $station_coord1;
				$distance_variable[$vehicle_name_rdb[$i]][] = $dist_var1;
			}			
		
			/*//######### PLANT
			$flag_plant=false;
			$query3 = "SELECT DISTINCT station_id,type,customer_no,station_coord,distance_variable FROM station WHERE ".
			"user_account_id='$account_id' AND customer_no ='".$customer_str1."' AND type='1' AND status=1";
			//echo "\nQuery=".$query2." #customer_sel=".$customer_sel;
			$result3 = mysql_query($query2,$DbConnection); 
			//$num2=mysql_num_rows($result2);
			//echo "<br>Num=".$num2;			
			if($row3 = mysql_fetch_object($result3))
			{
				$cust_tmp1 = $row3->customer_no;
				$station_id1 = $row3->station_id;
				$type_id1 = $row3->type;
				$station_coord1 = $row3->station_coord;
				$dist_var1 = $row3->distance_variable;
				$flag_plant = true;
			}
			//echo "\nC1=".$customer_sel." ,C2=".$cust_tmp1;
			
			if(($flag_plant) && ($customer_str1 == trim($cust_tmp1)))
			{				
				echo "\nPLANT";
				//echo "\nQuery Success ".$row2->station_id." ".$row2->type." ".$row2->station_coord." ".$row2->distance_variable;
				//$expected_time_sel[$route_sel][] = $expected_time_tmp;
				$customer_sel[$vehicle_name_rdb[$i]][]= $cust_tmp1;
				$transporter_sel[$vehicle_name_rdb[$i]][]= $transporter_name_rdb[$i];					
				$station_id[$vehicle_name_rdb[$i]][] = $station_id1;
				$type[$vehicle_name_rdb[$i]][] = $type_id1;
				$station_coord[$vehicle_name_rdb[$i]][] = $station_coord1;
				$distance_variable[$vehicle_name_rdb[$i]][] = $dist_var1;
			}*/			
		}
	}
}
?>  
