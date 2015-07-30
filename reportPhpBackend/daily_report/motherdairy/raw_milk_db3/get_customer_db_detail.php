<?php
function get_customer_db_detail($account_id, $shift_time, $route_type_param)
{
	echo "\nRoute_type_param=".$route_type_param;        
	global $DEBUG_OFFLINE;
        global $abspath;
	global $DbConnection;
	global $route_name_rdb;
	global $vehicle_imei_rdb;
	//global $remark_rdb;
	global $all_routes;
	global $all_customers;
	global $customer_sel;
	global $plant_sel;
	global $transporter_sel;
	global $station_id;
	global $type;
	global $station_coord;
	global $distance_variable;
	global $expected_time_sel;
	global $expected_time_plant_out_sel;

	global $expected_customer_csv;	//CSV VARIABLE
	global $expected_time_csv;
	
	global $expected_route_no;
	global $expected_plant;
	global $expected_plant_intime;
	global $expected_plant_outtime;
	
	global $transporter_m;
	global $vehicle_m;	
	global $route_type_rdb;
	
	global $plant_station_coord;
	global $plant_distance_variable;
	
	$customer_input_string = "";
	$customer_input_string2 = "";
	$row=0;
	
	//###### READ EXPECTED TIME
	read_expected_time($account_id, $shift_time);
	read_transporter($account_id, $shift_time);
	//echo "\nSizeExpectedCustomer=".sizeof($expected_customer_csv)." ,SizeExpectedTime=".sizeof($expected_time_csv);
			
	//##### READ MAIN MASTER FILE
	if($shift_time=="ZPMM")
	{
		echo "\nMor:Master";
		if($DEBUG_OFFLINE)
		{
			$path = $abspath."/gps_report/".$account_id."/master/morning_plant_customer#1#8.csv";
		}
		else
		{
			$path = $abspath."/gps_report/".$account_id."/master/morning_plant_customer#1#8.csv";
		}
	}
	else if($shift_time=="ZPME")
	{
		echo "\nEv:Master";
		if($DEBUG_OFFLINE)
		{
			$path = $abspath."/gps_report/".$account_id."/master/evening_plant_customer#1#7.csv";
		}
		else
		{
			$path = $abspath."/gps_report/".$account_id."/master/evening_plant_customer#1#7.csv";
		}
	}
	
	//echo "\npath=".$path;
	if (($handle = fopen($path, "r")) !== FALSE)
	{
		$count=1;
		//echo "\nInside File Read";
		$prev_route = "";
		
		$query1 = "SELECT DISTINCT station_id,type,customer_no,station_coord,distance_variable FROM station WHERE ".
		"user_account_id='$account_id' AND type='1' AND status=1";
		//echo "\nQuery=".$query2;
		$result1 = mysql_query($query1,$DbConnection);
		while($row1 = mysql_fetch_object($result1))
		{
			$plant_station_coord[$row1->customer_no] = $row1->station_coord;
			$plant_distance_variable[$row1->customer_no] = $row1->distance_variable;	
		}
		
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
		{
			$row++;
			$num = count($data);
			//echo "\nData Read ".$num." ".$row;
			if(($num<4) || ($row==1))
			{
				continue;
			}
			
			//echo "\nField Match ".sizeof($route_name_rdb);			
			//######## GET ALL ROUTES						
			/*$route_all_str = explode("/",$data[3]);
			for($a=0;$a<sizeof($route_all_str);$a++)
			{
				//if($route_all_str[$a]=="206013") echo "Found2::206013";								
				$all_routes[] = $route_all_str[$a];
				$all_customers[] = $route_all_str[$a].":".$data[0];
			}*/
												
			$plant_str = explode("/",$data[1]);
			$route_str = explode("/",$data[3]);
			$route_type_str = explode("/",$data[4]);
			$transporter_str = explode("/",$data[2]);	

			//######## GET ALL ROUTES						
			//$route_all_str = explode("/",$data[3]);
			for($a=0;$a<sizeof($route_str);$a++)
			{
				$match_a = false;
				/*if($route_str[$a] =="202526")
				{
					echo "<br>RouteStr=".$route_str[$a];
					echo "<br>RouteType=".$route_type_str[$a]." ,RouteParam=".$route_type_param;
				}*/
				//if($route_all_str[$a]=="206013") echo "Found2::206013";								
				
				if($route_type_param=="CASH")
				{
					if((strpos($route_type_str[$a],$route_type_param) !== false))
					{
						//echo "\nCASH1";
						$match_a = true;
					}
				}
				else if($route_type_param=="FOCAL")
				{
					$tmp_type="CASH";
					if(strpos($route_type_str[$a],$tmp_type) === false)
					{
						//echo "\nFOCAL1";
						$match_a = true;
					}
				}
				else if($route_type_param=="ALL")
				{
					//echo "\nALL1";
					$match_a = true;
				}				
				if($match_a)
				{
					$all_routes[] = $route_str[$a];
					$all_customers[] = $route_str[$a].":".$data[0];
				}				
			}			
														
			$size_route_str = 0;
			$size_route_str = sizeof($route_str);
			
			if($size_route_str>0)
			{
				for($j=0;$j<$size_route_str;$j++)
				{
					$route_match=false;
					$size_rdb2 =0;
					$size_rdb2 = sizeof($route_name_rdb);
					//echo "<br>size_rdb222222222=".$route_str[$j];
					for($i=0;$i<$size_rdb2;$i++)
					{
						$route_name_rdb_1 = explode('/',$route_name_rdb[$i]);					
						$size_rdb3 = 0;
						$size_rdb3 = sizeof($route_name_rdb_1);
						//echo "<br>size_rdb3=".$size_rdb3;
						for($k=0;$k<$size_rdb3;$k++)
						{
							$route_name_rdb_3="";
							if(strpos($route_name_rdb_1[$k],'@') !== false)
							{
								$route_name_rdb_2 = explode('@',$route_name_rdb_1[$k]);
								$route_name_rdb_3 = $route_name_rdb_2[1];
							}
							else
							{
								$route_name_rdb_3 = $route_name_rdb_1[$k];
							}						
							//echo "\nRouteFinal=".$route_name_rdb_3;
							//if((trim($route_name_rdb[$i]) == trim($route_str[$j])) && ((trim($route_type_str[$j])==trim($route_type_param)) || ($route_type_param=="ALL")) )
							/*if($route_name_rdb[$i] == $route_str[$j])
							{
								echo "\nROUTE MATCHED-1";
							}
							if((strpos($route_type_str[$j],$route_type_param) !== false) || ($route_type_param=="ALL"))
							{
								echo "\nROUTE TYPE MATCHED-2";
							}*/
							$pre_match = false;
							//echo "\nroute_type_param=".$route_type_param;
							if($route_type_param=="CASH")
							{
								if((trim($route_name_rdb_3) == trim($route_str[$j])) && ((strpos($route_type_str[$j],$route_type_param) !== false)) )
								{
									//echo "\nCASH1";
									$pre_match = true;
								}
							}
							else if($route_type_param=="FOCAL")
							{
								$tmp_type="CASH";
								if((trim($route_name_rdb_3) == trim($route_str[$j])) && ((strpos($route_type_str[$j],$tmp_type) === false)) )
								{
									//echo "\nFOCAL1";
									$pre_match = true;
								}
							}
							else if((trim($route_name_rdb_3) == trim($route_str[$j])) && ($route_type_param=="ALL"))
							{
								//echo "\nALL1";
								$pre_match = true;
							}
												
							if($pre_match)
							{
								/*$found_route=false;
								
								if(sizeof($route_total)>0)
								{								
									for($r=0;$r<sizeof($route_total);$r++)
									{
										if(trim($route_total[$r])==trim($route_name_rdb_3))
										{
											$found_route=true;
										}
									}
									if(!$found_route)
									{
										$route_total[] = $route_name_rdb_3;
									}
								}
								else
								{
									$route_total[] = $route_name_rdb_3;
									$found_route = false;
								}*/
								
								//echo "\nROUTE_MATCH";
								//if($route_str[$j]=="200502") echo "\nFoundInside::200502";
								$route_match=true;
								
								$route_type_rdb[$i] = trim($route_type_str[$j]);
								break;												
							}
						}
						/*if($route_match)
						{
							break;
						}*/
					}

					//########## STORE CUSTOMERS					
					if($route_match==true)
					{
						//echo "\nRouteMatch";
						$route_sel = trim($route_str[$j]);
						//$route_sel = $vehicle_imei_rdb[$i];
						$transporter_wrt_route = $transporter_str[$j];
						$plant_wrt_route = $plant_str[$j];
										
						//echo "\nData Write";
						//### GET EXPECTED TIME OF THIS ROW	
						$expected_time_tmp = "";	
						for($c=0;$c<sizeof($expected_customer_csv);$c++)
						{
							if(trim($data[0]) == trim($expected_customer_csv[$c]))
							{
								$expected_time_tmp = $expected_time_csv[$c];
								//+echo "\nExpectedTimeTmp=".$expected_time_tmp;
								break;
							}
						}
						
						$expected_plant_out_time_tmp = "";
						for($p=0;$p<sizeof($expected_route_no);$p++)
						{
							if( (trim($route_sel) == trim($expected_route_no[$p])) && ($expected_plant[$p]))
							{
								$expected_plant_out_time_tmp = $expected_plant_outtime[$p];								
								break;
							}
						}					
						
						$customer_input_string = $data[0];					
						$customer_input_string2 = " customer_no like '".$data[0]."@%'";

						//######### CUSTOMER
						$query2 = "SELECT DISTINCT station_id,type,customer_no,station_coord,distance_variable FROM station WHERE ".
						"user_account_id='$account_id' AND (customer_no IN(".$customer_input_string.") OR ".$customer_input_string2.") AND type='0' AND status=1";
						//echo "<br>Query=".$query2;
						$result2 = mysql_query($query2,$DbConnection); 
						$num2=mysql_num_rows($result2);
						//echo "<br>Num=".$num2;
						while($row2 = mysql_fetch_object($result2))
						{
							//echo "\nQuery Success ".$row2->station_id." ".$row2->type." ".$row2->station_coord." ".$row2->distance_variable;
							$expected_time_sel[$route_sel][] = $expected_time_tmp;
							$expected_time_plant_out_sel[$route_sel][] = $expected_plant_out_time_tmp;
							
							$customer_sel[$route_sel][]=$row2->customer_no;
							//$plant_tmp = implode('/',array_unique(explode('/', $data[1])));
							$plant_sel[$route_sel][] = $plant_wrt_route;
							//$transporter_sel[$route_sel][]=$data[2];
							$transporter_sel[$route_sel][]=$transporter_wrt_route;
							
							$station_id[$route_sel][] = $row2->station_id;
							$type[$route_sel][] = $row2->type;
							$station_coord[$route_sel][] = $row2->station_coord;
							$distance_variable[$route_sel][] = $row2->distance_variable;	
						}
						$prev_route = $route_sel;
						//#### CUSTOMER CLOSED
					}
					//######## IF ROUTE MATCHED CLOSED				
				}
			}
			$count++;
		}
		
		//echo "\nCount=".$count;
		fclose($handle);
	}
}

/*function read_all_routes($account_id,$shift_time)
{
	global $total_route;
	global $total_customer;

	//##### READ MAIN MASTER FILE
	if($shift_time=="ZPMM")
	{
		//$path = "D:\\test_app/gps_report/".$account_id."/master/morning_plant_customer#1#8.csv";
		$path = "/var/www/html/vts/beta/src/php/hourly_report/delhi/gps_report/".$account_id."/master/morning_plant_customer#1#8.csv";
	}
	else if($shift_time=="ZPME")
	{
		//$path = "D:\\test_app/gps_report/".$account_id."/master/evening_plant_customer#1#7.csv";
		$path = "/var/www/html/vts/beta/src/php/hourly_report/delhi/gps_report/".$account_id."/master/evening_plant_customer#1#7.csv";
	}
	
	//echo "\npath=".$path;
	if (($handle = fopen($path, "r")) !== FALSE)
	{
		//echo "\nInside File Read";
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
		{
			$row++;
			$num = count($data);
			//echo "\nData Read ".$num." ".$row;
			if(($num<4) || ($row==1))
			{
				continue;
			}
			//$route_match=false;
			
			//echo "\nField Match ".sizeof($route_name_rdb);			
			//######## GET ALL ROUTES
			//if($data[3]=="206013") echo "Found1::206013";
						
			$total_customer[] = $data[0];
			$total_route[] = $data[3];
		}
		fclose($handle);
	}
}*/
?>  
